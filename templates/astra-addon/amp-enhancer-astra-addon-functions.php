<?php

function amp_enhancer_astra_back_to_top(){

  ?>
  <div id="enbacktotop"></div>
    <div id="marker">
        <amp-position-observer on="enter:enHideAnim.start; exit:enShowAnim.start"
          layout="nodisplay">
        </amp-position-observer>
      </div>

  <?php
}

function amp_enhancer_astra_back_to_top_link(){

 $scroll_top_alignment = astra_get_option( 'scroll-to-top-icon-position' );
 $scroll_top_devices   = astra_get_option( 'scroll-to-top-on-devices' );
?>
<a id="ast-scroll-top" class="<?php echo esc_attr( apply_filters( 'astra_scroll_top_icon', 'ast-scroll-top-icon' ) ); ?> ast-scroll-to-top-<?php echo esc_attr( $scroll_top_alignment ); ?>" data-on-devices="<?php echo esc_attr( $scroll_top_devices ); ?>" on="tap:enbacktotop.scrollTo(duration=500)" >
  <span class="screen-reader-text"><?php esc_html_e( 'Scroll to Top', 'astra-addon' ); ?></span>
</a>

   <amp-animation id="enShowAnim"
      layout="nodisplay">
      <script type="application/json">
        {
          "duration": "400ms",
          "fill": "both",
          "iterations": "1",
          "direction": "alternate",
          "animations": [{
            "selector": "#ast-scroll-top",
            "keyframes": [{
              "opacity": "1",
              "visibility": "visible"
            }]
          }]
        }
      </script>
    </amp-animation>
    <amp-animation id="enHideAnim"
      layout="nodisplay">
      <script type="application/json">
        {
          "duration": "400ms",
          "fill": "both",
          "iterations": "1",
          "direction": "alternate",
          "animations": [{
            "selector": "#ast-scroll-top",
            "keyframes": [{
              "opacity": "0",
              "visibility": "hidden"
            }]
          }]
        }
      </script>
    </amp-animation>
  <?php
}


function amp_enhancer_astra_sticky_header_css(){

   $sticky_devices = astra_get_option('sticky-header-on-devices');
   // both //mobile //desktop
	  if(isset($sticky_devices) && $sticky_devices == 'mobile'){
	    $sticky_css = '@media(max-width: 768px){.main-header-bar {position: fixed;width: 100%;} }';
	  }elseif(isset($sticky_devices) && $sticky_devices == 'desktop'){
	    $sticky_css = '@media(min-width: 768px){.main-header-bar {position: fixed;width: 100%;}}';
	  }else{
	  	$sticky_css = '.main-header-bar {position: fixed;width: 100%;}';
	  }
return $sticky_css;
}