<?php

/**
 * Print class
 *
 * @since 1.0
 */
if ( ! class_exists( 'WooCommerce_Delivery_Notes_Print' ) ) {

	class WooCommerce_Delivery_Notes_Print {

		public $template_url;
		public $template_dir;
		public $template_base;
		public $template_name;
		public $theme_base;
		public $theme_path;
		public $order_id;

		private $order;

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {					
		}
		
		/**
		 * Load the class
		 *
		 * @since 1.0
		 */
		public function load( $order_id = 0 ) {
			global $woocommerce;
			
			$this->order_id = $order_id;
			$this->template_name = 'delivery-note';
			$this->template_base = 'templates/';
			$this->theme_base = $woocommerce->template_url;
			$this->template_dir = 'delivery-notes/';
			$this->theme_path = trailingslashit( get_stylesheet_directory() ); 
			
			if ( $this->order_id > 0 ) {
				$this->order = new WC_Order( $this->order_id );
			}			
		}

		/**
		 * Load the admin hooks
		 *
		 * @since 1.0
		 */
		public function load_hooks() {
		}

		/**
		 * Read the template file
		 *
		 * @since 1.0
		 */
		public function get_print_page( $template_name = 'delivery-note' ) {
			$this->template_name = $template_name;
			return $this->get_template_content( 'print', $this->template_name );
		}

		/**
		 * Read the template file
		 *
		 * @since 1.0
		 */
		private function get_template_content( $slug, $name = '' ) {
			$template = null;
			$template_file = null;
			
			// Look in yourtheme/woocommerce/delivery-notes/
			$template_file = $this->theme_path . $this->theme_base . $this->template_dir . $slug.'-'.$name.'.php';
			if ( !$template && $name && file_exists( $template_file) ) {
				$template = $template_file;
				$this->template_url = trailingslashit( get_stylesheet_directory_uri() ) . $this->theme_base . $this->template_dir;
			} 
						
			// Fall back to slug.php in yourtheme/woocommerce/delivery-notes/			
			$template_file = $this->theme_path . $this->theme_base . $this->template_dir . $slug.'.php';
			if ( !$template && file_exists( $template_file ) ) {
				$template = $template_file;
				$this->template_url = trailingslashit( get_stylesheet_directory_uri() ) . $this->theme_base . $this->template_dir;
			}
			
			// Legacy support for old custom template folder structure
			$template_file = $this->theme_path . $this->theme_base . 'delivery-note-template/template.php';
			if ( !$template && file_exists( $template_file ) ) {
				$template = $template_file;
				$this->template_url = trailingslashit( get_stylesheet_directory_uri() ) . 'delivery-note-template/';
			}
			
			// Look in pluginname/templates/delivery-notes/
			$template_file = WooCommerce_Delivery_Notes::$plugin_path . $this->template_base . $this->template_dir . $slug.'-'.$name.'.php';
			if ( !$template && $name && file_exists( $template_file ) ) {
				$template = $template_file;
				$this->template_url = WooCommerce_Delivery_Notes::$plugin_url . $this->template_base . $this->template_dir;
			}

			// Fall back to slug.php in pluginname/templates/delivery-notes/			
			$template_file = WooCommerce_Delivery_Notes::$plugin_path . $this->template_base . $this->template_dir . $slug.'.php';
			if ( !$template && file_exists( $template_file ) ) {
				$template = $template_file;
				$this->template_url = WooCommerce_Delivery_Notes::$plugin_url . $this->template_base . $this->template_dir;
			}
			
			// Return the content of the template
			if ( $template ) {
				ob_start();
				require_once( $template );
				$content = ob_get_clean();
				return $content;
			}
			
			// Return no content when no file was found
			return;
		}
		
		/**
		 * Get the current order
		 *
		 * @since 1.0
		 */
		public function get_order() {
			return $this->order;
		}

		/**
		 * Get the current order items
		 *
		 * @since 1.0
		 * @version 1.1
		 */
		public function get_order_items() {
			global $woocommerce;
			global $_product;

			if(!$this->order) {
				return;
			}

			$items = $this->order->get_items();
			$data_list = array();
		
			if ( sizeof( $items ) > 0 ) {
				foreach ( $items as $item ) {
					// Array with data for the printing template
					$data = array();
					
					// Create the product
					if ( isset( $item['variation_id'] ) && $item['variation_id'] > 0 ) {
						$product = new WC_Product_Variation( $item['variation_id'] );
						$data['variation'] = woocommerce_get_formatted_variation( $product->get_variation_attributes(), true );
					} else {
						$product = new WC_Product( $item['id'] );
						$data['variation'] = null;
					}
					
					// Set item name
					$data['name'] = $item['name'];
					
					// Set item quantity
					$data['quantity'] = $item['qty'];
					
					// Set item meta
					$meta = new order_item_meta( $item['item_meta'] );
					$data['meta'] = $meta->display(true, true);
					
					// Set item download url
					$data['download_url'] = null;
					if ( $product->exists && $product->is_downloadable() && $this->order->status == 'completed' ) {
						$data['download_url'] = $this->order->get_downloadable_file_url( $item['id'], $item['variation_id'] );
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

					// Get item tax rate
					$data['taxrate'] = $item['taxrate'];

					// Get line total price including tax
					$data['price_with_tax'] = woocommerce_price( ( $item['line_total'] + $item['line_tax'] ), array( 'ex_tax_label' => 0 ) );

					// Get single item price including tax
					$data['single_price_with_tax'] = woocommerce_price( ( $item['line_total'] + $item['line_tax'] ) / $item['qty'], array( 'ex_tax_label' => 0 ) );

					// Pass complete item array - more freedom for template developers
					$data['item'] = $item;
					
	                // Pass complete item array - more freedom for template developers
	                $data['item'] = $item;
					
					$data_list[] = $data;
				}
			}

			return $data_list;
		}

		/**
		 * Get the content for an option
		 *
		 * @since 1.0
		 */
		public function get_setting( $name ) {
			return get_option( WooCommerce_Delivery_Notes::$plugin_prefix . $name );
		}
	
	}

}
