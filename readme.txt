=== WooCommerce Print Invoices & Delivery Notes ===
Contributors: chabis, daveshine
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=wartamau%40gmail%2ecom&lc=US&item_name=piffpaffpuff&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: delivery notes, delivery, shipping, print, order, invoice, invoices, woocommerce, woothemes, shop, shop manager, deckerweb
Requires at least: 3.8 and WooCommerce 2.1
Tested up to: 3.9 and WooCommerce 2.1
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
* We are open for your suggestions and feedback. [report & contribute on GitHub](https://github.com/piffpaffpuff/woocommerce-delivery-notes/issues)

== Installation ==

1. Upload the entire `woocommerce-delivery-notes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Look under the regular WooCommerce settings menu: "WooCommerce > Settings > Tab "Print" and adjust them to your needs
4. On single order pages you'll find a new meta box on the right side where it says "Order Print" you can open the invoice or delivery note for the actual order and print it out directly
5. Go and manage your orders - good luck with sales :)

== Frequently Asked Questions ==


== Screenshots ==

== Changelog ==

= 3.0 =
- Print buttons on the "My Account" page
- Bulk print actions
- New and better templates


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