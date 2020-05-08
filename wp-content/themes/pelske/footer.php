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
				<a href="https://www.facebook.com/pelske.elske" target="_social" class="icon-svg icon-social">
					<svg role="img" title="Follow me on Facebook" aria-labelled-by="iconFacebook">
						<title id="iconFacebook">Follow me on Facebook</title>
						<use xlink:href="#icon-facebook"/>
					</svg>
				</a>
			</li>
			<li class="icon-list-item">
				<a href="https://www.instagram.com/pelske.elske" target="_social" class="icon-svg icon-social">
					<svg role="img" title="Follow me on Instagram" aria-labelled-by="iconInstagram">
						<title id="iconInstagram">Follow me on Instagram</title>
						<use xlink:href="#icon-instagram"/>
					</svg>
				</a>
			</li>
			<li class="icon-list-item">
				<a href="https://www.pinterest.com/pelske/" target="_social" class="icon-svg icon-social">
					<svg role="img" title="Follow me on Pinterest" aria-labelled-by="iconPinterest">
						<title id="iconPinterest">Follow me on Pinterest</title>
						<use xlink:href="#icon-pinterest"/>
					</svg>
				</a>
			</li>
		</ul>
	</footer>
	<div class="overlay" id="overlay">
		<div class="site-logo"><?php get_template_part( 'template-parts/logo-pelskes-vilt_circle.svg' ); ?></div>
		<div class="overlay-grid"></div>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
