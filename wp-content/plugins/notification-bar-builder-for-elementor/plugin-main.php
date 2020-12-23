<?php
/**
Plugin Name: Notification Bar Builder for Elementor
Plugin URI: http://demo.azplugins.com/notification-bar
Description: It allows elementor to build a notification bar using the page builder & use it any pages,posts,products etc.
Version: 1.0.2
Author: AZ_Plugins
Author URI: https://azplugins.com
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: nbbfem
Domain Path: /languages/
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define path
 */
define( 'NBBFEML_PLG_URI', plugins_url('', __FILE__) );
define( 'NBBFEML_PLG_DIR', dirname( __FILE__ ) );

/**
 * Include files
 */
include_once( NBBFEML_PLG_DIR. '/includes/em-init.php');