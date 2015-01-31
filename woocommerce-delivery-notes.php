<?php
/**
 * Print invoices & delivery notes for WooCommerce orders.
 *
 * Plugin Name: WooCommerce Print Invoice & Delivery Note
 * Plugin URI: https://github.com/piffpaffpuff/woocommerce-delivery-notes
 * Description: Print Invoices & Delivery Notes for WooCommerce Orders. 
 * Version: 4.1.3
 * Author: Triggvy Gunderson
 * Author URI: https://github.com/piffpaffpuff/woocommerce-delivery-notes
 * License: GPLv3 or later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: woocommerce-delivery-notes
 * Domain Path: /languages/
 *
 * Copyright 2014 Triggvy Gunderson, David Decker
 *		
 *     This file is part of WooCommerce Print Invoices & Delivery Notes,
 *     a plugin for WordPress.
 *
 *     WooCommerce Print Invoice & Delivery Note is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     WooCommerce Print Invoice & Delivery Note is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * Base class
 */
if ( !class_exists( 'WooCommerce_Delivery_Notes' ) ) {

	final class WooCommerce_Delivery_Notes {
	
		public static $plugin_version;
		public static $plugin_prefix;
		public static $plugin_url;
		public static $plugin_path;
		public static $plugin_basefile;
		public static $plugin_basefile_path;
		
		public $writepanel;
		public $settings;
		public $print;
		public $theme;

		/**
		 * Constructor
		 */
		public function __construct() {
			// Define the constants
			self::$plugin_version = '4.1.3';
			self::$plugin_prefix = 'wcdn_';
			self::$plugin_basefile_path = __FILE__;
			self::$plugin_basefile = plugin_basename( self::$plugin_basefile_path );
			self::$plugin_url = plugin_dir_url( self::$plugin_basefile );
			self::$plugin_path = trailingslashit( dirname( self::$plugin_basefile_path ) );	
			
			// Set hooks and wait for WooCommerce to load
			add_action( 'init', array( $this, 'localise' ) );
			add_action( 'woocommerce_init', array( $this, 'load' ) );
		}
		
		/**
		 * Include the main plugin classes and functions
		 */
		public function include_classes() {
			include_once( 'includes/class-wcdn-print.php' );
			include_once( 'includes/class-wcdn-settings.php' );
			include_once( 'includes/class-wcdn-writepanel.php' );
			include_once( 'includes/class-wcdn-theme.php' );
		}

		/**
		 * Function used to init Template Functions.
		 * This makes them pluggable by plugins and themes.
		 */
		public function include_template_functions() {
			include_once( 'includes/wcdn-template-functions.php' );
			include_once( 'includes/wcdn-template-hooks.php' );
		}
		
		/**
		 * Load the localisation 
		 */
		public function localise() {	
			$domain = 'woocommerce-delivery-notes';
						
			// Load language files from the wp-content/languages/plugins folder
			$mo_file = WP_LANG_DIR . '/plugins/woocommerce-delivery-notes-' . get_locale() . '.mo';
			if( is_readable( $mo_file ) ) {
				load_textdomain( $domain, $mo_file );
			}

			// Otherwise load them from the plugin folder
			load_plugin_textdomain( $domain, false, dirname( self::$plugin_basefile ) . '/languages/' );
		}
		
		/**
		 * Include the main plugin classes and functions
		 */
		public function load() {
			// WooCommerce activation required
			if ( $this->is_woocommerce_activated() ) {	
				// Include the classes	
				$this->include_classes();
							
				// Create the instances
				$this->print = new WooCommerce_Delivery_Notes_Print();
				$this->settings = new WooCommerce_Delivery_Notes_Settings();
				$this->writepanel = new WooCommerce_Delivery_Notes_Writepanel();
				$this->theme = new WooCommerce_Delivery_Notes_Theme();

				// Load the hooks for the template after the objetcs.
				// Like this the template has full access to all objects.
				add_action( 'admin_init', array( $this, 'load_admin_hooks' ) );
				add_action( 'init', array( $this, 'include_template_functions' ) );
			}
		}
			
		/**
		 * Load the admin hooks
		 */
		public function load_admin_hooks() {
			$this->update();
			
			add_filter( 'plugin_action_links_' . self::$plugin_basefile, array( $this, 'add_settings_link') );
		}
				
		/**
		 * Install or update the default settings
		 */
		public function update() {
			// Define default settings
			if( get_option( self::$plugin_prefix . 'version' ) != self::$plugin_version ) {
				// Print slug for the frontend
				$endpoint = get_option( self::$plugin_prefix . 'print_order_page_endpoint' );
				if( !$endpoint ) {
					update_option( self::$plugin_prefix . 'print_order_page_endpoint', 'print-order' );
	
					// Flush the rewrite rules when a fresh install
					set_transient( self::$plugin_prefix . 'flush_rewrite_rules', true );
				}
				
				// Template types
				foreach( WooCommerce_Delivery_Notes_Print::$templates as $template ) {
					// Enable 'invoice' and 'delivery_note' by default
					if( $template['type'] == 'invoice' || $template['type'] == 'delivery-note' ) {
						$option = get_option( self::$plugin_prefix . 'template_type_' . $template['type'] );
						if( !$option ) {
							update_option( self::$plugin_prefix . 'template_type_' . $template['type'], 1 );
						}
					}
				}
				
				// Update the settings to the latest version
				update_option( self::$plugin_prefix . 'version', self::$plugin_version );
			}
		}
		
		/**
		 * Add settings link to plugin page
		 */
		public function add_settings_link( $links ) {
			$url = esc_url( admin_url( add_query_arg( array( 'page' => 'wc-settings', 'tab' => $this->settings->tab_name ), 'admin.php' ) ) );
			$settings = sprintf( '<a href="%s" title="%s">%s</a>' , $url, __( 'Go to the settings page', 'woocommerce-delivery-notes' ) , __( 'Settings', 'woocommerce-delivery-notes' ) );
			array_unshift( $links, $settings );
			return $links;	
		}
				
		/**
		 * Check if woocommerce is activated
		 */
		public function is_woocommerce_activated() {
			$blog_plugins = get_option( 'active_plugins', array() );
			$site_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$woocommerce_basename = plugin_basename( WC_PLUGIN_FILE );
					
			if( ( in_array( $woocommerce_basename, $blog_plugins ) || isset( $site_plugins[$woocommerce_basename] ) ) && version_compare( WC_VERSION, '2.1', '>=' )) {
				return true;
			} else {
				return false;
			}
		}
		
	}
}

/**
 * Instance of the plugin
 */
$wcdn = new WooCommerce_Delivery_Notes();

?>