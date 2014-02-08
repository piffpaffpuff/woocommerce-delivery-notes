<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php wcdn_template_title(); ?></title>
	<?php wcdn_head(); ?>
	<link rel="stylesheet" href="<?php wcdn_stylesheet_url( 'style.css' ); ?>" type="text/css" media="screen,print" />
</head>
<body class="<?php echo wcdn_get_template_type(); ?>">
	<div id="container">
		<?php wcdn_navigation(); ?>
		<div id="page">