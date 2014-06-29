jQuery(document).ready(function($) {
		
	/*
	 * Print button
	 */	 
	$('.woocommerce .button.print').printLink();
	$('.woocommerce .button.print').on('printLinkError', function(event) {
		window.open(event.currentTarget.href);
	});
	
});