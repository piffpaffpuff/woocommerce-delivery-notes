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
			add_filter( 'plugin_row_meta', array( $this, 'add_support_links' ), 10, 2 );			
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
				wp_enqueue_style( 'woocommerce-delivery-notes-styles', WooCommerce_Delivery_Notes::$plugin_url . 'css/style.css' );
			}
		}
		
		/**
		 * Add the scripts
		 */
		public function add_scripts() {
			if( $this->is_order_edit_page() ) {
				$settings = new WooCommerce_Delivery_Notes_Settings();
				?>
				<script type="text/javascript">
					var show_print_preview = '<?php echo $settings->get_setting( 'show_print_preview' ); ?>';
					var open_print_window = '<?php echo $settings->get_setting( 'open_print_window' ); ?>';
				</script>
				<?php 
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'woocommerce-delivery-notes-scripts', WooCommerce_Delivery_Notes::$plugin_url . 'js/script.js', array( 'jquery', 'media-upload', 'thickbox' ) );
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
			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_delivery_notes_generate_print_content&template_type=invoice&order_id=' . $order->id ), 'woocommerce_delivery_notes_generate_print_content' ); ?>" class="button print-preview-button" target="_blank"><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></a>
			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_delivery_notes_generate_print_content&template_type=delivery-note&order_id=' . $order->id ), 'woocommerce_delivery_notes_generate_print_content' ); ?>" class="button print-preview-button" target="_blank"><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></a>
			<?php
		}
		
		/**
		 * Add various support links to plugin page
		 */
		public function add_support_links( $links, $file ) {
			if ( !current_user_can( 'install_plugins' ) ) {
				return $links;
			}
		
			if ( $file == WooCommerce_Delivery_Notes::$plugin_basefile ) {
				$links[] = '<a href="http://wordpress.org/extend/plugins/woocommerce-delivery-notes/faq/" target="_new" title="' . __( 'FAQ', 'woocommerce-delivery-notes' ) . '">' . __( 'FAQ', 'woocommerce-delivery-notes' ) . '</a>';
				$links[] = '<a href="http://wordpress.org/tags/woocommerce-delivery-notes?forum_id=10" target="_new" title="' . __( 'Support', 'woocommerce-delivery-notes' ) . '">' . __( 'Support', 'woocommerce-delivery-notes' ) . '</a>';
				$links[] = '<a href="' . __( 'http://genesisthemes.de/en/donate/', 'woocommerce-delivery-notes' ) . '" target="_new" title="' . __( 'Donate', 'woocommerce-delivery-notes' ) . '">' . __( 'Donate', 'woocommerce-delivery-notes' ) . '</a>';
			}
		
			return $links;
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
				<li><a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_delivery_notes_generate_print_content&template_type=invoice&order_id=' . $post_id ), 'woocommerce_delivery_notes_generate_print_content' ); ?>" class="button print-preview-button" target="_blank"><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></a></li>
				<li><a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_delivery_notes_generate_print_content&template_type=delivery-note&order_id=' . $post_id ), 'woocommerce_delivery_notes_generate_print_content' ); ?>" class="button print-preview-button" target="_blank"><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></a></li>
			</ul>
			<?php
		}
		
	}
	
}

?>