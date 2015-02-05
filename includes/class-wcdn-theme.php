<?php

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * Frontend Theme class
 */
if ( !class_exists( 'WooCommerce_Delivery_Notes_Theme' ) ) {

	class WooCommerce_Delivery_Notes_Theme {
				
		/**
		 * Constructor
		 */
		public function __construct() {						
			// Load the hooks
			add_action( 'wp_loaded', array( $this, 'load_hooks' ) );
		}
		
		/**
		 * Load the hooks at the end when  
		 * the theme and plugins are ready.
		 */
		public function load_hooks() {	
			// hooks
			add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'create_print_button_account_page' ), 10, 2 );
			add_action( 'woocommerce_view_order', array( $this, 'create_print_button_order_page' ) );
			add_action( 'woocommerce_thankyou', array( $this, 'create_print_button_order_page' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'woocommerce_email_after_order_table', array( $this, 'add_email_print_url' ), 100, 3 );
		}

		/**
		 * Add the scripts
		 */
		public function add_scripts() {
			if ( is_account_page() || is_order_received_page() || $this->is_woocommerce_tracking_page() ) {
				wp_enqueue_script( 'woocommerce-delivery-notes-print-link', WooCommerce_Delivery_Notes::$plugin_url . 'js/jquery.print-link.js', array( 'jquery' ) );
				wp_enqueue_script( 'woocommerce-delivery-notes-theme', WooCommerce_Delivery_Notes::$plugin_url . 'js/theme.js', array( 'jquery', 'woocommerce-delivery-notes-print-link' ) );
			}
		}		
		
		/**
		 * Create a print button for the 'My Account' page
		 */
		public function create_print_button_account_page( $actions, $order ) {			
			if( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_button_on_my_account_page' ) ) {				
				// Add the print button
				$actions['print'] = array(
					'url'  => wcdn_get_print_link( $order->id, $this->get_template_type( $order ) ),
					'name' => __( 'Print', 'woocommerce-delivery-notes' )
				);
			}		
			return $actions;
		}
		
		/**
		 * Create a print button for the 'View Order' page
		 */
		public function create_print_button_order_page( $order_id ) {
			$order = new WC_Order( $order_id );
			
			// Output the button only when the option is enabled
			if( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_button_on_view_order_page' ) ) {				
				// Default button for all pages and logged in users					
				$print_url = wcdn_get_print_link( $order_id, $this->get_template_type( $order ) );
				
				// Pass the email to the url for the tracking 
				// and thank you page. This allows to view the
				// print page without logging in.
				if( $this->is_woocommerce_tracking_page() ) {
					$print_url = wcdn_get_print_link( $order_id, $this->get_template_type( $order ), $_REQUEST['order_email'] );
				}
				
				// Thank you page
				if( is_order_received_page() && !is_user_logged_in() ) {
					// Don't output the butten when there is no email
					if( !$order->billing_email ) {
						return;
					}
					$print_url = wcdn_get_print_link( $order_id, $this->get_template_type( $order ), $order->billing_email );
				}
				
				?>
				<p class="order-print">
					<a href="<?php echo $print_url; ?>" class="button print"><?php _e( 'Print', 'woocommerce-delivery-notes' ); ?></a>
				</p>
				<?php
			}
		}
				
		/**
		 * Add a print url to the emails that are sent to the customer
		 */		
		public function add_email_print_url( $order, $sent_to_admin = true, $plain_text = false ) {
			if( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'email_print_link' ) ) {				
				if( $order->billing_email && !$sent_to_admin ) {
					$url = wcdn_get_print_link( $order->id, $this->get_template_type( $order ), $order->billing_email, true );
					
					if( $plain_text ) :
echo __( 'Print your order', 'woocommerce-delivery-notes' ) . "\n\n";

echo $url . "\n";
 
echo "\n****************************************************\n\n";
					else : ?>
						<p><strong><?php _e( 'Print:', 'woocommerce-delivery-notes' ); ?></strong> <a href="<?php echo $url; ?>"><?php _e( 'Open print view in browser', 'woocommerce-delivery-notes' ); ?></a></p>
					<?php endif; 
				}
			}
		}
		
		/**
		 * Get the print button template type depnding on order status
		 */
		public function get_template_type( $order ) {
			if( $order->status == 'completed' ) {
				$type = apply_filters( 'wcdn_theme_print_button_template_type_complete_status', 'invoice' );
			} else {
				$type = apply_filters( 'wcdn_theme_print_button_template_type', 'order' );
			}			
			return $type;
		}
				
		/**
		 * Is WooCommerce 'Order Tracking' page
		 */
		public function is_woocommerce_tracking_page() {
	        return ( is_page( wc_get_page_id( 'order_tracking' ) ) && isset( $_REQUEST['order_email'] ) ) ? true : false;
		}
			
	}

}

?>