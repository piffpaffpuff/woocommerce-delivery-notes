<?php

/**
 * Print class
 */
if ( ! class_exists( 'WooCommerce_Delivery_Notes_Print' ) ) {

	class WooCommerce_Delivery_Notes_Print {

		private $template_directory_name;
		private $template_path;
		private $template_default_path;
		private $template_default_uri;

		private $order;
		
		public $api_endpoints;
		public $query_vars;
		public $template_types;
		public $template_type;
		public $order_id;

		/**
		 * Constructor
		 */
		public function __construct() {	
			// Set the default variables
			$this->template_types = array(
				'invoice',
				'delivery-note',
				'order'
			);
			
			$this->api_endpoints = array( 
				'print-order' => get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_order_page_endpoint', 'print-order' )
			);
						
			$this->query_vars = array(
				'print-order-type',
				'print-order-email'
			);
			
			// Load the hooks
			add_action( 'init', array( $this, 'load_hooks' ) );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
			add_action( 'parse_request', array( $this, 'parse_request' ) );
			add_action( 'template_redirect', array( $this, 'template_redirect' ) );
			
			/*

			if ( is_admin() ) {
 	            // Bulk edit
 	            add_action( 'admin_footer', array( $this, 'bulk_admin_footer' ), 10 );
 	            add_action( 'load-edit.php', array( $this, 'bulk_action' ) );
 			}
*/
		}
		
		/**
		 * Load the init hooks
		 */
		public function load_hooks() {	
			// Define default variables
			$this->order = new WC_Order();
			$this->template_directory_name = 'print';
			$this->template_path = WC_TEMPLATE_PATH . $this->template_directory_name . '/';
			$this->template_default_path = WooCommerce_Delivery_Notes::$plugin_path . 'templates/' . $this->template_directory_name . '/';
			$this->template_default_uri = WooCommerce_Delivery_Notes::$plugin_url . 'templates/' . $this->template_directory_name . '/';
			
			// Add the endpoints
			$this->add_endpoints();
		}
		
		/**
		 * Add endpoints for query vars
		 */
		public function add_endpoints() {
			foreach( $this->api_endpoints as $var ) {
				add_rewrite_endpoint( $var, EP_PAGES );
			}
		}

		/**
		 * Add the query vars when no permalink structures aren't supported.
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
			// Map endpoint keys to their query var keys, or get them if there is no permalink structure.
			foreach( $this->api_endpoints as $key => $var ) {
				if( isset( $_GET[$var] ) ) {
					$wp->query_vars[$key] = $_GET[$var];
				} elseif ( isset( $wp->query_vars[$var] ) ) {
					$wp->query_vars[$key] = $wp->query_vars[$var];
				}
			}
		}
		
		/**
		 * Template handling
		 */
		public function template_redirect() {
			global $wp;
				
			// Check the page url and display the template when on my-account page
			if( !empty( $wp->query_vars['print-order'] ) && is_account_page() ) {
				$this->generate_print_content_from_query();
			}
		}
		
		/**
		 * Generate the template output
		 */
		public function generate_print_content( $order_id, $template_type = 'order' ) {
			// create an array of ids
			$order_ids = $order_id;
			if( !is_array( $order_id ) ) {
				$order_ids = array( $order_id );
			}
			
			// load the html
			$this->template_type = $template_type;
			$this->order_id = $order_id;
			//$this->order->get_order( $this->order_id );
			$this->get_template( apply_filters( 'wcdn_template_file_name', 'print-order.php', $template_type, $this ) );
			
			/*
				// default template type
			if( !in_array( $template_type, $this->template_types ) ) {
				$template_type = 'order';
			}
			
			// load the html
			$this->template_type = $template_type;
			$this->order_id = $order_id;
			$this->order->get_order( $this->order_id );
			$this->get_template( apply_filters( 'wcdn_template_file_name', 'print-' . $this->template_type . '.php', $template_type, $order_id, $this ) );
			
*/
			do_action( 'wcdn_generate_print_content' );
			
			exit;
		}
		
		/**
		 * Generate the template output based on the query
		 */
		public function generate_print_content_from_query() {
			global $post, $wp;
			
			// Default id
			$order_id = $wp->query_vars['print-order'];

			// Default type 			
			if( empty( $wp->query_vars['print-order-type'] ) || !in_array( $wp->query_vars['print-order-type'], $this->template_types ) ) {
				$template_type = 'order';
			} else {
				$template_type = $wp->query_vars['print-order-type'];
			}
			
			// Default email 			
			if( empty( $wp->query_vars['print-order-email'] ) ) {
				$order_email = null;
			} else {
				$order_email = strtolower( $wp->query_vars['print-order-email'] );
			}
			
			// Order exists
			$order = new WC_Order( $order_id );	
			if( empty( $order->id ) ) {
				wp_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) );
				exit;
			}
							
			// Logged in users			
			if( is_user_logged_in() && ( !current_user_can( 'edit_shop_orders' ) && !current_user_can( 'view_order', $order_id ) ) ) {
				wp_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) );
				exit;
			} 

			// Not logged in users require an email 
			if( !is_user_logged_in() && ( empty( $order_email ) || strtolower( $order->billing_email ) != $order_email ) ) {
				wp_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) );
				exit;
			}
			
			// Generate the output
			$this->generate_print_content( $wp->query_vars['print-order'], $template_type );
			exit;
		}		
						
		/**
		 * Get print page url
		 */
		public function get_print_page_url( $order_id, $template_type = 'order', $order_email = null ) {
			// Default args
			$args = array();
			
			if( in_array( $template_type, $this->template_types ) && $template_type != 'order' ) {
				$args = wp_parse_args( array( 'print-order-type' => $template_type ), $args );
			}
			
			if( !empty( $order_email ) ) {
				$args = wp_parse_args( array( 'print-order-email' => $order_email ), $args );
			}
			
			// Generate the url	
			$permalink = get_permalink( wc_get_page_id( 'myaccount' ) );
			$endpoint = $this->api_endpoints['print-order'];

			if( get_option( 'permalink_structure' ) ) {
				$url = trailingslashit( trailingslashit( $permalink ) . $endpoint . '/' . $order_id );
			} else {
				$url = add_query_arg( $endpoint, $order_id, $permalink );
			}
				
			$url = add_query_arg( $args, $url );
			
			return $url;
		}
		
		/**
		 * Get the template url for a file. locate by file existence
		 * and then return the corresponding url.
		 */
		public function get_template_url( $name = 'order' ) {
			global $woocommerce;
				
			$uri = $this->template_default_uri . $name;
			$child_theme_path = get_stylesheet_directory() . '/' . $this->template_path;
			$child_theme_uri = get_stylesheet_directory_uri() . '/' . $this->template_path;
			$theme_path = get_template_directory() . '/' . $this->template_path;
			$theme_uri = get_template_directory_uri() . '/' . $this->template_path;
	
			if( file_exists( $child_theme_path . $name ) ) {
				$uri = $child_theme_uri . $name;
			} elseif( file_exists( $theme_path . $name ) ) {
				$uri = $theme_uri . $name;
			}

			return $uri;
		}
		
		/**
		 * Load the template file content
		 */
		public function get_template( $name ) {
			wc_get_template( $name, null, $this->template_path, $this->template_default_path );
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
					
					// Set the id
					$data['product_id'] = $item['product_id'];
					$data['variation_id'] = $item['variation_id'];
										
					// Set item name
					$data['name'] = $item['name'];
					
					// Set item quantity
					$data['quantity'] = $item['qty'];

					// Set the subtotal for the number of products
					$data['line_total'] = $item['line_total'];
					$data['line_tax'] = $item['line_tax'];
					
					// Set the final subtotal for all products
					$data['line_subtotal'] = $item['line_subtotal'];
					$data['line_subtotal_tax'] = $item['line_subtotal_tax'];
					$data['formatted_line_subtotal'] = $this->order->get_formatted_line_subtotal( $item );
					$data['price'] = $data['formatted_line_subtotal'];
					
					// Set item meta and replace it when it is empty
					$meta = new WC_Order_Item_Meta( $item['item_meta'] );	
					$data['meta'] = $meta->display( false, true );

					// Pass complete item array
	                $data['item'] = $item;
					
					// Create the product to display more info
					$data['product'] = null;
					
					$product = $this->order->get_product_from_item( $item );
					
					// Checking for existance, thanks to MDesigner0 
					if(!empty($product)) {	
						// Set the single price
						$data['single_price'] = $product->get_price();
										
						// Set item SKU
						$data['sku'] = $product->get_sku();
		
						// Set item weight
						$data['weight'] = $product->get_weight();
						
						// Set item dimensions
						$data['dimensions'] = $product->get_dimensions();
						
						// Set flag for virtual products
						$data['virtual'] = $product->is_virtual();
					
						// Pass complete product object
						$data['product'] = $product;
					}

					$data_list[] = apply_filters( 'wcdn_order_item_data', $data );
				}
			}

			return apply_filters( 'wcdn_order_items_data', $data_list );
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
		
		
		
		
		
		
		
		
		/**
		 * Add the bulk edit functions to the list
		 */
	/*
	function bulk_admin_footer() {
            global $post_type;
            
            if ( $post_type == 'shop_order' ) {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery('<option>').val('print_invoices').text('<?php _e( 'Print Invoices' ); ?>').appendTo("select[name='action']");
                        jQuery('<option>').val('print_invoices').text('<?php _e( 'Print Invoices' ); ?>').appendTo("select[name='action2']");
                    });
                </script>
                <?php
            }
        }
        
*/
        /**
		 * The action to run when the "Print Invoices" bulk action is applied
		 */
   /*
     function bulk_action() {
            $wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
            $action = $wp_list_table->current_action();
            
            switch ( $action ) {
                case 'print_invoices':
                    $report_action = 'printed_invoices';
                    $changed = 0;
                
                    $post_ids = array_map( 'absint', (array)$_REQUEST['post'] );
                    
                    foreach ( $post_ids as $post_id ) {
                        
                        // Print invoice for each order
                        $this->template_type = 'invoice';
                        $this->order = new WC_Order();
						$this->order_id = $post_id;
						$this->order->get_order( $this->order_id );
						$this->get_template( apply_filters( 'wcdn_template_file_name', 'print-' . $this->template_type . '.php', $template_type, $order_id, $this ) );
						do_action( 'wcdn_generate_print_content' );
                        
                        $changed++;
                    }
            
                    //$sendback = add_query_arg( array( 'post_type' => 'shop_order', $report_action => true, 'changed' => $changed, 'ids' => join( ',', $post_ids ) ), '' );
                    //wp_redirect( $sendback );
                    
                    exit();
                break;
                default:
                    return;
            }
        }
*/
	
		
		
		
		
		
	}

}

?>