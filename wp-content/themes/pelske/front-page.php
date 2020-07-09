<?php
/**
 * The template for displaying the homepage
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pelske
 */

get_header();
$field_subtitle = ( pll_current_language() === 'nl' ) ? 'subtitle_NL' : 'subtitle_EN';
$field_intro_txt = ( pll_current_language() === 'nl' ) ? 'intro_text_NL' : 'intro_text_EN';
$info_slug = ( pll_current_language() === 'nl' ) ? 'nl/info' : 'about';
?>

	<main id="main" class="site-main">
		<h1>
			<span>Pelskes Vilt</span><br>
			<span class="subtitle"><?php echo esc_html( get_field('subtitle') ); ?></span>
		</h1>
		<div class="home-img home-img__small home-img__intro"><?php echo wp_get_attachment_image( get_field('intro_img'), 'medium', false ); ?></div>
		<div class="home-intro-txt">
			<?php
				$intro_text = get_field('intro_text');
				$intro_text = apply_filters('the_content', $intro_text);
				echo $intro_text;
			?>
			<a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/' . $info_slug . '/els'; ?>" class="button anim-gradient-flash"><?php _e( 'More about Els', 'pelske' ); ?></a>
		</div>
		<?php
			$nr_img_s = 6;
			$nr_img_m = 4;
			for ($i=1; $i < $nr_img_s+1; $i++) {
				echo '<div class="home-img home-img__small home-img__small-' . $i . '">';
				echo wp_get_attachment_image( get_field('home_img_small_' . $i), 'thumbnail', false );
				echo '</div>';
			}
			for ($i=1; $i < $nr_img_m+1; $i++) {
				echo '<div class="home-img home-img__medium home-img__medium-' . $i . '">';
				echo wp_get_attachment_image( get_field('home_img_medium_' . $i), 'large', false );
				echo '</div>';
			}
		?>
		<div class="home-img home-img__large"><?php echo wp_get_attachment_image( get_field('home_img_large'), 'full', false ); ?></div>
	</main>

<?php
get_sidebar();
get_footer();
