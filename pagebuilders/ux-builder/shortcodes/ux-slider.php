<?php

function amp_enhancer_shortcode_ux_slider($atts, $content=null) {

    extract( shortcode_atts( array(
        '_id' => 'slider-'.rand(),
        'timer' => '6000',
        'bullets' => 'true',
        'visibility' => '',
        'class' => '',
        'type' => 'slide',
        'bullet_style' => '',
        'auto_slide' => 'true',
        'auto_height' => 'true',
        'bg_color' => '',
        'slide_align' => 'center',
        'style' => 'normal',
        'slide_width' => '',
        'arrows' => 'true',
        'pause_hover' => 'true',
        'hide_nav' => '',
        'nav_style' => 'circle',
        'nav_color' => 'light',
        'nav_size' => 'large',
        'nav_pos' => '',
        'infinitive' => 'true',
        'freescroll' => 'false',
        'parallax' => '0',
        'margin' => '',
        'columns' => '1',
        'height' => '',
        'rtl' => 'false',
        'draggable' => 'true',
        'friction' => '0.6',
        'selectedattraction' => '0.1',
        'threshold' => '10',

        // Derpicated
        'mobile' => 'true',

    ), $atts ) );

    // Stop if visibility is hidden
    if($visibility == 'hidden') return;
    if($mobile !==  'true' && !$visibility) {$visibility = 'hide-for-small';}

    ob_start();

    $wrapper_classes = array('slider-wrapper', 'relative');
    if( $class ) $wrapper_classes[] = $class;
    if( $visibility ) $wrapper_classes[] = $visibility;
    $wrapper_classes = implode(" ", $wrapper_classes);

    $classes = array('slider');

    if ($type == 'fade') $classes[] = 'slider-type-'.$type;

    // Bullet style
    if($bullet_style) $classes[] = 'slider-nav-dots-'.$bullet_style;

    // Nav style
    if($nav_style) $classes[] = 'slider-nav-'.$nav_style;

    // Nav size
    if($nav_size) $classes[] = 'slider-nav-'.$nav_size;

    // Nav Color
    if($nav_color) $classes[] = 'slider-nav-'.$nav_color;

    // Nav Position
    if($nav_pos) $classes[] = 'slider-nav-'.$nav_pos;

    // Add timer
    if($auto_slide == 'true') $auto_slide = $timer;

    // Add Slider style
    if($style) $classes[] = 'slider-style-'.$style;

    // Always show Nav if set
    if($hide_nav ==  'true') {$classes[] = 'slider-show-nav';}

    // Slider Nav visebility
    $is_arrows = 'true';
    $is_bullets = 'true';

    if($arrows == 'false') $is_arrows = 'false';
    if($bullets == 'false') $is_bullets = 'false';

    if(is_rtl()) $rtl = 'true';

    $classes = implode(" ", $classes);

    // Inline CSS
    $css_args = array(
        'bg_color' => array(
          'attribute' => 'background-color',
          'value' => $bg_color,
        ),
        'margin' => array(
          'attribute' => 'margin-bottom',
          'value' => $margin,
        )
    );
?>
<div class="amp-slide <?php echo $wrapper_classes; ?>" id="<?php echo $_id; ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
    <div class="<?php echo $classes; ?>"
        data-flickity-options='{
            "cellAlign": "<?php echo $slide_align; ?>",
            "imagesLoaded": true,
            "lazyLoad": 1,
            "freeScroll": <?php echo $freescroll; ?>,
            "wrapAround": <?php echo $infinitive; ?>,
            "autoPlay": <?php echo $auto_slide;?>,
            "pauseAutoPlayOnHover" : <?php echo $pause_hover; ?>,
            "prevNextButtons": <?php echo $is_arrows; ?>,
            "contain" : true,
            "adaptiveHeight" : <?php echo $auto_height;?>,
            "dragThreshold" : <?php echo $threshold ;?>,
            "percentPosition": true,
            "pageDots": <?php echo $is_bullets; ?>,
            "rightToLeft": <?php echo $rtl; ?>,
            "draggable": <?php echo $draggable; ?>,
            "selectedAttraction": <?php echo $selectedattraction; ?>,
            "parallax" : <?php echo $parallax; ?>,
            "friction": <?php echo $friction; ?>
        }'
        >
            <div class="flickity-viewport" style="height: 580.406px; touch-action: pan-y;">
                <div class="flickity-slider" style="left: 0px; transform: translateX(0%);">

                  <?php 
                   $slider_content = flatsome_contentfix($content);
                  preg_match_all('/<div class="(.*?)ux-amp-cont"(.*?)>/', $slider_content, $matches);

                  if(isset($matches[0])){
                    foreach ($matches[0] as $key => $value) {
                       $perc = $key*100;
                       $custom_style = '  data-slide="'.$key.'"  style="position:absolute;left:'.$perc.'%;">';
                       $style_modfy = 'style="position:absolute;left:'.$perc.'%;';
                       $active_class = 'class="active-slider ';
                       $attr_mdfy = str_replace('>', $custom_style, $value);
                       $attr_mdfy = str_replace('style="', $style_modfy, $attr_mdfy);
                      if($key == 0){
                    	  $attr_mdfy = str_replace('class="', $active_class, $attr_mdfy);
                	  }
                     $slider_content = str_replace($value, $attr_mdfy, $slider_content);
                    }

                  }

                  ?>
        <?php echo $slider_content; ?>
                </div>
            </div>
<button class="flickity-button flickity-prev-next-button previous" type="button" aria-label="Previous"><svg class="flickity-button-icon" viewBox="0 0 100 100"><path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow"></path></svg></button>
<button class="flickity-button flickity-prev-next-button next" type="button" aria-label="Next"><svg class="flickity-button-icon" viewBox="0 0 100 100"><path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow" transform="translate(100, 100) rotate(180) "></path></svg></button>
     </div>

     <!-- <div class="loading-spin dark large centered"></div> -->

     <?php if($slide_width) { ?>
     <style>
            #<?php echo $_id; ?> .flickity-slider > *{ max-width: <?php echo $slide_width; ?>!important;
     </style>
     <?php } ?>
</div>

<?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode("ux_slider", "amp_enhancer_shortcode_ux_slider");