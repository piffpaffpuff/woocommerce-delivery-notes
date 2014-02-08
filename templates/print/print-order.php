<?php
/*
 * The Template to display the print view, including the different types for invoice and delivery note.
 *
 * Override this template by copying it to yourtheme/woocommerce/print/print-order.php
 *
 */
?>

<?php wcdn_get_template( 'print-header.php' ); ?>

	<?php
		// wcdn_before_content hook
		do_action( 'wcdn_before_content' );
	?>
		
		<article class="content <?php echo wcdn_get_template_type(); ?>">
			
			<?php wcdn_get_template( 'print-content.php' ); ?>
			
		</article><!-- .content -->

		<?php /*
$in = 2; foreach( $) : ?>
		
		
		<?php endforeach;
*/ ?>
		<?php //wcdn_get_template( 'print-header.php' ); ?>

	<?php
		// wcdn_after_content hook
		do_action( 'wcdn_after_content' );
	?>

<?php wcdn_get_template( 'print-footer.php' ); ?>