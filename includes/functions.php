<?php 
// Add Custom CSS
add_action( 'wp_head', 'amp_enhancer_add_custom_css');
function amp_enhancer_add_custom_css(){
   if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
    	wp_enqueue_style( 'amp_enhancer_css', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/includes/style.css', false, AMP_ENHANCER_VERSION );
      }
}



add_action('wp','amp_enhancer_third_party_compatibililty');

function amp_enhancer_third_party_compatibililty(){
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
	  	
	}

}