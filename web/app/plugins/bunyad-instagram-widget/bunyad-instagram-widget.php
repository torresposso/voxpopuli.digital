<?php
/**
 * Plugin Name: Bunyad Widget for Instagram
 * Description: For showing your latest Instagram photos.
 * Plugin URI: https://theme-sphere.com
 * Author: ThemeSphere
 * Author URI: https://theme-sphere.com
 * Version: 1.3.0
 * Requires PHP: 5.4
 * Text Domain: bunyad-instagram-widget
 * Domain Path: /assets/languages/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

$plugin_path = trailingslashit(plugin_dir_path(__FILE__));
require_once $plugin_path . 'inc/plugin.php';

$plugin = new Bunyad_Instgram_Plugin($plugin_path);

/**
 * An init is used to first check for conflicts.
 * 
 * Check for conflict at plugins_loaded (as WP Instagram Plugin may not be loaded before this point).
 */
add_action('plugins_loaded', function() {

	if (function_exists('wpiw_init') || class_exists('null_instagram_widget')) {
		add_action('admin_notices', function() {
			?>
			<div class="notice notice-error">
				<p><?php echo esc_html('Bunyad Widget for Instagram conflicts with the plugin "WP Instagram Widget". Please disable plugin named "WP Instagram Widget".'); ?></p>
			</div>
			<?php
		});

		return;
	}
});

// Clean up on deactivation.
register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);