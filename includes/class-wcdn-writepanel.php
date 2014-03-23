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
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );
			
			add_action( 'add_meta_boxes_shop_order', array( $this, 'add_box' ) );

			//add_action( 'admin_footer-edit.php', array( $this, 'add_bulk_actions' ) );
			//add_action( 'wp_ajax_get_print_permalink', array( $this, 'get_print_permalink_ajax' ) );
            //add_action( 'load-edit.php', array( $this, 'load_bulk_actions' ) );
			//add_action( 'admin_notices', array( $this, 'confirm_bulk_actions' ) );
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
			global $typenow;
			if( $typenow == 'shop_order' ) {
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
		public function add_bulk_actions() {
			if( $this->is_order_edit_page() ) : ?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						
						$('#doaction, #doaction2').on('click', function(event) {
							var inputs = $('#the-list .check-column input:checked');
							var select = $(this).parent().find('select');
							var action = select.val();
							if(inputs.length > 0) {
								if(action == 'wcdn_print_order_invoice' || action == 'wcdn_print_order_delivery_note') {
									// type
									var templateType = select.find(":selected").attr('title');
									
									// ids
									var orderIDs = [];
									inputs.each(function(index, element) {
										orderIDs.push($(element).val());
									});
									
									//generate the permalink	
									var data = {
										order_ids: orderIDs,
										template_type: templateType,
										action: 'get_print_permalink'
									}
																			window.open('http://localhost:8888/wordpress/my-account/print-order/166/?print-order-type=invoice');

									// handle the data
									$.post(ajaxurl, data, function(response) {
										console.log(response);
									});
									
									event.preventDefault();
								}
							}
						});	
										
						$('<option>').val('wcdn_print_order_invoice').attr('title', 'invoice').text('<?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action']");
						$('<option>').val('wcdn_print_order_invoice').attr('title', 'invoice').text('<?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action2']");
						
						$('<option>').val('wcdn_print_order_delivery_note').attr('title', 'delivery-note').text('<?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action']");
						$('<option>').val('wcdn_print_order_delivery_note').attr('title', 'delivery-note').text('<?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>').appendTo("select[name='action2']");
					});
				</script>
			<?php endif;
		}
		
		/**
		 * Load thumbnail with ajax
		 */
		public function get_print_permalink_ajax() {
			if( isset( $_POST['order_ids'] ) ) {
				echo wcdn_get_print_permalink( $_POST['order_ids'] , $_POST['template_type'] );
			}
			exit;
		}
		
		/**
		 * Add bulk print actions to the orders listing
		 */
		public function load_bulk_actions() {
			if( $this->is_order_edit_page() ) {
				// get the action staht should be started
				$wp_list_table = _get_list_table('WP_Posts_List_Table');
				$action = $wp_list_table->current_action();
								
				// stop if there are no post ids
				if( !isset( $_REQUEST['post'] ) ) {
					return;
				}
				
				// only for specified actions
				switch ( $action ) {
					case 'wcdn_print_order_invoice':
						$template_type = 'invoice';
						$report_action = 'printed_invoice';
						break;
					case 'wcdn_print_order_delivery_note':
						$template_type = 'delivery-note';
						$report_action = 'printed_delivery_note';
						break;
					default:
						return;
				}
				
				
				// do the action
				$post_ids = array_map( 'absint', (array) $_REQUEST['post'] );
				$changed = count( $post_ids );
				$permalink = wcdn_get_print_permalink( $post_ids , $template_type );
				
				/*// sendback to the same screen
				$args = array(
					'post_type' => 'shop_order', 
					$report_action => true, 
					'changed' => $changed
				);
				//wp_redirect( add_query_arg( $args ) );
				$sendback = add_query_arg( array( 'post_type' => 'shop_order', $report_action => true, 'changed' => $changed, 'ids' => implode( ',', $post_ids ) ), '' );
				wp_redirect( $permalink );
*/
				$sendback = add_query_arg( array( 'post_type' => 'shop_order', $report_action => true, 'changed' => $changed, 'ids' => join( ',', $post_ids ) ), '' );
				wp_redirect( $sendback );
				exit;
			}
        }
        
		/**
		 * Show confirmation message that orders are printed
		 */
		public function confirm_bulk_actions() {
			/*
global $post_type, $pagenow;
	
			if ( isset( $_REQUEST['marked_completed'] ) || isset( $_REQUEST['marked_processing'] ) || isset( $_REQUEST['marked_on-hold'] ) ) {
				$number = isset( $_REQUEST['changed'] ) ? absint( $_REQUEST['changed'] ) : 0;
	
				if ( 'edit.php' == $pagenow && 'shop_order' == $post_type ) {
					$message = sprintf( _n( 'Order status changed.', '%s order statuses changed.', $number, 'woocommerce' ), number_format_i18n( $number ) );
					echo '<div class="updated"><p>' . $message . '</p></div>';
				}
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