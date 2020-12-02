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

add_action( 'admin_menu', 'amp_enhancer_settings_option', 50 );
function amp_enhancer_settings_option() {

    if(class_exists('AMP_Options_Manager')){

       $amp_options = New AMP_Options_Manager();
       add_submenu_page( $amp_options::OPTION_NAME,
                         esc_html__( 'AMP Enhancer', 'amp-enhancer' ),
                         esc_html__( 'AMP Enhancer', 'amp-enhancer' ),
                         'manage_options',
                         'amp-enhacer-settings',
                         'amp_enhancer_settings_page' 
                       );
       }
    }

function  amp_enhancer_settings_page(){
    $woocommerce = $elementor = $contact_form7 = $GDPR_Cookie = $Cookie_Notice = $GDPR_Compliance = false;
    if(function_exists('WC')){
     $woocommerce = true;
    }
    if(class_exists('\Elementor\Plugin')){
     $elementor = true;
    }
    if(class_exists('WPCF7_ContactForm')){
     $contact_form7 = true;
    }
    if(class_exists('Cookie_Law_Info')){
     $GDPR_Cookie = true;
    }
    if(class_exists('Cookie_Notice')){
     $Cookie_Notice = true;
    }
    if(function_exists('gdpr_cookie_compliance_load_libs')){
     $GDPR_Compliance = true;
    }
 ?>
 <div class="enhc-container">
  <h1>AMP Enhancer Settings</h1>
    <div class="enhr-comp-cont">
             <div class="enhancer-nosetup">
                <h3 class="enhc-notice-title">No Setup Required</h3>
                <span class="enhc-notice-cont">It will now Automatically include the  features of the compatible third party plugins and starts working.</span>
            </div>
            <div class="enhr-compatible-section">
                <h2 class="enhr-comp-title">Compatibilities :</h2>
              <table class="enhr-comp-table">
                  <tr>
                    <th>Plugin</th>
                    <th>Status</th>
                  </tr>
                    <tr>
                    <td>WooCommerce</td>
                        <?php if($woocommerce == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Elementor</td>
                        <?php if($elementor == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Contact Form 7</td>
                        <?php if($contact_form7 == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>GDPR Cookie Consent</td>
                        <?php if($GDPR_Cookie == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Cookie Notice</td>
                        <?php if($Cookie_Notice == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>GDPR Cookie Compliance (CCPA, PIPEDA ready)</td>
                        <?php if($GDPR_Compliance == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                </table>
            </div>
    </div>
    <div class="enhr_support">
        <div class="postbox">
                <h2 class="enhr-hndle">
                    <span class="dashicons dashicons-email-alt"></span>
                    <span>Technical Support</span>
                </h2>
                <div class="enhr-inside">
                    <p>
                       If you have any queries, you can directly connect with our support developers by submitting a ticket
                    </p>
                    <p>
                    Our team will get back to your email address very shortly
                    </p>
                    <a href="http://ampenhancer.com/contact-us/">Submit a Ticket »</a>               </div>
            </div>

            <div class="postbox">
                <h2 class="enhr-hndle">
                    <span class="dashicons dashicons-star-filled"></span>
                    <span>Rate Us</span>
                </h2>
                <div class="enhr-inside">
                    <p>
                       If you have got the features which you are looking for in our plugin, then please rate us a 5 star review on WordPress.org.</p>
                    <p>This will help spread the word out about this plugin and will encourage us to continue the development.</p>
                    <p>Much appreciated, thank you very much.</p>
                    <a href="https://wordpress.org/support/plugin/amp-enhancer/reviews/?rate=5#new-post">Give Us a 5 star</a>               </div>
            </div>
    </div>
</div>
 <?php
}

add_action( 'admin_enqueue_scripts', 'amp_enhancer_settings_page_css' );
function amp_enhancer_settings_page_css( $hook ) {
 global $current_screen;
 $pagenow = false; 
  if(isset($current_screen->id)){ 
       if($current_screen->id == 'amp_page_amp-enhacer-settings'){
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