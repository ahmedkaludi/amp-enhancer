<?php
ob_start();
$overview = get_option('cookielawinfo_privacy_overview_content_settings', array('privacy_overview_content' => '', 'privacy_overview_title' => '',));
$cli_always_enable_text = esc_html__('Always Enabled', 'cookie-law-info');
$cli_enable_text = esc_html__('Enabled', 'cookie-law-info');
$cli_disable_text = esc_html__('Disabled', 'cookie-law-info');
$cli_privacy_readmore = '<a class="cli-privacy-readmore" [hidden]="hideMore" role="button" tabindex="0" on="tap:AMP.setState({extendread: \'cli-cont extend\',hideLess:false,hideMore:true})">' . esc_html__('Show more', 'cookie-law-info') . '</a>';
$third_party_cookie_options = get_option('cookielawinfo_thirdparty_settings');
$necessary_cookie_options = get_option('cookielawinfo_necessary_settings');
$overview_title = sanitize_text_field(stripslashes(isset($overview['privacy_overview_title']) ? $overview['privacy_overview_title'] : ''));
$privacy_overview_content = wp_kses_post(isset($overview['privacy_overview_content']) ? $overview['privacy_overview_content'] : '');
$privacy_overview_content = nl2br($privacy_overview_content);
$privacy_overview_content = do_shortcode(stripslashes($privacy_overview_content));
$content_length = strlen(strip_tags($privacy_overview_content));
$overview_title = trim($overview_title);

$cookie_categories = amp_enhancer_get_cookielawinfo_categories();
$js_blocking_enabled = Cookie_Law_Info::wt_cli_is_js_blocking_active();
$thirdparty_default_state = amp_enhancer_wt_cli_check_thirdparty_state();
$wt_cli_is_thirdparty_enabled = (bool) (isset($third_party_cookie_options['thirdparty_on_field'])  ? Cookie_Law_Info::sanitise_settings('thirdparty_on_field', $third_party_cookie_options['thirdparty_on_field']) : false);
?>
<div class="cli-container-fluid cli-tab-container">
    <div class="cli-row">
        <div class="cli-col-12 cli-align-items-stretch cli-px-0">
            <div class="cli-privacy-overview">
                <?php
                
                if (isset($overview_title) === true && $overview_title !== '') {
                    if (has_filter('wt_cli_change_privacy_overview_title_tag')) {
                        echo apply_filters('wt_cli_change_privacy_overview_title_tag', esc_html($overview_title), '<h4>', '</h4>');
                    } else {
                        echo "<h4>" . esc_html($overview_title) . "</h4>";
                    }
                }
                ?>
                <div class="cli-cont" [class]="extendread">
                <div  class="cli-privacy-content" >
                    <div class="cli-privacy-content-text"><?php echo esc_html($privacy_overview_content); ?></div>
                </div>
                </div>
                <a class="cli-privacy-readmore" [hidden]="hideMore" role="button" tabindex="0" on="tap:AMP.setState({extendread: 'cli-cont extend',hideLess:false,hideMore:true})"><?php echo esc_html__('Show more', 'cookie-law-info') ?></a>
                <a class="cli-privacy-readmore" hidden [hidden]="hideLess"  role="button" tabindex="0" on="tap:AMP.setState({extendread: 'cli-cont collapse',hideMore:false,hideLess:true})"><?php echo esc_html__('Show Less', 'cookie-law-info') ?></a>
            </div>
        </div>
        <div class="cli-col-12 cli-align-items-stretch cli-px-0 cli-tab-section-container">
            <?php foreach ($cookie_categories as $key => $value) {
                if ($key === "necessary") {
                    $cli_switch = '<div class="wt-cli-necessary-checkbox">
                        <input type="checkbox" class="cli-user-preference-checkbox"  id="wt-cli-checkbox-'.esc_attr($key).'" data-id="checkbox-'.esc_attr($key).'" checked="checked"  />
                        <label class="form-check-label" for="wt-cli-checkbox-'.esc_attr($key).'">'.$value.'</label>
                    </div>
                    <span class="cli-necessary-caption">' . $cli_always_enable_text . '</span> ';
                    $cli_cat_content = wp_kses_post(stripslashes(isset($necessary_cookie_options['necessary_description']) ? $necessary_cookie_options['necessary_description'] : ''));
                } else {
                    
                    $checked = false;
                    if( $js_blocking_enabled === true ) {
                        if(isset($thirdparty_default_state) && $thirdparty_default_state === true) {
                            $checked = true;     
                        }
                    } else {
                        if (isset($_COOKIE["cookielawinfo-checkbox-$key"]) && $_COOKIE["cookielawinfo-checkbox-$key"] == 'yes') {
                            $checked = true;
                        } else if (!isset($_COOKIE["cookielawinfo-checkbox-$key"])) {
    
                            $checked = true;
                            if ($key === 'non-necessary' && !$thirdparty_default_state) {
                                $checked = false;
                            }
                        }
                    }
                    $cli_switch =
                        '<div class="cli-switch">
                        <input type="checkbox" on="change:AMP.setState({non_checked:false,checked_value:(event.checked ? \'yes\' : \'no\')})"  id="wt-cli-checkbox-' . esc_attr($key) . '" class="cli-user-preference-checkbox"  data-id="checkbox-' . esc_attr($key) . '" ' . checked( $checked, true, false ) . ' />
                        <label for="wt-cli-checkbox-' . esc_attr($key) . '" class="cli-slider" data-cli-enable="' . $cli_enable_text . '" data-cli-disable="' . $cli_disable_text . '"><span class="wt-cli-sr-only">' . $value . '</span></label>
                        <button hidden [hidden]="non_checked" class="non-button" type="submit" name="thirdparty_cookie" value="" [value]="checked_value">Apply</button>
                    </div>';
                    $cli_cat_content = wp_kses_post(stripslashes(isset($third_party_cookie_options['thirdparty_description']) ? $third_party_cookie_options['thirdparty_description'] : ''));
                }
            ?>
                <?php
                if ($key === "non-necessary" && $wt_cli_is_thirdparty_enabled == false) {
                    echo '';
                } else {
                      $bindkey = str_replace('-', '', $key);
                     ?>
                    <div id="cli-tab-section" class="cli-tab-section" [class]="ext<?php echo $bindkey; ?>">
                        <div class="cli-tab-header" role="button"  tabindex="0" on="tap:AMP.setState({ext<?php echo esc_attr($bindkey); ?>: 'cli-tab-section cli-tab-active',hide<?php echo esc_attr($bindkey); ?>:true,open<?php echo esc_attr($bindkey); ?>:false})" [hidden]="hide<?php echo esc_attr($bindkey); ?>">
                            <a role="button"  tabindex="0" class="cli-nav-link cli-settings-mobile"  data-target="<?php echo esc_attr($key); ?>" data-toggle="cli-toggle-tab">
                                <?php echo $value ?>
                            </a>
                        </div>
                         <div class="cli-tab-header" hidden role="button" [hidden]="open<?php echo esc_attr($bindkey); ?>" tabindex="0" on="tap:AMP.setState({ext<?php echo esc_attr($bindkey); ?>: 'cli-tab-section',hide<?php echo esc_attr($bindkey); ?>:false,open<?php echo esc_attr($bindkey); ?>:true})">
                            <a role="button"  tabindex="0" class="cli-nav-link cli-settings-mobile"  data-target="<?php echo esc_attr($key); ?>" data-toggle="cli-toggle-tab">
                                <?php echo $value ?>
                            </a>
                        </div>

                        <div class="cli-tab-header" hidden role="button"  tabindex="0" on="tap:AMP.setState({ext<?php echo esc_attr($bindkey); ?>: 'cli-tab-section cli-tab-active'})">
                            <a role="button"  tabindex="0" class="cli-nav-link cli-settings-mobile" on="tap:AMP.setState({ext<?php echo esc_attr($bindkey); ?>: 'cli-tab-section cli-tab-active'})" data-target="<?php echo esc_attr($key); ?>" data-toggle="cli-toggle-tab">
                                <?php echo $value ?>
                            </a>
                        </div>
                           <span class="check-enable">
                            <?php echo $cli_switch; ?>   
                           </span>
                        <div class="cli-tab-content">
                            <div class="cli-tab-pane cli-fade" data-id="<?php echo esc_attr($key); ?>">
                                <p><?php echo do_shortcode($cli_cat_content, 'cookielawinfo-category'); ?></p>
                            </div>
                        </div>
                    </div>
            <?php }
            } ?>

        </div>
    </div>
</div>
<span class="cli-cont extend collapse cli-privacy-content cli-tab-section cli-tab-active" ></span>
<?php $pop_out = ob_get_contents();
ob_end_clean();
