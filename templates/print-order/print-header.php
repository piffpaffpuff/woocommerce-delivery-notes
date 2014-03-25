<?php
/**
 * Print order header
 *
 * @package WooCommerce Print Invoice & Delivery Note/Templates
 */
 
if ( !defined( 'ABSPATH' ) ) exit;
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title><?php wcdn_document_title(); ?></title>
	
	<?php
		// wcdn_head hook
		do_action( 'wcdn_head' );
	?>
</head>

<body class="<?php echo wcdn_get_template_type(); ?>">
	
	<div id="container">
	
		<?php
			// wcdn_head hook
			do_action( 'wcdn_before_page' );
		?>
				
		<div id="page">