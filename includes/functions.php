<?php 

// Amp form Santizer Modification
function amp_enhancer_form_sanitizer($data){
	require_once(AMP_ENHANCER_PLUGIN_DIR.'class-amp-enhancer-form-sanitizer.php');
		 unset($data['AMP_Form_Sanitizer']);
		 $data['AMP_Enhancer_Form_Sanitizer'] = array();

	return $data;
}

// Add Custom CSS
add_action( 'wp_head', 'amp_enhancer_add_custom_css');

function amp_enhancer_add_custom_css(){
  $settings = get_option( 'ampenhancer_settings');
   if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
    	wp_enqueue_style( 'amp_enhancer_css', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/includes/style.css', false, AMP_ENHANCER_VERSION );
     // Popup feature CSS
      if(isset($settings['popup']) && ($settings['popup'] == 'on' || $settings['popup'] == 1)){
           wp_enqueue_style( 'amp_enhancer_popup_css', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/includes/features/popup/popup-styles.css', false, AMP_ENHANCER_VERSION );
       }
       // Custom CSS feature style
       if(isset($settings['custom_css']) && ($settings['custom_css'] == 'on' || $settings['custom_css'] == 1)){
           wp_enqueue_style( 'amp_enhancer_custom_css', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/includes/features/custom-css/custom-styles.css', false, AMP_ENHANCER_VERSION );

           $custom_css = amp_enhancer_custom_css_output();
           wp_add_inline_style( 'amp_enhancer_custom_css', $custom_css );
       }
     // KK Star rating CSS
       if(function_exists('kksr_freemius')){
          $kksr_enable = get_option('kksr_enable'); 
          if($kksr_enable == 1){
             wp_enqueue_style( 'amp_enhancer_kkstar_css', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/templates/kk-star/kkstar-styles.css', false, AMP_ENHANCER_VERSION );
          }       
       }
     // Astra Addon
       if((defined('ASTRA_EXT_VER') || defined('ASTRA_THEME_VERSION')) && function_exists('astra_get_option')){
        // Sticky Header
         $sticky_header = defined('ASTRA_EXT_STICKY_HEADER_URI');
         $primary_header = astra_get_option('header-main-stick');

         wp_enqueue_style( 'amp_enhancer_astra_addon_css', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/templates/astra-addon/amp-enhancer-astra-addon-styles.css', false, AMP_ENHANCER_VERSION );

           if($sticky_header == true && $primary_header == true){
             $sticky_css = amp_enhancer_astra_sticky_header_css();
             wp_add_inline_style( 'amp_enhancer_astra_addon_css', $sticky_css );

           } 

         }
      // Icegram CSS
      if(defined('IG_PLUGIN_URL') && class_exists('Icegram')){

        wp_enqueue_style( 'amp_enhancer_icegram_css', untrailingslashit(IG_PLUGIN_URL) . '/message-types/popup/themes/popup.min.css', false, AMP_ENHANCER_VERSION );
      }
      // Smart Slider3
      if(defined('SMARTSLIDER3_LIBRARY_PATH')){
        wp_enqueue_style( 'amp_enhancer_smartslider3_css', trailingslashit(plugins_url()).'smart-slider-3/Public/SmartSlider3/Application/Frontend/Assets/dist/smartslider.min.css', false, AMP_ENHANCER_VERSION );
      }

    }// amp endpoint checking ends here...
}


add_action('wp','amp_enhancer_third_party_compatibililty');

function amp_enhancer_third_party_compatibililty(){
  $settings = get_option( 'ampenhancer_settings');

	if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {

		//Woocommerce
		if(function_exists('WC')){
	       remove_action( 'wp_head', 'wc_gallery_noscript' );
	  	}
	    //CF7
	    if(class_exists('WPCF7_ContactForm')){
		  add_filter('wpcf7_form_novalidate','amp_enhancer_cf7_validate',10,1);
		  add_filter('wpcf7_form_class_attr','amp_enhancer_add_cf7_custom_class',10,1);
		  add_filter('wpcf7_form_elements','amp_enhancer_cf7_form_elements_modification',10,1);
	    }
	    // Cookie Notice
        if(class_exists('Cookie_Notice')){
	      add_filter('cn_cookie_notice_output','amp_enhancer_cookie_notice_html_markup',10,2);
	    }

      // GDPR Cookie Consent
      if(class_exists('Cookie_Law_Info')){
        add_filter('cli_show_cookie_bar_only_on_selected_pages','amp_enhancer_gdpr_cookie_consent_remove_markup',10,2);
        add_action( 'wp_footer','amp_enhancer_cookielawinfo_html_markup',10);
        require_once(AMP_ENHANCER_TEMPLATE_DIR.'cookie-law-info/cookie-law-css.php');
        require_once(AMP_ENHANCER_TEMPLATE_DIR.'cookie-law-info/cookie-law-shortcode.php');
      }
      // GDPR Cookie Compliance
      if(function_exists('gdpr_cookie_compliance_load_libs')){
       add_filter('gdpr_infobar_base_module','amp_enhancer_gdpr_infobar_base_module',10,1);
       add_filter('gdpr_infobar_buttons_module','amp_enhancer_gdpr_infobar_buttons_module',10,1);
      }
      // Easy Table of Content
      if(class_exists('ezTOC_Option')){
       add_filter('the_content','amp_enhancer_easy_toc_content',99999,1);
      }

       // Table of Content Plus
       if(class_exists('toc')){
        add_filter('the_content','amp_enhancer_table_of_content_plus',99999,1);
       }

       // Lucky Table of Content
       if(class_exists('lwptocAutoloader')){
        add_filter('the_content','amp_enhancer_luckywp_toc',99999,1);
       }

      if(isset($settings['popup']) && ($settings['popup'] == 'on' || $settings['popup'] == 1)){
          require_once(AMP_ENHANCER_PLUGIN_DIR.'includes/features/popup/popup-frontend.php');
      }
     if(function_exists('wpforms')){
         remove_shortcode( 'wpforms' );
         add_shortcode( 'wpforms', 'amp_enhancer_wpforms_shortcode');
     }

     if(function_exists('Ninja_Forms')){
      remove_shortcode('ninja_form');
      add_shortcode( 'ninja_forms', 'amp_enhancer_ninja_forms_shortcode' );
      add_shortcode( 'ninja_form', 'amp_enhancer_ninja_forms_shortcode' );
     }  
     if(function_exists('kksr_freemius')){
       remove_shortcode(Bhittani\StarRating\config('shortcode'));  
       add_shortcode(Bhittani\StarRating\config('shortcode'),'amp_enhancer_kkstar_rating');  
     }
     
      // Content Views
      if(class_exists('PT_CV_Html')){

        $CV_PREFIX = defined('PT_CV_PREFIX_') ? PT_CV_PREFIX_ : 'pt_cv_';
        $cv_template = $CV_PREFIX. 'view_type_file';
        $collapsible = $CV_PREFIX. 'item_final_html';
        $view_all_output = $CV_PREFIX. 'view_all_output';
        $collapsible_wrapper = $CV_PREFIX. 'collapsible_filters';
        add_filter($cv_template,'amp_enhancer_cv_template_override',10,1);
        add_filter($collapsible,'amp_enhancer_cv_collapsible_filters',10,2);
        add_filter($collapsible_wrapper,'amp_enhancer_cv_collapsible_wrapper',10,2);
        add_filter($view_all_output,'amp_enhancer_cv_view_all_output',10,3);
      }  

       //Astra Addon
       if((defined('ASTRA_EXT_VER') || defined('ASTRA_THEME_VERSION')) && function_exists('astra_get_option')){
         require_once(AMP_ENHANCER_TEMPLATE_DIR.'astra-addon/amp-enhancer-astra-addon-functions.php');
         // Scroll to top
         $scroll_top = defined('ASTRA_EXT_SCROLL_TO_TOP_URL');

           if($scroll_top == true || $scroll_top == 1){
            add_action('wp_body_open','amp_enhancer_astra_back_to_top');
            add_action('wp_footer','amp_enhancer_astra_back_to_top_link');
           }
            $sticky_header = defined('ASTRA_EXT_STICKY_HEADER_URI');
            $primary_header = astra_get_option('header-main-stick');
            $header_method = astra_get_option( 'is-header-footer-builder', false );
           if($sticky_header == true && $primary_header == true && $header_method == false){

            remove_action( 'astra_masthead', 'astra_masthead_primary_template');
               add_action( 'astra_masthead', 'amp_enhancer_astra_masthead_primary_template_markup' );
           }

          if(class_exists('Astra_Builder_Header')){
               $astra_header = Astra_Builder_Header::get_instance();
     
               remove_action( 'astra_header_mobile_trigger', array( $astra_header, 'header_mobile_trigger' ) );
               remove_action( 'astra_mobile_header', array( $astra_header, 'mobile_header' ) );
               remove_action( 'wp_footer', array( $astra_header, 'mobile_popup' ) );
               add_action( 'astra_header_mobile_trigger', 'amp_enhancer_astra_mobile_trigger_html' );
               add_action( 'astra_mobile_header', 'amp_enhancer_astra_mobile_header'  );
               add_action( 'wp_footer', 'amp_enhancer_astra_mobile_popup_html' );
          //   add_filter( 'astra_attr_ast-main-header-bar-alignment', 'amp_enhancer_nav_menu_wrapper');  
          }



       }
    if(class_exists('JoinChat')){
     add_filter('joinchat_html_output','amp_enhancer_joinchat_html_output',10,2); 
    }
    if(class_exists('QLWAPP_Frontend')){
      add_filter('qlwapp_box_template','amp_enhancer_qlwapp_box_template',10,1);
    }
   
   if(function_exists('foogallery_fs')){ 
    add_filter('foogallery_attachment_html_image','amp_enhancer_foogallery_attachment_html_image',10,3);
    }
    // Icegram HTML Generation
     if(defined('IG_PLUGIN_URL') && class_exists('Icegram')){
      add_action('wp_footer','amp_enhancer_icegram_popup_output');
    }
    //Helpie FAQ
     if(class_exists('Helpie_FAQ_Plugin')){
      remove_shortcode('helpie_faq');
      add_shortcode('helpie_faq', 'amp_enhancer_helpie_faq');
    }
    // Convertkit
    if(class_exists('WP_ConvertKit')){
      remove_shortcode( 'convertkit');
      add_shortcode( 'convertkit', 'amp_enhancer_convertkit_shortcode',10 );
    }
     // Smart Slider3
    if(defined('SMARTSLIDER3_LIBRARY_PATH')){
       remove_shortcode('smartslider3');
       new templates\smartslider3\Enhancer_Slider_Shortcode();
     }
   
    if(defined('HEATEOR_FFC_VERSION')){
     add_filter( 'the_content','amp_enhancer_render_facebook_comments',10 );
     add_filter( 'the_excerpt', 'amp_enhancer_render_facebook_comments', 10 );
    }

     if ( class_exists( 'ET_Builder_Module' ) ) {
      require_once (AMP_ENHANCER_PAGEBUILDER_DIR.'divi/amp-enhancer-divi-pagebuilder.php');
        if(class_exists('pagebuilders\divi\AMP_Enhancer_Divi_Pagebuidler')){
         new pagebuilders\divi\AMP_Enhancer_Divi_Pagebuidler();
        }
     }


     //CF7
     if(class_exists('WPCF7_ContactForm')){
       remove_shortcode( 'contact-form-7', 'wpcf7_contact_form_tag_func' );
       remove_shortcode( 'contact-form', 'wpcf7_contact_form_tag_func' );
       add_shortcode( 'contact-form-7', 'amp_enhancer_wpcf7_contact_form_tag_func' );
       add_shortcode( 'contact-form', 'amp_enhancer_wpcf7_contact_form_tag_func' );
    }

    // Ultimate Addons For Gutenberg
    if(class_exists('UAGB_Loader')){
      add_filter('the_content','amp_enhancer_ul_addon_gutenberg_functionalities',999);
    }

	}
}

// Added Support Link
add_filter( 'plugin_action_links_' .AMP_ENHANCER_BASENAME, 'amp_enhancer_support_link' );

function amp_enhancer_support_link( $links ) { 
  if(function_exists('amp_activate')){
	     $support_link = '<a href="'.esc_url_raw( "admin.php?page=amp-enhacer-settings" ).'">'.esc_html__('Settings', 'amp-enhancer').'</a> | <a href="'.esc_url_raw( "http://ampenhancer.com/contact-us/" ).'">'.esc_html__('Support', 'amp-enhancer').'</a>';
   }else{
     $support_link = '<a href="'.esc_url_raw( "plugin-install.php?tab=plugin-information&plugin=amp" ).'">'.esc_html__('Please Activate Parent Plugin', 'amp-enhancer').'</a>'; 
   }
	array_unshift( $links, $support_link );
	return $links; 
}

// Added Opening Notice
 add_action( 'admin_notices', 'amp_enhancer_admin_notice' );

function amp_enhancer_admin_notice() {
    global $pagenow;
    $user_id = get_current_user_id();
    if ( (isset($pagenow) && $pagenow == 'plugins.php') && !get_user_meta( $user_id, 'amp_enhancer_dismiss_notice' ) ){ 
        echo '<div class="updated notice">
	                <p style = "font-size:14px;">' . esc_html__( 'Thank You for activating AMP Enhancer, it has Built-In functionalities no setup required.', 'amp-enhancer' ) . '
	                     <a  href="?amp-enhancer-dismissed" class="button">'. esc_html__('Dismiss','amp-enhancer').'</a>
	                </p>
                </div>';
        }
}

add_action( 'admin_init', 'amp_enhancer_dismiss_notice' );

function amp_enhancer_dismiss_notice() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['amp-enhancer-dismissed'] ) ){
        add_user_meta( $user_id, 'amp_enhancer_dismiss_notice', 'true', true );
    }
}

// color sanitizer
function amp_enhancer_sanitize_color( $color ) {
    if ( empty( $color ) || is_array( $color ) )
        return 'rgba(0,0,0,0)';
    // If string does not start with 'rgba', then treat as hex
    // sanitize the hex color and finally convert hex to rgba
    if ( false === strpos( $color, 'rgba' ) ) {
        return sanitize_hex_color( $color );
    }
    // By now we know the string is formatted as an rgba color so we need to further sanitize it.
    $color = str_replace( ' ', '', $color );
    sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
    return 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
}

// Settings Page

require_once(AMP_ENHANCER_PLUGIN_DIR.'admin/settings.php');
add_action( 'admin_menu', 'amp_enhancer_settings_option', 50 );
function amp_enhancer_settings_option() {
  $settings = get_option( 'ampenhancer_settings');
    if(class_exists('AMP_Options_Manager')){

       $amp_options = New AMP_Options_Manager();
       add_submenu_page( $amp_options::OPTION_NAME,
                         esc_html__( 'AMP Enhancer', 'amp-enhancer' ),
                         esc_html__( 'AMP Enhancer', 'amp-enhancer' ),
                         'manage_options',
                         'amp-enhacer-settings',
                         'amp_enhancer_settings_page' 
                       );
       if(isset($settings['popup']) && ($settings['popup'] == 'on' || $settings['popup'] == 1)){
           add_submenu_page( $amp_options::OPTION_NAME,
                           esc_html__( 'Popups', 'amp-enhancer' ),
                           esc_html__( 'Popups', 'amp-enhancer' ),
                           'manage_options',
                           admin_url( 'edit.php?post_type=ampenhancerpopup' )
                         );
          }
        if(isset($settings['custom_css']) && ($settings['custom_css'] == 'on' || $settings['custom_css'] == 1)){
            add_submenu_page( $amp_options::OPTION_NAME,
                         esc_html__( 'Custom CSS', 'amp-enhancer' ),
                         esc_html__( 'Custom CSS', 'amp-enhancer' ),
                         'manage_options',
                         'amp-enhancer-custom-css',
                         'amp_enhancer_custom_css' 
                        );
          }
       }
    }


add_action( 'admin_enqueue_scripts', 'amp_enhancer_settings_page_css' );
function amp_enhancer_settings_page_css( $hook ) {
 global $current_screen;
 $pagenow = false; 
  if(isset($current_screen->id)){ 
       if($current_screen->id == 'amp_page_amp-enhacer-settings'){
        $pagenow = true;
       }
       if($current_screen->id == 'amp_page_amp-enhancer-custom-css'){
        $pagenow = true;
       }

    }else{
        $pagenow = true;
    }
    if( is_admin() && $pagenow ==true ) {

        wp_register_style( 'amp-enhancer-settings-style', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/admin/css/amp-enhancer-settings.css',false,AMP_ENHANCER_VERSION );
        wp_enqueue_style( 'amp-enhancer-settings-style' );

    }
} 


// Easy table of content Code

function amp_enhancer_easy_toc_content($content){
  if(class_exists('ezTOC_Option')){
    if('1' == ezTOC_Option::get('visibility_hide_by_default')){
        $hidden = 'hidden';
        $hide = true;
     }else {
       $hidden = '';
       $hide = false;
    }
  }
   $ampStateJson = json_encode( array( 'easy_table' => $hide));
   $amp_state = '<amp-state id="ampeztable"><script type="application/json">'.$ampStateJson.'</script></amp-state>';

   if(preg_match('/<p class="ez-toc-title">(.*?)<\/p>(.*?)<a(.*?)class="(.*?)ez-toc-toggle(.*?)>(.*?)<\/a>/s', $content)){

     $content = preg_replace('/<p class="ez-toc-title">(.*?)<\/p>(.*?)<a(.*?)class="(.*?)ez-toc-toggle(.*?)>(.*?)<\/a>/s', ''.$amp_state.'<p class="ez-toc-title">$1</p>$2<a$3class="$4ez-toc-toggle$5 
      on="tap:AMP.setState({
                        ampeztable:{
                                easy_table: !(ampeztable.easy_table)}})" 
                                 role="button" tabindex="0">$6</a>', $content);
    }
    if(preg_match('/<nav><ul class="ez-toc-list (.*?)">/', $content)){

        $content = preg_replace('/<nav><ul class="ez-toc-list (.*?)">/', '<nav [hidden]="ampeztable.easy_table" '.esc_attr($hidden).'><ul class="ez-toc-list $1">', $content);
    }

 return $content;
}
// Easy table of content Code Ends here

// Table of Content Plus Code
function amp_enhancer_table_of_content_plus($content){

   global $tic;
   if(method_exists($tic, 'get_options')){
    $options = $tic->get_options();
   }
   $hidden =  $hide = '';
   $show = 'hidden';

   if(isset($options['visibility']) && $options['visibility'] == true){

       if(isset($options['visibility_hide_by_default']) && $options['visibility_hide_by_default'] == true){
          $hidden = $hide = 'hidden';
          $show = '';
       }

     $show_text = (isset($options['visibility_show']) && !empty($options['visibility_show'])) ? $options['visibility_show'] : 'show';
     $hide_text = (isset($options['visibility_hide']) && !empty($options['visibility_hide'])) ? $options['visibility_hide'] : 'hide';

     if(preg_match('/<p\s+class="toc_title">(.*?)<\/p>(.*?)<ul\s+class="toc_list">/', $content)){

         $content = preg_replace('/<p\s+class="toc_title">(.*?)<\/p>(.*?)<ul\s+class="toc_list">/', '<p class="toc_title">$1 <span class="toc_toggle">[<a 
           '.esc_attr($show).' [hidden]="showText"  role="button" tabindex="0"  on="tap:AMP.setState({hideToggle: false,showText:true})"> '.esc_html($show_text).'</a> <a 
            '.esc_attr($hide).'  [hidden]="hideToggle" role="button" tabindex="0"  on="tap:AMP.setState({hideToggle: true,showText:false})"> '.esc_html($hide_text).'</a>]</span></p>$2<ul class="toc_list" [hidden]="hideToggle" '.esc_attr($hidden).'>', $content);
     }
  }

 return $content;
}
// Table of Content Plus Code Ends Here...

// LuckyWp Table of Content

function amp_enhancer_luckywp_toc($content){
   
   $lwptoc_settings = get_option('lwptoc_general');
    
    if(isset($lwptoc_settings['toggle']) && $lwptoc_settings['toggle'] == 1){

       $content = preg_replace('/<a href="(.*?)"\s+class="lwptoc_toggle_label"\s+data-label="(.*?)">(.*?)<\/a>/', '<a  role="button" tabindex="0" class="lwptoc_toggle_label" data-label="'.esc_html('$2').'" on="tap:AMP.setState({ampen_ltoc:{lwptoc: !ampen_ltoc.lwptoc}})" [text]="ampen_ltoc.lwptoc? \''.esc_html('$2').'\':\''.esc_html('$3').'\'" >$3</a>', $content);

            if(isset($lwptoc_settings['hideItems']) && $lwptoc_settings['hideItems'] == 1){

               $content = preg_replace('/<div class="lwptoc_items(.*?)">/', '<div class="lwptoc_items"  hidden  [hidden]="ampen_ltoc.lwptoc? false : true ">', $content);

              }else{

                $content = preg_replace('/<div class="lwptoc_items(.*?)">/', '<div class="lwptoc_items"  [hidden]="ampen_ltoc.lwptoc? true : false ">', $content);
              }

       }
    
  return $content;
}

add_action('plugins_loaded','amp_enhancer_ajax_request_callbacks');

function amp_enhancer_ajax_request_callbacks(){
//set cookies Submission
   $settings = get_option( 'ampenhancer_settings');
  if(isset($settings['popup']) && ($settings['popup'] == 'on' || $settings['popup'] == 1)){
      add_action('wp_ajax_amp_enhancer_popup_dismiss_consent','amp_enhancer_popup_dismiss_consent');
      add_action('wp_ajax_nopriv_amp_enhancer_popup_dismiss_consent','amp_enhancer_popup_dismiss_consent');
   }
}

function amp_enhancer_popup_dismiss_consent(){
  $postId = $_POST['dismiss_id'];
  $timeInHour = get_post_meta($postId, 'en_cookie_time', true);
  $expire_time = time() + intval($timeInHour)*3600;
    setcookie('ampenhancer_popup','true', esc_attr( $expire_time) , "/");
    $current_url = $site_url = $site_host = $amp_site = '';
    $current_url  = wp_get_referer();
  $site_url     = parse_url( get_site_url() );
  $site_host    = $site_url['host'];
  $amp_site     = $site_url['scheme'] . '://' . $site_url['host'];
  header("AMP-Access-Control-Allow-Source-Origin: ".esc_url($amp_site));
  die();
}

function amp_enhancer_custom_css_output(){

  $custom_css = get_option('ampenhancer_custom_css');
  $raw_css = (isset($custom_css['css']) && !empty($custom_css['css'])) ? $custom_css['css'] : '' ;
  $css = '';
  $css     = wp_kses( $raw_css, array( '\'', '\"' ) );
  $css     = str_replace( '&gt;', '>', $css );
  return $css;
}

// Join.chat plugin
function amp_enhancer_joinchat_html_output($html_output, $settings){
  $href = 'https://api.whatsapp.com/send?phone='.$settings['telephone'].'&text='.$settings['message_send'].'' ;
  $html_output = '<a class="en_join_cht" href="'.esc_url_raw($href).'"  target="_blank" >'.$html_output.'</a>';
  return $html_output;
}

// wp social chat
function amp_enhancer_qlwapp_box_template($file){

  if(defined('QLWAPP_PLUGIN_DIR') &&  $file == QLWAPP_PLUGIN_DIR . 'template/box.php'){
    $file = AMP_ENHANCER_TEMPLATE_DIR.'wp-social-chat/box.php';
  }

 return $file;
}



function amp_enhancer_foogallery_attachment_html_image($html, $args, $foogallery_attachment){
  $src = isset($foogallery_attachment->url) ? $foogallery_attachment->url : false;
  $width = isset($args['width']) ? $args['width'] : 150;
  $height = isset($args['height']) ? $args['height'] : 150;
  if($src != false){
  $html = '<img class="fg-image" width="'.esc_attr($width).'" height="'.esc_attr($height).'" src="'.esc_url($src).'" />';
  }
 return $html;
}

function amp_enhancer_complete_html_after_dom_loaded( $content_buffer ) {

  if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
     
    $content_buffer = apply_filters('amp_enhancer_content_html_last_filter', $content_buffer);
  }
  
  return  $content_buffer;
}


add_action('wp', function(){ ob_start('amp_enhancer_complete_html_after_dom_loaded'); }, 999);