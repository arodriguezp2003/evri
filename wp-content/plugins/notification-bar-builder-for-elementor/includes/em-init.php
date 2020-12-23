<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


use \Elementor\Plugin as Plugin;


final class NBBFEML_Elementor_Init {

	const VERSION = "1.0.0";
	const MINIMUM_ELEMENTOR_VERSION = "2.0.0";
	const MINIMUM_PHP_VERSION = "5.6";

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
		}

		// Once we get here, We have passed all validation checks so we can safely include our files
		include_once( NBBFEML_PLG_DIR. '/includes/custom-posts.php');

		if( !function_exists('nbbfem_add_elementor_page_settings_controls') ){
			include_once( NBBFEML_PLG_DIR. '/includes/document-controls.php');
		}
		
		include_once( NBBFEML_PLG_DIR. '/includes/functions.php');

		// Register nacessary widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		// Elementor dashboard panel style
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_scripts' ] );

		// Enqueue frontend scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

		add_action('nbbfem_after_wp_enqueue_scripts', 'nbbfeml_prepare_markup_to_render' );

		// Add wrapper markup in elementor editor mode
		add_action( 'elementor/page_templates/canvas/before_content', 'nbbfeml_canvas_wrapper_open' );
		add_action( 'elementor/page_templates/canvas/after_content', 'nbbfeml_canvas_wrapper_close' );
	}

	// Editor scripts
	public function editor_scripts() {
		wp_enqueue_style( "nbbfem-editor", NBBFEML_PLG_URI . '/assets/css/editor.css' );
	}

	// Frontend scripts
	public function frontend_scripts() {
		wp_enqueue_style( 'nbbfem-main', NBBFEML_PLG_URI.'/assets/css/main.css');
		wp_enqueue_script( 'js-cookie', NBBFEML_PLG_URI.'/assets/js/js.cookie.min.js', array( 'jquery' ), '3.0.0', true );
		wp_enqueue_script( 'nbbfem-main', NBBFEML_PLG_URI.'/assets/js/main.js', array( 'jquery' ), '1.0.0', true );

		do_action( 'nbbfem_after_wp_enqueue_scripts' );
	}

	// Initialize widgets
	public function init_widgets() {
		if( !class_exists('NBBFEM_Button_With_Info') ){
			require_once( NBBFEML_PLG_DIR . '/includes/widgets/button-with-info.php' );

			// Register widget
			Plugin::instance()->widgets_manager->register_widget_type( new NBBFEML_Button_With_Info() );
		}
	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'nbbfem' ),
			'<strong>' . esc_html__( 'Notification Bar Builder', 'nbbfem' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'nbbfem' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'nbbfem' ),
			'<strong>' . esc_html__( 'Notification Bar Builder', 'nbbfem' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'nbbfem' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'nbbfem' ),
			'<strong>' . esc_html__( 'Notification Bar Builder', 'nbbfem' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'nbbfem' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

}

NBBFEML_Elementor_Init::instance();