=== WooCommerce Print Invoice & Delivery Note ===

Tags: delivery notes, packing list, invoice, delivery, shipping, print, order, woocommerce, woothemes, shop
Requires at least: 3.8 and WooCommerce 2.1
Tested up to: WordPress 3.9 Beta and WooCommerce 2.1.6
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

= How can I remove the Website URL and page number from the invoice or delivery note? =

You can find an option in the print view of every browser to disable them. This is a browser specific option that can't be controlled by the plugin. Please read your browser help for more infromation.

= Why are my bulk printed orders not splitted to seperate pages? =

Your browser is to old to create the page breaks correctly. Try to update it to the latest version or use another browser.

= How can I add some more fields to the order info section? =

Use the `wcdn_order_info_fields` filter hook. It returns all the fields as array. Also read the WooCommerce documentation to learn how you get custom checkout and order fields. Otherwise you won't be able to replace the 'content' with the real values.

An example that adds a 'VAT' and 'Customer Number' field to the end of the list:

`
function my_custom_order_fields( $fields ) {
	$new_fields = array( 
		'vat' => array( 
			'name' => 'VAT',
			'content' => '123456' 
		),
		'customer_number' => array( 
			'name' => 'Customer Number',
			'content' => 'EC-1234'
		)
	);	
	return array_merge( $fields, $new_fields );
}
add_filter( 'wcdn_order_info_fields', 'my_custom_order_fields' );
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


First of all
 my 
product image filter
no prices filter
differences addresses delivery note invoice








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
add_action( 'wcdn_head', 'my_serif_font_and_large_address', 100 );
`

= How do I customize the look of the invoice and delivery note? =

You can use the techniques from the questions above. Or you consider the `wcdn_head` hook to enqueue your own stylesheet. Or for full control, copy the file `style.css` from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing it. 

Note: Create the `woocommerce` and `print-order` folders if they do not exists. Second note: Never directly edit plugin files.



hook I want to add some content, li a 




= I would like to move the logo to the bottom, how can I do that?  =

Well, first try it with CSS and some hooks, maybe the questions above can help you. If this isn't enough, you are free to edit the HTML and CSS of the template. Consider this solution only, if you really know some HTML, CSS and PHP! Most probably you want to edit the `print-content.php` and `style.css`. Copy the files from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing them. 

Note: Create the `woocommerce` and `print-order` folders if they do not exists. Second note: Never directly edit plugin files.

= What template functions can I use? =

You can use the functions from WordPress, WooCommerce and every installed plugin or activated theme. You can find all plugin specific functions in the `wcdn-template-functions.php` file. In addition the `$order`variable in the template is just a normal `WC_Order` instance. 






= Why can't I print this or that? =

The plugin uses the exact same content as WooCommerce. If something isn't visible on the example.com/my-account/view-order page, then it will neither be visible in the delivery note and invoice. In case you have some special needs, you have to first think how you enhance WooCommerce to solve your problem. Afterwards you  can integrate your solution into the invoice and delivery note template.










== Changelog ==

= 3.0 =
- WooCommerce 2.1 compability
- Print buttons in the theme
- Bulk print actions
- New template structure