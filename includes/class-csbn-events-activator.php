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
		// flush rewrite cache
		flush_rewrite_rules();

	}

}
