<?php
/**
 * Plugin Skeleton Database Setup and Break Down.
 *
 * @package Plugin_Skeleton
 * @author  S Cayton
 */

namespace SCayton\PluginSkeleton;

/**
 * Search Log Database config, activation, and uninstall.
 *
 *  A class of methods that handle plugin setup, break-down, and database constants.
 */
class Plugin_DB {

	/**
	 * Config Constants
	 * NOTE: Table name will automatically add standard wp prefix do not include here.
	 */
	const TABLE_NAME_BASE   = 'plugin_skeleton';
	const DB_VERSION        = '0.0.1';
	const DB_VERSION_OPTION = 'plugin_skeleton_db_version';

	/**
	 * Instance of this class
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
		if ( null === self::$instance ) {
			self::$instance = new self();
			// check tables on init.
			self::$instance->table_check();
		}

		return self::$instance;
	}

	/**
	 * Get a constant from this class.
	 *
	 * Requesting the constant 'TABLE_NAME' will return full table name.
	 * Other constants can be requested by name.
	 *
	 * @param string $constant_name name of required constant.
	 * @return string required constant.
	 */
	public static function get_constant( $constant_name ) {
		if ( 'TABLE_NAME' === $constant_name ) {
			global $wpdb;
			return $wpdb->prefix . constant( 'self::TABLE_NAME_BASE' );
		}
		return constant( "self::$constant_name" );
	}


	/**
	 * Check if tables need to be updated.
	 */
	public static function table_check() {
		// if the stored db version is not current, setup the table.
		$option_name     = self::get_constant( 'DB_VERSION_OPTION' );
		$stored_version  = get_option( $option_name );
		$current_version = self::get_constant( 'DB_VERSION' );
		if ( $stored_version !== $current_version ) {
			self::setup_table();
		}
	}

	/**
	 * Setup the table on activation
	 */
	public static function setup_table() {
		global $wpdb;
		$table_name      = self::get_constant( 'TABLE_NAME' );
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
	  ID int NOT NULL AUTO_INCREMENT,
	  `time` TIMESTAMP default CURRENT_TIMESTAMP,
	  `col_1` varchar(50) NOT NULL,
	  `col_2` varchar(10),
	  PRIMARY KEY  (ID)
	) $charset_collate;";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );
		update_option( self::get_constant( 'DB_VERSION_OPTION' ), self::get_constant( 'DB_VERSION' ) );
	}

	/**
	 * Break camp on uninstall: drop table, delete user metadata
	 */
	public static function clean_up() {
		global $wpdb;
		$table_name = self::get_constant( 'TABLE_NAME' );

		$wpdb->prepare( 'DROP TABLE IF EXISTS %s', $table_name );
		delete_option( self::get_constant( 'DB_VERSION_OPTION' ) );
	}

	/**
	 * Insert a new row into the table.
	 *
	 * @param array $data array of data to insert.
	 * @return array or false on error.
	 */
	public static function plugin_table_insert( $data ) {
		global $wpdb;

		$table_name = self::get_constant( 'TABLE_NAME' );

		$inserted = $wpdb->insert( $table_name, $data, '%s' ); // db call ok.

		if ( $inserted ) {
			$data['row_id'] = $wpdb->insert_id;
			return $data;
		} else {
			// we got an error here, re-setup table.
			self::setup_table();
			return false;
		}
	}
}
