<?php 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Disable indexing of CookieLawInfo Cookie data
echo "<!--googleoff: all-->";

if($notify_html==""){ return; } //if filter is applied
echo $notify_html;
$pop_out='';
$cookielaw_nonce       =   wp_create_nonce( 'amp_enhancer_cookie_law_info' );
$pop_content_html_file = AMP_ENHANCER_TEMPLATE_DIR.'cookie-law-info/views/cookie-law-info_popup_content.php';
if(file_exists($pop_content_html_file))
{
    include $pop_content_html_file;
} 
?>
<div class="cli-modal" [class]="showpopup" id="cliSettingsPopup" tabindex="-1" role="dialog" aria-labelledby="cliSettingsPopup" aria-hidden="true">
  <div class="cli-modal-dialog" role="document">
    <div class="cli-modal-content cli-bar-popup">
      <button type="button" class="cli-modal-close" id="cliModalClose" on="tap:AMP.setState({showpopup: 'cli-modal',popupdelay: 'cli-modal-backdrop cli-fade cli-settings-overlay',law_info_bar:'law_info_bar_fade'})" >
        <svg class="" viewBox="0 0 24 24"><path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"></path><path d="M0 0h24v24h-24z" fill="none"></path></svg>
        <span class="wt-cli-sr-only"><?php echo esc_html__('Close', 'cookie-law-info');  ?></span>
      </button>
      <div class="cli-modal-body">
        <?php 
          echo $pop_out;
        ?>
      </div>
    </div>
  </div>
</div>
<div class="cli-modal-backdrop cli-fade cli-settings-overlay" [class]="popupdelay" 
      on="tap:AMP.setState({showpopup: 'cli-modal',popupdelay: 'cli-modal-backdrop cli-fade cli-settings-overlay'})" role="button" tabindex="0" ></div>
<div  class="cli-modal-backdrop cli-fade cli-popupbar-overlay"  [hidden]="hideCookieLaw"></div>
<div class="cli-show cli-blowup law_info_bar"></div>
<input type="hidden" name="amp_enhancer_cookie_law_info" value="<?php esc_attr_e($cookielaw_nonce) ?>"> 
</form>
<?php 
// Re-enable indexing
echo "<!--googleon: all-->";