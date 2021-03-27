<?php
function amp_enhancer_ux_builder_amp_script_opening(){

  $script_url = str_replace('http:','https:',AMP_ENHANCER_PAGEBUILDER_URI).'ux-builder/amp-scripts/amp-enhancer-ux-builder-func.js';
  ?>
      <amp-script src="<?php echo esc_url_raw($script_url);?>">

 <?php
}

function amp_enhancer_ux_builder_amp_script_closing(){?>

 </amp-script>
<?php }

  function amp_enhancer_ux_builder_initialize(){

     add_action('flatsome_before_page','amp_enhancer_ux_builder_amp_script_opening');

     add_action('flatsome_after_page','amp_enhancer_ux_builder_amp_script_closing');

	 remove_shortcode("ux_slider", "shortcode_ux_slider");
	 remove_shortcode('ux_banner', 'flatsome_ux_banner');
	 remove_shortcode('logo', 'ux_logo');
	 remove_shortcode( 'ux_image', 'ux_image' );

	 require_once AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/ux-builder/shortcodes/ux-slider.php';
	 require_once AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/ux-builder/shortcodes/ux-banner.php';
	 require_once AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/ux-builder/shortcodes/ux-image.php';
	 require_once AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/ux-builder/shortcodes/ux-logo.php';

  }
