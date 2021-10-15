<?php
/**
 * Plugin Skeleton
 *
 * @package   Plugin_Skeleton
 * @author    S Cayton
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Skeleton
 * Description:       Basic outline for plugins
 * Version:           0.0.1
 * Author:            S Cayton
 * Author URI:        https://github.com/CaytonCodes
 */

namespace SCayton\PluginSkeleton;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Autoloader
 *
 * @param string $class_name The fully-qualified class name.
 * @return void
 */
spl_autoload_register(
	function ( $class_name ) {
		/* Only autoload classes from this namespace */
		if ( false === strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}
		/* Remove namespace from class name */
		$class_file = str_replace( __NAMESPACE__ . '\\', '', $class_name );
		/* Convert class name format to file name format */
		$class_file = strtolower( $class_file );
		$class_file = str_replace( '_', '-', $class_file );
		/* Convert sub-namespaces into directories */
		$class_path = explode( '\\', $class_file );
		$class_file = array_pop( $class_path );
		$class_path = implode( '/', $class_path );
		/* Load the class */
		require_once __DIR__ . '/includes/' . $class_path . '/class-' . $class_file . '.php';
	}
);


/**
 * Initialize Plugin
 *
 * @since 0.0.1
 */
function plugskel_v1_init() {
	$plugskel_inst = Plugin_Skeleton::get_instance();
	$plugskel_db   = Plugin_DB::get_instance();
}

register_activation_hook( __FILE__, array( 'SCayton\\PluginSkeleton\\Plugin_DB', 'table_check' ) );
register_uninstall_hook( __FILE__, array( 'SCayton\\PluginSkeleton\\Plugin_DB', 'clean_up' ) );

add_action( 'init', 'SCayton\\PluginSkeleton\\plugskel_v1_init' );
