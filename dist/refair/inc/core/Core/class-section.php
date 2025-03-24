<?php
/**
 * File containing Section Class definition.
 *
 * @package Pixelscodex
 */

namespace PixelscodexCore;

/**
 * Section Class fot setting display.
 */
class Section {

	/**
	 * Name of the section.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * Id of the section (slug)
	 *
	 * @var string
	 */
	public $id = '';

	/**
	 * Description of the section.
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Options used to create the section.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Panel id where the section is added.
	 *
	 * @var string
	 */
	public $panel = null;

	/**
	 * List of all settings into the section.
	 *
	 * @var array
	 */
	public $setting_list = array();

	/**
	 * List of Built-in section.
	 *
	 * @var array
	 */
	private $built_in_sections = array( 'title_tagline', 'colors', 'header_image', 'background_image', 'nav', 'static_front_page' );

	/**
	 * Constructor of the class. Set input parameters to internal variables.
	 *
	 * @param  string $name Name of the section.
	 * @param  string $description Descrption of the section.
	 * @param  array  $options Options used at Section creation.
	 */
	public function __construct( $name, $description, $options = array() ) {
		$this->name = $name;

		$this->id = sanitize_key( $name );

		$this->description = $description;

		$this->options = $options;

		$this->setting_list = array();

		$this->init();
	}

	/**
	 * Action executed at Instance initialization.
	 *
	 * @return void
	 */
	private function init() {
		add_action( 'customize_register', array( $this, 'exec' ) );
	}

	/**
	 * Add setting to the section.
	 *
	 * @param  Setting $setting setting Class Instance.
	 * @return void
	 */
	public function add_setting( $setting ) {
		$setting->set_section( $this );
		array_push( $this->setting_list, $setting );
	}

	/**
	 * Set panel where section is displayed.
	 *
	 * @param  Panel $panel Panel Instance where Section has to be added.
	 * @return void
	 */
	public function set_panel( $panel ) {
		$this->panel = $panel->id;
	}

	/**
	 * Prepare and set section to the WP_Customize_Manager instance.
	 *
	 * @param  WP_Customize_Manager $wp_customize Customize Manager singleton.
	 * @return void
	 */
	public function exec( $wp_customize ) {

		$section_name        = $this->name;
		$section_description = $this->description;

		$args = array(
			'title'       => $section_name,
			'description' => $section_description,
			'priority'    => 40,
			'capability'  => 'edit_theme_options',
		);

		if ( isset( $this->options['priority'] ) ) {
			$args['priority'] = $this->options['priority'];}
		if ( isset( $this->panel ) ) {
			$args['panel'] = $this->panel;}

		if ( false === array_search( $this->name, $this->built_in_sections, true ) ) {
			$wp_customize->add_section( $this->id, $args );
		}

		foreach ( $this->setting_list as $setting ) {
			$setting->exec( $wp_customize );
		}
	}
}
