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
	     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-tabs.php' );
	     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-alert.php' );
	     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-counter.php' );
	     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-progress.php' );
	     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-image-carousel.php' );
	     if(class_exists("\ElementorPro\Plugin")){
	     	require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/pro/amp-gallery.php' );
	     	require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/pro/amp-slides.php' );
	     	require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/pro/carousel/amp-carousel-base.php' );
	     	require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/pro/carousel/amp-media-carousel.php' );
	     	require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/pro/carousel/amp-testimonial-carousel.php' );
	     }
	 }

	public function register_widgets($widgets_manager) {
		// Register Widgets
		if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {

			$this->include_widgets_files();

	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Accordion() );
	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Toggle() );
	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Tabs() );
	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\AMP_Alert() );
	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\AMP_Counter() );
	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\AMP_Progress() );
	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Image_Carousel() );
	        if(class_exists("\ElementorPro\Plugin")){
	        	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Gallery() );
	        	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\AMP_Slides() );
	        	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Carousel\AMP_Media_Carousel() );
	        	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Carousel\AMP_Testimonial_Carousel() );
	        }

		}
	}


	public function amp_enhancer_elementor_add_amp_script_wrapper($content){
		if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
		  $script_url = str_replace('http:','https:',AMP_ENHANCER_ElEMENTOR_URI).'elementor/amp-script/amp-enhancer-elementor.js?ver='.AMP_ENHANCER_VERSION;
		 $amp_script = ' <amp-script src="'.esc_url_raw($script_url).'" sandbox="allow-forms" >';
		 $close_script = '</amp-script>';
		 $content =  $amp_script.$content.$close_script;
	    }
      
       return $content;
	}

	public function __construct() {
		
		// Register widgets		
		//add_action( 'elementor/elements/elements_registered', [ $this, 'register_elements' ], 999999);
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ], 999999 );

		add_filter('elementor/frontend/the_content',[ $this, 'amp_enhancer_elementor_add_amp_script_wrapper' ],999,1);
	}

}

// Instantiate Plugin Class
Amp_Enhancer_Elementor_Widgets_Loading::instance();