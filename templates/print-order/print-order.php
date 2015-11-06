<?php
/**
 * Print order template. This is the main file. Most 
 * probably it is easier to edit one of the content by
 * copying it to your theme /woocommerce/print
 *
 * @package WooCommerce Print Invoice & Delivery Note/Templates
 */

if ( !defined( 'ABSPATH' ) ) exit;
?>

<?php
	// wcdn_before_template hook
	do_action( 'wcdn_before_template' );
?>	

<?php wcdn_get_template_content( 'print-header.php' ); ?>

	<?php
		// wcdn_before_content hook
		do_action( 'wcdn_before_content' );
	?>			
			
		<?php if( $orders = wcdn_get_orders() ) :	?>
			
			<?php
				// wcdn_before_loop hook
				do_action( 'wcdn_before_loop' );
			?>
			
			<?php foreach( $orders as $order ) : ?>
					
				<article class="content">
					
					<?php do_action( 'wcdn_loop_content', $order, wcdn_get_template_type() ); ?>

				</article><!-- .content -->
				
			<?php endforeach; ?>

			<?php
				// wcdn_after_loop hook
				do_action( 'wcdn_after_loop' );
			?>
		
		<?php endif; ?>
		
	<?php
		// wcdn_after_content hook
		do_action( 'wcdn_after_content' );
	?>

<?php wcdn_get_template_content( 'print-footer.php' ); ?>

<?php
	// wcdn_after_template hook
	do_action( 'wcdn_after_template' );
?>	