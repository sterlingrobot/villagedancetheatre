jQuery(document).ready(function($) {
	'use strict';

	$('#navigation').add('.menu-icon').addClass('init');

	$('.menu-icon').on('click', function() {
		return $(this).add('#navigation').toggleClass('open');
	});

	$('.nav-header').on('click', function(e) {
		var $navItem = $(this);
		e.stopPropagation();
		return (
			$('.nav-header').not($navItem).removeClass('open') &&
			$navItem.toggleClass('open') &&
			$navItem.hasClass('open') &&
			$(document).off('click.nav').one('click.nav', function(e) {
				if(e.target.href) { return true; }
				return !$(e.target).is($navItem) &&
					!$(e.target).closest($navItem).length &&
					$navItem.removeClass('open');
			})
		);
	});

});
