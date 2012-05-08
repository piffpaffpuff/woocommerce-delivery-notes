<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php if( wcdn_template_name() == 'invoice' ) : ?><?php _e( 'Invoice', 'woocommerce-delivery-notes' ); ?><?php else : ?><?php _e( 'Delivery Note', 'woocommerce-delivery-notes' ); ?><?php endif; ?></title>
	<link rel="stylesheet" href="<?php echo wcdn_template_url(); ?>css/style.css" type="text/css" media="screen,print" charset="utf-8"/>
	<?php echo wcdn_template_javascript(); ?>
</head>

<body>
	<div id="container">
		<div id="header">
			<div class="options">
				<?php echo wcdn_template_print_button(); ?>
			</div><!-- .options -->
		</div><!-- #header -->

		<div id="content">			
			<div id="page">
				<div id="letter-header">
					<div class="heading"><?php if( wcdn_company_logo_id() ) : ?><?php echo wcdn_company_logo(); ?><?php else : ?><?php if( wcdn_template_name() == 'invoice' ) : ?><?php _e( 'Invoice', 'woocommerce-delivery-notes' ); ?><?php else : ?><?php _e( 'Delivery Note', 'woocommerce-delivery-notes' ); ?><?php endif; ?><?php endif; ?></div>
					<div class="company-info">
						<div class="company-name"><?php echo wcdn_company_name(); ?></div>
						<div class="company-address"><?php echo wcdn_company_info(); ?></div>
					</div>
				</div><!-- #letter-header -->
				
				<div id="order-listing">
					<h3><?php _e( 'Recipient', 'woocommerce-delivery-notes' ); ?></h3>
					<div class="shipping-info">
						<?php if( wcdn_shipping_company() ) : ?><?php echo wcdn_shipping_company(); ?><br /><?php endif; ?>
						<?php echo wcdn_shipping_name(); ?><br />
						<?php echo wcdn_shipping_address_1(); ?><br />
						<?php if( wcdn_shipping_address_2() ) : ?><?php echo wcdn_shipping_address_2(); ?><br /><?php endif; ?>
						<?php echo wcdn_shipping_city(); ?>, <?php echo wcdn_shipping_state(); ?><br />
						<?php echo wcdn_shipping_postcode(); ?>

						<?php if( wcdn_shipping_country() ) : ?><br /><?php echo wcdn_shipping_country(); ?><?php endif; ?>
					</div><!-- .shipping-info -->
				</div><!-- #order-listing -->
				
				<ul id="order-info">
					<?php if( wcdn_company_logo() ) : ?>
					<li>
						<h3 class="order-number-label"><?php if( wcdn_template_name() == 'invoice' ) : ?><?php _e( 'Invoice', 'woocommerce-delivery-notes' ); ?><?php else : ?><?php _e( 'Delivery Note', 'woocommerce-delivery-notes' ); ?><?php endif; ?></h3>
					</li>
					<?php endif; ?>
					<li>
						<h3 class="order-number-label"><?php _e( 'Order No.', 'woocommerce-delivery-notes' ); ?></h3>
						<span class="order-number"><?php echo wcdn_order_number(); ?></span>
					</li>
					<li>
						<h3 class="order-date-label"><?php _e( 'Order Date', 'woocommerce-delivery-notes' ); ?></h3>
						<span class="order-date"><?php echo wcdn_order_date(); ?></span>
					</li>
				</ul><!-- #order-info -->
					
				<div id="order-items">
					<table>
						<thead>
							<tr>
								<th class="description" id="description-label"><?php _e( 'Product Name', 'woocommerce-delivery-notes' ); ?></th>
								<th class="quantity" id="quantity-label"><?php _e( 'Quantity', 'woocommerce-delivery-notes' ); ?></th>
								<th class="price" id="price-label"><?php _e( 'Price', 'woocommerce-delivery-notes' ); ?></th>
							</tr>
						</thead>
						<tfoot>
							<?php $items = wcdn_get_order_items(); foreach( $items as $item ) : ?><tr>
								<td class="description"><?php echo $item['name']; ?><?php if( $item['variation'] ) : ?> <span class="variation"><?php echo $item['variation']; ?></span><?php endif; ?>
<?php if( $item['sku'] || $item['weight'] ) : ?><br /><?php endif; ?><?php if( $item['sku'] ) : ?><span class="sku"><?php _e( 'SKU:', 'woocommerce-delivery-notes' ); ?> <?php echo $item['sku']; ?></span><?php endif; ?><?php if( $item['weight'] ) : ?><span class="weight"> <?php _e( 'Weight:', 'woocommerce-delivery-notes' );
	echo '&nbsp;' . $item['weight'];
	echo '&nbsp;' . get_option( 'woocommerce_weight_unit' ); ?></span><?php endif; ?></td>
								<td class="quantity"><?php echo $item['quantity']; ?></td>
								<td class="price"><?php echo $item['price']; ?></td>
							<tr><?php endforeach; ?>
						</tfoot>
					</table>
				</div><!-- #order-items -->
				
				<div id="order-summary">
					<table>
						<tbody>
							<tr>
								<th class="description" id="subtotal-label"><?php _e( 'Subtotal', 'woocommerce-delivery-notes' ); ?></th>
								<td class="price" id="subtotal-number"><?php echo wcdn_order_subtotal(); ?></td>
							</tr>
							<?php if ( wcdn_has_shipping() ) : ?>
							<tr>
								<th class="description" id="tax-label"><?php _e( 'Shipping', 'woocommerce-delivery-notes' ); ?></th>
								<td class="price" id="tax-number"><?php echo wcdn_order_shipping(); ?></td>
							</tr>
							<?php endif; ?>
							<?php if( wcdn_has_tax() ) : ?>
							<tr>
								<th class="description" id="tax-label"><?php _e( 'Tax', 'woocommerce-delivery-notes' ); ?></th>
								<td class="price" id="tax-number"><?php echo wcdn_order_tax(); ?></td>
							</tr>
							<?php endif; ?>
							<?php if( wcdn_has_discount() ) : ?>
							<tr>
								<th class="description" id="tax-label"><?php _e( 'Discount', 'woocommerce-delivery-notes' ); ?></th>
								<td class="price" id="tax-number"><?php echo wcdn_order_discount(); ?></td>
							</tr>
							<?php endif; ?>
							<tr>
								<th class="description" id="total-label"><?php _e( 'Grand Total', 'woocommerce-delivery-notes' ); ?></th>
								<td class="price" id="total-number"><?php echo wcdn_order_total(); ?></td>
							</tr>
						</tbody>
					</table>
				</div><!-- #order-summery -->
	
				<div id="order-notes">
					<div class="notes-shipping">
						<?php if ( wcdn_shipping_notes() ) : ?>
							<h3><?php _e( 'Customer Notes', 'woocommerce-delivery-notes' ); ?></h3>
							<?php echo wcdn_shipping_notes(); ?>
						<?php endif; ?>
					</div>
					<div class="notes-personal"><?php echo wcdn_personal_notes(); ?></div>
				</div><!-- #order-notes -->
					
				<?php if ( wcdn_policies_conditions() || wcdn_footer_imprint() ) : ?>
					<div id="letter-footer">
						<div class="policies"><?php echo wcdn_policies_conditions(); ?></div>
						<div class="imprint"><?php echo wcdn_footer_imprint(); ?></div>
					</div><!-- #letter-footer -->
				<?php endif; ?>
			</div><!-- #page -->
		</div><!-- #content -->
		
		<div id="footer">
		</div><!-- #footer -->
	</div><!-- #container -->
</body>
</html>
