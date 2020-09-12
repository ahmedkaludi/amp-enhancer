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