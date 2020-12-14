<?php

// Spoiler Shortcode
 function amp_enhancer_shortcode_spoiler( $atts = null, $content = null ) {
	$atts           = shortcode_atts(
		array(
			'title'         => __( 'Spoiler title', 'shortcodes-ultimate' ),
			'open'          => 'no',
			'style'         => 'default',
			'icon'          => 'plus',
			'anchor'        => '',
			'anchor_in_url' => 'no',
			'scroll_offset' => 0,
			'class'         => '',
		),
		$atts,
		'spoiler'
	);
	$atts['style']  = str_replace( array( '1', '2' ), array( 'default', 'fancy' ), $atts['style'] );
	$atts['anchor'] = ( $atts['anchor'] ) ? ' data-anchor="' . str_replace( array( ' ', '#' ), '', sanitize_text_field( $atts['anchor'] ) ) . '"' : '';
	if ( 'yes' !== $atts['open'] ) {
		$atts['class'] .= ' su-spoiler-closed';
	}
	su_query_asset( 'css', 'su-icons' );
	su_query_asset( 'css', 'su-shortcodes' );
	do_action( 'su/shortcode/spoiler', $atts );

	return '<amp-accordion animate>
	        <section class="su-spoiler su-spoiler-style-' . esc_attr($atts['style']) . ' su-spoiler-icon-' . $atts['icon'] . esc_attr(su_get_css_class( $atts )) . '"' . esc_attr($atts['anchor']) . '>
			    <header class="su-spoiler-title" id="amp-spoiler-title" style=" background-color: unset;border:unset;">
			    <span class="su-spoiler-icon"></span>
			     		' . esc_html(su_do_attribute( $atts['title'] )) . '
			     </header>
			      <div class="su-clearfix">
			     		 ' . esc_html(su_do_nested_shortcodes( $content, 'spoiler' )) . '
			      </div>
			</section>
		   </amp-accordion>';
}

// Accordion Shortcode
function amp_enhancer_shortcode_accordion( $atts = null, $content = null ) {

	$atts = shortcode_atts( array( 'class' => '' ), $atts, 'accordion' );
	do_action( 'su/shortcode/accordion', $atts );

	$accordionHtml = do_shortcode( $content );
	$string =  preg_replace('/<amp-accordion[^>]*>/i', '', $accordionHtml);
	$ampHtml = preg_replace('/<\/amp-accordion>/i', '', $string);
	return '<amp-accordion expand-single-section animate class="su-accordion su-u-trim' . esc_attr(su_get_css_class( $atts )) . '">' . $ampHtml . '</amp-accordion>';
}

add_action( 'wp', 'amp_enhancer_su_shortcode_override' );

function amp_enhancer_su_shortcode_override() {
	if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
		
		if ( class_exists('Shortcodes_Ultimate_Shortcodes')) {

			$prefix = su_get_shortcode_prefix();
			$shortcodes = su_get_all_shortcodes();

				foreach ( $shortcodes as $id => $shortcode ) {
					if ( isset( $shortcode['callback'] ) && is_callable( $shortcode['callback'] ) ) {
						$callback = $shortcode['callback'];
					}elseif ( isset( $shortcode['function'] ) && is_callable( $shortcode['function'] ) ) {
						$callback = $shortcode['function'];
					}else {
						continue;
					}

					su_remove_shortcode( $id );
					switch ($id) {
						case 'spoiler':
							$callback = 'amp_enhancer_shortcode_spoiler';
						break;
						case 'accordion':
							$callback = 'amp_enhancer_shortcode_accordion';
						break;
						default:
							$callback = $callback;
						break;
					}
					add_shortcode( $prefix . $id, $callback );
				 }

		}
	}
}
	