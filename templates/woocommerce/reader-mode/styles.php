<?php
add_action('wp','amp_enhancer_reader_mode_styles');

function amp_enhancer_reader_mode_styles(){
	
	add_action('amp_post_template_css','amp_enhancer_reader_mode_custom_css');
	add_action('amp_post_template_css', 'amp_enhancer_wc_plugin_css',11);
}


function amp_enhancer_reader_mode_custom_css(){

	if(function_exists('is_product') && is_product()){
     ?>

	     .woocommerce-error li:first-child {
	    	display: none;
	     }

		.carousel-inner {
		    position: relative;
		    overflow: hidden;
		    width: 100%;
		}

		.carousel-open:checked + .carousel-item {
		    position: static;
		    opacity: 100;
		}

		.carousel-item{
		    position: absolute;
		    opacity: 0;
		    -webkit-transition: opacity 0.6s ease-out;
		    transition: opacity 0.6s ease-out;
		}

		.tab-hide-item {
		    display: none;
		    opacity: 0;
		    -webkit-transition: opacity 0.6s ease-out;
		    transition: opacity 0.6s ease-out;
		}

		.tab-open:checked + .tab-hide-item {
		    display: block;
		    opacity: 100;
		    transition: opacity 0.6s ease-out;
		}

		.woocommerce-tabs .panel{
		    position: relative;
		}

		.carousel-item img {
		    display: block;
		    height: auto;
		    max-width: 100%;
		}

		html .single-product .product .woocommerce-product-gallery .flex-control-thumbs{
			margin-top: 30px;  
		}
		.storefront-product-pagination,.hide{
			display:none;
		}
		.carousel-indicators {
		    list-style: none;
		    padding: 0;
		    margin-top: 30px;    
		    bottom: 2%;
		    left: 0;
		    right: 0;
		    text-align: center;
		    z-index: 10;
		}

		.carousel-bullet,.tab-bullet {
		    cursor: pointer;
		    display: inline;
		}

		.carousel-bullet:hover {
		    color: #aaaaaa;
		}

		body .woocommerce-product-gallery{
		opacity: 100;
		}
		.woocommerce-message {
		    margin-top: 4em;
		}
		.woocommerce div.product form.cart .woocommerce-message .button{
		float: right;
		}
		.single-product .woocommerce-message{
		    margin-top: 65px;		    
	     }
	     @media(min-width: 500px){
			.single-product .woocommerce-message{
					padding: 14px;
	 			}
	     }
		:root:not(#_):not(#_):not(#_):not(#_):not(#_) .woocommerce-message::before{
		content:unset;
		}
	<?php
    }
    if(function_exists('is_cart') && is_cart()){
    ?>
     :root:not(#_):not(#_):not(#_):not(#_):not(#_)  .woocommerce table.cart amp-img {
    	height: 75px;
    	width: 75px
     }
    <?php }

}

function amp_enhancer_wc_plugin_css(){
    $srcs = array();

   if( (function_exists('is_product') && is_product() ) || (function_exists('is_cart') && is_cart()) ){

    $srcs[] = plugins_url( 'assets/css/woocommerce-layout.css',WC_PLUGIN_FILE );
    $srcs[] = plugins_url( 'assets/css/woocommerce.css',WC_PLUGIN_FILE );
    $srcs[] = plugins_url( 'packages/woocommerce-blocks/build/style.css', WC_PLUGIN_FILE );
    $srcs[] = plugins_url( 'assets/css/photoswipe/photoswipe.css', WC_PLUGIN_FILE );
    $srcs[] = plugins_url( 'assets/css/photoswipe/default-skin/default-skin.css', WC_PLUGIN_FILE );

    foreach ($srcs as $key => $urlValue) {
        $cssData = amp_enhancer_remote_content($urlValue);
        $cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
        $cssData = str_replace('img', 'amp-img', $cssData);
              echo $cssData;
      }
   }

}

function amp_enhancer_remote_content($src){
    if($src){
      $arg = array( "sslverify" => false, "timeout" => 60 ) ;
      $response = wp_remote_get( $src, $arg );
          if ( wp_remote_retrieve_response_code($response) == 200 && is_array( $response ) ) {
            $header = wp_remote_retrieve_headers($response); // array of http header lines
            $contentData =  wp_remote_retrieve_body($response); // use the content
            return $contentData;
          }else{
        $contentData = file_get_contents( $src );
        if(! $contentData ){
          $data = str_replace(get_site_url(), '', $src);//content_url()
          $data = getcwd().$data;
          if(file_exists($data)){
            $contentData = file_get_contents($data);
          }
        }
        return $contentData;
      }

    }
      return '';
}