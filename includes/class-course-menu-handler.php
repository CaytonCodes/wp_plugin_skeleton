<?php
/**
 * Dynamic Course Menu - Handler
 *
 * @package   NC_course_menu
 * @author    Scotty @ Neapolitan Creative
 */

namespace NC\CourseMenu;

/**
 * Class to handle creating dynamic menu and rendering appropriate menu object.
 */
class Course_Menu_Handler {
	/**
	 * Name of menu to be used for dynamic course menu.
	 */
	const MENU_NAME = 'Dynamic Course Menu';

	/**
	 * Slug of menu to be used for dynamic course menu.
	 */
	const MENU_SLUG = 'dynamic-course-menu';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->do_hooks();
			self::$instance->activate_dynamic_menu();
		}

		return self::$instance;
	}

	/**
	 * Handle WP actions and filters.
	 */
	private function do_hooks() {
		add_filter( 'wp_nav_menu_args', array( $this, 'dynamic_menu_handler' ) );
		add_action( 'wp_delete_nav_menu', array( $this, 'handle_menu_delete' ) );
		add_action( 'wp_update_nav_menu', array( $this, 'handle_menu_update' ) );
	}

	/**
	 * Hanlde Dynamic Course Menu.
	 *  - Check if menu is our dynamic menu, if so point to correct course menu object.
	 *
	 * @param  array $args Array of arguments for the menu.
	 */
	public function dynamic_menu_handler( $args ) {
		$filtered_menu_name = $args['menu'];
		$name_match         = in_array( $filtered_menu_name, array( self::MENU_NAME, self::MENU_SLUG, 'Dynamic Course Menu' ), true );
		if ( $name_match ) {
			$menu_id = $this->accessally_menu_router();
			if ( $menu_id ) {
				$args['menu'] = $menu_id;
			}
		}
		return $args;
	}

	/**
	 * AccessAlly Menu Router
	 * - Use accessally data to determine correct menu id.
	 */
	public function accessally_menu_router() {
		$current_post_id = get_the_ID();
		// we are relying on accessally course settings here!!!
		$course_settings = apply_filters( 'accessally_course_setting', false, $current_post_id );
		return isset( $course_settings['menu-id'] ) ? $course_settings['menu-id'] : false;

	}

	/**
	 * Check if plugin has been acivated.
	 */
	public function activate_dynamic_menu() {
		if ( is_numeric( get_option( 'nc_course_menu_id' ) ) ) {
			return;
		}

		$this->manage_dynamic_menu_object();
	}

	/**
	 * Create or update dynamic course menu object.
	 *  - No $id will default to creating a new menu.
	 *
	 * @param int $id ID of menu to be updated, if not set, create new menu.
	 */
	public function manage_dynamic_menu_object( $id = 0 ) {
		$menu_obj      = array(
			'menu-name' => self::MENU_NAME,
			'slug'      => self::MENU_SLUG,
		);
		$update_return = wp_update_nav_menu_object( $id, wp_slash( $menu_obj ) );

		if ( ! is_wp_error( $update_return ) ) {
			$homepage_added = wp_update_nav_menu_item(
				$update_return,
				0,
				array(
					'menu-item-title'  => 'Home',
					'menu-item-url'    => home_url(),
					'menu-item-status' => 'publish',
				)
			);

			if ( ! is_wp_error( $homepage_added ) ) {
				update_option( 'nc_course_menu_id', $update_return );
			}
		}
	}

	/**
	 * Handle when someone updates ot deletes a menu.
	 * - If it was our menu, update it.
	 *
	 * @param int $id ID of menu being updated.
	 */
	public function handle_menu_update( $id ) {
		$menu_id      = (int) get_option( 'nc_course_menu_id' );
		$updated_menu = wp_get_nav_menu_object( $id );

		// This action will fire after we update a menu to correct it, if that is the case, quit.
		if ( self::MENU_NAME === $updated_menu->name ) {
			return;
		}
		if ( null === $menu_id ) {
			// create a new menu.
			$this->manage_dynamic_menu_object();
		} elseif ( $menu_id === $id ) {
			// Somebody tried to change the menu, correct it.
			$this->manage_dynamic_menu_object( $id );
		}
	}

	/**
	 * Handle when someone deletes a menu.
	 * - Create our menu, in case it was the one that was deleted.
	 */
	public function handle_menu_delete() {
		// we can call this anytime a menu is deleted since we are commanding to create a new menu and not update one, it won't change anything if our menu still exists.
		$this->manage_dynamic_menu_object();
	}
}
