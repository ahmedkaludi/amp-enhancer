<?php
// Included Ajaxcalls File.
 require_once(AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/amp-enhancer-ajaxcalls.php');

 // Woocommerce Template override
 add_filter('wc_get_template','amp_enhancer_woocommerce_template_override',10,5);

 function amp_enhancer_woocommerce_template_override($template, $template_name, $args, $template_path, $default_path){

	  if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {

	  	   	if($template_name == 'single-product/product-image.php'){
			   $template = AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/single-product/product-image.php';
			}

            if($template_name == 'single-product/tabs/tabs.php'){
				$template = AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/single-product/tabs/tabs.php';
			}

	  		if($template_name == 'cart/cart.php'){
			    $template = AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/cart/cart.php';
			}

			if($template_name == 'loop/orderby.php'){
			    $template = AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/loop/orderby.php';
			}

		    if($template_name == 'notices/error.php'){
		        $template = AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/notices/error.php';
		    }
		    if($template_name == 'single-product/add-to-cart/variable.php'){
 				$template = AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/single-product/add-to-cart/variable.php';
		    }
		    if($template_name == 'single-product/add-to-cart/variation-add-to-cart-button.php'){
 				$template = AMP_ENHANCER_TEMPLATE_DIR.'woocommerce/single-product/add-to-cart/variation-add-to-cart-button.php';
		    }
			    
		}
		
	 return $template;

 }

function amp_enhancer_product_details_json($retunType=""){  
        if(!function_exists('WC')){
        	return;
        }
        global $woocommerce;
        global $product;
        $wcComments = new WC_Comments;
		$images 		= array();
		$image_gallery  = array();
		
	/*	print_r($product);die;*/
		$product_id_some = $woocommerce->product_factory->get_product();
		$addtoCartText = $product_id_some->add_to_cart_text();
		if ($product_id_some->get_type()=='variable' ) {
			$addtoCartText = 'Add to cart';
		}
		if ($product_id_some->get_type()=='simple' ) {
			$addtoCartText = 'Add to cart';
		}
		
		$product_details = array(
							'id'=>$product_id_some->get_id(),
							'name'=>$product_id_some->get_name(),
							'parmalink'=>$product_id_some->get_permalink(),
							'is_on_sale'=>$product_id_some->is_on_sale(),
							'slug'=>$product_id_some->get_slug(),
							'status'=>$product_id_some->get_status(),
							'featured'=>$product_id_some->get_featured(),
							'catalog_visibility'=>$product_id_some->get_catalog_visibility(),
							'description'=>$product_id_some->get_description(),
							'sku'=>$product_id_some->get_sku(),
							'currency'=> get_woocommerce_currency_symbol() ,
							'price'=> wc_get_price_to_display($product_id_some, array( 'price' => $product_id_some->get_price() )), //$product_id_some->get_price() ,
							'regular_price'=>$product_id_some->get_price(),
							'sale_price'=>$product_id_some->get_price(),
							'date_on_sale_from'=>$product_id_some->get_date_on_sale_from(),
							'date_on_sale_to'=>$product_id_some->get_date_on_sale_to(),
							'total_sales'=>$product_id_some->get_total_sales(),
							'manage_stock'=>$product_id_some->get_manage_stock(),
							'stock_quantity'=>$product_id_some->get_stock_quantity(),
							'stock_status'=>$product_id_some->get_stock_status(),
							'backorders'=>$product_id_some->get_backorders(),
							'weight'=>$product_id_some->get_weight(),
							'length'=>$product_id_some->get_length(),
							'width'=>$product_id_some->get_width(),
							'height'=>$product_id_some->get_height(),
							'product_type'=>$product_id_some->get_type(),
							'rating'=>$product_id_some->get_average_rating(),
							'categories'=> wc_get_product_category_list($product_id_some->get_id()),
							'currency_sym'=> html_entity_decode(get_woocommerce_currency_symbol()),
							//$product_id_some->get_categories(),
							'add_cart_text'=> $addtoCartText,
							'add_cart_url'=>$product_id_some->add_to_cart_url(),
							'cart_url'=>$cart_url = user_trailingslashit(trailingslashit(wc_get_cart_url())),
							);
		if(substr($product_details['cart_url'] , -1) !== '/'){
		 	$product_details['cart_url'] = $product_details['cart_url'].'/';
	    }
		$product_identy          = $woocommerce->product_factory->get_product()->get_id();
		$add_to_cart_text_new    = $woocommerce->product_factory->get_product()->add_to_cart_text();
		$get_available_variations = array();

	    $details = array(
		        'product'=>$product_details,
		        'id'=>$product_identy,
		        'variant_attributes'=> $get_available_variations,
		   		'selectedImage'=>0,
		   		'selectedqty'=>1,
		   		'minqty'=>1,
		   		'maxqty'=>$product_id_some->get_stock_quantity()
		   		);

        $details = apply_filters('amp_enhancer_wc_json_generator',$details);
		if($retunType=="array"){
			$json_detail =  $details;			
		}else{
			unset($details['product']['description']);
			unset($details['product']['categories']);
			$json_detail =   json_encode($details );  //
		}
	return $json_detail;
}
