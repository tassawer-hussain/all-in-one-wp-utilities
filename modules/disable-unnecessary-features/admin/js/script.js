(function( $ ) {
	'use strict';

	$( window ).load(function() {

		jQuery('#btn-all-select').on( 'click', function() {
			jQuery('input[name^="aiowpu_disable_unnecessary_features_options"]').prop('checked', true);
		});
	
		jQuery('#btn-all-unselect').on( 'click', function() {
			jQuery('input[name^="aiowpu_disable_unnecessary_features_options"]').prop('checked', false);
		});
	
	});

})( jQuery );