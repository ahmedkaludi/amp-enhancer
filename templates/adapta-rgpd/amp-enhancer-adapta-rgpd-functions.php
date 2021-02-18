<?php 

add_filter('amp_enhancer_content_html_last_filter','amp_enhancer_adapta_rpgd_html',10,1);

function amp_enhancer_adapta_rpgd_html($content_buffer){
	$submit_url =  admin_url('admin-ajax.php?action=amp_enhancer_cookie_rpgd_handle');
    $cookie_rpgd_Url = preg_replace('#^https?:#', '', $submit_url);
	$cookie_rpgd_nonce 		  = 	wp_create_nonce( 'amp_enhancer_cookie_rpgd' );
 	$hidden = ''; 

	if(isset($_COOKIE['hasConsent'])){
      $hidden = 'hidden';
	}

    if(preg_match('/(.*)<div(.*?)id="cookies-eu-wrapper"(.*?)<\/div>(.*?)<div\s+id="cookies-eu-banner-closed(.*?)>/s', $content_buffer)){

	     $content_buffer = preg_replace('/(.*)<div(.*?)id="cookies-eu-wrapper"(.*?)<\/div>(.*?)<div\s+id="cookies-eu-banner-closed(.*?)>/s', '$1
	     	 <form class="amp-cookie-rpgd"  method="post" action-xhr="'.  esc_url_raw($cookie_rpgd_Url).'">
	     	 <div '.esc_attr($hidden).'  [hidden]="hideCookieRgid" $2id="cookies-eu-wrapper"$3<input type="hidden" name="amp_enhancer_cookie_rpgd" value="'.esc_attr($cookie_rpgd_nonce).'">
	     	 </div>$4
	     	 </form>
	     	 <div id="cookies-eu-banner-closed$5>', $content_buffer);
 	}
    
    if(preg_match('/<button\s+id="cookies-eu-(.*?)"(.*?)>/s', $content_buffer)){

     	$content_buffer = preg_replace('/<button\s+id="cookies-eu-(.*?)"(.*?)>/s', '<button type="submit"  id="cookies-eu-$1"$2 name="'.esc_attr('cookies-eu-$1').'"  value="'.esc_attr('cookies-eu-$1').'" on="tap:AMP.setState({ hideCookieRgid: true })" >', $content_buffer);
 	}

     return  $content_buffer;

}


add_action('wp_ajax_amp_enhancer_cookie_rpgd_handle','amp_enhancer_cookie_rpgd_handle');
add_action('wp_ajax_nopriv_amp_enhancer_cookie_rpgd_handle','amp_enhancer_cookie_rpgd_handle');

function amp_enhancer_cookie_rpgd_handle(){
        
    header("access-control-allow-credentials:true");
    header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
    $siteUrl = parse_url(get_site_url());
    header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
    header("Content-Type:application/json");

   if(!wp_verify_nonce(sanitize_key($_POST['amp_enhancer_cookie_rpgd']),'amp_enhancer_cookie_rpgd') ){

		header('HTTP/1.1 500 FORBIDDEN');
	    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
	    header("Content-Type:application/json");
		echo wp_json_encode( 'Sorry, your nonce did not verify.' );
    	wp_die();

	} else {
		
	   	$cookie_path = '/';

	   	$expiry_time = time() + 33696000  ; // 13 months in seconds
	   	$hasConsents = array('ANLTCS','SCLS');
	   	$hasConsents = join("+",$hasConsents);

	    if(isset($_POST['cookies-eu-accept'])){
	     setcookie('hasConsent','true',esc_attr($expiry_time) , esc_attr($cookie_path));
	     setcookie('hasConsents',esc_attr($hasConsents),esc_attr($expiry_time) , esc_attr($cookie_path));
	    }elseif(isset($_POST['cookies-eu-reject'])){
	  	 setcookie('hasConsent','false',esc_attr($expiry_time), esc_attr($cookie_path));
	  	 setcookie('hasConsents','',esc_attr($expiry_time), esc_attr($cookie_path));
	    }

	    echo json_encode(array('successmsg'=>'Cookie Added'));
		exit;
    }
}