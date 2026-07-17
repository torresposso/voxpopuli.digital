<?php
/**
 * Plugin Name: Bunyad AMP 
 * Description: Add AMP support to your WordPress site. This is modified version.
 * Plugin URI: https://amp-wp.org
 * Author: ThemeSphere, AMP Project Contributors
 * Author URI: https://github.com/ampproject/amp-wp/graphs/contributors
 * Version: 2.5.2
 * License: GPLv2 or later
 * Requires at least: 5.3
 * Requires PHP: 7.4
 *
 * @package AMP
 */

function bunyad_amp_conflict_notice() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e('Bunyad AMP for WordPress conflicts with the plugin AMP. Please disable plugin named "AMP".', 'amp'); ?></p>
	</div>
	<?php
}

if (function_exists('amp_init')) {
	add_action('admin_notices', 'bunyad_amp_conflict_notice');
	return;
}

/**
 * Setup default options for the plugin
 */
function bunyad_amp_setup_default_options() {

	$options = get_option('amp-options');
	if (!$options || !array_key_exists('user_modified', $options)) {
		$options['all_templates_supported'] = 0;
		$options['supported_post_types'] = array(
			'post', 'page'
		);
		$options['mobile_redirect'] = 0;
	}

	$options['plugin_configured'] = true;
	update_option('amp-options', (array) $options);
}

// Setup default options on a plugin update as the older versions didn't have this.
add_action('upgrader_process_complete', function($object, $data = array()) {

	// Only for plugin updates
	if ($data['type'] !== 'plugin') {
		return;
	}

	if (!isset($data['plugins']) && !isset($data['plugin'])) {
		return;
	}

	// Bulk has it in 'plugins'
	$data['plugins'] = !empty($data['bulk']) ? $data['plugins'] : (array) $data['plugin'];

	$bunyad_amp = plugin_basename(__FILE__);
	if (!in_array($bunyad_amp, $data['plugins'])) {
		return;
	}

	bunyad_amp_setup_default_options();

}, 10, 2);

// Configure defaults on activatation.
register_activation_hook(__FILE__, 'bunyad_amp_setup_default_options');

/**
 * Hook into options update to set a user updated flag.
 * 
 * This is needed to prevent overriding user settings on an upgrade or 
 * reactivation of the plugin.
 */
add_filter('pre_update_option_amp-options', function($options) {

	$option_mod = false;
	if (defined('REST_REQUEST') && REST_REQUEST) {
		$route = $GLOBALS['wp']->query_vars['rest_route'];

		if ($route && preg_match('#amp/(.+?)/options#', $route)) {
			$option_mod = true;
		}
	}

	if (!$option_mod) {
		return $options;
	}

	// Set flag.
	$options['user_modified'] = true;
	return $options;
});

// This is used to recognize Bunyad AMP is active.
defined('BUNYAD_AMP') || define('BUNYAD_AMP', 1);

// The plugin file
require_once dirname(__FILE__) . '/amp.php';