<?php 

remove_shortcode( 'cookie_button' );
// a shortcode [cookie_button]
add_shortcode( 'cookie_button','amp_enhancer_cookielawinfo_shortcode_main_button');
/** Returns HTML for a generic button */
function amp_enhancer_cookielawinfo_shortcode_main_button( $atts ) {
	extract(shortcode_atts(array(
	    'margin' => '',
	), $atts ));
	$margin_style=$margin!="" ? ' margin:'.$margin.'; ' : '';

	$defaults =Cookie_Law_Info::get_default_settings();            
	$settings = wp_parse_args( Cookie_Law_Info::get_settings(),$defaults);        
	$class = '';
    $id_name = str_replace('#', '', $settings['button_1_action']);
    
	if($settings['button_1_as_button']) {
	    $class = ' class="' . esc_attr($settings['button_1_button_size']) . ' cli-plugin-button cli-plugin-main-button cookie_action_close_header cli_action_button"';
	}else{
	    $class = ' class="cli-plugin-main-button cookie_action_close_header cli_action_button" ' ;
	}

	// If is action not URL then don't use URL!
	$url = ( $settings['button_1_action'] == "CONSTANT_OPEN_URL" && $settings['button_1_url'] != "#" ) ? "href='esc_url($settings[button_1_url])'" : "role='button' tabindex='0'";        
	$link_tag = '<button name="accept" value="accept" on="tap:AMP.setState({hideCookieLaw: true,RevokeCookieLaw: false})" '.$url.' data-cli_action="accept" id="' . esc_attr($id_name) . '" ';
	$link_tag .= ( $settings['button_1_new_win'] ) ? 'target="_blank" ' : '' ;
	$link_tag .= $class.' style="display:inline-block; '.$margin_style.'">' . stripslashes( esc_html__($settings['button_1_text'],'cookie-law-info') ) . '</button>';

  return $link_tag;
}

remove_shortcode( 'cookie_reject' );
// a shortcode [cookie_button]
add_shortcode( 'cookie_reject','amp_enhancer_cookielawinfo_shortcode_reject_button');

/** Returns HTML for a standard (green, medium sized) 'Reject' button */
function amp_enhancer_cookielawinfo_shortcode_reject_button( $atts ) {
    extract(shortcode_atts(array(
        'margin' => '',
    ), $atts ));
    $margin_style=$margin!="" ? ' style="margin:'.$margin.';" ' : '';

    $defaults = Cookie_Law_Info::get_default_settings();
    $settings = wp_parse_args(Cookie_Law_Info::get_settings(),$defaults);
    
    $classr = '';
    $id_name = str_replace('#', '', $settings['button_3_action']);

    if($settings['button_3_as_button']) {
        $classr=' class="' . esc_attr($settings['button_3_button_size']) . ' cli-plugin-button cli-plugin-main-button cookie_action_close_header_reject cli_action_button"';
    }else{
        $classr=' class="cookie_action_close_header_reject cli_action_button" '; 
    }

    $url_reject = ( $settings['button_3_action'] == "CONSTANT_OPEN_URL" && $settings['button_3_url'] != "#" ) ? "href='esc_url($settings[button_3_url])'" : "role='button' tabindex='0'";
    $link_tag = '';
    $link_tag .= '<button name="reject" value="reject" on="tap:AMP.setState({hideCookieLaw: true,RevokeCookieLaw: false})"  '.$url_reject.' id="'.esc_attr($id_name).'" ';
    $link_tag .= ($settings['button_3_new_win'] ) ? 'target="_blank" ' : '' ;
    $link_tag .= $classr . '  data-cli_action="reject"'.$margin_style.'>' . stripslashes(esc_html__($settings['button_3_text'],'cookie-law-info')) . '</button>';
    return $link_tag;
}

remove_shortcode( 'cookie_settings' );
// a shortcode [cookie_button]
add_shortcode( 'cookie_settings','amp_enhancer_cookielawinfo_shortcode_settings_button');

function amp_enhancer_cookielawinfo_shortcode_settings_button( $atts ) {   
    extract(shortcode_atts(array(
        'margin' => '',
    ), $atts ));

    $margin_style=$margin!="" ? ' style="margin:'.$margin.';" ' : '';
    $defaults =Cookie_Law_Info::get_default_settings();
    $settings =wp_parse_args(Cookie_Law_Info::get_settings(),$defaults);
    $settings['button_4_url']="#";
    $settings['button_4_action']='#cookie_action_settings';
    $settings['button_4_new_win']=false;
    $classr = '';

    if( $settings['button_4_as_button'] ) {
        $classr= ' class="' . $settings['button_4_button_size'] . ' cli-plugin-button cli-plugin-main-button cli_settings_button"';
    }else{
        $classr= ' class="cli_settings_button"';
    }

    //adding custom style
    $url_s = ( $settings['button_4_action'] == "CONSTANT_OPEN_URL" && $settings['button_4_url'] != "#" ) ? "href='esc_url($settings[button_4_url])'" : "role='button' tabindex='0'";
    $link_tag = '';
    $link_tag .= '<a on="tap:AMP.setState({showpopup: \'cli-modal cli-show cli-blowup\',popupdelay: \'cli-modal-backdrop cli-fade cli-settings-overlay cli-show\',law_info_bar: \'law_info_bar\'})" ' . $url_s;
    $link_tag .= ( $settings['button_4_new_win'] ) ? ' target="_blank" ' : '' ;
    $link_tag .= $classr.''.$margin_style.'>' . stripslashes( esc_html($settings['button_4_text']) ) . '</a>';
    return $link_tag;           
}
remove_shortcode( 'cookie_close' );
// a shortcode [cookie_button]
add_shortcode( 'cookie_close','amp_enhancer_cookielawinfo_shortcode_close_button');
function amp_enhancer_cookielawinfo_shortcode_close_button(){     

    return '<button name="accept" value="accept" on="tap:AMP.setState({hideCookieLaw: true,RevokeCookieLaw: false})"  aria-label="'.esc_html__('Close the cookie bar','cookie-law-info').'" data-cli_action="accept" class="wt-cli-element cli_cookie_close_button" title="'.esc_html__('Close and Accept','cookie-law-info').'">×</button>';
}


add_shortcode( 'amp_wt_cli_ccpa_optout','amp_enhancer_wt_cli_ccpa_optout_callback');

function amp_enhancer_wt_cli_ccpa_optout_callback() {
        $ccpa_class = New Cookie_Law_Info_CCPA(); 
        $ccpa_data           = '';
        $ccpa_enabled        = $ccpa_class->ccpa_enabled;
        $ccpa_as_link        = $ccpa_class->ccpa_as_link;
        $ccpa_text           = $ccpa_class->ccpa_text;
        $ccpa_colour         = $ccpa_class->ccpa_link_colour;  
        if( $ccpa_enabled === false ) {
            return '';
        }
        if( $ccpa_as_link === false ) {

            $ccpa_data = '<span class="wt-cli-form-group wt-cli-custom-checkbox wt-cli-ccpa-checkbox"><input type="checkbox" id="wt-cli-ccpa-opt-out" class="wt-cli-ccpa-opt-out wt-cli-ccpa-opt-out-checkbox" ><label for="wt-cli-ccpa-opt-out" style="color:'.amp_enhancer_sanitize_color($ccpa_colour).';" >'.esc_html($ccpa_text).' testttt</label></span>';
        
        } else {
            $ccpa_data = '<a on="tap:AMP.setState({ccpapopup:false,popupdelay: \'cli-modal-backdrop cli-fade cli-settings-overlay cli-show\'})" role="button" tabindex=\'0\'  style="color:'.amp_enhancer_sanitize_color($ccpa_colour).';" class="wt-cli-ccpa-opt-out test">'.esc_html($ccpa_text).'</a>';
            $ccpa_data .= '<div hidden [hidden]="ccpapopup" class="cli-modal cli-show cli-blowup" id="cLiCcpaOptoutPrompt"><div class="cli-modal-dialog"><div class="cli-modal-content cli-bar-popup"><div class="cli-modal-body"><div class="wt-cli-element cli-container-fluid cli-tab-container"><div class="cli-row"><div class="cli-col-12"><div class="cli-alert-dialog-content">Do you really wish to opt out?</div><div class="cli-alert-dialog-buttons"><button name="ccpa_notice" value="cancel" on="tap:AMP.setState({ccpapopup:true,popupdelay: \'cli-modal-backdrop cli-fade cli-settings-overlay\'})" class="cli-ccpa-button-cancel">Cancel</button><button name="ccpa_notice" value="confirm" on="tap:AMP.setState({ccpapopup:true,popupdelay: \'cli-modal-backdrop cli-fade cli-settings-overlay\'})" class="cli-ccpa-button-confirm">Confirm</button></div></div></div></div></div></div></div></div>';
        }
        return $ccpa_data;
}
