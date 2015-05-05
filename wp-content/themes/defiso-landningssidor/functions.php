<?php
/**
 * Defiso Landningssidor functions and definitions
 *
 * @package Defiso Landningssidor
 */

/**
 * Add Redux Framework & extras
 */
require get_template_directory() . '/admin/admin-init.php';
global $landingpage;

//remove inline width and height added to images
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );
// Removes attached image sizes as well
add_filter( 'the_content', 'remove_thumbnail_dimensions', 10 );
function remove_thumbnail_dimensions( $html ) {
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
	return $html;
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'defiso_landningssidor_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function defiso_landningssidor_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Defiso Landningssidor, use a find and replace
	 * to change 'defiso-landningssidor' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'defiso-landningssidor', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	add_image_size( 'frontslider', 1088, 400, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'defiso-landningssidor' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );
}
endif; // defiso_landningssidor_setup
add_action( 'after_setup_theme', 'defiso_landningssidor_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function defiso_landningssidor_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'defiso-landningssidor' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	) );
	register_sidebar( array(
		'name'          => __( 'Sidfot', 'defiso-landningssidor' ),
		'id'            => 'footer-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<span class="footer-widget-title">',
		'after_title'   => '</span>',
	) );
}
add_action( 'widgets_init', 'defiso_landningssidor_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function defiso_landningssidor_scripts() {
	wp_enqueue_style( 'defiso-landningssidor-style', get_stylesheet_uri() );

	wp_enqueue_style( 'owl-style', get_template_directory_uri() . '/css/owl.carousel.min.css', array(), '2.0.0-beta.2.4' );

	wp_enqueue_style( 'owl-style-theme', get_template_directory_uri() . '/css/owl.theme.default.min.css', array(), '2.0.0-beta.2.4' );

	wp_enqueue_script( 'jquery-2', get_template_directory_uri() . '/js/jquery-2.1.3.min.js', array(), '2.1.3', true );

	wp_enqueue_script( 'defiso-landningssidor-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'defiso-landningssidor-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), '2.0.0-beta.2.4', true);
}
add_action( 'wp_enqueue_scripts', 'defiso_landningssidor_scripts' );

function remove_menus(){
  
  remove_menu_page( 'index.php' );                  //Dashboard
  remove_menu_page( 'edit.php' );                   //Posts
  remove_menu_page( 'edit-comments.php' );          //Comments
  remove_menu_page( 'users.php' );                  //Users
  remove_menu_page( 'tools.php' );                  //Tools
  remove_menu_page( 'plugins.php' );                //Plugins
  
}
add_action( 'admin_menu', 'remove_menus' );


add_action( 'admin_menu', 'adjust_the_wp_menu', 999 );

function adjust_the_wp_menu() {
  $page = remove_submenu_page( 'options-general.php', 'options-writing.php' );
  $page = remove_submenu_page( 'options-general.php', 'options-media.php' );
  $page = remove_submenu_page( 'themes.php', 'customize.php' );
  $page = remove_submenu_page( 'themes.php', 'nav-menus.php' );

}

/**
 * Remove blog-stuff this landingpage does not use...
 */

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Loads Mandrill SMTP-config
 */

require get_template_directory() . '/inc/SMTP-config.php';

/**
 * Load form widgets.
 */
require get_template_directory() . '/inc/widget-contact-form.php';

/**
 * Load Google Maps widgets.
 */
require get_template_directory() . '/inc/widget-google-maps.php';

/**
 * Load contact form with shortcodes.
 */
require get_template_directory() . '/inc/contact-form.php';

// Custom Page Titles
require get_template_directory() . '/inc/custom-title.php';

// Custom descriptions
require get_template_directory() . '/inc/custom-description.php';

// Custom css in header
require get_template_directory() . '/inc/custom-css.php';

