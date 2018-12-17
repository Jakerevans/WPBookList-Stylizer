<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: WPBookList Stylizer Extension
Plugin URI: https://www.jakerevans.com
Description: Allows for customization of WPBookList Styling
Version: 1.0.0
Author: Jake Evans - Forward Creation
Author URI: https://www.jakerevans.com
License: GPL2
*/ 

/*
CHANGELOG
= 1.0.0 =
	1. Initial release
*/

global $wpdb;
require_once('includes/stylizer-functions.php');
require_once('includes/stylizer-ajaxfunctions.php');

// Root plugin folder directory.
if ( ! defined('WPBOOKLIST_VERSION_NUM' ) ) {
	define( 'WPBOOKLIST_VERSION_NUM', '6.1.2' );
}

// This Extension's Version Number.
define( 'WPBOOKLIST_STYLIZER_VERSION_NUM', '6.1.2' );

// Root plugin folder URL of this extension
define('STYLIZER_ROOT_URL', plugins_url().'/wpbooklist-stylizer/');

// Grabbing db prefix
define('STYLIZER_PREFIX', $wpdb->prefix);

// Define the Uploads base directory
$uploads = wp_upload_dir();
$upload_path = $uploads['basedir'];
define('STYLIZER_UPLOADS_BASE_DIR', $upload_path.'/');

// Root plugin folder directory for this extension
define('STYLIZER_ROOT_DIR', plugin_dir_path(__FILE__));

// Root WordPress Plugin Directory.
define( 'STYLIZER_ROOT_WP_PLUGINS_DIR', str_replace( '/wpbooklist-stylizer', '', plugin_dir_path( __FILE__ ) ) );

// Root WPBL Dir.
if ( ! defined('ROOT_WPBL_DIR' ) ) {
	define( 'ROOT_WPBL_DIR', STYLIZER_ROOT_WP_PLUGINS_DIR . 'wpbooklist/' );
}

// Root WPBL Url.
if ( ! defined('ROOT_WPBL_URL' ) ) {
	define( 'ROOT_WPBL_URL', plugins_url() . '/wpbooklist/' );
}

// Root WPBL Classes Dir.
if ( ! defined('ROOT_WPBL_CLASSES_DIR' ) ) {
	define( 'ROOT_WPBL_CLASSES_DIR', ROOT_WPBL_DIR . 'includes/classes/' );
}

// Root WPBL Transients Dir.
if ( ! defined('ROOT_WPBL_TRANSIENTS_DIR' ) ) {
	define( 'ROOT_WPBL_TRANSIENTS_DIR', ROOT_WPBL_CLASSES_DIR . 'transients/' );
}

// Root WPBL Translations Dir.
if ( ! defined('ROOT_WPBL_TRANSLATIONS_DIR' ) ) {
	define( 'ROOT_WPBL_TRANSLATIONS_DIR', ROOT_WPBL_CLASSES_DIR . 'translations/' );
}

// Root WPBL Root Img Icons Dir.
if ( ! defined('ROOT_WPBL_IMG_ICONS_URL' ) ) {
	define( 'ROOT_WPBL_IMG_ICONS_URL', ROOT_WPBL_URL . 'assets/img/icons/' );
}

// Root WPBL Root Utilities Dir.
if ( ! defined('ROOT_WPBL_UTILITIES_DIR' ) ) {
	define( 'ROOT_WPBL_UTILITIES_DIR', ROOT_WPBL_CLASSES_DIR . 'utilities/' );
}




// Root Image Icons URL of this extension
define('STYLIZER_ROOT_IMG_ICONS_URL', STYLIZER_ROOT_URL.'assets/img/');

// Root Classes Directory for this extension
define('STYLIZER_CLASS_DIR', STYLIZER_ROOT_DIR.'includes/classes/');

// Define the Stylizer base directory
define('STYLIZER_UPLOAD_DIR', STYLIZER_UPLOADS_BASE_DIR.'wpbooklist/stylizer/');

// Define the Stylizer base directory
define('STYLIZER_TEMP_UPLOAD_DIR', STYLIZER_UPLOADS_BASE_DIR.'wpbooklist/stylizer/tempdir/');

// Root CSS URL for this extension
define('STYLIZER_ROOT_CSS_URL', STYLIZER_ROOT_URL.'assets/css/');

// Root CSS Directory for this extension
define('STYLIZER_ROOT_CSS_DIR', STYLIZER_ROOT_DIR.'assets/css/');

// Root JS URL for this plugin
define('STYLIZER_ROOT_JS_URL', STYLIZER_ROOT_URL.'assets/js/');

// Required page title
define('STYLIZER_REQUIRED_PAGE_TITLE', 'WPBookList Stylizer Required Page');

// Creates table upon activation
register_activation_hook( __FILE__, 'wpbooklist_stylizer_create_settings_table' );

// Adding the jscolor script
add_action('admin_enqueue_scripts', 'wpbooklist_colorpicker_script' );

// For registering table name
add_action('init','wpbooklist_stylizer_register_table_name');

// For creating iframe test page
add_action('admin_footer','wpbooklist_stylizer_create_iframe_page');

// For determining avaiable fonts on front-end
add_action('wp_footer','wpbooklist_stylizer_available_fonts');

// Adding the jscolor script
add_action('wp_enqueue_scripts', 'wpbooklist_fontdetect_script' );

// Adding the front-end ui css file for this extension
add_action('wp_enqueue_scripts', 'wpbooklist_jre_stylizer_frontend_ui_style');

// Adding the bookview ui css file for this extension
add_action('wp_enqueue_scripts', 'wpbooklist_jre_stylizer_bookview_ui_style');

// Adding the admin css file for this extension
add_action('admin_enqueue_scripts', 'wpbooklist_jre_stylizer_admin_style' );

// For loading the iframe
add_action('admin_footer','wpbooklist_stylizer_iframeload_javascript');

// For listening to changes in the style inputs and applying to preview
add_action( 'admin_footer', 'wpbooklist_stylizer_style_listeners_javascript' );

// For displaying a book in colorbox
add_action( 'admin_footer', 'wpbooklist_stylizer_colorbox_action_javascript' );
add_action( 'wp_footer', 'wpbooklist_stylizer_colorbox_action_javascript' );
add_action( 'wp_ajax_wpbooklist_stylizer_colorbox_action', 'wpbooklist_stylizer_colorbox_action_callback' );
add_action( 'wp_ajax_nopriv_wpbooklist_stylizer_colorbox_action', 'wpbooklist_stylizer_colorbox_action_callback' );

// For saving new styles
add_action( 'admin_footer', 'wpbooklist_stylizer_write_styles_action_javascript' );
add_action( 'wp_ajax_wpbooklist_stylizer_write_styles_action', 'wpbooklist_stylizer_write_styles_action_callback' );
add_action( 'wp_ajax_nopriv_wpbooklist_stylizer_write_styles_action', 'wpbooklist_stylizer_write_styles_action_callback' );

// For resetting all styles
add_action( 'admin_footer', 'wpbooklist_stylizer_reset_action_javascript' );
add_action( 'wp_ajax_wpbooklist_stylizer_reset_action', 'wpbooklist_stylizer_reset_action_callback' );
add_action( 'wp_ajax_nopriv_wpbooklist_stylizer_reset_action', 'wpbooklist_stylizer_reset_action_callback' );

// Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
register_activation_hook( __FILE__, 'wpbooklist_stylizer_core_plugin_required' );

/*
 * Function that utilizes the filter in the core WPBookList plugin, resulting in a new submenu. Possible options for the first argument in the 'Add_filter' function below are:
 *  - 'wpbooklist_add_submenu_books'
 *  - 'wpbooklist_add_submenu_display'
 *
 *
 *
 * The instance of "Stylizer" in the $extra_submenu array can be replaced with whatever you want - but the 'stylizer' instance MUST be your one-word descriptor.
*/
add_filter('wpbooklist_add_sub_menu', 'wpbooklist_stylizer_submenu');
function wpbooklist_stylizer_submenu($submenu_array) {
 	$extra_submenu = array(
		'Stylizer'
	);
 
	// combine the two arrays
	$submenu_array = array_merge($submenu_array,$extra_submenu);
	return $submenu_array;
}

?>