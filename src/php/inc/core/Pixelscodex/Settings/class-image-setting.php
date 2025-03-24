<?php
/**
 * File containing Image_Setting Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

use PixelscodexCore\Setting;
use WP_Customize_Control;
use WP_Customize_Media_Control;

/**
 * EditableSetting
 */
class Image_Setting extends Setting {

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
		if ( isset( $options['subtype'] ) ) {
			$type = $options['subtype'];
		} else {
			$type = 'text';}

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				parent::get_id() . '_image',
				array(
					'label'       => parent::get_name(),
					'section'     => parent::get_section(),
					'mime_type'   => 'image',
					'description' => __( 'Image to display', 'pixelscodex_core' ),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				parent::get_id() . '_text',
				array(
					'label'   => __( 'Legend or url', 'pixelscodex_core' ),
					'section' => parent::get_section(),
					'type'    => $type,
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

		$wp_customize->add_setting( parent::get_id() . '_image', $args );
		$wp_customize->add_setting( parent::get_id() . '_text', $args );

		$this->show_setting( $wp_customize );
	}
}
