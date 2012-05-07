<?php
/**
 * Load all available data for the Delivery Notes printing page.
 */
$id = $_GET['order'];
$name = $_GET['name'];

/**
 * Load Wordpress to use its functions
 *
 * @since 1.0
 */
if ( !defined( 'ABSPATH' ) ) {
	require_once '../../../wp-load.php';
}

/**
 * Check the current user capabilities
 *
 * @since 1.0
 */
if (!current_user_can('manage_woocommerce_orders') || empty($id) || empty($name)) {
	wp_die( __( 'You do not have sufficient permissions to access this page.', 'woocommerce-delivery-notes' ) );
}

/**
 * Load the order
 *
 * @since 1.0
 */
$wcdn->print->load( $id );

/**
 * Return Delivery Note template url
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_url' ) ) {
	function wcdn_template_url() {
		global $wcdn;
		return $wcdn->print->template_url;
	}
}

/**
 * Return Type of print
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_name' ) ) {
	function wcdn_template_name() {
		global $wcdn;
		return $wcdn->print->template_name;
	}
}

/**
 * Return javascript
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_javascript' ) ) {
	function wcdn_template_javascript() {
		global $wcdn;
		
		$js = '<script type="text/javascript">
			function openPrintWindow() {
		    	window.print();
		    	return false;
			}';
		
		if( checked( $wcdn->print->get_setting( 'open_print_window' ), 'yes', false ) ) {
			$js .= 'window.onload = openPrintWindow;';
		}
		
		$js .= '</script>';
		
		return $js;
	}
}

/**
 * Return print button
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_print_button' ) ) {
	function wcdn_template_print_button() {
		?>
		<a href="#print" onclick="javascript:openPrintWindow();return false;"><?php _e( 'Print Page', 'woocommerce-delivery-notes' ); ?></a>
		<?php
	}
}
	
/**
 * Return default title name of Delivery Note 
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_company_name' ) ) {
	function wcdn_company_name() {
		global $wcdn;
		$name = trim( $wcdn->print->get_setting( 'custom_company_name' ) );
		if( !empty( $name ) ) {
			return wpautop( $name );
		} else {
			return get_bloginfo( 'name' );
		}
	}
}

/**
 * Return shop/company info if provided
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string company address
 */
if ( ! function_exists( 'wcdn_company_info' ) ) {
	function wcdn_company_info() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_setting( 'company_address' ) ) );
	}
}

/**
 * Return shipping name
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping name
 */
if ( ! function_exists( 'wcdn_shipping_name' ) ) {
	function wcdn_shipping_name() {
		global $wcdn;
		return $wcdn->print->get_order()->shipping_first_name . ' ' . $wcdn->print->get_order()->shipping_last_name;
	}
}

/**
 * Return shipping company
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping company
 */
if ( ! function_exists( 'wcdn_shipping_company' ) ) {
	function wcdn_shipping_company() {
		global $wcdn;
		return $wcdn->print->get_order()->shipping_company;
	}
}

/**
 * Return shipping address 1
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping address
 */
if ( ! function_exists( 'wcdn_shipping_address_1' ) ) {
	function wcdn_shipping_address_1() {
		global $wcdn;
		return $wcdn->print->get_order()->shipping_address_1;
	}
}

/**
 * Return shipping address 2
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping address 2
 */
if ( ! function_exists( 'wcdn_shipping_address_2' ) ) {
	function wcdn_shipping_address_2() {
		global $wcdn;
		return $wcdn->print->get_order()->shipping_address_2;
	}
}

/**
 * Return shipping city
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping city
 */
if ( ! function_exists( 'wcdn_shipping_city' ) ) {
	function wcdn_shipping_city() {
		global $wcdn;
		return $wcdn->print->get_order()->shipping_city;
	}
}

/**
 * Return shipping state
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping state
 */
if ( ! function_exists( 'wcdn_shipping_state' ) ) {
	function wcdn_shipping_state() {
		global $wcdn;
		return $wcdn->print->get_order()->shipping_state;
	}
}

/**
 * Return shipping postcode
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping postcode
 */
if ( ! function_exists( 'wcdn_shipping_postcode' ) ) {
	function wcdn_shipping_postcode() {
		global $wcdn;
		return $wcdn->print->get_order()->shipping_postcode;
	}
}

/**
 * Return shipping country
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping country
 */
if ( ! function_exists( 'wcdn_shipping_country' ) ) {
	function wcdn_shipping_country() {
		global $wcdn, $woocommerce;
		$country = $wcdn->print->get_order()->shipping_country;
		$full_country = ( isset( $woocommerce->countries->countries[$country] ) ) ? $woocommerce->countries->countries[$country] : $country;
		return $full_country;
	}
}

/**
 * Return shipping notes
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string shipping notes
 */
if ( ! function_exists( 'wcdn_shipping_notes' ) ) {
	function wcdn_shipping_notes() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_order()->customer_note ) );
	}
}

/**
 * Return billing phone
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string billing phone
 */
if ( ! function_exists( 'wcdn_billing_phone' ) ) {
	function wcdn_billing_phone() {
		global $wcdn;
		return $wcdn->print->get_order()->billing_phone;
	}
}

/**
 * Return order id
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string order id
 */
if ( ! function_exists( 'wcdn_order_number' ) ) {
	function wcdn_order_number() {
		global $wcdn;
		$before = trim( $wcdn->print->get_setting( 'before_order_number' ) );
		$after = trim( $wcdn->print->get_setting( 'after_order_number' ) );
		$offset = trim( $wcdn->print->get_setting( 'order_number_offset' ) );

		// try to get custom order number as provided by the plugin
		// http://wordpress.org/extend/plugins/woocommerce-sequential-order-numbers/
		$order_id     = $wcdn->print->order_id;
		$order_number = $wcdn->print->get_order()->order_custom_fields['_order_number'][0];
		
		// if custom order number is zero, fall back to ID
		if ( intval($order_number) != 0 ) $order_id = $order_number;

		$number = $before . ( intval( $offset ) + intval( $order_id ) ) . $after;
		return $number;
	}
}

/**
 * Return the order date
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string order date
 */
if ( ! function_exists( 'wcdn_order_date' ) ) {
	function wcdn_order_date() {
		global $wcdn;
		$order = $wcdn->print->get_order();
		return date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) );
	}
}

/**
 * Return the order items
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return strings order items
 */
if ( ! function_exists( 'wcdn_get_order_items' ) ) {
	function wcdn_get_order_items() {
		global $wcdn;
		return $wcdn->print->get_order_items();
	}
}

/**
 * Return the order items price
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string items price
 */
if ( !function_exists( 'wcdn_format_price' ) ) {
	function wcdn_format_price( $price, $tax_rate = 0 ) {
		$tax_included = ( $tax_rate > 0 ) ? 0 : 1;
		return woocommerce_price( ( ( $price / 100 ) * $tax_rate ) + $price, array( 'ex_tax_label' => $tax_included ) );
	}
}

/**
 * Return the order subtotal
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string order subtotal
 */
if ( !function_exists( 'wcdn_order_subtotal' ) ) {
	function wcdn_order_subtotal() {
		global $wcdn;
		return $wcdn->print->get_order()->get_subtotal_to_display();
	}
}

/**
 * Return the order tax
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string order tax
 */
if ( ! function_exists( 'wcdn_order_tax' ) ) {
	function wcdn_order_tax() {
		global $wcdn;
		return woocommerce_price( $wcdn->print->get_order()->get_total_tax() );
	}
}

/**
 * Return the order shipping cost
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string order shipping cost
 */
if ( ! function_exists( 'wcdn_order_shipping' ) ) {
	function wcdn_order_shipping() {
		global $wcdn;
		return $wcdn->print->get_order()->get_shipping_to_display();
	}
}

/**
 * Return the order discount
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string order discount
 */
if ( ! function_exists( 'wcdn_order_discount' ) ) {
	function wcdn_order_discount() {
		global $wcdn;
		return woocommerce_price( $wcdn->print->get_order()->order_discount );
	}
}

/**
 * Return the order grand total
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string grand total
 */
if ( ! function_exists( 'wcdn_order_total' ) ) {
	function wcdn_order_total() {
		global $wcdn;
		return woocommerce_price( $wcdn->print->get_order()->order_total );
	}
}

/**
 * Return if the order has a shipping
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return boolean
 */
if ( ! function_exists( 'wcdn_has_shipping' ) ) {
	function wcdn_has_shipping() {
		global $wcdn;
		return ( $wcdn->print->get_order()->order_shipping > 0 ) ? true : false;
	}
}

/**
 * Return if the order has a tax
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return boolean
 */
if ( ! function_exists( 'wcdn_has_tax' ) ) {
	function wcdn_has_tax() {
		global $wcdn;
		return ( $wcdn->print->get_order()->get_total_tax() > 0 ) ? true : false;
	}
}

/**
 * Return if the order has a discount
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return boolean
 */
if ( ! function_exists( 'wcdn_has_discount' ) ) {
	function wcdn_has_discount() {
		global $wcdn;
		return ( $wcdn->print->get_order()->order_discount > 0 ) ? true : false;
	}
}

/**
 * Return personal notes, season greetings etc.
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string personal notes
 */
if ( ! function_exists( 'wcdn_personal_notes' ) ) {
	function wcdn_personal_notes() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_setting( 'personal_notes' ) ) );
	}
}

/**
 * Return policy for returns
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string policy
 */
if ( ! function_exists( 'wcdn_policies_conditions' ) ) {
	function wcdn_policies_conditions() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_setting( 'policies_conditions' ) ) );
	}
}

/**
 * Return shop/company footer imprint, copyright etc.
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string footer imprint
 */
if ( ! function_exists( 'wcdn_footer_imprint' ) ) {
	function wcdn_footer_imprint() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_setting( 'footer_imprint' ) ) );
	}
}

/**
 * Show the template
 *
 * @since 1.0
 *
 * @global $wcdn->print
 * @return string footer imprint
 */
echo $wcdn->print->get_print_page( $name );
