<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://pixelcanopy.com
 * @since      1.0.0
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 * @author     Pixel Canopy <info@pixelcanopy.com>
 */
class I_Hate_Fb_So_Much_Deactivator {

  /**
   * Deactivate the plugin.
   *
   * Clean up rewrite rules and flush them.
   *
   * @since    1.0.0
   */
  public static function deactivate() {
    
    // Flush rewrite rules to remove our custom endpoint
    flush_rewrite_rules();

    // Log deactivation for debugging
    if ( get_option( 'i_hate_fb_so_much_debug_mode', false ) ) {
      error_log( 'FB Checkout URL Handler plugin deactivated' );
    }

    // Note: We don't delete options on deactivation in case user reactivates
    // Options are only deleted in uninstall.php if user completely removes plugin

  }

}
