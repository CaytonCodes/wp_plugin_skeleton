<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since 0.0.1.
 *
 * @package Plugin_Skeleton
 * @author  S Cayton
 */

namespace SCayton\PluginSkeleton\Admin;

use SCayton\PluginSkeleton\Plugin_Config;

/**
 * The admin-specific functionality of the plugin.
 *
 * @package Plugin_Skeleton
 * @subpackage Plugin_Skeleton/Admin
 * @author  S Cayton
 */
class Plugin_Admin {
	/**
	 * Settings options
	 *
	 * @var object
	 */
	private $options;

	/**
	 * Plain English title of the plugin.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Snake_case name of the plugin.
	 *
	 * @var string
	 */
	private $name;

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
	 * Perform construction of the admin class.
	 */
	private function __construct() {
		// Do any initial setup.
		$this->title = Plugin_Config::get_constant( 'PLUGIN_TITLE' );
		$this->name  = Plugin_Config::get_constant( 'PLUGIN_NAME' );
	}

	/**
	 * Handle WP actions and filters.
	 */
	private function do_hooks() {
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	}

	/**
	 * Register admin scripts and styles.
	 *
	 * @return void
	 */
	public function register_admin_scripts() {
		wp_register_script( $this->snake_to_dash( $this->plugin_name ) . '-admin-script', plugins_url( '/js/admin.js', __FILE__ ), array( 'jquery' ), '0.0.1', true );
		wp_register_style( $this->snake_to_dash( $this->plugin_name ) . '-admin-style', plugins_url( '/js/admin.css', __FILE__ ), '0.0.1', true );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress dashboard menu.
	 */
	public function add_plugin_admin_menus() {
		$title = $this->title;
		$name  = $this->name;

		add_menu_page(
			$title,
			$title,
			'manage_options',
			$name . '_admin_page',
			array( $this, 'display_admin_page' ),
			'dashicons-beer' // See https://developer.wordpress.org/resource/dashicons/#insert-after.
		);
	}

	/**
	 * Convert snake case to dash case.
	 *
	 * @param string $str initial string.
	 * @return string $str converted string.
	 */
	public function snake_to_dash( $str ) {
		$str = str_replace( '_', '-', $str );

		return $str;
	}

	/**
	 * Displays the admin page.
	 *
	 * @return void
	 */
	public function display_admin_page() {
		$name = $this->name;

		wp_enqueue_script( $this->snake_to_dash( $this->plugin_name ) . '-admin-script' );
		wp_enqueue_style( $this->snake_to_dash( $this->plugin_name ) . '-admin-style' );

		$content = "
			<div class='$name container' id='$name'>
				<H1>$name Plugin Settings</H1>
			</div>";

		echo $content;
	}
}
