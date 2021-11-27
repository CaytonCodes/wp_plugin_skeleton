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
use SCayton\PluginSkeleton\Plugin_Skeleton;

/**
 * The admin-specific functionality of the plugin.
 *
 * @package Plugin_Skeleton
 * @subpackage Plugin_Skeleton/Admin
 * @author  S Cayton
 */
class Plugin_Admin {
	/**
	 * Settings object
	 *
	 * @var object
	 */
	private $settings;

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
		$this->title    = Plugin_Config::get_constant( 'PLUGIN_TITLE' );
		$this->name     = Plugin_Config::get_constant( 'PLUGIN_NAME' );
		$this->settings = Plugin_Settings::get_instance();
	}

	/**
	 * Handle WP actions and filters.
	 */
	private function do_hooks() {
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'admin_page_init' ) );
	}

	/**
	 * Register admin scripts and styles.
	 *
	 * @return void
	 */
	public function register_admin_scripts() {
		// array of variables to send as arguments.
		$admin_args = array(
			'key' => 'value',
		);

		wp_register_script( $this->settings::snake_to_dash( $this->name ) . '-admin-script', plugins_url( '../../assets/js/admin.js', __FILE__ ), null, '0.0.1', true );

		// send variables to script.
		wp_localize_script(
			$this->settings::snake_to_dash( $this->name ) . '-admin-script',
			'admin_args',
			$admin_args
		);

		// built script.
		wp_enqueue_script( 'admin-js-app', plugins_url( '../../assets/js/admin-app/build/index.js', __FILE__ ), array( 'wp-blocks' ), '1.0.0', true );

		// Styles.
		wp_register_style( $this->settings::snake_to_dash( $this->name ) . '-admin-style', plugins_url( '../../assets/css/admin.css', __FILE__ ), '0.0.1', true );
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
	 * Displays the admin page.
	 *
	 * @return void
	 */
	public function display_admin_page() {
		$name  = $this->name;
		$title = $this->title;
		$slug  = $this->settings->snake_to_dash( $name );

		wp_enqueue_script( $this->settings::snake_to_dash( $name ) . '-admin-script' );
		wp_enqueue_style( $this->settings::snake_to_dash( $name ) . '-admin-style' );

		ob_start();
		?>
			<div class='$name .  container' id='$name'>
				<H1>
					<?php echo esc_attr( $title ); ?>
					Plugin Settings</H1>
				<div class='settings-section'>
					<h2>General Settings</h2>
					<form method='post' action='options.php'>
						<table class='form-table'>
							<?php
							settings_fields( $name ); // reference the $option_group.
							do_settings_sections( $name ); // reference the settings' $page.
							submit_button( 'Save Settings' );
							?>
						</table>
					</form>
				</div>
			</div>
		<?php
		echo ob_get_clean();
	}

	/**
	 * Initialize page  and plugin settings.
	 */
	public function admin_page_init() {
		$this->settings->build_settings();
		$this->settings->register_settings();
	}
}
