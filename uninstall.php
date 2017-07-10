<?php
/**
 * Handles uninstallation logic.
 **/
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}


require_once 'includes/ucf-post-list-config.php';

// Delete options
UCF_Post_List_Config::delete_options();
