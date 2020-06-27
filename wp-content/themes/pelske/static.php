<?php
/**
 * Template Name: Static
 *
 * @package Pelske
 */

get_header();
$n_text_blocks = 3;
?>
	<main id="main" class="site-main">
		<article>
			<?php
				esc_html( the_title('<h1><span>','</span></h1>') );
				for ($i=1; $i < $n_text_blocks+1; $i++) {
					$text = get_field('block_' . $i . '_text');
					$text = apply_filters('the_content', $text);
					echo '<section class="text-block">' .
									'<div class="text-block-img-wrap">' .
										wp_get_attachment_image( get_field('block_' . $i . '_img'), 'large', false, array( 'class' => 'text-block-img' ) ) .
									'</div>' .
									'<div class="text-block-copy">' . $text . '</div>' .
								'</section>';
				}
			?>
		</article>
	</main>
<?php
get_footer();