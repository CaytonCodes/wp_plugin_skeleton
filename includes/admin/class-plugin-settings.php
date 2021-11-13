<?php
/**
 * A model for building settings.
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
 * Plugin Settings class.
 *
 * @package Plugin_Skeleton
 * @subpackage Plugin_Skeleton/Admin
 * @author  S Cayton
 */
class Plugin_Settings {
	/**
	 * Plugin settings
	 *
	 * @var array
	 */
	private $settings;

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
		}

		return self::$instance;
	}

	/**
	 * Perform construction of the class.
	 */
	private function __construct() {
		// Do any initial setup.
	}

	/**
	 * Initialize the settings.
	 */
	public function build_settings() {
		$title = Plugin_Config::get_constant( 'PLUGIN_TITLE' ); // Plain English.
		$name  = Plugin_Config::get_constant( 'PLUGIN_NAME' ); // Snake case.
		$slug  = $this->snake_to_dash( $name );

		$this->settings = array(
			'option_group' => $name,
			'options_name' => $name,
			'sections'     => array(
				array(
					'id'     => 'general',
					'title'  => 'General',
					'page'   => $slug,
					'fields' => array(
						array(
							'id'      => 'text_field',
							'title'   => 'Text Field',
							'type'    => 'text',
							'desc'    => 'This is a text field.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'text_area',
							'title'   => 'Text Area',
							'type'    => 'textarea',
							'desc'    => 'This is a text area.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'checkbox',
							'type'    => 'Checkbox',
							'title'   => 'Checkbox',
							'desc'    => 'This is a checkbox.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'radio',
							'type'    => 'Radio',
							'title'   => 'Radio',
							'desc'    => 'This is a radio.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'select',
							'type'    => 'Select',
							'title'   => 'Select',
							'desc'    => 'This is a select.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'multiselect',
							'type'    => 'Multiselect',
							'title'   => 'Multiselect',
							'desc'    => 'This is a multiselect.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'color',
							'type'    => 'Color',
							'title'   => 'Color',
							'desc'    => 'This is a color.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'date',
							'type'    => 'Date',
							'title'   => 'Date',
							'desc'    => 'This is a date.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'time',
							'type'    => 'Time',
							'title'   => 'Time',
							'desc'    => 'This is a time.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'datetime',
							'type'    => 'Datetime',
							'title'   => 'Datetime',
							'desc'    => 'This is a datetime.',
							'default' => '',
							'class'   => '',
						),
						array(
							'id'      => 'file',
							'type'    => 'File',
							'title'   => 'File',
							'desc'    => 'This is a file.',
							'default' => '',
							'class'   => '',
						),
					),
				),
			),
		);
	}

	/**
	 * Initialize the settings.
	 */
	public function register_settings() {
		$success[1] = register_setting( $this->settings['option_group'], $this->settings['options_name'], array( $this, 'sanitize_settings' ) );

		foreach ( $this->settings['sections'] as $section ) {
			add_settings_section( $section['id'], $section['title'], array( $this, 'section_header' ), $section['page'] );

			foreach ( $section['fields'] as $field ) {
				add_settings_field(
					$field['id'],
					$field['title'],
					array( $this, 'field_callback' ),
					$section['page'],
					$section['id'],
					array(
						'label_for' => $field['desc'],
						'class'     => $field['class'],
					)
				);
			}
		}
	}

	/**
	 * Convert snake case to dash case.
	 *
	 * @param string $str initial string.
	 * @return string $str converted string.
	 */
	public static function snake_to_dash( $str ) {
		$str = str_replace( '_', '-', $str );

		return $str;
	}

	/**
	 * Field callback.
	 */
	public function field_callback( $args ) {
		Plugin_Skeleton::log( 'field_callback', $args );

		$option = get_option( $this->settings['options_name'] );

		$value = isset( $option[ $args['label_for'] ] ) ? $option[ $args['label_for'] ] : '';

		switch ( $args['class'] ) {
			case 'text':
				printf( '<input type="text" id="%s" name="%s[%s]" value="%s" />', $args['label_for'], $this->settings['options_name'], $args['label_for'], $value );
				break;
			case 'textarea':
				printf( '<textarea id="%s" name="%s[%s]">%s</textarea>', $args['label_for'], $this->settings['options_name'], $args['label_for'], $value );
				break;
			case 'checkbox':
				printf( '<input type="checkbox" id="%s" name="%s[%s]" value="1" %s />', $args['label_for'], $this->settings['options_name'], $args['label_for'], checked( 1, $value, false ) );
				break;
			case 'radio':
				foreach ( $args['label_for'] as $key => $label ) {
					printf( '<input type="radio" id="%s" name="%s[%s]" value="%s" %s /> %s', $args['label_for'], $this->settings['options_name'], $args['label_for'], $key, checked( $key, $value, false ), $label );
				}
				break;
		}
	}

	/**
	 * Section Header.
	 *
	 * @return string $html HTML for section header.
	 */
	public function section_header() {
		$html = '<p>Section header</p>';

		return $html;
	}

	/**
	 * Sanitize each setting field as needed.
	 *
	 * @param array $input Contains all settings fields as array keys.
	 *
	 * @return array $output Contains all sanitized settings.
	 */
	public function sanitize_settings( $input ) {
		$output = array();

		foreach ( $input as $key => $value ) {
			if ( isset( $input[ $key ] ) ) {
				$output[ $key ] = sanitize_text_field( $value );
			}
		}

		return $output;
	}
}
