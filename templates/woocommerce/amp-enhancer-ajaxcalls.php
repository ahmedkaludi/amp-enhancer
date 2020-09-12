<?php 

add_action('wp_ajax_amp_enhancer_cart_coupon_operation','amp_enhancer_cart_coupon_operation');
add_action('wp_ajax_nopriv_amp_enhancer_cart_coupon_operation','amp_enhancer_cart_coupon_operation');

function amp_enhancer_cart_coupon_operation(){

	    header("access-control-allow-credentials:true");
	    header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
	    $siteUrl = parse_url(get_site_url());
	    header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
	    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
	    header("Content-Type:application/json");

		if(!wp_verify_nonce(sanitize_key($_POST['amp_enhance_cart_wpnonce']),'amp_enhance_cart_wpnonce') ){

			header('HTTP/1.1 500 FORBIDDEN');
		    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
		    header("Content-Type:application/json");
			echo wp_json_encode( 'Security-check.' );
	    	wp_die();

		} else {

				$siteUrl = parse_url(
						get_site_url()
					);
			 	$Site_url = esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']);
				header('AMP-Access-Control-Allow-Source-Origin: '.esc_url($Site_url));
				$url =  trailingslashit(wc_get_cart_url());
				  $getOption = get_option('amp-options');

		         if ($getOption['theme_support'] == 'transitional'){
				    $url = $url.'?amp';
			      }

				$url = str_replace('http:', 'https:', $url);
				
                // Update Cart Code Starts here..
				if(isset($_POST['amp_update_cart'])){

							wc_nocache_headers();
						$nonce_value = wc_get_var( $_REQUEST['woocommerce-cart-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

						// Update Cart - checks apply_coupon too because they are in the same form.
						if ( ! empty( $_POST['amp_update_cart'] ) && wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) {

							$cart_updated = false;
							$cart_totals  = isset( $_POST['cart'] ) ? wp_unslash( $_POST['cart'] ) : ''; // PHPCS: input var ok, CSRF ok, sanitization ok.

							if ( ! WC()->cart->is_empty() && is_array( $cart_totals ) ) {
								foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {

									$_product = $values['data'];

									// Skip product if no updated quantity was posted.
									if ( ! isset( $cart_totals[ $cart_item_key ] ) || ! isset( $cart_totals[ $cart_item_key ]['qty'] ) ) {
										continue;
									}

									// Sanitize.
									$quantity = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( '/[^0-9\.]/', '', $cart_totals[ $cart_item_key ]['qty'] ) ), $cart_item_key );

									if ( '' === $quantity || $quantity === $values['quantity'] ) {
										continue;
									}

									// Update cart validation.
									$passed_validation = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $values, $quantity );

									// is_sold_individually.
									if ( $_product->is_sold_individually() && $quantity > 1 ) {
										/* Translators: %s Product title. */
										wc_add_notice( sprintf( __( 'You can only have 1 %s in your cart.', 'woocommerce' ), $_product->get_name() ), 'error' );
										$passed_validation = false;
									}

									if ( $passed_validation ) {
										WC()->cart->set_quantity( $cart_item_key, $quantity, false );
										$cart_updated = true;
									}
								}
							}
						}

							// Trigger action - let 3rd parties update the cart if they need to and update the $cart_updated variable.
							$cart_updated = apply_filters( 'woocommerce_update_cart_action_cart_updated', $cart_updated );

							if ( $cart_updated ) {
								WC()->cart->calculate_totals();
							}

						header("AMP-Redirect-To: ".esc_url_raw($url));
						header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
						echo json_encode(array('successmsg'=>'Cart Updated'));
						exit;
			    }// Update Cart Code Ends here..
                 
                // Coupon Code Method Starts here.. 
				switch ($_POST['coupon_method']) {

					case 'apply_coupon':
						$coupon_code = esc_attr($_POST['coupon_code']);
						if(empty($coupon_code)){
							header('HTTP/1.1 500 FORBIDDEN');
							header("Content-Type:application/json");
							echo wp_json_encode( 'Empty coupon code applied' );
							exit;
						}
						WC()->cart->apply_coupon( sanitize_text_field( esc_attr($_POST['coupon_code'] ) ) );
						break;

					case 'remove_coupon':
						WC()->cart->remove_coupon( wc_clean( esc_attr($_POST['remove_coupon'] ) ) );
						break;

					default:
						# code...
						break;
				}

		        WC()->cart->calculate_totals();
				header("AMP-Redirect-To: ".esc_url_raw($url));		
				header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin"); 
				echo json_encode(array('successmsg'=>'Cart Updated'));
				exit;
	}
}