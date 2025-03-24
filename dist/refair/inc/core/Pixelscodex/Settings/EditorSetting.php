<?php
namespace Pixelscodex;

use PixelscodexCore\Setting;
use Editor_Custom_Control;

/**
 * EditableMeta
 */
class EditorSetting extends Setting {

	/**
	 * Constructor of the editor text control setting.
	 *
	 * @param  string $name Name of the setting.
	 * @param  string $description Description of the setting.
	 * @param  array  $options option of the setting
	 */
	public function __construct( $name, $description, $options ) {
		parent::__construct( $name, $description, $options );
	}


	/**
	 * Add control to display
	 *
	 * @param  WP_Customize_Manager $wp_customize
	 * @return void
	 */
	public function show_setting( $wp_customize ) {

		$wp_customize->add_control(
			new Editor_Custom_Control(
				$wp_customize,
				$this->id . '_control',
				array(
					'label'    => __( $this->name, 'refair-theme' ),
					'section'  => $this->section,
					'settings' => $this->id,
				)
			)
		);
	}
}
