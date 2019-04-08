jQuery(document).ready(function($) {
	'use strict';

	var $img = $('.item-image img, .category-desc img').first(),
			$hero = $('.pghero'),
			imgSrc = $img.length ?
				$img.attr('src')
				: '/images/stories/villagedancetheatre_1.jpg';

	return (
		$hero.css('background-image', 'url(' + imgSrc + ')') &&
		$img.remove()
	);

});
