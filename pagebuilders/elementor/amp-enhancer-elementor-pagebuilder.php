<?php 
final class Amp_Enhancer_Elementor_Support {

	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	const MINIMUM_PHP_VERSION = '5.0';
	public $postID;
	public $header_html;
	public $sanitizer_script;
	public $footer_html;

	public function __construct() {
		// Init Plugin
		 $this->initialize();
	}

	function initialize(){
         //include files
		 require_once( AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/elementor/load-elementor-widgets.php' );
		   if(function_exists('elementor_pro_load_plugin') && (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint())){
                require_once( AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/elementor/themebuilder/theme-document.php' );
                require_once( AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/elementor/themebuilder/theme-page-document.php' );
                require_once( AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/elementor/themebuilder/theme-section-document.php' );
            }
	}

}

 new Amp_Enhancer_Elementor_Support();