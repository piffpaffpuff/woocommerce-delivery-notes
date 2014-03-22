<?php

/**
 * Writepanel class
 */
if ( !class_exists( 'WooCommerce_Delivery_Notes_Writepanel' ) ) {

	class WooCommerce_Delivery_Notes_Writepanel {

		/**
		 * Constructor
		 */
		public function __construct() {
			// Load the hooks
			add_action( 'admin_init', array( $this, 'load_admin_hooks' ) );
		}

		/**
		 * Load the admin hooks
		 */
		public function load_admin_hooks() {				
			add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_listing_actions' ) );
			add_action( 'admin_footer-edit.php', array( $this, 'add_bulk_actions_dropdown' ) );
            add_action( 'load-edit.php', array( $this, 'handle_bulk_actions' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );
			
			add_action( 'add_meta_boxes_shop_order', array( $this, 'add_box' ) );

			/*

			if ( is_admin() ) {
 	            // Bulk edit
 	            add_action( 'admin_footer', array( $this, 'bulk_admin_footer' ), 10 );
 	            add_action( 'load-edit.php', array( $this, 'bulk_action' ) );
 			}
*/
		}

		/**
		 * Add the styles
		 */
		public function add_styles() {
			if( $this->is_order_edit_page() ) {
				wp_enqueue_style( 'woocommerce-delivery-notes-admin', WooCommerce_Delivery_Notes::$plugin_url . 'css/admin.css' );
			}
		}
		
		/**
		 * Add the scripts
		 */
		public function add_scripts() {
			if( $this->is_order_edit_page() ) {
				wp_enqueue_script( 'woocommerce-delivery-notes-print-link', WooCommerce_Delivery_Notes::$plugin_url . 'js/jquery.print-link.js', array( 'jquery' ) );
				wp_enqueue_script( 'woocommerce-delivery-notes-admin', WooCommerce_Delivery_Notes::$plugin_url . 'js/admin.js', array( 'jquery', 'woocommerce-delivery-notes-print-link' ) );
			}
		}	
			
		/**
		 * Is order page
		 */
		public function is_order_edit_page() {
			global $typenow, $post_type;
			if( $typenow == 'shop_order' || $post_type == 'shop_order' ) {
				return true;	
			} else {
				return false;
			}
		}	
			
		/**
		 * Add print actions to the orders listing
		 */
		public function add_listing_actions( $order ) {
			?>			
			<a href="<?php echo wcdn_get_print_permalink( $order->id, 'invoice' ); ?>" class="button tips print-preview-button invoice" target="_blank" alt="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>" data-tip="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>">
				<?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>
			</a>
			<a href="<?php echo wcdn_get_print_permalink( $order->id, 'delivery-note' ); ?>" class="button tips print-preview-button delivery-note" target="_blank" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>" data-tip="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>">
				<?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>
			</a>
			<span class="loading spinner"></span>
			<?php
		}
		
		/**
		 * Add bulk actions with javascript to the dropdown.
		 * This is not so pretty but WordPress does not yet
		 * offer any better solution. The JS code is in here 
		 * because we can't determine the page without 
		 * checking the post_type.
		 * https://core.trac.wordpress.org/ticket/16031
		 */
		public function add_bulk_actions_dropdown() {
			echo '<!-- foobar -->';
			/*
if( $this->is_order_edit_page() ) : ?>
				<script type="text/javascript">
					$(document).ready(function() {
						$('<option>').val('wcdn_print_order_invoice').text('<?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action']");
						$('<option>').val('wcdn_print_order_invoice').text('<?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action2']");
						
						$('<option>').val('wcdn_print_order_delivery_note').text('<?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action']");
						$('<option>').val('wcdn_print_order_delivery_note').text('<?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action2']");
					});
				</script>
			<?php endif;
*/
		}
		
		/**
		 * Add bulk print actions to the orders listing
		 */
		public function handle_bulk_actions() {
			if( $this->is_order_edit_page() ) {
				// get the action staht should be started
				$wp_list_table = _get_list_table('WP_Posts_List_Table');
				$action = $wp_list_table->current_action();
				
				// do only for specified actions
				switch ( $action ) {
					case 'wcdn_print_order_invoice':
						$template_type = 'invoice';
						$report_action = 'wcdn_printed_order_invoice';
						break;
					case 'wcdn_print_order_delivery_note':
						$template_type = 'delivery-note';
						$report_action = 'wcdn_printed_order_delivery_note';
						break;
					default:
						return;
				}
				
				// go on if there are post ids
				$post_ids = array_map( 'absint', (array) $_REQUEST['post'] );
				if( empty( $post_ids ) ) {
					return;
				}
				
				// do the action
				$changed = count( $post_ids );
				
				print_r($post_ids); 
				//wcdn_get_print_permalink( $post_ids , $template_type );
				/*
foreach ( $post_ids as $post_id ) {
					$order = new WC_Order( $post_id );
				}
*/
				
				// sendback to the same screen
				//$sendback = add_query_arg( array( 'post_type' => 'shop_order', $report_action => true, 'changed' => $changed, 'ids' => implode( ',', $post_ids ) ), '' );
				//wp_redirect( $sendback );
				exit();
		
		
			/*
	
				// security check
				check_admin_referer('bulk-posts');
				
				// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
				if(isset($_REQUEST['post'])) {
					$post_ids = array_map('intval', $_REQUEST['post']);
				}
				
				if(empty($post_ids)) return;
*/
				
			}
			
			


			
            /*
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
*/
        }

		/**
		 * Add the meta box on the single order page
		 */
		public function add_box() {
			add_meta_box( 'woocommerce-delivery-notes-box', __( 'Order Print', 'woocommerce-delivery-notes' ), array( $this, 'create_box_content' ), 'shop_order', 'side', 'low' );
		}

		/**
		 * Create the meta box content on the single order page
		 */
		public function create_box_content() {
			global $post_id;
			?>
			<a href="<?php echo wcdn_get_print_permalink( $post_id, 'invoice' ); ?>" class="button print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></a>
			<a href="<?php echo wcdn_get_print_permalink( $post_id, 'delivery-note' ); ?>" class="button print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></a>
			<span class="loading spinner"></span>
			<?php
		}
		
	}
	
}

?>