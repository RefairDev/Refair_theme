<?php

namespace Pixelscodex;

use PixelscodexCore\Setting;
use WP_Customize_Control;
use WP_Customize_Media_Control;

/**
 * EditableSetting
 */
class ExtensibleSetting extends Setting {

	/**
	 * Constructor of the extensible control setting.
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

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$this->id . '_nb',
				array(
					'label'   => __( 'Numbers of fields', 'pixelscodex_core' ),
					'section' => $this->section,
					'type'    => 'number',
				)
			)
		);

		$nb = get_theme_mod( $this->id . '_nb' );
		if ( empty( $nb ) ) {
			$nb = 1;}
		for ( $ctrl_idx = 0;$ctrl_idx < $nb; $ctrl_idx++ ) {
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					$this->id . '_text_' . strval( $ctrl_idx ),
					array(
						'label'   => __( 'field', 'pixelscodex_core' ),
						'section' => $this->section,
						'type'    => 'text',
					)
				)
			);
		}
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

		$wp_customize->add_setting( $this->id . '_nb', $args );
		$nb = get_theme_mod( $this->id . '_nb' );
		for ( $ctrl_idx = 0;$ctrl_idx < $nb; $ctrl_idx++ ) {
			$wp_customize->add_setting( $this->id . '_text_' . strval( $ctrl_idx ), $args );
		}

		$this->show_setting( $wp_customize );
	}
}
