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
add_filter('use_block_editor_for_post', '__return_false', 10);


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

  if ($type == 'success') $response = "<p class='success'>{$message}</p>";
  else $response = "<p class='error'>{$message}</p>";

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
		'name'               => __( 'Evenementen', 'pelske' ),
		'singular_name'      => __( 'Evenement', 'pelske' ),
		'add_new'            => __( 'Voeg nieuw Evenement toe', 'pelske', 'pelske' ),
		'add_new_item'       => __( 'Voeg nieuw Evenement toe', 'pelske' ),
		'edit_item'          => __( 'Bewerk Evenement', 'pelske' ),
		'new_item'           => __( 'Nieuw Evenement', 'pelske' ),
		'view_item'          => __( 'Bekijk Evenement', 'pelske' ),
		'menu_name'          => __( 'Evenement', 'pelske' ),
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
require_once PELSKE__PLUGIN_DIR . 'admin/class-pelske-event.php';
require_once PELSKE__PLUGIN_DIR . 'admin/class-pelske-event-meta-box.php';

// Initialize pelske_event metabox in the backend
function run_pelske_event() {

	$pelske_event = new Pelske_Event_Admin( 'pelske_event' );
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
		'name'               => __( 'Gastenboek Berichten', 'pelske' ),
		'singular_name'      => __( 'Gastenboek Bericht', 'pelske' ),
		'add_new'            => __( 'Voeg nieuw Gastenboek Bericht toe', 'pelske', 'pelske' ),
		'add_new_item'       => __( 'Voeg nieuw Gastenboek Bericht toe', 'pelske' ),
		'edit_item'          => __( 'Bewerk Gastenboek Bericht', 'pelske' ),
		'new_item'           => __( 'Nieuw Gastenboek Bericht', 'pelske' ),
		'view_item'          => __( 'Bekijk Gastenboek Bericht', 'pelske' ),
		'menu_name'          => __( 'Gastenboek Berichten', 'pelske' ),
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


/***************************************************************************
 * GALLERY
 **************************************************************************/
/**
 * Registers a new taxonomy 'gallery_img_tax' for post type 'gallery_img'
 */
add_action( 'init', 'create_gallery_img_taxonomy' );
function create_gallery_img_taxonomy() {
    register_taxonomy(
        'gallery_img_tax',
        'gallery_img',
        array(
            'label' => 'Foto Categorieën',
            'public' => false,
            'rewrite' => false,
						'hierarchical' => false,
        )
    );
}
/**
 * Insert terms into the newly created taxonomy
 */
add_action( 'init', 'add_terms_to_gallery_img_taxonomy' );
function add_terms_to_gallery_img_taxonomy() {

	$terms = [ 'sjaal', 'hoed', 'tas', 'varia' ];

	foreach( $terms as $term ) {

		if( ! term_exists( $term ) ) {
			wp_insert_term( $term, 'gallery_img_tax' );
		}

	}

}

/**
 * Registers a new post type 'gallery_img'
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param 		string  				Post type key, must not exceed 20 characters
 * @param 		array|string 		See optional args description above.
 * @return 		object|WP_Error The registered post type object, or an error object
 */
add_action( 'init', 'pelske_custom_post_type_gallery_img' );
function pelske_custom_post_type_gallery_img() {

	$labels = array(
		'name'               => __( 'Foto\'s', 'pelske' ),
		'singular_name'      => __( 'Foto', 'pelske' ),
		'add_new'            => __( 'Voeg nieuwe Foto toe', 'pelske', 'pelske' ),
		'add_new_item'       => __( 'Voeg nieuwe Foto toe', 'pelske' ),
		'edit_item'          => __( 'Bewerk Foto', 'pelske' ),
		'new_item'           => __( 'Nieuwe Foto', 'pelske' ),
		'view_item'          => __( 'Bekijk Foto', 'pelske' ),
		'menu_name'          => __( 'Foto\'s', 'pelske' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'public'              => true,
		'menu_position'       => 2,
		'menu_icon'           => 'dashicons-format-gallery',
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'publicly_queryable'  => false,
		'query_var'           => false,
		'rewrite'             => false,
		'supports'            => array(
			'post-formats',
			'thumbnail',
		),
	);

	register_post_type( 'gallery_img', $args );

}

register_taxonomy_for_object_type( 'gallery_img_tax', 'gallery_img' );

/**
 * The core plugin class (and dependencies) that is used to define the meta boxes and their content.
 */
require_once PELSKE__PLUGIN_DIR . 'admin/class-pelske-gallery.php';

// Initialize pelske_gallery metabox in the backend
function run_pelske_gallery() {

	// if( get_current_screen()->post_type === 'gallery_img' ) {

		$pelske_gallery = new Pelske_Gallery_Admin( 'gallery_img' );
		$pelske_gallery->initialize_hooks();

	// }

}
add_action( 'admin_init', 'run_pelske_gallery' );

?>
