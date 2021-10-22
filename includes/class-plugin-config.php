<?php
/**
 * Static methods for plugin activation, uninstall, etc.
 *
 * @since 0.0.1.
 *
 * @package Plugin_Skeleton
 * @author  S Cayton
 */

namespace SCayton\PluginSkeleton;

/**
 * Plugin config, activation, and uninstall.
 *
 *  A class of static methods that for plugin configuration.
 *
 * @package Plugin_Skeleton
 * @author  S Cayton
 */
class Plugin_Config {
	/**
	 * Plugin Constants
	 */
	const PLUGIN_NAME    = 'plugin_skeleton'; // snake_case plugin name.
	const PLUGIN_TITLE   = 'Plugin Skeleton'; // Plain english title.
	const PLUGIN_VERSION = '0.0.1';

	/**
	 * Get a constant from this class.
	 *
	 * @param string $constant_name name of required constant.
	 * @return string required constant.
	 */
	public static function get_constant( $constant_name ) {
		return constant( "self::$constant_name" );
	}

	/**
	 * Handle activation of plugin.
	 */
	public static function activate() {
		Plugin_DB::table_check();
	}

	/**
	 * Handle deactivation of plugin.
	 */
	public static function deactivate() {

	}

	/**
	 * Handle uninstall of plugin.
	 */
	public static function uninstall() {
		if ( ! defined( 'WP_UNIINSTALL_PLUGIN' ) ) {
			exit;
		}

		Plugin_DB::clean_up();
	}
}
