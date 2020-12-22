<?php 
function amp_enhancer_wpforms_output( $id, $title = false, $description = false ) {

    if ( empty( $id ) ) {
      return;
    }

    // Grab the form data, if not found then we bail.
    $form = wpforms()->form->get( (int) $id );

    if ( empty( $form ) ) {
      return;
    }

    // Basic information.
    $form_data   = apply_filters( 'wpforms_frontend_form_data', wpforms_decode( $form->post_content ) );
    $form_id     = absint( $form->ID );
    $settings    = $form_data['settings'];
    $action      = esc_url_raw( remove_query_arg( 'wpforms' ) );
    $classes     = wpforms_setting( 'disable-css', '1' ) == '1' ? array( 'wpforms-container-full' ) : array();
    $errors      = empty( wpforms()->process->errors[ $form_id ] ) ? array() : wpforms()->process->errors[ $form_id ];
    $title       = filter_var( $title, FILTER_VALIDATE_BOOLEAN );
    $description = filter_var( $description, FILTER_VALIDATE_BOOLEAN );

    // If the form does not contain any fields - do not proceed.
    if ( empty( $form_data['fields'] ) ) {
      echo '<!-- WPForms: no fields, form hidden -->';
      return;
    }

    // Add url query var wpforms_form_id to track post_max_size overflows.
    if ( in_array( 'file-upload', wp_list_pluck( $form_data['fields'], 'type' ), true ) ) {
      $action = add_query_arg( 'wpforms_form_id', $form_id, $action );
    }

    // Before output hook.
    do_action( 'wpforms_frontend_output_before', $form_data, $form );

    // Check for return hash.
    if (
      ! empty( $_GET['wpforms_return'] ) &&
      wpforms()->process->valid_hash &&
      absint( wpforms()->process->form_data['id'] ) === $form_id
    ) {
      do_action( 'wpforms_frontend_output_success', wpforms()->process->form_data, wpforms()->process->fields, wpforms()->process->entry_id );
      wpforms_debug_data( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
      return;
    }

    // Check for error-free completed form.
    if (
      empty( $errors ) &&
      ! empty( $form_data ) &&
      ! empty( $_POST['wpforms']['id'] ) &&
      absint( $_POST['wpforms']['id'] ) === $form_id
    ) {
      do_action( 'wpforms_frontend_output_success', $form_data, false, false );
      wpforms_debug_data( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
      return;
    }

    // Allow filter to return early if some condition is not met.
    if ( ! apply_filters( 'wpforms_frontend_load', true, $form_data, null ) ) {
      do_action( 'wpforms_frontend_not_loaded', $form_data, $form );
      return;
    }

    // All checks have passed, so calculate multi-page details for the form.
    $pages = wpforms_get_pagebreak_details( $form_data );
/*    if ( $pages ) {
      $WPForms_Frontend->pages = $pages;
    } else {
      $WPForms_Frontend->pages = false;
    }*/

    // Allow final action to be customized - 3rd param ($form) has been deprecated.
    $action = apply_filters( 'wpforms_frontend_form_action', $action, $form_data, null );

    // Allow form container classes to be filtered and user defined classes.
    $classes = apply_filters( 'wpforms_frontend_container_class', $classes, $form_data );
    if ( ! empty( $settings['form_class'] ) ) {
      $classes = array_merge( $classes, explode( ' ', $settings['form_class'] ) );
    }
    $classes = wpforms_sanitize_classes( $classes, true );

    $form_classes = array( 'wpforms-validate', 'wpforms-form' );

    if ( ! empty( $form_data['settings']['ajax_submit'] ) && ! wpforms_is_amp() ) {
      $form_classes[] = 'wpforms-ajax-form';
    }

    $form_atts = array(
      'id'    => sprintf( 'wpforms-form-%d', absint( $form_id ) ),
      'class' => $form_classes,
      'data'  => array(
        'formid' => absint( $form_id ),
      ),
      'atts'  => array(
        'method'  => 'post',
        'enctype' => 'multipart/form-data',
        'action'  => esc_url( $action ),
      ),
    );

    if ( wpforms_is_amp() ) {

      // Set submitting state.
      if ( ! isset( $form_atts['atts']['on'] ) ) {
        $form_atts['atts']['on'] = '';
      } else {
        $form_atts['atts']['on'] .= ';';
      }
      $form_atts['atts']['on'] .= sprintf(
        'submit:AMP.setState( %1$s ); submit-success:AMP.setState( %2$s ); submit-error:AMP.setState( %2$s );',
        wp_json_encode(
          array(
            amp_enhancer_get_form_amp_state_id( $form_id ) => array(
              'submitting' => true,
            ),
          )
        ),
        wp_json_encode(
          array(
            amp_enhancer_get_form_amp_state_id( $form_id ) => array(
              'submitting' => false,
            ),
          )
        )
      );

      // Upgrade the form to be an amp-form to avoid sanitizer conversion.
      if ( isset( $form_atts['atts']['action'] ) ) {
        $form_atts['atts']['action-xhr'] = $form_atts['atts']['action'];
        unset( $form_atts['atts']['action'] );

        $form_atts['atts']['verify-xhr'] = $form_atts['atts']['action-xhr'];
      }
    }

    $form_atts = apply_filters( 'wpforms_frontend_form_atts', $form_atts, $form_data );

    // Begin to build the output.
    do_action( 'wpforms_frontend_output_container_before', $form_data, $form );

    printf( '<div class="wpforms-container %s" id="wpforms-%d">', esc_attr( $classes ), absint( $form_id ) );

    do_action( 'wpforms_frontend_output_form_before', $form_data, $form );

    echo '<form ' . wpforms_html_attributes( $form_atts['id'], $form_atts['class'], $form_atts['data'], $form_atts['atts'] ) . '>';

    if ( wpforms_is_amp() ) {

      $state = array(
        'submitting' => false,
      );
      printf(
        '<amp-state id="%s"><script type="application/json">%s</script></amp-state>',
        amp_enhancer_get_form_amp_state_id($form_id ),
        wp_json_encode( $state )
      );
    }


    do_action( 'wpforms_frontend_output', $form_data, null, $title, $description, $errors );

    echo '</form>';

    do_action( 'wpforms_frontend_output_form_after', $form_data, $form );

    echo '</div>  <!-- .wpforms-container -->';

    do_action( 'wpforms_frontend_output_container_after', $form_data, $form );

    // Add form to class property that tracks all forms in a page.
    //$WPForms_Frontend->forms[ $form_id ] = $form_data;

    // Optional debug information if WPFORMS_DEBUG is defined.
    wpforms_debug_data( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

    // After output hook.
    do_action( 'wpforms_frontend_output_after', $form_data, $form );

  }

  function amp_enhancer_wpforms_shortcode( $atts ) {
   
    $defaults = array(
      'id'          => false,
      'title'       => false,
      'description' => false,
    );

    $atts = shortcode_atts( $defaults, shortcode_atts( $defaults, $atts, 'output' ), 'wpforms' );

    ob_start();

    amp_enhancer_wpforms_output( $atts['id'], $atts['title'], $atts['description'] );

    return ob_get_clean();
  }

   function amp_enhancer_get_form_amp_state_id( $form_id ) {
    return sprintf( 'wpforms_form_state_%d', $form_id );
  }