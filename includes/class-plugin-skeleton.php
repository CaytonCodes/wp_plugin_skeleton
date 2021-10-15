<?php
/**
 * Plugin Skeleton - class skeleton
 *
 * @package   Plugin_Skeleton
 * @author    S Cayton
 */

namespace ScottyC\PluginSkeleton;

/**
 * Basic Class using singleton instantiation
 */
class Plugin_Skeleton {
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
		}

		return self::$instance;
	}

	/**
	 * Handle WP actions and filters.
	 */
	private function do_hooks() {
		add_action( 'wp', array( $this, 'skeleton_hook' ) );
	}

	/**
	 * Do skeleton action on hook event.
	 */
	public function skeleton_hook() {
		echo 'Hello from the skeleton';
	}
}
