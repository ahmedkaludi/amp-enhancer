<?php
/**
 * Uninstall AMP Enhancer
 *
 */// if uninstall.php is not called by WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
 delete_option( 'amp_enhancer_install_date' );
 delete_option( 'amp_enhancer_review_rating' );