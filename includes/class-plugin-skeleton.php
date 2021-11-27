<?php
/**
 * Plugin Skeleton - class skeleton
 *
 * @package   Plugin_Skeleton
 * @author    S Cayton
 */

namespace SCayton\PluginSkeleton;

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		// array of variables to send as arguments.
		$args = array(
			'key' => 'value',
		);

		// single-page function.
		wp_enqueue_script( 'plugin-skeleton', plugins_url( '../assets/js/plugin-skeleton.js', __FILE__ ), null, '1.0.0', true );
		// send variables to script.
		wp_localize_script(
			'plugin-skeleton',
			'args',
			$args
		);

		// built script.
		wp_enqueue_script( 'plugin-app', plugins_url( '../assets/js/app/build/index.js', __FILE__ ), array( 'wp-blocks' ), '1.0.0', true );
	}

	/**
	 * Do skeleton action on hook event.
	 */
	public function skeleton_hook() {
		echo 'Hello from the skeleton';
	}

	/**
	 * Log a string and data to the console.
	 *
	 * Useful for debugging / dev.
	 *
	 * @param string $string String to prefix data with.
	 * @param any    $data Data to log, will be in stringified JSON format.
	 */
	public static function log( $string, $data ) {
		echo "<script>console.log( '" . esc_attr( $string ) . "' + ' : ' + JSON.stringify( " . wp_json_encode( $data ) . ' ) );</script>';
	}
}
