<?php
/**
 * File containing Map_Setting class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

use PixelscodexCore\Setting;
use WP_Customize_Control;
use WP_Query;

/**
 * EditableMeta
 */
class Map_Setting extends Setting {

	/**
	 * Constructor of the map control setting.
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

		/* Check if PCLM is active*/
		if ( is_plugin_active( 'PCLM/bootstrap.php' ) ) {

			$choices = array();
			$args    = array(
				'post_type'   => 'pclm_map',
				'post_statut' => 'publish',
			);

			$my_query = new WP_Query( $args );
			while ( $my_query->have_posts() ) {
				$my_query->the_post();

				$choices[ get_the_ID() ] = get_the_title();
			}

			if ( 0 === count( $choices ) ) {
				$choices['0'] = __( 'No map', 'refair-theme' );}

			$wp_customize->add_control(
				'map_id_control',
				array(
					'label'    => __( 'Map to display', 'refair-theme' ),
					'section'  => 'theme_sections',
					'settings' => 'home_map_id',
					'type'     => 'select',
					'choices'  => $choices,
				)
			);
		}
	}
}
