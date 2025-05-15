<?php
/**
 * Set theme template meta for static page display
 *
 * @package refair
 */

defined( 'ABSPATH' ) || exit;

use Pixelscodex\Agent_Meta_Parameters;
use Pixelscodex\Agent_Meta_Factory;

if ( ! function_exists( 'pages_metas_setup' ) ) :

	/**
	 * Setup all pages metas
	 *
	 * @return void
	 */
	function pages_metas_setup() {
		global $agent;
		$front_page_metas     = $agent->create_page_template_metas_factory( 'template-front_page.php' );
		$approach_page_metas  = $agent->create_page_template_metas_factory( 'template-demarche.php' );
		$bdr_page_metas       = $agent->create_page_template_metas_factory( 'template-bdr.php' );
		$papl_page_metas      = $agent->create_page_template_metas_factory( 'template-plusloin.php' );
		$sites_page_metas     = $agent->create_page_template_metas_factory( 'template-deposits.php' );
		$providers_page_metas = $agent->create_page_template_metas_factory( 'template-providers.php' );

		$front_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_title',
				'Section de titre',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'image', 'background_image', "Image d'arrière plan" ),
						new Agent_Meta_Parameters( 'image', 'logo', 'Image du logo' ),
						new Agent_Meta_Parameters( 'text', 'acronym', 'Acronyme' ),
						new Agent_Meta_Parameters( 'text', 'slogan', 'sous-titre secondaire' ),
					),
				)
			)
		);

		$front_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_search',
				'Section des types de recherche',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de la section' ),
						new Agent_Meta_Parameters( 'image', 'title_icon', 'Icone de titre' ),

						new Agent_Meta_Parameters(
							'extensible',
							'search_types',
							'Types de recherche',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'search_type',
									'Type de recherche',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'image', 'feature', 'image du type' ),
											new Agent_Meta_Parameters( 'text', 'over_letter', 'lettre au survol' ),
											new Agent_Meta_Parameters( 'text', 'page_link', 'lien' ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		$front_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_news',
				'Section Actualités',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title_news', 'Titre de la section actualités' ),
						new Agent_Meta_Parameters( 'button', 'news_link', 'lien vers les actualités' ),
					),
				)
			)
		);

		$front_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_approach',
				'Section démarche',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de la section' ),
						new Agent_Meta_Parameters( 'button', 'link', 'lien' ),
						new Agent_Meta_Parameters( 'image', 'desktop_img_approach', 'illustration Desktop' ),
						new Agent_Meta_Parameters( 'image', 'mobile_img_approach', 'illustration Mobile' ),
						new Agent_Meta_Parameters(
							'extensible',
							'key_figures',
							'chiffres clés',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'key_figure',
									'chiffre clés',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'text', 'figure', 'chiffre' ),
											new Agent_Meta_Parameters( 'text', 'description', 'Description' ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		/*
		----------------------------------------------------------------
								page démarche
		----------------------------------------------------------------
		*/

		$approach_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_description',
				'Section DEscription',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de la section' ),
						new Agent_Meta_Parameters( 'image', 'side_image', 'Image latérale' ),
						new Agent_Meta_Parameters( 'editor', 'desc', 'Text description' ),
					),
				)
			)
		);

		$approach_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_principles',
				'Section principes',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de la section' ),
						new Agent_Meta_Parameters(
							'extensible',
							'principles',
							'Principes',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'tool',
									'Outil',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'text', 'title', 'Titre' ),
											new Agent_Meta_Parameters( 'editor', 'desc', 'Description' ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		$approach_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_tools',
				'Les outils',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de la section' ),
						new Agent_Meta_Parameters(
							'extensible',
							'tools',
							'Outils',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'tool',
									'Outil',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'text', 'title', 'Titre' ),
											new Agent_Meta_Parameters( 'image', 'image', 'Image' ),
											new Agent_Meta_Parameters( 'editor', 'desc', 'Description' ),
											new Agent_Meta_Parameters( 'button', 'read_more', 'Bouton' ),

										),
									)
								),
							)
						),
					),
				)
			)
		);

		$approach_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_ressources',
				'Les ressources',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de la section' ),
						new Agent_Meta_Parameters(
							'extensible',
							'ressources_categories',
							'Catégories de ressources',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'ressource_category',
									'Catégorie de ressources',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'text', 'title', 'Titre' ),
											new Agent_Meta_Parameters(
												'extensible',
												'list',
												'Liste des ressources',
												array(
													'meta' => new Agent_Meta_Parameters(
														'group',
														'tool',
														'Outil',
														array(
															'metas' => array(
																new Agent_Meta_Parameters( 'image', 'doc', 'Document' ),
																new Agent_Meta_Parameters( 'text', 'year', 'Année' ),
															),
														)
													),
												)
											),

										),
									)
								),
							)
						),
					),
				)
			)
		);

		/*
		-------------------------------------------------------------------
									Page BDR
		-------------------------------------------------------------------
		*/

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'image',
				'logo_bdr',
				'Logo base du réemploi'
			)
		);

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'text',
				'introduction_title',
				"Titre de l'introduction"
			)
		);

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'extensible',
				'introduction_details',
				"Détails de l'introduction",
				array(
					'meta' => new Agent_Meta_Parameters(
						'group',
						'detail',
						"Détail d'introduction",
						array(
							'metas' => array(
								new Agent_Meta_Parameters( 'text', 'header', 'Titre du détail' ),
								new Agent_Meta_Parameters( 'editor', 'description', 'Description du détail' ),

							),

						)
					),
				)
			),
		);

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_axonometrie',
				'Section axonometrie',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'image', 'main_infographic', 'infographie principale' ),
						new Agent_Meta_Parameters( 'image', 'main_infographic_background', 'infographie principale arrière plan' ),
						new Agent_Meta_Parameters(
							'extensible',
							'axono_layers',
							"Calques d'axonométrie",
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'axono_layer',
									"Calque d'axonométrie",
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'text', 'title', 'Titre du calque' ),
											new Agent_Meta_Parameters( 'text', 'surface', 'Surface' ),
											new Agent_Meta_Parameters( 'editor', 'description', 'description' ),
											new Agent_Meta_Parameters( 'image', 'axono_image', 'Image du calque' ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'extensible',
				'bdr_gallery',
				'Galerie',
				array(
					'meta' => new Agent_Meta_Parameters(
						'image',
						'image_gallery',
						'Image de la Galerie'
					),
				)
			)
		);

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'occupants',
				'Occupants',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'block_title', 'Titre du bloc' ),
						new Agent_Meta_Parameters(
							'extensible',
							'block_items',
							'Élements du block',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'block_item',
									'Élement de bloc',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'image', 'block_item_image', "image de l'élément" ),
											new Agent_Meta_Parameters( 'button', 'block_item_title', "Titre de l'élément" ),
											new Agent_Meta_Parameters( 'editor', 'block_item_text', "Text de l'élément" ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'block_demarche',
				'Bloc de la démarche',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'block_title', 'Titre du bloc' ),
						new Agent_Meta_Parameters( 'editor', 'block_text', 'Text du bloc' ),
						new Agent_Meta_Parameters(
							'extensible',
							'block_links',
							'Documents du bloc',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'block_link',
									'Document à télécharger',
									array(
										'metas' => array(
											new Agent_Meta_Parameters(
												'image',
												'document',
												'Document'
											),
											new Agent_Meta_Parameters(
												'text',
												'doc_details',
												'Détail du document'
											),

										),
									)
								),
							),
						),
					),
				),
			),
		);

		$bdr_page_metas->create(
			new Agent_Meta_Parameters(
				'extensible',
				'key_figures',
				'Tous les Chiffres clés',
				array(
					'meta' => new Agent_Meta_Parameters(
						'group',
						'key_figure',
						'Chiffre clé',
						array(
							'metas' => array(
								new Agent_Meta_Parameters(
									'text',
									'value',
									'Valeur du Chiffre'
								),
								new Agent_Meta_Parameters(
									'text',
									'legend',
									'Description du chiffre'
								),

							),
						)
					),
				)
			)
		);

		$papl_page_metas->create( new Agent_Meta_Parameters( 'text', 'intro', "Texte d'introduction" ) );

		$papl_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_platform_sale',
				'Section plateformes de vente de matériaux',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de section' ),
						new Agent_Meta_Parameters(
							'extensible',
							'platforms',
							'Platformes',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'eltgrp',
									"Groupes d'éléments de platforme",
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'button', 'link', 'Lien' ),
											new Agent_Meta_Parameters( 'image', 'img', 'Image de la platforme' ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		$papl_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_platform_reclycling',
				'Section plateformes de réemploi-reclyclage',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de section' ),
						new Agent_Meta_Parameters(
							'extensible',
							'platforms',
							'Platformes',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'platform',
									'platforme',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'button', 'link', 'Lien' ),
											new Agent_Meta_Parameters( 'text', 'text', 'Texte' ),
											new Agent_Meta_Parameters( 'image', 'img', 'Image de la platforme' ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		$papl_page_metas->create(
			new Agent_Meta_Parameters(
				'group',
				'section_studies',
				'Section études publiées',
				array(
					'metas' => array(
						new Agent_Meta_Parameters( 'text', 'title', 'Titre de section' ),
						new Agent_Meta_Parameters(
							'extensible',
							'studies',
							'Études',
							array(
								'meta' => new Agent_Meta_Parameters(
									'group',
									'design_office',
									'Étude',
									array(
										'metas' => array(
											new Agent_Meta_Parameters( 'button', 'link', 'Lien' ),
											new Agent_Meta_Parameters( 'text', 'text', 'Texte' ),
										),
									)
								),
							)
						),
					),
				)
			)
		);

		$providers_page_metas->create(
			new Agent_Meta_Parameters(
				'editor',
				'content_footer',
				'Pied de page de Contenu'
			)
		);
	}

	pages_metas_setup();

	endif;
