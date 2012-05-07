<?php

/**
 * Writepanel class
 *
 * @since 1.0
 */
if ( !class_exists( 'WooCommerce_Delivery_Notes_Writepanel' ) ) {

	class WooCommerce_Delivery_Notes_Writepanel {

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
		public function load() {
			add_action( 'admin_init', array( $this, 'load_hooks' ) );
		}

		/**
		 * Load the admin hooks
		 *
		 * @since 1.0
		 */
		public function load_hooks() {	
			add_filter( 'plugin_row_meta', array( $this, 'add_support_links' ), 10, 2 );			
			add_action( 'add_meta_boxes_shop_order', array( $this, 'add_box' ) );
			add_action( 'admin_print_styles-post.php', array( $this, 'print_styles' ) );
		}

		/**
		 * Load the styles
		 *
		 * @since 1.0
		 */
		public function print_styles() {
			global $post_type;
			
			if ( $post_type == 'shop_order' ) {
				wp_enqueue_style( 'woocommerce-delivery-notes-styles', WooCommerce_Delivery_Notes::$plugin_url . 'css/style.css' );
			}
		}
		
		/**
		 * Add various support links to plugin page
		 *
		 * @since 1.0
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
		 *
		 * @since 1.0
		 */
		public function add_box() {
			add_meta_box( 'woocommerce-delivery-notes-box', __( 'Order Print', 'woocommerce-delivery-notes' ), array( $this, 'create_box_content' ), 'shop_order', 'side', 'default' );
		}

		/**
		 * Create the meta box content on the single order page
		 *
		 * @since 1.0
		 */
		public function create_box_content() {
			global $post_id;

			?>
			<ul class="woocommerce-delivery-notes-actions">
				<li><a href="<?php echo WooCommerce_Delivery_Notes::$plugin_url; ?>woocommerce-delivery-notes-print.php?order=<?php echo $post_id; ?>&name=invoice" id="woocommerce-delivery-notes-print-invoice" class="button button" target="_blank"><?php _e( 'Print Invoice', 'woocommerce-delivery-notes' ); ?></a></li>
				<li><a href="<?php echo WooCommerce_Delivery_Notes::$plugin_url; ?>woocommerce-delivery-notes-print.php?order=<?php echo $post_id; ?>&name=delivery-note" id="woocommerce-delivery-notes-print-delivery-note" class="button button" target="_blank"><?php _e( 'Print Delivery Note', 'woocommerce-delivery-notes' ); ?></a></li>
			</ul>
			<?php
		}
		
	}
	
}
