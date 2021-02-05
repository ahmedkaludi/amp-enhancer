<?php
function amp_enhancer_render_facebook_comments( $content ) {

   $version = HEATEOR_FFC_VERSION;
   $options = get_option( 'heateor_ffc' );
   $facebook_public = new Fancy_Facebook_Comments_Public( $options, $version );

    global $post;
    
    if ( ! $post ) {
      return $content;
    }

    $post_meta = get_post_meta( $post->ID, '_heateor_ffc_meta', true );

    // return if AMP
    /*if ( $facebook_public->is_amp_page() ) {
      return $content;
    }*/

    if ( isset( $post_meta['facebook_comments'] ) && $post_meta['facebook_comments'] == 1 && ( ! is_front_page() || ( is_front_page() && 'page' == get_option( 'show_on_front' ) ) ) ) {
      return $content;
    }

    $bp_activity = false;

    if ( current_filter() == 'bp_activity_entry_meta' ) {
      if ( isset( $options['bp_activity'] ) ) {
        $bp_activity = true;
      }
    }
    
    $post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
    $post_types = array_diff( $post_types, array( 'post', 'page' ) );

    // default post url
    $post_url = get_permalink( $post->ID );
    if ( $bp_activity ) {
      $post_url = bp_get_activity_thread_permalink();
    } else {
      if ( $options['url_to_comment'] == 'default' ) {
        $post_url = get_permalink( $post->ID );
        if ( $post_url == '' ) {
          $post_url = html_entity_decode( esc_url( $facebook_public->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
        }
      } elseif ( $options['url_to_comment'] == 'home' ) {
        $post_url = home_url();
      } elseif ( $options['url_to_comment'] == 'custom' ) {
        $post_url = $options['url_to_comment_custom'] ? $options['url_to_comment_custom'] : get_permalink( $post->ID );
      }
    }
    
    $facebook_comments_style = '<style type="text/css">.fb-comments,.fb-comments span,.fb-comments span iframe[style]{min-width:100%!important;width:100%!important}</style><div id="fb-root"></div>';
    $post_url = $facebook_public->generate_facebook_comments_url( $post_url );
    
    $facebook_comments_html = $facebook_public->facebook_comments_html( $post_url );
    $container_style = 'width:100%;text-align:' . $options['title_alignment'] . ';';
    if ( $options['bg_color'] ) {
      $container_style .= 'background-color:' . $options['bg_color'] . ';';
    }
    $title_style = 'padding:10px;';
    if ( $options['title_font_size'] ) {
      $title_style .= 'font-size:' . $options['title_font_size'] . 'px;';
    }
    if ( $options['title_font_family'] ) {
      $title_style .= 'font-family:' . $options['title_font_family'] . ';';
    }
    if ( $options['title_color'] ) {
      $title_style .= 'color:' . $options['title_color'] . ';';
    }

    $horizontal_div = "<div class='heateorFfcClear'></div><div style='". $container_style ."' class='heateor_ffc_facebook_comments'><" . $options['heading_tag'] . " class='heateor_ffc_facebook_comments_title' style='" . $title_style . "' >" . ucfirst( $options['commenting_label'] ) . "</" . $options['heading_tag'] . ">" . $facebook_comments_html . "</div><div class='heateorFfcClear'></div>";
    if ( $bp_activity ) {
      echo $horizontal_div;
    }
    // show Facebook Comments box
    if ( ( isset( $options['home'] ) && is_front_page() ) || ( isset( $options['category'] ) && is_category() ) || ( isset( $options['archive'] ) && is_archive() ) || ( isset( $options['post'] ) && is_single() && isset( $post -> post_type ) && $post -> post_type == 'post' ) || ( isset( $options['page'] ) && is_page() && isset( $post -> post_type ) && $post -> post_type == 'page' ) || ( isset( $options['excerpt'] ) && (is_home() || current_filter() == 'the_excerpt' ) ) || ( isset( $options['bb_reply'] ) && current_filter() == 'bbp_get_reply_content' ) || ( isset( $options['bb_forum'] ) && ( isset( $options['top'] ) && current_filter() == 'bbp_template_before_single_forum' || isset( $options['bottom'] ) && current_filter() == 'bbp_template_after_single_forum' ) ) || ( isset( $options['bb_topic'] ) && ( isset( $options['top'] ) && in_array( current_filter(), array( 'bbp_template_before_single_topic', 'bbp_template_before_lead_topic' ) ) || isset( $options['bottom'] ) && in_array( current_filter(), array( 'bbp_template_after_single_topic', 'bbp_template_after_lead_topic' ) ) ) ) || ( isset( $options['woocom_shop'] ) && current_filter() == 'woocommerce_after_shop_loop_item' ) || ( isset( $options['woocom_product'] ) && current_filter() == 'woocommerce_share' ) || ( isset( $options['woocom_thankyou'] ) && current_filter() == 'woocommerce_thankyou' ) || (current_filter() == 'bp_before_group_header' && isset( $options['bp_group'] ) ) ) {
      if ( in_array( current_filter(), array( 'bbp_template_before_single_topic', 'bbp_template_before_lead_topic', 'bbp_template_before_single_forum', 'bbp_template_after_single_topic', 'bbp_template_after_lead_topic', 'bbp_template_after_single_forum', 'woocommerce_after_shop_loop_item', 'woocommerce_share', 'woocommerce_thankyou', 'bp_before_group_header' ) ) ) {
        echo '<div class="heateorFfcClear"></div>' . $horizontal_div . '<div class="heateorFfcClear"></div>';
      } else {
        if ( isset( $options['top'] ) && isset( $options['bottom'] ) ) {
          $content = $horizontal_div . '<br/>' . $content . '<br/>' . $horizontal_div;
        } else {
          if ( isset( $options['top'] ) ) {
            $content = $horizontal_div.$content;
          } elseif ( isset( $options['bottom'] ) ) {
            $content = $content.$horizontal_div;
          }
        }
        $content = $facebook_comments_style . $content;
      }
    } elseif ( count( $post_types ) ) {
      foreach ( $post_types as $post_type ) {
        if ( isset( $options[$post_type] ) && ( is_single() || is_page() ) && isset( $post -> post_type ) && $post -> post_type == $post_type ) {
          if ( isset( $options['top'] ) && isset( $options['bottom'] ) ) {
            $content = $horizontal_div . '<br/>' . $content . '<br/>' . $horizontal_div;
          } else {
            if ( isset( $options['top'] ) ) {
              $content = $horizontal_div . $content;
            } elseif ( isset( $options['bottom'] ) ) {
              $content = $content . $horizontal_div;
            }
          }
          $content = $facebook_comments_style . $content;
        }
      }
    }
    return $content;
  }
