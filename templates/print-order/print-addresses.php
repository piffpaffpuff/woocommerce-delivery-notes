<?php
/**
 * Print order addresses
 *
 * @package WooCommerce Delivery Notes/Templates
 */

if ( !defined( 'ABSPATH' ) ) exit;
?>

				<div class="order-branding">
				
					<header class="title">
						<?php if( wcdn_get_company_logo_id() ) : ?><?php wcdn_company_logo(); ?><?php else : ?><h3><?php wcdn_document_title(); ?></h3><?php endif; ?>
					</header>
					
					<div class="company-info">
						<div class="company-name"><?php wcdn_company_name(); ?></div>
						<div class="company-address"><?php wcdn_company_info(); ?></div>
					</div>
				
					<?php do_action( 'wcdn_after_branding', $order ); ?>
													
				</div><!-- .order-branding -->

				<div class="order-recipient">
					
					<header class="title">
						<h3><?php _e( 'Recipient', 'woocommerce-delivery-notes' ); ?></h3>
					</header>
					
					<address><p>
						<?php if( wcdn_get_template_type() == 'invoice' ) : ?>
				
							<?php if( !$order->get_formatted_billing_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_billing_address(); ?>
				
						<?php else : ?>
				
							<?php if ( get_option( 'woocommerce_ship_to_billing_address_only' ) === 'no' && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

								<?php if ( !$order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_shipping_address(); ?>

							<?php endif; ?>
					
						<?php endif ?>
					</p></address>
					
					<?php do_action( 'wcdn_after_recipient', $order ); ?>
					
				</div><!-- .order-recipient -->

				<div class="order-info">
					
					<header class="title">
						<h3><?php wcdn_document_title(); ?></h3>
					</header>
					
					<dl class="customer-details">
						
						<dt class="term-number"><?php _e( 'Order Number', 'woocommerce-delivery-notes' ); ?></dt><dd class="description-number"><?php echo $order->get_order_number(); ?></dd>
						<dt class="term-date"><?php _e( 'Order Date', 'woocommerce-delivery-notes' ); ?></dt><dd class="description-date"><?php wcdn_order_date( $order ); ?></dd>
						<dt class="term-payment"><?php _e( 'Payment Method', 'woocommerce-delivery-notes' ); ?></dt><dd class="description-payment"><?php wcdn_payment_method( $order ); ?></dd>
										
						<?php if( $order->billing_email ) : ?>
							
							<dt class="term-email"><?php _e( 'Email', 'woocommerce-delivery-notes' ); ?></dt><dd class="description-email"><?php echo $order->billing_email; ?></dd>
						
						<?php endif; ?>
						
						<?php if( $order->billing_phone ) : ?>
							
							<dt class="term-telephone"><?php _e( 'Telephone', 'woocommerce-delivery-notes' ); ?></dt><dd class="description-telephone"><?php echo $order->billing_phone; ?></dd>
						
						<?php endif; ?>
						
						<?php do_action( 'wcdn_customer_details_list', $order ); ?>
					</dl>
						
					<?php do_action( 'wcdn_after_info', $order ); ?>
					
				</div><!-- .order-info -->