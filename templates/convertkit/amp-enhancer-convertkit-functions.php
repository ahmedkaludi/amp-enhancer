<?php
function amp_enhancer_convertkit_shortcode($attributes, $content = null){
  $form_id = isset($attributes['form']) ? $attributes['form'] : '';
  $formUrl   = admin_url('admin-ajax.php?action=amp_enhancer_convertkit_handle');
   $formUrl   = preg_replace('#^https?:#', '', $formUrl);
   $convertkit_nonce       =   wp_create_nonce( 'amp_enhancer_convertkit' );
 ?>
    <form action-xhr="<?php echo esc_url_raw($formUrl); ?>" class="seva-form formkit-form" method="post">

      <input type="text" class="formkit-input" name="email_address" style="color:#8b8b8b;border-color:#dde0e4;font-weight:400" aria-label="Email Address" placeholder="Email Address" required="">

      <input type="hidden" name="formid" value="<?php  esc_attr_e($form_id); ?>">
      <input type="hidden" name="amp_enhancer_convertkit" value="<?php esc_attr_e($convertkit_nonce) ?>">
      <button data-element="submit" class="formkit-submit formkit-submit" style="color:#ffffff;background-color:#f6a6ab;border-radius:3px;font-weight:700;padding-top: 15px;padding-bottom: 15px;"><div class="formkit-spinner"><div></div><div></div><div></div></div><span class="">Subscribe</span></button>
      <div submit-success>
      <template type="amp-mustache">
        <span class="form-res" style="color: green;"> Thanks for Subscribing, We have sent you a comfirmation email please confirm.
      </template>
    </div>
    <div submit-error>
      <template type="amp-mustache">
        <span class="form-res" style="color: red;">Something went wrong please try again.</span>
      </template>
    </div>
    </form>
    <?php

}

 add_action('wp_ajax_amp_enhancer_convertkit_handle','amp_enhancer_convertkit_handle');
 add_action('wp_ajax_nopriv_amp_enhancer_convertkit_handle','amp_enhancer_convertkit_handle');

 function amp_enhancer_convertkit_handle(){

   header("access-control-allow-credentials:true");
    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
    header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
    $siteUrl = parse_url(get_site_url());
    header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']) . '://' . esc_attr($siteUrl['host']));
    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
    header("Content-Type:application/json");
    if(!wp_verify_nonce($_POST['amp_enhancer_convertkit'],'amp_enhancer_convertkit') ){

    header('HTTP/1.1 500 FORBIDDEN');
      header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
      header("Content-Type:application/json");
    echo wp_json_encode( 'Sorry, your nonce did not verify.' );
      wp_die();

  }else{
    
      $settings = get_option('_wp_convertkit_settings');
      $api_key = isset($settings['api_key']) ? $settings['api_key'] : '';
      if(!empty($api_key) && isset($_POST['formid'])){
          $formid  = $_POST['formid'];
          $email_address = isset($_POST['email_address']) ? $_POST['email_address'] : '';
          $url = 'https://api.convertkit.com/v3/forms/'.$formid.'/subscribe';
          $jsonData = array(
                      'api_key'  => esc_attr($api_key),
                      'email'    => esc_attr($email_address),
                      'state'    =>'active',
                  );

          $response = wp_remote_post( 
                                        $url, 
                                        array('headers' => array('Content-Type' => 'application/json;charset=utf-8'),
                                            'body'        => json_encode($jsonData),
                                      ));
            $response_code = wp_remote_retrieve_response_code($response);
                if($response_code == 200 ){
                echo wp_json_encode( 'Success..' );
                      wp_die();
                }else{
                    header('HTTP/1.1 500 FORBIDDEN');
                    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
                    header("Content-Type:application/json");
                    echo wp_json_encode( 'Form not submitted try again..' );
                    wp_die();

                }
         }
    }

 }