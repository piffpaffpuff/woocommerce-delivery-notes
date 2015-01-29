<?php

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

/** 
 * Header
 */
add_action( 'wcdn_head', 'wcdn_navigation_style' );
add_action( 'wcdn_head', 'wcdn_template_stylesheet' );

/** 
 * Before page
 */
add_action( 'wcdn_before_page', 'wcdn_navigation' );

/** 
 * Content
 */
add_action( 'wcdn_loop_content', 'wcdn_content', 10, 2 );
add_filter( 'wcdn_order_item_fields', 'wcdn_additional_product_fields', 10, 3);
?>