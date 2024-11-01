<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
wordpress-plugin
Plugin Name: Webcam-viewer-free-edition

Plugin URI: https://www.programmisitiweb.lacasettabio.it/shop

Description: A widget to show your webcam images.

link              programmisitiweb.lacasettabio.it
since             1.0.0
package           Webcam_Viewer
Version: 2.1

Author: Antonio Germani
Author URI: https://www.programmisitiweb.lacasettabio.it

License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       webcam-viewer-free-edition
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
# Protezione
defined('ABSPATH') or die('No script kiddies please!');

define( 'WEBCAM_VIEWER_VERSION', '1.0.0' );

// Register widget and style
function my_register_custom_widget() {
	register_widget( "Webcam_Viewer_Widget" );
	wp_enqueue_style ('style', plugins_url ('webcam-style.css', __FILE__),'', '1.0');	
}
add_action( "widgets_init", "my_register_custom_widget" );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'settings.php';

load_plugin_textdomain('Webcam-viewer', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Require widget file
require plugin_dir_path( __FILE__ ) . 'widget.php';

// Require shortcode file
//require plugin_dir_path( __FILE__ ) . 'shortcode.php';



if ( is_admin() )
	$webcam_viewer_settings = new WebcamViewerSettings();

?>