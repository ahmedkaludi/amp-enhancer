<?php
if(!isset($_COOKIE['ampenhancer_popup'])){
   add_action('wp_footer','amp_enhancer_popup_feature_output');
   add_action('amp_post_template_footer','amp_enhancer_popup_feature_output');
}

function amp_enhancer_popup_feature_output(){

    $condition_logics = amp_enhancer_pop_up_post_type_data();
       
     if(!$condition_logics){
         return ;
     }
    if($condition_logics){
      foreach( $condition_logics as $condition_logic ) { 
    
           $post_id= $condition_logic['post_id'];
           $display_condition = $condition_logic['display_condition'];

           if(isset($display_condition) && $display_condition == 'homepage' && (!is_home() && 
             !is_front_page())){
              continue;
           }
           
           $load_everytime = ( isset($condition_logic['cookie_type']) &&  $condition_logic['cookie_type'] == 'en_popup_without_cookie' ) ? 'data-persist-dismissal="false"' : '';

           $dataPersistDismissalValue = (isset($condition_logic['en_popup_set_time'])  && $condition_logic['en_popup_set_time'] == 1 ) ? 'data-persist-dismissal="false"' : '';

           //store cookie if true
                $cookieFormOpen  = $cookieFormClose = '';
                if( isset($condition_logic['cookie_type']) && isset($condition_logic['en_popup_set_time'])  &&   $condition_logic['en_popup_set_time'] == 1 && $condition_logic['cookie_type'] == 'en_popup_with_cookie'){

                    $formUrl   = admin_url('admin-ajax.php?action=amp_enhancer_popup_dismiss_consent');
                    $formUrl   = preg_replace('#^https?:#', '', $formUrl);
                    $cookieFormOpen = '<form class="amp_pu_cf" action-xhr="'.esc_url($formUrl).'" method="post" target="_top"><input type="hidden" name="dismiss_id" value="'.$post_id.'"/>';
                    $cookieFormClose = '</form>';
                }  
            
           ?>
           <amp-user-notification id="amp-en-pop-up-<?php esc_attr_e($post_id); ?>"   <?php echo $load_everytime; ?> layout="nodisplay" <?php echo $dataPersistDismissalValue; ?> style="background: rgba(240, 240, 240, 0.83); border-color: #242323b3; z-index: 10000; height: -webkit-fill-available; height: -moz-fill-available; top:0;"   >
              <div class="en-afwp"> 
                  <?php  
                    $pop_up_data['data'] = '<p>' .$condition_logic['content']. '</p>';
                    ?>
                 <div class ="en-apf post-<?php esc_attr_e($post_id); ?>"   style="  position: relative;background:#fff;box-shadow: 0px 0px 60px 0px #d0d0d0;border-radius: 10px;text-align: center;padding: 40px;overflow-y: auto;max-height: 90vh;"> 
                      <?php

                        echo do_shortcode($pop_up_data['data']);

                      echo $cookieFormOpen;?>
                      <button on="tap:amp-en-pop-up-<?php esc_attr_e($post_id); ?>.dismiss"
                      class="en-apcb"><?php echo 'X';?>
                      </button> 
                    <?php echo $cookieFormClose;?>
                </div>  
            </div>
         </amp-user-notification>
      <?php
      } //for each closes
   
   }
}



//FrontEnd
 
function amp_enhancer_pop_up_post_type_data($contentArray=''){

        $post_idArray = array();
        $arg = array(
              'post_type' => 'ampenhancerpopup',
              'post_status' => 'publish',
              'posts_per_page' => -1,
          );
        if($contentArray != ''){
          $arg['post__in'] = array($contentArray['popup_btn_link']);
        }

        $query = new WP_Query($arg);
        while ($query->have_posts()) {
              $query->the_post();
              $post_idArray[] = get_the_ID();
          } 
          wp_reset_query();
          wp_reset_postdata();
    if(count($post_idArray)>0){    

      $returnData = array();
      foreach ($post_idArray as $key => $post_id){ 
         
              $returnData[] = array(
                    'content' => get_post_field('en_popup_content', $post_id),
                    'display_condition' => get_post_meta( $post_id, 'en_popup_display', true),
                    'cookie_type'  => get_post_meta( $post_id, 'en_popup_cookie_type', true), 
                    'en_popup_set_time' => get_post_meta( $post_id, 'en_popup_set_time', true),
                    'en_cookie_duration' => get_post_meta( $post_id, 'en_cookie_time', true),
                    'post_id'   => $post_id,

                  ); 
            }
     return $returnData;   
  }//iF Closed post_idArray     
   return false;
}  

add_action('amp_post_template_css','amp_enhancer_reader_mode_popup_css',20 );

function amp_enhancer_reader_mode_popup_css(){?>
    .en-afwp { 
      position: fixed; 
      top: 0; 
      right: 0; 
      bottom: 0; 
      left: 0; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      width:800px;
      margin:0 auto;
      -webkit-overflow-scrolling: touch; 
    }

    .en-apcb {
        border: 1px solid #ddd;
        border-radius: 55px;
        height: 36px;
        top: 13px;
        cursor: pointer;
        width: 36px;
        position: absolute;
        font-size: 14px;
        color: #333;
        right: 13px;
        background: transparent;
    }
    .en-apf amp-img { 
      height: auto;
        width: 170px;
        margin: 0 auto;
    }
    .en-apft{
      font-size:56px;
      line-height:1.4;
      font-weight:700;
      color:#333333;
      margin-bottom:10px;
    }
    .en-apf p{
      font-size: 18px;
        line-height: 1.6;
        color: #555;
        font-weight: 300;
    }
    .en-apbtn button { border:none; }

    .en-cu-cls{
      font-size: 15px;
        line-height: 1.2;
        border: none;
        right: 13px;
        width: auto;
        letter-spacing: 1px;
    }
    @media(max-width:768px){
          .en-afwp{
            width:100%;
            padding:15px;
          }
          .en-apft {
             font-size: 45px;
          }
          .en-apf p {
            font-size: 15px;
          }
          .en-apbtn a{
            font-size: 16px;
          }
          .en-apbtn button{
            font-size: 14px;
          }
    }
  @media(max-width:500px){
      .en-apf{
        padding:20px;
      }
      .en-apft {     
          font-size: 30px;
          margin-top:20px;
      }
      .en-apbtn a{
        padding: 20px 25px;
      }
  }    

<?php }

