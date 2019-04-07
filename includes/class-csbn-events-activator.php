<?php

/**
 * Fired during plugin activation
 *
 * @link       https://kenneth.kitchen
 * @since      1.0.0
 *
 * @package    Csbn_Events
 * @subpackage Csbn_Events/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Csbn_Events
 * @subpackage Csbn_Events/includes
 * @author     Kenneth Kitchen <kenn@kmd.enterprises>
 */
class Csbn_Events_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
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

	}

}
