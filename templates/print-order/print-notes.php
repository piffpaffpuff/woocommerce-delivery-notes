<?php
/**
 * Print order addresses
 *
 * @package WooCommerce Delivery Notes/Templates
 */

if ( !defined( 'ABSPATH' ) ) exit;
?>

				<div class="order-notes">
				
					<div class="notes-shipping">
						<?php if( $order->customer_note ) : ?>
							<h3><?php _e( 'Customer Notes', 'woocommerce-delivery-notes' ); ?></h3>
							<?php echo $order->customer_note; ?>
						<?php endif; ?>
					</div>
					
					<div class="notes-personal"><?php wcdn_personal_notes(); ?></div>
					
					<?php do_action( 'wcdn_after_notes', $order ); ?>
					
				</div><!-- .order-notes -->
					
				<div class="order-colophon">

					<div class="policies"><?php wcdn_policies_conditions(); ?></div>
					<div class="imprint"><?php wcdn_imprint(); ?></div>	
					
					<?php do_action( 'wcdn_after_colophon', $order ); ?>
					
				</div><!-- .order-colophon -->