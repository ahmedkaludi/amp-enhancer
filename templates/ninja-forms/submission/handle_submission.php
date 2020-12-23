<?php
$postedData = $_POST;
$form_id = $postedData['form_detail'];
unset($postedData['_wp_http_referer']);
unset($postedData['_wpnonce']);

$form = Ninja_Forms()->form( $form_id )->get();
$formSettings = $form->get_settings();

$fieldData = array();
foreach ($postedData as $key => $value) {
	$fieldId = str_replace('nf-field-', "", $key);
	$fieldData[$fieldId] = array(
							"value"=> $value,
      						"id"=> $fieldId
							);
}

$formData = array_merge(array('id'=>$form_id),array('fields'=>$fieldData), array('settings'=>$formSettings));
$_POST['formData'] = json_encode($formData);

$ninjaSubmit = new AMP_Enhancer_NF_AJAX_Controllers_Submission();
$ninjaSubmit->submit();

