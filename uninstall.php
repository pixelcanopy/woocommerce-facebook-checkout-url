<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://pixelcanopy.com
 * @since      1.0.0
 *
 * @package    I_Hate_Fb_So_Much
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

/**
 * Clean up all plugin data when uninstalling
 */
function i_hate_fb_so_much_uninstall() {
  
  // Delete plugin options
  delete_option( 'i_hate_fb_so_much_debug_mode' );
  delete_option( 'i_hate_fb_so_much_clear_cart_always' );

  // For multisite installations
  if ( is_multisite() ) {
    $blogs = get_sites();
    foreach ( $blogs as $blog ) {
      switch_to_blog( $blog->blog_id );
      
      // Delete options for each site
      delete_option( 'i_hate_fb_so_much_debug_mode' );
      delete_option( 'i_hate_fb_so_much_clear_cart_always' );
      
      restore_current_blog();
    }
  }

  // Clear any cached data
  wp_cache_flush();

  // Log uninstall for debugging (if debug was enabled)
  error_log( 'FB Checkout URL Handler plugin completely uninstalled and cleaned up' );
}

// Run the uninstall function
i_hate_fb_so_much_uninstall();
