<?php

function amp_enhancer_ninja_field_markup($s){
	$fieldTypeName = $fieldHtml = '';
	$form_id = (isset($s['parent_id'])? $s['parent_id']: '');
	
	ob_start();
	Ninja_Forms::template( 'fields-'.$s['type'].'.html' );
	 $fieldHtml .= ob_get_contents();
	ob_get_clean();
	$fieldTypeName = $s['type'];

	if(empty($fieldHtml)){
		if($s['type'] == 'spam' || $s['type'] == 'confirm'){
			ob_start();
			Ninja_Forms::template( 'fields-textbox.html' );
			 $fieldHtml .= ob_get_contents();
			ob_get_clean();
		}elseif($s['type'] == 'listmultiselect' || $s['type'] == 'listcountry' || $s['type'] == 'liststate'){
			ob_start();
			Ninja_Forms::template( 'fields-listselect.html' );
			 $fieldHtml .= ob_get_contents();
			ob_get_clean();
		}else{
			ob_start();
			Ninja_Forms::template( 'fields-'.$s['parentType'].'.html' );
			 $fieldHtml .= ob_get_contents();
			ob_get_clean();
		}
		$fieldTypeName = $s['parentType'];
	}

	$fieldHtml = amp_enhancer_ninja_template_cleanup($s, $fieldHtml, $fieldTypeName);
	if(isset($s['options'])){

	}
	return $fieldHtml;
}

function amp_enhancer_ninja_template_cleanup($s, $fieldHtml, $fieldTypeName = ''){
	if(empty($fieldTypeName)){ $fieldTypeName = $s['type']; }
	/*switch($s['type']){
		case 'textbox':*/

	preg_match_all("/{{{ (.*?) }}}/", $fieldHtml, $matches);
	$matches = array_unique($matches[1]);
	foreach ($matches as $key => $attr) {
		if(strpos($attr, "()")!==False){
			$attrArray = explode(".", $attr);
			$arrayKey = trim($attrArray[1]);
			switch ($attrArray[1]) {
				case 'renderElement()':
					# code...
					break;
				case 'renderLabel()':
					# code...
					break;
				case 'renderLabelClasses()':
					$e = "";
                    $replace = ( 0 !== $s['customLabelClasses'] && ($e = $s['customLabelClasses']));
                    $fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);
					break;
				case 'renderPlaceholder()':
					$e =  "";
					$replace = (isset($s['placeholder']) && ($e = $s['placeholder']) ? 'placeholder="'.$e.'"' : "");
					$fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);
					break;
				case 'renderWrapClass()':
					$replace = "field-wrap " . $s['type'] . "-wrap";
					$fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);
					break;
				case 'renderClasses()':
					$replace = (isset($s['classes'])? $s['classes'] : "ninja-forms-field");
					$fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);

					break;
				case 'maybeDisabled()':
					 $replace = (isset($s['disable_input'] ) && 1 == $s['disable_input'] ? "disabled" : "");
					 $fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);
					break;
				case 'maybeDisableAutocomplete()':
					$replace  = (isset($s['disable_browser_autocomplete']) && 1 == $s['disable_browser_autocomplete'] ? 'autocomplete="off"' : "");
					$fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);
					break;
				case 'maybeInputLimit()':
					$replace = ("characters" == $s['input_limit_type'] && "" != trim($s['input_limit']) ? 'maxlength="' + $s['input_limit'] . '"' : "");

					$fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);

					break;
				case 'getHelpText()':
					$s['help_text'] = (isset($s['help_text'])? $s['help_text']: "");
					$fieldHtml = str_replace("{{{ ".$attr." }}}", "<p>".$s['help_text']."</p>", $fieldHtml);
					break;
				case 'maybeRenderHelp()':
					$fieldHtml = str_replace("{{{ ".$attr." }}}", "<p>".$s['help_text']."</p>", $fieldHtml);
					break;
				case 'renderDescText()':
					$fieldHtml = str_replace("{{{ ".$attr." }}}", "<p>".$s['desc_text']."</p>", $fieldHtml);
					break;
				case 'renderOtherAttributes()':
				$fieldHtml = str_replace("{{{ ".$attr." }}}", "", $fieldHtml);
					break;
				case 'maybeRequired()':
				$required = '';
				if($s['required'] == true){
			    $required = 'required';		
				}
				$fieldHtml = str_replace("{{{ ".$attr." }}}", $required, $fieldHtml);
				break;
				case 'renderRatings()':
				if($s['type'] == 'starrating'){
					$fieldHtml = preg_replace("/required\n/", "", $fieldHtml);
					$fieldHtml = preg_replace("/<#\s}\s#>/", "", $fieldHtml);
					$replace = (isset($s['classes'])? $s['classes'] : "ninja-forms-field");
					preg_match("/<script\sid=\"tmpl-nf-field-starrating\"\stype=\"text\/template\">(.*?)<\/script>/si", $fieldHtml, $matchOpts);
					preg_match("/<script\sid=\"tmpl-nf-field-starrating-star\"\stype=\"text\/template\">(.*?)<\/script>/si", $fieldHtml, $matchStars);
					$optionsTagTemplate = strip_tags($matchOpts[1]);
					$starsTemplate = $matchStars[1];
					$optionsTagHtml = '';
					$starsHtml = '';
					$radioButtons = "";
					for($i=$s['number_of_stars'];$i>=1;$i--){
						$radioButtons = trim(str_replace(array('{{{ data.classes }}}','{{{ data.num }}}','{{{ data.checked }}}'), array($replace,$i,''), $starsTemplate));
						$starsHtml .= $radioButtons.'<label for="rating'.$i.'" title="'.$i.' stars" >☆</label>';
					}
					$optionsTagHtml = '<fieldset class="rating">'.$starsHtml.'</fieldset>';
					$fieldHtml = str_replace("{{{ data.renderRatings() }}}", $optionsTagHtml, $optionsTagTemplate );
					
				}
					break;
				case 'renderOptions()':

				if( $s['type'] == 'listimage'){
					preg_match("/<script\sid=\"tmpl-nf-field-listimage\"\stype=\"text\/template\">(.*?)<\/script>/si", $fieldHtml, $matchWrapper);
					preg_match("/<script\sid=\'tmpl-nf-field-listimage-option\'\stype=\'text\/template\'>(.*?)<\/script>/si", $fieldHtml, $matchOpts);
					$optionsTagTemplate = $matchWrapper[1];
					$optionsTagHtml = $matchOpts[1];
					$optionsTagImage = '';
					$replace = (isset($s['classes'])? $s['classes'] : "ninja-forms-field");
					$image_selection_type = 'radio';
					if($s['allow_multi_select'] == 1){
						$image_selection_type = 'checkbox';
					}
					$image_label = '';
					$optionsFieldName = '';
					$styles = '';
					if( $s['list_orientation'] == 'horizontal'){
						$styles = 'display: grid; grid-template-columns: repeat('.$s['num_columns'].', 1fr); grid-gap: 10px;right: 0px;';
					}else{
						$styles = 'display: grid; grid-template-columns: 1fr; grid-gap: 10px;right: 0px;';
					}
					
					if(isset($s['image_options']) && count($s['image_options'])>0 && !empty($optionsTagTemplate)){
						foreach($s['image_options'] as $optionData){
							if(isset($optionData['visible']) && ! $optionData['visible'] ){
								continue;
							}
							if($s['show_option_labels'] == 1){
								$image_label = '<span>'.$optionData['label'].'</span>';
							}
							
							$checked = '';
							if($optionData['selected']){
								$checked = "checked";
							}
							$optionsTagImage = trim(str_replace(array(
								'{{{ data.value }}}',
								'{{{ data.classes }}}',
								'{{{ data.label }}}',
								'{{{ data.index }}}',
								'{{{ data.fieldID }}}',
								'{{{data.image}}}',
								'{{{data.alt_text}}}',
								'{{{data.img_title}}}',
								'{{{data.image_type}}}',
								'{{{ ( data.isSelected ) ? \'checked="checked"\' : \'\' }}}',
								'{{{ ( data.isSelected ) ? \' nf-checked\' : \'\' }}}',
								'{{{data.styles}}}',
											"{{{ ( 1 == data.selected ) ? 'selected=\"selected\"' : '' }}}",
											"<# if ( ! data.visible ) { return ''; } #>"
											), 
										array($optionData['value'],
											$replace,
											$image_label,
											$optionData['order'],
											$s['id'],
											$optionData['image'],
											$optionData['alt_text'],
											$optionData['img_title'],
											$image_selection_type,
											$checked,
											$checked,
											"",
											"",
											""
											), $optionsTagHtml));
							
							if($s['allow_multi_select'] == 1){
								$optionsFieldName .= preg_replace('/name="(.*?)"/', 'name="$1['.$optionData['order'].']"', $optionsTagImage);
							}else{
								$optionsFieldName .= $optionsTagImage;
							}
						}
					}	
					$fieldHtml = str_replace("{{{ data.renderOptions() }}}", $optionsFieldName, $optionsTagTemplate );

					$fieldHtml = preg_replace("/<#\sif\(\sdata.required\s\)\s{(.*?)#>\s/", "", $fieldHtml);
					$fieldHtml = preg_replace("/required\n/", "", $fieldHtml);
					$fieldHtml = preg_replace("/<#\s}\s#>/", "", $fieldHtml);
					$fieldHtml = preg_replace('/<ul(.*?)style=\"(.*?)\"(.*?)>/', '<ul$1style="'.$styles.'"$3>', $fieldHtml);
				}

				if($s['type'] == 'listcheckbox'){
					preg_match("/<script\sid=\'tmpl-nf-field-listcheckbox-option\'\stype=\'text\/template'>(.*?)<\/script>/si", $fieldHtml, $matchOpts);
					$optionsTagTemplate = $matchOpts[1];
					$optionsTagHtml = '';
					$optionsFieldName = '';
					if(isset($s['options']) && count($s['options'])>0 && !empty($optionsTagTemplate)){
						foreach($s['options'] as $optionData){
							if(isset($optionData['visible']) && ! $optionData['visible'] ){
								continue;
							}
							
							$optionsTagHtml = trim(str_replace(array('{{{ data.value }}}',
											'{{{ data.label }}}',
											'{{{ data.index }}}',
											'{{{ data.fieldID }}}',
											"{{{ ( 1 == data.selected ) ? 'selected=\"selected\"' : '' }}}",
											"<# if ( ! data.visible ) { return ''; } #>"
											), 
										array($optionData['value'],
											$optionData['label'],
											$optionData['order'],
											$s['id'],
											"",
											""
											), $optionsTagTemplate));
							
							$optionsFieldName .= preg_replace('/name="(.*?)"/', 'name="$1['.$optionData['order'].']"', $optionsTagHtml);
						}
					}

					$fieldHtml = str_replace("{{{ data.renderOptions() }}}", $optionsFieldName, $fieldHtml );
					$fieldHtml = preg_replace("/<script id=\"tmpl-nf-field-listcheckbox-option\" type=\"text\/template\">(.*?)<\/script>/si", "", $fieldHtml);
					$fieldHtml = preg_replace("/<#\sif\(\sdata.required\s\)\s{(.*?)#>\s/", "", $fieldHtml);
					$fieldHtml = preg_replace("/required\n/", "", $fieldHtml);
					$fieldHtml = preg_replace("/<#\s}\s#>/", "", $fieldHtml);
				}

				if($s['type'] == 'listselect' || $s['type'] == 'listmultiselect' || $s['type'] == 'listcountry' || $s['type'] == 'liststate'){
					preg_match("/<script id=\"tmpl-nf-field-listselect-option\" type=\"text\/template\">(.*?)<\/script>/si", $fieldHtml, $matchOpts);
					$optionsTagTemplate = $matchOpts[1];
					$optionsTagHtml = '';
					if(isset($s['options']) && count($s['options'])>0 && !empty($optionsTagTemplate)){
						foreach($s['options'] as $optionData){
							if(isset($optionData['visible']) && ! $optionData['visible'] ){
								continue;
							}
							$optionsTagHtml .= trim(str_replace(array('{{{ data.value }}}',
											'{{{ data.label }}}',
											"{{{ ( 1 == data.selected ) ? 'selected=\"selected\"' : '' }}}",
											"<# if ( ! data.visible ) { return ''; } #>",
											), 
										array($optionData['value'],
											$optionData['label'],
											"",
											"",
											), $optionsTagTemplate));
						}
					}
					$fieldHtml = str_replace("{{{ data.renderOptions() }}}", $optionsTagHtml, $fieldHtml );
					$fieldHtml = preg_replace("/<script id=\"tmpl-nf-field-listselect-option\" type=\"text\/template\">(.*?)<\/script>/si", "", $fieldHtml);
				}
			// for radio button.
			if($s['type'] == 'listradio'){
					preg_match("/<script id=\'tmpl-nf-field-listradio-option\' type=\'text\/template\'>(.*?)<\/script>/si", $fieldHtml, $matches);
					$optionsTagTemplate = $matches[1];
					$optionsTagHtml = '';
					if(isset($s['options']) && count($s['options'])>0 && !empty($optionsTagTemplate)){
						foreach($s['options'] as $optionData){
							if(isset($optionData['visible']) && ! $optionData['visible'] ){
								continue;
							}
							$optionsTagHtml .= trim(str_replace(array('{{{ data.value }}}',
											'{{{ data.label }}}',
											'{{{ data.index }}}',
											'{{{ data.fieldID }}}',
											"{{{ ( 1 == data.selected ) ? 'selected=\"selected\"' : '' }}}",
											"<# if ( ! data.visible ) { return ''; } #>"
											), 
										array($optionData['value'],
											$optionData['label'],
											$optionData['order'],
											$s['id'],
											"",
											""
											), $optionsTagTemplate));
						}
					}
				$fieldHtml = str_replace("{{{ data.renderOptions() }}}", $optionsTagHtml, $fieldHtml );
				$fieldHtml = preg_replace("/<script id=\"tmpl-nf-field-listradio-option\" type=\"text\/template\">(.*?)<\/script>/si", "", $fieldHtml);
				$fieldHtml = preg_replace("/<#\sif\(\sdata.required\s\)\s{(.*?)#>\s/", "", $fieldHtml);
				$fieldHtml = preg_replace("/required\n/", "", $fieldHtml);
				$fieldHtml = preg_replace("/<#\s}\s#>/", "", $fieldHtml);
			}
				break;
				default:
					# code...
					break;
			}
		}else{

			$attrArray = explode(".", $attr);
			$arrayKey = trim($attrArray[1]);
			$replace = isset($s[$arrayKey])? $s[$arrayKey]: '';
			$fieldHtml = str_replace("{{{ ".$attr." }}}", $replace, $fieldHtml);
		}
	}
	
	if($s['type'] == 'spam' || $s['type'] == 'confirm'){
		$fieldTypeName = 'textbox';
	}
	if($s['type'] == 'listmultiselect'){
		$fieldTypeName = 'listselect';
		$fieldHtml = preg_replace('/name="(.*?)"/', 'name="$1[]"', $fieldHtml);
		$fieldHtml = str_replace('<select', '<select multiple', $fieldHtml);
	}
	if($s['type'] == 'listcountry' || $s['type'] == 'liststate'){
		$fieldTypeName = 'listselect';
	}

	$fieldHtml = preg_replace("/<script id=\"tmpl-nf-field-".$fieldTypeName."\" type=\"text\/template\">(.*?)<\/script>/s", "$1", $fieldHtml);
	$fieldHtml = preg_replace("/<script(.*?)>(.*?)<\/script>/s", "", $fieldHtml);
	
	$fieldHtml = str_replace(
				array(
					'<div for="nf-field-'.$s['id'].'"></div>'
				),
				array(
					'',
				), $fieldHtml);

	$fieldHtml = preg_replace("/<#(.*?)#>/", "", $fieldHtml);
	//name replacement
	preg_match_all("/{{ (.*?) }}/", $fieldHtml, $nameAttrMatches);
	$nameAttrMatches = array_unique($nameAttrMatches[1]);
	foreach ($nameAttrMatches as $key => $name) {
		$fieldHtml = str_replace("{{ ".$name." }}", "nf-field-".$s['id'], $fieldHtml);	
	}
	$confirm_field = '';
	if($s['type'] == 'confirm'){
		$confirm_field = $s['confirm_field'];
		if(!empty($confirm_field)){
			$fieldHtml = $fieldHtml.'<input type="hidden" name="confirm_field" value="'.$confirm_field.'"><input type="hidden" name="confirm_field_id" value="'.$s['id'].'">';
		}
	}
	if($s['type']=='submit'){
		$fieldHtml = str_replace('type="button"', 'type="submit"', $fieldHtml);	
	}

	return $fieldHtml;
}

function amp_enhancer_ninja_wrapper_template_cleanup($fieldWrapper, $fieldLabel,$fieldsHtml,$field){
	$fieldWrapper = str_replace(
						array(
							'<script id="tmpl-nf-field-wrap" type="text/template">',
							'</script>',
							),
						array(
							'',
							'',
						), $fieldWrapper);
	$fieldWrapper = preg_replace("/<#(.*?)#>/s", "", $fieldWrapper);
	$fieldWrapper = preg_replace("/{{{ data.id }}}/", $field['id'], $fieldWrapper);
	
	$fieldWrapper = str_replace("{{{ data.renderWrapClass() }}}", "field-wrap ".$field['type'] . "-wrap" , $fieldWrapper);

	//label
	if($field['key']!='submit'){
		$fieldLabel = str_replace(
							array(
								'</script>',
								'<script id="tmpl-nf-field-label" type="text/template">'
								),
							array(
								'',
								'',
							), $fieldLabel);
		$fieldLabel = preg_replace("/{{{ data.id }}}/", $field['id'], $fieldLabel);
		$fieldLabel = preg_replace("/{{{ data.label }}}/", $field['label'], $fieldLabel);
		$requiredReplace = '';
		if(!isset($field['required'])){
			$field['required'] = '';
		}
		if($field['required']==1){
			$requiredReplace = '<span class="ninja-forms-req-symbol">*</span>';
		}
		$fieldLabel = str_replace("{{{ ( 'undefined' != typeof data.required && 1 == data.required ) ? '<span class=\"ninja-forms-req-symbol\">*</span>' : '' }}}", $requiredReplace, $fieldLabel);

		$customLabelClasses = '';
		if(isset($field['customLabelClasses'])){
			$customLabelClasses = $field['customLabelClasses'];
		}
		$fieldLabel = str_replace("{{{ data.renderLabelClasses() }}}", $customLabelClasses, $fieldLabel);
		$fieldLabel = str_replace("{{{ data.maybeRenderHelp() }}}", "" , $fieldLabel);

		
			
	}else{//if Closed $field['key']!='submit'
	$fieldLabel = '';
	}
	$fieldWrapper = str_replace(array('{{{ data.renderElement() }}}', '{{{ data.renderLabel() }}}','{{{ data.renderDescText() }}}'), array($fieldsHtml,  $fieldLabel, ''), $fieldWrapper);	
	return $fieldWrapper;
}