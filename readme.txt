=== WooCommerce Print Invoices & Delivery Notes ===
Contributors: chabis, daveshine
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=wartamau%40gmail%2ecom&lc=US&item_name=piffpaffpuff&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: delivery notes, delivery, shipping, print, order, invoice, invoices, woocommerce, woothemes, shop, shop manager, deckerweb
Requires at least: 3.5 and WooCommerce 1.6.6
Tested up to: 3.5.1 and WooCommerce 2.0
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.opensource.org/licenses/gpl-license.php

Print order invoices & delivery notes for WooCommerce shop. You can add company/shop info as well as personal notes & policies to print pages.

== Description ==

With this plugin you can print out **simple invoices and delivery notes** for the orders **via the WooCommerce Shop Plugin**. You can edit the Company/Shop name, Company/Shop postal address and also add personal notes, conditions/policies (like a refund policy) and a footer imprint/branding.

The plugin adds a new side panel on the order page to allow shop administrators to print out delivery notes. This is useful for a lot of shops that sell goods which need delivery notes for shipping or with added refund policies etc. In some countries (e.g. in the European Union) such refund policies are required so this plugin could help to combine this with the order info for the customer.

= Features =
* The plugin comes with an attached template for the invoice and delivery note (printing) page - you could also copy this to your theme and customize it to your needs! The plugin will recognize the new place. (See under [FAQ here](http://wordpress.org/extend/plugins/woocommerce-delivery-notes/faq/))
* All setting fields on the plugin's settings pages are optional - you can leave them empty to not use them at all or only apply what you need.
* If the company/shop name field is left empty then the regular website/blog title is used (defined via regular WordPress options)
* If there are added "Customer Notes" (regular WooCommerce feature) for an order these will automatically displayed at the bottom of the delivery note.
* Custom order numbering via the free [WooCommerce Sequential Order Numbers](http://wordpress.org/extend/plugins/woocommerce-sequential-order-numbers/) plugin.
* Included help tab system.
* Localized in English, German, Dutch, Swedish, Spanish, French, Italian, Polish, Russian, Turkish, Slovakian, Finnish and Portuguese (BR). (thanks to all translators, submit your translation) - some of them only partial yet (we'd love to see you [complete them](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes) :).

Credit where credit is due: This plugin here is inspired and based on the work of Steve Clark, Trigvvy Gunderson and the awesome "Jigoshop Delivery Notes" plugin! See below how you can contribute to the further development of both:

= Translations =
* Translate and submit files with our [GlotPress](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes) tool. Read to the [translation](http://wordpress.org/extend/plugins/woocommerce-delivery-notes/other_notes/) section to learn more.

= Feedback =
* We are open for your suggestions and feedback! Use the [plugin's forum](http://wordpress.org/tags/woocommerce-delivery-notes?forum_id=10) or [report & contribute on GitHub](https://github.com/piffpaffpuff/woocommerce-delivery-notes/issues)

= More =
* [Other plugins by main plugin author](http://github.com/piffpaffpuff)

== Installation ==

1. Upload the entire `woocommerce-delivery-notes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Look under the regular WooCommerce settings menu: "WooCommerce > Settings > Tab "Print" and adjust them to your needs
4. On single order pages you'll find a new meta box on the right side where it says "Order Print" you can open the invoice or delivery note for the actual order and print it out directly
5. Go and manage your orders - good luck with sales :)

== Frequently Asked Questions ==

= Why is my company logo not displayed at the size I've uploaded it? =
Printed paper needs a higher pixel density for images than the screen. To make your printed logo look nice and crisp it will be resized via CSS to a fourth of the uploaded pixel size. This means: An image of 400p x 400px will be displayed as a 100px x 100px image but will be printed with the original amount of pixels. With the resizing, 288 pixels of the original image width correspond to about 1 printed inch.

= How can I change the address format of the recipient? =
WooCommerce includes address formats for many different countries. But maybe your country format isn't included. For such cases you can define your own format with a filter in your `functions.php` if you know your country code (ie `LI`).

`
function custom_localisation_address_formats($formats) {
	$formats['LI'] = "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}";
	return $formats;
}
add_filter('woocommerce_localisation_address_formats', 'custom_localisation_address_formats');
`

= How can I use a custom CSS file for the printing page without copying and editing the template files? =
Copy the `style.css` (and no other file) from the `/wp-content/plugins/woocommerce-delivery-notes/templates/print` folder and paste it inside your `/wp-content/themes/your-theme-name/woocommerce/print` folder (if not there just create it). You can modify CSS to fit your own needs. 

*Note:* There is automatically a class assigned to the html tag that helps you to target the template-type in your CSS.

= How can I use a custom template for the printing page? =
If you want to use your own template then all you need to do is copy the the files you want to edit from `/wp-content/plugins/woocommerce-delivery-notes/templates/print` folder and paste them inside your `/wp-content/themes/your-theme-name/woocommerce/print` folder (if not there just create it). The folder from the plugin comes with the default templates and the basic CSS files. You can modifiy those to fit your own needs.

= What template functions can I use? =
All WordPress and all functions from other plugins and themes can be used. There are also many many specific template functions. Open the `/wp-content/plugins/woocommerce-delivery-notes/woocommerce-delivery-notes.php` file to see all of them.

= How do I add more info or custom fields to the templates? =
Create a custom template (see questions above). Then edit the file to your needs. You can use all functions that are available in WordPress or any activated plugin or theme. 

Example: Show an order custom field that was added by another plugin (e.g. `'_my_custom_field'`)

`echo wcdn_get_order_custom_field('_my_custom_field');` 

= What will actually get printed out? =
The page will be printed as you see it in your browser when you enabled the preview. 

Beyond the styling of the template be aware of any special features of the used browser. They may not print websites properly or add a pagination or website url. Use a "Print Preview" feature of your browser which all current versions of Firefox, Chrome, Safari and Opera support.

= How can I translate the plugin with my own wording? =
For custom and update-secure language files please upload them to `/wp-content/languages/woocommerce-delivery-notes/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that, just use a language file like `woocommerce-delivery-notes-en_US.mo/.po` to achieve that (for creating one see the tools on "Other Notes"). And finally contact one of the developers if you would like to add your translation to the standard distribution. 

== Screenshots ==

1. Plugin's settings page where you can set up to five fields for the delivery note. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-1.png)
2. Single Order Edit page with the meta box and the print buttons.
3. Invoice printing page with default template - and the five custom sections. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-3.png)
4. Delivery Note printing page with default template - and the five custom sections. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-4.png)
5. Help tabs on the plugin's settings page with some info and important plugin links. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-5.png)

== Changelog ==

= 2.0.1 =
* FIX: Updated print functionality for better cross-browser support.

= 2.0 =
* FIX: Print privileges for WooCommerce 2.0.
* FIX: The Hi-DPI resizing of the company logo is working better now.
* FIX: Plugin activates correctly on a multisite installation.
* UPDATE: More stable cross-browser print support.
* UPDATE: Printing without thickbox.
* UPDATE: Removed the thickbox preview
* NEW: Template preview links in the settings page (only visible when there is at least one order).
* NEW: Many template functions can now be filtered with `add_filter`.
* NEW: In the template the payment method is now displayed via the `wcdn_payment_method` function.
* NEW: In the template the today's date is now used as default instead of the order date.

= 1.4.6 =
* FIX: Child theme template files are better loaded.
* UPDATE: Translations are better loaded.

= 1.4.5 =
* UPDATE: Better order number support for the sequential order numbers plugin. (thanks FoxRunSoftware)
* NEW: Danish localization. (thanks brandt)
* FIX: Logo uploader "Insert into Post" button works again.

= 1.4.4 =
* FIX: Product variations are returned instead of echoed.
* UPDATE: Better product meta data display.

= 1.4.3 =
* FIX: Logo uploader tabs.

= 1.4.2 =
* FIX: Display Product variations.
* NEW: Icon buttons for the order actions.

= 1.4.1 =
* FIX: Logo uploader button.
* UPDATE: Indicate when the print page is loading.

= 1.4 =
* ATTENTION: For custom template creators: This update breaks your custom template because of some new features. Please update your template PHP code and CSS.
* NEW: Print buttons on the orders list page.
* NEW: Print preview window opens in lightbox.
* NEW: Print immediately, without preview window.
* FIX: Order totals are now properly displayed again.
* FIX: Close unclosed `<tr>` tag in the template.

= 1.2.4 =
* ATTENTION: This update breaks your custom template because many functions were renamed or removed. Please update your custom template.
* NEW: The custom `style.css` is now loaded even when there is no `print.php` in your theme folder `yourthemename/woocommerce/delivery-notes`. Like this the look of the default template can be changed without editing the `print.php` file.
* NEW: The company logo isn't resized anymore. Instead it is loaded with the original pixel dimensions but is then resized via CSS to a fourth. Make sure that your original image file has the desired pixel dimensions!
* NEW: Removed the custom/sequential order number settings. They are now fully handled by the additional plugin.
* UPDATE: Renamed or removed many template functions. Please update your custom template.
* UPDATE: The print navigation is now separated from the template file. Please update your custom template.

= 1.2.3 =
* UPDATE: Order totals are now displayed the same way as when the customer reviews the order online (Update custom template to use the feature).
* UPDATE: Variations and Attributes are now properly displayed (Update custom template to use the feature).

= 1.2.2 =
* FIX: The media management button "Insert into Post" is now visible again. It was hidden by a the css.

= 1.2.1 =
* NEW: Company logo upload.
* NEW: Order numbering supports the sequential order numbers plugin. The offset field was removed because the plugin is much better. (thanks FoxRunSoftware, welovewordpress).
* FIX: Print page doesn't block the user when get vars aren't set.
* UPDATE: Template shows customer phone number (thanks welovewordpress).
* UPDATE: Template item list contains more data fields (thanks welovewordpress).

= 1.2 =
* IMPORTANT CHANGE: New main development and authorship now: [WordPress.org user "chabis"](http://profiles.wordpress.org/chabis/) - with daveshine (David Decker) remaining as a co-author.
* *New features:*
 * NEW: Basic invoice template support.
 * NEW: Custom order number.
 * NEW: New cleaner looking print template.
* CODE: Restructured classes - plugin now completely relies on classes!
* CODE: General code cleanup and numerous improvements.
* UPDATE: Settings are now part of the "WooCommerce" settings, now see: WooCommerce > Settings > Tab "Print"
* UPDATE - IMPORTANT CHANGE: Template folder renaming -- custom templates must be renamed in order to work! -- See [FAQ section here](http://wordpress.org/extend/plugins/woocommerce-delivery-notes/faq/) for more info on that...
* UPDATE: Updated all existing screenshots and added two new ones.
* UPDATE: Updated readme.txt here with changed documentation and all other important new stuff, regarding authorship, plugin links etc.
* NEW: Added new partial translations for: Dutch, French, Spanish - all user-submitted! Big thanks to Ramon, Olivier and @JAVidania
* UPDATE: Updated German translations and also the .pot file for all translators!
* UPDATE: Extended GPL License info in readme.txt as well as main plugin file.
* NEW: Added banner image on WordPress.org for better plugin branding :)
* NEW: Easy plugin translation platform with GlotPress tool: [Translate "WooCommerce Print Invoices & Delivery Notes"...](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes)

= 1.1 =
* *Maintenance release*
* UPDATE: Changed product price calculation due to changes in WooCommerce itself -- this led to **new required versions** for this plugin: **at least WordPress 3.3 and WooCommerce 1.4** or higher (Note: If you still have WooCommerc 1.3.x running then use version 1.0 of the Delivery Notes plugin!)
* UPDATE: Custom fields on settings page now accept proper `img` tags, so you can add logo images or such via HTML IMG tag (for example: `<img src="your-image-url" width="100" height="100" alt="Logo" title="My Shop" />`)
* UPDATE: Corrected readme.txt file
* NEW: Added Swedish translation - Thanx to Christopher Anderton
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.0 =
* Initial release
* Forked and extended from original plugin for Jigoshop ("Jigoshop Delivery Notes" at GitHub)

== Upgrade Notice ==

= 1.5 =
Update your custom style.css. Remove any width and height CSS properties that were assigned to the img tag.

= 1.2.4 =
Many template functions were updated. Please update your custom template too or everything breaks!

= 1.2.1 =
The sequential order numbers plugin requires at least WooCommerce 1.5.3.

= 1.2 =
Major additions & improvements: Now with basic invoice support. Code cleanup & improvements. Added new partial translations, updated German translations plus .pot file for translators. Also, new plugin authorship!

= 1.1 =
Several changes: Changed price calculation due to WC 1.4+ changes. Added img tag support for fields on settings page. Corrected readme.txt file, added Swedish translations, also updated .pot file together with German translations.

= 1.0 =
Just released into the wild.

== Plugin Links ==
* [Translations (GlotPress)](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes)
* [User support forums](http://wordpress.org/tags/woocommerce-delivery-notes?forum_id=10)
* [Developers: reports bugs & issues](https://github.com/piffpaffpuff/woocommerce-delivery-notes/issues)
* [Developers: contribute](https://github.com/piffpaffpuff/woocommerce-delivery-notes)

== Translations ==

* English - default, always included
* German (de_DE): Deutsch - immer dabei! [Download auch via deckerweb.de](http://deckerweb.de/material/sprachdateien/woocommerce-und-extensions/#woocommerce-delivery-notes)
* Dutch (nl_NL): Nederlands - user-submitted by [Ramon van Belzen](http://profiles.wordpress.org/Ramoonus/)
* Swedish (sv_SE): Svenska - user-submitted by [Christopher Anderton](http://www.deluxive.se/)
* Spanish (es_ES): Español - user-submitted by @JAVidania
* French (fr_FR): Français - user-submitted by Olivier
* Danish (da_DK): Dansk - user-submitted by [boldt](http://boldt.325.dk/)
* Polish (pl_PL): Polski - user-submitted
* Russian (ru_RU): русский - user-submitted
* Finnish (fi): Suomi - user-submitted
* Italian (it_IT): Italiano - user-submitted
* Portuguse, Brazilian (pt_BR): Português - user-submitted
* Slovakian (sk_SK): Slovenčina - user-submitted
* Turkish (tr_TR): Türk - user-submitted
* For custom and update-secure language files please upload them to `/wp-content/languages/woocommerce-delivery-notes/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that as well, just use a language file like `woocommerce-delivery-notes-en_US.mo/.po` to achieve that.

**Easy plugin translation platform with GlotPress tool:** [Translate the plugin here](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes)

**Made your own translation?:** [Just send it in](http://genesisthemes.de/en/contact/)

*Note:* All my plugins are internationalized/ translateable by default. This is very important for all users worldwide. So please contribute your language to the plugin to make it even more useful. For translating I recommend the awesome ["Codestyling Localization" plugin](http://wordpress.org/extend/plugins/codestyling-localization/) and for validating the ["Poedit Editor"](http://www.poedit.net/), which works fine on Windows, Mac and Linux.

== Credits ==
Thanks to WooThemes company and WooCommerce team for promoting this plugin on their official homepage as well as on the download page here on wordpress.org! ;-)
