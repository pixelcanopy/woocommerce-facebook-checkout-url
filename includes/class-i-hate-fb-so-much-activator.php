<?php

/**
 * Fired during plugin activation
 *
 * @link       https://pixelcanopy.com
 * @since      1.0.0
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 * @author     Pixel Canopy <info@pixelcanopy.com>
 */
class I_Hate_Fb_So_Much_Activator {

  /**
   * Activate the plugin.
   *
   * Set up rewrite rules for Facebook checkout endpoint and flush rewrite rules.
   *
   * @since    1.0.0
   */
  public static function activate() {
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
      // Deactivate the plugin
      deactivate_plugins( plugin_basename( __FILE__ ) );
      
      // Show error message
      wp_die(
        __( 'This plugin requires WooCommerce to be installed and activated. Please install WooCommerce first, then activate this plugin.', 'i-hate-fb-so-much' ),
        __( 'WooCommerce Required', 'i-hate-fb-so-much' ),
        array( 'back_link' => true )
      );
    }

    // Add rewrite rule for Facebook checkout endpoint
    add_rewrite_rule(
      '^facebook-checkout/?$',
      'index.php?facebook_checkout=1',
      'top'
    );

    // Set default options
    if ( get_option( 'i_hate_fb_so_much_debug_mode' ) === false ) {
      add_option( 'i_hate_fb_so_much_debug_mode', false );
    }

    if ( get_option( 'i_hate_fb_so_much_clear_cart_always' ) === false ) {
      add_option( 'i_hate_fb_so_much_clear_cart_always', true );
    }

    // Flush rewrite rules to ensure our new rule is registered
    flush_rewrite_rules();

    // Log activation for debugging
    if ( get_option( 'i_hate_fb_so_much_debug_mode', false ) ) {
      error_log( 'FB Checkout URL Handler plugin activated successfully' );
    }

  }

}
