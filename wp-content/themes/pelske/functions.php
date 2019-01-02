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


if ( ! function_exists('pelske_setup') ) :

	function pelske_setup() {

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
add_action( 'after_setup_theme', 'pelske_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function pelske_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'pelske_content_width', 1280 );
}
add_action( 'after_setup_theme', 'pelske_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function pelske_widgets_init() {
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
add_action('widgets_init', 'pelske_widgets_init');


/**
 * Enqueue scripts and styles.
 */
function pelske_scripts() {

	// Enqueue styles
	wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css' );
	wp_enqueue_style( 'pelske-style', get_stylesheet_uri() );

	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/vendor/modernizr-3.6.0.min.js', array(), null, true );

	// Get version number WP jQuery, then deregister so we can register the probably cached version from google
	$version = $GLOBALS['wp_scripts']->registered['jquery']->ver;
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
	wp_enqueue_script( 'pelske-plugins', get_template_directory_uri() . '/js/plugins.js', array(), '20182525', true );
	wp_enqueue_script( 'pelske-main', get_template_directory_uri() . '/js/main.js', array( 'jquery', 'pelske-plugins' ), '20182525', true );

	// Load page specific script files
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
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';