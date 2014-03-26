=== WooCommerce Print Invoice & Delivery Note ===
Tags: delivery notes, packing list, invoice, delivery, shipping, print, order, woocommerce, woothemes, shop
Requires at least: 3.8 and WooCommerce 2.1
Tested up to: WordPress 3.9 Beta and WooCommerce 2.1.6
License: GPLv3 or later
License URI: http://www.opensource.org/licenses/gpl-license.php

Print order invoices & delivery notes for the WooCommerce shop plugin.

== Description ==

With this plugin you can print out simple invoices and delivery notes for the orders via the WooCommerce Shop Plugin. You can edit the Company/Shop name, Company/Shop postal address and also add personal notes, conditions/policies (like a refund policy) and a footer imprint/branding.

The plugin adds a new side panel on the order page to allow shop administrators to print out delivery notes. This is useful for a lot of shops that sell goods which need delivery notes for shipping or with added refund policies etc. In some countries (e.g. in the European Union) such refund policies are required so this plugin could help to combine this with the order info for the customer.

= Features =
* The plugin comes with an attached template for the invoice and delivery note (printing) page - you could also copy this to your theme and customize it to your needs! The plugin will recognize the new place. (See under [FAQ here](http://wordpress.org/extend/plugins/woocommerce-print-order/faq/))
* All setting fields on the plugin's settings pages are optional - you can leave them empty to not use them at all or only apply what you need.
* If there are added "Customer Notes" (regular WooCommerce feature) for an order these will automatically displayed at the bottom of the delivery note.
* Custom order numbering via the free [WooCommerce Sequential Order Numbers](http://wordpress.org/extend/plugins/woocommerce-sequential-order-numbers/) plugin.

== Installation ==

1. Upload the entire `woocommerce-delivery-notes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Look under the regular WooCommerce settings menu: "WooCommerce > Settings > Tab "Print" and adjust them to your needs
4. On the order page you'll find a new side panel to print the invoice or delivery note for the actual order

== Frequently Asked Questions ==

How do I quickly change the font for the invoice and delivery note?
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

How do I fully customize the look of the invoice and delivery note?
You can use the technique from the question above. Or you can use the the `wcdn_head` hook to enqueue your own stylesheet. For full control copy the file `style.css` from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing it. 

Note: Create the `woocommerce` and `print-order` folders if they do not exists. Second note: Never directly edit plugin files.



hook I want to add some content, li a 




I want to move the logo to the bottom, how can I do that? 
Well, first try it with CSS and some hooks, maybe the questions above can help you. If this isn't enough, you are free to edit the HTML and CSS of the template. Consider this solution only, if you really know some HTML, CSS and PHP! Most probably you want to edit the `print-content.php` and `style.css`. Copy the files from `woocommerce-delivery-notes/templates/print-order` to `yourtheme/woocommerce/print-order` and start editing them. 

Note: Create the `woocommerce` and `print-order` folders if they do not exists. Second note: Never directly edit plugin files.

Why can't I print this or that?
The plugin uses the exact same content as WooCommerce. If something isn't visible on the theyourdomain.com/my-account/view-order page, then it also won't be in the delivery note and invoice. In case you have some special needs, you have to first think how you enhance WooCommerce to solve your problem. Afterwards you  can integrate your solution into the invoice and delivery note template.

How can I remove the Website URL and page number from the invoice or delivery note?
You can find an option in the print view of every browser to disable them. This is Browser specific option that can't be controlled by the plugin. Please read your browser help for more infromation.


How can I add a custom field to the order?
First of all
 my 
custom fields filter
product image filter
no prices filter
template new page made with css breaks
template hooks
create custom css
remove url and page titel from printes sheet
differences addresses delivery note invoice




What template functions can I use?
You can use the functions from WordPress, WooCommerce and every installed plugin or activated theme. You can find all plugin specific functions in the `wcdn-template-functions.php` file. In addition the `$order`variable in the template is just a normal `WC_Order` instance. 

== Changelog ==

= 3.0 =
- WooCommerce 2.1 compability
- Print buttons in the theme
- Bulk print actions
- New template structure