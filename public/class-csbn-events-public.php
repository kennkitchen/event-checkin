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
		global $wp, $wp_query, $wpdb;

		$this_page = home_url(add_query_arg(array(), $wp->request));
		$parameter_string = $wp_query->get('custom-form', null);

		if ((!$parameter_string) || ('initial' == $parameter_string)) {
			$event_add_meta_nonce = wp_create_nonce( 'event_add_meta_form_nonce' );

			$admin_url = esc_url( admin_url( 'admin-post.php?custom-form=initial' ) );

			$events = $wpdb->get_results(
				"select distinct p.ID, p.post_title, " .
				"(select pm.meta_value from " . $wpdb->prefix . "postmeta pm " .
				"where pm.meta_key = '_csbn_event_date_key' " .
				"and pm.post_id = p.ID) csbn_event_date_key, " .
				"(select pm.meta_value from " . $wpdb->prefix . "postmeta pm " .
				"where pm.meta_key = '_csbn_event_time_key' " .
				"and pm.post_id = p.ID) csbn_event_time_key " .
				"from " . $wpdb->prefix . "posts p " .
				"where p.post_type = 'cpt_event' and p.post_status = 'publish' " .
				"order by p.post_title"
			);

			$screen = <<<EOT
<div id="container">
	<form action="$admin_url" method="post">
		<input type="hidden" name="action" value="event_form">
		<input type="hidden" name="event_add_meta_form_nonce" value="$event_add_meta_nonce" />
		<input type="hidden" name="event_redirect_url" value="$this_page" />
<label for="selected_event">For Event:</label><br />
<select name="selected_event">
EOT;

			foreach ($events as $event) {
				$screen .= '<option value="' . $event->ID . '">' . $event->post_title . '</option>';
			}

			$screen .= <<<EOT
		</select><br /><br />
		<input type="submit" value="Submit">
	</form> 
EOT;


		} elseif ($parameter_string) {
			$patrons = $wpdb->get_results(
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
					"from " . $wpdb->prefix . "posts p " .
					"where p.post_type = 'cpt_patron' and p.post_status = 'publish'" .
					"order by p.post_title"
			);

			$event_name = $wpdb->get_var("select p.post_title from "
				. $wpdb->prefix . "posts p " . "where p.post_type = 'cpt_event' "
				. "and p.ID = '" . $parameter_string . "'" );

			$screen = '<h2>Checkin for Event: ' . $event_name . '</h2><br />';

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
					$screen .= '<h3><a name="' . $current_letter . '" class="title">' . strtoupper($current_letter) . '</a></h3>';
				}
				$prior_letter = $current_letter;
				$screen .= <<<EOT
<p><button value="checkin:$patron->csbn_patron_email_address_key:$patron->ID:$parameter_string" class="csbn_smbutton csbn_button2">Checkin</button> $patron->post_title ($patron->csbn_patron_email_address_key)<br></p>
EOT;
			}

			$screen .= '<p><button class="csbn_button csbn_button4"><a href="#header">Back to Top</a></button></p>';
			$screen .= '<hr><input type="text" name="add_fname" placeholder="First Name"/> <input type="text" name="add_lname" placeholder="Last Name"/> <input type="text" name="add_email" placeholder="Email"/><br />';
			$screen .= '<button id="addnew" class="csbn_button csbn_button4">Add New</button>';
			$screen .= "</div>";

		}



		/*
		else {
			$screen = <<<EOT
	<h1>Shortcode-Generated Page Response</h1>
	<p>Your form was successfully submitted!</p>
	<hr>
	<a href="$this_page">Return to Form Page</a>
EOT;
		}
		*/

		return $screen;
	}

	public function event_form_response() {
		global $wp, $wpdb;

		$redirect_url = sanitize_text_field( $_POST['event_redirect_url'] ) . '?custom-form=redirect';

		if( isset( $_POST['event_add_meta_form_nonce'] ) && wp_verify_nonce( $_POST['event_add_meta_form_nonce'], 'event_add_meta_form_nonce') ) {

			//$event_form_state = sanitize_text_field( $_POST['event_form_state'] );

			// sanitize the input
			$selected_event = sanitize_text_field( $_POST['selected_event'] );

			$redirect_url = sanitize_text_field( $_POST['event_redirect_url'] ) . '?custom-form=' . $selected_event;

			//
			// here is where you will do whatever you need to do with the data
			//

			// redirect the user to the appropriate page
			wp_redirect( $redirect_url );
			exit;

		} else {
			wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ),
				array(
					'response' 	=> 403,
					'back_link' => 'admin.php?page=' . $this->plugin_name,
				)
			);
		}

	}

}
