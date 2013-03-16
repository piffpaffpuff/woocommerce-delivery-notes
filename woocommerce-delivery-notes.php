<?php
/**
 * Print order invoices & delivery notes for WooCommerce shop plugin.
 * You can add company/shop info as well as personal notes & policies to print pages.
 *
 * Plugin Name: WooCommerce Print Invoices & Delivery Notes
 * Plugin URI: https://github.com/piffpaffpuff/woocommerce-delivery-notes
 * Description: Print order invoices & delivery notes for WooCommerce shop plugin. You can add company/shop info as well as personal notes & policies to print pages.
 * Version: 2.0.1
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
 */
if ( !class_exists( 'WooCommerce_Delivery_Notes' ) ) {

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
		 */
		public function __construct() {
			self::$plugin_prefix = 'wcdn_';
			self::$plugin_basefile = plugin_basename(__FILE__);
			self::$plugin_url = plugin_dir_url(self::$plugin_basefile);
			self::$plugin_path = trailingslashit(dirname(__FILE__));
		}
		
		/**
		 * Load the hooks
		 */
		public function load() {
			// load the hooks
			add_action( 'plugins_loaded', array($this, 'load_localisation') );
			add_action( 'init', array( $this, 'load_hooks' ) );
			add_action( 'admin_init', array( $this, 'load_admin_hooks' ) );
		}
	
		/**
		 * Load the main plugin classes and functions
		 */
		public function includes() {
			include_once( 'classes/class-wcdn-writepanel.php' );
			include_once( 'classes/class-wcdn-settings.php' );
			include_once( 'classes/class-wcdn-print.php' );
		}

		/**
		 * Load the localisation 
		 */
		public function load_localisation() {	
			load_plugin_textdomain( 'woocommerce-delivery-notes', false, dirname( self::$plugin_basefile ) . '/../../languages/woocommerce-delivery-notes/' );
			load_plugin_textdomain( 'woocommerce-delivery-notes', false, dirname( self::$plugin_basefile ) . '/languages' );
		}

		/**
		 * Load the init hooks
		 */
		public function load_hooks() {	
			if ( $this->is_woocommerce_activated() ) {
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
		 * Load the admin hooks
		 */
		public function load_admin_hooks() {
			if ( $this->is_woocommerce_activated() ) {
				add_filter( 'plugin_row_meta', array( $this, 'add_support_links' ), 10, 2 );			
				add_filter( 'plugin_action_links_' . self::$plugin_basefile, array( $this, 'add_settings_link') );
			}
		}
			
		/**
		 * Add various support links to plugin page
		 */
		public function add_support_links( $links, $file ) {
			if ( !current_user_can( 'install_plugins' ) ) {
				return $links;
			}
		
			if ( $file == WooCommerce_Delivery_Notes::$plugin_basefile ) {
				$links[] = '<a href="http://wordpress.org/extend/plugins/woocommerce-delivery-notes/faq/" target="_blank" title="' . __( 'FAQ', 'woocommerce-delivery-notes' ) . '">' . __( 'FAQ', 'woocommerce-delivery-notes' ) . '</a>';
				$links[] = '<a href="http://wordpress.org/support/plugin/woocommerce-delivery-notes" target="_blank" title="' . __( 'Support', 'woocommerce-delivery-notes' ) . '">' . __( 'Support', 'woocommerce-delivery-notes' ) . '</a>';
			}
			return $links;
		}
		
		/**
		 * Add settings link to plugin page
		 */
		public function add_settings_link( $links ) {
			$settings = sprintf( '<a href="%s" title="%s">%s</a>' , admin_url( 'admin.php?page=woocommerce&tab=' . $this->settings->tab_name ) , __( 'Go to the settings page', 'woocommerce-delivery-notes' ) , __( 'Settings', 'woocommerce-delivery-notes' ) );
			array_unshift( $links, $settings );
			return $links;	
		}
		
		/**
		 * Check if woocommerce is activated
		 */
		public function is_woocommerce_activated() {
			$blog_plugins = get_option( 'active_plugins', array() );
			$site_plugins = get_site_option( 'active_sitewide_plugins', array() );

			if ( in_array( 'woocommerce/woocommerce.php', $blog_plugins ) || isset( $site_plugins['woocommerce/woocommerce.php'] ) ) {
				return true;
			} else {
				return false;
			}
		}
	}
}

/**
 * Instance of plugin
 */
$wcdn = new WooCommerce_Delivery_Notes();
$wcdn->load();

/**
 * Public functions
 */
 
/**
 * Return Type of the print template
 */
if ( !function_exists( 'wcdn_get_template_type' ) ) {
	function wcdn_get_template_type() {
		global $wcdn;
		return $wcdn->print->template_type;
	}
}

/**
 * Show the template part
 */
if ( !function_exists( 'wcdn_get_template' ) ) {
	function wcdn_get_template( $name ) {
		global $wcdn;
		$wcdn->print->get_template( $name );
	}
}

/**
 * Show template  url
 */
if ( !function_exists( 'wcdn_stylesheet_url' ) ) {
	function wcdn_stylesheet_url( $name ) {
		global $wcdn;
		echo apply_filters( 'wcdn_stylesheet_url', $wcdn->print->get_template_url( $name ) );
	}
}

/**
 * Show the template title depending on type
 */
if ( !function_exists( 'wcdn_template_title' ) ) {
	function wcdn_template_title() {
		if( wcdn_get_template_type() == 'invoice' ) {
			echo apply_filters( 'wcdn_template_title', __( 'Invoice', 'woocommerce-delivery-notes' ) );
		} else {
			echo apply_filters( 'wcdn_template_title', __( 'Delivery Note', 'woocommerce-delivery-notes' ) );
		}
	}
}

/**
 * Create header
 */
if ( !function_exists( 'wcdn_head' ) ) {
	function wcdn_head() {
		?>
		<style>
			#navigation {
				font-family: sans-serif;
				background-color: #f7f7f7;
				z-index: 200;
				border-bottom: 1px solid #dfdfdf;
				left: 0;
				right: 0;
				top: 0;
				position: fixed;
				padding: 6px;
			}

			#navigation .button {
				display: inline-block;
				text-decoration: none;
				font-size: 12px;
				line-height: 23px;
				height: 24px;
				margin: 0;
				padding: 0 10px 1px;
				cursor: pointer;
				border-width: 1px;
				border-style: solid;
				-webkit-border-radius: 3px;
				-webkit-appearance: none;
				border-radius: 3px;
				white-space: nowrap;
				-webkit-box-sizing: border-box;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
				
				background: #f3f3f3;
				background-image: -webkit-gradient(linear, left top, left bottom, from(#fefefe), to(#f4f4f4));
				background-image: -webkit-linear-gradient(top, #fefefe, #f4f4f4);
				background-image: -moz-linear-gradient(top, #fefefe, #f4f4f4);
				background-image: -o-linear-gradient(top, #fefefe, #f4f4f4);
				background-image: linear-gradient(to bottom, #fefefe, #f4f4f4);
				border-color: #bbb;
			 	color: #333;
				text-shadow: 0 1px 0 #fff;
			}
			
			#navigation .button:hover,
			#navigation .button:focus {
				background: #f3f3f3;
				background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#f3f3f3));
				background-image: -webkit-linear-gradient(top, #fff, #f3f3f3);
				background-image: -moz-linear-gradient(top, #fff, #f3f3f3);
				background-image: -ms-linear-gradient(top, #fff, #f3f3f3);
				background-image: -o-linear-gradient(top, #fff, #f3f3f3);
				background-image: linear-gradient(to bottom, #fff, #f3f3f3);
				border-color: #999;
				color: #222;
			}
			
			#navigation .button:active {
				background: #eee;
				background-image: -webkit-gradient(linear, left top, left bottom, from(#f4f4f4), to(#fefefe));
				background-image: -webkit-linear-gradient(top, #f4f4f4, #fefefe);
				background-image: -moz-linear-gradient(top, #f4f4f4, #fefefe);
				background-image: -ms-linear-gradient(top, #f4f4f4, #fefefe);
				background-image: -o-linear-gradient(top, #f4f4f4, #fefefe);
				background-image: linear-gradient(to bottom, #f4f4f4, #fefefe);
				border-color: #999;
				color: #333;
				text-shadow: 0 -1px 0 #fff;
				-webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
			 	box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
			}
			
			@media print {	
				#navigation {
					display: none;
				}
			}
		</style>
		<?php
	}
}

/**
 * Create meta navigation
 */
if ( !function_exists( 'wcdn_navigation' ) ) {
	function wcdn_navigation() {
		?>
		<div id="navigation">
			<a href="#" class="button" onclick="window.print();return false;"><?php _e( 'Print', 'woocommerce-delivery-notes' ); ?></a>
		</div>
		<?php
	}
}

/**
 * Return logo id
 */
if ( !function_exists( 'wcdn_get_company_logo_id' ) ) {
	function wcdn_get_company_logo_id() {
		global $wcdn;
		return apply_filters( 'wcdn_company_logo_id', $wcdn->settings->get_setting( 'company_logo_image_id' ) );
	}
}

/**
 * Show logo html
 */
if ( !function_exists( 'wcdn_company_logo' ) ) {
	function wcdn_company_logo() {
		global $wcdn;
		$attachment_id = wcdn_get_company_logo_id();
		$company = $wcdn->settings->get_setting( 'custom_company_name' );
		if( $attachment_id ) {
			$attachment_src = wp_get_attachment_image_src( $attachment_id, 'full', false );
			
			// resize the image to a 1/4 of the original size
			// to have a printing point density of about 288ppi.
			?>
			<img src="<?php echo $attachment_src[0]; ?>" width="<?php echo $attachment_src[1] / 4; ?>" height="<?php echo $attachment_src[2] / 4; ?>" alt="<?php echo esc_attr( $company ); ?>" />
			<?php
		}
	}
}

/**
 * Return default title name of Delivery Note 
 */
if ( !function_exists( 'wcdn_company_name' ) ) {
	function wcdn_company_name() {
		global $wcdn;
		$name = trim( $wcdn->settings->get_setting( 'custom_company_name' ) );
		if( !empty( $name ) ) {
			echo apply_filters( 'wcdn_company_name', wpautop( wptexturize( $name ) ) );
		} else {
			echo apply_filters( 'wcdn_company_name', get_bloginfo( 'name' ) );
		}
	}
}

/**
 * Return shop/company info if provided
 */
if ( !function_exists( 'wcdn_company_info' ) ) {
	function wcdn_company_info() {
		global $wcdn;
		echo wpautop( wptexturize( $wcdn->settings->get_setting( 'company_address' ) ) );
	}
}

/**
 * Show billing phone
 */
if ( !function_exists( 'wcdn_billing_phone' ) ) {
	function wcdn_billing_phone() {
		global $wcdn;
		echo $wcdn->print->get_order()->billing_phone;
	}
}

/**
 * Show billing email
 */
if ( !function_exists( 'wcdn_billing_email' ) ) {
	function wcdn_billing_email() {
		global $wcdn;
		echo $wcdn->print->get_order()->billing_email;
	}
}

/**
 * Show billing address
 */
if ( !function_exists( 'wcdn_billing_address' ) ) {
	function wcdn_billing_address() {
		global $wcdn;
		$address = $wcdn->print->get_order()->get_formatted_billing_address();
		if( !$address ) {
			$address = __('N/A', 'woocommerce');
		}
		echo apply_filters( 'wcdn_billing_address', $address );
	}
}

/**
 * Show shipping address
 */
if ( !function_exists( 'wcdn_shipping_address' ) ) {
	function wcdn_shipping_address() {
		global $wcdn;
		$address = $wcdn->print->get_order()->get_formatted_shipping_address();
		if( !$address ) {
			$address = __('N/A', 'woocommerce');
		}
		echo apply_filters( 'wcdn_shipping_address', $address );
	}
}

/**
 * Show the current date
 */
if ( !function_exists( 'wcdn_date' ) ) {
	function wcdn_date() {
		echo apply_filters( 'wcdn_date', date_i18n( get_option( 'date_format' ) ) );
	}
}

/**
 * Show payment method  
 */
if ( !function_exists( 'wcdn_payment_method' ) ) {
	function wcdn_payment_method() {
		global $wcdn;
		echo apply_filters( 'wcdn_payment_method', __( $wcdn->print->get_order()->payment_method_title, 'woocommerce' ) );
	}
}

/**
 * Get order
 */
if ( !function_exists( 'wcdn_get_order' ) ) {
	function wcdn_get_order( $order_id = null ) {
		global $wcdn;
		return $wcdn->print->get_order( $order_id );
	}
}

/**
 * Get order custom field
 */
if ( !function_exists( 'wcdn_get_order_custom_field' ) ) {
	function wcdn_get_order_custom_field( $field ) {
		global $wcdn;
		return $wcdn->print->get_order_field( $field );
	}
}

/**
 * Show order number
 */
if ( !function_exists( 'wcdn_order_number' ) ) {
	function wcdn_order_number() {
		global $wcdn;

		// Trim the hash to have a clean number but still 
		// support any filters that were applied before.
		$order_number = ltrim($wcdn->print->get_order()->get_order_number(), '#');
		echo $order_number;
	}
}

/**
 * Show the order date
 */
if ( !function_exists( 'wcdn_order_date' ) ) {
	function wcdn_order_date() {
		global $wcdn;
		$order = $wcdn->print->get_order();
		echo apply_filters( 'wcdn_order_date', date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ) );
	}
}

/**
 * Return the order items
 */
if ( !function_exists( 'wcdn_get_order_items' ) ) {
	function wcdn_get_order_items() {
		global $wcdn;
		return apply_filters( 'wcdn_order_items', $wcdn->print->get_order_items() );
	}
}

/**
 * Return the order totals listing
 */
if ( !function_exists( 'wcdn_get_order_totals' ) ) {
	function wcdn_get_order_totals() {
		global $wcdn;		
		
		// get totals and remove the semicolon
		$totals = apply_filters( 'wcdn_raw_order_totals', $wcdn->print->get_order()->get_order_item_totals() );
		
		// remove the colon for every label
		foreach ( $totals as $key => $total ) {
			$label = $total['label'];
			$colon = strrpos( $label, ':' );
			if( $colon !== false ) {
				$label = substr_replace( $label, '', $colon, 1 );
			}		
			$totals[$key]['label'] = $label;
		}

		return apply_filters( 'wcdn_order_totals', $totals );
	}
}

/**
 * Return has shipping notes
 */
if ( !function_exists( 'wcdn_get_shipping_notes' ) ) {
	function wcdn_get_shipping_notes() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_order()->customer_note ) );
	}
}

/**
 * Show shipping notes
 */
if ( !function_exists( 'wcdn_shipping_notes' ) ) {
	function wcdn_shipping_notes() {
		global $wcdn;
		echo wcdn_get_shipping_notes();
	}
}

/**
 * Return personal notes, season greetings etc.
 */
if ( !function_exists( 'wcdn_get_personal_notes' ) ) {
	function wcdn_get_personal_notes() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->settings->get_setting( 'personal_notes' ) ) );
	}
}

/**
 * Show personal notes, season greetings etc.
 */
if ( !function_exists( 'wcdn_personal_notes' ) ) {
	function wcdn_personal_notes() {
		global $wcdn;
		echo wcdn_get_personal_notes();
	}
}

/**
 * Return policy for returns
 */
if ( !function_exists( 'wcdn_get_policies_conditions' ) ) {
	function wcdn_get_policies_conditions() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->settings->get_setting( 'policies_conditions' ) ) );
	}
}

/**
 * Show policy for returns
 */
if ( !function_exists( 'wcdn_policies_conditions' ) ) {
	function wcdn_policies_conditions() {
		global $wcdn;
		echo wcdn_get_policies_conditions();
	}
}

/**
 * Return shop/company footer imprint, copyright etc.
 */
if ( !function_exists( 'wcdn_get_footer_imprint' ) ) {
	function wcdn_get_footer_imprint() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->settings->get_setting( 'footer_imprint' ) ) );
	}
}

/**
 * Show shop/company footer imprint, copyright etc.
 */
if ( !function_exists( 'wcdn_footer_imprint' ) ) {
	function wcdn_footer_imprint() {
		global $wcdn;
		echo wcdn_get_footer_imprint();
	}
}

?>