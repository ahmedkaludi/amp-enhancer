<?php 

add_action('wp_head','amp_enhancer_cookie_law_info_css',999);
function amp_enhancer_cookie_law_info_css(){
		$options = Cookie_Law_Info::get_settings();

		$positioning = 'bottom: 0px;';
		$position = 'fixed'; 
		if(isset($options['notify_position_vertical']) && $options['notify_position_vertical'] == 'top'){
            $positioning = 'top: 0px;';
			if(isset($options['header_fix']) && $options['header_fix'] != true){
				$position = 'absolute';
			}
	    }
	    if(isset($options['notify_position_horizontal']) && $options['notify_position_horizontal'] == 'left'){
	    	$revoke_pos = 'left';
	    }else{
	    	$revoke_pos = 'right';
	    }

	   	if(isset($options['showagain_x_position']) ){
	    	$x_position = $options['showagain_x_position'];
	    }
	    
?>
<style>
    <?php if(isset($options['cookie_bar_as']) && ($options['cookie_bar_as'] == 'banner') ){ ?>
			#cookie-law-info-bar .cli_messagebar_head{
		    text-align: left;
		    /* padding-left: 15px; */
		    margin-bottom: 5px;
		    margin-top: 0px;
		    font-size: 16px;
		    }
			.amp-cookie-law-notice #cookie-law-info-bar.law_info_bar{
				opacity: 0.1;
			}
		    #cookie-law-info-bar{
			   	display: block;
		        opacity: 1;
		        <?php esc_attr_e($positioning); ?>         
			}
		    #cookie-law-info-bar{
		    padding: 14px 25px;
		    }
		    .cli-popupbar-overlay{
		    	display: none;
		    }
    <?php } ?>

    <?php if(isset($options['cookie_bar_as']) && ($options['cookie_bar_as'] == 'popup') ){ ?>
	    #cookie-law-info-bar{
			    display: block;
		        opacity: 1;
				width: 500px;
			    height: auto;
			    max-height: 617px;
			    top: 50%;
			    left: 50%;
			    margin-left: -250px;
			    margin-top: -81.5px;
			    overflow: auto;
		}
		#cookie-law-info-bar {
 		   animation: blowUpModal .5s cubic-bezier(.165,.84,.44,1) forwards;
		}
	    #cookie-law-info-bar{
	       padding: 32px 45px;
	    }
		 #cookie-law-info-bar .cli-bar-message {
		    width: 100%;
		}
		#cookie-law-info-bar .cli-bar-container {
		    display: block;
		}
		#cookie-law-info-bar .cli-bar-btn_container {
		    margin-top: 8px;
		    margin-left: 0px;
		}
		@media (max-width: 768px) {
	     #cookie-law-info-bar{
		    width: 300px;
		    height: auto;
		    max-height: 211px;
		    margin-left: -150px;
		    margin-top: -99.5px;
		   }
	    }
    <?php } ?>

	#cookie-law-info-bar[data-cli-style="cli-style-v2"][data-cli-type="widget"]{
	    padding:32px 30px;
	}
	#cookie-law-info-bar[data-cli-style="cli-style-v2"][data-cli-type="popup"] {
	    padding: 32px 45px;
	}

	#cookie-law-info-bar,#cookie-law-info-again{
	        background-color: <?php echo amp_enhancer_sanitize_color($options['background']); ?>;
	        color: <?php echo amp_enhancer_sanitize_color($options['text']); ?>;
	        font-family: <?php esc_attr_e($options['font_family']); ?>;
		    position: <?php esc_attr_e($position); ?>; 
	        
	}
	#cookie-law-info-again{
		    width: auto;
		    <?php esc_attr_e($revoke_pos); ?> : <?php esc_attr_e($x_position); ?>;
		    <?php esc_attr_e($positioning); ?>  
	}
	.cookie_action_close_header,.cookie_action_close_header:hover{
			display: inline-block;
		    color: <?php echo amp_enhancer_sanitize_color($options['button_1_link_colour']); ?>;
		    background-color: <?php echo amp_enhancer_sanitize_color($options['button_1_button_colour']); ?>;
	}
	.cookie_action_close_header_reject,.cookie_action_close_header_reject:hover{
		    color: <?php echo amp_enhancer_sanitize_color($options['button_3_link_colour']); ?>;
            background-color: <?php echo amp_enhancer_sanitize_color($options['button_3_button_colour']); ?>;
	}
	.cli_settings_button,.cli_settings_button:hover{
		    color: <?php echo amp_enhancer_sanitize_color($options['button_4_link_colour']); ?>;
	}

	.cli-modal.cli-blowup {
	    z-index: 999999;
	    transform: scale(1);
	    opacity: 1;
     }
    .cli-cont.extend .cli-privacy-content{
        max-height: none;
     }
    .cli-cont.collapse .cli-privacy-content{
        max-height: 60px;
    }
    .cli-tab-active .cli-tab-content{
     	display: block;
    }
    .cli-tab-active .cli-tab-header a:before {
	    transform: rotate(45deg);
    }
	.check-enable {
	    position: absolute;
	    right: 60px;
	    top: 0px;
	}
	#cli-tab-section{
		position: relative;
		margin-bottom: 5px;
	}
	.cli-necessary-caption {
	    display: inline-block;
	    margin-top: 14px;
	    margin-right: 11px;
	}
 	.cli_cookie_close_button{
	    text-decoration: none;
	    color: #333333;
	    font-size: 22px;
	    line-height: 22px;
	    cursor: pointer;
	    position: absolute;
	    right: 10px;
	    top: 5px;
	    background-color: unset;
	    padding: 0px;
	}
	.cli_cookie_close_button:hover{
		background-color: unset;
		color: #333333;
	}
	.non-button{
		padding: 4px;
	    font-size: smaller;
	    margin-left: 4px;
	    position: absolute;
	    left: 40px;
	}
	#cookie-law-info-bar .wt-cli-element {
		 display: block;
   	}

	.cli-ccpa-button-cancel {
	    background: transparent;
	    color: #61a229;
	}
	.cli-alert-dialog-buttons button {
	    -webkit-box-flex: 1;
	    -ms-flex: 1;
	    flex: 1;
	    -webkit-appearance: none;
	    -moz-appearance: none;
	    appearance: none;
	    margin: 4px;
	    padding: 8px 16px;
	    border-radius: 64px;
	    cursor: pointer;
	    font-weight: 700;
	    font-size: 12px;
	    text-align: center;
	    text-transform: capitalize;
	    border: 2px solid #61a229;
	}
	.cli-ccpa-button-confirm {
	    background-color: #61a229;
	    color: #ffffff;
    }
    #cLiCcpaOptoutPrompt .cli-modal-dialog {
   		max-width: 320px;
	}
	@media (max-width: 985px) {
	     #cookie-law-info-bar{
	        padding: 25px 25px;
	    }
	}
	@media (max-width: 320px) {
		.check-enable {
		    right: 40px;
		}
    }
</style>

<?php }