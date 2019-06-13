(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	jQuery(document).ready(function ($) {
		$(document).on( "click", ".catch-infinite-scroll-upload-media-button", function (e) {
			e.preventDefault();
			var $button = $(this);

			// Create the media frame.
			var file_frame = wp.media.frames.file_frame = wp.media({
				title: 'Select or upload media',
				button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			file_frame.on('select', function () {
			 // We set multiple to false so only get one image from the uploader
			 	$button.siblings('.catch-infinite-scroll-reset-media-button').removeClass('ctis-hide');
				$button.text('Change');

				var attachment = file_frame.state().get('selection').first().toJSON();

				/* Show Reset Button on image change */
				$button.siblings('input').val(attachment.url);
				$button.siblings('span.ctis-image-holder').html('<img src="' + attachment.url + '">');
			});

			// Finally, open the modal
			file_frame.open();
		});

		$('.image-url').each(function(){
			$(this).on('keyup', function(){
				$(this).siblings('span.ctis-image-holder').html('<img src="' + $(this).val() + '">');

				/* Show Reset Button on image change */
				$(this).siblings('.catch-infinite-scroll-reset-media-button').removeClass('ctis-hide');
				$(this).siblings('.catch-infinite-scroll-upload-media-button').text('Change');
			});
		});

		/* Change image to default and hide remove Reset button */
		$(document).on( 'click', '.catch-infinite-scroll-reset-media-button', function (e) {
			e.preventDefault();
			$(this).siblings('input').val(default_options['image']);
			$(this).siblings('span.ctis-image-holder').find('img').attr('src', default_options['image'] );
			$(this).siblings('.catch-infinite-scroll-upload-media-button').text('Upload');
			$(this).addClass('ctis-hide');
		});

		$('.ctis-trigger').on('change', function(){
			if( 'click' === $(this).val() ){
				$('.ctis-more-text').parent().parent().show();
			}else{
				$('.ctis-more-text').parent().parent().hide();
			}
		});	
	});

	$(function() {

        // Tabs
        $('.catchp_widget_settings .nav-tab-wrapper a').on('click', function(e){
            e.preventDefault();

            if( !$(this).hasClass('ui-state-active') ){
                $('.nav-tab').removeClass('nav-tab-active');
                $('.wpcatchtab').removeClass('active').fadeOut(0);

                $(this).addClass('nav-tab-active');

                var anchorAttr = $(this).attr('href');

                $(anchorAttr).addClass('active').fadeOut(0).fadeIn(500);
            }

        });
    });

    // jQuery Match Height init for sidebar spots
    $(document).ready(function() {
        $('.catchp-sidebar-spot .sidebar-spot-inner, .col-2 .catchp-lists li, .col-3 .catchp-lists li').matchHeight();
    });

})( jQuery );

/**
 * Facebook Script
 */
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];

	if (d.getElementById(id)) return;

	js = d.createElement(s); js.id = id;

	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=276203972392824";

	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/**
 * Twitter Script
 */
!function(d,s,id){
	var js,fjs=d.getElementsByTagName(s)[0];

	if(!d.getElementById(id)){
		js=d.createElement(s);

		js.id=id;

		js.src="//platform.twitter.com/widgets.js";

		fjs.parentNode.insertBefore(js,fjs);
	}
}(document,"script","twitter-wjs");
