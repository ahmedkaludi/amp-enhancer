<?php 

function amp_enhancer_icegram_popup_output(){

  $argument = array(
      'post_type'        => 'ig_campaign',
      'post_status'      => 'publish',
      'posts_per_page' => -1,
    );
    $ig_campaign = get_posts( $argument ); 


    foreach ($ig_campaign as $key => $value) {
      $id = $value->ID;
      $post_data = get_post_meta($id);
      if(isset($post_data['messages'][0])){
        $campaign_data = unserialize($post_data['messages'][0]);
        $campaign_target = unserialize($post_data['icegram_campaign_target_rules'][0]);
         $campaign_id = isset($campaign_id[0]['id']) ? $campaign_id[0]['id'] : $id+1;

            $campaign_settings = get_post_meta($campaign_id);
            if(isset($campaign_settings['icegram_message_data'])){

              $serial_settings = unserialize($campaign_settings['icegram_message_data'][0]);
             // print_r($serial_settings);die;
              $textcolor = isset($serial_settings['text_color']) ? $serial_settings['text_color'] : '#ffffff';
              $bg_style = '';
              if(isset($serial_settings['bg_color']) && !empty($serial_settings['bg_color'])){
                $bg_style = 'style="background-color:'.$serial_settings['bg_color'].'"';
               }
              ?>
           <amp-user-notification id="amp-en-pop-up-<?php esc_attr_e($campaign_id); ?>"   layout="nodisplay"  >


              <div class="mfp-bg mfp-ready"></div>

              <div class="mfp-wrap mfp-auto-cursor mfp-ready" tabindex="-1" style="height: auto;">

                <div class="mfp-container mfp-s-ready mfp-inline-holder">

                  <div class="mfp-content">
               
                      <div id="icegram_message_<?php esc_attr_e($campaign_id); ?>" class="icegram ig_popup ig_persuade ig_container ig_no_icon ig_show ig_anim_no-anim_in" <?php echo $bg_style;?> >
                      <?php if(isset($serial_settings['use_custom_code']) && $serial_settings['use_custom_code'] == 'yes'){ ?>
                                  <style id="ig_custom_css_<?php esc_attr_e($campaign_id); ?>" type="text/css">
                                     <?php  
                                       $custom_css = $serial_settings['custom_css'];
                                       $custom_css = str_replace('#ig_this_message', '#icegram_message_'.$campaign_id.'', $custom_css);

                                       echo $custom_css;
                                     ?>
                                  </style>

                           <?php } ?>
                            <div on="tap:amp-en-pop-up-<?php esc_attr_e($campaign_id); ?>.dismiss" class="ig_close" id="popup_box_close_<?php esc_attr_e($campaign_id); ?>" style="display: inline;background-image: url(<?php echo IG_PLUGIN_URL.'/assets/images/sprite_1.png' ?>)" >X</div><div class="ig_clear_fix" data="<?php esc_attr_e($campaign_id); ?>"><div class="ig_bg_overlay"></div><div class="ig_data ig_clear_fix"><div class="ig_headline" style="display: none;"></div>
                             <div class="ig_content">

                                <div class="ig_message ig_clear_fix"  style="color:<?php esc_attr_e($textcolor); ?>" >

                                      <?php echo  $serial_settings['message']; ?>
                                </div>
                              </div>
                    <?php if(isset($serial_settings['label']) && isset($serial_settings['link'])) {?>
                        <div class="ig_button" style="cursor: pointer;"><a style="color: white;" href="<?php echo esc_url($serial_settings['link']); ?>"> <?php esc_html_e($serial_settings['label'],'amp-enhancer') ?> </a></div>
                     <?php  }  ?>
                   </div></div></div>
                   </div> <!-- mfp-content -->
                   <div class="mfp-preloader">Loading...</div>
                </div>
              </div>  <!-- mfp-wrap mfp-auto-cursor mfp-ready -->

         </amp-user-notification>
      <?php

          }
      }
    }

}