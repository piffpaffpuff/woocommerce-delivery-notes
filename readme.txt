=== WooCommerce Print Invoices & Delivery Notes ===
Contributors: chabis, daveshine, deckerweb
Tags: delivery notes, delivery, shipping, print, order, invoice, invoices, woocommerce, woothemes, shop, shop manager, deckerweb
Requires at least: 3.3 and WooCommerce 1.5.3+
Tested up to: 3.4
Stable tag: 1.2.1
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
* Modify the order numbering by prepend or append text to it.
* Included help tab system with new WordPress 3.3 standard! (Will also be extended if needed!)

= Localization =
* English (default) - always included
* German - always included
* Dutch - user-submitted, thanks to [Ramon van Belzen](http://profiles.wordpress.org/Ramoonus/) -- Currently 37% complete
* Swedish - user-submitted, thanks to [Christopher Anderton](http://www.deluxive.se/) -- Currently 42% complete
* Spanish - user-submitted by @JAVidania -- Currently 42% complete
* French - user-submitted by Olivier -- Currently 42% complete
* .pot file (`woocommerce-delivery-notes.pot`) for translators is also always included :)
* Easy plugin translation platform with GlotPress tool: [Translate "WooCommerce Print Invoices & Delivery Notes"...](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes)
* *Your translation? - [Just send it in](http://genesisthemes.de/en/contact/)*

Credit where credit is due: This plugin here is inspired and based on the work of Steve Clark, Trigvvy Gunderson and the awesome "Jigoshop Delivery Notes" plugin! See below how you can contribute to the further development of both:

= Feedback =
* We are open for your suggestions and feedback! Use the [plugin's forum](http://wordpress.org/tags/woocommerce-delivery-notes?forum_id=10) or [report & contribute on GitHub](https://github.com/piffpaffpuff/woocommerce-delivery-notes/issues)
* Drop Dave a line [@deckerweb](http://twitter.com/#!/deckerweb) on Twitter
* Follow Dave on [my Facebook page](http://www.facebook.com/deckerweb.service)
* Or follow Dave on [+David Decker](http://deckerweb.de/gplus) on Google Plus ;-)

= More =
* [Other plugins from main plugin author](http://profiles.wordpress.org/chabis/)
* [Also see other by plugin co-author DECKERWEB](http://genesisthemes.de/en/wp-plugins/) or see [his WordPress.org profile page](http://profiles.wordpress.org/daveshine/)
* Tip for Genesis users: [*GenesisFinder* - Find then create. Your Genesis Framework Search Engine.](http://genesisfinder.com/)

== Installation ==

1. Upload the entire `woocommerce-delivery-notes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Look under the regular WooCommerce settings menu: "WooCommerce > Settings > Tab "Print" and adjust them to your needs
4. On single order pages you'll find a new meta box on the right side where it says "Order Print" you can open the invoice or delivery note for the actual order and print it out directly
5. Go and manage your orders - good luck with sales :)

**Please note:** You must run WordPress 3.3 or higher and WooCommerce 1.4 or higher in order tun this plugin. This is due to changes in WooCommerc v1.4+!

**Own translation/wording:** For custom and update-secure language files please upload them to `/wp-content/languages/woocommerce-delivery-notes/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that, just use a language file like `woocommerce-delivery-notes-en_US.mo/.po` to achieve that (for creating one see the tools on "Other Notes").

== Frequently Asked Questions ==

= Can I use a custom template for the printing page? =
If you want to use your own template then all you need to do is copy the `/wp-content/plugins/woocommerce-delivery-notes/templates/delivery-notes` folder and paste it inside your `/wp-content/themes/your-theme-name/woocommerce` folder (if not there just create it). The folder from the plugin comes with the default template and the basic CSS stylesheet file. You can modifiy this to fit your own needs.

*Note:* This works with both single themes and child themes (if you use some framework like Genesis). If your current active theme is a child theme put the custom folder there! (e.g. `/wp-content/themes/your-child-theme-name/woocommerce`)

= Can I use a different custom template for invoices and delivery notes? =
Yes. Create in the `your-theme-name/woocommerce/delivery-notes` folder a file named `print-invoice.php` and another `print-delivery-note.php`. Or just create a file `print.php` to use the same template for invoices and delivery notes. Now write some nice code to make your templates look as you like. 

*Note:* The `print.php` isn't needed when you have a `print-invoice.php` and `print-delivery-note.php` file. However the template system falls back to the `print.php` file inside your themes folder and then inside the plugins folder when `print-invoice.php` and/or `print-delivery-note.php` weren't found.

= What Template Functions can I use? =
Various functions are available in the template, especially many Delivery Notes specific template functions. Open the `woocommerce-delivery-notes/woocommerce-delivery-notes-print.php` file to see all available functions.

*Please note:* This is only intended for developers who know what they do! Please be careful with adding any code/functions! The default template and functions should fit most use cases.

= What will actually get printed out? =
No worries, the print bar at the top will automatically be hidden on print!

The other sections get printed as styled via the packaged template (or your custom template if configured). For the shop/company name and all other notes sections: only these will get printed which are actually configured.

Beyond the styling of your template be aware of any special features of your used browser - I highly recommend to use the "Print Preview" feature of your browser which all current versions of Firefox, Chrome and Opera support.

= Can you update the plugin with feature X or option Y? =
Mmh. Maybe.

The basic intention is to have the plugin at the same time as leightweight and useful as possible. So any feature request needs to be reviewed for that reasons.

== Screenshots ==

1. Plugin's settings page where you can set up to five fields for the delivery note. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-1.png)
2. Single Order Edit page with the meta box and the print buttons.
3. Invoice printing page with default template - and the five custom sections. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-3.png)
4. Delivery Note printing page with default template - and the five custom sections. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-4.png)
5. Help tabs on the plugin's settings page with some info and important plugin links. [Click for larger image view](http://www.wordpress.org/extend/plugins/woocommerce-delivery-notes/screenshot-5.png)

== Changelog ==

= 1.2.1 (2012-05-08) =
* NEW: Company logo upload.
* NEW: Order numbering supports the sequential order numbers plugin. The offset field was removed because the plugin is much better. (thanks FoxRunSoftware, welovewordpress).
* FIX: Print page doesn't block the user when get vars aren't set.
* UPDATE: Template shows customer phone number (thanks welovewordpress).
* UPDATE: Template item list contains more data fields (thanks welovewordpress).

= 1.2 (2012-05-06) =
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

= 1.1 (2012-02-07) =
* *Maintenance release*
* UPDATE: Changed product price calculation due to changes in WooCommerce itself -- this led to **new required versions** for this plugin: **at least WordPress 3.3 and WooCommerce 1.4** or higher (Note: If you still have WooCommerc 1.3.x running then use version 1.0 of the Delivery Notes plugin!)
* UPDATE: Custom fields on settings page now accept proper `img` tags, so you can add logo images or such via HTML IMG tag (for example: `<img src="your-image-url" width="100" height="100" alt="Logo" title="My Shop" />`)
* UPDATE: Corrected readme.txt file
* NEW: Added Swedish translation - Thanx to Christopher Anderton
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.0 (2011-12-30) =
* Initial release
* Forked and extended from original plugin for Jigoshop ("Jigoshop Delivery Notes" at GitHub)

== Upgrade Notice ==

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

== Donate ==
Enjoy using *WooCommerce Print Invoices & Delivery Notes*? Please consider [making a small donation](http://genesisthemes.de/en/donate/) to support the project's continued development.

== Translations ==

* English - default, always included
* German (de_DE): Deutsch - immer dabei! [Download auch via deckerweb.de](http://deckerweb.de/material/sprachdateien/woocommerce-und-extensions/#woocommerce-delivery-notes)
* Dutch (nl_NL): Nederlands - user-submitted by [Ramon van Belzen](http://profiles.wordpress.org/Ramoonus/)
* Swedish (sv_SE): Svenska - user-submitted by [Christopher Anderton](http://www.deluxive.se/)
* Spanish (es_ES): Español - user-submitted by @JAVidania
* French (fr_FR): Français - user-submitted by Olivier
* For custom and update-secure language files please upload them to `/wp-content/languages/woocommerce-delivery-notes/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that as well, just use a language file like `woocommerce-delivery-notes-en_US.mo/.po` to achieve that.

**Easy plugin translation platform with GlotPress tool: [Translate "WooCommerce Print Invoices & Delivery Notes"...](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/woocommerce-delivery-notes)**

*Note:* All my plugins are internationalized/ translateable by default. This is very important for all users worldwide. So please contribute your language to the plugin to make it even more useful. For translating I recommend the awesome ["Codestyling Localization" plugin](http://wordpress.org/extend/plugins/codestyling-localization/) and for validating the ["Poedit Editor"](http://www.poedit.net/), which works fine on Windows, Mac and Linux.

== Additional Info ==
**Idea Behind / Philosophy:** Just a little plugin for all the WooCommerce shop managers out there to make their daily shop admin life a bit easier.

== Credits ==
Thanks to WooThemes company and WooCommerce team for promoting this plugin on their official homepage as well as on the download page here on wordpress.org! ;-)