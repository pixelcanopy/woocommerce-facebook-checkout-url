=== FB Checkout URL Handler for WooCommerce ===
Contributors: pixelcanopy
Donate link: https://ko-fi.com/pixelcanopy
Tags: facebook, woocommerce, checkout, commerce, ecommerce
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0
WC tested up to: 8.0

Handle Facebook Commerce Platform checkout URLs for WooCommerce. Because Facebook decided to make this unnecessarily complicated for all WooCommerce store owners!

== Description ==

This plugin handles Facebook Commerce Platform checkout URLs so your customers can seamlessly purchase from Facebook ads and shops. Because apparently Facebook decided to make this unnecessarily complicated for all WooCommerce store owners! 🙄

When Facebook discontinued on-platform checkout, they introduced a new system where customers are redirected to your website with product information in the URL. This plugin automatically processes those URLs, adds the products to the customer's WooCommerce cart, and redirects them to either the cart or checkout page.

**Key Features:**

* **Automatic Product Addition** - Parses Facebook checkout URLs and adds products to WooCommerce cart
* **Multiple Product Support** - Handle multiple products with quantities: `products=123:2,456:1`
* **Coupon Support** - Automatically apply coupons from Facebook URLs
* **Flexible Product Matching** - Matches products by WooCommerce ID, SKU, or custom Facebook Content ID
* **Cart Management** - Option to clear cart before adding Facebook products
* **Redirect Control** - Choose to redirect customers to cart or checkout page
* **Error Handling** - Comprehensive validation and user-friendly error messages
* **Debug Mode** - Optional logging for troubleshooting
* **Easy Setup** - Simple configuration with clear instructions

**How It Works:**

1. Customer clicks "Buy Now" on Facebook
2. Facebook redirects to your site: `yoursite.com/facebook-checkout?products=123:2&coupon=SAVE10`
3. Plugin processes the URL and adds products to cart
4. Customer is redirected to cart or checkout page
5. Customer completes purchase on your site

**Product ID Matching:**

The plugin tries to match Facebook product IDs in this order:
1. WooCommerce Product ID
2. Product SKU
3. Custom field "facebook_content_id" (for different IDs)

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/` directory, or install through WordPress admin
2. Ensure WooCommerce is installed and activated
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Settings → FB Checkout Handler to configure the plugin
5. Copy your checkout URL and paste it in Facebook Commerce Manager

**Facebook Commerce Manager Setup:**

1. Go to Facebook Commerce Manager
2. Navigate to Commerce → Settings → Checkout
3. Paste your checkout URL: `yoursite.com/facebook-checkout`
4. Save settings and test

**Important Notice**

If Facebook is passing random Content ID's in the url, you will have to MANUALLY UPDATE every single you product on your site with the custom field "facebook_content_id". Every. Single. Product. (Thanks, Zuck!) To add Facebook Content IDs to your products:

**Method 1 - Individual Product Edit:**
1. Go to Products → All Products
2. Click "Edit" on any product
3. Scroll to the "General" tab
4. Look for the "Facebook Content ID" field
5. Enter the Content ID from your Facebook Commerce Manager
6. Update the product

**Method 2 - Quick Edit (Faster for Multiple Products):**
1. Go to Products → All Products
2. Hover over any product and click "Quick Edit"
3. Look for the "Facebook Content ID" field at the bottom
4. Enter the Content ID and click "Update"


== Frequently Asked Questions ==

= Does this work with Facebook Shops? =

Yes! This plugin works with Facebook Shops, Instagram Shopping, and any Facebook Commerce Platform feature that uses checkout URLs.

= What if my Facebook product IDs don't match my WooCommerce product IDs? =

No problem! The plugin can match by SKU or you can use the custom "Facebook Content ID" field on each product to specify the exact ID Facebook will send.

= Can I redirect customers directly to checkout? =

Yes! In the plugin settings, you can choose to redirect customers to either the cart page (so they can review) or directly to the checkout page for a streamlined experience.

= Will this work with my existing cart contents? =

By default, the plugin clears the cart before adding Facebook products to avoid confusion. This can be disabled in settings if you prefer to add to existing cart contents.

= What happens if a product is out of stock? =

The plugin checks stock availability and will show an error message if a product is out of stock, rather than adding it to the cart.

= Does this support variable products? =

The plugin works with simple products. Variable product support depends on how Facebook sends the variation information in the URL.

= Can I debug issues? =

Yes! Enable debug mode in the settings to log all Facebook checkout requests to your WordPress error log.

== Changelog ==

= 1.0.0 =
* Initial release
* Facebook Commerce Platform checkout URL handling
* Multiple product support with quantities
* Coupon code application
* Product matching by ID, SKU, or custom field
* Configurable cart clearing
* Configurable redirect destination (cart or checkout)
* Debug logging option
* Admin interface for easy setup
* Error handling and validation
* WooCommerce integration

== Upgrade Notice ==

= 1.0.0 =
Initial release. Install to handle Facebook Commerce Platform checkout URLs seamlessly.

== Support ==

For support, please visit [pixelcanopy.com](https://pixelcanopy.com) or consider supporting development at [ko-fi.com/pixelcanopy](https://ko-fi.com/pixelcanopy).

**Important Note:** Make sure your Facebook product catalog uses the same IDs as your WooCommerce products (Product ID or SKU). Otherwise, products won't be found and customers will see errors. Check your Facebook Commerce Manager product Content ID fields to see what Facebook will send in the checkout URLs.
