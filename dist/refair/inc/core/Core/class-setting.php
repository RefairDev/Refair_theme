<?php
/**
 * File containing Setting class.
 *
 * @package Pixelscodex.
 */

namespace PixelscodexCore;

/**
 * Setting
 */
abstract class Setting {

	/**
	 * ID/slug of the setting.
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Name of the setting.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Description of the setting.
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Array of options for the setting creation.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Section where the setting is created.
	 *
	 * @var string
	 */
	protected $section = '';

	/**
	 * Default value of the setting.
	 *
	 * @var string
	 */
	protected $default = '';


	/**
	 * The function is a PHP constructor that dynamically calls a specific constructor method based on the
	 * number of arguments passed.
	 */
	public function __construct() {
		$a = func_get_args();
		$i = func_num_args();
		$f = 'construct' . $i;
		if ( method_exists( $this, $f ) ) {
			call_user_func_array( array( $this, $f ), $a );
		}
	}


	/**
	 * Constructor with name parameter.
	 *
	 * @param  string $name Name of the setting.
	 * @return void
	 */
	public function construct1( $name ) {
		$this->construct4( sanitize_key( $name ), $name, '', array() );
	}

	/**
	 * Constructor with name and description parameters.
	 *
	 * @param  string $name Name of the setting.
	 * @param  string $description Description of the setting.
	 * @return void
	 */
	public function construct2( $name, $description ) {
		$this->construct4( sanitize_key( $name ), $name, $description, array() );
	}

	/**
	 * Constructor with name, description and options parameters.
	 *
	 * @param  string $name Name of the setting.
	 * @param  string $description Description of the setting.
	 * @param  array  $options Options used to create the setting.
	 * @return void
	 */
	public function construct3( $name, $description, $options ) {
		$this->construct4( sanitize_key( $name ), $name, $description, $options );
	}

	/**
	 * Constructor with id, name, description and options parameters.
	 *
	 * @param  string $id Id/slug of the setting.
	 * @param  string $name Name of the setting.
	 * @param  string $description Description of the setting.
	 * @param  array  $options Options used to create the setting.
	 * @return void
	 */
	public function construct4( $id, $name, $description, $options ) {
		$this->name = $name;

		if ( isset( $id ) && ! empty( $id ) ) {
			$this->id = $id;
		} else {
			$this->id = sanitize_key( $name );
		}

		$this->description = $description;

		$this->section = 'default';

		$this->options = $options;

		if ( isset( $options['default'] ) ) {
			$this->default = $options['default'];
		} else {
			$this->default = null;}
	}

	/**
	 * Set the section of the setting.
	 *
	 * @param  Section $section Id of Section where the setting is created.
	 * @return void
	 */
	public function set_section( $section ) {

		$this->section = $section->id;
	}

	/**
	 * Get and retun name property
	 *
	 * @return string Name property.
	 */
	protected function get_name() {
		return $this->name;
	}

	/**
	 * Get and retun Id property
	 *
	 * @return string Id property.
	 */
	protected function get_id() {
		return $this->id;
	}

	/**
	 * Get and retun Description property
	 *
	 * @return string Description property.
	 */
	protected function get_description() {
		return $this->description;
	}

	/**
	 * Get and retun Options property
	 *
	 * @return string Options property.
	 */
	protected function get_options() {
		return $this->options;
	}

	/**
	 * Get and retun section property
	 *
	 * @return string Section property.
	 */
	protected function get_section() {
		return $this->section;
	}

	/**
	 * Add setting to the customizer
	 *
	 * @param  WP_Customize_Manager $wp_customize Customize manager which add setting to panel.
	 * @return void
	 */
	public function exec( $wp_customize ) {

		$args = array(
			'type'       => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport'  => 'refresh',

		);
		if ( isset( $this->default ) ) {
			$args['default'] = $this->default;}

		$wp_customize->add_setting( $this->id, $args );
		$this->show_setting( $wp_customize );
	}

	/**
	 * Abstract function defining control display.
	 *
	 * @param  WP_Customize_Manager $wp_customize Customize manager which add setting to panel.
	 * @return void
	 */
	abstract public function show_setting( $wp_customize );
}
