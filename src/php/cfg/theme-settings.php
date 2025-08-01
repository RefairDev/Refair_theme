<?php
/**
 * Set theme settigns
 *
 * @package refair
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'theme_settings' ) ) :

	/**
	 * Init function of theme settings.
	 *
	 * @return void
	 */
	function theme_settings() {
		global $agent;

		$default_settings = array(
			array(
				'name'        => 'Maintenance',
				'description' => 'Activation du mode mainteance',
				'type'        => 'radio',
				'default'     => 'false',
			),

		);

		$theme_settings = array(

			array(
				'name'        => 'Page des sites',
				'description' => '',
				'type'        => 'page',
				'default'     => '',
			),
			array(
				'name'        => 'Page des Matériaux',
				'description' => '',
				'type'        => 'page',
				'default'     => '',
			),
			array(
				'name'        => 'Page des Fournisseurs',
				'description' => '',
				'type'        => 'page',
				'default'     => '',
			),
			array(
				'name'        => 'Page de Mention Longue Validation',
				'description' => '',
				'type'        => 'page',
				'default'     => '',
			),
			array(
				'name'        => 'Page de Mention Longue Inscription',
				'description' => '',
				'type'        => 'page',
				'default'     => '',
			),
			array(
				'name'        => 'Page de Mention Longue Newsletter',
				'description' => '',
				'type'        => 'page',
				'default'     => '',
			),
			array(
				'name'        => "Conditions générales d'utilisation",
				'description' => '',
				'type'        => 'page',
				'default'     => '#',
			),
			array(
				'name'        => 'Mention à cocher',
				'description' => "Pour l'inscription à la newsletter",
				'type'        => 'editor',
				'default'     => '',
			),
			array(
				'name'        => 'Mention Courte Newsletter',
				'description' => '',
				'type'        => 'editor',
				'default'     => '',
			),
			array(
				'name'        => 'Centre de carte',
				'description' => '(par défaut)',
				'type'        => 'latlng',
				'default'     => '',
			),
		);

		$global_settings = array_merge( $default_settings, $theme_settings );

		$agent->build_settings(
			array(
				'sections' => array(
					array(
						'name'        => 'Global',
						'description' => 'Ici sont ajoutées les informations globales au site',
						'settings'    => $global_settings,
					),
				),
			)
		);
	}

	theme_settings();
endif;
