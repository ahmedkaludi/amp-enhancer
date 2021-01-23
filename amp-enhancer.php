<?php
/*
Plugin Name: AMP Enhancer
Description: AMP Enhancer is a Compatibility Layer for Official AMP Plugin ( Its Plug & Play, Requires No Settings )
Author: ampenhancer
Version: 1.0.20
Author URI: http://ampenhancer.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: amp-enhancer
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define('AMP_ENHANCER_VERSION','1.0.20');
define('AMP_ENHANCER_PLUGIN_URI', plugin_dir_url(__FILE__));
define('AMP_ENHANCER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AMP_ENHANCER_BASENAME',plugin_basename(__FILE__));
define('AMP_ENHANCER_TEMPLATE_DIR', plugin_dir_path(__FILE__).'templates/');
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
		   //Astra Addon
		   if(defined('ASTRA_EXT_VER')){
		   	require_once(AMP_ENHANCER_TEMPLATE_DIR.'astra-addon/amp-enhancer-astra-addon-functions.php');
		   }
		   //Icegram
		   if(class_exists('Icegram')){
		   	 require_once(AMP_ENHANCER_TEMPLATE_DIR.'icegram/amp-enhancer-icegram-popup-html.php');
		   }
		   //Helpie FAQ
     	   if(class_exists('Helpie_FAQ_Plugin')){
			require_once(AMP_ENHANCER_TEMPLATE_DIR.'helpie-faq/amp-enhancer-helpie-faq-functions.php');
     	   }
		     
}
