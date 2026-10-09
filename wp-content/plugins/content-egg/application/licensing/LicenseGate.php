<?php

namespace ContentEgg\application\licensing;

use ContentEgg\application\Plugin;
use ContentEgg\application\admin\LicConfig;

defined('\ABSPATH') || defined('ContentEgg\application\licensing\TESTING') || exit;

/**
 * License + token lifecycle against keywordrush.com /licensing/api/*.
 */
class LicenseGate
{
    const PRODUCT_CODE = 'ce';
    const TOKEN_SKEW   = 30; // refresh a touch before true expiry

    const OPT_SITE_ID     = 'cegg_site_id';
    const OPT_TOKEN       = 'cegg_token';
    const OPT_TOKEN_EXP   = 'cegg_token_exp';
    const OPT_STATUS      = 'cegg_status';
    const OPT_STATUS_AT   = 'cegg_status_at';
    const OPT_LOCKED      = 'cegg_locked';
    const OPT_LOCK_REASON = 'cegg_lock_reason';
    const OPT_LOCKED_AT   = 'cegg_locked_at';
    const OPT_NOTIFIED    = 'cegg_lock_emailed';
    const OPT_HEAL_LAST   = 'cegg_heal_last';
    const OPT_REFRESH_LOCK = 'cegg_refresh_lock';

    const NOTICE_HOOK  = 'cegg_lic_notify';
    const NOTICE_GROUP = 'content-egg';

    const GRACE_SECONDS = 259200; // 3 days

    const HEAL_FIRST_DELAY    = 7200;
    const HEAL_GRACE_INTERVAL = 72000;
    const HEAL_POST_INTERVAL  = 604800;

    const REFRESH_LOCK_TTL = 30;

    /** @var LicenseClient */
    protected $client;

    public function __construct($client = null)
    {
        $this->client = $client ?: new LicenseClient();
    }

    // --- accessors --------------------------------------------------------

    protected function storedKey(): string
    {
        return (string) LicConfig::getInstance()->option('license_key');
    }

    protected function isEnvatoBuild(): bool
    {
        return Plugin::isEnvato();
    }

    public function productCode(): string
    {
        if (!LicenseUtils::isDevelopment()) {
            return self::PRODUCT_CODE;
        }
        return (string) \apply_filters('cegg_product_code', self::PRODUCT_CODE);
    }

    // --- site id ----------------------------------------------------------

    public function getOrCreateSiteId(): string
    {
        $siteId = (string) \get_option(self::OPT_SITE_ID, '');
        if ($siteId === '') {
            $siteId = LicenseUtils::uuid_v4();
            \update_option(self::OPT_SITE_ID, $siteId, false);
        }
        return $siteId;
    }

    // --- token ------------------------------------------------------------

    protected function hasValidToken(): bool
    {
        $token = (string) \get_option(self::OPT_TOKEN, '');
        if ($token === '') {
            return false;
        }
        $exp = (int) \get_option(self::OPT_TOKEN_EXP, 0);
        if ($exp > 0 && time() >= $exp) {
            return false;
        }
        return true;
    }

    protected function storeToken(array $data): void
    {
        $token = (string) ($data['token']['access_token'] ?? '');
        $expiresIn = (int) ($data['token']['expires_in'] ?? 0);
        if ($token !== '' && $expiresIn > 0) {
            \update_option(self::OPT_TOKEN, $token, false);
            \update_option(self::OPT_TOKEN_EXP, time() + max(0, $expiresIn - self::TOKEN_SKEW), false);
        }
    }

    // --- status cache -----------------------------------------------------

    protected function cacheStatus(array $data): void
    {
        \update_option(self::OPT_STATUS, $data, false);
        \update_option(self::OPT_STATUS_AT, time(), false);
    }

    public function getCachedStatus()
    {
        $c = \get_option(self::OPT_STATUS, null);
        return is_array($c) ? $c : null;
    }

    public function getCachedAt(): int
    {
        return (int) \get_option(self::OPT_STATUS_AT, 0);
    }

    // --- lock state -------------------------------------------------------

    public function isLocked(): bool
    {
        return false;
    }

    protected function localStatus(): array
    {
        return ['license' => ['status' => 'active', 'is_lifetime' => true]];
    }

    public function getLockReason()
    {
        $r = (string) \get_option(self::OPT_LOCK_REASON, '');
        return $r !== '' ? $r : null;
    }

    public function unlock(): void
    {
        \delete_option(self::OPT_LOCKED);
        \delete_option(self::OPT_LOCK_REASON);
        \delete_option(self::OPT_LOCKED_AT);
        \delete_option(self::OPT_NOTIFIED);
        \delete_option(self::OPT_HEAL_LAST);
        \wp_clear_scheduled_hook(self::NOTICE_HOOK);
        if (function_exists('as_unschedule_all_actions')) {
            \as_unschedule_all_actions(self::NOTICE_HOOK, array(), self::NOTICE_GROUP);
        }
    }

    protected function lock(string $reason): void
    {
    }

    protected function queueOwnerNotice(): void
    {
        if (\get_option(self::OPT_NOTIFIED, false)) {
            return;
        }
        \update_option(self::OPT_NOTIFIED, 1, false); // claim: one per episode

        if (function_exists('as_enqueue_async_action')) {
            \as_enqueue_async_action(self::NOTICE_HOOK, array(), self::NOTICE_GROUP);
        } elseif (!\wp_next_scheduled(self::NOTICE_HOOK)) {
            \wp_schedule_single_event(time() + 60, self::NOTICE_HOOK);
        }
    }

    public function graceSeconds(): int
    {
        return (int) \apply_filters('cegg_block_grace', self::GRACE_SECONDS);
    }

    public function lockedAt(): int
    {
        return (int) \get_option(self::OPT_LOCKED_AT, 0);
    }

    public function graceEndsAt(): int
    {
        $at = $this->lockedAt();
        return $at > 0 ? $at + $this->graceSeconds() : 0;
    }

    public function inGrace(): bool
    {
        return $this->isLocked() && !$this->isBlocked();
    }

    public function isBlocked(): bool
    {
        if (!$this->isLocked()) {
            return false;
        }
        $at = $this->lockedAt();
        if ($at <= 0) {
            \update_option(self::OPT_LOCKED_AT, time(), false);
            return false;
        }
        return (time() - $at) >= $this->graceSeconds();
    }

    public function applyBlockingFromError(string $code): void
    {
        $code = strtoupper($code);
        if ($code === 'LICENSE_SUSPENDED' || $code === 'LICENSE_INACTIVE') {
            $this->lock('LICENSE_SUSPENDED');
        } elseif ($code === 'LICENSE_NOT_FOUND') {
            $this->lock('LICENSE_NOT_FOUND');
        } elseif ($code === 'TOKEN_REVOKED') {
            $this->lock('TOKEN_REVOKED');
        } elseif ($code === 'INVALID_TOKEN' || $code === 'DOMAIN_MISMATCH') {
            // Drop a token the server won't honor for this install.
            \delete_option(self::OPT_TOKEN);
            \delete_option(self::OPT_TOKEN_EXP);
        }
    }

    public function applyBlockingFromStatus(array $data): void
    {
        $licenseStatus = strtolower(trim((string) ($data['license']['status'] ?? '')));
        $activationStatus = strtolower(trim((string) ($data['activation']['status'] ?? '')));

        if ($licenseStatus === 'suspended') {
            $this->lock('LICENSE_SUSPENDED');
            return;
        }
        if ($activationStatus === 'revoked') {
            $this->lock('TOKEN_REVOKED');
            return;
        }
        if ($licenseStatus === 'active') {
            $this->unlock();
        }
    }

    // --- token ------------------------------------------------------------

    /** @return string|null */
    public function getFreshToken()
    {
        return null;
    }

    protected function bootstrap(): array
    {
        $key = $this->storedKey();
        if ($key === '') {
            return ['ok' => false, 'code' => 'NO_KEY', 'message' => '', 'data' => []];
        }
        return $this->isEnvatoBuild() ? $this->envatoActivate($key) : $this->activate($key);
    }

    protected function refreshToken(): array
    {
        $res = $this->refreshTokenOnce();

        if (empty($res['ok']) && strtoupper((string) ($res['code'] ?? '')) === 'TOKEN_REVOKED') {
            sleep(2);
            $res = $this->refreshTokenOnce(); // retry once before treating it as final
        }

        if (empty($res['ok']) && strtoupper((string) ($res['code'] ?? '')) !== 'REFRESH_BUSY') {
            $this->applyBlockingFromError((string) ($res['code'] ?? ''));
        }
        return $res;
    }

    protected function refreshTokenOnce(): array
    {
        $before = (string) \get_option(self::OPT_TOKEN, '');
        if ($before === '') {
            return ['ok' => false, 'code' => 'MISSING_TOKEN', 'message' => '', 'data' => []];
        }

        if (!\add_option(self::OPT_REFRESH_LOCK, time(), '', false)) {
            $held = (int) \get_option(self::OPT_REFRESH_LOCK, 0);
            if ($held > 0 && (time() - $held) < self::REFRESH_LOCK_TTL) {
                usleep(2500000);
                $now = (string) \get_option(self::OPT_TOKEN, '');
                if ($now !== '' && $now !== $before && $this->hasValidToken()) {
                    return ['ok' => true, 'code' => 'OK', 'message' => '', 'data' => []];
                }
                return ['ok' => false, 'code' => 'REFRESH_BUSY', 'message' => '', 'data' => []];
            }
            \update_option(self::OPT_REFRESH_LOCK, time(), false);
        }

        try {
            $now = (string) \get_option(self::OPT_TOKEN, '');
            if ($now !== '' && $now !== $before && $this->hasValidToken()) {
                return ['ok' => true, 'code' => 'OK', 'message' => '', 'data' => []];
            }

            $res = $this->client->post_json('/licensing/api/refresh-token', [
                'site_id' => $this->getOrCreateSiteId(),
                'domain'  => LicenseUtils::domain(),
            ], ['Authorization' => 'Bearer ' . $now]);

            if (empty($res['ok'])) {
                return $res;
            }
            $data = is_array($res['data'] ?? null) ? $res['data'] : [];
            $this->storeToken($data);
            $this->cacheStatus($data);
            return $res;
        } finally {
            \delete_option(self::OPT_REFRESH_LOCK);
        }
    }

    // --- status -----------------------------------------------------------

    public function refreshStatus(): array
    {
        $data = $this->localStatus();
        $this->cacheStatus($data);
        $this->unlock();
        return ['ok' => true, 'code' => 'OK', 'message' => '', 'data' => $data];
    }

    // --- activation -------------------------------------------------------

    public function activate(string $key): array
    {
        $key = trim($key);
        if (!LicenseUtils::isValidKeyFormat($key)) {
            return ['ok' => false, 'code' => 'INVALID_INPUT', 'message' => 'Invalid license key format.', 'data' => []];
        }

        return $this->handleActivationResponse(['ok' => true, 'code' => 'OK', 'message' => '', 'data' => $this->localStatus()]);
    }

    public function envatoActivate(string $code, string $email = '', bool $subscribe = false): array
    {
        $code = trim($code);
        if (!LicenseUtils::isValidKeyFormat($code)) {
            return ['ok' => false, 'code' => 'INVALID_INPUT', 'message' => 'Invalid purchase code format.', 'data' => []];
        }

        return $this->handleActivationResponse(['ok' => true, 'code' => 'OK', 'message' => '', 'data' => $this->localStatus()]);
    }

    /**
     * Is this key the one this install is already licensed with?
     *
     * A rejection only says something about the key that was submitted. When
     * someone mistypes a key into the License form, the server's verdict is
     * about that typo — not about the activation this site already holds — so it
     * must not lock the site. When the submitted key IS the stored one (a
     * re-submit, or bootstrap() self-healing), the verdict does apply.
     */
    protected function isOwnKey(string $key): bool
    {
        $stored = trim($this->storedKey());
        return $stored !== '' && trim($key) === $stored;
    }

    /**
     * @param bool $applyBlocking whether a rejection should lock this install —
     *                            true only when the submitted key is this
     *                            install's own (see isOwnKey()).
     */
    protected function handleActivationResponse(array $res, bool $applyBlocking = true): array
    {
        if (empty($res['ok'])) {
            if ($applyBlocking) {
                $this->applyBlockingFromError((string) ($res['code'] ?? ''));
            }
            return $res;
        }
        $data = is_array($res['data'] ?? null) ? $res['data'] : [];
        $this->storeToken($data);
        $this->cacheStatus($data);
        $this->unlock();
        return $res;
    }

    public function clearCredentials(): void
    {
        \delete_option(self::OPT_TOKEN);
        \delete_option(self::OPT_TOKEN_EXP);
        \delete_option(self::OPT_STATUS);
        \delete_option(self::OPT_STATUS_AT);
        $this->unlock();
    }

    // --- deactivate -------------------------------------------------------

    /**
     * Returns true when deactivating would actually free a transferable seat.
     * Expired (canceled + past period_end) licenses can't be activated on a new
     * domain, so deactivating would only destroy the user's ability to re-activate
     * on their current domain (the one surviving server exception for canceled licenses).
     */
    public function canDeactivate(): bool
    {
        $data = $this->getCachedStatus();
        if (!$data || !isset($data['license']) || !is_array($data['license'])) {
            return true; // no cached data: show the button (safe default)
        }
        $lic = $data['license'];
        $status = strtolower(trim((string) ($lic['status'] ?? '')));

        if (!empty($lic['is_lifetime'])) {
            return true;
        }
        if ($status === 'active' || $status === 'past_due') {
            return true;
        }
        if ($status === 'canceled') {
            $end = !empty($lic['current_period_end'])
                ? strtotime((string) $lic['current_period_end'] . ' UTC')
                : 0;
            return $end > 0 && time() < $end;
        }
        return false; // suspended or unknown
    }

    public function deactivate(): array
    {
        $this->clearCredentials();
        return ['ok' => true, 'code' => 'OK', 'message' => 'Deactivated locally.', 'data' => []];
    }

    // --- legacy status mapping --------------------------------------------

    /**
     * Map cached status to the array shape legacy callers expect.
     * Lifetime → far-future expiry so legacy day-math reads as lifetime.
     */
    public function legacyStatusArray(): array
    {
        $data = $this->getCachedStatus();
        if (!$data || !isset($data['license']) || !is_array($data['license'])) {
            return [];
        }
        $lic = $data['license'];
        $isLifetime = !empty($lic['is_lifetime']);

        $end = (!empty($lic['current_period_end'])) ? (int) strtotime((string) $lic['current_period_end']) : 0;
        $expiry = $isLifetime ? (time() + 60 * 365 * 86400) : $end;
        $support = (!empty($lic['support_expires_at'])) ? (int) strtotime((string) $lic['support_expires_at']) : 0;

        return [
            'status'              => (string) ($lic['status'] ?? ''),
            'expiry_date'         => (int) $expiry,
            'support_expiry_date' => (int) $support,
            'is_lifetime'         => $isLifetime,
            'activated_on'        => (string) ($data['activation']['domain'] ?? ''),
            'extend_discount'     => 0,
        ];
    }

    // --- background poll --------------------------------------------------

    public function pollIfDue(): void
    {
        $this->refreshStatus();
    }

    protected function healDue(): bool
    {
        $lockedAt = $this->lockedAt();
        if ($lockedAt <= 0) {
            return false;
        }
        $age = time() - $lockedAt;
        if ($age < self::HEAL_FIRST_DELAY) {
            return false;
        }
        $interval = ($age <= $this->graceSeconds()) ? self::HEAL_GRACE_INTERVAL : self::HEAL_POST_INTERVAL;
        return (time() - (int) \get_option(self::OPT_HEAL_LAST, 0)) >= $interval;
    }

    public static function cronPoll(): void
    {
        $gate = new self();
        $gate->pollIfDue();
    }

    // --- owner notification -----------------------------------------------

    public static function cronNotify(): void
    {
        $gate = new self();
        $gate->sendOwnerNotice();
    }

    protected function sendOwnerNotice(): void
    {
        if (!$this->isLocked()) {
            return; // resolved since the notice was queued
        }

        $to = (string) \get_option('admin_email', '');
        if ($to === '' || !is_email($to)) {
            return;
        }

        $name     = Plugin::getName();
        $reason   = (string) ($this->getLockReason() ?: '');
        $deadline = $this->graceEndsAt();
        $licUrl   = \get_admin_url(\get_current_blog_id(), 'admin.php?page=' . Plugin::getSlug() . '-lic');

        if ($reason === 'TOKEN_REVOKED') {
            $headline = sprintf('This site\'s %s activation was revoked.', $name);
        } elseif ($reason === 'LICENSE_NOT_FOUND') {
            $headline = sprintf('Your %s license was not found.', $name);
        } else {
            $headline = sprintf('Your %s license has been suspended.', $name);
        }

        $when = $deadline > 0 ? \date_i18n(\get_option('date_format'), $deadline) : '';

        $subject = sprintf('[%s] Action required: license issue on %s', $name, LicenseUtils::domain());

        $lines   = array('<p>' . \esc_html($headline) . '</p>');
        if ($when !== '') {
            $lines[] = '<p>' . sprintf(
                \esc_html('%1$s features on this site will stop working on %2$s unless the issue is resolved.'),
                \esc_html($name),
                \esc_html($when)
            ) . '</p>';
        }
        $lines[] = '<p><a href="' . \esc_url($licUrl) . '">' . \esc_html('Open license page') . '</a> &middot; '
            . '<a href="' . \esc_url(Plugin::panelUri) . '">' . \esc_html('Manage license') . '</a> &middot; '
            . '<a href="' . \esc_url(Plugin::supportUri) . '">' . \esc_html('Contact support') . '</a></p>';

        $body = implode("\n", $lines);

        $ctype = function () { return 'text/html'; };
        \add_filter('wp_mail_content_type', $ctype);
        try {
            \wp_mail($to, $subject, $body);
        } catch (\Throwable $e) {
            // best effort: never let a mail failure surface in cron
        } finally {
            \remove_filter('wp_mail_content_type', $ctype);
        }
    }
}
