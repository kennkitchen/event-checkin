<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://kenneth.kitchen
 * @since      1.0.0
 *
 * @package    Csbn_Events
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

global $wpdb;

$charset_collate = $wpdb->get_charset_collate();

$history_table_name = $wpdb->prefix . "csbn_event_history";

$sql = "CREATE TABLE IF NOT EXISTS " . $history_table_name . " (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		event_id INT UNSIGNED,
    		patron_id INT UNSIGNED,
    		attended BOOLEAN DEFAULT FALSE,
    		prize_awarded BOOLEAN DEFAULT FALSE,
            created DATETIME DEFAULT NULL,
            modified DATETIME DEFAULT NULL,
    		INDEX ndx_event_id (event_id),
    		INDEX ndx_patron_id (patron_id)
        ) ENGINE=INNODB " . $charset_collate;

dbDelta($sql);

// flush rewrite cache
flush_rewrite_rules();