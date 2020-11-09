<?php

// Empty Cookie Markup
function amp_enhancer_gdpr_cookie_consent_remove_markup($notify_html, $post_slug){
    $notify_html = '';
    return $notify_html;
}

// Generating cookielawinfo HTML Markup
function amp_enhancer_cookielawinfo_html_markup(){
		global $wp_customize;

		$the_options = Cookie_Law_Info::get_settings();
		if ($the_options['is_on'] == true) {
			// Output the HTML in the footer:
			$message = nl2br($the_options['notify_message']);
			$message = str_replace('[wt_cli_ccpa_optout]', '[amp_wt_cli_ccpa_optout]', $message);
			$str = do_shortcode(stripslashes($message));
			$str = __($str, 'cookie-law-info');
			$head = __($the_options['bar_heading_text'], 'cookie-law-info');
			$head = trim(stripslashes($head));
			$thirdparty_options = get_option('cookielawinfo_thirdparty_settings');
			$expiry_time = time()+31536000;

			 if (!isset($_COOKIE["cookielawinfo-checkbox-necessary"])) {
                setcookie('cookielawinfo-checkbox-necessary','yes',esc_attr($expiry_time) , '/');
			 }

			 if(isset($thirdparty_options['thirdparty_on_field']) && ($thirdparty_options['thirdparty_on_field'] == true) && (!isset($_COOKIE["cookielawinfo-checkbox-non-necessary"]))){
			 	    $non_value = 'no';

			 	  if(isset($thirdparty_options['third_party_default_state']) && $thirdparty_options['third_party_default_state'] == true){
                     $non_value = 'yes';
			 	  }
			 	  
			 	setcookie('cookielawinfo-checkbox-non-necessary',esc_attr($non_value),esc_attr($expiry_time) , '/');
			 }

			$submit_url =  admin_url('admin-ajax.php?action=amp_enhancer_cookie_law_info_handle');
		    $cookieXhrUrl = preg_replace('#^https?:#', '', $submit_url);

			$showagain_tab = $hidden_class = ''; 

		    $revoke_class = 'hidden';
		 	if(isset($_COOKIE['viewed_cookie_policy'])){
		 		$hidden_class = 'hidden';
		 		$revoke_class = '';
		 	}
		 	
		 	if(isset($the_options['showagain_tab']) && $the_options['showagain_tab'] == false){
				$showagain_tab = 'hidden';
			}

			$notify_html = '<form class="amp-cookie-law-notice"  method="post" 						 action-xhr="'.  esc_url_raw($cookieXhrUrl).'">
							<div id="' . esc_attr(amp_enhancer_cookielawinfo_remove_hash($the_options["notify_div_id"])) . '" '.esc_attr($hidden_class).' [hidden]="hideCookieLaw" [class]="law_info_bar"  >' .
							($head != "" ? '<h5 class="cli_messagebar_head">' . $head . '</h5>' : '')
							. '<span>' . $str . '</span></div>';

			$show_again = $the_options["showagain_text"];
			$notify_html .= '<div '.esc_attr($showagain_tab).' id="' . esc_attr(amp_enhancer_cookielawinfo_remove_hash($the_options["showagain_div_id"])) . '" '.esc_attr($revoke_class).'  [hidden]="RevokeCookieLaw"  role="button" tabindex="0" on="tap:AMP.setState({hideCookieLaw: false,RevokeCookieLaw: true,showpopup: \'cli-modal\',overlaypopup:\'cli-modal-backdrop cli-fade cli-popupbar-overlay cli-show\'})"  ><span id="cookie_hdr_showagain">' . esc_html__($show_again,'cookie-law-info') . '</span></div>';
			//}
			global $wp_query;
			$current_obj = get_queried_object();
			$post_slug = '';
			if (is_object($current_obj)) {
				if (is_category() || is_tag()) {
					$post_slug = isset($current_obj->slug) ? $current_obj->slug : '';
				} elseif (is_archive()) {
					$post_slug = isset($current_obj->rewrite) && isset($current_obj->rewrite['slug']) ? $current_obj->rewrite['slug'] : '';
				} else {
					if (isset($current_obj->post_name)) {
						$post_slug = $current_obj->post_name;
					}
				}
			}
			if (isset($wp_customize)) {
				$notify_html = '';
			}
			//$notify_html = apply_filters('cli_show_cookie_bar_only_on_selected_pages', $notify_html, $post_slug);
			require_once(AMP_ENHANCER_TEMPLATE_DIR.'cookie-law-info/views/cookie-law-info_bar.php');
		}
}


function amp_enhancer_cookielawinfo_remove_hash($str){

		if ($str[0] == "#") {
			$str = substr($str, 1, strlen($str));
		} else {
			return $str;
		}
		return $str;
}

function amp_enhancer_get_cookielawinfo_categories(){
		$cookie_categories = array(
			'necessary' => esc_html__('Necessary', 'cookie-law-info'),
			'non-necessary' => esc_html__('Non-necessary', 'cookie-law-info'),
		);
		$cookie_categories = apply_filters('wt_cli_add_custom_cookie_categories_name', $cookie_categories);
		return $cookie_categories;
}

function amp_enhancer_wt_cli_check_thirdparty_state(){

		$wt_cli_default_state = false;

		$third_party_cookie_options = get_option('cookielawinfo_thirdparty_settings');
		$wt_cli_default_state_field =  isset($third_party_cookie_options['third_party_default_state']) ? $third_party_cookie_options['third_party_default_state'] : true;
		$wt_cli_default_state = Cookie_Law_Info::sanitise_settings('third_party_default_state', $wt_cli_default_state_field);

		return $wt_cli_default_state;
}

// Ajax Handle
add_action('wp_ajax_amp_enhancer_cookie_law_info_handle','amp_enhancer_cookie_law_info_handle');
add_action('wp_ajax_nopriv_amp_enhancer_cookie_law_info_handle','amp_enhancer_cookie_law_info_handle');

function amp_enhancer_cookie_law_info_handle(){
        
    header("access-control-allow-credentials:true");
    header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
    $siteUrl = parse_url(get_site_url());
    header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
    header("Content-Type:application/json");

	 if(!wp_verify_nonce(sanitize_key($_POST['amp_enhancer_cookie_law_info']),'amp_enhancer_cookie_law_info') ){

		header('HTTP/1.1 500 FORBIDDEN');
	    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
	    header("Content-Type:application/json");
		echo wp_json_encode( 'Sorry, your nonce did not verify.' );
    	wp_die();

	} else {

	   	$expiry_time = time()+31536000;

	    if(isset($_POST['accept'])){
	     	setcookie('viewed_cookie_policy','yes',esc_attr($expiry_time) , '/');
	    }elseif(isset($_POST['reject'])){
	  	 	setcookie('viewed_cookie_policy','no',esc_attr($expiry_time), '/');
	    }

		$ccpa_consent = '';

	    if(isset($_COOKIE['cookielaw-ccpa-amp'])){

	    	if($_COOKIE['cookielaw-ccpa-amp'] == 'true'){
	    	 $set_ccpa_val = 'true';
           	}else{
 			$set_ccpa_val = 'false';
           	} 

           	$ccpa_consent = '"ccpaOptout":'.$set_ccpa_val.',';
	    }

	    if(isset($_POST['thirdparty_cookie'])){
	    	if($_POST['thirdparty_cookie'] == 'yes'){
               $thirdparty_value = 'yes';
               $non_necessary_val = 'true';
	    	}else{
			   $thirdparty_value = 'no';
			   $non_necessary_val = 'false';
	    	}
	    	setcookie('cookielawinfo-checkbox-non-necessary',esc_attr($thirdparty_value),esc_attr($expiry_time), '/');
	    	$thirdparty_json = '{'.$ccpa_consent.'"necessary":true,"non-necessary":'.$non_necessary_val.'}';
	    	$CookieLaw_cosnent =  base64_encode( $thirdparty_json );
	    	setcookie('CookieLawInfoConsent',esc_attr($CookieLaw_cosnent),esc_attr($expiry_time), '/');
	    	 echo json_encode(array('successmsg'=>'CookieLawInfoConsent Added'));
			exit;
	    }

	    $non_necessary = '';

	    if(isset($_COOKIE['cookielawinfo-checkbox-non-necessary'])){
	    	if($_COOKIE['cookielawinfo-checkbox-non-necessary'] == 'yes'){
	    		$non_cookie = 'true';
	    	}else{
	    		$non_cookie = 'false';
	    	}
	    	$non_necessary = ',"non-necessary":'.$non_cookie.'';
		}

        $json_format = '{'.$ccpa_consent.'"necessary":true'.$non_necessary.'}';

        $law_info_consent =  base64_encode( $json_format );

			$ccpa_notice = '';

        if(isset($_POST['ccpa_notice'])){
           
           if($_POST['ccpa_notice'] == 'confirm'){
             $ccpa_value = 'true';
           }else{
 			$ccpa_value = 'false';
           } 
           	$ccpa_json = '{"ccpaOptout":'.$ccpa_value.',"necessary":true'.$non_necessary.'}';

       		$ccpa_law_info_consent =  base64_encode( $ccpa_json);
       		setcookie('CookieLawInfoConsent',esc_attr($ccpa_law_info_consent),esc_attr($expiry_time), '/');
       		setcookie('cookielaw-ccpa-amp',esc_attr($ccpa_value),esc_attr($expiry_time), '/');
       		echo json_encode(array('successmsg'=>'CCPA Added'));
			exit;

		}

        if(isset($_POST['accept']) || isset($_POST['reject'])){
    	  setcookie('CookieLawInfoConsent',esc_attr($law_info_consent),esc_attr($expiry_time), '/');
        }
	    echo json_encode(array('successmsg'=>'Cookie Added'));
		exit;
	}
    
}

