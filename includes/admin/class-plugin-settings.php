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
		$name = Plugin_Config::get_constant( 'PLUGIN_NAME' );

		$this->settings = array(
			'option_group' => $name,
			'option_name'  => $name,
			'sections'     => array(
				array(
					'id'     => $name,
					'title'  => 'General',
					'page'   => $name,
					'fields' => array(
						// Add fields here.
						// Id is slug, title is plain English.
						array(
							'id'    => 'text_field',
							'title' => 'Text Field',
							'args'  => array(
								'id'          => 'text_field',
								'type'        => 'text',
								'label_for'   => 'text_field',
								'class'       => 'regular-text',
								'default'     => '',
								'description' => 'This is a text field.',
								'size'        => '60',
							),
						),
						array(
							'id'    => 'text_area',
							'title' => 'Text Area',
							'args'  => array(
								'id'          => 'text_area',
								'type'        => 'textarea',
								'label_for'   => 'text_area',
								'class'       => 'large-text',
								'default'     => '',
								'description' => 'This is a text area.',
							),
						),
						array(
							'id'    => 'checkbox',
							'title' => 'Checkbox',
							'args'  => array(
								'id'          => 'checkbox',
								'type'        => 'checkbox',
								'label_for'   => 'checkbox',
								'class'       => '',
								'default'     => '',
								'description' => 'This is a checkbox.',
							),
						),
						array(
							'id'    => 'radio',
							'title' => 'Radio',
							'args'  => array(
								'id'          => 'radio',
								'type'        => 'radio',
								'label_for'   => 'radio',
								'class'       => '',
								'default'     => '',
								'description' => 'This is a radio.',
								'options'     => array(
									'option_1' => 'Option 1',
									'option_2' => 'Option 2',
									'option_3' => 'Option 3',
								),
							),
						),
						array(
							'id'    => 'select',
							'title' => 'Select',
							'args'  => array(
								'id'          => 'select',
								'type'        => 'select',
								'label_for'   => 'select',
								'class'       => '',
								'default'     => '',
								'description' => 'This is a select.',
								'options'     => array(
									'option_1' => 'Option 1',
									'option_2' => 'Option 2',
									'option_3' => 'Option 3',
								),
							),
						),
						array(
							'id'    => 'multiselect',
							'title' => 'Multiselect',
							'args'  => array(
								'id'          => 'multiselect',
								'type'        => 'multiselect',
								'label_for'   => 'multiselect',
								'class'       => '',
								'default'     => '',
								'description' => 'This is a multiselect.',
								'options'     => array(
									'option_1' => 'Option 1',
									'option_2' => 'Option 2',
									'option_3' => 'Option 3',
								),
							),
						),
						array(
							'id'    => 'color',
							'title' => 'Color',
							'args'  => array(
								'id'          => 'color',
								'type'        => 'color',
								'label_for'   => 'color',
								'class'       => '',
								'default'     => '',
								'description' => 'This is a color.',
							),
						),
						array(
							'id'    => 'file',
							'title' => 'File',
							'args'  => array(
								'id'          => 'file',
								'type'        => 'file',
								'label_for'   => 'file',
								'class'       => '',
								'default'     => '',
								'description' => 'This is a file.',
							),
						),
						array(
							'id'    => 'image',
							'title' => 'Image',
							'args'  => array(
								'id'          => 'image',
								'type'        => 'image',
								'label_for'   => 'image',
								'class'       => '',
								'default'     => '',
								'description' => 'This is an image.',
							),
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

		register_setting( $this->settings['option_group'], $this->settings['option_name'], array( $this, 'sanitize' ) );

		foreach ( $this->settings['sections'] as $section ) {
			add_settings_section( $section['id'], $section['title'], array( $this, 'section_header' ), $section['page'] );

			foreach ( $section['fields'] as $field ) {
				add_settings_field(
					$field['id'],
					$field['title'],
					array( $this, 'field_callback' ),
					$section['page'],
					$section['id'],
					$field['args'],
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
	 * Section Header.
	 */
	public function section_header() {
		print 'Enter settings below.';
	}

	/**
	 * Sanitize setting field
	 *
	 * @param array $input Contains all settings fields as array keys.
	 */
	public function sanitize( $input ) {
		$output = array();
		foreach ( $input as $key => $value ) {
			$output[ $key ] = sanitize_text_field( $value );
		}

		return $output;
	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @param array $args Arguments for field to be printed.
	 */
	public function field_callback( $args ) {
		$name          = Plugin_Config::get_constant( 'PLUGIN_NAME' );
		$current_value = isset( get_option( $name )[ $args['id'] ] ) ?
			get_option( $name )[ $args['id'] ] :
			$args['default'];
		$field_name    = $name . '[' . $args['id'] . ']';
		$size          = isset( $args['size'] ) ? $args['size'] : '20';

		if ( ( 'select' === $args['type'] ) || ( 'multiselect' === $args['type'] ) ) {
			if ( ! isset( $args['size'] ) ) {
				$options_length = count( $args['options'] );
				$size           = ( $options_length > 7 ) ? 7 : $options_length;
			}
			$html = '';
			$html = '<select id="' . esc_attr( $args['id'] )
			. '" name="' . esc_attr( $field_name )
			. '" size="' . esc_attr( $size )
			. '" value="' . esc_attr( $current_value ) . '" />';
			foreach ( $args['options'] as $key => $label ) {
				$html .= '<option value="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</option>';
			}
			$html .= '</select>';
			$html .= '<p class="' . esc_attr( $field_name . ' description' )
			. '">' . esc_html( $args['description'] ) . '</p>';

			echo $html;
		} else {
			$html  = '';
			$html  = '<input id="' . esc_attr( $args['id'] )
				. '" name="' . esc_attr( $field_name )
				. '" type="' . esc_attr( $args['type'] )
				. '" size="' . esc_attr( $size )
				. '" value="' . esc_attr( $current_value ) . '" />';
			$html .= '<p class="' . esc_attr( $field_name . ' description' )
				. '">' . esc_html( $args['description'] ) . '</p>';

			echo $html;
		}
	}
}
