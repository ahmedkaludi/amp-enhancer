<?php 
//Back End
  $settings = $settings = get_option( 'ampenhancer_settings');
  if(is_admin() && isset($settings['popup']) && ($settings['popup'] == 'on' || $settings['popup'] == 1)){
   add_action( 'init', 'amp_enhancer_pop_up_create_post_type' );
     //removing default wysiwig editor
   add_action( 'admin_init', 'ampenhancerpopup_removing_default_wysiwig' );

   add_action( 'add_meta_boxes', 'ampenhancerpopup_create_meta_box_select' );

 }
  function amp_enhancer_pop_up_create_post_type() {

    register_post_type( 'ampenhancerpopup',
      array(
            'labels' => array(
            'name'              => esc_html__( 'Popups (AMP)', 'amp-enhancer' ),
            'singular_name'     => esc_html__( 'Popup (AMP)', 'amp-enhancer' ),
            'add_new'           => esc_html__( 'Add New', 'amp-enhancer' ),
            'add_new_item'      => esc_html__( 'Add New', 'amp-enhancer' ),
            'edit_item'         => esc_html__( 'Edit Popup','amp-enhancer')
        ),
          'public'                => true,
          'has_archive'           => false,
          'exclude_from_search'   => true,
          'publicly_queryable'    => false,
          'supports'              => array('title','editor'),
          'show_in_menu'          => false
      )
    );
  }

  function ampenhancerpopup_removing_default_wysiwig() {
    remove_post_type_support( 'ampenhancerpopup', 'editor');   
   
  }

  function ampenhancerpopup_create_meta_box_select(){
  // Repeater Comparison Field 
 
  add_meta_box( 'ampenhancerpopup_content', esc_html__( 'Content','amp-enhancer' ), 'ampenhancerpopup_content_callback', 'ampenhancerpopup','normal', 'high' );

  add_meta_box( 'ampenhancerpopup_advance', esc_html__( 'Advance Popup settings','amp-enhancer' ), 'amp_enhancer_pop_up_advance_settings_callback', 'ampenhancerpopup','normal', 'low' );

  }

   function ampenhancerpopup_content_callback($post) {
    global $wpdb,$post;
    $post_id = $post->ID;
    $editor_type = get_post_meta( $post_id, 'en_popup_type', true);
    $en_popup_content = get_post_meta( $post_id, 'en_popup_content', true);
    $display_cond = get_post_meta( $post_id, 'en_popup_display', true);
    if(empty($editor_type)){
      $editor_type = 'visual_editor';
    }
 
    ?>
     <style type="text/css">
        .option-table-class{width:100%;}
         .option-table-class tr td {padding: 10px 10px 10px 10px ;}
         .option-table-class tr > td{width: 30%;}
         .option-table-class tr td:last-child{width: 60%;}
         .option-table-class input[type="text"], select,textarea{width:100%;}
         .show{display: table-row;}.hide{display: none;}
         .switch {position: relative;display: inline-block;width: 56px;height: 26px;}
         .switch input {display:none;}.popup-switch .slider {width: 100%;}
         .slider {position: absolute;cursor: pointer;top: 0;left: 0;right: 0;bottom: 0;
         background-color: #ccc;-webkit-transition: .4s;transition: .4s;}
         .slider:before {position: absolute;content: "";height: 19px;width: 20px;left: 4px;bottom: 4px;background-color: white;-webkit-transition: .4s;transition: .4s;}
         input:checked + .slider {background-color: green;}
         input:checked + .slider:before {-webkit-transform: translateX(26px);-ms-transform: translateX(26px);transform: translateX(26px);}
          .slider.round {border-radius: 34px;}
         .slider.round:before {border-radius: 50%;}
     </style>
      <table class="option-table-class">
      <tbody>
        <tr>
          <td>
            <label for="editor_type"><?php echo esc_html__( 'Editor Type', 'amp-enhancer' ); ?></label>  
          </td>
            <td>
              <select onchange="amp_enhancer_editor_js(this);"  id="editor_type" name="editor_type">
              <?php
                $all_action_array = array(
                   'visual_editor' => 'Visual Editor',
                   'shortcode' => 'Shortcode',
                   'custom_amp_editor' => 'Custom HTML (AMP)',
              );
               foreach ($all_action_array as $key => $value) {
                 $sel = '';
                  if($editor_type==$key){
                   $sel = 'selected';
                  }
                  echo "<option value='".esc_attr($key)."' ".esc_attr($sel).">".esc_html__($value, 'amp-enhancer' )."</option>";
               }
              ?>
             </select>
            </td>
           </tr>
           <?php 
             $visual_edit_class = ($editor_type == 'visual_editor') ? 'show' : 'hide'; 
             $visual_content =  ($editor_type == 'visual_editor') ? $en_popup_content : '';
             ?>
           <tr id="tr_visual_editor" class="en-popup-editor <?php esc_attr_e($visual_edit_class); ?>">
          <td> 
            <label for="content_wysiwig_class_name"><?php echo esc_html__( 'Pop up Content', 'amp-enhancer' ); ?></label> 
          </td>
          <td>  
            <?php amp_enhancer_pop_up_wysiwig_content($visual_content); ?>
          </td>
        </tr> 
        <?php
           
            $shortcode_class = ($editor_type == 'shortcode') ? 'show' : 'hide'; 
            $shortcode_content =  ($editor_type == 'shortcode') ? $en_popup_content : ''; 
           ?>
        <tr id="tr_shortcode" class="en-popup-editor <?php esc_attr_e($shortcode_class); ?>">
            <td> 
               <label for="shortcode_content"><?php echo esc_html__( 'Enter Shortcode', 'amp-enhancer' ); ?></label> 
            </td>
            <td> 
              <textarea id="shortcode_content" name="shortcode_content" rows="6" placeholder="You can place the shortcodes here"><?php echo $shortcode_content; ?></textarea>
            </td> 
         </tr>
          <?php
            $amp_editor = ($editor_type == 'custom_amp_editor') ? 'show' : 'hide';
            $amp_content =  ($editor_type == 'custom_amp_editor') ? $en_popup_content : '';
           ?>
          <tr id="tr_custom_amp_editor" class="en-popup-editor <?php esc_attr_e($amp_editor); ?>" >
            <td> 
               <label for="custom_amp_content"><?php echo esc_html__( 'Custom AMP HTML', 'amp-enhancer' ); ?></label> 
            </td>
            <td> 
              <textarea id="custom_amp_content" name="custom_amp_editor_content" rows="6" placeholder="Enter AMP Supported HTML"><?php echo $amp_content; ?></textarea>
            </td> 
         </tr>
          <tr>
          <td>
            <label for="display_cond"><?php echo esc_html__( 'Display', 'amp-enhancer' ); ?></label>  
          </td>
            <td>
              <?php 
                $global_sel = $home_sel = '';
                if($display_cond == 'globally'){
                  $global_sel = 'selected';
                }
                if($display_cond == 'homepage'){
                  $home_sel = 'selected';
                }
                  
               ?>
              <select  id="display_cond" name="display_cond">
                <option value="globally" <?php echo esc_attr($global_sel);?> ><?php echo esc_html__('Globally', 'amp-enhancer' );?></option>
                <option value="homepage" <?php echo esc_attr($home_sel);?> ><?php echo esc_html__('Homepage', 'amp-enhancer' );?></option>
              </select>
            </td>
          </tr>
      </tbody>
    </table>
    <?php
     //security check
       wp_nonce_field( 'amp_enhancer_pop_up_editor_action_nonce', 'amp_enhancer_pop_up_editor_nonce' ); 
    ?>
     <script>
        function amp_enhancer_editor_js(value){

        var editor = document.getElementsByClassName("en-popup-editor");

        for (i=0;i<editor.length;i++) {
          editor[i].style.display = "none"
        }
        var selected_editor = "tr_"+value.value;
        if(selected_editor){
          document.getElementById(selected_editor).style.display = "table-row";
        }

    }
  </script>
    <?php

   }


function amp_enhancer_pop_up_wysiwig_content($wis){
  $content   = !empty($wis)?  $wis  : '';
  do_shortcode($content);
  $editor_id = 'visual_editor_content';
  wp_editor(stripslashes($content), $editor_id );
}


  function amp_enhancer_pop_up_advance_settings_callback($post) {
    global $post;
    $popup_cookie_duration = get_post_meta($post->ID, 'en_cookie_time', true);
    $popup_cookie_type  = get_post_meta($post->ID, 'en_popup_cookie_type', true);
    $popup_with_cookie     = get_post_meta($post->ID, 'en_popup_set_time', true); ?>
    <script>
      jQuery(document).ready(function($){
          $("#en_popup_set_time").click(function(){
            if($(this).is(':checked')){
              $("#en_set_cookie_time_text").parents('tr').show();
            }else{
                $("#en_set_cookie_time_text").parents('tr').hide();
                }   
             
          });
      });
      function popup_cookie_type_check(value){
        if(value.value == "en_popup_with_cookie"){
           document.getElementById("en_popup_with_cookie_wpr").style.display = "table-row-group";
        }else{
          document.getElementById("en_popup_with_cookie_wpr").style.display = "none";
        }  
      }
    </script>
    <table class="option-table-class">
      <tbody>
        <tr>
            <td><label for="en_popup_cookie_type" class="popup_cookie_type_label">When the Popup should display</label></td>
            <td>
              <select onchange="popup_cookie_type_check(this);" id="en_popup_cookie_type" name="en_popup_cookie_type">
                  <?php
                    $all_array = array(
                       'en_popup_with_cookie' => 'After a specific Time',
                       'en_popup_without_cookie' => 'EveryTime User Reloads',
                   );
                    foreach ($all_array as $key => $value) {
                      $sel = '';
                      if($popup_cookie_type==$key){
                        $sel = 'selected';
                      }
                      echo "<option value='".esc_attr($key)."' ".esc_attr($sel).">".esc_html__($value, 'amp-enhancer' )."</option>";
                    }
                  ?>
                </select>        
            </td>
        </tr>
        </tbody>
    </table>
    <table class="option-table-class popup-switch">
      <tbody id="en_popup_with_cookie_wpr" <?php if($popup_cookie_type == 'en_popup_without_cookie'){echo 'style="display:none";'; } ?>>
        <tr>
            <td>
              <label for="en_popup_set_time" class="button_checkbox_label">Set Time 
              </label>
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="en_popup_set_time" name="en_popup_set_time" value="1" <?php if(isset($popup_with_cookie ) && $popup_with_cookie ==1){echo 'checked'; } ?> >
                <span class="slider round"></span>
              </label>            
            </td>
        </tr>

            <tr id="en_popup_with_cookie_txtwpr" <?php if((!isset($popup_with_cookie) || $popup_with_cookie !=1) || $popup_cookie_type == 'popup_without_cookie'){echo 'style="display:none"'; }?> >
            <td>
              <label for="en_set_cookie_time_text"><?php echo esc_html__( 'Set the Time Interval in Hours, only in numerics ( eg: 12 )', 'amp-enhancer' ); ?></label>  
            </td>
            <td>
              <input type="text" id="en_set_cookie_time_text" name="en_cookie_time" value="<?php if( isset($popup_cookie_duration) ){echo esc_attr($popup_cookie_duration); } else{ echo '12'; } ?>">
            </td>
        </tr>
      </tbody>
  </table>
 <?php }


  // Save PHP Editor
add_action ( 'save_post' , 'amp_enhancer_pop_up_editor_save_data' );
function amp_enhancer_pop_up_editor_save_data ( $post_id ) {      
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
   
  // if our nonce isn't there, or we can't verify it, bail
  if( !isset( $_POST['amp_enhancer_pop_up_editor_nonce'] ) || !wp_verify_nonce( $_POST['amp_enhancer_pop_up_editor_nonce'], 'amp_enhancer_pop_up_editor_action_nonce' ) ) return;
  
    // if our current user can't edit this post, bail
  if( !current_user_can( 'edit_post' ) ) return; 
   $editor_type = isset($_POST['editor_type']) ? $_POST['editor_type'] : 'visual_editor';
   $content_name =  $editor_type.'_content';
   $pop_up_content         =  (isset($_POST[$content_name]) && !empty($_POST[$content_name]) ) ? $_POST[$content_name] : ''; 
   $display_cond = isset($_POST['display_cond']) ? $_POST['display_cond'] : 'globally';
    $post_popup_cookie_type =  sanitize_text_field($_POST['en_popup_cookie_type']);
    $post_popup_with_cookie =  sanitize_text_field($_POST['en_popup_set_time']);
    $popup_duration =  sanitize_text_field($_POST['en_cookie_time']);

    update_post_meta(
      $post_id, 
      'en_popup_type', 
      $editor_type 
    );
    update_post_meta(
      $post_id, 
      'en_popup_content', 
      $pop_up_content
    );
    update_post_meta(
      $post_id, 
      'en_popup_display', 
      $display_cond
    );
    update_post_meta(
      $post_id, 
      'en_popup_cookie_type', 
      $post_popup_cookie_type
    );
    update_post_meta(
      $post_id, 
      'en_popup_set_time', 
      $post_popup_with_cookie
    );
    update_post_meta(
      $post_id, 
      'en_cookie_time', 
      $popup_duration
    );


  }