<?php
/**
 * File of init function of the theme.
 *
 * @package refair
 */

/**
 * Init function of widget sidebars.
 *
 * @return void
 */
function refair_theme_widgets_init() {

	if ( function_exists( 'register_sidebar' ) ) {
		register_sidebar(
			array(
				'name'          => __( 'Manual', 'Refair-theme' ),
				'id'            => 'manual',
				'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Footer', 'Refair-theme' ),
				'id'            => 'footer',
				'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
			)
		);

	}
}
add_action( 'widgets_init', 'refair_theme_widgets_init' );
