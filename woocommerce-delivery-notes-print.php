<?php

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
if (!current_user_can('manage_woocommerce_orders') || empty($_GET['order']) || empty($_GET['name'])) {
	wp_die( __( 'You do not have sufficient permissions to access this page.', 'woocommerce-delivery-notes' ) );
}

/**
 * Load all available data for the Delivery Notes printing page.
 */
$id = $_GET['order'];
$name = $_GET['name'];

/**
 * Load the order
 *
 * @since 1.0
 */
$wcdn->print->load( $id );

/**
 * Return Type of template
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_get_template_type' ) ) {
	function wcdn_get_template_type() {
		global $wcdn;
		return $wcdn->print->template_name;
	}
}

/**
 * Show the template class
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_class' ) ) {
	function wcdn_template_class() {
		global $wcdn;
		echo sanitize_key( wcdn_get_template_type() );
	}
}

/**
 * Show the template head
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_head' ) ) {
	function wcdn_template_head() {
		?>
		<title><?php wcdn_template_title(); ?></title>
		<?php wcdn_template_javascript(); ?>
		<style type="text/css">
			#navigation {
				font-family: Helvetica, Arial, sans-serif;
				position: fixed;
				top: 0px;
				left: 0px;
				right: 0px;
				padding-top: 6px;
				padding-left: 6px;
				padding-right: 6px;
				height: 28px;
				text-shadow: 0px 1px 0px #ffffff;
				filter: dropshadow(color=#ffffff, offx=0, offy=1);
				background: #F1F1F1;
				background-image: -ms-linear-gradient(top, #F9F9F9, #ECECEC);
				background-image: -moz-linear-gradient(top, #F9F9F9, #ECECEC);
				background-image: -o-linear-gradient(top, #F9F9F9, #ECECEC);
				background-image: -webkit-gradient(linear, left top, left bottom, from(#F9F9F9), to(#ECECEC));
				background-image: -webkit-linear-gradient(top, #F9F9F9, #ECECEC);
				background-image: linear-gradient(top, #F9F9F9, #ECECEC);
				border-bottom: 1px solid #DFDFDF;
				border-top: 1px solid #DFDFDF;
			}
			
			.options {
				overflow: hidden;
			}
			
			.options a {
				border: 1px solid #bbb;
				-webkit-border-radius: 12px;
				-moz-border-radius: 12px;
				-ms-border-radius: 12px;
				-o-border-radius: 12px;
				border-radius: 12px;
				color: #464646;
				display: block;
				float: right;
				font-size: 0.875em;
				height: 20px;
				line-height: 20px;
				padding-left: 15px;
				padding-right: 15px;
				text-decoration: none;
				background: #F1F1F1;
				background: -moz-linear-gradient(top, #ffffff 0%, #efefef 100%);
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#efefef));
				background: -webkit-linear-gradient(top, #ffffff 0%,#efefef 100%);
				background: -o-linear-gradient(top, #ffffff 0%,#efefef 100%);
				background: -ms-linear-gradient(top, #ffffff 0%,#efefef 100%);
				background: linear-gradient(top, #ffffff 0%,#efefef 100%);
				-webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
				-moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
				-ms-box-shadow: inset 0px 1px 0px 0px #ffffff;
				-o-box-shadow: inset 0px 1px 0px 0px #ffffff;
				box-shadow: inset 0px 1px 0px 0px #ffffff;
			}
			
			.options a:hover {
				border-color: #999;
				color: #000;
			}
			
			.options a:active {
				border-color: #666;
				-webkit-box-shadow: inset 0px 0px 6px 0px rgba(0, 0, 0, 0.45);
				-moz-box-shadow: inset 0px 0px 6px 0px rgba(0, 0, 0, 0.45);
				-ms-box-shadow: inset 0px 0px 6px 0px rgba(0, 0, 0, 0.45);
				-o-box-shadow: inset 0px 0px 6px 0px rgba(0, 0, 0, 0.45);
				box-shadow: inset 0px 0px 6px 0px rgba(0, 0, 0, 0.45);
				background: -moz-linear-gradient(top, #ffffff 0%, #efefef 100%);
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#efefef));
				background: -webkit-linear-gradient(top, #efefef 0%,#e6e6e6 100%);
				background: -o-linear-gradient(top, #ffffff 0%,#efefef 100%);
				background: -ms-linear-gradient(top, #ffffff 0%,#efefef 100%);
				background: linear-gradient(top, #ffffff 0%,#efefef 100%);
			}

			@media print {
				#navigation {
					display: none;
				}
			}		
		</style>
		<?php
	}
}

/**
 * Show javascript
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_javascript' ) ) {
	function wcdn_template_javascript() {
		global $wcdn;
		?>
		<script type="text/javascript">
			function openPrintWindow() {
		    	window.print();
		    	return false;
			}
			<?php if( checked( $wcdn->print->get_setting( 'open_print_window' ), 'yes', false ) ) : ?>
				window.onload = openPrintWindow;
			<?php endif ?>
		</script>
		<?php
	}
}

/**
 * Show template url
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_url' ) ) {
	function wcdn_template_url() {
		global $wcdn;
		echo $wcdn->print->template_url;
	}
}

/**
 * Show template stylesheet url
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_stylesheet_url' ) ) {
	function wcdn_template_stylesheet_url() {
		global $wcdn;
		echo $wcdn->print->template_stylesheet_url;
	}
}

/**
 * Show the template nav bar
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_navigation' ) ) {
	function wcdn_template_navigation() {
		?>
		<div id="navigation">
			<div class="options">
				<?php echo wcdn_template_print_button(); ?>
			</div>
		</div>
		<?php
	}
}

/**
 * Show the template title depending on type
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_template_title' ) ) {
	function wcdn_template_title() {
		if( wcdn_get_template_type() == 'invoice' ) {
			echo __( 'Invoice', 'woocommerce-delivery-notes' );
		} else {
			echo __( 'Delivery Note', 'woocommerce-delivery-notes' );
		}
	}
}

/**
 * Show print button
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
 * Return logo id
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_get_company_logo_id' ) ) {
	function wcdn_get_company_logo_id() {
		global $wcdn;
		return $wcdn->print->get_setting( 'company_logo_image_id' );
	}
}

/**
 * Show logo html
 *
 * @since 1.0
 */
if ( !function_exists( 'wcdn_company_logo' ) ) {
	function wcdn_company_logo() {
		global $wcdn;
		$attachment_id = wcdn_get_company_logo_id();
		if( $attachment_id ) {
			$attachment_src = wp_get_attachment_image_src( $attachment_id, 'full', false );
			?>
			<img src="<?php echo $attachment_src[0]; ?>" width="<?php echo $attachment_src[1]; ?>" height="<?php echo $attachment_src[2]; ?>" />
			<?php
		}
		return;
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
			echo wpautop( wptexturize( $name ) );
		} else {
			echo get_bloginfo( 'name' );
		}
	}
}

/**
 * Return shop/company info if provided
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_company_info' ) ) {
	function wcdn_company_info() {
		global $wcdn;
		echo wpautop( wptexturize( $wcdn->print->get_setting( 'company_address' ) ) );
	}
}

/**
 * Show billing phone
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_billing_phone' ) ) {
	function wcdn_billing_phone() {
		global $wcdn;
		echo $wcdn->print->get_order()->billing_phone;
	}
}

/**
 * Show billing email
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_billing_email' ) ) {
	function wcdn_billing_email() {
		global $wcdn;
		echo $wcdn->print->get_order()->billing_email;
	}
}

/**
 * Show billing address
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_billing_address' ) ) {
	function wcdn_billing_address() {
		global $wcdn;
		$address = $wcdn->print->get_order()->get_formatted_billing_address();
		if( !$address ) {
			$address = _e('N/A', 'woocommerce');
		}
		echo $address;
	}
}

/**
 * Show shipping address
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_shipping_address' ) ) {
	function wcdn_shipping_address() {
		global $wcdn;
		$address = $wcdn->print->get_order()->get_formatted_shipping_address();
		if( !$address ) {
			$address = _e('N/A', 'woocommerce');
		}
		echo $address;
	}
}

/**
 * Get order
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_get_order' ) ) {
	function wcdn_get_order() {
		global $wcdn;
		return $wcdn->print->get_order();
	}
}

/**
 * Show order number
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_order_number' ) ) {
	function wcdn_order_number() {
		global $wcdn;

		// get custom order number as provided by the plugin
		// http://wordpress.org/extend/plugins/woocommerce-sequential-order-numbers/
		// if custom order number is zero, fall back to ID
		$order_id = $wcdn->print->order_id;

		if ( !empty( $wcdn->print->get_order()->order_custom_fields['_order_number'] ) ) {
			$order_id = $wcdn->print->get_order()->order_custom_fields['_order_number'][0];
		}
		
		echo $order_id;
	}
}

/**
 * Show the order date
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_order_date' ) ) {
	function wcdn_order_date() {
		global $wcdn;
		$order = $wcdn->print->get_order();
		echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) );
	}
}

/**
 * Return the order items
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_get_order_items' ) ) {
	function wcdn_get_order_items() {
		global $wcdn;
		return $wcdn->print->get_order_items();
	}
}

/**
 * Return the order totals listing
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_get_order_totals' ) ) {
	function wcdn_get_order_totals() {
		global $wcdn;		
		
		// remove the semicolon
		$input = $wcdn->print->get_order()->get_order_item_totals();
		$keys = array_keys($input);
		$values = array_values($input);
		$result = preg_replace('/:$/', '', $keys);
		$output = array_combine($result, $values);
		return $output;
	}
}

/**
 * Return has shipping notes
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_get_shipping_notes' ) ) {
	function wcdn_get_shipping_notes() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_order()->customer_note ) );
	}
}

/**
 * Show shipping notes
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_shipping_notes' ) ) {
	function wcdn_shipping_notes() {
		global $wcdn;
		echo wcdn_get_shipping_notes();
	}
}

/**
 * Return personal notes, season greetings etc.
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_get_personal_notes' ) ) {
	function wcdn_get_personal_notes() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_setting( 'personal_notes' ) ) );
	}
}

/**
 * Show personal notes, season greetings etc.
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_personal_notes' ) ) {
	function wcdn_personal_notes() {
		global $wcdn;
		echo wcdn_get_personal_notes();
	}
}

/**
 * Return policy for returns
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_get_policies_conditions' ) ) {
	function wcdn_get_policies_conditions() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_setting( 'policies_conditions' ) ) );
	}
}

/**
 * Show policy for returns
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_policies_conditions' ) ) {
	function wcdn_policies_conditions() {
		global $wcdn;
		echo wcdn_get_policies_conditions();
	}
}

/**
 * Return shop/company footer imprint, copyright etc.
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_get_footer_imprint' ) ) {
	function wcdn_get_footer_imprint() {
		global $wcdn;
		return wpautop( wptexturize( $wcdn->print->get_setting( 'footer_imprint' ) ) );
	}
}

/**
 * Show shop/company footer imprint, copyright etc.
 *
 * @since 1.0
 */
if ( ! function_exists( 'wcdn_footer_imprint' ) ) {
	function wcdn_footer_imprint() {
		global $wcdn;
		echo wcdn_get_footer_imprint();
	}
}

/**
 * Show the template
 *
 * @since 1.0
 */
echo $wcdn->print->get_print_page( $name );
