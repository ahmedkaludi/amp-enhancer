<?php 

 require_once(AMP_ENHANCER_TEMPLATE_DIR.'ninja-forms/amp-ninja-forms-array-data.php');
 require_once(AMP_ENHANCER_TEMPLATE_DIR.'ninja-forms/amp-ninja-forms-fields.php');


if(!function_exists('amp_enhancer_ninja_forms_shortcode')){
	function amp_enhancer_ninja_forms_shortcode($atts, $content = null, $code = ''){
		
		$form_id 	= (int) $atts['id'];
		$formoptions = amp_enhancer_get_ninja_formData($form_id);
		// echo "<pre>";
  //       print_r($formoptions);die;

		$content = '';
		ob_start();
        Ninja_Forms::template( 'display-form-container.html.php', compact( 'form_id' ) );
        $content .= ob_get_contents();
        ob_get_clean();

        //FieldWrapper
        $fieldWrapper = '';
        ob_start();
        Ninja_Forms::template( 'fields-wrap.html', compact( 'form_id' ) );
        $fieldWrapper = ob_get_contents();
        ob_get_clean();

        ob_start();
	    Ninja_Forms::template( 'fields-label.html', compact( 'form_id' ) );
	    $fieldlabel = ob_get_contents();
	    ob_get_clean();

        $wrapper = '';
        foreach($formoptions['fields'] as $key => $field){
            $removeFields = array('recaptcha');
            if(in_array($field['type'], $removeFields)){
                continue;
            }

        	$fieldsHtml = amp_enhancer_ninja_field_markup($field);
        	$wrapper .= amp_enhancer_ninja_wrapper_template_cleanup($fieldWrapper, $fieldlabel, $fieldsHtml,$field);
        }
        $content = str_replace('<div class="nf-loading-spinner"></div>', $wrapper, $content);
        $content = str_replace('>Submit', '', $content);
        $submit_url =  admin_url('admin-ajax.php?action=amp_enhancer_ninja_form_submission');
		$actionXhrUrl = preg_replace('#^https?:#', '', $submit_url);

        $content = wp_nonce_field('ninja_forms_display_nonce','_wpnonce',true,false).$content;
		$content = "<input type='hidden' name='form_detail' value='".$form_id."'> ".$content;
		$content = '<div id="ninja_form_'.esc_attr($form_id).'" class="form-fields-wrapper">'.$content.'</div>
            <div submit-success>
			<template type="amp-mustache" >
                <div class="amp-en-form-status amp_en_success_message">
    			  <b>Success</b>! {{{data.actions.success_message}}}
                </div>
			</template>
		</div>
		<div submit-error>
		    <template type="amp-mustache" >
				<div class="amp-en-form-status amp_en_error_message">
				{{message}}
				<div class="amp-en-form-status amp_en_error_message">
				 {{#errors}} 
				 	<div>{{error_detail}}</div>
				 {{/errors}} 
				</div> 
				</div>
			</template>
		</div>
			';
		$amp_class_append = '';
        $content = '<form class="ninja_wrapper ampforwp-form-allow" action-xhr="'.esc_url($actionXhrUrl).'" method="post" target="_top">'.$content.'</form>';
        return $content;
	}
}

// Ninja Forms

add_action('wp_ajax_amp_enhancer_ninja_form_submission','amp_enhancer_ninja_form_submission');
add_action('wp_ajax_nopriv_amp_enhancer_ninja_form_submission','amp_enhancer_ninja_form_submission');

function amp_enhancer_ninja_form_submission(){

    if(!wp_verify_nonce($_POST['_wpnonce'],'ninja_forms_display_nonce')){
        header('HTTP/1.1 500 FORBIDDEN');
    }else{
        require_once AMP_ENHANCER_TEMPLATE_DIR."ninja-forms/submission/submit_controller.php";
        require_once AMP_ENHANCER_TEMPLATE_DIR."ninja-forms/submission/submission.php";
        require_once AMP_ENHANCER_TEMPLATE_DIR."ninja-forms/submission/handle_submission.php";
    }
    header("access-control-allow-credentials:true");
    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
    header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
    $siteUrl = parse_url(
            get_site_url()
        );
    header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
    header("Content-Type:application/json");
     wp_die();
}