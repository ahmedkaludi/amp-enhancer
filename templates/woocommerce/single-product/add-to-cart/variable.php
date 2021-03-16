<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

global $product;

$default_varprice = '';
$default_variation = $product->get_default_attributes();
$show_class = "none";
$variation_id = 0;
add_option('amp_enhancer_wc_variation_id',$variation_id);

  if(!empty($default_variation)){

      $dflt_var_arr = array();
      foreach ($default_variation as $new_key => $def_value) {
        # code...
        $dflt_var_arr['attribute_' .$new_key] = $def_value;
      }


          $variable_product = wc_get_product( absint( $product->get_id() ) );
          $data_store   = WC_Data_Store::load( 'product' );
          $variation_id = $data_store->find_matching_product_variation( $variable_product, $dflt_var_arr );
          $variation    = $variation_id ? $variable_product->get_available_variation( $variation_id ) : false;
         update_option('amp_enhancer_wc_variation_id',$variation_id);

     $default_varprice = $variation['display_price'];
     $show_class = 'inline-block';
  }

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

$allStaticData = amp_enhancer_product_details_json('array');
$script_url = str_replace('http:','https:',AMP_ENHANCER_TEMPLATE_URI).'woocommerce/amp-scripts/amp_enhancer_variation_calc.js';

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<amp-script src="<?php echo esc_url_raw($script_url);?>">

	<form class="variations_form cart"  id="amp_variations" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" data-site-url="<?php echo get_site_url(); ?>" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
		<?php do_action( 'woocommerce_before_variations_form' ); ?>

		<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
			<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', esc_html__( 'This product is currently out of stock and unavailable.', 'amp-enhancer' ) ) ); ?></p>
		<?php else : ?>
			<table class="variations" cellspacing="0">
				<tbody>
					<?php foreach ( $attributes as $attribute_name => $options ) : ?>
						<tr>
							<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
							<td class="value">
								<?php
									wc_dropdown_variation_attribute_options(
										array(
											'options'   => $options,
											'attribute' => $attribute_name,
											'product'   => $product,
										)
									);
									echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			 <div id="var_display_price"  class="single_variation_wrap" style="display:<?php echo $show_class; ?> ">
		      <div class="var_show_price ">
		        <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo esc_html__($allStaticData['product']['currency_sym'],'amp-enhancer'); ?></span><span id="var_price" ><?php echo esc_html__($default_varprice,'amp-enhancer'); ?></span></span></span>
		        <div id="awc-in-stock" style="margin-top: 20px;"></div>
		      </div>
				<?php
					/**
					 * Hook: woocommerce_before_single_variation.
					 */
					do_action( 'woocommerce_before_single_variation' );

					/**
					 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
					 *
					 * @since 2.4.0
					 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
					 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
					 */
					do_action( 'woocommerce_single_variation' );

					/**
					 * Hook: woocommerce_after_single_variation.
					 */
					do_action( 'woocommerce_after_single_variation' );
				?>
			</div>
			<span id="error_msg"> </span>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_variations_form' ); ?>
	</form>
</amp-script>
<?php
do_action( 'woocommerce_after_add_to_cart_form' );
