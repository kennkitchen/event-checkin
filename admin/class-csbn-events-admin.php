<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://kenneth.kitchen
 * @since      1.0.0
 *
 * @package    Csbn_Events
 * @subpackage Csbn_Events/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Csbn_Events
 * @subpackage Csbn_Events/admin
 * @author     Kenneth Kitchen <kenn@kmd.enterprises>
 */
class Csbn_Events_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/csbn-events-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/csbn-events-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the Events custom post type.
	 *
	 * @since    1.0.0
	 */
	public function events_post_type() {

		$labels = array(
			'name'                  => _x( 'Events', 'Post Type General Name', 'csbn-events' ),
			'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'csbn-events' ),
			'menu_name'             => __( 'Events', 'csbn-events' ),
			'name_admin_bar'        => __( 'Event', 'csbn-events' ),
			'archives'              => __( 'Event Archives', 'csbn-events' ),
			'attributes'            => __( 'Event Attributes', 'csbn-events' ),
			'parent_item_colon'     => __( 'Event Parent Item:', 'csbn-events' ),
			'all_items'             => __( 'All Events', 'csbn-events' ),
			'add_new_item'          => __( 'Add New Event', 'csbn-events' ),
			'add_new'               => __( 'Add New', 'csbn-events' ),
			'new_item'              => __( 'New Event', 'csbn-events' ),
			'edit_item'             => __( 'Edit Event', 'csbn-events' ),
			'update_item'           => __( 'Update Event', 'csbn-events' ),
			'view_item'             => __( 'View Event', 'csbn-events' ),
			'view_items'            => __( 'View Events', 'csbn-events' ),
			'search_items'          => __( 'Search Events', 'csbn-events' ),
			'not_found'             => __( 'Not found', 'csbn-events' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'csbn-events' ),
			'featured_image'        => __( 'Featured Image', 'csbn-events' ),
			'set_featured_image'    => __( 'Set featured image', 'csbn-events' ),
			'remove_featured_image' => __( 'Remove featured image', 'csbn-events' ),
			'use_featured_image'    => __( 'Use as featured image', 'csbn-events' ),
			'insert_into_item'      => __( 'Insert into events', 'csbn-events' ),
			'uploaded_to_this_item' => __( 'Uploaded to this event', 'csbn-events' ),
			'items_list'            => __( 'Events list', 'csbn-events' ),
			'items_list_navigation' => __( 'Events list navigation', 'csbn-events' ),
			'filter_items_list'     => __( 'Filter events list', 'csbn-events' ),
		);
		$args = array(
			'label'                 => __( 'Event', 'csbn-events' ),
			'description'           => __( 'Event Description', 'csbn-events' ),
			'labels'                => $labels,
			'supports'              => 'title',
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'cpt_event', $args );

	}

	/**
	 * Register the Events meta box.
	 *
	 * @since    1.0.0
	 */
	public function events_meta_box() {

		add_meta_box(
			'event_meta_box',               // Unique ID
            'Events',       // Box title
            [self::class, 'events_custom_box_html'], // Content callback
            'cpt_event',              // screen on which to show the box
            'normal',                // or 'side', 'advanced'
            'default',               // 'high', 'low'
            null                   // $args property of the box array
        );

	}

	/**
	 * Register the Patrons custom post type.
	 *
	 * @since    1.0.0
	 */
	public function patrons_post_type() {

		$labels = array(
			'name'                  => _x( 'Patrons', 'Post Type General Name', 'csbn-events' ),
			'singular_name'         => _x( 'Patron', 'Post Type Singular Name', 'csbn-events' ),
			'menu_name'             => __( 'Patrons', 'csbn-events' ),
			'name_admin_bar'        => __( 'Patron', 'csbn-events' ),
			'archives'              => __( 'Patron Archives', 'csbn-events' ),
			'attributes'            => __( 'Patron Attributes', 'csbn-events' ),
			'parent_item_colon'     => __( 'Patron Parent Item:', 'csbn-events' ),
			'all_items'             => __( 'All Patrons', 'csbn-events' ),
			'add_new_item'          => __( 'Add New Patron', 'csbn-events' ),
			'add_new'               => __( 'Add New', 'csbn-events' ),
			'new_item'              => __( 'New Patron', 'csbn-events' ),
			'edit_item'             => __( 'Edit Patron', 'csbn-events' ),
			'update_item'           => __( 'Update Patron', 'csbn-events' ),
			'view_item'             => __( 'View Patron', 'csbn-events' ),
			'view_items'            => __( 'View Patrons', 'csbn-events' ),
			'search_items'          => __( 'Search Patrons', 'csbn-events' ),
			'not_found'             => __( 'Not found', 'csbn-events' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'csbn-events' ),
			'featured_image'        => __( 'Featured Image', 'csbn-events' ),
			'set_featured_image'    => __( 'Set featured image', 'csbn-events' ),
			'remove_featured_image' => __( 'Remove featured image', 'csbn-events' ),
			'use_featured_image'    => __( 'Use as featured image', 'csbn-events' ),
			'insert_into_item'      => __( 'Insert into events', 'csbn-events' ),
			'uploaded_to_this_item' => __( 'Uploaded to this event', 'csbn-events' ),
			'items_list'            => __( 'Patrons list', 'csbn-events' ),
			'items_list_navigation' => __( 'Patrons list navigation', 'csbn-events' ),
			'filter_items_list'     => __( 'Filter events list', 'csbn-events' ),
		);
		$args = array(
			'label'                 => __( 'Patron', 'csbn-events' ),
			'description'           => __( 'Patron Description', 'csbn-events' ),
			'labels'                => $labels,
			'supports'              => 'title',
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'cpt_patron', $args );

	}

	/**
	 * Register the Patrons meta box.
	 *
	 * @since    1.0.0
	 */
	public function patrons_meta_box() {
		add_meta_box(
			'patron_meta_box',               // Unique ID
			'Patrons',       // Box title
			[self::class, 'patrons_custom_box_html'], // Content callback
			'cpt_patron',              // screen on which to show the box
			'normal',                // or 'side', 'advanced'
			'default',               // 'high', 'low'
			null                   // $args property of the box array
		);

	}

	public function events_custom_box_html($post) {
		$event_date = get_post_meta($post->ID, '_csbn_event_date_key', true);
		$event_time = get_post_meta($post->ID, '_csbn_event_time_key', true);
		?>
        <div class="csbn_input">
		Event Date:<br>
		<input type="text" name="event_date" value="<?= (!empty($event_date)) ? $event_date : '' ?>">
		<br>
		Event Time:<br>
		<input type="text" name="event_time" value="<?= (!empty($event_time)) ? $event_time : '' ?>">
		<br><br>
        </div>
		<?php
	}

	public function patrons_custom_box_html($post) {
		$first_name = get_post_meta($post->ID, '_csbn_patron_first_name_key', true);
		$last_name = get_post_meta($post->ID, '_csbn_patron_last_name_key', true);
		$email_address = get_post_meta($post->ID, '_csbn_patron_email_address_key', true);
		?>
        <div class="csbn_input">
		First Name:<br>
		<input type="text" name="first_name" value="<?= (!empty($first_name)) ? $first_name : '' ?>">
		<br>
		Last Name:<br>
		<input type="text" name="last_name" value="<?= (!empty($last_name)) ? $last_name : '' ?>">
		<br>
		Email Address:<br>
		<input type="text" name="email_address" value="<?= (!empty($email_address)) ? $email_address : '' ?>">
		<br><br>
        </div>
		<?php
	}


	public function persist_events_meta_box_data($post_id) {
		if (array_key_exists('event_date', $_POST)) {
			update_post_meta(
				$post_id,
				'_csbn_event_date_key',
				$_POST['event_date']
			);
		}

		if (array_key_exists('event_time', $_POST)) {
			update_post_meta(
				$post_id,
				'_csbn_event_time_key',
				$_POST['event_time']
			);
		}

	}

	public function persist_patrons_meta_box_data($post_id) {
		if (array_key_exists('_csbn_patron_first_name_key', $_POST)) {
			update_post_meta(
				$post_id,
				'_csbn_patron_first_name_key',
				$_POST['first_name']
			);
		}

		if (array_key_exists('_csbn_patron_last_name_key', $_POST)) {
			update_post_meta(
				$post_id,
				'_csbn_patron_last_name_key',
				$_POST['last_name']
			);
		}

		if (array_key_exists('_csbn_patron_email_address_key', $_POST)) {
			update_post_meta(
				$post_id,
				'_csbn_patron_email_address_key',
				$_POST['email_address']
			);
		}
	}
}
