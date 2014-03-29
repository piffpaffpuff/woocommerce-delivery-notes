=== WooCommerce Print Invoice & Delivery Note ===

Contributors: Trigvvy Gunderson, David Decker
Tags: delivery note, packing slip, invoice, delivery, shipping, print, order, woocommerce, woothemes, shop
Requires at least: 3.8 and WooCommerce 2.1
Tested up to: WordPress 3.9 Beta and WooCommerce 2.1.6
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.opensource.org/licenses/gpl-license.php

Print order invoices & delivery notes for the WooCommerce shop plugin.




== Description ==

With this plugin you can print out simple invoices and delivery notes for the WooCommerce orders. You can edit the Company/Shop name, Company/Shop postal address and also add personal notes, conditions/policies (like a refund policy) and a footer imprint.

The plugin adds a new side panel on the order page to allow shop administrators to print out the invoice or delivery notes. This is useful for a lot of shops that sell goods which need delivery notes for shipping or with added refund policies etc. In some countries (e.g. in the European Union) such refund policies are required so this plugin could help to combine this with the order info for the customer.




== Installation ==

1. Upload the entire `woocommerce-delivery-notes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Look under the regular WooCommerce settings menu: "WooCommerce > Settings > Tab "Print" and adjust them to your needs
4. On the order page you'll find a new side panel to print the invoice or delivery note for the actual order




== Frequently Asked Questions ==


= How to prevent that the Website URL and page numbers are printed? =

You can find an option in the print view of every browser to disable those. This is a browser specific option that can't be controlled by the plugin. Please read your browser help for more infromation.



= Why are my bulk printed orders not splitted to seperate pages? =

Your browser is to old to create the page breaks correctly. Try to update it to the latest version or use another browser.



= Eventhough the shipping and billing addresses are the same, both are still shown, why is that so? =

It depends on your WooCommerce settings. Addresses are displayed the same way as on the Account page. Only one addrress is printed in case you disabled altenative shipping adresses or the whole shipping. In all other cases both addresses are shown.



= How do I quickly change the font for the invoice and delivery note? =

You can style them with CSS. Use the `wcdn_head` hook and then write your own CSS. It's best to place the code in the `functions.php` file of your theme. 

An example that changes the font and makes the addresses very large:

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
add_action( 'wcdn_head', 'my_serif_font_and_large_address', 50 );
`



= Can I hide the prices on the delivery note? =
Sure, the easiest way is to hide them with some CSS that is hooked in with `wcdn_head`.

`
function my_price_free_delivery_note() {
	?>
		<style>
			.delivery-note .total-heading span, 
			.delivery-note .product-price span {
				display: none;
			}
		</style>
	<?php
}
add_action( 'wcdn_head', 'my_price_free_delivery_note', 50 );
`



= Is it possible to remove a field from the order info section? =

Yes, use the `wcdn_order_info_fields` filter hook. It returns all the fields as array. Unset or rearange the values as you like.

An example that removes the 'Payment Method' field:

`
function my_removed_payment_method( $fields ) {
	unset( $fields['payment_method'] );
	return $fields;
}
add_filter( 'wcdn_order_info_fields', 'my_removed_payment_method' );
`



= How can I add some more fields to the order info section? =

Use the `wcdn_order_info_fields` filter hook. It returns all the fields as array. Also read the WooCommerce documentation to learn how you get custom checkout and order fields. Otherwise you won't be able to replace the 'value' with the real values.

An example that adds a 'VAT' and 'Customer Number' field to the end of the list:

`
function my_custom_order_fields( $fields ) {
	$new_fields = array( 
		'vat' => array( 
			'label' => 'VAT',
			'value' => '123456' 
		),
		'customer_number' => array( 
			'label' => 'Customer Number',
			'value' => 'EC-1234'
		)
	);	
	return array_merge( $fields, $new_fields );
}
add_filter( 'wcdn_order_info_fields', 'my_custom_order_fields' );
`



= What about the product image, can I add it to the invoice and deliver note? =

Yes, use the `wcdn_order_item_before` action hook. It allows you to add html content before the itam name.

An example that adds a 50px large product image:

`
function my_product_image( $product ) {	
	if( isset( $product->id ) && has_post_thumbnail( $product->id ) ) {
		echo get_the_post_thumbnail( $product->id, array( 50, 50 ) );
	}
}
add_action( 'wcdn_order_item_before', 'my_product_image' );
`



= How do I customize the look of the invoice and delivery note? =

You can use the techniques from the questions above. Or you consider the `wcdn_head` hook to enqueue your own stylesheet. Or for full control, copy the file `style.css` from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing it. 

Note: Create the `woocommerce` and `print-order` folders if they do not exists. This way your changes won't be overridden on plugin updates.



= I would like to move the logo to the bottom, put the products between the shipping and billing address and rotate it by 90 degrees, how can I do that? =

Well, first try it with CSS and some filter/action hooks, maybe the questions above can help you. If this isn't enough, you are free to edit the HTML and CSS of the template. Consider this solution only, if you really know some HTML, CSS and PHP! Most probably you want to edit the `print-content.php` and `style.css`. Copy the files from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing them. 

Note: Create the `woocommerce` and `print-order` folders if they do not exists. This way your changes won't be overridden on plugin updates.



= Is there a list of all action and filter hooks I can use? =

Unfortunately there isn't. But you can look at the template files directly to see what is available. 



= What are the template functions can I use? =

You can use the functions from WordPress, WooCommerce and every installed plugin or activated theme. You can find all plugin specific functions in the `wcdn-template-functions.php` file. In addition the `$order`variable in the template is just a normal `WC_Order` instance. 



= Why can't I print this or that? =

The plugin uses the exact same content as WooCommerce. If something isn't visible on `/my-account/view-order` page, then it will neither be visible in the delivery note and invoice. In case you have some special needs, you have to first think how you enhance WooCommerce to solve your issue. Afterwards you can integrate the solution into the invoice and delivery note template.



= How can I translate the plugin? =

Upload your language file to `/wp-content/languages/plugins/` (create this folder if it doesn't exist). WordPress will then load the language. Make sure you use the same locale as in your configuration and the correct plugin locale ie. `woocommerce-delivery-notes-it_IT.mo/.po`. Also, complete custom English wording is possible with that, just use a language file like `woocommerce-delivery-notes-en_US.mo/.po`. And finally get in contact if you would like to add your translation to the standard distribution. 




== Upgrade Notice ==

= 3.0 =
* Requires at least WooCommerce 2.1




== Changelog ==

= 3.0 =
* WooCommerce 2.1 compability
* Print buttons in the theme
* Bulk print actions
* New template structure
* Template hooks to better customize the tamplate
* Updated FAQ with code examples
* Complete code rebuild
