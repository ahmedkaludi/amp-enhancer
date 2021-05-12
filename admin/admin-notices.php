<?php
//Notice Review 

function amp_enhancer_request_review(){


    $install_date = get_option( 'amp_enhancer_install_date' );
    $display_date = date( 'Y-m-d h:i:s' );
    $datetime1    = new DateTime( $install_date );
    $datetime2    = new DateTime( $display_date );
    $diff_intrval = round( ($datetime2->format( 'U' ) - $datetime1->format( 'U' )) / (60 * 60 * 24) );

    $rate = get_option( 'amp_enhancer_review_rating', false );

    if( $diff_intrval >= 7 && ($rate === "no" || false === $rate || amp_enhancer_rate_again() ) ) {
        echo '<div class="amp_en_fivestar updated " style="box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);background-color:white;">
        <p>Awesome, you\'ve been using <strong>AMP Enhancer</strong> for more than 1 week. <br> May i ask you to give it a <strong>5-star rating</strong> on Wordpress? </br>
        This will help to spread its popularity and to make this plugin a better one.
        <br><br>Your help is much appreciated. Thank you very much
        <ul>
            <li><a href="https://wordpress.org/support/plugin/amp-enhancer/reviews/?rate=5#new-post" class="thankyou" target="_new" title="Ok, you deserved it" style="font-weight:bold;">Ok, you deserved it</a></li>
            <li><a href="javascript:void(0);" class="ampenHideRating" title="I already did" style="font-weight:bold;">I already did</a></li>
            <li><a href="javascript:void(0);" class="ampenHideRating" title="No, not good enough" style="font-weight:bold;">No, not good enough</a></li>
            <br>
            <li><a href="javascript:void(0);" class="ampenHideRatingWeek" title="No, not good enough" style="font-weight:bold;">I want to rate it later. Ask me again in a week!</a></li>
            <li class="spinner" style="float:none;display:list-item;margin:0px;"></li>        
</ul>

    </div>
    <script>
    jQuery( document ).ready(function( $ ) {

    jQuery(\'.ampenHideRating\').click(function(){
    jQuery(".spinner").addClass("is-active");
        var data={\'action\':\'amp_en_hide_rating\'}
             jQuery.ajax({
        
        url: "' . admin_url( 'admin-ajax.php' ) . '",
        type: "post",
        data: data,
        dataType: "json",
        async: !0,
        success: function(e) {
            if (e=="success") {
               jQuery(".spinner").removeClass("is-active");
               jQuery(\'.amp_en_fivestar\').slideUp(\'fast\');
               
            }
        }
         });
        })
    
        jQuery(\'.ampenHideRatingWeek\').click(function(){
        jQuery(".spinner").addClass("is-active");
        var data={\'action\':\'amp_en_hide_rating_week\'}
             jQuery.ajax({
        
        url: "' . admin_url( 'admin-ajax.php' ) . '",
        type: "post",
        data: data,
        dataType: "json",
        async: !0,
        success: function(e) {
            if (e=="success") {
               jQuery(".spinner").removeClass("is-active");
               jQuery(\'.amp_en_fivestar\').slideUp(\'fast\');
               
            }
        }
         });
        })
    
    });
    </script>
    ';
    }
}

function amp_en_hide_rating() {
    update_option( 'amp_enhancer_review_rating', 'yes' );
    delete_option( 'amp_enhancer_date_next_notice' );
    echo json_encode( array("success") );
    exit;
}

add_action( 'wp_ajax_amp_en_hide_rating', 'amp_en_hide_rating' );

/**
 * Write the timestamp when rating notice will be opened again
 */
function amp_en_hide_rating_week() {
    $nextweek   = time() + (7 * 24 * 60 * 60);
    $human_date = date( 'Y-m-d h:i:s', $nextweek );
    update_option( 'amp_enhancer_date_next_notice', $human_date );
    update_option( 'amp_enhancer_review_rating', 'yes' );
    echo json_encode( array("success") );
    exit;
}

add_action( 'wp_ajax_amp_en_hide_rating_week', 'amp_en_hide_rating_week' );

/**
 * Check if admin notice will open again after one week of closing
 * @return boolean
 */
function amp_enhancer_rate_again() {
    $rate_again_date = get_option( 'amp_enhancer_date_next_notice' );

    if( false === $rate_again_date ) {
        return false;
    }

    $current_date = date( 'Y-m-d h:i:s' );
    $datetime1    = new DateTime( $rate_again_date );
    $datetime2    = new DateTime( $current_date );
    $diff_intrval = round( ($datetime2->format( 'U' ) - $datetime1->format( 'U' )) / (60 * 60 * 24) );

    if( $diff_intrval >= 0 ) {
        return true;
    }
}