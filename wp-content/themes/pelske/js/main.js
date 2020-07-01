(function( $ ) {
	'use strict';

	let preloader,
			hasSeenPreloader = sessionStorage.getItem('preloaded') ? true : false;

	$(document).ready(function(){

		$('html').removeClass('no-js').addClass('js');

		if(!hasSeenPreloader) {
			showPreloader();
		} else {
			$('#overlay').removeClass('on-load');
			$('#overlay > .site-logo').remove();
		};

		navHandler();
		if( $('#gallery').length > 0 ) {
			galleryHandler();
		}
		if( $('#guestbook-form').length > 0 ) {
			formToggler();
		}
		footerLinkAnimations();

	});

	function showPreloader(){
		// Make sure we are at top of page
		window.scrollTo(0,0);
		// Show overlay
		preloader = new Overlay('preloader');
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
			this.gridIsPopulated = $('#overlay .overlay-grid-item').length <= 0 ? false : true;
			this.overlayIsUp = false;
			this.gridItemHeight = 0;
			this.gridItemWidth = 0;
			this.nrOfCols = 0;
			this.nrOfRows = 0;
			this.nrOfItems = 0;
		}

		init() {
			this.measureGrid();
			if( !this.gridIsPopulated ) {
				this.populateGrid();
			}
			this.resizeHandler();
		}

		measureGrid() {
			// Disable page scroll and show overlay to get measurement (visibility is hidden)
			$('body').addClass('has-overlay');
			// Check if there's already a populated grid e.g. from preloader
			if( !this.gridIsPopulated ) {
				// Append a single grid-item to get its dimensions
				$('#overlay .overlay-grid').css('visibility','hidden').append('<div class="overlay-grid-item"></div>');
				// Store measurements
				this.setVariables();
				$('#overlay .overlay-grid').empty().css('visibility','visible');
			} else {
				// Store measurements
				this.setVariables();
			}
			// Reenable scrollbars if overlay is not yet visible
			if( this.callerID !== 'preloader' && !$('#overlay').hasClass('overlay__preloader') && !this.overlayIsUp ) {
				$('body').removeClass('has-overlay');
			}
		}

		setVariables() {
			this.firstCell = $('#overlay .overlay-grid-item').first();
			this.gridItemHeight = this.firstCell.height();
			this.gridItemWidth = this.firstCell.width();
			// Determine nr of squares needed to fill screen
			this.nrOfCols = Math.ceil(window.innerWidth/this.gridItemWidth);
			this.nrOfRows = Math.ceil(window.innerHeight/this.gridItemHeight);
			this.nrOfItems = this.nrOfCols * this.nrOfRows;
		}

		populateGrid() {
			$('#overlay > .overlay-grid').empty();
			let c = document.createDocumentFragment();
			for (let i=0; i<this.nrOfItems; i++) {
				let e = document.createElement('div');
				e.className = 'overlay-grid-item';
				c.appendChild(e);
			}
			document.querySelector('#overlay > .overlay-grid').appendChild(c);
			this.gridIsPopulated = true;
		}

		show() {
			$('body').addClass('has-overlay');
			switch(this.callerID){
				// The preloader does not need to animate the grid in
				case 'preloader':
					$('#overlay').removeClass('on-load');
					$('#overlay').addClass('overlay__preloader');
					if(this.callerID === 'preloader'){
						$('#overlay .overlay-grid-item').css('opacity','1');
						$('#site-nav > .menu-toggle').addClass('hidden');
					}
					break;
				// If not preloader anime stagger opacity of grid items
				case 'mainNav':
					$('#overlay').addClass('overlay__nav');
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 1,
						delay: anime.stagger(70, {grid: [this.nrOfCols, this.nrOfRows], from: 'first' }),
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
					$('#overlay').addClass('overlay__gallery');
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 1,
						delay: anime.stagger(90, {grid: [this.nrOfCols, this.nrOfRows], from: this.clickPosOnGrid }),
						update: function(anim) {
							if(Math.round(anim.progress) === 50) {
								$('#overlay').addClass('complete');
							}
						}
					});
					this.setupButtons();
					break;
			}
			this.overlayIsUp = true;
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

		hide(siteLogoOffset, immediate) {
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
						},
						complete: function(){
							// destroy instance as we won't be showing it anymore this session
							$('#overlay').removeClass('overlay__preloader');
							preloader = null;
						}
					})
					break;

				case 'mainNav':
					if(!immediate) {
						anime({
							targets: '#overlay .overlay-grid-item',
							opacity: 0,
							delay: anime.stagger(70, {grid: [this.nrOfCols, this.nrOfRows], from: 'last' }),
							begin: function(anim){
								const siteNav = $('#site-nav');
								// Toggle nav into position and fade it in with transition in CSS
								siteNav.removeClass('toggled');
								siteNav.children('.menu-toggle').attr('aria-expanded', 'false');
								siteNav.children('#primary-menu').attr( 'aria-expanded', 'false' );
							},
							update: function(anim){
								if(Math.round(anim.progress) === 50){
									$('#site-nav > .menu-toggle').removeClass('toggled');
									// Reenable page scroll
									$('body').removeClass('has-overlay');
								}
							},
							complete: function(){
								$('#overlay').removeClass('overlay__nav');
							}
						})
					} else {
						const siteNav = $('#site-nav');
						// Toggle nav into position and fade it in with transition in CSS
						siteNav.removeClass('toggled');
						siteNav.children('.menu-toggle').attr('aria-expanded', 'false');
						siteNav.children('#primary-menu').attr( 'aria-expanded', 'false' );
						$('#site-nav > .menu-toggle').removeClass('toggled');
						$('body').removeClass('has-overlay');
						$('#overlay').removeClass('overlay__nav');
						$('#overlay .overlay-grid-item').css('opacity', '0');
					}
					break;

				case 'gallery':
					let lastCellFirstRowIndex = this.nrOfCols - 1;
					anime({
						targets: '#overlay .overlay-grid-item',
						opacity: 0,
						delay: anime.stagger(70, {grid: [this.nrOfCols, this.nrOfRows], from: lastCellFirstRowIndex }),
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
			this.overlayIsUp = false;
		}

		getClickPosOnGrid(clickOffset){
			const gridPosX = Math.round(clickOffset.left / this.gridItemWidth);
			const gridPosY = Math.round(clickOffset.top / this.gridItemHeight);
			this.clickPosOnGrid = (this.nrOfCols * gridPosY) + gridPosX + 1;
		}

		resizeHandler(){
			let that = this;
			// only fires at start of resize
			let maskGrid = debounce(function() {
				if(that.overlayIsUp) {
					// add class .mask to overlay to match the bg to the squares
					$('#overlay').addClass('mask');
				}
			}, 250, true);
			window.addEventListener('resize', maskGrid);

			// only fires at end of resize
			let resizeGrid = debounce(function() {
				// measure grid
				that.measureGrid();
				// repopulate
				that.populateGrid();
				if(that.overlayIsUp) {
					if(that.callerID === 'mainNav' && window.innerWidth >= 800) {
						that.hide(null, true);
					}
					$('#overlay .overlay-grid-item').css('opacity','1');
					$('#overlay').removeClass('mask');
				}
			}, 250, false);

			window.addEventListener('resize', resizeGrid);

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
		// Get potential scrollbar width
		const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
		// Animate overlay logo to site logo position and size
		anime({
			targets: '#overlay .site-logo',
			duration: 500,
			delay: 400,
			translateY: -(overlayLogoOffset.top - siteLogoOffset.top),
			translateX: -(scrollBarWidth/2),
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
		// var container, button, menu, links, i, len;

		const siteNavigation = document.getElementById( 'site-nav' );

		// Return early if the navigation don't exist.
		if ( ! siteNavigation ) {
			return;
		}

		const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

		// Return early if the button don't exist.
		if ( 'undefined' === typeof button ) {
			return;
		}

		const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

		// Hide menu toggle button if menu is empty and return early.
		if ( 'undefined' === typeof menu ) {
			button.style.display = 'none';
			return;
		}

		if ( ! menu.classList.contains( 'nav-menu' ) ) {
			menu.classList.add( 'nav-menu' );
		}

		let navOverlay = new Overlay('mainNav');
		navOverlay.init();

		// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
		button.addEventListener( 'click', function() {

			if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
				button.setAttribute( 'aria-expanded', 'false' );
				navOverlay.hide();
			} else {
				button.setAttribute( 'aria-expanded', 'true' );
				navOverlay.show();
			}
		} );

		// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
		document.addEventListener( 'click', function( event ) {
			const isClickInside = siteNavigation.contains( event.target );

			if ( ! isClickInside ) {
				siteNavigation.classList.remove( 'toggled' );
				button.setAttribute( 'aria-expanded', 'false' );
			}
		} );

		// Get all the link elements within the menu.
		const links = menu.getElementsByTagName( 'a' );

		// Get all the link elements with children within the menu.
		const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

		// Toggle focus each time a menu link is focused or blurred.
		for ( const link of links ) {
			link.addEventListener( 'focus', toggleFocus, true );
			link.addEventListener( 'blur', toggleFocus, true );
		}

		// Toggle focus each time a menu link with children receive a touch event.
		for ( const link of linksWithChildren ) {
			link.addEventListener( 'touchstart', toggleFocus, false );
		}

		/**
		 * Sets or removes .focus class on an element.
		 */
		function toggleFocus() {
			if ( event.type === 'focus' || event.type === 'blur' ) {
				let self = this;
				// Move up through the ancestors of the current link until we hit .nav-menu.
				while ( ! self.classList.contains( 'nav-menu' ) ) {
					// On li elements toggle the class .focus.
					if ( 'li' === self.tagName.toLowerCase() ) {
						self.classList.toggle( 'focus' );
					}
					self = self.parentNode;
				}
			}

			if ( event.type === 'touchstart' ) {
				const menuItem = this.parentNode;
				event.preventDefault();
				for ( const link of menuItem.parentNode.children ) {
					if ( menuItem !== link ) {
						link.classList.remove( 'focus' );
					}
				}
				menuItem.classList.toggle( 'focus' );
			}
		}

	};

function galleryHandler() {
	let galleryOverlay = new Overlay('gallery');
	galleryOverlay.init();

	const btnPinterest = $('#btnSharePinterest');

	$('#gallery').on('click', '.grid-list-link', function(event){
		event.preventDefault();
		const imgUrl = $(this).attr('href');
		const galleryImg = $('<img class="gallery-img-full" src="' + imgUrl + '" />');
		// insert full image into overlay
		$('#overlay').prepend(galleryImg);
		// get click pos on grid for starting pos stagger animation
		galleryOverlay.getClickPosOnGrid( { left: $(this).offset().left, top: event.clientY } );
		// show overlay
		galleryOverlay.show();
		// Change share image
		setGalleryShareImg(imgUrl);
	})

	function setGalleryShareImg(imgUrl) {
		// get original image url from optimole url
		const endIndex = imgUrl.indexOf('http', 5);
		const newImgUrl = imgUrl.substr(endIndex);

		// Set Pinterest share URL
		const oldPinterestURL = btnPinterest.attr('href');
		const substr = oldPinterestURL.substring( oldPinterestURL.indexOf('&media='), oldPinterestURL.indexOf('&description=') );
		btnPinterest.attr('href', oldPinterestURL.replace(substr, '&media=' + newImgUrl));

		// Change Facebook og:image meta tag
		$('meta[property="og:image"]').attr('content', newImgUrl);

	}

}

function formToggler(){
	let button = $('#btnShowForm');
	let elements = $('#guestbook-form-wrapper, #guestbook-form');
	let isToggled = false;
	const lang = $('html').attr('lang').substr(0,2);
	const defaultText = button.text();
	button.on('click', function(e){
		e.preventDefault();
		if(isToggled){
			elements.removeClass('toggled');
			isToggled = false;
			$(this).text(defaultText);
		} else {
			elements.addClass('toggled');
			isToggled = true;
			if(lang === 'nl') {
				$(this).text('Verberg formulier');
			} else {
				$(this).text('Hide form');
			}
		}
	});
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
			duration: 500,
		});
	})
}

})( jQuery );