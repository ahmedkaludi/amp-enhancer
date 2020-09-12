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

// Added Support Link
add_filter( 'plugin_action_links_' .AMP_ENHANCER_BASENAME, 'amp_enhancer_support_link' );

function amp_enhancer_support_link( $links ) { 
	$support_link = '<a href="'.esc_url_raw( "https://magazine3.company/contact/" ).'">'.esc_html__('Support', 'amp-enhancer').'</a>';
	array_unshift( $links, $support_link );
	return $links; 
}

// Added Opening Notice
 add_action( 'admin_notices', 'amp_enhancer_admin_notice' );

function amp_enhancer_admin_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'amp_enhancer_dismiss_notice' ) ){ 
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