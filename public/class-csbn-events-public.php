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
				"select p.ID, p.post_title, pm.meta_key, pm.meta_value " .
				"from " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta pm " .
				"where p.post_type = 'cpt_event' and p.post_status = 'publish' " .
				"and pm.post_id = p.ID and pm.meta_key like '_csbn%' " .
				"order by p.post_title", null
			)
		);

		$event_display_name = $event_date = $event_time = "";

		foreach ($events as $event) {
			if ($event_display_name == "") {
				$event_display_name = $event->post_title;
			}
			$event_display_name = $event->post_title;
			switch ($event->meta_key) {
				case "_csbn_event_date_key":
					$event_date = strtotime($event->meta_value);
					break;
				case "_csbn_event_time_key":
					$event_time = $event->meta_value;
					break;
			}
		}

		$patrons = $wpdb->get_results(
			$wpdb->prepare(
				"select p.ID, p.post_title, pm.meta_key, pm.meta_value " .
				"from " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta pm " .
				"where p.post_type = 'cpt_patron' and p.post_status = 'publish' " .
				"and pm.post_id = p.ID and pm.meta_key like '_csbn%' " .
				"order by p.post_title", null
			)
		);

		// create letters row
		$screen = '<div id="container">';

		for ($x = 'A'; $x < 'Z'; $x++) {
			$screen .= '<a href="#' . $x . '"> ' . $x . '</a>' . ' - ';
		}
		$screen .= '<a href="#Z">Z</a>';
		$screen .= "</div><br />";

		// initialize contacts section
		$screen .= "<div>";
		$this_patron = "-first";

		$current_letter = "-";
		$prior_letter = "";

		$patron_display_name = $patron_first_name = $patron_last_name = $patron_email_address = "";

		foreach ($patrons as $patron) {
			if ( substr($this_patron, 0, 1) != $current_letter ) {
				$prior_letter = $current_letter;
				$current_letter = substr($this_patron, 0, 1);
				if ($prior_letter != "-") {
					$screen .= '<p><button class="csbn_button csbn_button4"><a href="#header">Back to Top</a></button> <button class="csbn_button csbn_button4"><a href="#actions-sidebar">Add New</a></button></p>';
				}
				$screen .= '<a name="' . $current_letter . '" class="title">' . strtoupper($current_letter) . '</a>';
			}
			if ($this_patron == "-first") {
				$this_patron = $patron->post_title;
			}
			if ($this_patron != $patron->post_title) {
				$screen .= <<<EOT
<p><button id="target" value="checkin:$patron_email_address" class="csbn_smbutton csbn_button2">Checkin</button> $patron_display_name ($patron_email_address)<br></p>
EOT;
				$patron_first_name =  "";
				$patron_last_name =  "";
				$patron_email_address = "";
				$this_patron = $patron->post_title;
			} else {
				$patron_display_name = $patron->post_title;
				switch ($patron->meta_key) {
					case "_csbn_patron_first_name_key":
						$patron_first_name = $patron->meta_value;
						break;
					case "_csbn_patron_last_name_key":
						$patron_last_name = $patron->meta_value;
						break;
					case "_csbn_patron_email_address_key":
						$patron_email_address = $patron->meta_value;
						break;
				}
			}
		}

		$screen .= <<<EOT
<p><button id="target" value="checkin:$patron_email_address" class="csbn_smbutton csbn_button2">Checkin</button> $patron_display_name ($patron_email_address)<br></p>
EOT;
		$screen .= '<p><button class="csbn_button csbn_button4"><a href="#header">Back to Top</a></button> <button class="csbn_button csbn_button4"><a href="#actions-sidebar">Add New</a></button></p>';
		$screen .= "</div>";


		return $screen;
	}

	public function show_event_raffle() {
		return 'Test the plugin';
	}

}
