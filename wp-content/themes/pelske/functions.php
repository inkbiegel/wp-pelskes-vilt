<?php
/**
 * Pelske functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Pelske
 */

//set global variables
// CUR_LANG = pll_current_language();


if ( ! function_exists('plsk_setup') ) :

	function plsk_setup() {

		// Necessary for Yoast SEO to manage the page titles
		add_theme_support('title-tag');

		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );

		add_theme_support( 'post-thumbnails', array( 'post' ) );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'pelske' ),
		) );

		// Load text domain for translated strings
		load_theme_textdomain( 'pelske', get_template_directory() . '/languages' );

	}
endif;
add_action( 'after_setup_theme', 'plsk_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function plsk_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'plsk_content_width', 1280 );
}
add_action( 'after_setup_theme', 'plsk_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function plsk_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__('Sidebar', 'pelske'),
		'id'            => 'sidebar-1',
		'description'   => esc_html__('Add widgets here.', 'pelske'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
}
add_action('widgets_init', 'plsk_widgets_init');

/**
 * Add critical css as inline <style> in head, mostly to hide initial page load for preloader
 */
add_action('wp_head', 'plsk_critical_css', 3);
function plsk_critical_css() {
	echo
	"<script>
		document.querySelector('#overlay').classList.add('on-load');
	</script>
	<style>
	 	.overlay.on-load {
			display: flex;
		}
		.overlay.on-load > .site-logo {
			display: none;
		}
		.overlay {
			display: none;
			position: fixed;
			top: 0; left: 0;
			width: 100vw;
			height: 100vh;
			z-index: 50;
		}
		.overlay.mask,
		.overlay.on-load {
			background: #f0e3fa;
		}
		</style>";
}

/**
 * Enqueue scripts and styles.
 */
function pelske_scripts() {

	// Enqueue styles
	wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css' );
	wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Delius|Nunito', null );
	wp_enqueue_style( 'pelske-style', get_stylesheet_uri(), array( 'normalize', 'fonts' ) );

	// Get version number WP jQuery, then deregister so we can register the probably cached version from google
	$version = $GLOBALS['wp_scripts']->registered['jquery']->ver;
	$version = str_replace( '-wp', '', $version );
	wp_deregister_script( 'jquery' );

  wp_enqueue_script(
    'jquery',
    "//ajax.googleapis.com/ajax/libs/jquery/{$version}/jquery.min.js",
    array(),
    $version,
    true
	);
	// Local fallback in case googleapis is down..
	if ( ! wp_script_is( 'jquery', $list = 'enqueued' ) ) {
		wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/vendor/jquery-1.12.4.min.js', array(), null, true );
	}

	// Load general script files
	$theme_dir = get_template_directory_uri();
	wp_enqueue_script( 'pelske-plugins', $theme_dir . '/js/plugins.js', array(), '20182525', true );
	wp_enqueue_script( 'animejs', $theme_dir . '/js/vendor/anime.min.js', array(), null, true );
	wp_register_script( 'pelske-main', $theme_dir . '/js/main.js', array( 'jquery', 'pelske-plugins', 'animejs' ), '20182525', true );
	wp_localize_script( 'pelske-main', '$php_var_theme_dir', $theme_dir );
	wp_enqueue_script( 'pelske-main' );


	// Load page specific script files
	if( is_page_template( 'gallery.php' ) ){
		wp_enqueue_script( 'pinterest', '//assets.pinterest.com/js/pinit.js', array(), false, false );
		wp_script_add_data( 'pinterest', 'defer', true );
		wp_script_add_data( 'pinterest', 'async', true );
	}

	if( is_page_template( 'contact-form.php' ) || is_page_template( 'guestbook.php' ) ){

		$localize_data = array(
			'error_empty' => __( 'Please fill out this field.', 'pelske' ),
			'error_type' => __( 'Please use the correct input type.', 'pelske' ),
			'error_email' => __( 'Please enter an email address.', 'pelske' ),
			'error_pattern' => __( 'Please match the requested format.', 'pelske' ),
			'error_generic' => __( 'The value you entered for this field is invalid.', 'pelske' )
		);

		wp_register_script( 'contact', get_template_directory_uri() . '/js/contact.js', array( 'jquery' ), '', false );
		wp_localize_script( 'contact', '$php_vars', $localize_data );
		wp_enqueue_script( 'contact' );
		wp_script_add_data( 'contact', 'defer', true );
		wp_script_add_data( 'contact', 'async', true );

		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array( 'contact' ), false, false );
		wp_script_add_data( 'recaptcha', 'defer', true );
		wp_script_add_data( 'recaptcha', 'async', true );

	}

}
add_action( 'wp_enqueue_scripts', 'pelske_scripts' );


 /**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Add site logo to main navigation
 */
function plsk_add_logo_to_nav($items, $args) {
	if($args->theme_location == 'menu-1'){
		// Write svg to buffer so dynamic gradient can be set
		ob_start();
		include('template-parts/logo-pelskes-vilt_circle.svg.php');
		$getContent = ob_get_contents();
		ob_end_clean();
		// Insert li with logo after the third closing li
		$needle = '</li>';
		$insert_text = '<li class="menu-item menu-item-logo"><a href="' . get_home_url() . '" class="site-logo">' . $getContent . '</a></li>';
		$insert_pos = strposOffset($needle, $items, 3) + strlen($needle);
		$items = substr_replace($items, $insert_text, $insert_pos, 0);
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'plsk_add_logo_to_nav', 10, 2);

/**
 * Add class to menu anchors
*/
function plsk_add_atts_to_menu_links( $atts, $item, $args ) {
	if( $args->theme_location == 'menu-1' ) {
		$atts['class'] = 'menu-link';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'plsk_add_atts_to_menu_links', 10, 3 );

/**
 * Get position of nth occurance of needle in haystack
 */
function strposOffset($needle, $haystack, $offset) {
	$arr = explode($needle, $haystack);
	switch( $offset )	{
		case $offset == 0:
		return false;
		break;

		case $offset > max(array_keys($arr)):
		return false;
		break;

		default:
		return strlen( implode($needle, array_slice($arr, 0, $offset)));
	}
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';