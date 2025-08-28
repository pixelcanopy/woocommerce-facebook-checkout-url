<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://pixelcanopy.com
 * @since      1.0.0
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/includes
 * @author     Pixel Canopy <contact@pixelcanopy.com>
 */
class I_Hate_Fb_So_Much {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      I_Hate_Fb_So_Much_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {
    if ( defined( 'I_HATE_FB_SO_MUCH_VERSION' ) ) {
      $this->version = I_HATE_FB_SO_MUCH_VERSION;
    } else {
      $this->version = '1.0.0';
    }
    $this->plugin_name = 'i-hate-fb-so-much';
    $this->endpoint_slug = 'facebook-checkout';

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
    $this->define_facebook_checkout_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - I_Hate_Fb_So_Much_Loader. Orchestrates the hooks of the plugin.
   * - I_Hate_Fb_So_Much_i18n. Defines internationalization functionality.
   * - I_Hate_Fb_So_Much_Admin. Defines all hooks for the admin area.
   * - I_Hate_Fb_So_Much_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i-hate-fb-so-much-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i-hate-fb-so-much-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-i-hate-fb-so-much-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-i-hate-fb-so-much-public.php';

    $this->loader = new I_Hate_Fb_So_Much_Loader();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the I_Hate_Fb_So_Much_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {

    $plugin_i18n = new I_Hate_Fb_So_Much_i18n();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {
    $plugin_admin = new I_Hate_Fb_So_Much_Admin( $this->get_plugin_name(), $this->get_version() );

    // Existing admin hooks
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
    $this->loader->add_action( 'admin_init', $plugin_admin, 'settings_init' );

    // NEW: Facebook Content ID custom field hooks (ADMIN ONLY)
    $this->loader->add_action( 'woocommerce_product_options_general_product_data', $plugin_admin, 'add_facebook_content_id_field' );
    $this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'save_facebook_content_id_field' );
    $this->loader->add_action( 'woocommerce_product_quick_edit_end', $plugin_admin, 'add_facebook_content_id_quick_edit' );
    $this->loader->add_action( 'woocommerce_product_quick_edit_save', $plugin_admin, 'save_facebook_content_id_quick_edit' );
    $this->loader->add_action( 'wp_ajax_get_facebook_content_id', $plugin_admin, 'ajax_get_facebook_content_id' );
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {

    $plugin_public = new I_Hate_Fb_So_Much_Public( $this->get_plugin_name(), $this->get_version(), $this->endpoint_slug );

    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

  }

  /**
   * Register all of the hooks related to Facebook checkout functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_facebook_checkout_hooks() {
    $plugin_public = new I_Hate_Fb_So_Much_Public( $this->get_plugin_name(), $this->get_version(), $this->endpoint_slug );

    // Initialize rewrite rules and query vars
    $this->loader->add_filter( 'query_vars', $plugin_public, 'add_query_vars' );
    
    // Handle the Facebook checkout request
    $this->loader->add_action( 'wp', $plugin_public, 'handle_facebook_checkout' );
    
    // Add checkout notices
    $this->loader->add_action( 'wp_head', $plugin_public, 'add_checkout_notices' );
    $this->loader->add_action( 'wp', $plugin_public, 'handle_checkout_errors' );
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    I_Hate_Fb_So_Much_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

  /**
   * Retrieve the Facebook checkout endpoint slug.
   *
   * @since     1.0.0
   * @return    string    The endpoint slug.
   */
  public function get_endpoint_slug() {
    return $this->endpoint_slug;
  }
}
