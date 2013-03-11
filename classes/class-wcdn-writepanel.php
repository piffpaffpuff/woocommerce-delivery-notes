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
			add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_listing_actions' ) );
			add_action( 'add_meta_boxes_shop_order', array( $this, 'add_box' ) );
			add_action( 'admin_print_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_print_styles', array( $this, 'add_styles' ) );
		}

		/**
		 * Add the styles
		 */
		public function add_styles() {
			if( $this->is_order_edit_page() ) {
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_style( 'woocommerce-delivery-notes', WooCommerce_Delivery_Notes::$plugin_url . 'css/style.css' );
			}
		}
		
		/**
		 * Add the scripts
		 */
		public function add_scripts() {
			if( $this->is_order_edit_page() ) {
				?>
				<script type="text/javascript">
					var show_print_preview = 'no';
				</script>
				<?php 
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'woocommerce-delivery-notes', WooCommerce_Delivery_Notes::$plugin_url . 'js/script.js', array( 'jquery', 'media-upload', 'thickbox' ) );
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
			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_print_content&template_type=invoice&order_id=' . $order->id ), 'generate_print_content' ); ?>" class="button tips print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>" data-tip="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>">
				<span><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></span>
				<img src="<?php echo WooCommerce_Delivery_Notes::$plugin_url . 'images/print-invoice.png'; ?>" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>" width="14">
			</a>
			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_print_content&template_type=delivery-note&order_id=' . $order->id ), 'generate_print_content' ); ?>" class="button tips print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>" data-tip="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>">
				<span><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></span>
				<img src="<?php echo WooCommerce_Delivery_Notes::$plugin_url . 'images/print-delivery-note.png'; ?>" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>" width="14">
			</a>
			<img src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>" class="loading" alt="">
			<?php
		}
		
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
				<li><a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_print_content&template_type=invoice&order_id=' . $post_id ), 'generate_print_content' ); ?>" class="button print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></a></li>
				<li><a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_print_content&template_type=delivery-note&order_id=' . $post_id ), 'generate_print_content' ); ?>" class="button print-preview-button" target="_blank" alt="<?php esc_attr_e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></a></li>
			</ul>
			<img src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>" class="loading" alt="">
			<?php
		}
		
	}
	
}

?>