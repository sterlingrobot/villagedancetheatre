jQuery(document).ready(function($) {
	'use strict';

	var $bkgnd = $('.carousel-bkgnd');

	$('#carousel-home').on('slid.bs.carousel', function(e) {

		var $img = $(e.relatedTarget).find('img');
		$bkgnd.css('background-image', 'url(' + $img.attr('src') + ')');

	});
});
