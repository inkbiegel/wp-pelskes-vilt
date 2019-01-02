<?php
/**
 *
 *	Plugin Name: Pelske's Vilt
 *	Description: General site plugin for all non theme related functions
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  wp_die('Nothing to see here, move along...');
}

define( 'PELSKE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/***************************************************************************
 * DEFAULT WORDPRESS CLEAN UP
 **************************************************************************/

//Disable the emoji's (src: https://kinsta.com/knowledgebase/disable-emojis-wordpress/)
function disable_emojis() {
	remove_action('wp_head', 'print_emoji_detection_script', 7 );
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
	add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
	add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'disable_emojis');

function disable_emojis_tinymce($plugins) {

 	if ( is_array( $plugins) ) {

 		return array_diff( $plugins, array('wpemoji') );

 	} else {

 		return array();

 	}
}

function disable_emojis_remove_dns_prefetch($urls,$relation_type) {

 	if ('dns-prefetch' == $relation_type) {

	 	/** This filter is documented in wp-includes/formatting.php */
	 	$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

		$urls = array_diff($urls,array($emoji_svg_url));

 	}

	return $urls;
}

function clear_out_crap(){

	// Clean up the <head> (src: http://www.whatabouthtml.com/how-to-clean-up-unnecessary-code-from-wordpress-header-175)
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_shortlink_wp_head');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
	remove_action('wp_head', 'rest_output_link_wp_head', 10 );
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_action('rest_api_init', 'wp_oembed_register_route');
	remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
	// No auto RSS Post feeds (src: http://cubiq.org/clean-up-and-optimize-wordpress-for-your-next-theme)
	remove_action('wp_head', 'feed_links_extra', 3);
	// Hide all admin stuff on the front end
	// add_filter('show_admin_bar','__return_false');
	add_filter('edit_post_link', '__return_false');
}
add_action('after_setup_theme', 'clear_out_crap');

// Completely disable Gutenberg
add_filter('gutenberg_can_edit_post_type', '__return_false');


/***************************************************************************
 * GENERAL
 **************************************************************************/

/**
 * Allows adding of async and/or defer attributes to script tags
 * src: https://gist.github.com/wpscholar/7b343ec2a8c52752dbc0
 * usage: wp_script_add_data( $handle, 'async'/'defer', true ); after script is enqueued
 */
class Pelske_Defer_Scripts {
  public static function initialize() {
    add_filter( 'script_loader_tag', array( __CLASS__, 'defer_scripts' ), 10, 2 );
    add_filter( 'script_loader_tag', array( __CLASS__, 'async_scripts' ), 10, 2 );
  }
  public static function defer_scripts( $tag, $handle ) {
    if ( wp_scripts()->get_data( $handle, 'defer' ) ) {
      $tag = str_replace( '></', ' defer></', $tag );
    }
    return $tag;
  }
  public static function async_scripts( $tag, $handle ) {
    if ( wp_scripts()->get_data( $handle, 'async' ) ) {
      $tag = str_replace( '></', ' async></', $tag );
    }
    return $tag;
  }
}
$defer_async_scripts = new Pelske_Defer_Scripts();
$defer_async_scripts->initialize();

/**
 * Permalink shortcodes
 */

function pelske_permalinks($atts) {

	extract( shortcode_atts(array(
		'id' => 1,
		'text' => ""
	), $atts));

	if ( $text ) {

		$url = get_permalink($id);
		return "<a href='$url'>$text</a>";

	} else {

		return get_permalink($id);

	}
}
add_shortcode('permalink', 'pelske_permalinks');

/***************************************************************************
 * FORMS
 **************************************************************************/

/**
 * Generate validation/succes/error responses for forms
 *
 * @param    string    	$type    	'error'/'success'
 * @param    string    	$message  Text describing the response
 */
function pelske_form_generate_response( $type, $message ){

  global $response;

  if ($type == 'success') $response = "<div class='success'>{$message}</div>";
  else $response = "<div class='error'>{$message}</div>";

}

 /**
 * Validate invisible recaptcha's for contact form and guestbook
 *
 * @return 		bool 			true if recaptcha is valid, else false
 */
function recaptcha_is_valid(){

	if( empty( $_POST['g-recaptcha-response'] ) ) return false;

  $response = wp_remote_get( add_query_arg( array(
    'secret'   => '6Lcc-2AUAAAAANBXD4sVz9n6X1deBe_6uLHdoBg2',
    'response' => isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '',
    'remoteip' => isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']
  ), 'https://www.google.com/recaptcha/api/siteverify' ) );

  if( is_wp_error( $response ) || empty( $response['body'] ) || ! ( $json = json_decode( $response['body'] ) ) || ! $json->success ) {
    return false;
	} else {
		return true;
	}

}

/***************************************************************************
 * EVENTS
 **************************************************************************/

/**
 * Registers a new post type 'pelske_event'
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param 		string  				Post type key, must not exceed 20 characters
 * @param 		array|string 		See optional args description above.
 * @return 		object|WP_Error The registered post type object, or an error object
 */
function pelske_custom_post_type_event() {

	$labels = array(
		'name'               => __( 'Events', 'pelske' ),
		'singular_name'      => __( 'Event', 'pelske' ),
		'add_new'            => __( 'Add New Event', 'pelske', 'pelske' ),
		'add_new_item'       => __( 'Add New Event', 'pelske' ),
		'edit_item'          => __( 'Edit Event', 'pelske' ),
		'new_item'           => __( 'New Event', 'pelske' ),
		'view_item'          => __( 'View Event', 'pelske' ),
		'menu_name'          => __( 'Events', 'pelske' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'public'              => true,
		'menu_position'       => 2,
		'menu_icon'           => 'dashicons-calendar-alt',
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'publicly_queryable'  => false,
		'query_var'           => false,
		'rewrite'             => false,
		'supports'            => array(
			'title',
			'post-formats',
			'post-thumbnails',
		),
	);

	register_post_type( 'pelske_event', $args );
}
add_action( 'init', 'pelske_custom_post_type_event' );

/**
 * The core plugin class (and dependencies) that is used to define the meta boxes and their content.
 */
require_once PELSKE__PLUGIN_DIR . 'admin/class-pelske-event-meta-box.php';
require_once PELSKE__PLUGIN_DIR . 'admin/class-pelske-event.php';

// Initialize pelske_event metabox in the backend
function run_pelske_event() {

  $pelske_event = new Pelske_Event_Admin( 'pelske-event' );
  $pelske_event->initialize_hooks();

}
add_action( 'admin_init', 'run_pelske_event' );


/***************************************************************************
 * GUESTBOOK
 **************************************************************************/

/**
 * Registers a new post type 'pelske_gb_entry'
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param 		string  				Post type key, must not exceed 20 characters
 * @param 		array|string 		See optional args description above.
 * @return 		object|WP_Error The registered post type object, or an error object
 */
function pelske_custom_post_type_gb_entry() {

	$labels = array(
		'name'               => __( 'Guestbook Entries', 'pelske' ),
		'singular_name'      => __( 'Guestbook Entry', 'pelske' ),
		'add_new'            => __( 'Add New Guestbook Entry', 'pelske', 'pelske' ),
		'add_new_item'       => __( 'Add New Guestbook Entry', 'pelske' ),
		'edit_item'          => __( 'Edit Guestbook Entry', 'pelske' ),
		'new_item'           => __( 'New Guestbook Entry', 'pelske' ),
		'view_item'          => __( 'View Guestbook Entry', 'pelske' ),
		'menu_name'          => __( 'Guestbook Entries', 'pelske' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'public'              => true,
		'menu_position'       => 3,
		'menu_icon'           => 'dashicons-format-chat',
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'publicly_queryable'  => false,
		'query_var'           => false,
		'rewrite'             => false,
		'supports'            => array(
			'title',
			'editor',
			'comments',
		),
	);

	register_post_type( 'pelske_gb_entry', $args );
}
add_action( 'init', 'pelske_custom_post_type_gb_entry' );

?>