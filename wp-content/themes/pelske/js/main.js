(function( $ ) {
	'use strict';

	$(document).ready(function(){

		$('html').removeClass('no-js').addClass('js');

		let hasSeenPreloader = sessionStorage.getItem('preloaded') ? true : false;
		if(!hasSeenPreloader) {
			showPreloader();
		} else {
			$('.overlay > .site-logo').remove();
		};

		navHandler();
		if( $('#gallery').length > 0 ) {
			galleryHandler();
		}
		footerLinkAnimations();

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
			// Check if there's already a populated grid e.g. from preloader
			this.gridIsPopulated = $('#overlay .overlay-grid-item').length <= 0 ? false : true;
			if(!this.gridIsPopulated) {
				// Append a single grid-item to get its dimensions
				$('#overlay .overlay-grid').css('visibility','hidden').append('<div class="overlay-grid-item"></div>');
				// Store measurements
				this.setVariables();
				$('#overlay .overlay-grid').empty().css('visibility','visible');
				// Populate grid
				this.populateGrid();
			} else {
				// Store measurements
				this.setVariables();
			}
			// Reenable scrollbars if overlay is not yet visible
			if(this.callerID === 'gallery') {
				$('body').removeClass('has-overlay');
			}
		}

		setVariables() {
			this.firstCell = $('#overlay .overlay-grid-item').first();
			this.gridItemHeight = this.firstCell.height();
			this.gridItemWidth = this.firstCell.width();
			// Determine nr of squares needed to fill screen
			this.nrOfCols = Math.ceil(window.innerWidth/this.gridItemWidth);
			this.nrOfRows = Math.ceil(window.innerHeight/this.gridItemHeight) + 1;
			this.nrOfItems = this.nrOfCols * this.nrOfRows;
		}

		show() {
			switch(this.callerID){
				// The preloader does not need to animate the grid in
				case 'preloader':
					if(this.callerID === 'preloader'){
						$('#overlay .overlay-grid-item').css('opacity','1');
						$('#site-nav > .menu-toggle').addClass('hidden');
					}
					break;
				// If not preloader anime stagger opacity of grid items
				case 'mainNav':
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 1,
						delay: anime.stagger(100, {grid: [this.nrOfCols, this.nrOfRows], from: 'first' }),
						begin: function(anim){
							$('#site-nav > .menu-toggle').addClass('toggled');
						},
						update: function(anim){
							if(Math.round(anim.progress) === 50){
								// Toggle nav into position and fade it in with transition in CSS
								const siteNav = $('#site-nav');
								siteNav.addClass('toggled');
								siteNav.children('.menu-toggle').attr('aria-expanded', 'true');
								siteNav.children('#primary-menu').attr( 'aria-expanded', 'true' );
							}
						}
					});
					break;
				case 'gallery':
					$('body').addClass('has-overlay');
					$('#overlay').addClass('overlay__gallery');
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 1,
						delay: anime.stagger(100, {grid: [this.nrOfCols, this.nrOfRows], from: this.clickPosOnGrid }),
						update: function(anim) {
							if(Math.round(anim.progress) === 50) {
								$('#overlay').addClass('complete');
							}
						}
					});
					this.setupButtons();
					break;
			}

		}

		setupButtons() {
			let overlay = this;
			$('#overlay .icon-anim-border').on('mouseenter', function(e){
				let that = this;
				anime({
					targets: that.querySelector('.border'),
					strokeDashoffset: [anime.setDashoffset, 0],
					easing: 'easeInOutCirc',
					duration: 500,
				});
			});
			$('#overlayBtnClose').on('click', function(e) {
				e.preventDefault();
				overlay.hide();
			});
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
						update: function(anim){
							if(Math.round(anim.progress) === 70){
								// Reenable page scroll
								$('body').removeClass('has-overlay');
								$('#site-nav > .menu-toggle.hidden').removeClass('hidden');
								// Remove site logo
								$('#overlay .site-logo').remove();
							}
						}
					})
					break;

				case 'mainNav':
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 0,
						delay: anime.stagger(100, {grid: [this.nrOfCols, this.nrOfRows], from: 'last' }),
						begin: function(anim){
							const siteNav = $('#site-nav');
							// Toggle nav into position and fade it in with transition in CSS
							siteNav.removeClass('toggled');
							siteNav.children('.menu-toggle').attr('aria-expanded', 'false');
							siteNav.children('#primary-menu').attr( 'aria-expanded', 'false' );
						},
						update: function(anim){
							if(Math.round(anim.progress) === 70){
								$('#site-nav > .menu-toggle').removeClass('toggled');
								// Reenable page scroll
								$('body').removeClass('has-overlay');
							}
						}
					})
					break;

				case 'gallery':
					let lastCellFirstRowIndex = this.nrOfCols - 1;
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 0,
						delay: anime.stagger(100, {grid: [this.nrOfCols, this.nrOfRows], from: lastCellFirstRowIndex }),
						begin: function(anim){
							$('#overlay').removeClass('complete');
						},
						update: function(anim){
							if(Math.round(anim.progress) === 70){
								// Remove image from overlay
								$('#overlay .gallery-img-full').remove();
								// Reenable page scroll
								$('body').removeClass('has-overlay');
							}
						},
						complete: function(){
							$('#overlay').removeClass('overlay__gallery');
						}
					})
					break;
			}
		}

		getClickPosOnGrid(clickOffset){
			const gridPosX = Math.round(clickOffset.left / this.gridItemWidth);
			const gridPosY = Math.round(clickOffset.top / this.gridItemHeight);
			this.clickPosOnGrid = (this.nrOfCols * gridPosY) + gridPosX + 1;
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

	/**
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
	function navHandler() {
		var container, button, menu, links, i, len;

		container = document.getElementById( 'site-nav' );
		if ( ! container ) {
			return;
		}

		button = container.getElementsByTagName( 'button' )[0];
		if ( 'undefined' === typeof button ) {
			return;
		}

		menu = container.getElementsByTagName( 'ul' )[0];

		// Hide menu toggle button if menu is empty and return early.
		if ( 'undefined' === typeof menu ) {
			button.style.display = 'none';
			return;
		}

		menu.setAttribute( 'aria-expanded', 'false' );
		if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
			menu.className += ' nav-menu';
		}

		let navOverlay = new Overlay('mainNav');

		button.onclick = function() {
			if ( -1 !== container.className.indexOf( 'toggled' ) ) {
				navOverlay.hide();
			} else {
				navOverlay.init();
				navOverlay.show();
			}
		};

		// Get all the link elements within the menu.
		links    = menu.getElementsByTagName( 'a' );

		// Each time a menu link is focused or blurred, toggle focus.
		for ( i = 0, len = links.length; i < len; i++ ) {
			links[i].addEventListener( 'focus', toggleFocus, true );
			links[i].addEventListener( 'blur', toggleFocus, true );
		}

		/**
		 * Sets or removes .focus class on an element.
		 */
		function toggleFocus() {
			var self = this;

			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					if ( -1 !== self.className.indexOf( 'focus' ) ) {
						self.className = self.className.replace( ' focus', '' );
					} else {
						self.className += ' focus';
					}
				}

				self = self.parentElement;
			}
		}

		/**
		 * Toggles `focus` class to allow submenu access on tablets.
		 */
		( function( container ) {
			var touchStartFn, i,
				parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

			if ( 'ontouchstart' in window ) {
				touchStartFn = function( e ) {
					var menuItem = this.parentNode, i;

					if ( ! menuItem.classList.contains( 'focus' ) ) {
						e.preventDefault();
						for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
							if ( menuItem === menuItem.parentNode.children[i] ) {
								continue;
							}
							menuItem.parentNode.children[i].classList.remove( 'focus' );
						}
						menuItem.classList.add( 'focus' );
					} else {
						menuItem.classList.remove( 'focus' );
					}
				};

				for ( i = 0; i < parentLink.length; ++i ) {
					parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
				}
			}
		}( container ) );
};

function galleryHandler() {
	let galleryOverlay = new Overlay('gallery');
	galleryOverlay.init();

	$('#gallery').on('click', '.grid-list-link', function(event){
		event.preventDefault();
		const imgUrl = $(this).attr('href');
		const pageUrl = window.location.href;
		const galleryImg = $('<img class="gallery-img-full" src="' + imgUrl + '" />');
		const btnPinterest =
			'<a class="icon-svg icon-social icon-anim-border" id="btnPinGalleryImg" href="https://www.pinterest.com/pin/create/button/?url=' + encodeURI(pageUrl) + '&media=' + imgUrl + '&description=Pelskes%Vilt" target="_blank">'
				+ '<svg role="img" title="Pin on Pinterest" aria-labelled-by="btnPinterestTitle" viewBox="0 0 48 48">'
					+ '<title id="btnPinterestTitle">Pin on Pinterest</title>'
					+ '<use xlink:href="#icon-pinterest"></use>'
					+ '<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>'
				+ '</svg>'
			+ '</a>';
		const clickOffset = $(this).offset();
		// insert full image and pinterest button into overlay
		$('#overlay').prepend(galleryImg, btnPinterest);
		// get click pos on grid for starting pos stagger animation
		galleryOverlay.getClickPosOnGrid(clickOffset);
		// show overlay
		galleryOverlay.show();
	})

}

function footerLinkAnimations(){
	const targets = $('#colophon .icon-social');
	targets.on('mouseenter', function(e){
		const iconID = $(this).attr('id');
		const border = '#' + iconID + ' .border';
		anime({
			targets: border,
			strokeDashoffset: [anime.setDashoffset, 0],
			easing: 'easeInOutCirc',
			duration: 1000,
		});
	})
}

})( jQuery );