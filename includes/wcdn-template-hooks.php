<?php

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
 * Orders loop
 */
add_action( 'wcdn_loop_content', 'wcdn_get_template_addresses' );
add_action( 'wcdn_loop_content', 'wcdn_get_template_items' );
add_action( 'wcdn_loop_content', 'wcdn_get_template_notes' );

?>