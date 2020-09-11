<?php 
// Add Custom CSS
add_action( 'wp_head', 'amp_enhancer_add_custom_css');
function amp_enhancer_add_custom_css(){
   if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
    wp_enqueue_style( 'amp_enhancer_css', untrailingslashit(AMP_ENHANCER_PLUGIN_URI) . '/includes/style.css', false, AMP_ENHANCER_VERSION );
      }
}

 // Woocommerce Template override
 function amp_enhancer_woocommerce_template_override($template, $template_name, $args, $template_path, $default_path){

	  if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {

	  	   	if($template_name == 'single-product/product-image.php'){
			   $template = AMP_ENHANCER_PLUGIN_DIR.'templates/single-product/product-image.php';
			   }
             if($template_name == 'single-product/tabs/tabs.php'){
				    $template = AMP_ENHANCER_PLUGIN_DIR.'templates/single-product/tabs/tabs.php';
				}
	  		if($template_name == 'cart/cart.php'){
			    $template = AMP_ENHANCER_PLUGIN_DIR.'templates/cart/cart.php';
				}
			if($template_name == 'loop/orderby.php'){
			    $template = AMP_ENHANCER_PLUGIN_DIR.'templates/loop/orderby.php';
			    }
		    if($template_name == 'notices/error.php'){
		        $template = AMP_ENHANCER_PLUGIN_DIR.'templates/notices/error.php';
		    }
			    
		 }
			return $template;

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
	  add_filter('wpcf7_form_elements','amp_enhancer_cf7_form_elements_modification',10,1);
	  add_filter('wpcf7_form_novalidate','amp_enhancer_cf7_validate',10,1);
	  add_filter('wpcf7_form_class_attr','amp_enhancer_add_cf7_custom_class',10,1);
	  }
	  	
	}

}
function amp_enhancer_cf7_form_elements_modification($fields){
$required_msg = 'This field is required.';
$invalid_email = 'The e-mail address entered is invalid.';

 if(preg_match('<input\s+type="(.*?)"\s+name="(.*?)"\s+aria-required="true"(.*?)>', $fields)){
	$fields = preg_replace( '/<input\s+type="(.*?)"\s+name="(.*?)"\s+aria-required="true"(.*?)>/', '<input required type="$1" name="$2" id="show-all-on-submit-$2" aria-required="true"$3><span visible-when-invalid="valueMissing" validation-for="show-all-on-submit-$2">'.esc_html($required_msg).'</span>', $fields ); 
 }
 if(preg_match('/<input\s+required\s+type="email"\s+name="(.*?)"(.*?)>/', $fields)){
	$fields = preg_replace( '/<input\s+required\s+type="email"\s+name="(.*?)"(.*?)>/', '<input required type="email" name="$1" $2> <span visible-when-invalid="typeMismatch" validation-for="show-all-on-submit-$1">'.esc_html($invalid_email).'</span>', $fields );
 }
 if(preg_match('/<textarea(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/textarea>/', $fields)){
	 $fields = preg_replace( '/<textarea(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/textarea>/', '<textarea required $1 name="$2" $3 id="show-all-on-submit-$2" aria-required="true"$4>$5</textarea><span visible-when-invalid="valueMissing" validation-for="show-all-on-submit-$2">'.esc_html($required_msg).'</span>', $fields );
	}
    if(preg_match('/<select(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/select>/', $fields)){
	$fields = preg_replace( '/<select(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/select>/', '<select required $1 name="$2" $3 id="show-all-on-submit-$2" aria-required="true"$4>$5</select><span visible-when-invalid="valueMissing" validation-for="show-all-on-submit-$2">'.esc_html($required_msg).'</span>', $fields );
      }

return $fields;

}

function amp_enhancer_cf7_validate($validate){
$validate = false;
return $validate;

}

function amp_enhancer_add_cf7_custom_class($class){
$class .='  amp_wpcf7_form  ';
return $class;

}