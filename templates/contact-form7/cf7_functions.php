<?php

function amp_enhancer_cf7_form_elements_modification($fields){
	$required_msg = 'This field is required.';
	$invalid_email = 'The e-mail address entered is invalid.';

		if(preg_match('<input\s+type="(.*?)"\s+name="(.*?)"\s+aria-required="true"(.*?)>', $fields)){

			$fields = preg_replace( '/<input\s+type="(.*?)"\s+name="(.*?)"\s+aria-required="true"(.*?)>/', '<input required type="$1" name="$2" id="show-all-on-submit-$2" aria-required="true"$3><span visible-when-invalid="valueMissing" validation-for="show-all-on-submit-$2">'.esc_html($required_msg).'</span>', $fields ); 
		}

		if(preg_match('/<input\s+required\s+type="email"\s+name="(.*?)"(.*?)>/', $fields)){

			$fields = preg_replace( '/<input\s+required\s+type="email"\s+name="(.*?)"(.*?)>/', '<input required type="email" name="$1" $2> <span visible-when-invalid="typeMismatch" validation-for="show-all-on-submit-$1">'.esc_html($invalid_email).'</span>', $fields );
		}

		if(preg_match('/<textarea(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/textarea>/', $fields)){

		 	$fields = preg_replace( '/<textarea(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/textarea>/', '<textarea required $1 name="$2" $3 id="show-all-on-submit-$2" aria-required="true"$4>$5</textarea><span visible-when-invalid="valueMissing" validation-for="show-all-on-submit-$2">'.esc_html($required_msg).'</span>', $fields );
		}
		
		if(preg_match('/<select(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/select>/', $fields)){

			$fields = preg_replace( '/<select(.*?)name="(.*?)"(.*?)aria-required="true"(.*?)>(.*?)<\/select>/', '<select required $1 name="$2" $3 id="show-all-on-submit-$2" aria-required="true"$4>$5</select><span visible-when-invalid="valueMissing" validation-for="show-all-on-submit-$2">'.esc_html($required_msg).'</span>', $fields );
		}

    return $fields;

 }

function amp_enhancer_cf7_validate($validate){
	$validate = false;
	return $validate;
}

function amp_enhancer_add_cf7_custom_class($class){
	$class .='  amp_wpcf7_form  ';
	return $class;
}


function amp_enhancer_wpcf7_contact_form_tag_func($atts, $content = null, $code = ''){

  if ( is_feed() ) {
    return '[contact-form-7]';
  }

  if ( 'contact-form-7' == $code ) {
    $atts = shortcode_atts(
      array(
        'id' => 0,
        'title' => '',
        'html_id' => '',
        'html_name' => '',
        'html_class' => '',
        'output' => 'form',
      ),
      $atts, 'wpcf7'
    );

    $id = (int) $atts['id'];
    $title = trim( $atts['title'] );
     


    if ( ! $contact_form = amp_enhancer_wpcf7_contact_form( $id ) ) {
      $contact_form = amp_enhancer_wpcf7_get_contact_form_by_title( $title );
    }

  } else {
    if ( is_string( $atts ) ) {
      $atts = explode( ' ', $atts, 2 );
    }

    $id = (int) array_shift( $atts );
    $contact_form = amp_enhancer_wpcf7_get_contact_form_by_old_id( $id );
  }

  if ( ! $contact_form ) {
    return sprintf(
      '[contact-form-7 404 "%s"]',
      esc_html( __( 'Not Found', 'contact-form-7' ) )
    );
  }

  return  $contact_form->form_html( $atts );
}


function amp_enhancer_wpcf7_contact_form( $id ) {
  return AMP_Enhancer_WPCF7_ContactForm::get_instance( $id );
}


function amp_enhancer_wpcf7_get_contact_form_by_title( $title ) {
  $page = get_page_by_title( $title, OBJECT, WPCF7_ContactForm::post_type );

  if ( $page ) {
    return amp_enhancer_wpcf7_contact_form( $page->ID );
  }

  return null;
}

function amp_enhancer_wpcf7_get_contact_form_by_old_id( $old_id ) {
  global $wpdb;

  $q = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_old_cf7_unit_id'"
    . $wpdb->prepare( " AND meta_value = %d", $old_id );

  if ( $new_id = $wpdb->get_var( $q ) ) {
    return amp_enhancer_wpcf7_contact_form( $new_id );
  }
}