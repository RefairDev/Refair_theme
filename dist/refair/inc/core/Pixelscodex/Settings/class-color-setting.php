<?php
/**
 * File containing Color_Setting class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

use PixelscodexCore\Setting;
use WP_Customize_Color_Control;

/**
 * Color Setting for Customizer.
 */
class Color_Setting extends Setting {

	/**
	 * Constructor of the color control setting.
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
			new WP_Customize_Color_Control(
				$wp_customize,
				parent::get_id() . '_control',
				array(
					'label'    => parent::get_name(),
					'section'  => parent::get_section(),
					'settings' => parent::get_id(),
				)
			)
		);
	}
}
