jQuery(document).ready(function($){
	wdgInitPhotos($);
});

function wdgInitPhotos($) {
	$('.wp_pg_upload_button').click(function(event) {
		event.preventDefault();
		// media_upload();
	});
}