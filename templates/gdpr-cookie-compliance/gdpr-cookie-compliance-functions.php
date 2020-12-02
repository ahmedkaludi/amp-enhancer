<?php
function amp_enhancer_gdpr_infobar_base_module($response){

	$submit_url =  admin_url('admin-ajax.php?action=amp_enhancer_gdpr_compliance_handle');
    $gdpr_complianceXhrUrl = preg_replace('#^https?:#', '', $submit_url);
	$gdpr_compliance_nonce 		  = 	wp_create_nonce( 'amp_enhancer_gdpr_compliance' );

 	if ( !isset( $_COOKIE['moove_gdpr_popup'] ) ){
 	  $response = str_replace('moove-gdpr-info-bar-hidden', '', $response);
     }
     if(preg_match('/<aside\s+id="moove_gdpr_cookie_info_bar"(.*?)>/', $response)){
     	$response = preg_replace('/<aside\s+id="moove_gdpr_cookie_info_bar"(.*?)>/', '<aside id="moove_gdpr_cookie_info_bar" [hidden]="hideBar" $1>', $response);
     }
 	$amp_response = '<form class="amp-gdpr_compliance"  method="post" action-xhr="'.  esc_url_raw($gdpr_complianceXhrUrl).'">'.$response.'
 	   <input type="hidden" name="amp_enhancer_gdpr_compliance" value="'.esc_attr($gdpr_compliance_nonce).'">
 	    </form>';

 return $amp_response;
}

function amp_enhancer_gdpr_infobar_buttons_module($response){
        
    if(preg_match('/<button\s+class="(.*?)mgbutton(.*?)"\s+aria-label="Accept"(.*?)>/', $response)){    
		$response = preg_replace('/<button\s+class="(.*?)mgbutton(.*?)"\s+aria-label="Accept"(.*?)>/', '<button type="submit" on="tap:AMP.setState({ hideBar: true})" class="$1mgbutton$2" aria-label="Accept" name="accept" value="accept" >', $response);
    }
    if(preg_match('/<button\s+class="(.*?)mgbutton(.*?)"\s+aria-label="Reject"(.*?)>/', $response)){ 
		$response = preg_replace('/<button\s+class="(.*?)mgbutton(.*?)"\s+aria-label="Reject"(.*?)>/', '<button type="submit" on="tap:AMP.setState({ hideBar: true})" class="$1mgbutton$2" aria-label="Reject" name="reject" value="reject" >', $response);
     }


 return $response;

}



add_action('wp_ajax_amp_enhancer_gdpr_compliance_handle','amp_enhancer_gdpr_compliance_handle');
add_action('wp_ajax_nopriv_amp_enhancer_gdpr_compliance_handle','amp_enhancer_gdpr_compliance_handle');

function amp_enhancer_gdpr_compliance_handle(){
        
    header("access-control-allow-credentials:true");
    header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
    $siteUrl = parse_url(get_site_url());
    header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
    header("Content-Type:application/json");

   if(!wp_verify_nonce(sanitize_key($_POST['amp_enhancer_gdpr_compliance']),'amp_enhancer_gdpr_compliance') ){

		header('HTTP/1.1 500 FORBIDDEN');
	    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
	    header("Content-Type:application/json");
		echo wp_json_encode( 'Sorry, your nonce did not verify.' );
    	wp_die();

	} else {
        if(class_exists('Moove_GDPR_Content')){
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
			$modal_options        = get_option( $option_name );
	    }
		$cookie_expiration 		= isset( $modal_options['moove_gdpr_consent_expiration'] ) && intval( $modal_options['moove_gdpr_consent_expiration'] ) >= 0 ? intval( $modal_options['moove_gdpr_consent_expiration'] ) : 365; 

	     $cookie_exp_secs = intval($cookie_expiration) * 86400;
	   	$expiry =  time()+$cookie_exp_secs;


	    if(isset($_POST['accept'])){
		   	$accept_value =  array(
							    'strict' => 1,
							    'thirdparty' => 1,
							    'advanced' => 1
							 );
	    	$encoded_value = json_encode( wp_slash( $accept_value ), true );
	     	setcookie('moove_gdpr_popup',$encoded_value,esc_attr($expiry) , '/');
	    }elseif(isset($_POST['reject'])){
				$reject_value=  array(
									'strict' => 1,
									'thirdparty' => 0,
									'advanced' => 0
									);
				$reject_encoded = json_encode( wp_slash( $reject_value ), true );
		  	 	setcookie('moove_gdpr_popup',$reject_encoded,esc_attr($expiry), '/');
	    }

	    echo json_encode(array('successmsg'=>'Cookie Added'));
		exit;
    }
}