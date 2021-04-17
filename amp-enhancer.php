<?php
/*
Plugin Name: AMP Enhancer
Description: AMP Enhancer is a Compatibility Layer for Official AMP Plugin ( Its Plug & Play, Requires No Settings )
Author: ampenhancer
Version: 1.0.39
Author URI: http://ampenhancer.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: amp-enhancer
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define('AMP_ENHANCER_VERSION','1.0.39');
define('AMP_ENHANCER_PLUGIN_URI', plugin_dir_url(__FILE__));
define('AMP_ENHANCER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AMP_ENHANCER_BASENAME',plugin_basename(__FILE__));
define('AMP_ENHANCER_TEMPLATE_DIR', plugin_dir_path(__FILE__).'templates/');
define('AMP_ENHANCER_TEMPLATE_URI', plugin_dir_url(__FILE__).'templates/');
define('AMP_ENHANCER_PAGEBUILDER_DIR', plugin_dir_path(__FILE__).'pagebuilders/');
define('AMP_ENHANCER_PAGEBUILDER_URI', plugin_dir_url(__FILE__).'pagebuilders/');


require_once(AMP_ENHANCER_PLUGIN_DIR.'includes/functions.php');


add_action('plugins_loaded','amp_enhancer_third_party_plugins_support');

function amp_enhancer_third_party_plugins_support(){

			// Amp form Santizer Modification
		   if(function_exists('amp_activate')){
			  add_filter('amp_content_sanitizers','amp_enhancer_form_sanitizer',100);
			}
			// Woocommerce Template override
		   if(function_exists('WC')){
		     require_once(AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/wc_functions.php');
		    }
		   // Elementor Plugin  Support
		   if(class_exists('\Elementor\Plugin')){
			  require_once AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/elementor/amp-enhancer-elementor-pagebuilder.php';
		    }
		    // Contact Form Response Support
		    if(class_exists('WPCF7_ContactForm')){
		      require_once(AMP_ENHANCER_TEMPLATE_DIR.'contact-form7/cf7_functions.php');
		      require_once(AMP_ENHANCER_TEMPLATE_DIR.'contact-form7/class_amp_enhancer_wpcf7_contactform.php');
		    }
	       // Cookie Notice
	       if(class_exists('Cookie_Notice')){
		      require_once(AMP_ENHANCER_TEMPLATE_DIR.'cookie-notice/cookie-notice-functions.php');
		   }

		   // GDPR Cookie Consent
	       if(class_exists('Cookie_Law_Info')){
		      require_once(AMP_ENHANCER_TEMPLATE_DIR.'cookie-law-info/cookie-law-info-functions.php');
		   }

		    // GDPR Cookie Compliance
	       if(function_exists('gdpr_cookie_compliance_load_libs')){

		      require_once(AMP_ENHANCER_TEMPLATE_DIR.'gdpr-cookie-compliance/gdpr-cookie-compliance-functions.php');
		   }

		    // Shortcodes Ultimate
	       if ( class_exists('Shortcodes_Ultimate_Shortcodes')) {
		      require_once(AMP_ENHANCER_TEMPLATE_DIR.'shortcodes-ultimate/shortcodes-ultimate-functions.php');
		   }

		   // WP-FORMS
		   if(function_exists('wpforms')){
		   	require_once(AMP_ENHANCER_TEMPLATE_DIR.'wp-forms/amp-wp-forms-functions.php');
		   }

		   // Ninja Forms
		    if(function_exists('Ninja_Forms')){
		   	require_once(AMP_ENHANCER_TEMPLATE_DIR.'ninja-forms/amp-enhancer-ninja-forms-functions.php');
		   }

		   // KK Star Rating
		    if(function_exists('kksr_freemius')){
		   	require_once(AMP_ENHANCER_TEMPLATE_DIR.'kk-star/amp-enhancer-kkstar-functions.php');
		   } 
		    // Content Views
      	   if(class_exists('PT_CV_Html')){
		   	require_once(AMP_ENHANCER_TEMPLATE_DIR.'content-views/content-views-functions.php');
		   }
		   
		   //Icegram
		   if(class_exists('Icegram')){
		   	 require_once(AMP_ENHANCER_TEMPLATE_DIR.'icegram/amp-enhancer-icegram-popup-html.php');
		   }
		   //Helpie FAQ
     	   if(class_exists('Helpie_FAQ_Plugin')){
			require_once(AMP_ENHANCER_TEMPLATE_DIR.'helpie-faq/amp-enhancer-helpie-faq-functions.php');
     	   }
     	   // Convertkit
    	  if(class_exists('WP_ConvertKit')){
    	  	require_once(AMP_ENHANCER_TEMPLATE_DIR.'convertkit/amp-enhancer-convertkit-functions.php');
    	  }
          // SMART SLIDER3
    	  if(defined('SMARTSLIDER3_LIBRARY_PATH')){
    	  	require_once(AMP_ENHANCER_TEMPLATE_DIR.'smart-slider-3/shortcode.php');
    	  	require_once(AMP_ENHANCER_TEMPLATE_DIR.'smart-slider-3/AbstractController.php');
    	  	require_once(AMP_ENHANCER_TEMPLATE_DIR.'smart-slider-3/controllerSlider.php');
    	  }
          // fancy comments
    	  if(defined('HEATEOR_FFC_VERSION')){
    	  	require_once(AMP_ENHANCER_TEMPLATE_DIR.'fancy-comments-wordpress/fancy-comments-wordpress-functions.php');
    	  }
         // Adapta RGPD
         if(class_exists('Adapta_RGPD')){ 
          require_once(AMP_ENHANCER_TEMPLATE_DIR.'adapta-rgpd/amp-enhancer-adapta-rgpd-functions.php');
    	  }
         // Ultimate Addons For Gutenberg
    	  if(class_exists('UAGB_Loader')){
		     require_once(AMP_ENHANCER_TEMPLATE_DIR.'ultimate-addons-for-gutenberg/amp-enhancer-ul-addon-gtbg-functions.php');
		  }
		  // NinjaTable
    	  if(function_exists('ninja_tables_boot')){
    	  	require_once(AMP_ENHANCER_TEMPLATE_DIR.'ninja-tables/amp-enhancer-ninja-tables-functions.php');
    	  }
    	  // Redirection for Contact Form 7
    	  if(class_exists('WPCF7r_Submission')){ 
    	  		require_once(AMP_ENHANCER_TEMPLATE_DIR.'wpcf7-redirect/amp-enhancer-wpcf7-redirect-functions.php');	
    	  		add_action( 'wpcf7_submit','amp_enhancer_wpcf7_redirect_handle',99,1);
    	 }
    	 //FooGallery
    	 if(function_exists('foogallery_fs')){ 
          require_once(AMP_ENHANCER_TEMPLATE_DIR.'foogallery/foogallery-functions.php');	
     	 }

}
