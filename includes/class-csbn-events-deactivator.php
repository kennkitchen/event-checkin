<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://kenneth.kitchen
 * @since      1.0.0
 *
 * @package    Csbn_Events
 * @subpackage Csbn_Events/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Csbn_Events
 * @subpackage Csbn_Events/includes
 * @author     Kenneth Kitchen <kenn@kmd.enterprises>
 */
class Csbn_Events_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// flush rewrite cache
		flush_rewrite_rules();

	}

}
