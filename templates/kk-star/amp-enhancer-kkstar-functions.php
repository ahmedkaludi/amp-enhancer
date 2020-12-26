<?php 
function amp_enhancer_kkstar_rating(){

  $en_rating_stored_meta       =  get_post_meta ( get_the_ID(),'_kksr_avg',true ) ;
    $total_star_count  = floatval($en_rating_stored_meta);
    $amp_no_of_ratings = get_post_meta(get_the_ID(),'_kksr_casts',true);

      if ( $total_star_count >= 0  ) {
      // Create value for star rating
        if ( $total_star_count > 10 ) {
            $total_star_count = 10;
        }
      // Select the rating type between 5 and 10
      $rating_star_type   = 5;

      // $total_star_count = $total_star_count / 2;

      $rating_star_count  = $total_star_count;
      $rating_full_star   = floor($rating_star_count);

      if( $total_star_count == 0){
        // Create value for Half star
        $rating_half_star   = (int)$rating_star_count - $rating_full_star;
      }else{
        $rating_half_star   = $rating_star_count - $rating_full_star;
      }
    }
      
      // Create value for empty star
      $rating_empty_star  = $rating_star_type - $rating_full_star - $rating_half_star;
      $rating_empty_star  = floor($rating_empty_star);
      $submit_url =  admin_url('admin-ajax.php?action=amp_enhancer_kk_star_rating_form');
      $actionXhrUrl = preg_replace('#^https?:#', '', $submit_url);
      $kksr_enable = get_option('kksr_enable');
       if($kksr_enable == 1 && is_singular()){
       $rating_html =   amp_enhancer_rating_form($total_star_count, $rating_star_type, $amp_no_of_ratings, $actionXhrUrl);

        echo $rating_html;
     }
}

function amp_enhancer_rating_form($rating_total, $rating_star_type,$amp_no_of_ratings, $actionXhrUrl){

       $thank_you = 'Thanks for rating';
       $vote = 'Votes';
   
  $total_rating  = round($rating_total,0,PHP_ROUND_HALF_UP);
  $no_of_votes = '';
  if( $amp_no_of_ratings ){
    $no_of_votes = $amp_no_of_ratings;
  }
 $rating_form = '';
  $rating_star_type = 5;
  $total_star_count  = floatval($rating_total);
  $star_count  = $total_star_count;
  $rating_full_star   = floor($star_count);

  if( $total_star_count == 0){
    // Create value for Half star
    $rating_half_star   = (int)$star_count - $rating_full_star;
  }else{
    $rating_half_star   = $star_count - $rating_full_star;
  } 
  // Create value for empty star
  (int)$rating_empty_star  = $rating_star_type - $rating_full_star - $rating_half_star;
  $rating_form = '<span class="add-star-rating"> ';
    // Full star code
    if ($rating_full_star) {
      for ($i=0; $i < $rating_full_star; $i++) {
        $rating_form .='  <span class="en-sticn full">☆</span>';
      }
    }
    // Half star code
    if ( $rating_half_star > 0) {
      $rating_form .=' <span class="en-sticn half">☆</span> ';
    }
    // Empty star code
    if ((int)$rating_empty_star) {
      for ($i=0; $i < (int)$rating_empty_star; $i++) {
        $rating_form .='  <span class="en-sticn empty">☆</span> ';
      }
    }
    // .star-rating
  $rating_form .= '</span>';
              $add_rating_text = 'Add Rating';
              $Stars_text = 'Stars';
    $get_the_curr_ID = get_the_ID();
    
    $ratings = get_post_meta($get_the_curr_ID, '_kksr_ratings', true) ? get_post_meta($get_the_curr_ID, '_kksr_ratings', true) : 0;
    $casts = get_post_meta($get_the_curr_ID, '_kksr_casts', true) ? get_post_meta($get_the_curr_ID, '_kksr_casts', true) : 0;

    $strategies = (array) get_option(Bhittani\StarRating\prefix('strategies'), []);
    if (in_array('unique', $strategies)) {
      if($casts >= 1 ){
        $add_rating_text = '';
        }
        $custom_css = 'body .en-sticn.full:before,body .en-sticn.half:before {
      color:#ffd700;}';
         wp_add_inline_style( 'amp_enhancer_kkstar_css', $custom_css );
      }
  $rating_nonce = wp_create_nonce( 'amp_en_kkrating_nonce' );  
  $rating_form = '<div>'. $rating_form.'<span class="str-rt-txt">
          '. round($rating_total,1). '<span class="kksr-muted">/</span>'. $rating_star_type .'<span class="kksr-muted"> ( </span>'.$no_of_votes.' <span class="kksr-muted">'.esc_html__('votes','amp-enhancer').' )</span>
        </span>
        <span class= "rt_form">
    <button on="tap:AMP.setState({ hideRatingForm: false })">'.esc_html__($add_rating_text,'amp-enhancer').'</button>
      <span hidden [hidden]="hideRatingForm"><form id="rating" class="p2" method="post" action-xhr="'.esc_url_raw($actionXhrUrl).'" target="_blank">
          <fieldset class="en-rt">
            <input name="rating" type="radio" id="rating5" value="5" on="change:rating.submit" />
            <label for="rating5" title="5 stars" >☆</label>

            <input name="rating" type="radio" id="rating4" value="4" on="change:rating.submit" />
            <label for="rating4" title="4 stars" >☆</label>

            <input name="rating" type="radio" id="rating3" value="3" on="change:rating.submit" />
            <label for="rating3" title="3 stars" >☆</label>

            <input name="rating" type="radio" id="rating2" value="2" on="change:rating.submit" />
            <label for="rating2" title="2 stars" >☆</label>

            <input name="rating" type="radio" id="rating1" value="1" on="change:rating.submit" />
            <label for="rating1" title="1 stars" >☆</label>
          </fieldset>
          <input name="post_id" type="hidden" id="post_id" value="'.get_the_ID().'" />
          <input name="amp_en_kkrating_nonce" type="hidden" id="amp_en_kkrating_nonce" value="'.esc_attr($rating_nonce).'" />
          <div submit-success>
            <template type="amp-mustache">
              <span itemprop="ratingValue">{{average}}</span> ({{percent}}) 
                <span itemprop="ratingCount">{{votes}}</span> '.$vote.'
              <p> '.esc_html__($thank_you,'amp-enhancer').' {{rating}} '.esc_html__($Stars_text,'amp-enhancer').'!</p>
            </template>
          </div>
          <div submit-error>
            <template type="amp-mustache">
            Looks like something went wrong. Please try to rate again. {{error}}
            </template>
          </div>
        </form></span>
      </span>
        </div> ';

        return $rating_form;
}

// kkrating form submitting function
add_action('wp_ajax_amp_enhancer_kk_star_rating_form','amp_enhancer_kk_star_rating_form');
add_action('wp_ajax_nopriv_amp_enhancer_kk_star_rating_form','amp_enhancer_kk_star_rating_form');

function amp_enhancer_kk_star_rating_form(){
    if(!wp_verify_nonce($_POST['amp_en_kkrating_nonce'],'amp_en_kkrating_nonce')){
        header('HTTP/1.1 500 FORBIDDEN');
        echo wp_json_encode( 'Sorry, your nonce did not verify.' );
        die;
    }
    else{   
        require_once(AMP_ENHANCER_TEMPLATE_DIR.'kk-star/amp-enhancer-kkstar-ajaxcalls.php');
    }
}