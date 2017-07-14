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

		public static $template_registrations;
		public static $template_styles;

		public $template_locations;
		public $template;

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
			self::$template_registrations = apply_filters( 'wcdn_template_registration', array(
				apply_filters( 'wcdn_template_registration_invoice', array(
					'type' => 'invoice',
					'labels' => array(
						'name' => __( 'Invoice', 'woocommerce-delivery-notes' ),
						'name_plural' => __( 'Invoices', 'woocommerce-delivery-notes' ),
						'print' => __( 'Print Invoice', 'woocommerce-delivery-notes' ),
						'print_plural' => __( 'Print Invoices', 'woocommerce-delivery-notes' ),
						'message' => __( 'Invoice created.', 'woocommerce-delivery-notes' ),
						'message_plural' => __( 'Invoices created.', 'woocommerce-delivery-notes' ),
						'setting' => __( 'Show "Print Invoice" button', 'woocommerce-delivery-notes' )
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
						'setting' => __( 'Show "Print Delivery Note" button', 'woocommerce-delivery-notes' )
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
						'setting' => __( 'Show "Print Receipt" button', 'woocommerce-delivery-notes' )
					)
				) )
			) );
			
			// Add the default template as first item after filter hooks passed
			array_unshift( self::$template_registrations, array(
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
			) );

			// Template styles
			self::$template_styles = apply_filters( 'wcdn_template_styles', array() );
			
			// Add the default style as first item after filter hooks passed
			array_unshift( self::$template_styles, array(
				'name' => __( 'Default', 'woocommerce-delivery-notes' ),
				'type' => 'default',
				'path' => WooCommerce_Delivery_Notes::$plugin_path . 'templates/print-order/',
				'url' => WooCommerce_Delivery_Notes::$plugin_url . 'templates/print-order/'
			) );

			// Default template
			$this->template = self::$template_registrations[0];
			
			// Build all template locations
			$this->template_locations = $this->build_template_locations();

			// Add the endpoint for the frontend
			$this->api_endpoints = array( 
				'print-order' => get_option( 'wcdn_print_order_page_endpoint', 'print-order' )
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
			if( get_transient( 'wcdn_flush_rewrite_rules' ) == true ) {
				delete_transient( 'wcdn_flush_rewrite_rules' );
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
		 * Build the template locations
		 */
		public function build_template_locations() {
			$wc_template_directory = WC_TEMPLATE_PATH . 'print-order/';
			
			// Get the paths for custom styles
			$settings_type = get_option( 'wcdn_template_style' );
			$settings_path = null;
			$settings_url = null;
			if( isset( $settings_type ) && $settings_type !== 'default' ) {
				foreach( self::$template_styles as $template_style ) {
					if( $settings_type === $template_style['type'] ) {
						$settings_path = $template_style['path'];
						$settings_url = $template_style['url'];
						break;
					}
				}
			}
			
			// Build the locations
			$locations = array(
				'child_theme' => array(
					'path' => trailingslashit( get_stylesheet_directory() ) . $wc_template_directory,
					'url' => trailingslashit( get_stylesheet_directory_uri() ) . $wc_template_directory
				),
				
				'theme' => array(
					'path' => trailingslashit( get_template_directory() ) . $wc_template_directory,
					'url' => trailingslashit( get_template_directory_uri() ) . $wc_template_directory
				),
				
				'settings' => array(
					'path' => $settings_path,
					'url' => $settings_url
				),
				
				'plugin' => array(
					'path' => self::$template_styles[0]['path'],
					'url' => self::$template_styles[0]['url']
				)
			);					
		
			return $locations;
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
			
			// Set the current template 
			foreach( self::$template_registrations as $template_registration ) {
				if( $template_type == $template_registration['type'] ) {
					$this->template = $template_registration;
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
			$location = $this->get_template_file_location( 'print-order.php' );
			wc_get_template( 'print-order.php', null, $location, $location );
			exit;
		}
		
		/**
		 * Find the location of a template file 
		 */
		public function get_template_file_location( $name, $url_mode = false ) {
			$found = '';
			foreach( $this->template_locations as $template_location ) {
				if( isset( $template_location['path'] ) && file_exists( trailingslashit( $template_location['path'] ) . $name ) ) {
					if( $url_mode ) {
						$found = $template_location['url'];
					} else {
						$found = $template_location['path'];
					}
					break;
				} 
			}
			return $found;
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
			foreach( self::$template_registrations as $template_registration ) {
				if( $template_type == $template_registration['type'] && $template_type != 'order' ) {
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
				$base_url = wc_get_page_permalink( 'myaccount' );
				$endpoint = $this->api_endpoints['print-order'];
								
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
				
				$wdn_order_id =  ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">="  ) ) ? $order->get_id() : $order->id;
				// Logged in users			
				if( is_user_logged_in() && ( !current_user_can( 'edit_shop_orders' ) && !current_user_can( 'view_order', $wdn_order_id ) ) ) {
					$this->orders = null;
					return false;
				} 

                $wdn_order_billing_id  =  ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">="  ) ) ? $order->get_billing_email() : $order->billing_email;
                
                
				// An email is required for not logged in users  
				if( !is_user_logged_in() && ( empty( $this->order_email ) || strtolower( $wdn_order_billing_id ) != $this->order_email ) ) {
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
			$invoice_count = intval( get_option( 'wcdn_invoice_number_count', 1 ) );
			$invoice_prefix = get_option( 'wcdn_invoice_number_prefix' );
			$invoice_suffix = get_option( 'wcdn_invoice_number_suffix' );
	
			// Add the invoice number to the order when it doesn't yet exist
			$meta_key = '_wcdn_invoice_number';
			$meta_added = add_post_meta( $order_id, $meta_key, $invoice_prefix . $invoice_count . $invoice_suffix, true );
						
			// Update the total count
			if( $meta_added ) {
				update_option( 'wcdn_invoice_number_count', $invoice_count + 1  );
			}
			
			// Get the invoice number
			return apply_filters( 'wcdn_order_invoice_number', get_post_meta( $order_id, $meta_key, true ) );
		}	
		
		/**
		 * Get the order invoice date
		 */
		public function get_order_invoice_date( $order_id ) {	
			// Add the invoice date to the order when it doesn't yet exist
			$meta_key = '_wcdn_invoice_date';
			$meta_added = add_post_meta( $order_id, $meta_key, time(), true );
	
			// Get the invoice date
			$meta_date = get_post_meta( $order_id, $meta_key, true );
			$formatted_date = date_i18n( get_option('date_format'), $meta_date );
			return apply_filters( 'wcdn_order_invoice_date', $formatted_date, $meta_date );
		}
	
	}

}

?>
