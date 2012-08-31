<?php

/**
 * Print class
 */
if ( ! class_exists( 'WooCommerce_Delivery_Notes_Print' ) ) {

	class WooCommerce_Delivery_Notes_Print {

		private $template_directory_name;
		private $template_path;
		private $template_default_path;
		private $template_uri;
		private $template_default_uri;
		private $order;
		
		public $template_type;
		public $order_id;

		/**
		 * Constructor
		 */
		public function __construct() {					
			global $woocommerce;
			$this->order = new WC_Order();
			$this->template_directory_name = 'print';
			$this->template_path = $woocommerce->template_url . $this->template_directory_name . '/';
			$this->template_default_path = WooCommerce_Delivery_Notes::$plugin_path . 'templates/' . $this->template_directory_name . '/';
			$this->template_uri = get_template_directory_uri() . '/' . $this->template_path;
			$this->template_default_uri = WooCommerce_Delivery_Notes::$plugin_url . 'templates/' . $this->template_directory_name . '/';
		}
		
		/**
		 * Load the class
		 */
		public function load() {
			add_action( 'admin_init', array( $this, 'load_hooks' ) );
		}

		/**
		 * Load the admin hooks
		 */
		public function load_hooks() {	
			add_action('wp_ajax_generate_print_content', array($this, 'generate_print_content_ajax'));
		}

		/**
		 * Generate the template output
		 */
		public function generate_print_content( $template_type, $order_id ) {
			$this->template_type = $template_type;
			$this->order_id = $order_id;
			$this->order->get_order( $this->order_id );
			$this->get_template( 'print-' . $this->template_type . '.php' );
		}
		
		/**
		 * Load and generate the template output with ajax
		 */
		public function generate_print_content_ajax() {		
			// Let the admin only access the page
			if( !is_admin() ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			// Check the user privileges and nonce
			if( !current_user_can( 'manage_woocommerce_orders' ) || empty( $_GET['action'] ) || !check_admin_referer( $_GET['action'] ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			// Check if all parameters are set
			if( empty( $_GET['template_type'] ) || empty( $_GET['order_id'] ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			// Generate the output
			$this->generate_print_content( $_GET['template_type'], $_GET['order_id'] );
			
			exit;
		}
		
		/**
		 * Get the template url for a file
		 */
		public function get_template_url( $name ) {
			global $woocommerce;
			$url = $this->template_uri;
			$template = locate_template(
				array(
					$this->template_path . $name
				)
			);
			
			// Set the url to the theme directory
			if( !$template ) {
				$url = $this->template_default_uri;
			}
			
			return $url . $name;
		}
		
		/**
		 * Load the template file content
		 */
		public function get_template( $name ) {
			woocommerce_get_template( $name, null, $this->template_path, $this->template_default_path );
		}
							
		/**
		 * Get the current order
		 */
		public function get_order() {
			return $this->order;
		}

		/**
		 * Get the current order items
		 */
		public function get_order_items() {
			global $woocommerce;
			global $_product;

			$items = $this->order->get_items();
			$data_list = array();
		
			if( sizeof( $items ) > 0 ) {
				foreach ( $items as $item ) {
					// Array with data for the printing template
					$data = array();
					
					// Create the product
					$product = $this->order->get_product_from_item( $item );

					// Set the variation
					if( isset( $item['variation_id'] ) && $item['variation_id'] > 0 ) {
						$data['variation'] = woocommerce_get_formatted_variation( $product->get_variation_attributes() );
					} else {
						$data['variation'] = null;
					}
					
					// Set item meta and replace it when it is empty
					$meta = new WC_Order_Item_Meta( $item['item_meta'] );	
					$data['meta'] = $meta->display( false, true );
					if( empty( $data['meta'] ) ) {
						$data['meta'] = $data['variation'];
					}
										
					// Set item name
					$data['name'] = $item['name'];
					
					// Set item quantity
					$data['quantity'] = $item['qty'];
															
					// Set item download url									
					if( $product->exists() && $product->is_downloadable() && $product->has_file() && ( $this->order->status == 'completed' || ( get_option( 'woocommerce_downloads_grant_access_after_payment' ) == 'yes' && $this->order->status == 'processing' ) ) ) {
						$data['download_url'] = $this->order->get_downloadable_file_url( $item['id'], $item['variation_id'] );
					} else {
						$data['download_url'] = null;
					}

					// Set the price
					$data['price'] = $this->order->get_formatted_line_subtotal( $item );
									
					// Set the single price
					$data['single_price'] = $product->get_price();
									
					// Set item SKU
					$data['sku'] = $product->get_sku();
	
					// Set item weight
					$data['weight'] = $product->get_weight();
					
					// Set item dimensions
					$data['dimensions'] = $product->get_dimensions();
										
	                // Pass complete item array
	                $data['item'] = $item;

					// Pass complete product object
	                $data['product'] = $product;
					
					$data_list[] = $data;
				}
			}

			return $data_list;
		}
		
		/**
		 * Get order custom field
		 */
		public function get_order_field( $field ) {
			if( isset( $this->get_order()->order_custom_fields[$field] ) ) {
				return $this->get_order()->order_custom_fields[$field][0];
			} 
			return;
		}
	}

}

?>