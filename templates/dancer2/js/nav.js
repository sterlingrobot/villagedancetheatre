jQuery(document).ready(function($) {
	'use strict';

	$('#navigation').add('.menu-icon').addClass('init');

	$('.menu-icon').on('click', function() {
		return $(this).add('#navigation').toggleClass('open');
	});

});
