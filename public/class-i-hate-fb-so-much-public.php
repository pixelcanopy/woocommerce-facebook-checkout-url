<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://pixelcanopy.com
 * @since      1.0.0
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/public
 * @author     Pixel Canopy <contact@pixelcanopy.com>
 */
class I_Hate_Fb_So_Much_Public {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * The Facebook checkout endpoint slug.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $endpoint_slug    The slug for Facebook checkout endpoint.
   */
  private $endpoint_slug;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   * @param      string    $endpoint_slug    The Facebook checkout endpoint slug.
   */
  public function __construct( $plugin_name, $version, $endpoint_slug ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->endpoint_slug = $endpoint_slug;
  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in I_Hate_Fb_So_Much_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The I_Hate_Fb_So_Much_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/i-hate-fb-so-much-public.css', array(), $this->version, 'all' );

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in I_Hate_Fb_So_Much_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The I_Hate_Fb_So_Much_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/i-hate-fb-so-much-public.js', array( 'jquery' ), $this->version, false );

  }

  /**
   * Add custom query variables
   *
   * @since    1.0.0
   * @param    array    $vars    Existing query vars
   * @return   array             Modified query vars
   */
  public function add_query_vars( $vars ) {
    $vars[] = 'facebook_checkout';
    return $vars;
  }

  /**
   * Handle Facebook checkout requests
   *
   * @since    1.0.0
   */
  public function handle_facebook_checkout() {
    if ( get_query_var( 'facebook_checkout' ) ) {
      $this->process_facebook_checkout();
      exit;
    }
  }

  /**
   * Process the Facebook checkout URL parameters
   *
   * @since    1.0.0
   */
  private function process_facebook_checkout() {
    try {
      // Check if WooCommerce is available
      if ( ! class_exists( 'WooCommerce' ) ) {
        wp_redirect( home_url( '/?facebook_checkout_error=wc_missing' ) );
        exit;
      }

      // Clear existing cart if setting is enabled (default: true)
      $clear_cart = get_option( 'i_hate_fb_so_much_clear_cart_always', true );
      if ( $clear_cart ) {
        WC()->cart->empty_cart();
      }

      // Log the request for debugging if enabled
      if ( get_option( 'i_hate_fb_so_much_debug_mode', false ) ) {
        error_log( 'Facebook Checkout Request: ' . print_r( $_GET, true ) );
      }

      $errors = array();
      $success_count = 0;

      // Process products parameter
      if ( isset( $_GET['products'] ) && ! empty( $_GET['products'] ) ) {
        $products_param = sanitize_text_field( $_GET['products'] );
        
        // Security: Add length limit to prevent abuse
        if ( strlen( $products_param ) > 2000 ) {
          $errors[] = __( 'Products parameter is too long', 'i-hate-fb-so-much' );
        } else {
          $products_param = urldecode( $products_param );
          $this->add_products_to_cart( $products_param, $errors, $success_count );
        }
      }

      // Process coupon parameter
      if ( isset( $_GET['coupon'] ) && ! empty( $_GET['coupon'] ) ) {
        $coupon_code = sanitize_text_field( $_GET['coupon'] );
        
        // Security: Add length limit for coupon codes
        if ( strlen( $coupon_code ) > 50 ) {
          $errors[] = __( 'Coupon code is too long', 'i-hate-fb-so-much' );
        } else {
          $this->apply_coupon( $coupon_code, $errors );
        }
      }

      // Store any errors/messages in session for display
      if ( ! empty( $errors ) ) {
        WC()->session->set( 'facebook_checkout_errors', $errors );
      }

      if ( $success_count > 0 ) {
        WC()->session->set( 'facebook_checkout_success', sprintf(
          /* translators: %d: number of products */
          _n( '%d product added to cart from Facebook', '%d products added to cart from Facebook', $success_count, 'i-hate-fb-so-much' ),
          $success_count
        ) );
      }

      // Redirect based on admin setting (cart or checkout)
      $redirect_to = get_option( 'i_hate_fb_so_much_redirect_to', 'cart' );
      
      if ( $redirect_to === 'checkout' ) {
        wp_redirect( wc_get_checkout_url() );
      } else {
        wp_redirect( wc_get_cart_url() );
      }
      exit;

    } catch ( Exception $e ) {
      error_log( 'Facebook Checkout Error: ' . $e->getMessage() );
      wp_redirect( home_url( '/?facebook_checkout_error=general' ) );
      exit;
    }
  }

  /**
   * Add products to cart from Facebook products parameter
   *
   * @since    1.0.0
   * @param    string    $products_param    The products parameter from URL
   * @param    array     $errors           Reference to errors array
   * @param    int       $success_count    Reference to success counter
   */
  private function add_products_to_cart( $products_param, &$errors, &$success_count ) {
    // Security: Additional validation on the products parameter
    if ( ! preg_match( '/^[\w:,%-]+$/', $products_param ) ) {
      $errors[] = __( 'Invalid characters in products parameter', 'i-hate-fb-so-much' );
      return;
    }
    
    // Parse the products parameter: "id1:qty1,id2:qty2,id3:qty3"
    // Note: Facebook escapes commas as %2C and colons as %3A in the URL
    $products_param = str_replace( array( '%2C', '%3A' ), array( ',', ':' ), $products_param );
    $product_items = explode( ',', $products_param );
    
    // Security: Limit number of products to prevent abuse
    if ( count( $product_items ) > 50 ) {
      $errors[] = __( 'Too many products in request', 'i-hate-fb-so-much' );
      return;
    }

    foreach ( $product_items as $item ) {
      $item = trim( $item );
      if ( empty( $item ) ) {
        continue;
      }

      $parts = explode( ':', $item );
      if ( count( $parts ) !== 2 ) {
        $errors[] = sprintf(
          /* translators: %s: invalid product format */
          __( 'Invalid product format: %s', 'i-hate-fb-so-much' ), 
          esc_html( $item )
        );
        continue;
      }

      $product_id = sanitize_text_field( $parts[0] );
      $quantity = intval( $parts[1] );
      
      // Security: Additional validation
      if ( empty( $product_id ) || ! preg_match( '/^[\w-]+$/', $product_id ) ) {
        $errors[] = sprintf(
          /* translators: %s: product ID */
          __( 'Invalid product ID: %s', 'i-hate-fb-so-much' ), 
          esc_html( $product_id )
        );
        continue;
      }

      if ( $quantity <= 0 || $quantity > 999 ) {
        $errors[] = sprintf(
          /* translators: %s: product ID */
          __( 'Invalid quantity for product %s', 'i-hate-fb-so-much' ), 
          esc_html( $product_id )
        );
        continue;
      }

      // Try to find the product
      $product = $this->get_product_by_id( $product_id );

      if ( ! $product ) {
        $errors[] = sprintf(
          /* translators: %s: product ID */
          __( 'Product not found: %s', 'i-hate-fb-so-much' ), 
          esc_html( $product_id )
        );
        continue;
      }

      // Check if product is purchasable
      if ( ! $product->is_purchasable() ) {
        $errors[] = sprintf(
          /* translators: %s: product name */
          __( 'Product is not available for purchase: %s', 'i-hate-fb-so-much' ), 
          esc_html( $product->get_name() )
        );
        continue;
      }

      // Check stock
      if ( ! $product->has_enough_stock( $quantity ) ) {
        $errors[] = sprintf(
          /* translators: %s: product name */
          __( 'Not enough stock for product: %s', 'i-hate-fb-so-much' ), 
          esc_html( $product->get_name() )
        );
        continue;
      }

      // Add to cart
      $cart_item_key = WC()->cart->add_to_cart( $product->get_id(), $quantity );

      if ( $cart_item_key ) {
        $success_count++;
      } else {
        $errors[] = sprintf(
          /* translators: %s: product name */
          __( 'Failed to add product to cart: %s', 'i-hate-fb-so-much' ), 
          esc_html( $product->get_name() )
        );
      }
    }
  }

  /**
   * Get product by ID (supports both product ID and SKU)
   *
   * @since    1.0.0
   * @param    string    $product_id    The product identifier
   * @return   WC_Product|false         The product object or false if not found
   */
  private function get_product_by_id( $product_id ) {
    // First try by product ID
    $product = wc_get_product( $product_id );
    if ( $product ) {
      return $product;
    }

    // Try by SKU
    $product_id_by_sku = wc_get_product_id_by_sku( $product_id );
    if ( $product_id_by_sku ) {
      return wc_get_product( $product_id_by_sku );
    }

    // Try custom meta query (in case you store Facebook product IDs in custom fields)
    $posts = get_posts( array(
      'post_type' => 'product',
      'meta_query' => array(
        array(
          'key' => 'facebook_content_id',
          'value' => $product_id,
          'compare' => '='
        )
      ),
      'numberposts' => 1
    ) );

    if ( ! empty( $posts ) ) {
      return wc_get_product( $posts[0]->ID );
    }

    return false;
  }

  /**
   * Apply coupon to cart
   *
   * @since    1.0.0
   * @param    string    $coupon_code    The coupon code to apply
   * @param    array     $errors        Reference to errors array
   */
  private function apply_coupon( $coupon_code, &$errors ) {
    if ( ! WC()->cart->has_discount( $coupon_code ) ) {
      $result = WC()->cart->apply_coupon( $coupon_code );
      if ( ! $result ) {
        $errors[] = sprintf(
          /* translators: %s: coupon code */
          __( 'Invalid or expired coupon code: %s', 'i-hate-fb-so-much' ), 
          $coupon_code 
        );
      }
    }
  }

  /**
   * Add notices for checkout errors/success
   *
   * @since    1.0.0
   */
  public function add_checkout_notices() {
    if ( is_checkout() ) {
      $errors = WC()->session->get( 'facebook_checkout_errors' );
      $success = WC()->session->get( 'facebook_checkout_success' );

      if ( $errors ) {
        foreach ( $errors as $error ) {
          wc_add_notice( $error, 'error' );
        }
        WC()->session->__unset( 'facebook_checkout_errors' );
      }

      if ( $success ) {
        wc_add_notice( $success, 'success' );
        WC()->session->__unset( 'facebook_checkout_success' );
      }
    }
  }

  /**
   * Handle general Facebook checkout errors
   *
   * @since    1.0.0
   */
  public function handle_checkout_errors() {
    if ( isset( $_GET['facebook_checkout_error'] ) ) {
      $error_type = sanitize_text_field( $_GET['facebook_checkout_error'] );
      
      switch ( $error_type ) {
        case 'wc_missing':
          $message = __( 'WooCommerce is required for Facebook checkout to work.', 'i-hate-fb-so-much' );
          break;
        case 'general':
        default:
          $message = __( 'There was an error processing your Facebook checkout. Please try again or contact support.', 'i-hate-fb-so-much' );
          break;
      }
      
      wc_add_notice( $message, 'error' );
      wp_redirect( home_url() );
      exit;
    }
  }

}
