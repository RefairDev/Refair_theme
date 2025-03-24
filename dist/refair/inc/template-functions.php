<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package refair
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function refair_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'refair_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function refair_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'refair_pingback_header' );


if ( ! function_exists( 'get_meta_values' ) ) {
	/**
	 * Function to grab all possible meta values of the chosen meta key.
	 *
	 * @param  string $meta_key Meta key to grab.
	 * @param  string $post_type Targeted post type.
	 * @return array Meta values for all posts.
	 */
	function get_meta_values( $meta_key, $post_type = 'post' ) {

		$posts = get_posts(
			array(
				'post_type'      => $post_type,
				'meta_key'       => $meta_key,
				'posts_per_page' => -1,
			)
		);

		$meta_values = array();
		foreach ( $posts as $post ) {
			$meta_values[] = get_post_meta( $post->ID, $meta_key, true );
		}

		return $meta_values;
	}
}


if ( ! function_exists( 'get_deposit_by_ref' ) ) {
	/**
	 * Get deposit WP_post object according to metakey deposit reference
	 *
	 * @param  string $reference deposit reference.
	 * @return mixed WP_Post | false return Deposit WP_Post or false.
	 */
	function get_deposit_by_ref( $reference ) {

		$found_posts = false;
		if ( ! empty( $reference ) ) {
			$found_posts_raw = get_posts(
				array(
					'post_type'  => 'deposit',
					'meta_key'   => 'reference',
					'meta_value' => $reference,
				)
			);

			if ( is_array( $found_posts_raw ) ) {
				$found_posts = $found_posts_raw[0];
			}
		}

		return $found_posts;
	}
}



if ( ! function_exists( 'mode_maintenance' ) ) :
	/**
	 * Function to activate maintenance mode according to user loggin and capability and maintenance moide activated
	 *
	 * @return void
	 */
	function mode_maintenance() {
		if ( ( ! is_user_logged_in() || ! current_user_can( 'read' ) ) && 'true' === get_theme_mod( 'maintenance' ) ) {

			$template = get_template_part( 'template-parts/maintenance' );
			if ( false === $template ) {
				$template = '<h1 style="text-align:center">' . get_bloginfo( 'name' ) . '</h1><p style="text-align:center">Site en maintenance</p>';
			}
			wp_die( wp_kses_post( $template ) );
		}
	}
	add_action( 'get_header', 'mode_maintenance' );
endif;
