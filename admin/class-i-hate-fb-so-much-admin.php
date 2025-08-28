<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://pixelcanopy.com
 * @since      1.0.0
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    I_Hate_Fb_So_Much
 * @subpackage I_Hate_Fb_So_Much/admin
 * @author     Pixel Canopy <contact@pixelcanopy.com>
 */
class I_Hate_Fb_So_Much_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/i-hate-fb-so-much-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/i-hate-fb-so-much-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add admin menu for plugin settings
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'FB Checkout URL Handler Settings', 'i-hate-fb-so-much' ),
			__( 'FB Checkout Handler', 'i-hate-fb-so-much' ),
			'manage_options',
			'i-hate-fb-so-much-settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Initialize plugin settings
	 *
	 * @since    1.0.0
	 */
	public function settings_init() {
		// Register settings
		register_setting( 'i_hate_fb_so_much', 'i_hate_fb_so_much_debug_mode' );
		register_setting( 'i_hate_fb_so_much', 'i_hate_fb_so_much_clear_cart_always' );
		register_setting( 'i_hate_fb_so_much', 'i_hate_fb_so_much_redirect_to' );

		// Add settings section
		add_settings_section(
			'i_hate_fb_so_much_settings_section',
			__( 'Facebook Checkout Settings', 'i-hate-fb-so-much' ),
			array( $this, 'settings_section_callback' ),
			'i_hate_fb_so_much'
		);

		// Debug mode field
		add_settings_field(
			'i_hate_fb_so_much_debug_mode',
			__( 'Debug Mode', 'i-hate-fb-so-much' ),
			array( $this, 'debug_mode_callback' ),
			'i_hate_fb_so_much',
			'i_hate_fb_so_much_settings_section'
		);

		// Clear cart setting
		add_settings_field(
			'i_hate_fb_so_much_clear_cart_always',
			__( 'Always Clear Cart', 'i-hate-fb-so-much' ),
			array( $this, 'clear_cart_callback' ),
			'i_hate_fb_so_much',
			'i_hate_fb_so_much_settings_section'
		);

		// Redirect destination setting
		add_settings_field(
			'i_hate_fb_so_much_redirect_to',
			__( 'Redirect Destination', 'i-hate-fb-so-much' ),
			array( $this, 'redirect_to_callback' ),
			'i_hate_fb_so_much',
			'i_hate_fb_so_much_settings_section'
		);

		// Checkout URL display (read-only)
		add_settings_field(
			'i_hate_fb_so_much_checkout_url',
			__( 'Your Facebook Checkout URL', 'i-hate-fb-so-much' ),
			array( $this, 'checkout_url_callback' ),
			'i_hate_fb_so_much',
			'i_hate_fb_so_much_settings_section'
		);

		// Usage instructions
		add_settings_field(
			'i_hate_fb_so_much_instructions',
			__( 'Setup Instructions', 'i-hate-fb-so-much' ),
			array( $this, 'instructions_callback' ),
			'i_hate_fb_so_much',
			'i_hate_fb_so_much_settings_section'
		);
	}

	/**
	 * Display the plugin settings page
	 *
	 * @since    1.0.0
	 */
	public function settings_page() {
		// Check if WooCommerce is active
		$wc_active = class_exists( 'WooCommerce' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<?php if ( ! $wc_active ) : ?>
				<div class="notice notice-error">
					<p>
						<strong><?php _e( 'WooCommerce Required!', 'i-hate-fb-so-much' ); ?></strong>
						<?php _e( 'This plugin requires WooCommerce to be installed and activated.', 'i-hate-fb-so-much' ); ?>
					</p>
				</div>
			<?php else : ?>
				<div class="notice notice-success">
					<p>
						<?php _e( 'If you find this plugin helpful, please consider <a href="https://ko-fi.com/pixelcanopy" target="_blank">buying me a coffee</a>. If you need website help, please consider using Pixel Canopy. Get more info about our services at <a href="https://pixelcanopy.com" target="_blank">https://pixelcanopy.com</a>.', 'i-hate-fb-so-much' ); ?>
					</p>
				</div>
			<?php endif; ?>

			<form action="options.php" method="post">
				<?php
				settings_fields( 'i_hate_fb_so_much' );
				do_settings_sections( 'i_hate_fb_so_much' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Settings section description
	 *
	 * @since    1.0.0
	 */
	public function settings_section_callback() {
		echo '<p>' . __( 'Configure how your site handles Facebook Commerce Platform checkout URLs.', 'i-hate-fb-so-much' ) . '</p>';
	}

	/**
	 * Debug mode setting callback
	 *
	 * @since    1.0.0
	 */
	public function debug_mode_callback() {
		$debug_mode = get_option( 'i_hate_fb_so_much_debug_mode', false );
		?>
		<input type="checkbox" id="i_hate_fb_so_much_debug_mode" name="i_hate_fb_so_much_debug_mode" value="1" <?php checked( $debug_mode, 1 ); ?>>
		<label for="i_hate_fb_so_much_debug_mode"><?php _e( 'Enable debug logging', 'i-hate-fb-so-much' ); ?></label>
		<p class="description"><?php _e( 'Log Facebook checkout requests to the WordPress error log for debugging. Useful when Facebook\'s checkout URLs are being extra annoying.', 'i-hate-fb-so-much' ); ?></p>
		<?php
	}

	/**
	 * Clear cart setting callback
	 *
	 * @since    1.0.0
	 */
	public function clear_cart_callback() {
		$clear_cart = get_option( 'i_hate_fb_so_much_clear_cart_always', true );
		?>
		<input type="checkbox" id="i_hate_fb_so_much_clear_cart_always" name="i_hate_fb_so_much_clear_cart_always" value="1" <?php checked( $clear_cart, 1 ); ?>>
		<label for="i_hate_fb_so_much_clear_cart_always"><?php _e( 'Always clear cart before adding Facebook products', 'i-hate-fb-so-much' ); ?></label>
		<p class="description"><?php _e( 'Recommended: This ensures customers only see the products they clicked on from Facebook, not old items in their cart.', 'i-hate-fb-so-much' ); ?></p>
		<?php
	}

	/**
	 * Redirect destination setting callback
	 *
	 * @since    1.0.0
	 */
	public function redirect_to_callback() {
		$redirect_to = get_option( 'i_hate_fb_so_much_redirect_to', 'cart' );
		?>
		<select id="i_hate_fb_so_much_redirect_to" name="i_hate_fb_so_much_redirect_to">
			<option value="cart" <?php selected( $redirect_to, 'cart' ); ?>><?php _e( 'Cart Page', 'i-hate-fb-so-much' ); ?></option>
			<option value="checkout" <?php selected( $redirect_to, 'checkout' ); ?>><?php _e( 'Checkout Page', 'i-hate-fb-so-much' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Choose where to redirect customers after adding Facebook products to their cart. Cart page lets them review/modify, checkout page streamlines the purchase process.', 'i-hate-fb-so-much' ); ?></p>
		<?php
	}

	/**
	 * Checkout URL display callback
	 *
	 * @since    1.0.0
	 */
	public function checkout_url_callback() {
		$checkout_url = home_url( 'facebook-checkout' );
		?>
		<input type="text" id="i_hate_fb_so_much_checkout_url" value="<?php echo esc_url( $checkout_url ); ?>" readonly style="width: 500px; font-family: monospace;">
		<button type="button" class="button" onclick="navigator.clipboard.writeText(this.previousElementSibling.value)"><?php _e( 'Copy URL', 'i-hate-fb-so-much' ); ?></button>
		<p class="description">
			<?php _e( 'Copy this URL and paste it as your "Checkout URL" in Facebook Commerce Platform settings. This is where Facebook will send customers when they click "Buy Now" on your products.', 'i-hate-fb-so-much' ); ?>
		</p>
		<?php
	}

	/**
	 * Instructions callback
	 *
	 * @since    1.0.0
	 */
	public function instructions_callback() {
		$checkout_url = home_url( 'facebook-checkout' );
		?>
		<div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #0073aa; margin-top: 10px;">
			<h4><?php _e( 'How to Set Up Facebook Commerce Platform:', 'i-hate-fb-so-much' ); ?></h4>
			<ol>
				<li><?php _e( 'Copy the checkout URL above', 'i-hate-fb-so-much' ); ?></li>
				<li><?php _e( 'Go to your Facebook Commerce Manager', 'i-hate-fb-so-much' ); ?></li>
				<li><?php _e( 'Navigate to Commerce → Settings → Checkout', 'i-hate-fb-so-much' ); ?></li>
				<li><?php _e( 'Paste the URL in the "Checkout URL" field', 'i-hate-fb-so-much' ); ?></li>
				<li><?php _e( 'Save your settings and test to make sure it works', 'i-hate-fb-so-much' ); ?></li>
			</ol>

			<h4><?php _e( 'How It Works:', 'i-hate-fb-so-much' ); ?></h4>
			<p><?php _e( 'When customers click "Buy Now" on Facebook, they\'ll be redirected to your site with the products automatically added to their cart. Facebook will append parameters like:', 'i-hate-fb-so-much' ); ?></p>
			<code><?php echo esc_url( $checkout_url ); ?>?products=123:2,456:1&coupon=SAVE10</code>
			<p><small><?php _e( 'This example adds product ID 123 (quantity 2) and product ID 456 (quantity 1) to the cart, then applies coupon "SAVE10".', 'i-hate-fb-so-much' ); ?></small></p>

			<h4><?php _e( 'Product ID Matching:', 'i-hate-fb-so-much' ); ?></h4>
			<p><?php _e( 'The plugin will try to match Facebook product IDs in this order:', 'i-hate-fb-so-much' ); ?></p>
			<ul>
				<li><?php _e( 'WooCommerce Product ID', 'i-hate-fb-so-much' ); ?></li>
				<li><?php _e( 'Product SKU', 'i-hate-fb-so-much' ); ?></li>
				<li><?php _e( 'Custom field "facebook_content_id" (if you use different IDs)', 'i-hate-fb-so-much' ); ?></li>
			</ul>
			<p><?php _e( "You'll have to check your Facebook Commerce Manager products to see for sure what Facebook will send in the URL. As of now (Aug 2025) they will use whatever is in the Content ID field. This may be the same as your WooCommerce product id's, or your SKU's, or it may be a random id Facebook created depending on your Commerce Manager settings.", 'i-hate-fb-so-much' ); ?></p>
		</div>

		<style>
		.wrap code {
			background: #f1f1f1;
			padding: 2px 6px;
			border-radius: 3px;
			font-family: monospace;
			font-size: 13px;
		}
		.wrap ul, .wrap ol {
			margin-left: 20px;
		}
		.wrap li {
			margin-bottom: 5px;
		}
		</style>
		<?php
	}

	/**
	 * Add Facebook Content ID field to product general tab
	 */
	public function add_facebook_content_id_field() {
	    global $post;

	    echo '<div class="options_group">';

	    woocommerce_wp_text_input( array(
	        'id'            => 'facebook_content_id',
	        'label'         => __( 'Facebook Content ID', 'i-hate-fb-so-much' ),
	        'placeholder'   => __( 'Enter Facebook Content ID', 'i-hate-fb-so-much' ),
	        'desc_tip'      => true,
	        'description'   => __( 'The Content ID from Facebook Commerce Manager. Required for Facebook checkout to work with this product.', 'i-hate-fb-so-much' ),
	        'value'         => get_post_meta( $post->ID, 'facebook_content_id', true )
	    ) );

	    echo '</div>';
	}

	/**
	 * Save Facebook Content ID field from product edit screen
	 */
	public function save_facebook_content_id_field( $post_id ) {
		// Security checks
		if ( ! isset( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) ) {
			return;
		}

		// Capability check
		if ( ! current_user_can( 'edit_product', $post_id ) ) {
			return;
		}

		// Autosave check
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['facebook_content_id'] ) ) {
			$facebook_content_id = sanitize_text_field( $_POST['facebook_content_id'] );
			update_post_meta( $post_id, 'facebook_content_id', $facebook_content_id );
		}
	}

	/**
	 * Add Facebook Content ID field to quick edit
	 */
	public function add_facebook_content_id_quick_edit() {
	    ?>
	    <br class="clear" />
	    <div class="inline-edit-group">
	        <label class="inline-edit-status alignleft fb-content-id-quickedit-label">
	            <span class="title fb-content-id-quickedit-title"><?php _e( 'Facebook Content ID', 'i-hate-fb-so-much' ); ?></span>
	            <span class="input-text-wrap">
	                <input type="text" name="facebook_content_id" class="text" placeholder="<?php _e( 'Facebook Content ID', 'i-hate-fb-so-much' ); ?>">
	            </span>
	        </label>
	    </div>

	    <script type="text/javascript">
	    jQuery(document).ready(function($) {
	        // Populate quick edit field with existing value via AJAX
	        $('#the-list').on('click', '.editinline', function() {
	            var row = $(this).closest('tr');
	            var postId = row.attr('id').replace('post-', '');

	            // Make AJAX call to get Facebook Content ID
	            $.post(ajaxurl, {
	                action: 'get_facebook_content_id',
	                post_id: postId,
	                nonce: '<?php echo wp_create_nonce("get_facebook_content_id"); ?>'
	            }, function(response) {
	                if (response.success) {
	                    // Wait for edit row to be created, then populate
	                    setTimeout(function() {
	                        var editRow = $('tr#edit-'+postId);
	                        var input = editRow.find('input[name="facebook_content_id"]');
	                        input.attr('placeholder', '');
	                        // Clear loading state and set value
	                        input.removeClass('fb-loading').val(response.data || '');
	                    }, 100);
	                }
	            });
	            
	            // Add loading state to input after quick edit opens
	            setTimeout(function() {
	                var editRow = $('tr#edit-'+postId);
	                var input = editRow.find('input[name="facebook_content_id"]');
	                input.addClass('fb-loading').attr('placeholder', 'Loading Facebook ID...');
	            }, 50);
	        });
	    });
	    </script>

	    <style>
	    .inline-edit-group .inline-edit-status {
	        width: 50%;
	        float: left;
	        margin-right: 2%;
	    }
	    .inline-edit-group .inline-edit-status .title {
	        display: block;
	        margin-bottom: 5px;
	        font-weight: 600;
	    }
	    .inline-edit-group .inline-edit-status .fb-content-id-quickedit-title {
	    	min-width: 130px;
	    }
	    .inline-edit-row fieldset label.fb-content-id-quickedit-label span.input-text-wrap {
	    	margin-left: 0;
	    }
	    .inline-edit-group .input-text-wrap {
	        display: block;
	    }
	    .inline-edit-group .input-text-wrap input {
	        width: 100%;
	    }
	    .inline-edit-group .input-text-wrap input[name="facebook_content_id"] {
	    	min-width: 200px;
	    }
	    
	    /* Loading state for Facebook Content ID field */
	    input.fb-loading {
	        background: linear-gradient(90deg, #f0f0f1 25%, #e0e0e1 50%, #f0f0f1 75%) !important;
	        background-size: 200% 100% !important;
	        animation: fb-loading 1.2s ease-in-out infinite !important;
	        color: #999 !important;
	        width: 200px;
	    }
	    
	    @keyframes fb-loading {
	        0% { background-position: -200% 0; }
	        100% { background-position: 200% 0; }
	    }
	    </style>
	    <?php
	}

	/**
	 * AJAX handler to get Facebook Content ID for a product
	 */
	public function ajax_get_facebook_content_id() {
		// Security check
		if ( ! check_ajax_referer( 'get_facebook_content_id', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$post_id = intval( $_POST['post_id'] );

		// Check if user can edit this product
		if ( ! current_user_can( 'edit_product', $post_id ) ) {
			wp_send_json_error( 'Permission denied' );
		}

		$facebook_id = get_post_meta( $post_id, 'facebook_content_id', true );

		wp_send_json_success( $facebook_id );
	}

	/**
	 * Save Facebook Content ID from quick edit
	 */
	public function save_facebook_content_id_quick_edit( $product ) {
		// Security check - WooCommerce handles nonce for quick edit
		if ( ! current_user_can( 'edit_product', $product->get_id() ) ) {
			return;
		}

		if ( isset( $_POST['facebook_content_id'] ) ) {
			$facebook_content_id = sanitize_text_field( $_POST['facebook_content_id'] );
			if ( ! empty( $facebook_content_id ) ) {
				update_post_meta( $product->get_id(), 'facebook_content_id', $facebook_content_id );
			} else {
				delete_post_meta( $product->get_id(), 'facebook_content_id' );
			}
		}
	}

}
