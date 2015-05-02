<?php

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * Print class
 */
if ( ! class_exists( 'WooCommerce_Delivery_Notes_Print' ) ) {

	class WooCommerce_Delivery_Notes_Print {

		public static $templates;

		public $template;
		public $template_directory_name;
		public $template_path_theme;
		public $template_path_plugin;
		public $template_url_plugin;
		
		public $api_endpoints;
		public $query_vars;

		public $order_ids;
		public $order_email;
		public $orders;
		
		/**
		 * Constructor
		 */
		public function __construct() {	
			// Define the templates
			self::$templates = apply_filters( 'wcdn_template_registration', array(
				apply_filters( 'wcdn_template_registration_invoice', array(
					'type' => 'invoice',
					'labels' => array(
						'name' => __( 'Invoice', 'woocommerce-delivery-notes' ),
						'name_plural' => __( 'Invoices', 'woocommerce-delivery-notes' ),
						'print' => __( 'Print Invoice', 'woocommerce-delivery-notes' ),
						'print_plural' => __( 'Print Invoices', 'woocommerce-delivery-notes' ),
						'message' => __( 'Invoice created.', 'woocommerce-delivery-notes' ),
						'message_plural' => __( 'Invoices created.', 'woocommerce-delivery-notes' ),
						'setting' => __( 'Enable Invoices', 'woocommerce-delivery-notes' )
					)
				) ),
				apply_filters( 'wcdn_template_registration_delivery_note', array(
					'type' => 'delivery-note',
					'labels' => array(
						'name' => __( 'Delivery Note', 'woocommerce-delivery-notes' ),
						'name_plural' => __( 'Delivery Notes', 'woocommerce-delivery-notes' ),
						'print' => __( 'Print Delivery Note', 'woocommerce-delivery-notes' ),
						'print_plural' => __( 'Print Delivery Notes', 'woocommerce-delivery-notes' ),
						'message' => __( 'Delivery Note created.', 'woocommerce-delivery-notes' ),
						'message_plural' => __( 'Delivery Notes created.', 'woocommerce-delivery-notes' ),
						'setting' => __( 'Enable Delivery Notes', 'woocommerce-delivery-notes' )
					)
				) ),
				apply_filters( 'wcdn_template_registration_receipt', array(
					'type' => 'receipt',
					'labels' => array(
						'name' => __( 'Receipt', 'woocommerce-delivery-notes' ),
						'name_plural' => __( 'Receipts', 'woocommerce-delivery-notes' ),
						'print' => __( 'Print Receipt', 'woocommerce-delivery-notes' ),
						'print_plural' => __( 'Print Receipts', 'woocommerce-delivery-notes' ),
						'message' => __( 'Receipt created.', 'woocommerce-delivery-notes' ),
						'message_plural' => __( 'Receipts created.', 'woocommerce-delivery-notes' ),
						'setting' => __( 'Enable Receipts', 'woocommerce-delivery-notes' )
					)
				) )
			) );
			
			// Default empty template
			$this->template = array(
				'type' => 'order',
				'labels' => array(
					'name' => __( 'Order', 'woocommerce-delivery-notes' ),
					'name_plural' => __( 'Orders', 'woocommerce-delivery-notes' ),
					'print' => __( 'Print Order', 'woocommerce-delivery-notes' ),
					'print_plural' => __( 'Print Orders', 'woocommerce-delivery-notes' ),
					'message' => null,
					'message_plural' => null,
					'setting' => null
				)
			);

			// Add the endpoint for the frontend
			$this->api_endpoints = array( 
				'print-order' => get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_order_page_endpoint', 'print-order' )
			);
			
			// Insert the query vars			
			$this->query_vars = array(
				'print-order-type',
				'print-order-email'
			);
			
			// Load the hooks
			add_action( 'init', array( $this, 'load_hooks' ) );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
			add_action( 'parse_request', array( $this, 'parse_request' ) );
			add_action( 'template_redirect', array( $this, 'template_redirect_theme' ) );
			add_action( 'wp_ajax_print_order', array( $this, 'template_redirect_admin' ) );
		}
		
		/**
		 * Load the init hooks
		 */
		public function load_hooks() {	
			// Define default variables
			$this->template_directory_name = apply_filters( 'wcdn_template_directory_name', 'print-order' );
			$this->template_path_theme = WC_TEMPLATE_PATH . $this->template_directory_name . '/';
			$this->template_path_plugin = WooCommerce_Delivery_Notes::$plugin_path . 'templates/' . $this->template_directory_name . '/';
			$this->template_url_plugin = WooCommerce_Delivery_Notes::$plugin_url . 'templates/' . $this->template_directory_name . '/';
			
			// Add the endpoints
			$this->add_endpoints();
		}
		
		/**
		 * Add endpoints for query vars.
		 * the endpoint is used in the front-end to
		 * generate the print template and link.
		 */
		public function add_endpoints() {
			foreach( $this->api_endpoints as $var ) {
				add_rewrite_endpoint( $var, EP_PAGES );
			}

			// Flush the rules when the transient is set.
			// This is important to make the endpoint work.
			if( get_transient( WooCommerce_Delivery_Notes::$plugin_prefix . 'flush_rewrite_rules' ) == true ) {
				delete_transient( WooCommerce_Delivery_Notes::$plugin_prefix . 'flush_rewrite_rules' );
				flush_rewrite_rules();
			}
		}

		/**
		 * Add the query vars to the url
		 */
		public function add_query_vars( $vars ) {
			foreach( $this->query_vars as $var ) {
				$vars[] = $var;
			}
		    return $vars;
		}
		
		/**
		 * Parse the query variables
		 */
		public function parse_request( $wp ) {
			// Map endpoint keys to their query var keys, when another endpoint name was set.
			foreach( $this->api_endpoints as $key => $var ) {
				if( isset( $_GET[$var] ) ) {
					$wp->query_vars[$key] = $_GET[$var];
				} elseif ( isset( $wp->query_vars[$var] ) ) {
					$wp->query_vars[$key] = $wp->query_vars[$var];
				}
			}
		}
		
		/**
		 * Template handling in the front-end
		 */
		public function template_redirect_theme() {
			global $wp;
			// Check the page url and display the template when on my-account page
			if( !empty( $wp->query_vars['print-order'] ) && is_account_page() ) {
				$type = !empty( $wp->query_vars['print-order-type'] ) ? $wp->query_vars['print-order-type'] : null;
				$email = !empty( $wp->query_vars['print-order-email'] ) ? $wp->query_vars['print-order-email'] : null;
				$this->generate_template( $wp->query_vars['print-order'], $type, $email );
				exit;
			}
		}
		
		/**
		 * Template handling in the back-end
		 */
		public function template_redirect_admin() {	
			// Let the backend only access the page
			if( is_admin() && current_user_can( 'edit_shop_orders' ) && !empty( $_REQUEST['print-order'] ) && !empty( $_REQUEST['action'] ) ) {
				$type = !empty( $_REQUEST['print-order-type'] ) ? $_REQUEST['print-order-type'] : null;
				$email = !empty( $_REQUEST['print-order-email'] ) ? $_REQUEST['print-order-email'] : null;
				$this->generate_template( $_GET['print-order'], $type, $email );
				exit;
			}
			exit;
		}
		
		/**
		 * Generate the template 
		 */
		public function generate_template( $order_ids, $template_type = 'order', $order_email = null ) {
			global $post, $wp;
			
			// Explode the ids when needed
			if( !is_array( $order_ids ) ) {
				$this->order_ids = array_filter( explode('-', $order_ids ) );
			}
			
			// Set the template 
			foreach( self::$templates as $template ) {
				if( $template_type == $template['type'] ) {
					$this->template = $template;
					break;
				}
			}
			
			// Set the email 
			if( empty( $order_email ) ) {
				$this->order_email = null;
			} else {
				$this->order_email = strtolower( $order_email );
			}
			
			// Create the orders and check permissions
			$populated = $this->populate_orders();
			
			// Only continue if the orders are populated
			if( !$populated ) {
				die();
			} 
						
			// Load the print template html
			wc_get_template( 'print-order.php', null, $this->template_path_theme, $this->template_path_plugin );
			exit;
		}
						
		/**
		 * Get print page url
		 */
		public function get_print_page_url( $order_ids, $template_type = 'order', $order_email = null, $permalink = false ) {
			// Explode the ids when needed
			if( !is_array( $order_ids ) ) {
				$order_ids = array_filter( explode( '-', $order_ids ) );
			}
			
			// Build the args
			$args = array();
			
			// Set the template type arg
			foreach( self::$templates as $template ) {
				if( $template_type == $template['type'] && $template_type != 'order' ) {
					$args = wp_parse_args( array( 'print-order-type' => $template_type ), $args );
					break;
				}
			}
			
			// Set the email arg
			if( !empty( $order_email ) ) {
				$args = wp_parse_args( array( 'print-order-email' => $order_email ), $args );
			}
			
			// Generate the url	
			$order_ids_slug = implode( '-', $order_ids );
			
			// Create another url depending on where the user prints. This
			// prevents some issues with ssl when the my-account page is 
			// secured with ssl but the admin isn't.
			if( is_admin() && current_user_can( 'edit_shop_orders' ) && $permalink == false ) {
				// For the admin we use the ajax.php for better security
				$args = wp_parse_args( array( 'action' => 'print_order' ), $args );
				$base_url = admin_url( 'admin-ajax.php' );
				$endpoint = 'print-order';
				
				// Add the order ids and create the url
				$url = add_query_arg( $endpoint, $order_ids_slug, $base_url );
			} else {				
				// For the theme
				$base_url = get_permalink( wc_get_page_id( 'myaccount' ) );
				$endpoint = $this->api_endpoints['print-order'];
				
				// The permalink function can return a faulty protocol when 
				// the front-end uses ssl but the back-end doesn't. This 
				// depends on which plugin is used for ssl. To fix this, the
				// home_url is checked for the correct protocol.
				$home_url_scheme = parse_url(home_url(), PHP_URL_SCHEME);
				$base_url_scheme = parse_url($base_url, PHP_URL_SCHEME);
				if( $base_url_scheme != $home_url_scheme ) {
					$base_url = str_replace( $base_url_scheme . '://', $home_url_scheme . '://', $base_url );
				}	
				
				// Add the order ids and create the url
				if( get_option( 'permalink_structure' ) ) {
					$url = trailingslashit( trailingslashit( $base_url ) . $endpoint . '/' . $order_ids_slug );
				} else {
					$url = add_query_arg( $endpoint, $order_ids_slug, $base_url );
				}
			}
			
			// Add all other args	
			$url = add_query_arg( $args, $url );
			
			return esc_url( $url );
		}
		
		/**
		 * Get the template url for a file. locate by file existence
		 * and then return the corresponding url.
		 */
		public function get_template_url( $name ) {			
			$child_theme_path = get_stylesheet_directory() . '/' . $this->template_path_theme;
			$child_theme_uri = get_stylesheet_directory_uri() . '/' . $this->template_path_theme;
			$theme_path = get_template_directory() . '/' . $this->template_path_theme;
			$theme_uri = get_template_directory_uri() . '/' . $this->template_path_theme;
			
			// buld the url depenind on where the file is
			if( file_exists( $child_theme_path . $name ) ) {
				$uri = $child_theme_uri . $name;
			} elseif( file_exists( $theme_path . $name ) ) {
				$uri = $theme_uri . $name;
			} else {
				$uri = $this->template_url_plugin . $name;
			}
			
			return $uri;
		}
		
		/**
		 * Create the orders list and check the permissions
		 */
		private function populate_orders() {			
			$this->orders = array();
			
			// Get the orders
			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'shop_order',
				'post_status' => 'any',
				'post__in' => $this->order_ids,
				'orderby' => 'post__in'
			);
			$posts = get_posts( $args );
			
			// All orders should exist
			if( count( $posts ) !== count( $this->order_ids ) ) {
				$this->orders = null;
				return false;
			}
			
			// Check permissons of the user to determine 
			// if the orders should be populated.
			foreach( $posts as $post ) {
				$order = new WC_Order( $post->ID );
				
				// Logged in users			
				if( is_user_logged_in() && ( !current_user_can( 'edit_shop_orders' ) && !current_user_can( 'view_order', $order->id ) ) ) {
					$this->orders = null;
					return false;
				} 

				// An email is required for not logged in users  
				if( !is_user_logged_in() && ( empty( $this->order_email ) || strtolower( $order->billing_email ) != $this->order_email ) ) {
					$this->orders = null;
					return false;
				}
				
				// Save the order to get it without an additional database call
				$this->orders[$post->ID] = $order;
			}
			return true;
		}
		
		/**
		 * Get the order
		 */
		public function get_order( $order_id ) {			
			if( isset( $this->orders[$order_id] ) ) {
				return $this->orders[$order_id];
			}
			return;	
		}	
		
		/**
		 * Get the order invoice number
		 */
		public function get_order_invoice_number( $order_id ) {						
			$invoice_start = intval( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_number_start', 1 ) );
			$invoice_counter = intval( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_number_counter', 0 ) );
			$invoice_prefix = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_number_prefix' );
			$invoice_suffix = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_number_suffix' );
	
			// Add the invoice number to the order when it doesn't yet exist
			$meta_key = '_' . WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_number';
			$meta_added = add_post_meta( $order_id, $meta_key, $invoice_prefix . ( $invoice_start + $invoice_counter ) . $invoice_suffix, true );
						
			// Update the total count
			if( $meta_added ) {
				update_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_number_counter', $invoice_counter + 1  );
			}
			
			// Get the invoice number
			return apply_filters( 'wcdn_order_invoice_number', get_post_meta( $order_id, $meta_key, true ) );
		}	
		
		/**
		 * Get the order invoice date
		 */
		public function get_order_invoice_date( $order_id ) {	
			// Add the invoice date to the order when it doesn't yet exist
			$meta_key = '_' . WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_date';
			$meta_added = add_post_meta( $order_id, $meta_key, time(), true );
	
			// Get the invoice date
			$meta_date = get_post_meta( $order_id, $meta_key, true );
			$formatted_date = date_i18n( get_option('date_format'), $meta_date );
			return apply_filters( 'wcdn_order_invoice_date', $formatted_date, $meta_date );
		}
	
	}

}

?>
