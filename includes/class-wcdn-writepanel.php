<?php

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * Writepanel class
 */
if ( !class_exists( 'WooCommerce_Delivery_Notes_Writepanel' ) ) {

	class WooCommerce_Delivery_Notes_Writepanel {
		
		public $enable_type_invoice;
		public $enable_type_delivery_note;
		public $enable_type_receipt;
		
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
			// Read the settings tor the types
			$this->enable_type_invoice = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'template_type_invoice' );
			$this->enable_type_delivery_note = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'template_type_delivery_note' );
			$this->enable_type_receipt = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'template_type_receipt' );
		
			// Hooks
			add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_listing_actions' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );
			
			add_action( 'add_meta_boxes_shop_order', array( $this, 'add_box' ) );

			add_action( 'admin_footer-edit.php', array( $this, 'add_bulk_actions' ) );
            add_action( 'load-edit.php', array( $this, 'load_bulk_actions' ) );
			add_action( 'admin_notices', array( $this, 'confirm_bulk_actions' ) );
		}

		/**
		 * Add the styles
		 */
		public function add_styles() {
			if( $this->is_order_edit_page() || $this->is_order_post_page() ) {
				wp_enqueue_style('thickbox');
				wp_enqueue_style( 'woocommerce-delivery-notes-admin', WooCommerce_Delivery_Notes::$plugin_url . 'css/admin.css' );
			}
		}
		
		/**
		 * Add the scripts
		 */
		public function add_scripts() {
			if( $this->is_order_edit_page() || $this->is_order_post_page() ) {
				wp_enqueue_script( 'thickbox' ); 
				wp_enqueue_script( 'woocommerce-delivery-notes-print-link', WooCommerce_Delivery_Notes::$plugin_url . 'js/jquery.print-link.js', array( 'jquery' ) );
				wp_enqueue_script( 'woocommerce-delivery-notes-admin', WooCommerce_Delivery_Notes::$plugin_url . 'js/admin.js', array( 'jquery', 'woocommerce-delivery-notes-print-link', 'thickbox' ) );
			}
		}	
			
		/**
		 * Is order edit page
		 */
		public function is_order_edit_page() {
			global $typenow, $pagenow;
			if( $typenow == 'shop_order' && $pagenow == 'edit.php' ) {
				return true;	
			} else {
				return false;
			}
		}	
		
		/**
		 * Is order edit page
		 */
		public function is_order_post_page() {
			global $typenow, $pagenow;
			if( $typenow == 'shop_order' && ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) ) {
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
			<?php if( $this->enable_type_invoice ) : ?>			
			<a href="<?php echo wcdn_get_print_link( $order->id, 'invoice' ); ?>" class="button tips print-preview-button invoice" target="_blank" alt="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>" data-tip="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>">
				<?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>
			</a>
			<?php endif; ?>
			
			<?php if( $this->enable_type_delivery_note ) : ?>			
			<a href="<?php echo wcdn_get_print_link( $order->id, 'delivery-note' ); ?>" class="button tips print-preview-button delivery-note" target="_blank" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>" data-tip="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>">
				<?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>
			</a>
			<?php endif; ?>

			<?php if( $this->enable_type_receipt ) : ?>			
			<a href="<?php echo wcdn_get_print_link( $order->id, 'receipt' ); ?>" class="button tips print-preview-button receipt" target="_blank" alt="<?php esc_attr_e( 'Print Receipt', 'woocommerce-delivery-notes' ); ?>" data-tip="<?php esc_attr_e( 'Print Receipt', 'woocommerce-delivery-notes' ); ?>">
				<?php _e( 'Print Receipt', 'woocommerce-delivery-notes' ); ?>
			</a>
			<?php endif; ?>

			<span class="print-preview-loading spinner"></span>
			<?php
		}
		
		/**
		 * Add bulk actions with javascript to the dropdown.
		 * This is not so pretty but WordPress does not yet
		 * offer any better solution. The JS code is inline 
		 * because we can't determine the page without 
		 * checking the post_type.
		 * https://core.trac.wordpress.org/ticket/16031
		 */
		public function add_bulk_actions() {
			if( $this->is_order_edit_page() ) : ?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {		
						<?php if( $this->enable_type_invoice ) : ?>
							$('<option>').val('wcdn_print_invoice').attr('title', 'invoice').text('<?php echo esc_js( __( 'Print Invoice', 'woocommerce-delivery-notes' ) ); ?>').appendTo('select[name="action"]');
							$('<option>').val('wcdn_print_invoice').attr('title', 'invoice').text('<?php echo esc_js( __( 'Print Invoice', 'woocommerce-delivery-notes' ) ); ?>').appendTo('select[name="action2"]');
						<?php endif; ?>						
						
						<?php if( $this->enable_type_delivery_note ) : ?>
							$('<option>').val('wcdn_print_delivery_note').attr('title', 'delivery-note').text('<?php echo esc_js( __( 'Print Delivery Note', 'woocommerce-delivery-notes' ) ); ?>').appendTo('select[name="action"]');
							$('<option>').val('wcdn_print_delivery_note').attr('title', 'delivery-note').text('<?php echo esc_js( __( 'Print Delivery Note', 'woocommerce-delivery-notes' ) ); ?>').appendTo('select[name="action2"]');
						<?php endif; ?>						
						
						<?php if( $this->enable_type_receipt ) : ?>
							$('<option>').val('wcdn_print_receipt').attr('title', 'receipt').text('<?php echo esc_js( __( 'Print Receipt', 'woocommerce-delivery-notes' ) ); ?>').appendTo('select[name="action"]');
							$('<option>').val('wcdn_print_receipt').attr('title', 'receipt').text('<?php echo esc_js( __( 'Print Receipt', 'woocommerce-delivery-notes' ) ); ?>').appendTo('select[name="action2"]');
						<?php endif; ?>						
					});
				</script>
			<?php endif;
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
					case 'wcdn_print_invoice':
						$template_type = 'invoice';
						$report_action = 'printed_invoice';
						break;
					case 'wcdn_print_delivery_note':
						$template_type = 'delivery-note';
						$report_action = 'printed_delivery_note';
						break;
					case 'wcdn_print_receipt':
						$template_type = 'receipt';
						$report_action = 'printed_receipt';
						break;
					default:
						return;
				}
				
				// do the action
				$post_ids = array_map( 'absint', (array) $_REQUEST['post'] );
				$total = count( $post_ids );
				$url = wcdn_get_print_link( $post_ids , $template_type );
				$sendback = add_query_arg( array( 'post_type' => 'shop_order', $report_action => true, 'total' => $total, 'print_url' => urlencode( $url ) ), '' );
				
				wp_redirect( $sendback );
				exit;
			}
        }
        
		/**
		 * Show confirmation message that orders are printed
		 */
		public function confirm_bulk_actions() {
			if( $this->is_order_edit_page() ) {
				if ( isset( $_REQUEST['printed_delivery_note'] ) || isset( $_REQUEST['printed_invoice'] ) || isset( $_REQUEST['printed_receipt'] ) ) {
					$total = isset( $_REQUEST['total'] ) ? absint( $_REQUEST['total'] ) : 0;
					
					// Confirmation message
					if( isset( $_REQUEST['printed_invoice'] ) ) {
						$message = sprintf( _n( 'Invoice created.', '%s invoices created.', $total, 'woocommerce-delivery-notes' ), number_format_i18n( $total ) );
					} elseif( isset( $_REQUEST['printed_delivery_note'] ) ) {
						$message = sprintf( _n( 'Delivery note created.', '%s delivery notes created.', $total, 'woocommerce-delivery-notes' ), number_format_i18n( $total ) );
					} elseif( isset( $_REQUEST['printed_receipt'] ) ) {
						$message = sprintf( _n( 'Receipt created.', '%s receipts created.', $total, 'woocommerce-delivery-notes' ), number_format_i18n( $total ) );
					}
					?>
					<div id="woocommerce-delivery-notes-bulk-print-message" class="updated">
						<p><?php echo $message; ?> <a href="<?php echo urldecode( $_REQUEST['print_url'] ); ?>" target="_blank" class="print-preview-button" id="woocommerce-delivery-notes-bulk-print-button"><?php _e( 'Print now', 'woocommerce-delivery-notes' ) ?></a> <span class="print-preview-loading spinner"></span></p>
					</div>
					<?php
				}
			}
		}

		/**
		 * Add the meta box on the single order page
		 */
		public function add_box() {
			add_meta_box( 'woocommerce-delivery-notes-box', __( 'Order Printing', 'woocommerce-delivery-notes' ), array( $this, 'create_box_content' ), 'shop_order', 'side', 'low' );
		}

		/**
		 * Create the meta box content on the single order page
		 */
		public function create_box_content() {
			global $post_id, $wcdn;
			?>
			<div class="print-actions">
				<?php if( $this->enable_type_invoice ) : ?>
					<a href="<?php echo wcdn_get_print_link( $post_id, 'invoice' ); ?>" class="button print-preview-button invoice" target="_blank" alt="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></a>
				<?php endif; ?>
				
				<?php if( $this->enable_type_delivery_note ) : ?>
					<a href="<?php echo wcdn_get_print_link( $post_id, 'delivery-note' ); ?>" class="button print-preview-button delivery-note" target="_blank" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></a>
				<?php endif; ?>

				<?php if( $this->enable_type_receipt ) : ?>
					<a href="<?php echo wcdn_get_print_link( $post_id, 'receipt' ); ?>" class="button print-preview-button receipt" target="_blank" alt="<?php esc_attr_e( 'Print Receipt', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Receipt', 'woocommerce-delivery-notes' ); ?></a>
				<?php endif; ?>

				<span class="print-preview-loading spinner"></span>
			</div>
			<?php 
			$create_invoice_number = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'create_invoice_number' );
			$has_invoice_number = get_post_meta( $post_id, '_' . WooCommerce_Delivery_Notes::$plugin_prefix . 'invoice_number', true );
			if( !empty( $create_invoice_number ) && $has_invoice_number ) : 
				$invoice_number = wcdn_get_order_invoice_number( $post_id );
				$invoice_date = wcdn_get_order_invoice_date( $post_id );
			?>
				<ul class="print-info">
					<li><strong><?php _e( 'Invoice number: ', 'woocommerce-delivery-notes' ); ?></strong> <?php echo $invoice_number; ?></li>
					<li><strong><?php _e( 'Invoice date: ', 'woocommerce-delivery-notes' ); ?></strong> <?php echo $invoice_date; ?></li>
				</ul>
			<?php endif; ?>
			<?php
		}
		
	}
	
}

?>