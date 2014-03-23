<?php

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
	return apply_filters( 'wcdn_template_type', $wcdn->print->template_type );
}

/**
 * Return print page permalink
 */
function wcdn_get_print_permalink( $order_ids, $template_type = 'order', $order_email = null ) {
	global $wcdn;
	return $wcdn->print->get_print_page_url( $order_ids, $template_type, $order_email );
}

/**
 * Output the document title depending on type
 */
function wcdn_document_title() {
	if( wcdn_get_template_type() == 'invoice' ) {
		echo apply_filters( 'wcdn_document_title', __( 'Invoice', 'woocommerce-delivery-notes' ) );
	} elseif( wcdn_get_template_type() == 'delivery-note' ) {
		echo apply_filters( 'wcdn_document_title', __( 'Delivery Note', 'woocommerce-delivery-notes' ) );
	} else {
		echo apply_filters( 'wcdn_document_title', __( 'Order', 'woocommerce-delivery-notes' ) );
	} 
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
			top: 0;
			position: fixed;
			padding: 6px;
		}

		#navigation .button {
			transition-property: border, background, color;
			display: inline-block;
			text-decoration: none;
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
			
			color: #555;
			border-color: #cccccc;
			background: #f7f7f7;

			-webkit-box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0,0,0,.08);
			box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0,0,0,.08);
			vertical-align: top;
		}
		
		#navigation .button:hover,
		#navigation .button:focus {
			background: #fafafa;
			border-color: #999;
			color: #222;
		}
		
		#navigation .button:active {
			background: #eee;
			border-color: #999;
			color: #333;
			-webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
			box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
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
	echo wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'company_address' ) ) );
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
	return $wcdn->print->get_order( $order_id );
}

/**
 * Show the order date
 */
function wcdn_order_date( $order ) {
	global $wcdn;
	echo apply_filters( 'wcdn_order_date', date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ) );
}

/**
 * Show payment method  
 */
function wcdn_payment_method( $order ) {
	global $wcdn;
	echo apply_filters( 'wcdn_payment_method', __( $order->payment_method_title, 'woocommerce' ) );
}

/**
 * Remove the semicolon from the output  
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
 * Return customer notes
 */
function wcdn_get_customer_notes( $order ) {
	global $wcdn;
	return wpautop( wptexturize( $order->customer_note ) );
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
	return wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'personal_notes' ) ) );
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
	return wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'policies_conditions' ) ) );
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
	return wpautop( wptexturize( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'footer_imprint' )) );
}

/**
 * Show shop/company footer imprint, copyright etc.
 */
function wcdn_imprint() {
	global $wcdn;
	echo wcdn_get_imprint();
}


?>