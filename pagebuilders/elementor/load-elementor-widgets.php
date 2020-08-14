<?php
namespace Amp_Enhancer_Elementor_Support;

class Amp_Enhancer_Elementor_Widgets_Loading {
	
	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function include_widgets_files() {

     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-accordion.php' );
     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-toggle.php' );
	 }

	 public function register_widgets($widgets_manager) {
		// Register Widgets
		if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {

			$this->include_widgets_files();

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Accordion() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Toggle() );

		}
	}

	 public function __construct() {
		
		// Register widgets		
		//add_action( 'elementor/elements/elements_registered', [ $this, 'register_elements' ], 999999);
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ], 999999 );
	}

}

// Instantiate Plugin Class
Amp_Enhancer_Elementor_Widgets_Loading::instance();