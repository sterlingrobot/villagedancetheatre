(function($) {
	'use strict';

	/* globals gapi */
	function init() {

		gapi.load('client', function() {

			gapi.client.init({
				'apiKey': 'AIzaSyASKZIgcT0pptJqvimmYJBdNDqGbZ3wjPI',
				'client_id': '107955381732-76nj6f1vg9t2s9v570ikhv1oism00n4r.apps.googleusercontent.com',
				'scopes': 'https://www.googleapis.com/auth/plus.business.manage',
				'discoveryDocs': ['https://mybusiness.googleapis.com/$discovery/rest?version=v4'],
			}).then(function() {

			});
		});
	}

	return $.getScript('https://apis.google.com/js/api.js', init);

}(jQuery));
