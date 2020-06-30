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
		<nav class="footer-nav">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-2',
					'menu_id'        => 'footer-menu',
				) );
			?>
		</nav>
	</footer>
	<div class="overlay on-load" id="overlay">
		<button class="icon-svg icon-anim-border btn-close" id="overlayBtnClose">
			<svg role="img" title="Close" aria-labelled-by="btnCloseTitle" viewBox="0 0 48 48">
				<title id="btnCloseTitle"><?php _e('Close', 'pelske'); ?></title>
				<use xlink:href="#btn-close"></use>
				<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>
			</svg>
		</button>
		<?php
		if( is_page_template('gallery.php') ) :
			$share_text = urlencode( __( 'Pelskes Vilt ~ Felt designs handmade with love','pelske') );
			$page_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		?>
		<ul class="icon-list icon-list__vertical">
			<li class="icon-list-item">
				<a class="icon-svg icon-social icon-anim-border" id="btnSharePinterest" href="https://www.pinterest.com/pin/create/button/?url=<?php echo $page_url; ?>&media=IMGURL&description=<?php echo $share_text; ?>" target="_social">
					<svg role="img" title="<?php _e('Pin on Pinterest', 'pelske'); ?>" aria-labelled-by="btnPinterestTitle" viewBox="0 0 48 48">
						<title id="btnPinterestTitle"><?php _e('Pin on Pinterest', 'pelske'); ?></title>
						<use xlink:href="#icon-pinterest"></use>
						<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>
					</svg>
				</a>
			</li>
			<li class="icon-list-item">
				<a class="icon-svg icon-social icon-anim-border" id="btnShareFacebook" href="https://www.facebook.com/sharer.php?u=<?php echo $page_url; ?>" target="_social">
					<svg role="img" title="<?php _e('Share on Facebook', 'pelske'); ?>" aria-labelled-by="btnFacebookTitle" viewBox="0 0 48 48">
						<title id="btnFacebookTitle"><?php _e('Share on Facebook', 'pelske'); ?></title>
						<use xlink:href="#icon-facebook"></use>
						<circle class="border" fill="none" stroke-miterlimit="10" cx="24" cy="24" r="22.4" transform="rotate(-180 24 24)"/>
					</svg>
				</a>
			</li>
		</ul>
		<?php endif; ?>
		<div class="site-logo"><?php get_template_part( 'template-parts/logo-pelskes-vilt_circle.svg' ); ?></div>
		<div class="overlay-grid"></div>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
