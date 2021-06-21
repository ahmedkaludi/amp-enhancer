<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>

<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="transition: opacity .25s ease-in-out;">
	<figure class="woocommerce-product-gallery__wrapper carousel-inner">
			<?php
			if ( $product->get_image_id() ) {
				$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
				$html .= '</div>';
			}
	 

		if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
			return;
		}

		$gallery_ids = $product->get_gallery_image_ids();

		if(empty($gallery_ids)){
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		$product_id = array($product->get_image_id());
		$attachment_ids = array_merge($product_id,$gallery_ids);

		if ( !empty($gallery_ids) && $attachment_ids && $product->get_image_id() ) {

			$i=1;
		    $ol_elements = '';

			foreach ( $attachment_ids as $attachment_id ) {
			     $full_src  = wp_get_attachment_image_src( $attachment_id, 'full' );
			     $thumb_src  = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

				 $checked = '';	
				 if($i == 1){
				    $checked = 'checked="checked"';
				 }
				 	$main_image = false;
					$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
					$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
					$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
					$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
					$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
					$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
					$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
					$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
					$image             = wp_get_attachment_image(
						$attachment_id,
						$image_size,
						false,
						apply_filters(
							'woocommerce_gallery_image_html_attachment_image_params',
							array(
								'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
								'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
								'data-src'                => esc_url( $full_src[0] ),
								'data-large_image'        => esc_url( $full_src[0] ),
								'data-large_image_width'  => esc_attr( $full_src[1] ),
								'data-large_image_height' => esc_attr( $full_src[2] ),
								'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
								'lightbox'  =>'',
							),
							$attachment_id,
							$image_size,
							$main_image
						)
					);
				 ?>
			       <input class="carousel-open" type="radio" id="carousel-<?php echo esc_attr($i); ?>" name="carousel" aria-hidden="true" hidden="" <?php echo esc_attr($checked); ?> >
					<div class="carousel-item ">

						<?php  echo $image;  ?>

					</div>
					
					
				<?php
					$ol_elements .= '
						<li>
						<label for="carousel-'.esc_attr($i).'" class="carousel-bullet"><img src="'.esc_url($thumb_src[0]).'" alt=""></label>
						</li>
						';
			    $i++;
			}

			if(!empty($gallery_ids)){
				echo '<ol class="flex-control-nav flex-control-thumbs carousel-indicators">'.$ol_elements.'</ol>';
		    }
		}
			//do_action( 'woocommerce_product_thumbnails' );
			?>
	</figure>
</div>