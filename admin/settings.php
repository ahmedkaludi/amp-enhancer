<?php

function  amp_enhancer_settings_page(){

    if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
    $settings = get_option( 'ampenhancer_settings');
    $tab = amp_enhancer_get_tab('compatibilities', array('compatibilities','features'));
 ?>
 <div class="enhc-container">
  <h1>AMP Enhancer Settings</h1>
    <div class="enhr-comp-cont">
      <h2 class="nav-tab-wrapper">
          <?php
          echo '<a href="' . esc_url(amp_enhancer_admin_link('compatibilities')) . '" class="nav-tab ' . esc_attr( $tab == 'compatibilities' ? 'nav-tab-active' : '') . '">' . esc_html__('Compatibilities', 'amp-enhancer') . '</a>';

          echo '<a href="' . esc_url(amp_enhancer_admin_link('features')) . '" class="nav-tab ' . esc_attr( $tab == 'features' ? 'nav-tab-active' : '') . '">' . esc_html__('Features','amp-enhancer') . '</a>';
            ?>
          </h2>
          <form action="options.php" method="post" enctype="multipart/form-data" class="amp-en-settings-form">
             <?php 
               // Output nonce, action, and option_page fields for a settings page.
                settings_fields( 'amp_enhancer_settings_group' );  

                 echo "<div class='amp-enhancer-compatibilities' ".( $tab != 'compatibilities' ? 'style="display:none;"' : '').">";
                   amp_enhancer_plugin_comaptibilities_list();
                 echo "</div>";

                echo "<div class='amp-enhancer-features' ".( $tab != 'features' ? 'style="display:none;"' : '').">"; 

                // Popup Settings
                 $help = 'Adds Pop-Up for AMP Pages';
                 $docs_url = 'http://ampenhancer.com/docs/article-categories/popup/';
                 $settings_url =  admin_url( 'edit.php?post_type=ampenhancerpopup' );
                 echo   '<div class="en-feature-sub">
                    <h2 class="amp-en-label">POP-UP 
                          '.amp_enhancer_tooltip($help,$docs_url).'
                          </h2> 
                           <label class="switch">
                             <input type="checkbox" '.(isset( $settings['popup'] ) && ($settings['popup'] == 'on' || $settings['popup'] == 1) ? 'checked="checked"' : '').' name="ampenhancer_settings[popup]" />
                              <span class="slider round"></span>
                           </label>';
                   if(isset($settings['popup']) && ($settings['popup'] == 'on' || $settings['popup'] == 1)){
                    echo  '<span class="amp-en-popup-settings"><a href="'.esc_url_raw($settings_url).'"><i class="dashicons dashicons-admin-generic"></i> </a></span>';
                   }
                   echo '</div>'; 

                  // Custom CSS Settings
                 $css_help = 'Adds up Custom CSS for AMP Pages';
                 $css_docs_url = 'http://ampenhancer.com/docs/article-categories/custom-css/';
                 $css_settings_url =  admin_url( 'admin.php?page=amp-enhancer-custom-css' );
                 echo   '<div class="en-feature-sub">
                        <h2 class="amp-en-label">Custom CSS 
                          '.amp_enhancer_tooltip($css_help,$css_docs_url).'
                          </h2> 
                           <label class="switch">
                             <input type="checkbox" '.(isset( $settings['custom_css'] ) && ($settings['custom_css'] == 'on' || $settings['custom_css'] == 1) ? 'checked="checked"' : '').' name="ampenhancer_settings[custom_css]" />
                              <span class="slider round"></span>
                           </label>';
                   if(isset($settings['custom_css']) && ($settings['custom_css'] == 'on' || $settings['custom_css'] == 1)){
                    echo  '<span class="amp-en-popup-settings"><a href="'.esc_url_raw($css_settings_url).'"><i class="dashicons dashicons-admin-generic"></i> </a></span> ';
                   }
                 echo '</div>'; 
                echo "</div>";
               ?>
               <?php 
     // Output save settings button
          submit_button( esc_html__('Save Settings', 'amp-enhancer') );
          ?>
          </form>
    </div>
    <div class="enhr_support">
           <?php amp_enhancer_settings_support_box_html(); ?>
     </div>
      <div class="clearfix"></div>
  </div>
  <?php
}

function amp_enhancer_plugin_comaptibilities_list(){ 

  $woocommerce = $elementor = $contact_form7 = $GDPR_Cookie = $Cookie_Notice = $GDPR_Compliance = $toc_plus = $easy_toc = $lwp_toc = $shortcodes = $wpforms = $ninja_forms = $kkstar  = $cv = $coblocks = $astra = $joinchat = $wp_social =  $foogallery =  $icegram = $helpie = $convertkit = $smartslider3 = $fancy_comments =  $divi = $adapta_RGPD = $UAGB = $nj_tables = $wpcf7_redirect = $wpfront_bar = $ux_builder = $beaver = false;
    if(function_exists('WC')){
     $woocommerce = true;
    }
    if(class_exists('\Elementor\Plugin')){
     $elementor = true;
    }
    if(class_exists('WPCF7_ContactForm')){
     $contact_form7 = true;
    }
    if(class_exists('Cookie_Law_Info')){
     $GDPR_Cookie = true;
    }
    if(class_exists('Cookie_Notice')){
     $Cookie_Notice = true;
    }
    if(function_exists('gdpr_cookie_compliance_load_libs')){
     $GDPR_Compliance = true;
    }
    if(class_exists('toc')){
     $toc_plus = true;
    }
    if(class_exists('ezTOC_Option')){
     $easy_toc = true;
    }
    if(class_exists('lwptocAutoloader')){
      $lwp_toc = true;
    }
    if(class_exists('Shortcodes_Ultimate_Shortcodes')){
      $shortcodes = true;
    }
     if(function_exists('wpforms')){
      $wpforms = true;
    } 
    if(function_exists('Ninja_Forms')){
      $ninja_forms = true;
    } 
    if(function_exists('kksr_freemius')){
      $kkstar = true;
    }
    if(class_exists('PT_CV_Html')){
      $cv = true;
    }
    if(function_exists('coblocks')){
      $coblocks = true;
    }
    if(defined('ASTRA_EXT_VER') || defined('ASTRA_THEME_VERSION')){
      $astra = true;
    }
    if(class_exists('JoinChat')){
      $joinchat = true;
    }
    if(class_exists('QLWAPP_Frontend')){
      $wp_social = true;
    }
    if(function_exists('foogallery_fs')){ 
      $foogallery = true;
    }
    if(class_exists('Icegram')){
      $icegram = true;
    }
    if(class_exists('Helpie_FAQ_Plugin')){
      $helpie = true;
    }
    if(class_exists('WP_ConvertKit')){
      $convertkit = true;
    }
    if(defined('SMARTSLIDER3_LIBRARY_PATH')){
       $smartslider3 = true;
    }
    if(defined('HEATEOR_FFC_VERSION')){
       $fancy_comments = true;
    }
    if ( function_exists( 'et_setup_theme' ) ) {
         $divi = true;
    }
    if(class_exists('Adapta_RGPD')){ 
      $adapta_RGPD = true;
    }

    if(class_exists('UAGB_Loader')){
      $UAGB = true;
    }
    if(function_exists('ninja_tables_boot')){
      $nj_tables = true;
    }
    if(class_exists('WPCF7r_Submission')){ 
      $wpcf7_redirect = true;
    }
    if(class_exists('WPFront_Notification_Bar')){
       $wpfront_bar = true;
    }
    if(function_exists('ux_builder')){
       $ux_builder = true;
    }
    if(class_exists('FLBuilderLoader')){ 
      $beaver = true;
    }

  ?>
  <div class="enhancer-nosetup">
                <h3 class="enhc-notice-title">No Setup Required</h3>
                <span class="enhc-notice-cont">It will now Automatically include the  features of the compatible third party plugins and starts working.</span>
            </div>
            <div class="enhr-compatible-section">
                <h2 class="enhr-comp-title">Compatibilities :</h2>
              <table class="enhr-comp-table">
                  <tr>
                    <th>Plugin</th>
                    <th>Status</th>
                  </tr>
                    <tr>
                    <td>WooCommerce</td>
                        <?php if($woocommerce == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Elementor</td>
                        <?php if($elementor == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Contact Form 7</td>
                        <?php if($contact_form7 == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>GDPR Cookie Consent</td>
                        <?php if($GDPR_Cookie == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Cookie Notice</td>
                        <?php if($Cookie_Notice == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                   <tr>
                    <td>Divi Theme</td>
                        <?php if($divi == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>GDPR Cookie Compliance (CCPA, PIPEDA ready)</td>
                        <?php if($GDPR_Compliance == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Table of Contents Plus</td>
                        <?php if($toc_plus == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Easy Table of Contents</td>
                        <?php if($easy_toc == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>LuckyWP Table of Contents</td>
                        <?php if($lwp_toc == true){?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Shortcodes Ultimate</td>
                        <?php if($shortcodes == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>WPForms</td>
                        <?php if($wpforms == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Ninja Forms</td>
                        <?php if($ninja_forms == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr> 
                  <tr>
                    <td>kk Star Ratings</td>
                        <?php if($kkstar == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Content Views– Post Grid & Filter for WordPress</td>
                        <?php if($cv == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>CoBlocks</td>
                        <?php if($coblocks == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Astra Pro</td>
                        <?php if($astra == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Join.chat</td>
                        <?php if($joinchat == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>WP Social Chat</td>
                        <?php if($wp_social == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>FooGallery</td>
                        <?php if($foogallery == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Icegram</td>
                        <?php if($icegram == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Helpie FAQ</td>
                        <?php if($helpie == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>ConvertKit</td>
                        <?php if($convertkit == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Smart Slider 3</td>
                        <?php if($smartslider3 == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Fancy Comments WordPress</td>
                        <?php if($fancy_comments == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Adapta RGPD</td>
                        <?php if($adapta_RGPD == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Ultimate Addons for Gutenberg</td>
                        <?php if($UAGB == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Ninja Tables</td>
                        <?php if($nj_tables == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Redirection for Contact Form 7</td>
                        <?php if($wpcf7_redirect == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>WPFront Notification Bar</td>
                        <?php if($wpfront_bar == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                   <tr>
                    <td>UX Builder(Flatsome Theme)</td>
                        <?php if($ux_builder == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                  <tr>
                    <td>Beaver Builder Plugin (Pro Version)</td>
                        <?php if($beaver == true){ ?>
                        <td><span class="dashicons dashicons-yes-alt enhr-yes"></span>Active</td>
                        <?php }else{ ?>
                        <td>Inactive</td>  
                        <?php } ?>
                  </tr>
                </table>
            </div>
    <?php 
}

function amp_enhancer_settings_support_box_html(){ ?>

  <div class="postbox">
                <h2 class="enhr-hndle">
                    <span class="dashicons dashicons-email-alt"></span>
                    <span>Technical Support</span>
                </h2>
                <div class="enhr-inside">
                    <p>
                       If you have any queries, you can directly connect with our support developers by submitting a ticket
                    </p>
                    <p>
                    Our team will get back to your email address very shortly
                    </p>
                    <a href="http://ampenhancer.com/contact-us/">Submit a Ticket »</a>               </div>
            </div>

            <div class="postbox">
                <h2 class="enhr-hndle">
                    <span class="dashicons dashicons-star-filled"></span>
                    <span>Rate Us</span>
                </h2>
                <div class="enhr-inside">
                    <p>
                       If you have got the features which you are looking for in our plugin, then please rate us a 5 star review on WordPress.org.</p>
                    <p>This will help spread the word out about this plugin and will encourage us to continue the development.</p>
                    <p>Much appreciated, thank you very much.</p>
                    <a href="https://wordpress.org/support/plugin/amp-enhancer/reviews/?rate=5#new-post" target="_blank">Give Us a 5 star</a>               </div>
            </div>
            <?php
}


function amp_enhancer_admin_link($tab = ''){ 
    
  $page = 'amp-enhacer-settings';

    $link = admin_url( 'admin.php?page=' . $page );


  if ( $tab ) {
    $link .= '&tab=' . $tab;
  }

  return esc_url($link);
}

function amp_enhancer_get_tab( $default = '', $available = array() ) {

  $tab = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : $default;
        
  if ( ! in_array( $tab, $available ) ) {
    $tab = $default;
  }

  return $tab;
}

function amp_enhancer_tooltip($help,$docs_url){
  $tooltipHtml = '<span class="am-en-tooltip"><i class="dashicons dashicons-editor-help"></i> 
              <span class="am-en-help-subtitle">'.esc_html__($help, 'amp-enhancer').'
              <a href="'.esc_url($docs_url).'" target="_blank">'.esc_html__('Learn more', 'amp-enhancer').'</a>'.'
              </span>
          </span>';

  return $tooltipHtml;
}

/*
  WP Settings API
*/
add_action('admin_init', 'amp_enhancer_settings_init');

function amp_enhancer_settings_init(){

  register_setting( 'amp_enhancer_settings_group', 'ampenhancer_settings' );
  register_setting( 'amp_enhancer_custom_css_group', 'ampenhancer_custom_css' );

}

require_once(AMP_ENHANCER_PLUGIN_DIR.'admin/amp-enhancer-popup/amp-enhancer-popup-admin.php');
require_once(AMP_ENHANCER_PLUGIN_DIR.'admin/amp-enhancer-custom-css/amp-enhancer-custom-css.php');