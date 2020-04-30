(function( $ ) {
	'use strict';

	$(document).ready(function() {

		$('html').removeClass('no-js').addClass('js');

		if( $('.site-logo').length > 0 ) { animateSiteLogo();	};

	});

	function animateSiteLogo() {
		anime({
			targets: '.site-logo .border .el',
			strokeDashoffset: [anime.setDashoffset, 0],
			easing: 'linear',
			duration: 2000,
			complete: function(anim){
				$('.site-logo').addClass('finished');
			}
		});

		var tl = anime.timeline({
			duration: 150,
			easing: 'linear',
		});
		$('.site-logo .writing .el').each(function(){
			var targetIDs = '.site-logo .writing #' + $(this).attr('id');
			tl.add({
				targets: targetIDs,
				strokeDashoffset: [anime.setDashoffset, 0],
			})

		})

	}

})( jQuery );