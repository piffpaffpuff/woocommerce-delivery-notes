<?php
/**
 * Main plugin file.
 * Print order invoices & delivery notes for WooCommerce shop plugin.
 * You can add company/shop info as well as personal notes & policies to print pages.
 *
 * @package   WooCommerce Print Invoices & Delivery Notes
 * @copyright Copyright 2011-2012 Steve Clark, Trigvvy Gunderson, David Decker - DECKERWEB
 *
 * @credits   Inspired and based on the plugin "Jigoshop Delivery Notes" by Steve Clark and Trigvvy Gunderson
 * @link      http://www.clark-studios.co.uk/blog/
 * @link      https://github.com/piffpaffpuff
 *
 * Plugin Name: WooCommerce Print Invoices & Delivery Notes
 * Plugin URI: https://github.com/piffpaffpuff/woocommerce-delivery-notes
 * Description: Print order invoices & delivery notes for WooCommerce shop plugin. You can add company/shop info as well as personal notes & policies to print pages.
 * Version: 1.2.3
 * Author: Steve Clark, Triggvy Gunderson, David Decker
 * Author URI: https://github.com/piffpaffpuff/woocommerce-delivery-notes
 * License: GPLv3 or later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: woocommerce-delivery-notes
 * Domain Path: /languages/
 *
 * Copyright 2011-2012 Steve Clark, Trigvvy Gunderson, David Decker - DECKERWEB
 *		
 *     This file is part of WooCommerce Print Invoices & Delivery Notes,
 *     a plugin for WordPress.
 *
 *     WooCommerce Print Invoices & Delivery Notes is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     WooCommerce Print Invoices & Delivery Notes is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Base class
 *
 * @since 1.0
 */
if ( ! class_exists( 'WooCommerce_Delivery_Notes' ) ) {

	class WooCommerce_Delivery_Notes {
	
		public static $plugin_prefix;
		public static $plugin_url;
		public static $plugin_path;
		public static $plugin_basefile;
		
		public $writepanel;
		public $settings;
		public $print;

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			self::$plugin_prefix = 'wcdn_';
			self::$plugin_basefile = plugin_basename(__FILE__);
			self::$plugin_url = plugin_dir_url(self::$plugin_basefile);
			self::$plugin_path = trailingslashit(dirname(__FILE__));
		}
		
		/**
		 * Load the hooks
		 *
		 * @since 1.0
		 */
		public function load() {
			add_action( 'init', array( $this, 'load_hooks' ) );
		}
		
		/**
		 * Load the main plugin classes and functions
		 *
		 * @since 1.0
		 */
		public function includes() {
			include_once( 'classes/class-wcdn-writepanel.php' );
			include_once( 'classes/class-wcdn-settings.php' );
			include_once( 'classes/class-wcdn-print.php' );
		}

		/**
		 * Load the hooks
		 *
		 * @since 1.0
		 */
		public function load_hooks() {	
			if ( $this->is_woocommerce_activated() ) {					
				load_plugin_textdomain( 'woocommerce-delivery-notes', false, dirname( self::$plugin_basefile ) . '/../../languages/woocommerce-delivery-notes/' );
				load_plugin_textdomain( 'woocommerce-delivery-notes', false, dirname( self::$plugin_basefile ) . '/languages' );

				$this->includes();
				$this->writepanel = new WooCommerce_Delivery_Notes_Writepanel();
				$this->writepanel->load();
				$this->settings = new WooCommerce_Delivery_Notes_Settings();
				$this->settings->load();
				$this->print = new WooCommerce_Delivery_Notes_Print();
				$this->print->load();
			}
		}
		
		/**
		 * Check if woocommerce is activated
		 *
		 * @since 1.0
		 */
		public function is_woocommerce_activated() {
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				return true;
			} else {
				return false;
			}
		}
	
	}
}

/**
 * Instance of plugin
 *
 * @since 1.0
 */
$wcdn = new WooCommerce_Delivery_Notes();
$wcdn->load();
