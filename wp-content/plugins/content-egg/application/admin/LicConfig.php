<?php

namespace ContentEgg\application\admin;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\Config;
use ContentEgg\application\Plugin;
use ContentEgg\application\licensing\LicenseGate;

use function ContentEgg\prnx;;

/**
 * LicConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class LicConfig extends Config
{

	public function page_slug()
	{
		if ($this->option('license_key') || \get_option(Plugin::slug . '_env_install'))
		{
			return Plugin::slug . '-lic';
		}
		else
		{
			return Plugin::slug;
		}
	}

	public function option_name()
	{
		return Plugin::slug . '_lic';
	}

	public function add_admin_menu()
	{
		$this->resetLicense();

		if (Plugin::isBlocked())
			return;

		\add_submenu_page(Plugin::slug, __('License', 'content-egg') . ' &lsaquo; Content Egg', __('License', 'content-egg'), 'manage_options', $this->page_slug(), array(
			$this,
			'settings_page'
		));
	}

	protected function options()
	{
		return array(
			'license_key' => array(
				'title'       => __('License key', 'content-egg'),
				'description' => __('Please enter your license key.', 'content-egg') . ' ' . sprintf(__('You can find your key on the %s page.', 'content-egg'), '<a href="' . \esc_url(Plugin::panelUri) . '" target="_blank">My Account</a>') . ' ' .
					sprintf(__("If you don't have one yet, you can buy it from our <a target='_blank' href='%s'>official website</a>.", 'content-egg'), Plugin::pluginSiteUrl()),
				'callback'    => array($this, 'render_license_key'),
				'default'     => '',
				'validator'   => array(
					'trim',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'required'),
						'message' => __('The "License key" can not be empty', 'content-egg'),
					),
					array(
						'call'    => array($this, 'licFormat'),
						'message' => __('Invalid license key.', 'content-egg'),
					),
					array(
						'call'    => array($this, 'activatingLicense'),
						'message' => sprintf(
							__(
								'The license key could not be validated. Please verify it is correct. If the issue continues, your server may be blocking connections to keywordrush.com — contact your host or %s.',
								'content-egg'
							),
							'<a href="https://www.keywordrush.com/contact" target="_blank" rel="noopener noreferrer">our support team</a>'
						),
					),
				),
				'section'     => 'default',
			),
		);
	}

	/**
	 * License-key input. Never renders the stored key into the DOM: the field is
	 * always empty (you only type here to enter a new key), and the current key
	 * is shown masked in the status card. A blank submit can't wipe the stored
	 * key — Config::validate() restores it when the "required" validator fails.
	 */
	public function render_license_key($args)
	{
		$has_key = (string) $args['value'] !== '';
		$placeholder = $has_key ? __('Enter a new license key', 'content-egg') : __('Paste your license key here', 'content-egg');

		echo '<input type="text" name="' . \esc_attr($args['option_name']) . '['
			. \esc_attr($args['name']) . ']" id="' . \esc_attr($args['label_for']) . '" value="" '
			. 'class="regular-text ltr" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" '
			. 'placeholder="' . \esc_attr($placeholder) . '" />';

		if (!empty($args['description']))
			echo '<p class="description">' . \wp_kses_post($args['description']) . '</p>';
	}

	public function settings_page()
	{
		PluginAdmin::render('lic_settings', array('page_slug' => $this->page_slug()));
	}

	public function licFormat($value)
	{
		if (preg_match('/[^0-9a-zA-Z_~\-]/', $value))
		{
			return false;
		}
		if (strlen($value) !== 32 && !preg_match('/^\w{8}-\w{4}-\w{4}-\w{4}-\w{12}$/', $value))
		{
			return false;
		}

		return true;
	}

	public function activatingLicense($value)
	{
		$res = (new LicenseGate())->activate((string) $value);

		if (!empty($res['ok']))
		{
			delete_option(Plugin::getShortSlug() . '_sys_status');
			delete_option(Plugin::getShortSlug() . '_sys_deadline');
			return true;
		}

		$message = isset($res['message']) ? (string) $res['message'] : '';
		if ($message !== '')
			add_settings_error('license_key', 'license_key', wp_kses_post($message));

		return false;
	}

	private function resetLicense()
	{
		if ($GLOBALS['pagenow'] != 'admin.php' || empty($_GET['page']) || $_GET['page'] != 'content-egg-lic')
		{
			return;
		}

		if (isset($_POST['cmd']) && $_POST['cmd'] == 'lic_reset')
		{
			if (!\current_user_can('delete_plugins') || empty($_POST['nonce_reset']) || !\wp_verify_nonce(sanitize_key($_POST['nonce_reset']), 'license_reset'))
			{
				\wp_die('You don\'t have access to this page.');
			}

			if (Plugin::isEnvato())
			{
				$redirect_url = \get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-lic');
			}
			else
			{
				$redirect_url = \get_admin_url(\get_current_blog_id(), 'plugins.php');
			}

			$res = (new LicenseGate())->deactivate();

			\delete_option(LicConfig::getInstance()->option_name());

			if (!empty($res['ok']))
				$redirect_url = AdminNotice::add2Url($redirect_url, 'license_reset_success', 'warning');
			else
				$redirect_url = AdminNotice::add2Url($redirect_url, 'license_reset_error', 'error');

			\wp_safe_redirect($redirect_url);
			exit;
		}
	}

}
