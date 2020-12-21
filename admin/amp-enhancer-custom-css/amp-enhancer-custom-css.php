<?php
function  amp_enhancer_custom_css(){
  $custom_css = get_option('ampenhancer_custom_css');
  $css = (isset($custom_css['css']) && !empty($custom_css['css'])) ? $custom_css['css'] : '/* Enter Your Custom CSS Here */'; 
  ?>
  <div class="enhc-css-container">
   <form action="options.php" method="post" enctype="multipart/form-data" class="amp-en-settings-form">
  <h1>AMP Custom CSS</h1>
             <?php 
               // Output nonce, action, and option_page fields for a settings page.
                settings_fields( 'amp_enhancer_custom_css_group' );  
                ?>
    <textarea id="amp_enhancer_custom_css" name="ampenhancer_custom_css[css]" class="amp_enhancer_custom_css" style="border: 1px solid #DFDFDF; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; width: 100%; height: 200px; position: relative;background-color: #272822;color: #F8F8F2;" ><?php echo $css; ?></textarea> 
    <?php
     // Output save settings button
          submit_button( esc_html__('Save Settings', 'amp-enhancer') );
          ?>
          </form>
  </div>
   <?php
  }


function amp_enhancer_register_codemirror_css_lib( $hook ) {

    wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
    wp_enqueue_script( 'amp-enhancer-css-editor-js',AMP_ENHANCER_PLUGIN_URI.'admin/js/editor_css.js', array( 'jquery' ), AMP_ENHANCER_VERSION, true );
  
}
add_action( 'admin_enqueue_scripts', 'amp_enhancer_register_codemirror_css_lib' );