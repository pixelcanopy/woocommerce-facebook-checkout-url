<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://pixelcanopy.com
 * @since      1.0.0
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 * @author     Pixel Canopy <contact@pixelcanopy.com>
 */
class I_Hate_Fb_So_Much_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'i-hate-fb-so-much',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
