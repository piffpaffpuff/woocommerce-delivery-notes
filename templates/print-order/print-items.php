<?php
/**
 * Print order content. Copy this file to your themes
 * directory /woocommerce/print to customize it.
 *
 * @package WooCommerce Delivery Notes/Templates
 */

if ( !defined( 'ABSPATH' ) ) exit;
?>
				
				<div class="order-items">
				
					<table>
					
						<thead>
							<tr>
								<th class="title-product"><?php _e('Product', 'woocommerce-delivery-notes'); ?></th>
								<th class="title-total"><?php _e('Total', 'woocommerce-delivery-notes'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php if( sizeof( $order->get_items() ) > 0 ) : ?>
								<?php foreach( $order->get_items() as $item ) : ?>
									
									<?php
										$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
										$item_meta = new WC_Order_Item_Meta( $item['item_meta'], $_product );
									?>
									
									<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
										<td class="product-name">
											<?php
												echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
					
												echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );
					
												$item_meta->display();
					
												if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {
					
													$download_files = $order->get_item_downloads( $item );
													$i = 0;
													$links = array();
					
													foreach ( $download_files as $download_id => $file ) {
														$i++;
					
														$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
													}
					
													echo '<br/>' . implode( '<br/>', $links );
												}
											?>
										</td>
										<td class="product-total">
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
											<td class="product-name"><?php echo $total['label']; ?></th>
											<td class="product-total"><?php echo $total['value']; ?></td>
										</tr>
								
								<?php endforeach; ?>
							<?php endif; ?>
						</tfoot>
						
						<?php do_action( 'wcdn_order_items_table', $order ); ?>

					</table>

					<?php do_action( 'wcdn_after_items', $order ); ?>

				</div><!-- .order-items -->
