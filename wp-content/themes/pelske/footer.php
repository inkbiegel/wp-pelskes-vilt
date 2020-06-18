<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Pelske
 */

?>
	<footer id="colophon" class="site-footer">
		<ul class="icon-list txt-center">
			<li class="icon-list-item">
				<a href="https://www.facebook.com/pelske.elske" target="_social" class="icon-svg icon-social icon-anim-border" id="iconSocialFb">
					<svg role="img" title="Follow me on Facebook" aria-labelled-by="iconFacebookTitle" viewBox="0 0 48 48">
						<title id="iconFacebookTitle"><?php _e('Follow me on Facebook', 'pelske'); ?></title>
						<use xlink:href="#icon-facebook"></use>
						<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>
					</svg>
				</a>
			</li>
			<li class="icon-list-item">
				<a href="https://www.instagram.com/pelske.elske" target="_social" class="icon-svg icon-social icon-anim-border" id="iconSocialInstagram">
					<svg role="img" title="Follow me on Instagram" aria-labelled-by="iconInstagramTitle" viewBox="0 0 48 48">
						<title id="iconInstagramTitle"><?php _e('Follow me on Instagram', 'pelske'); ?></title>
						<use xlink:href="#icon-instagram"></use>
						<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>
					</svg>
				</a>
			</li>
			<li class="icon-list-item">
				<a href="https://www.pinterest.com/pelske/" target="_social" class="icon-svg icon-social icon-anim-border" id="iconSocialPinterest">
					<svg role="img" title="Follow me on Pinterest" aria-labelled-by="iconPinterestTitle" viewBox="0 0 48 48">
						<title id="iconPinterestTitle"><?php _e('Follow me on Pinterest', 'pelske'); ?></title>
						<use xlink:href="#icon-pinterest"></use>
						<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>
					</svg>
				</a>
			</li>
		</ul>
	</footer>
	<div class="overlay on-load" id="overlay">
		<button class="icon-svg icon-anim-border btn-close" id="overlayBtnClose">
			<svg role="img" title="Close" aria-labelled-by="btnCloseTitle" viewBox="0 0 48 48">
				<title id="btnCloseTitle"><?php _e('Close', 'pelske'); ?></title>
				<use xlink:href="#btn-close"></use>
				<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>
			</svg>
		</button>
		<div class="site-logo"><?php get_template_part( 'template-parts/logo-pelskes-vilt_circle.svg' ); ?></div>
		<div class="overlay-grid"></div>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
