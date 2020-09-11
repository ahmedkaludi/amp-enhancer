<?php
 require_once(AMP_ENHANCER_TEMPPLATE_DIR.'woocommerce/amp-enhancer-ajaxcalls.php');
 add_filter('wc_get_template','amp_enhancer_woocommerce_template_override',10,5);

 // Woocommerce Template override
 function amp_enhancer_woocommerce_template_override($template, $template_name, $args, $template_path, $default_path){

	  if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {

	  	   	if($template_name == 'single-product/product-image.php'){
			   $template = AMP_ENHANCER_TEMPPLATE_DIR.'woocommerce/single-product/product-image.php';
			   }
             if($template_name == 'single-product/tabs/tabs.php'){
				    $template = AMP_ENHANCER_TEMPPLATE_DIR.'woocommerce/single-product/tabs/tabs.php';
				}
	  		if($template_name == 'cart/cart.php'){
			    $template = AMP_ENHANCER_TEMPPLATE_DIR.'woocommerce/cart/cart.php';
				}
			if($template_name == 'loop/orderby.php'){
			    $template = AMP_ENHANCER_TEMPPLATE_DIR.'woocommerce/loop/orderby.php';
			    }
		    if($template_name == 'notices/error.php'){
		        $template = AMP_ENHANCER_TEMPPLATE_DIR.'woocommerce/notices/error.php';
		        }
			    
		 }
			return $template;

 }

