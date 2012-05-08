jQuery(document).ready(function($) {

	// button to send media to content
	$('#media-upload .use-image-button').live('click', function(event) {
		var id = Number($(this).attr('id').substr(17));
		var image = $(this).closest('.describe').find('.thumbnail').attr('src');
		var win = window.dialogArguments || opener || parent || top;
		win.sendImageToContent(id, image);
		event.preventDefault();
	});
});