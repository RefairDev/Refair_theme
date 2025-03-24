<?php

namespace Pixelscodex;

use PixelscodexCore\Setting;
use WP_Customize_Control;
use WP_Customize_Media_Control;

/**
 * EditableSetting
 */
class ImageSetting extends Setting {

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
	 * @param  WP_Customize_Manager $wp_customize
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
				$this->id . '_image',
				array(
					'label'       => __( $this->name, 'pixelscodex_core' ),
					'section'     => $this->section,
					'mime_type'   => 'image',
					'description' => __( 'Image to display', 'pixelscodex_core' ),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$this->id . '_text',
				array(
					'label'   => __( 'Legend or url', 'pixelscodex_core' ),
					'section' => $this->section,
					'type'    => $type,
				)
			)
		);
	}

	public function exec( $wp_customize ) {

		$args = array(
			'type'       => 'theme_mod',
			'capability' => 'edit_theme_options',
			// 'theme_supports'        => ,
			'transport'  => 'refresh',
			// 'sanitize_callback'     => ,
			// 'sanitize_js_callback'  =>
		);
		if ( isset( $this->default ) ) {
			$args['default'] = $this->default;}

		$wp_customize->add_setting( $this->id . '_image', $args );
		$wp_customize->add_setting( $this->id . '_text', $args );

		$this->show_setting( $wp_customize );
	}
}
