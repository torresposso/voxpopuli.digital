<?php
/**
 * Debloat Plugin.
 * 
 * @package           Sphere\Debloat
 *
 * Plugin Name:       Debloat
 * Description:       Remove Unused CSS, Optimize CSS, Optimize JS and speed up your site.
 * Version:           1.3.0
 * Author:            asadkn
 * Author URI:        https://profiles.wordpress.org/asadkn/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       debloat
 * Domain Path:       /languages
 * Requires PHP:      7.4.1
 */

defined('WPINC') || exit;

define('DEBLOAT_PLUGIN_FILE', __FILE__);
require_once plugin_dir_path(__FILE__) . 'bootstrap.php';