<?php
function amp_enhancer_wpcf7_redirect_handle( $wpcf7 ) {

    if ( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) && class_exists('WPCF7_Submission') && class_exists('WPCF7r_Submission') && ! WPCF7_Submission::is_restful() ) {

        $submission = WPCF7_Submission::get_instance();

        $wpcf7_form = get_cf7r_form( $wpcf7, $submission );

          if ( 'mail_sent' === $wpcf7_form->get_submission_status() ) {

            $submit_class = new WPCF7r_Submission();

            $results = $submit_class->handle_valid_actions( $wpcf7 );

                if ( $results ) {
                    foreach ( $results as $result_type => $result_actions ) {

                            foreach ( $result_actions as $result_action ) {
                                  if ( 'redirect' === $result_type ) {
                                      $redirect_url = $result_action['redirect_url'];

                                      $redirect_url = trailingslashit($redirect_url).'?amp';

                                      header("AMP-Redirect-To: ".esc_url($redirect_url));
                                      header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
                                           //if ( 'new_tab' === $result_action['type'] ) {}
                                  }
                            }
                    }
                }
          }
      }

  }