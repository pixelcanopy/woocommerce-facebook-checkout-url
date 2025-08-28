<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://pixelcanopy.com
 * @since             1.0.0
 * @package           I_Hate_Fb_So_Much
 *
 * @wordpress-plugin
 * Plugin Name:       FB Checkout URL Handler for Woocommerce
 * Plugin URI:        https://github.com/pixelcanopy/woocommerce-facebook-checkout-url/tree/main
 * Description:       This is a simple plugin that handles checkout url's from Facebooks SUPER AWESOME SHINY AND NEW MIDDLE FINGER TO ALL WOOCOMMERCE SHOP OWNERS!!! (aka, the new checkout url stuff). See the settings page for installation details.
 * Version:           1.0.0
 * Author:            Pixel Canopy
 * Author URI:        https://pixelcanopy.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       i-hate-fb-so-much
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * WC requires at least: 3.0
 * WC tested up to: 8.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'I_HATE_FB_SO_MUCH_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-i-hate-fb-so-much-activator.php
 */
function activate_i_hate_fb_so_much() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-i-hate-fb-so-much-activator.php';
	I_Hate_Fb_So_Much_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-i-hate-fb-so-much-deactivator.php
 */
function deactivate_i_hate_fb_so_much() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-i-hate-fb-so-much-deactivator.php';
	I_Hate_Fb_So_Much_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_i_hate_fb_so_much' );
register_deactivation_hook( __FILE__, 'deactivate_i_hate_fb_so_much' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-i-hate-fb-so-much.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_i_hate_fb_so_much() {

	$plugin = new I_Hate_Fb_So_Much();
	$plugin->run();

}
run_i_hate_fb_so_much();
