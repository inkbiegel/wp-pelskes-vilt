(function( $ ) {
	'use strict';

	$(document).ready(function(){

		$('html').removeClass('no-js').addClass('js');

		// let hasSeenPreloader = sessionStorage.getItem('preloaded') ? true : false;
		// if(!hasSeenPreloader) {
			showPreloader();
		// } else {
		// 	$('.overlay > .site-logo').remove();
		// };

	});

	function showPreloader(){
		// Show overlay
		let preloader = new Overlay('preloader');
		preloader.init();
		preloader.show();
		// Animate Logo
		animateSiteLogo(preloader);
		// Set SessionStorage
		sessionStorage.setItem('preloaded', true);
	}

	class Overlay {
		constructor(callerID) {
			this.callerID = callerID;
		}

		init() {
			// Disable page scroll
			$('body').addClass('has-overlay');
			// Append a single grid-item to get its dimensions
			$('#overlay .overlay-grid').css('visibility','hidden').append('<div class="overlay-grid-item"></div>');
			this.firstCell = $('#overlay .overlay-grid-item').first();
			this.gridItemHeight = this.firstCell.height();
			this.gridItemWidth = this.firstCell.width();
			this.nrOfCols = Math.ceil(window.innerWidth/this.gridItemWidth);
			this.nrOfRows = Math.ceil(window.innerHeight/this.gridItemHeight) + 1;
			this.nrOfItems = this.nrOfCols * this.nrOfRows;
			$('#overlay .overlay-grid').empty().css('visibility','visible');
		}

		show() {
			// Check if there's already a populated grid
			if($('#overlay .overlay-grid-item').length <= 0) {
				this.populateGrid();
			}
			// If not preloader anime stagger opacity of grid items
		}

		populateGrid() {
			let c = document.createDocumentFragment();
			for (let i=0; i<this.nrOfItems; i++) {
				let e = document.createElement('div');
				e.className = 'overlay-grid-item';
				c.appendChild(e);
			}
			document.querySelector('#overlay > .overlay-grid').appendChild(c);
		}

		hide(siteLogoOffset) {
			switch (this.callerID) {
				case 'preloader':
					let rowNumber = Math.round(siteLogoOffset.top/this.gridItemHeight);
					let colNumber = Math.round(this.nrOfCols/2);
					let fromIndex = rowNumber * this.nrOfCols + colNumber;
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 0,
						delay: anime.stagger(100, {grid: [this.nrOfCols, this.nrOfRows], from: fromIndex }),
						complete: function(anim){
							// Reenable page scroll
							$('body').removeClass('has-overlay');
							// Remove site logo
							$('#overlay .site-logo').remove();
						}
					})
					break;

				default:
					break;
			}
		}
	}

	function animateSiteLogo(preloader){
		anime({
			targets: '#overlay .site-logo .border .el',
			strokeDashoffset: [anime.setDashoffset, 0],
			easing: 'easeInOutCirc',
			duration: 2000,
			complete: function(anim){
				$('#overlay .site-logo').addClass('finished');
				moveSiteLogo(preloader);
			}
		});

		let tl = anime.timeline({
			duration: 150,
			easing: 'easeInOutCirc',
		});
		$('#overlay .site-logo .writing .el').each(function(){
			const targetIDs = '#overlay .site-logo .writing #' + $(this).attr('id');
			tl.add({
				targets: targetIDs,
				strokeDashoffset: [anime.setDashoffset, 0],
			});
		});
	}

	function moveSiteLogo(preloader){
		// Get position and size of overlay logo
		const overlayLogo = $('#overlay .site-logo');
		const overlayLogoOffset = overlayLogo.offset();
		const overlayLogoSize = overlayLogo.height();
		// Get position and size of visible site logo
		const siteLogo = $('.site-header .site-logo:visible');
		const siteLogoOffset = siteLogo.offset();
		const siteLogoSize = siteLogo.height();
		// Animate overlay logo to site logo position and size
		anime({
			targets: '#overlay .site-logo',
			duration: 500,
			delay: 400,
			translateY: -(overlayLogoOffset.top - siteLogoOffset.top),
			scale: siteLogoSize / overlayLogoSize,
			complete: function(anim){
				preloader.hide(siteLogoOffset);
			}
		});
	}

})( jQuery );