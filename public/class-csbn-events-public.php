<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://kenneth.kitchen
 * @since      1.0.0
 *
 * @package    Csbn_Events
 * @subpackage Csbn_Events/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Csbn_Events
 * @subpackage Csbn_Events/public
 * @author     Kenneth Kitchen <kenn@kmd.enterprises>
 */
class Csbn_Events_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Csbn_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Csbn_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/csbn-events-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Csbn_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Csbn_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/csbn-events-public.js', array( 'jquery' ), $this->version, false );

	}

	public function show_event_checkin() {
		global $wpdb;

		$events = $wpdb->get_results(
			$wpdb->prepare(
				"select distinct p.ID, p.post_title, " .
					"(select pm.meta_value from " . $wpdb->prefix . "postmeta pm " .
					"where pm.meta_key = '_csbn_event_date_key' " .
					"and pm.post_id = p.ID) csbn_event_date_key, " .
					"(select pm.meta_value from " . $wpdb->prefix . "postmeta pm " .
					"where pm.meta_key = '_csbn_event_time_key' " .
					"and pm.post_id = p.ID) csbn_event_time_key " .
				"from " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta pm " .
				"where p.post_type = 'cpt_event' and p.post_status = 'publish' " .
				"and pm.post_id = p.ID and pm.meta_key like '_csbn%' " .
				"order by p.post_title", null
			)
		);

		$screen = '<div id="container">';
		$screen .= '<label for="selected_event">For Event:</label>';
		$screen .= '<select name="selected_event">';

		foreach ($events as $event) {
			$screen .= '<option value="' . $event->ID . '">' . $event->post_title . '</option>';
		}
		$screen .= '</select><br /><br />';

		$patrons = $wpdb->get_results(
			$wpdb->prepare(
				"select distinct p.ID, p.post_title, " .
					"(select pm.meta_value from " . $wpdb->prefix . "postmeta pm " .
					"where pm.meta_key = '_csbn_patron_first_name_key' " .
					"and pm.post_id = p.ID) csbn_patron_first_name_key, " .
					"(select pm.meta_value from " . $wpdb->prefix . "postmeta pm " .
					"where pm.meta_key = '_csbn_patron_last_name_key' " .
					"and pm.post_id = p.ID) csbn_patron_last_name_key, " .
					"(select pm.meta_value from " . $wpdb->prefix . "postmeta pm " .
					"where pm.meta_key = '_csbn_patron_email_address_key' " .
					"and pm.post_id = p.ID) csbn_patron_email_address_key " .
				"from " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta pm " .
				"where p.post_type = 'cpt_patron' and p.post_status = 'publish'" .
				"and pm.post_id = p.ID and pm.meta_key like '_csbn%'" .
				"order by p.post_title", null
			)
		);

		// create letters row
		for ($x = 'A'; $x < 'Z'; $x++) {
			$screen .= '<a href="#' . $x . '"> ' . $x . '</a>' . ' - ';
		}
		$screen .= '<a href="#Z">Z</a>';
		$screen .= "</div><br />";

		// initialize contacts section
		$screen .= "<div>";

		$current_letter = "-";
		$prior_letter = "-";

		foreach ($patrons as $patron) {
			$current_letter = substr($patron->post_title, 0, 1);
			if ($current_letter != $prior_letter) {
				if ($prior_letter != "-") {
					$screen .= '<p><button class="csbn_button csbn_button4"><a href="#header">Back to Top</a></button> <button class="csbn_button csbn_button4"><a href="#actions-sidebar">Add New</a></button></p>';
				}
				$screen .= '<a name="' . $current_letter . '" class="title">' . strtoupper($current_letter) . '</a>';
			}
			$prior_letter = $current_letter;
			$screen .= <<<EOT
<p><button id="target" value="checkin:$patron->csbn_patron_email_address_key:$patron->ID:event_id" class="csbn_smbutton csbn_button2">Checkin</button> $patron->post_title ($patron->csbn_patron_email_address_key)<br></p>
EOT;
		}

		$screen .= '<p><button class="csbn_button csbn_button4"><a href="#header">Back to Top</a></button> <button class="csbn_button csbn_button4"><a href="#actions-sidebar">Add New</a></button></p>';

		$screen .= "</div>";

		return $screen;
	}

	public function show_event_raffle() {
		return 'Test the plugin';
	}

}
