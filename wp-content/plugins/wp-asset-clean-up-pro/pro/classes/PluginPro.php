<?php
namespace WpAssetCleanUpPro;

use WpAssetCleanUp\Misc;

/**
 * Class PluginPro
 * @package WpAssetCleanUpPro
 */
class PluginPro
{
	/**
	 * @var string
	 */
	public static $muPluginFileName = 'wpacu-plugins-filter.php';

	/**
	 * PluginPro constructor.
	 */
	public function __construct()
	{
		// Only trigger when a plugin page is accessed within the Dashboard
		if (is_admin() && isset($_GET['page']) && (strpos($_GET['page'], WPACU_PLUGIN_ID.'_') !== false)) {
			self::copyMuPluginFilter();
		}

		// e.g. Plugin update failed notice instructions
		add_action('admin_footer', array($this, 'adminFooter'));

		add_action( 'upgrader_process_complete', static function( $upgrader_object, $options ) {
			self::copyMuPluginFilter();
		}, 10, 2 );

		register_activation_hook(WPACU_PLUGIN_FILE, array($this, 'whenActivated'));
		register_deactivation_hook(WPACU_PLUGIN_FILE, array($this, 'whenDeactivated'));
	}

	/**
	 *
	 */
	public function init()
    {
	    if (is_admin() && strpos($_SERVER['REQUEST_URI'], 'update-core.php') !== false) {
		    add_action('admin_head', array($this, 'pluginIconUpdateCorePage'));
	    }
    }

	/**
	 *
	 */
	public static function copyMuPluginFilter()
	{
		// Isn't the MU plugin there? Copy it
		$copyFrom = dirname( WPACU_PLUGIN_FILE ) . '/pro/mu-plugins/to-copy/' . self::$muPluginFileName;
		$copyTo   = WPMU_PLUGIN_DIR . '/' . self::$muPluginFileName;

		if (! is_file(WPMU_PLUGIN_DIR . '/' . self::$muPluginFileName)) {
			// MU plugins directory has to be there first
			if (! is_dir( WPMU_PLUGIN_DIR )) {
				// Attempt directory creation
				$muPluginsCreateDir = ( @mkdir(WPMU_PLUGIN_DIR, 0755 ) && is_dir( WPMU_PLUGIN_DIR ) );

				if ( $muPluginsCreateDir ) {
					@copy( $copyFrom, $copyTo );
					return;
				}

				// The directory couldn't be created / The error will be shown from /classes/PluginsManager.php
				return;
			}

			// MU plugin directory was already created; copy the MU plugin
			@copy( $copyFrom, $copyTo );
		}
	}

	/**
	 * Replaces default plugin icon ('Dashicons' type) with the actual Asset CleanUp Pro icon
	 */
	public function pluginIconUpdateCorePage()
	{
		?>
		<style <?php echo Misc::getStyleTypeAttribute(); ?>>
            .wp-asset-clean-up-pro.plugin-title .dashicons.dashicons-admin-plugins {
                position: relative;
            }

            .wp-asset-clean-up-pro.plugin-title .dashicons.dashicons-admin-plugins::before {
                content: '';
                position: absolute;
                background: transparent url('https://ps.w.org/wp-asset-clean-up/assets/icon-256x256.png') no-repeat 0 0;
                height: 100%;
                left: 0;
                top: 0;
                width: 100%;
                background-size: cover;
                max-width: 60px;
                max-height: 60px;
                box-shadow: 0 0 0 0 transparent;
            }
		</style>

		<script type="text/javascript">
            jQuery(document).ready(function($) {
                /* Append the right class to the plugin row so the CSS above would take effect */
                $('input[value="wp-asset-clean-up-pro/wpacu.php"]').parent().next().addClass('wp-asset-clean-up-pro');
            });
		</script>
		<?php
	}

	/**
	 *
	 */
	public function adminFooter()
	{
		$isPluginsAdminPage = is_admin() && isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false);

		if ( ! $isPluginsAdminPage ) {
			return;
		}

		$wpUpdatesUrl = esc_url(admin_url( 'update-core.php' ));
		?>
		<span style="display: none;" id="wpacu-try-alt-plugin-update">&nbsp;&nbsp;Please try one of the following, depending on the error you got:<br/>
<span style="display: block; margin-bottom: 11px; margin-top: 11px;">&#10141; <strong>"Plugin update failed" error:</strong>&nbsp;Go to <a target="_blank" href="<?php echo esc_url($wpUpdatesUrl); ?>">"Dashboard" &#187; "Updates"</a>, tick the corresponding plugin checkbox and use the "Update Plugins" button. This will reload the page and there are higher chances the plugin will update, thus avoiding any timeout.</span>
<span style="display: block; margin-bottom: 10px;">&#10141; <strong>"Unauthorized" error: It is likely that you are trying to update the plugin for a website that is not active in the system, although it is marked as "active" on your end (e.g. you moved it from Staging to Live and it remained marked as active in the records). Please go to <a target="_blank" href="https://www.gabelivan.com/customer-dashboard/">Customer Dashboard</a> -&gt; Purchase History -&gt; View Licenses</strong> to manage the active websites or deactivate the license from the website you made the import from and re-activate it here, on the current website.</span>
</span>
		<?php
		$wpacuProDataPlugin = WPACU_PLUGIN_BASE;
		$wpacuProDataPluginBase = substr(strrchr(WPACU_PLUGIN_BASE, '/'), 1);
		?>
		<script type="text/javascript">
            jQuery(document).ready( function($) {
                $(document).ajaxComplete(function(event, xhr, settings) {
                    var $wpacuTryAltPluginUpdateElement = $('#wpacu-try-alt-plugin-update'),
                        $wpacuPluginUpdateFailedElement = $('tr.plugin-update-tr[data-plugin="<?php echo esc_js($wpacuProDataPlugin); ?>"]')
                            .find('.update-message.notice-error > p');

                    if ($wpacuPluginUpdateFailedElement.length > 0 && settings.url.indexOf('admin-ajax.php') !== -1
                        && xhr.responseText.indexOf('<?php echo esc_js($wpacuProDataPluginBase); ?>') !== -1
                        && xhr.responseText.indexOf('errorMessage') !== -1
                    ) {
                        setTimeout(function() {
                            $wpacuPluginUpdateFailedElement.append($wpacuTryAltPluginUpdateElement);
                            $wpacuTryAltPluginUpdateElement.show();
                        }, 100);
                    }
                });
            });
		</script>
		<?php
	}

	/**
	 * Copy/Update the MU plugin file
	 */
	public function whenActivated()
	{
		self::copyMuPluginFilter();
	}

	/**
	 * Remove the MU plugin file
	 */
	public function whenDeactivated()
	{
		@unlink(WPMU_PLUGIN_DIR.'/'.self::$muPluginFileName);
	}
}
