<?php
/**
 * Print order content. Copy this file to your themes
 * directory /woocommerce/print to customize it.
 *
 * @package WooCommerce Delivery Notes/Templates
 */

if ( !defined( 'ABSPATH' ) ) exit;
?>

				<div class="order-branding">
					<div class="logo">
						<?php if( wcdn_get_company_logo_id() ) : ?><?php wcdn_company_logo(); ?><?php endif; ?>
					</div>
					
					<div class="company-info">
						<h1 class="company-name"><?php wcdn_company_name(); ?></h1>
						<div class="company-address"><?php wcdn_company_info(); ?></div>
					</div>
					
					<?php do_action( 'wcdn_after_branding', $order ); ?>
				</div><!-- .order-branding -->


				<div class="order-addresses">

					<?php if( get_option( 'woocommerce_ship_to_billing_address_only' ) === 'no' && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>
					
					<div class="shipping-address">
						<h3><?php _e( 'Shipping Address', 'woocommerce-delivery-notes' ); ?></h3>
						<address>
	
							<?php if( !$order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); else echo apply_filters( 'wcdn_address_invoice', $order->get_formatted_shipping_address(), $order ); ?>
						
						</address>
					</div>
					
					<?php endif; ?>
					
					<div class="billing-address">
						<h3><?php _e( 'Billing Address', 'woocommerce-delivery-notes' ); ?></h3>
						<address>
							<?php if( !$order->get_formatted_billing_address() ) _e( 'N/A', 'woocommerce' ); else echo apply_filters( 'wcdn_address_invoice', $order->get_formatted_billing_address(), $order ); ?>
							
							<?php if( wcdn_get_template_type() == 'invoice' ) : ?>
					
							<?php else : ?>
						
							<?php endif ?>
						</address>
					</div>
					
					<?php do_action( 'wcdn_after_addresses', $order ); ?>
				</div><!-- .order-addresses -->


				<div class="order-info">
					<h2><?php wcdn_document_title(); ?></h2>

					<ul class="customer-details">
						<li class="number-info">
							<strong><?php _e( 'Order Number', 'woocommerce-delivery-notes' ); ?></strong>
							<span><?php echo $order->get_order_number(); ?></span>
						</li>
						<li class="date-info">
							<strong><?php _e( 'Order Date', 'woocommerce-delivery-notes' ); ?></strong>
							<span><?php wcdn_order_date( $order ); ?></span>
						</li>
						<li class="payment-info">
							<strong><?php _e( 'Payment Method', 'woocommerce-delivery-notes' ); ?></strong>
							<span><?php wcdn_payment_method( $order ); ?></span>
						</li>
										
						<?php if( $order->billing_email ) : ?>
							<li class="email-info">
								<strong><?php _e( 'Email', 'woocommerce-delivery-notes' ); ?></strong>
								<span><?php echo $order->billing_email; ?></span>
							</li>
						<?php endif; ?>
						
						<?php if( $order->billing_phone ) : ?>
							<li class="telephone-info">
								<strong><?php _e( 'Telephone', 'woocommerce-delivery-notes' ); ?></strong>
								<span><?php echo $order->billing_phone; ?></span>
							</li>
						<?php endif; ?>
						
						<?php do_action( 'wcdn_customer_details_list', $order ); ?>
					</ul>
					
					<?php do_action( 'wcdn_after_info', $order ); ?>
				</div><!-- .order-info -->
				
				
				<div class="order-items">
					<table>
						<thead>
							<tr>
								<th class="product-heading"><?php _e('Product', 'woocommerce-delivery-notes'); ?></th>
								<th class="total-heading"><?php _e('Total', 'woocommerce-delivery-notes'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php if( sizeof( $order->get_items() ) > 0 ) : ?>
								<?php foreach( $order->get_items() as $item ) : ?>
									
									<?php
										$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
										$item_meta = new WC_Order_Item_Meta( $item['item_meta'], $_product );
									?>
									
									<tr>
										<td class="product-name">
											<span class="name"><?php echo apply_filters( 'wcdn_order_item_name', $item['name'], $item ); ?></span>
											<dl class="quantity">
												<dt><?php _e( 'Quantity:', 'woocommerce-delivery-notes' ); ?></dt>
												<dd><?php echo apply_filters( 'wcdn_order_item_quantity', $item['qty'], $item ); ?></dd>

												<?php 
													$fields = apply_filters( 'wcdn_order_item_fields', array(), $item, $order ); 
													
													foreach ( $fields as $field ) : ?>
														
														<dt><?php echo $field['title']; ?></dt>
														<dd><?php echo $field['content']; ?></dd>
														
													<?php endforeach; ?>
											</dl>
											
											<?php $item_meta->display(); ?>
											
											<dl class="files">
												<?php if( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) : ?>
													
													<dt><?php _e( 'Download:', 'woocommerce-delivery-notes' ); ?></dt>
													<dd><?php echo count( $order->get_item_downloads( $item ) ) ?> <?php _e( 'Files', 'woocommerce-delivery-notes' ); ?></dd>
														
												<?php endif; ?>
											</dl>
										</td>
										<td class="product-price">
											<?php echo $order->get_formatted_line_subtotal( $item ); ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
						
						<tfoot>
							<?php if( $totals = $order->get_order_item_totals() ) : ?>
								<?php foreach ( $totals as $total ) : ?>
										
										<tr>
											<td class="total-name"><?php echo $total['label']; ?></td>
											<td class="total-price"><?php echo $total['value']; ?></td>
										</tr>
								
								<?php endforeach; ?>
							<?php endif; ?>
						</tfoot>
						
						<?php do_action( 'wcdn_order_items_table', $order ); ?>
					</table>
										
					<?php do_action( 'wcdn_after_items', $order ); ?>
				</div><!-- .order-items -->
				
				
				<div class="order-notes">
					<?php if( wcdn_has_customer_notes( $order ) ) : ?>
						<h4><?php _e( 'Customer Note', 'woocommerce-delivery-notes' ); ?></h4>
						<?php wcdn_customer_notes( $order ); ?>
					<?php endif; ?>
					
					
					<?php do_action( 'wcdn_after_notes', $order ); ?>
				</div><!-- .order-notes -->
					
				
				<div class="order-thanks">
					<?php wcdn_personal_notes(); ?>
					
					<?php do_action( 'wcdn_after_thanks', $order ); ?>
				</div><!-- .order-thanks -->
					
					
				<div class="order-colophon">
					<div class="colophon-policies">
						<?php wcdn_policies_conditions(); ?>
					</div>
					
					<div class="colophon-imprint">
						<?php wcdn_imprint(); ?>
					</div>	
					
					<?php do_action( 'wcdn_after_colophon', $order ); ?>
				</div><!-- .order-colophon -->
				