<?php 
 // Cookie Notice Code Starts Here..
 function amp_enhancer_cookie_notice_html_markup($output, $options){

 	$submit_url =  admin_url('admin-ajax.php?action=amp_enhancer_cookie_notice_handle');
    $cookieXhrUrl = preg_replace('#^https?:#', '', $submit_url);
	$cookie_nonce 		  = 	wp_create_nonce( 'amp_enhancer_cookie_notice' );
 	$hidden_class = ''; 

    $revoke_class = 'cookie-revoke-hidden';
 	if(isset($_COOKIE['cookie_notice_accepted'])){
 		$hidden_class = 'cookie-notice-hidden';
 		$revoke_class = '';
 	}

 	$notice_bind_cls = 'cookie-notice-hidden cn-position-' . esc_attr($options['position']);

 	$revoke_bind_cls = 'cookie-revoke-hidden cn-position-' . esc_attr($options['position']);

 	$bkg_color =  'rgba(' . implode( ',', amp_enhancer_hex2rgb( $options['colors']['bar'] ) ) . ',' . $options['colors']['bar_opacity'] * 0.01 . ')';

   // message output
   $output = '
	     <form class="amp-cookie-notice"  method="post" action-xhr="'.  esc_url_raw($cookieXhrUrl).'">
			<div id="cookie-notice" [class]="hideCookie" role="banner" class=" '.esc_attr($hidden_class).  esc_attr($revoke_class).'   cn-position-' . esc_attr($options['position']) . '" aria-label="' . esc_attr($options['aria_label']) . '" style="background-color: '.amp_enhancer_sanitize_color($bkg_color).';">'
				. '<div class="cookie-notice-container" style="color: ' . amp_enhancer_sanitize_color($options['colors']['text']) . ';">'
				. '<span id="cn-notice-text" class="cn-text-container">'. esc_html__($options['message_text'],'amp-enhancer') . '</span>'
				. '<span id="cn-notice-buttons" class="cn-buttons-container"><button type="submit" on="tap:AMP.setState({ hideCookie: \''.esc_attr($notice_bind_cls).'\'})" id="cn-accept-cookie" data-cookie-set="accept" name="accept" value="accept" class="cn-set-cookie ' . esc_attr($options['button_class']) . ( $options['css_style'] !== 'none' ? ' ' . $options['css_style'] : '' ) . ( $options['css_class'] !== '' ? ' ' . esc_attr($options['css_class']) : '' ) . '" aria-label="' . esc_attr($options['accept_text']) . '">' . esc_html__($options['accept_text'],'amp-enhancer') . '</button>'
				. ( $options['refuse_opt'] === true ? '<button type="submit" on="tap:AMP.setState({ hideCookie: \''.esc_attr($notice_bind_cls).'\' })" id="cn-refuse-cookie" data-cookie-set="refuse" name="refuse" value="refuse" class="cn-set-cookie ' . esc_attr($options['button_class']) . ( $options['css_style'] !== 'none' ? ' ' . esc_attr($options['css_style']) : '' ) . ( $options['css_class'] !== '' ? ' ' . esc_attr($options['css_class']) : '' ) . '" aria-label="' . esc_attr($options['refuse_text']) . '">' . esc_html__($options['refuse_text'],'amp-enhancer') . '</button>' : '' )
				. ( $options['see_more'] === true && $options['link_position'] === 'banner' ? '<a href="' . ( $options['see_more_opt']['link_type'] === 'custom' ? esc_url($options['see_more_opt']['link']) : esc_url(get_permalink( $options['see_more_opt']['id'] ) ) ) . '" target="' . esc_attr($options['link_target']) . '" id="cn-more-info" class="cn-more-info ' . esc_attr($options['button_class']) . ( $options['css_style'] !== 'none' ? ' ' . esc_attr($options['css_style']) : '' ) . ( $options['css_class'] !== '' ? ' ' . esc_attr($options['css_class']) : '' ) . '" aria-label="' . esc_attr($options['see_more_opt']['text']) . '">' . esc_html__($options['see_more_opt']['text'],'amp-enhancer') . '</a>' : '' ) 
				. '</span><button type="submit" data-cookie-set="accept" name="accept" value="accept" on="tap:AMP.setState({ hideCookie: \''.esc_attr($notice_bind_cls).'\' })"  id="cn-close-notice" data-cookie-set="accept" class="cn-close-icon" aria-label="' . esc_attr($options['accept_text']) . '"></button>
				    <input type="hidden" name="amp_enhancer_cookie_notice" value="'.esc_attr($cookie_nonce).'"> '
				. '</div>
				' . ( $options['refuse_opt'] === true && $options['revoke_cookies'] == true ? 
				'<div class="cookie-revoke-container"  style="color: ' . amp_enhancer_sanitize_color($options['colors']['text']) . ';">'
				. ( ! empty( $options['revoke_message_text'] ) ? '<span id="cn-revoke-text" class="cn-text-container">'. esc_html__($options['revoke_message_text'],'amp-enhancer') . '</span>' : '' )
				. '<span id="cn-revoke-buttons" class="cn-buttons-container"><a on="tap:AMP.setState({ hideCookie: \''.esc_attr($revoke_bind_cls).'\' })" role="button" tabindex="0" class="cn-revoke-cookie ' . esc_attr($options['button_class']) . ( $options['css_style'] !== 'none' ? ' ' . esc_attr($options['css_style']) : '' ) . ( $options['css_class'] !== '' ? ' ' . esc_attr($options['css_class']) : '' ) . '" aria-label="' . $options['revoke_text'] . '">' . esc_html__( $options['revoke_text'],'amp-enhancer' ) . '</a></span>
				</div>' : '' ) . '
				<span class= "cookie-revoke-hidden cookie-notice-hidden"></span>
			</div>
		 </form>';

  return $output;

 }


add_action('wp_ajax_amp_enhancer_cookie_notice_handle','amp_enhancer_cookie_notice_handle');
add_action('wp_ajax_nopriv_amp_enhancer_cookie_notice_handle','amp_enhancer_cookie_notice_handle');

function amp_enhancer_cookie_notice_handle(){
        
    header("access-control-allow-credentials:true");
    header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
    $siteUrl = parse_url(get_site_url());
    header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
    header("Content-Type:application/json");

   if(!wp_verify_nonce(sanitize_key($_POST['amp_enhancer_cookie_notice']),'amp_enhancer_cookie_notice') ){

		header('HTTP/1.1 500 FORBIDDEN');
	    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
	    header("Content-Type:application/json");
		echo wp_json_encode( 'Sorry, your nonce did not verify.' );
    	wp_die();

	} else {
		
	   	$cookie_path =  defined( 'COOKIEPATH' ) ? (string) COOKIEPATH  : '/';

	   	$options = get_option('cookie_notice_options');

	   	if(isset($options['time'])){
	      $accept_time = amp_enhancer_calculate_cookie_exp_time($options['time']);
	   	}

	   	if(isset($options['time_rejected'])){
	   	   $refuse_time = amp_enhancer_calculate_cookie_exp_time($options['time_rejected']);
	   	}

	   	$accept_expiry = isset($accept_time) ? (time()+$accept_time) : 86400 ;
	   	$refuse_expiry = isset($refuse_time) ? (time()+$refuse_time) : 86400;

	    if(isset($_POST['accept'])){
	     setcookie('cookie_notice_accepted','true',esc_attr($accept_expiry) , esc_attr($cookie_path));
	    }elseif(isset($_POST['refuse'])){
	  	 setcookie('cookie_notice_accepted','false',esc_attr($refuse_expiry), esc_attr($cookie_path));
	    }

	    echo json_encode(array('successmsg'=>'Cookie Added'));
		exit;
    }
}

function amp_enhancer_calculate_cookie_exp_time($value){

	 switch($value) {

        case 'hour' :
          $time = 3600;
        break;

        case 'day' :
          $time = 86400;
        break;

        case 'week' :
          $time = 604800;
        break;

        case 'month' :
          $time = 2592000;
        break;

        case '3months' :
          $time = 7862400;
        break;

        case '6months' :
          $time = 15811200;
        break;

        case 'year' :
          $time = 31536000;
        break;

        case 'infinity' :
          $time = 2147483647;
        break;

        default :
          $time = 86400;
        break;
    }

   return $time;

}

function amp_enhancer_hex2rgb( $color ) {

		if ( $color[0] == '#' )
			$color = substr( $color, 1 );

		if ( strlen( $color ) == 6 )
			list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		elseif ( strlen( $color ) == 3 )
			list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		else
			return false;

		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );

		return array( $r, $g, $b );
}