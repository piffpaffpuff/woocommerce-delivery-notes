<?php

/**
 * Print class
 */
if ( ! class_exists( 'WooCommerce_Delivery_Notes_Print' ) ) {

	class WooCommerce_Delivery_Notes_Print {

		public $template_directory_name;
		public $template_path_theme;
		public $template_path_plugin;
		public $template_url_plugin;
		
		public $api_endpoints;
		public $query_vars;
		public $template_types;
		public $template_type;

		public $order_ids;
		public $order_email;
		public $orders;
		
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
		}
		
		/**
		 * Load the init hooks
		 */
		public function load_hooks() {	
			// Define default variables
			$this->template_directory_name = 'print-order';
			$this->template_path_theme = WC_TEMPLATE_PATH . $this->template_directory_name . '/';
			$this->template_path_plugin = WooCommerce_Delivery_Notes::$plugin_path . 'templates/' . $this->template_directory_name . '/';
			$this->template_url_plugin = WooCommerce_Delivery_Notes::$plugin_url . 'templates/' . $this->template_directory_name . '/';
			
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
				$this->generate_template();
			}
		}
		
		/**
		 * Generate the template 
		 */
		public function generate_template( $template_type = 'order' ) {
			global $post, $wp;
			
			// Explode the ids when needed
			if( !is_array( $wp->query_vars['print-order'] ) ) {
				$this->order_ids = array_filter( explode('-', $wp->query_vars['print-order'] ) );
			}
			
			// Default type 			
			if( empty( $wp->query_vars['print-order-type'] ) || !in_array( $wp->query_vars['print-order-type'], $this->template_types ) ) {
				$this->template_type = 'order';
			} else {
				$this->template_type = $wp->query_vars['print-order-type'];
			}
			
			// Default email 
			if( empty( $wp->query_vars['print-order-email'] ) ) {
				$this->order_email = null;
			} else {
				$this->order_email = strtolower( $wp->query_vars['print-order-email'] );
			}
			
			// Create the orders and check permissions
			$populated = $this->populate_orders();
			
			// Only continue if the orders are populated
			if( !$populated ) {
				wp_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) );
				exit;
			} 
						
			// Load the print template html
			wc_get_template( 'print-order.php', null, $this->template_path_theme, $this->template_path_plugin );
			exit;
		}
						
		/**
		 * Get print page url
		 */
		public function get_print_page_url( $order_ids, $template_type = 'order', $order_email = null ) {
			// Explode the ids when needed
			if( !is_array( $order_ids ) ) {
				$order_ids = array_filter( explode( '-', $order_ids ) );
			}

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
			$order_ids_slug = implode( '-', $order_ids );
			
			if( get_option( 'permalink_structure' ) ) {
				$url = trailingslashit( trailingslashit( $permalink ) . $endpoint . '/' . $order_ids_slug );
			} else {
				$url = add_query_arg( $endpoint, $order_ids_slug, $permalink );
			}
				
			$url = add_query_arg( $args, $url );
			
			return $url;
		}
		
		/**
		 * Get the template url for a file. locate by file existence
		 * and then return the corresponding url.
		 */
		public function get_template_url( $name ) {
			global $woocommerce;
			
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
				'post_type' => 'shop_order',
				'post_status' => 'publish',
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
				if( is_user_logged_in() && ( !current_user_can( 'edit_shop_orders' ) && !current_user_can( 'view_order', $order->ID ) ) ) {
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