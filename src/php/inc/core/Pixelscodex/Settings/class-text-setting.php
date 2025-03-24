<?php
/**
 * File containing Text_Setting class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

use PixelscodexCore\Setting;
use WP_Customize_Control;

/**
 * EditableMeta
 */
class Text_Setting extends Setting {

	/**
	 * Constructor of the text control setting.
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
		if ( isset( $this->options['type'] ) ) {
			$type = $this->options['type'];
		} else {
			$type = 'text';}

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$this->id . '_control',
				array(
					'label'    => $this->name,
					'section'  => $this->section,
					'settings' => $this->id,
					'type'     => $type,
				)
			)
		);
	}
}
