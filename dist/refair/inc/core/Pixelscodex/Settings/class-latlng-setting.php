<?php
/**
 * File containing Image_Setting Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

use PixelscodexCore\Setting;
use WP_Customize_Control;

/**
 * EditableSetting
 */
class Latlng_Setting extends Setting {

	/**
	 * Constructor of the image control setting.
	 *
	 * @param  string $name Name of the setting.
	 * @param  string $description Description of the setting.
	 * @param  array  $options Options of the setting.
	 */
	public function __construct( $name, $description, $options ) {
		parent::__construct( $name, $description, $options );
	}

	/**
	 * Add control to display
	 *
	 * @param  WP_Customize_Manager $wp_customize Manager of theme customization.
	 * @return void
	 */
	public function show_setting( $wp_customize ) {

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				parent::get_id() . '-lat',
				array(
					'label'   => __( 'Latitude', 'pixelscodex_core' ),
					'section' => parent::get_section(),
					'type'    => 'text',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				parent::get_id() . '-lng',
				array(
					'label'   => __( 'Longitude', 'pixelscodex_core' ),
					'section' => parent::get_section(),
					'type'    => 'text',
				)
			)
		);
	}

	/**
	 * Add settings to the customizer
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

		$wp_customize->add_setting( parent::get_id() . '-lat', $args );
		$wp_customize->add_setting( parent::get_id() . '-lng', $args );

		$this->show_setting( $wp_customize );
	}
}
