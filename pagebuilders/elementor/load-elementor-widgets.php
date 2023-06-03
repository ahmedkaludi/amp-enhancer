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
	     require_once( AMP_ENHANCER_PLUGIN_DIR . 'pagebuilders/elementor/widgets/amp-video.php' );
	 }

	public function register_widgets() {
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
	        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Video() );
		}
	}

	public function register() {
		// Register Widgets
		if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {

			$this->include_widgets_files();

	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Amp_Accordion() );
	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Amp_Toggle() );
	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Amp_Tabs() );
	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\AMP_Alert() );
	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\AMP_Counter() );
	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\AMP_Progress() );
	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Amp_Image_Carousel() );
	        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Amp_Video() );
		}
	}


	public function amp_enhancer_elementor_add_amp_script_wrapper($content){
		$post_id = get_the_ID();

        if(function_exists('elementor_pro_load_plugin')){
	   		 $popup_enabled = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( "popup" );
	     }


		if(defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '3.2.0') >= 0 ) {
			
			if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() && class_exists('\Elementor\Plugin') && (\Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor() || (isset($popup_enabled) && !empty($popup_enabled)))   ) ) {
	   	
				$script_url = str_replace('http:','https:',AMP_ENHANCER_PAGEBUILDER_URI).'elementor/amp-script/amp-enhancer-elementor.js?ver='.AMP_ENHANCER_VERSION;
			    $amp_script = ' <amp-script src="'.esc_url_raw($script_url).'" sandbox="allow-forms" >';
			    $close_script = '</amp-script>';  
				$content =  $amp_script.$content.$close_script;
		  	}

		}else{

			if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() && class_exists('\Elementor\Plugin') && (\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id) || (isset($popup_enabled) && !empty($popup_enabled)))   ) ) {
	   	
				$script_url = str_replace('http:','https:',AMP_ENHANCER_PAGEBUILDER_URI).'elementor/amp-script/amp-enhancer-elementor.js?ver='.AMP_ENHANCER_VERSION;
			    $amp_script = ' <amp-script src="'.esc_url_raw($script_url).'" sandbox="allow-forms" >';
			    $close_script = '</amp-script>';  
				$content =  $amp_script.$content.$close_script;
		  	}
		}	   
      
       return $content;
	}

	public function amp_enhancer_elementor_postid_css(){

		$srcs = array();

        if(function_exists('elementor_pro_load_plugin')){

             $popup_enabled = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( "popup" );
             if(function_exists('wp_upload_dir') && isset($popup_enabled) && !empty($popup_enabled)){

                $uploadUrl = wp_upload_dir()['baseurl'];
                $uploads_dir = wp_upload_dir()['basedir'];

                  foreach ($popup_enabled as $key => $value) {
                     $file_dir = $uploads_dir."/elementor/css/post-".$key.".css";
                    if(file_exists($file_dir)){
                      $srcs[] = $uploadUrl."/elementor/css/post-".$key.".css";
                    }
                  }
              }

              if(empty($srcs)){
              	return false;
              }
		    foreach ($srcs as $key => $urlValue) {
		        $cssData = $this->amp_enhancer_elementor_remote_content($urlValue);
		        $cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
		        $cssData = str_replace('img', 'amp-img', $cssData);
		              return $cssData;
		      }
		   }

		}

		public function amp_enhancer_elementor_remote_content($src){
		    if($src){
		      $arg = array( "sslverify" => false, "timeout" => 60 ) ;
		      $response = wp_remote_get( $src, $arg );
		          if ( wp_remote_retrieve_response_code($response) == 200 && is_array( $response ) ) {
		            $header = wp_remote_retrieve_headers($response); // array of http header lines
		            $contentData =  wp_remote_retrieve_body($response); // use the content
		            return $contentData;
		          }else{
		        $contentData = file_get_contents( $src );
		        if(! $contentData ){
		          $data = str_replace(get_site_url(), '', $src);//content_url()
		          $data = getcwd().$data;
		          if(file_exists($data)){
		            $contentData = file_get_contents($data);
		          }
		        }
		        return $contentData;
		      }

		    }
		      return '';
		}

		public function amp_enhancer_elementor_postid_custom_css($content_buffer){

           $postid_css = $this->amp_enhancer_elementor_postid_css();
           if($postid_css !== false && preg_match('/<style amp-custom(.*?)>(.*?)<\/style>/s', $content_buffer)){
          	 $content_buffer = preg_replace('/<style amp-custom(.*?)>(.*?)<\/style>/s', '<style amp-custom$1>'.$postid_css.'$2</style>', $content_buffer);     
           }
                 
           return $content_buffer;
		}

	public function __construct() {
		
		// Register widgets						

		if(defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '3.5.0') >= 0 ) {
			add_action( 'elementor/widgets/register', [ $this, 'register' ], 999999 );
		}
		else{
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ], 999999 );
		}
		
		add_filter('elementor/frontend/the_content',[ $this, 'amp_enhancer_elementor_add_amp_script_wrapper' ],999,1);

		add_filter('amp_enhancer_content_html_last_filter',[ $this, 'amp_enhancer_elementor_postid_custom_css' ],999,1);
	}

}

// Instantiate Plugin Class
Amp_Enhancer_Elementor_Widgets_Loading::instance();