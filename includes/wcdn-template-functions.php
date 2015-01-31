<?php

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * Output the template part
 */
function wcdn_get_template_content( $name, $args = null ) {
	global $wcdn;
	wc_get_template( $name, $args, $wcdn->print->template_path_theme, $wcdn->print->template_path_plugin );
}

/**
 * Return Type of the print template
 */
function wcdn_get_template_type() {
	global $wcdn;
	return apply_filters( 'wcdn_template_type', $wcdn->print->template['type'] );
}

/**
 * Return Title of the print template
 */
function wcdn_get_template_title() {
	global $wcdn;
	return apply_filters( 'wcdn_template_type', __( $wcdn->print->template['labels']['name'], 'woocommerce-delivery-notes' ) );
}

/**
 * Return print page link
 */
function wcdn_get_print_link( $order_ids, $template_type = 'order', $order_email = null, $permalink = false ) {
	global $wcdn;
	return $wcdn->print->get_print_page_url( $order_ids, $template_type, $order_email, $permalink );
}

/**
 * Output the document title depending on type
 */
function wcdn_document_title() {
	echo apply_filters( 'wcdn_document_title', wcdn_get_template_title() );
}

/**
 * Output the print navigation style
 */
function wcdn_navigation_style() {
	?>
	<style>
		#navigation {
			font-family: sans-serif;
			background-color: #f1f1f1;
			z-index: 200;
			border-bottom: 1px solid #dfdfdf;
			left: 0;
			right: 0;
			bottom: 0;
			position: fixed;
			padding: 6px 8px;
			text-align: right;
		}

		#navigation .button {
			transition-property: border, background, color;
			display: inline-block;
			font-size: 13px;
			line-height: 26px;
			height: 28px;
			margin: 0;
			padding: 0 10px 1px;
			cursor: pointer;
			border-width: 1px;
			border-style: solid;
			-webkit-border-radius: 3px;
			-webkit-appearance: none;
			border-radius: 3px;
			white-space: nowrap;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			
			background: #2ea2cc;
			border-color: #0074a2;
		 	-webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,0.5), 0 1px 0 rgba(0,0,0,.15);
		 	box-shadow: inset 0 1px 0 rgba(120,200,230,0.5), 0 1px 0 rgba(0,0,0,.15);
		 	color: #fff;
			text-decoration: none;
		}
		
		#navigation .button:hover,
		#navigation .button:focus {
			background: #1e8cbe;
			border-color: #0074a2;
		 	-webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,0.6);
		 	box-shadow: inset 0 1px 0 rgba(120,200,230,0.6);
			color: #fff;
		}
		
		#navigation .button:active {
			background: #1b7aa6;
			border-color: #005684;
			color: rgba(255,255,255,0.95);
			-webkit-box-shadow: inset 0 1px 0 rgba(0,0,0,0.1);
			box-shadow: inset 0 1px 0 rgba(0,0,0,0.1);
		}
		
		@media print {	
			#navigation {
				display: none;
			}
		}
	</style>
	<?php
}

/**
 * Create print navigation
 */
function wcdn_navigation() {
	?>
	<div id="navigation">
		<a href="#" class="button" onclick="window.print();return false;"><?php _e( 'Print', 'woocommerce-delivery-notes' ); ?></a>
	</div><!-- #navigation -->
	<?php
}

/**
 * Output template stylesheet
 */
function wcdn_template_stylesheet() {
	global $wcdn;
	$name = apply_filters( 'wcdn_template_stylesheet_name', 'style.css' );
	?>
	<link rel="stylesheet" href="<?php echo $wcdn->print->get_template_url( $name ); ?>" type="text/css" media="screen,print" />
	<?php
}

/**
 * Output the template print content
 */
function wcdn_content( $order, $template_type ) {
	global $wcdn;
	
	// Add WooCommerce hooks here to not 
	// make global changes to the totals 
	add_filter( 'woocommerce_get_order_item_totals', 'wcdn_remove_semicolon_from_totals', 10, 2 );
	add_filter( 'woocommerce_get_order_item_totals', 'wcdn_remove_payment_method_from_totals', 20, 2 );
	add_filter( 'woocommerce_get_order_item_totals', 'wcdn_add_refunded_order_totals', 30, 2 );
	
	// Load the template
	wcdn_get_template_content( 'print-content.php', array( 'order' => $order, 'template_type' => $template_type ) );
}

/**
 * Return logo id
 */
function wcdn_get_company_logo_id() {
	global $wcdn;
	return apply_filters( 'wcdn_company_logo_id', get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'company_logo_image_id' ) );
}

/**
 * Show logo html
 */
function wcdn_company_logo() {
	global $wcdn;
	$attachment_id = wcdn_get_company_logo_id();
	$company = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'custom_company_name' );
	if( $attachment_id ) {
		$attachment_src = wp_get_attachment_image_src( $attachment_id, 'full', false );
		
		// resize the image to a 1/4 of the original size
		// to have a printing point density of about 288ppi.
		?>
		<img src="<?php echo $attachment_src[0]; ?>" width="<?php echo $attachment_src[1] / 4; ?>" height="<?php echo $attachment_src[2] / 4; ?>" alt="<?php echo esc_attr( $company ); ?>" />
		<?php
	}
}

/**
 * Return default title name of Delivery Note 
 */
function wcdn_company_name() {
	global $wcdn;
	$name = trim( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'custom_company_name' ) );
	if( !empty( $name ) ) {
		echo apply_filters( 'wcdn_company_name', stripslashes( wptexturize( $name ) ) );
	} else {
		echo apply_filters( 'wcdn_company_name', get_bloginfo( 'name' ) );
	}
}

/**
 * Return shop/company info if provided
 */
function wcdn_company_info() {
	global $wcdn;
	echo stripslashes( wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'company_address' ) ) ) );
}

/**
 * Get orders as array. Every order is a normal WC_Order instance.
 */
function wcdn_get_orders() {
	global $wcdn;
	return $wcdn->print->orders;
}

/**
 * Get an order
 */
function wcdn_get_order( $order_id ) {
	global $wcdn;
	return $wcdn->print->get_order( $order_id );
}

/**
 * Get the order info fields
 */
function wcdn_get_order_info( $order ) {
	global $wcdn;
	$fields = array();
	$create_invoice_number = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'create_invoice_number' );
	
	if( wcdn_get_template_type() == 'invoice' && !empty( $create_invoice_number ) ) {
		$fields['invoice_number'] = array( 
			'label' => __( 'Invoice Number', 'woocommerce-delivery-notes' ),
			'value' => wcdn_get_order_invoice_number( $order->id )
		);
	}
	
	$fields['order_number'] = array( 
		'label' => __( 'Order Number', 'woocommerce-delivery-notes' ),
		'value' => $order->get_order_number() 
	);
	
	$fields['order_date'] = array( 
		'label' => __( 'Order Date', 'woocommerce-delivery-notes' ),
		'value' => date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) )
	);
	
	$fields['payment_method'] = array( 
		'label' => __( 'Payment Method', 'woocommerce-delivery-notes' ),
		'value' => __( $order->payment_method_title, 'woocommerce' )
	);
	
	if( $order->billing_email ) {
		$fields['billing_email'] = array(
			'label' => __( 'Email', 'woocommerce-delivery-notes' ),
			'value' => $order->billing_email
		);
	}
	
	if( $order->billing_phone ) {
		$fields['billing_phone'] = array(
			'label' => __( 'Telephone', 'woocommerce-delivery-notes' ),
			'value' => $order->billing_phone
		);
	}
	
	return $fields;
}

/**
 * Get the invoice number of an order
 */
function wcdn_get_order_invoice_number( $order_id ) {
	global $wcdn;
	return $wcdn->print->get_order_invoice_number( $order_id );
}

/**
 * Get the invoice date of an order
 */
function wcdn_get_order_invoice_date( $order_id ) {
	global $wcdn;
	return $wcdn->print->get_order_invoice_date( $order_id );
}

/**
 * Additional fields for the product
 */
function wcdn_additional_product_fields( $fields = null, $product = null, $order ) {
	$new_fields = array();
	
	// Stock keeping unit
	if( $product && $product->exists() && $product->get_sku() ) {
		$fields[] = array(
			'label' => __( 'SKU:', 'woocommerce-delivery-notes' ),
			'value' => $product->get_sku()
		);
	}		
	return array_merge( $fields, $new_fields );
}

/**
 * Check if a shipping address is enabled
 */
function wcdn_has_shipping_address( $order ) {
	// Works only with WooCommerce 2.2 and higher
	if( version_compare( WC_VERSION, '2.2.0', '>=' ) ) {
		if( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}

/**
 * Check if an order contains a refund
 */
function wcdn_has_refund( $order ) {
	// Works only with WooCommerce 2.2 and higher
	if( version_compare( WC_VERSION, '2.2.0', '>=' ) ) {
		if( $order->get_total_refunded() ) {
			return true;
		}
	}
	return false;
}

/**
 * Gets formatted item subtotal for display.
 */
function wcdn_get_formatted_item_price( $order, $item, $tax_display = '' ) {

	if ( ! $tax_display ) {
		$tax_display = $order->tax_display_cart;
	}

	if ( ! isset( $item['line_subtotal'] ) || ! isset( $item['line_subtotal_tax'] ) ) {
		return '';
	}

	if ( 'excl' == $tax_display ) {
		$ex_tax_label = $order->prices_include_tax ? 1 : 0;

		$subtotal = wc_price( $order->get_item_subtotal( $item ), array( 'ex_tax_label' => $ex_tax_label, 'currency' => $order->get_order_currency() ) );
	} else {
		$subtotal = wc_price( $order->get_item_subtotal( $item, true ), array('currency' => $order->get_order_currency()) );
	}

	return apply_filters( 'wcdn_formatted_item_price', $subtotal, $item, $order );
}

/**
 * Add refund totals
 */
function wcdn_add_refunded_order_totals( $total_rows, $order ) {		
	if( wcdn_has_refund( $order ) ) {
		if( version_compare( WC_VERSION, '2.3.0', '>=' ) ) {
			$refunded_tax_del = '';
			$refunded_tax_ins = '';
			
			// Tax for inclusive prices
			if ( wc_tax_enabled() && 'incl' == $order->tax_display_cart ) {
				$tax_del_array = array();
				$tax_ins_array = array();
	
				if ( 'itemized' == get_option( 'woocommerce_tax_total_display' ) ) {
	
					foreach ( $order->get_tax_totals() as $code => $tax ) {
						$tax_del_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
						$tax_ins_array[] = sprintf( '%s %s', wc_price( $tax->amount - $order->get_total_tax_refunded_by_rate_id( $tax->rate_id ), array( 'currency' => $order->get_order_currency() ) ), $tax->label );
					}
	
				} else {
					$tax_del_array[] = sprintf( '%s %s', wc_price( $order->get_total_tax(), array( 'currency' => $order->get_order_currency() ) ), WC()->countries->tax_or_vat() );
					$tax_ins_array[] = sprintf( '%s %s', wc_price( $order->get_total_tax() - $order->get_total_tax_refunded(), array( 'currency' => $order->get_order_currency() ) ), WC()->countries->tax_or_vat() );
				}
	
				if ( ! empty( $tax_del_array ) ) {
					$refunded_tax_del .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_del_array ) );
				}
	
				if ( ! empty( $tax_ins_array ) ) {
					$refunded_tax_ins .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_ins_array ) );
				}
			}
			
			// use only the number for new wc versions
			$order_subtotal = wc_price( $order->get_total(), array( 'currency' => $order->get_order_currency() ) );
		} else {
			$refunded_tax_del = '';
			$refunded_tax_ins = '';
			
			// use the normal total for older wc versions
			$order_subtotal = $total_rows['order_total']['value'];
		}		
		
		// Add refunded totals row
		$total_rows['wcdn_refunded_total'] = array(
			'label' => __( 'Refund', 'woocommerce-delivery-notes' ), 
			'value' => wc_price( -$order->get_total_refunded(), array( 'currency' => $order->get_order_currency() ) )
		);
		
		// Add new order totals row
		$total_rows['wcdn_order_total'] = array(
			'label' => $total_rows['order_total']['label'], 
			'value' => wc_price( $order->get_total() - $order->get_total_refunded(), array( 'currency' => $order->get_order_currency() ) ) . $refunded_tax_ins
		);
		
		// Edit the original order total row
		$total_rows['order_total'] = array(
			'label' => __( 'Order Subtotal', 'woocommerce-delivery-notes' ), 
			'value' => $order_subtotal
		);
	}
	
	return $total_rows;
}

/**
 * Remove the semicolon from the totals  
 */
function wcdn_remove_semicolon_from_totals( $total_rows, $order ) {		
	foreach( $total_rows as $key => $row ) {
		$label = $row['label'];
		$colon = strrpos( $label, ':' );
		if( $colon !== false ) {
			$label = substr_replace( $label, '', $colon, 1 );
		}		
		$total_rows[$key]['label'] = $label;
	}
	return $total_rows;
}

/**
 * Remove the payment method text from the totals  
 */
function wcdn_remove_payment_method_from_totals( $total_rows, $order ) {		
	unset($total_rows['payment_method']);
	return $total_rows;
}

/**
 * Return customer notes
 */
function wcdn_get_customer_notes( $order ) {
	global $wcdn;
	return stripslashes( wpautop( wptexturize( $order->customer_note ) ) );
}

/**
 * Show customer notes
 */
function wcdn_customer_notes( $order ) {
	global $wcdn;
	echo wcdn_get_customer_notes( $order );
}

/**
 * Return has customer notes
 */
function wcdn_has_customer_notes( $order ) {
	global $wcdn;
	if( wcdn_get_customer_notes( $order ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Return personal notes, season greetings etc.
 */
function wcdn_get_personal_notes() {
	global $wcdn;
	return stripslashes( wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'personal_notes' ) ) ) );
}

/**
 * Show personal notes, season greetings etc.
 */
function wcdn_personal_notes() {
	global $wcdn;
	echo wcdn_get_personal_notes();
}

/**
 * Return policy for returns
 */
function wcdn_get_policies_conditions() {
	global $wcdn;
	return stripslashes( wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'policies_conditions' ) ) ) );
}

/**
 * Show policy for returns
 */
function wcdn_policies_conditions() {
	global $wcdn;
	echo wcdn_get_policies_conditions();
}

/**
 * Return shop/company footer imprint, copyright etc.
 */
function wcdn_get_imprint() {
	global $wcdn;
	return stripslashes( wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'footer_imprint' ) ) ) );
}

/**
 * Show shop/company footer imprint, copyright etc.
 */
function wcdn_imprint() {
	global $wcdn;
	echo wcdn_get_imprint();
}


?>