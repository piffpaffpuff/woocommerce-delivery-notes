=== WooCommerce Print Invoice & Delivery Note ===

Contributors: piffpaffpuff
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=K2JKYEASQBBSQ&lc=US&item_name=WooCommerce%20Print%20Invoice%20%26%20Delivery%20Note&item_number=WCDN&amount=20%2e00&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest
Tags: delivery note, packing slip, invoice, delivery, shipping, print, order, woocommerce, woothemes, shop
Requires at least: 3.8 and WooCommerce 2.1
Tested up to: WordPress 4.0 Alpha and WooCommerce 2.1.7
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.opensource.org/licenses/gpl-license.php

Print invoices and delivery notes for WooCommerce orders.  

== Description ==

You can print out invoices and delivery notes for the [WooCommerce](http://wordpress.org/plugins/woocommerce/) orders. You can edit the Company/Shop name, Company/Shop postal address and also add personal notes, conditions/policies (like a refund policy) and a footer imprint.

The plugin adds a new side panel on the order page to allow shop administrators to print out the invoice or delivery note. Registered customers can also can also print their order with a button that is added to the order screen.

= Features =

* Print invoices and delivery notes via the side panel on the "Order Edit" page
* Quickly print invoices and delivery notes on the "Orders" page
* Bulk print invoices and delivery notes
* Customers can print an invoice on the "My Account" and "Order Details" page
* Add a company address, a logo and many other information to the invoice and delivery note
* Intelligent invoice and delivery note template system with hooks and functions.php support  
* Completely customize the invoice and delivery note template
* Simple invoice numbering
* Supports sequential order numbers

= Support =

Support can take place in the [public support forums](http://wordpress.org/support/plugin/woocommerce-delivery-notes), where the community can help each other out.

= Contributing =

If you have a patch, or stumbled upon an issue with the source code that isn't a [WooCommerce issue](https://github.com/woothemes/woocommerce/issues?labels=Bug&milestone=22&state=open), you can contribute this back [on GitHub](https://github.com/piffpaffpuff/woocommerce-delivery-notes).

= Translating =

When your language is missing you can contribute a translation to the [GitHub repository](https://github.com/piffpaffpuff/woocommerce-delivery-notes) or [GlotPress repository](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes). 

== Installation ==

1. Upload the entire `woocommerce-delivery-notes` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Choose 'WooCommerce' then 'Settings' and the tab 'Print' to adjust the settings to your need.
4. Print the order with the side panel on the actual order page.

== Frequently Asked Questions ==

= How to prevent that the Website URL and page numbers are printed? =

You can find an option in the print window of your browser to hide those. This is a browser specific option that can't be controlled by the plugin. Please read the browser help for more information.

= Why are my bulk printed orders not splited to separate pages? =

Your browser is to old to create the page breaks correctly. Try to update it to the latest version or use another browser.

= Even though the shipping and billing address is the same, both are still shown, why? =

It depends on your WooCommerce settings. Addresses are displayed the same way as on the WooCommerce account page. Only one address is printed in case you disabled alternative shipping addresses or the whole shipping. In all other cases both addresses are shown.

= It prints the 404 page instead of the order, how to correct that? =

This is most probably due to the permalink settings. Go either to the WordPress Permalink or the WooCommerce Print Settings and save them again.

If that didn't help, go to the WooCommerce 'Accounts' settings tab and make sure that for 'My Account Page' a page is selected.  

= How do I quickly change the font of the invoice and delivery note? =

You can change the font with CSS. Use the `wcdn_head` hook and then write your own CSS code. It's best to place the code in the `functions.php` file of your theme. 

An example that changes the font and makes the addresses very large. Paste the code in the `functions.php` file of your theme:

`
function my_serif_font_and_large_address() {
	?>
		<style>
			#page {
				font-size: 1em;
				font-family: Georgia, serif;
			}
			
			.order-addresses address {
				font-size: 2.5em;
				line-height: 125%;
			}
		</style>
	<?php
}
add_action( 'wcdn_head', 'my_serif_font_and_large_address', 20 );
`

= Can I hide the prices on the delivery note? =

Sure, the easiest way is to hide them with some CSS that is hooked in with `wcdn_head`.

An example that hides the whole price column and the totals. Paste the code in the `functions.php` file of your theme:

`
function my_price_free_delivery_note() {
	?>
		<style>
			.delivery-note .head-price span, 
			.delivery-note .product-price span,
			.delivery-note .order-items tfoot {
				display: none;
			}
			.delivery-note .order-items tbody tr:last-child {
				border-bottom: 0.24em solid black;
			}
		</style>
	<?php
}
add_action( 'wcdn_head', 'my_price_free_delivery_note', 20 );
`

= Is it possible to remove a field from the order info section? =

Yes, use the `wcdn_order_info_fields` filter hook. It returns all the fields as array. Unset or rearrange the values as you like.

An example that removes the 'Payment Method' field. Paste the code in the `functions.php` file of your theme:

`
function my_removed_payment_method( $fields ) {
	unset( $fields['payment_method'] );
	return $fields;
}
add_filter( 'wcdn_order_info_fields', 'my_removed_payment_method' );
`

=  How can I add some more fields to the order info section? =

Use the `wcdn_order_info_fields` filter hook. It returns all the fields as array. Read the WooCommerce documentation to learn how you get custom checkout and order fields. Tip: To get custom meta field values you will most probably need the `get_post_meta( $order->id, 'your_meta_field_name', true);` function and of course the `your_meta_field_name`. 

An example that adds a 'VAT' and 'Customer Number' field to the end of the list. Paste the code in the `functions.php` file of your theme:

`
function my_custom_order_fields( $fields, $order ) {
	$new_fields = array();
		
	if( get_post_meta( $order->id, 'your_meta_field_name', true ) ) {
		$new_fields['your_meta_field_name'] = array( 
			'label' => 'VAT',
			'value' => get_post_meta( $order->id, 'your_meta_field_name', true )
		);
	}
	
	if( get_post_meta( $order->id, 'your_meta_field_name', true ) ) {
		$new_fields['your_meta_field_name'] = array( 
			'label' => 'Customer Number',
			'value' => get_post_meta( $order->id, 'your_meta_field_name', true )
		);
	}
	
	return array_merge( $fields, $new_fields );
}
add_filter( 'wcdn_order_info_fields', 'my_custom_order_fields', 10, 2 );
`

=  What about the product image, can I add it to the invoice and delivery note? =

Yes, use the `wcdn_order_item_before` action hook. It allows you to add html content before the item name.

An example that adds a 40px large product image. Paste the code in the `functions.php` file of your theme:

`
function my_product_image( $product ) {	
	if( isset( $product->id ) && has_post_thumbnail( $product->id ) ) {
		echo get_the_post_thumbnail( $product->id, array( 40, 40 ) );
	}
}
add_action( 'wcdn_order_item_before', 'my_product_image' );
`

= How can I differentiate between invoice and delivery note through CSS? =

The `body` tag contains a class that specifies the template type. The class can be `invoice` or `delivery-note`. You can prefix your style rules to only target one template. For example you could rise the font size for the addresses on the right side:

`
.invoice .billing-address {
	font-size: 2em;
}

.delivery-note .shipping-address {
	font-size: 2em;
}
`

= How do I customize the look of the invoice and delivery note? =

You can use the techniques from the questions above. Or you consider the `wcdn_head` hook to enqueue your own stylesheet. Or for full control, copy the file `style.css` from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing it. 

Note: Create the `woocommerce` and `print-order` folders if they do not exist. This way your changes won't be overridden on plugin updates.

= I would like to move the logo to the bottom, put the products between the shipping and billing address and rotate it by 90 degrees, how can I do that? =

Well, first try it with CSS and some filter/action hooks, maybe the questions above can help you. If this isn't enough, you are free to edit the HTML and CSS of the template. Consider this solution only, if you really know some HTML, CSS and PHP! Most probably you want to edit the `print-content.php` and `style.css`. Copy the files from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing them. 

Note: Create the `woocommerce` and `print-order` folders if they do not exists. This way your changes won't be overridden on plugin updates.

= Is there a list of all action and filter hooks? =

Unfortunately there isn't yet. But you can look directly at the template files to see what is available. 

= Which template functions are available? =

You can use the functions from WordPress, WooCommerce and every installed plugin or activated theme. You can find all plugin specific functions in the `wcdn-template-functions.php` file. In addition the `$order`variable in the template is just a normal `WC_Order` instance. 

= Can I download the order as PDF instead of printing it out? =

No, this isn't possible. Look for another plugin that can do this.

= I need some more content on the order, how can I add it? =

The plugin uses the exact same content as WooCommerce. If the content isn't available in WooCommerce, then it will neither be in the delivery note and invoice. In case you have some special needs, you first have to enhance WooCommerce to solve your issue. Afterwards you can integrate the solution into the invoice and delivery note template via hooks.

= How can I translate the plugin? =

Upload your language file to `/wp-content/languages/plugins/` (create this folder if it doesn't exist). WordPress will then load the language. Make sure you use the same locale as in your configuration and the correct plugin locale i.e. `woocommerce-delivery-notes-it_IT.mo/.po`. 

Please [contribute your translation](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes) to include it in the distribution.

== Screenshots ==

1. The clean invoice print view. 
2. Print panel.
3. Quick print actions.
4. Bulk print orders.
5. Enter company and contact information.
6. Customers can also print the order.

== Changelog ==

= 3.1.1 =

* Fix the hidden loading indicator on order edit screen
* Other small visual optimizations
* Later plugin load hook for better compatibility

= 3.1 =

**Note: Template changes had to be made. Please control your template after the update in case you applied some custom styling.**

* By popular demand the 'Quantity' column is back in the template
* Basic invoice numbering

= 3.0.6 =

* Fixed the known issue where the print button stopped working becuse of SSL
* Fixed an issue where the print page was redirected to the account page 

= 3.0.5 =

**Known issue: Printing won't work when your account uses SSL and the rest of the page doesn't. The issue will be fixed in a future version.**

* Added SKU to the template
* Modified the alignment of product attributes in the template
* Print buttons in the theme will print the invoice (can be changed with hook) 

= 3.0.4 =

* Save the endpoint at activation to not print a 404 page. (Note: Try to resave the print settings if the issue persists after the update.)

= 3.0.3 =

**Attention: This update works only with WooCommerce 2.1 (or later) and Wordpress 3.8 (or later). Install it only if your system meets the requirements.**

* Supports only WooCommerce 2.1 (or later)
* Bulk print actions
* Print buttons in the front-end
* Redesigned template look
* New template structure and action hooks

== Upgrade Notice ==

= 3.0.6 =

Thanks everybody to help fixing the SSL issue. Please report to the support forums if you still have SSL problems after the update.

= 3.0.3 =

* This update works only with WooCommerce 2.1 (or later) and Wordpress 3.8 (or later). Install it only if your system meets the requirements.
* Prior print templates aren't compatible. Read the [FAQ](http://wordpress.org/plugins/woocommerce-delivery-notes/faq/) to customize the new template.
* Translations aren't updated, except German.