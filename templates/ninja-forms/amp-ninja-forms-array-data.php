<?php

function amp_enhancer_get_ninja_formData($form_id){
	global $wp_locale;
	$form = Ninja_Forms()->form( $form_id )->get();
		$settings = $form->get_settings();
		foreach( $settings as $name => $value ){
            if( ! in_array(
                $name,
                array(
                    'changeEmailErrorMsg',
                    'confirmFieldErrorMsg',
                    'fieldNumberNumMinError',
                    'fieldNumberNumMaxError',
                    'fieldNumberIncrementBy',
                    'formErrorsCorrectErrors',
                    'validateRequiredField',
                    'honeypotHoneypotError',
                    'fieldsMarkedRequired',
                )
            ) ) continue;

            if( $value ) continue;

            unset( $settings[ $name ] );
        }
        $settings = array_merge( Ninja_Forms::config( 'i18nFrontEnd' ), $settings );
        $settings = apply_filters( 'ninja_forms_display_form_settings', $settings, $form_id );
        $form->update_settings( $settings );

        if( $form->get_setting( 'logged_in' ) && ! is_user_logged_in() ){
            echo do_shortcode( $form->get_setting( 'not_logged_in_msg' ));
            return;
        }

        if( $form->get_setting( 'sub_limit_number' ) ){
            $subs = Ninja_Forms()->form( $form_id )->get_subs();

            // TODO: Optimize Query
            global $wpdb;
            $count = 0;
            $subs = $wpdb->get_results( "SELECT post_id FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_form_id' AND `meta_value` = $form_id" );
            foreach( $subs as $sub ){
                if( 'publish' == get_post_status( $sub->post_id ) ) $count++;
            }

            if( $count >= $form->get_setting( 'sub_limit_number' ) ) {
                echo do_shortcode( apply_filters( 'nf_sub_limit_reached_msg', $form->get_setting( 'sub_limit_msg' ), $form_id ));
                return;
            }
        }

        if( ! apply_filters( 'ninja_forms_display_show_form', true, $form_id, $form ) ) return;

        $currency = $form->get_setting( 'currency', Ninja_Forms()->get_setting( 'currency' ) );
        $currency_symbol = Ninja_Forms::config( 'CurrencySymbol' );
        $form->update_setting( 'currency_symbol', ( isset( $currency_symbol[ $currency ] ) ) ? $currency_symbol[ $currency ] : '' );

        $title = apply_filters( 'ninja_forms_form_title', $form->get_setting( 'title' ), $form_id );
        $form->update_setting( 'title', $title );

        $before_form = apply_filters( 'ninja_forms_display_before_form', '', $form_id );
        $form->update_setting( 'beforeForm', $before_form );

        $before_fields = apply_filters( 'ninja_forms_display_before_fields', '', $form_id );
        $form->update_setting( 'beforeFields', $before_fields );

        $after_fields = apply_filters( 'ninja_forms_display_after_fields', '', $form_id );
        $form->update_setting( 'afterFields', $after_fields );

        $after_form = apply_filters( 'ninja_forms_display_after_form', '', $form_id );
        $form->update_setting( 'afterForm', $after_form );

        $form_fields = Ninja_Forms()->form( $form_id )->get_fields();
        $fields = array();

        if( empty( $form_fields ) ){
            echo __( 'No Fields Found.', 'ninja-forms' );
        } else {

            // TODO: Replace unique field key checks with a refactored model/factory.
            $unique_field_keys = array();
            $cache_updated = false;

            foreach ($form_fields as $field) {

                if( is_object( $field ) ) {
                    $field = array(
                        'id' => $field->get_id(),
                        'settings' => $field->get_settings()
                    );
                }

                $field_id = $field[ 'id' ];


                /*
                 * Duplicate field check.
                 * TODO: Replace unique field key checks with a refactored model/factory.
                 */
                $field_key = $field[ 'settings' ][ 'key' ];

                if( in_array( $field_key, $unique_field_keys ) || '' == $field_key ){

                    // Delete the field.
                    Ninja_Forms()->request( 'delete-field' )->data( array( 'field_id' => $field_id ) )->dispatch();

                    // Remove the field from cache.
                    if( $form_cache ) {
                        if( isset( $form_cache[ 'fields' ] ) ){
                            foreach( $form_cache[ 'fields' ] as $cached_field_key => $cached_field ){
                                if( ! isset( $cached_field[ 'id' ] ) ) continue;
                                if( $field_id != $cached_field[ 'id' ] ) continue;

                                // Flag cache to update.
                                $cache_updated = true;

                                unset( $form_cache[ 'fields' ][ $cached_field_key ] ); // Remove the field.
                            }
                        }
                    }

                    continue; // Skip the duplicate field.
                }
                array_push( $unique_field_keys, $field_key ); // Log unique key.
                /* END Duplicate field check. */

                $field_type = $field[ 'settings' ][ 'type' ];

                if( ! is_string( $field_type ) ) continue;

                if( ! isset( Ninja_Forms()->fields[ $field_type ] ) ) {
                    $unknown_field = NF_Fields_Unknown::create( $field );
                    $field = array(
                        'settings' => $unknown_field->get_settings(),
                        'id' => $unknown_field->get_id()
                    );
                    $field_type = $field[ 'settings' ][ 'type' ];
                }

                $field = apply_filters('ninja_forms_localize_fields', $field);
                $field = apply_filters('ninja_forms_localize_field_' . $field_type, $field);

                $field_class = Ninja_Forms()->fields[$field_type];

                //if (NF_Display_Render::$use_test_values) {
                    $field[ 'value' ] = $field_class->get_test_value();
                //}

                // Hide the label on invisible reCAPTCHA fields
                if ( 'recaptcha' === $field[ 'settings' ][ 'type' ] && 'invisible' === $field[ 'settings' ][ 'size' ] ) {
                    $field[ 'settings' ][ 'label_pos' ] = 'hidden';
                }

                // Copy field ID into the field settings array for use in localized data.
                $field[ 'settings' ][ 'id' ] = $field[ 'id' ];


                /*
                 * TODO: For backwards compatibility, run the original action, get contents from the output buffer, and return the contents through the filter. Also display a PHP Notice for a deprecate filter.
                 */

                $display_before = apply_filters( 'ninja_forms_display_before_field_type_' . $field[ 'settings' ][ 'type' ], '' );
                $display_before = apply_filters( 'ninja_forms_display_before_field_key_' . $field[ 'settings' ][ 'key' ], $display_before );
                $field[ 'settings' ][ 'beforeField' ] = $display_before;

                $display_after = apply_filters( 'ninja_forms_display_after_field_type_' . $field[ 'settings' ][ 'type' ], '' );
                $display_after = apply_filters( 'ninja_forms_display_after_field_key_' . $field[ 'settings' ][ 'key' ], $display_after );
                $field[ 'settings' ][ 'afterField' ] = $display_after;

               /* $templates = $field_class->get_templates();
                var_dump( $templates);die;
                if (!array($templates)) {
                    $templates = array($templates);
                }

                foreach ($templates as $template) {
                    NF_Display_Render::load_template('fields-' . $template);
                }*/

                $settings = $field[ 'settings' ];
                foreach ($settings as $key => $setting) {
                    if (is_numeric($setting) && 'custom_mask' != $key )
                    	$settings[$key] =
	                    floatval($setting);
                }

                if( ! isset( $settings[ 'label_pos' ] ) || 'default' == $settings[ 'label_pos' ] ){
                    $settings[ 'label_pos' ] = $form->get_setting( 'default_label_pos' );
                }

                $settings[ 'parentType' ] = $field_class->get_parent_type();

                if( 'list' == $settings[ 'parentType' ] && isset( $settings[ 'options' ] ) && is_array( $settings[ 'options' ] ) ){
                    $settings[ 'options' ] = apply_filters( 'ninja_forms_render_options', $settings[ 'options' ], $settings );
                    $settings[ 'options' ] = apply_filters( 'ninja_forms_render_options_' . $field_type, $settings[ 'options' ], $settings );
                }

                $default_value = ( isset( $settings[ 'default' ] ) ) ? $settings[ 'default' ] : null;
                $default_value = apply_filters('ninja_forms_render_default_value', $default_value, $field_type, $settings);
                if ( $default_value ) {

                    $default_value = preg_replace( '/{[^}]}/', '', $default_value );

                    if ($default_value) {
                        $settings['value'] = $default_value;

                        ob_start();
                        do_shortcode( $settings['value'] );
                        $ob = ob_get_clean();

                        if( ! $ob ) {
                            $settings['value'] = do_shortcode( $settings['value'] );
                        }
                    }
                }

                $thousands_sep = $wp_locale->number_format[ 'thousands_sep'];
                $decimal_point = $wp_locale->number_format[ 'decimal_point' ];

                // TODO: Find a better way to do this.
                if ('shipping' == $settings['type']) {
                    $settings[ 'shipping_cost' ] = preg_replace ('/[^\d,\.]/', '', $settings[ 'shipping_cost' ] );
                    $settings[ 'shipping_cost' ] = str_replace( Ninja_Forms()->get_setting( 'currency_symbol' ), '', $settings[ 'shipping_cost' ] );

                    $settings[ 'shipping_cost' ] = str_replace( $decimal_point, '||', $settings[ 'shipping_cost' ] );
                    $settings[ 'shipping_cost' ] = str_replace( $thousands_sep, '', $settings[ 'shipping_cost' ] );
                    $settings[ 'shipping_cost' ] = str_replace( '||', '.', $settings[ 'shipping_cost' ] );
                } elseif ('product' == $settings['type']) {
                    $settings['product_price'] = preg_replace ('/[^\d,\.]/', '', $settings[ 'product_price' ] );
                    $settings['product_price'] = str_replace( Ninja_Forms()->get_setting( 'currency_symbol' ), '', $settings['product_price']);

                    $settings[ 'product_price' ] = str_replace( $decimal_point, '||', $settings[ 'product_price' ] );
                    $settings[ 'product_price' ] = str_replace( $thousands_sep, '', $settings[ 'product_price' ] );
                    $settings[ 'product_price' ] = str_replace( '||', '.', $settings[ 'product_price' ] );

                } elseif ('total' == $settings['type'] && isset($settings['value'])) {
                    $settings['value'] = number_format($settings['value'], 2);
                }

                $settings['element_templates'] = '';//$templates;
                $settings['old_classname'] = $field_class->get_old_classname();
                $settings['wrap_template'] = $field_class->get_wrap_template();

                $fields[] = apply_filters( 'ninja_forms_localize_field_settings_' . $field_type, $settings, $form );

                /*if( 'recaptcha' == $field[ 'settings' ][ 'type' ] ){
                    array_push( NF_Display_Render::$form_uses_recaptcha, $form_id );
                }
                if( 'date' == $field[ 'settings' ][ 'type' ] ){
                    array_push( NF_Display_Render::$form_uses_datepicker, $form_id );
                }
                if( 'starrating' == $field[ 'settings' ][ 'type' ] ){
                    array_push( NF_Display_Render::$form_uses_starrating, $form_id );
                }
                if( isset( $field[ 'settings' ][ 'mask' ] ) && $field[ 'settings' ][ 'mask' ] ){
                    array_push( NF_Display_Render::$form_uses_inputmask, $form_id );
                }
                if( isset( $field[ 'settings' ][ 'mask' ] ) && 'currency' == $field[ 'settings' ][ 'mask' ] ){
                    array_push( NF_Display_Render::$form_uses_currencymask, $form_id );
                }
                if( isset( $field[ 'settings' ][ 'textarea_rte' ] ) && $field[ 'settings' ][ 'textarea_rte' ] ){
                    array_push( NF_Display_Render::$form_uses_rte, $form_id );
                }
                if( isset( $field[ 'settings' ][ 'textarea_media' ] ) && $field[ 'settings' ][ 'textarea_media' ] ){
                    array_push( NF_Display_Render::$form_uses_textarea_media, $form_id );
                }
                // strip all tags except image tags
                if( isset( $field[ 'settings' ][ 'help_text' ] ) &&
                    strip_tags( $field[ 'settings' ][ 'help_text' ], '<img>'
                    ) ){
                    array_push( NF_Display_Render::$form_uses_helptext, $form_id );
                }*/
            }

            if( $cache_updated ) {
                update_option('nf_form_' . $form_id, $form_cache); // Update form cache without duplicate fields.
            }
        }
        $fields = apply_filters( 'ninja_forms_display_fields', $fields );

        // Output Form Container
        //do_action( 'ninja_forms_before_container', $form_id, $form->get_settings(), $form_fields );
        //Ninja_Forms::template( 'display-form-container.html.php', compact( 'form_id' ) );

        $form_id = "$form_id";

        /*array( "Settings"=> $form->get_settings(),
        		"fields"=> $fields 
        	);*/

        return array( "settings"=> $form->get_settings(),
        		"fields"=> $fields,
        	);
}