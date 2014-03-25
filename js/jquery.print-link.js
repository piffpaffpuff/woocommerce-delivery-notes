(function($) {
		
	$.fn.printLink = function(options) {
		var iframe = null;
        var settings = $.extend({
            url: null
        }, options);
        
        // Apply to all elements
        return this.each(function() {
			
			// Click handler on the link
			$(this).on('click', function(event) {
				event.preventDefault();
	
				// Target element
				var element = $(this);

				// Get the url from the anchor or overwrite it
				var url = element.attr('href');
				if(settings.url) {
					url = settings.url;
				}
				
				// Open the url directly when an iframe printing is not supported.
				if(navigator.userAgent.match(/opera/i) || navigator.userAgent.match(/trident/i) || (navigator.userAgent.match(/msie/i) && window.addEventListener)) {				
					element.trigger('printLinkError');
					return false;
				}
				
				// Trigger load
				element.trigger('printLinkInit');
				
				// Print the url with a hidden iframe
				if(!$('#printLinkIframe')[0]) {
					// Create a new iframe
					var iframe = '<iframe id="printLinkIframe" name="printLinkIframe" src=' + url + ' style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>';
					$('body').append(iframe);
		
					// Start the printing when the url is loaded
					$('#printLinkIframe').on('load',function() {  
						element.trigger('printLinkComplete');
						frames['printLinkIframe'].focus();
						frames['printLinkIframe'].print();
					});
				} else {
					// Change the iframe src in case the iframe already exists
					$('#printLinkIframe').attr('src', url);
				}
			});
			
		});
        
        return this;
    };
 
}(jQuery));