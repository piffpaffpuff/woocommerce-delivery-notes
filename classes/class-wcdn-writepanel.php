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
			add_action( 'add_meta_boxes_shop_order', array( $this, 'add_box' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_print_styles', array( $this, 'add_styles' ) );
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
			global $post_type;
			if( $post_type == 'shop_order' ) {
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
		 * Add bulk print action
		 */
		
		/**
		 * Add the meta box on the single order page
		 */
		public function add_box() {
			add_meta_box( 'woocommerce-delivery-notes-box', __( 'Order Print', 'woocommerce-delivery-notes' ), array( $this, 'create_box_content' ), 'shop_order', 'side', 'default' );
		}

		/**
		 * Create the meta box content on the single order page
		 */
		public function create_box_content() {
			global $post_id;
			?>
			<ul class="woocommerce-delivery-notes-actions">
				<li><a href="<?php echo wcdn_get_print_permalink( $post_id, 'invoice' ); ?>" class="button print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></a></li>
				<li><a href="<?php echo wcdn_get_print_permalink( $post_id, 'delivery-note' ); ?>" class="button print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></a></li>
			</ul>
			<span class="loading spinner"></span>
			<?php
		}
		
	}
	
}

?>