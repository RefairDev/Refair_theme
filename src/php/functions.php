<?php
/**
 * REFAIR functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package refair
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// UnderStrap's includes directory.
$theme_inc_dir = get_template_directory() . '/inc';
$theme_cfg_dir = get_template_directory() . '/cfg';

require_once $theme_inc_dir . '/core/Agent/class-agent-meta-parameters.php';
require_once $theme_inc_dir . '/core/Agent/class-agent.php';

use Pixelscodex\Agent_Meta_Parameters;
use Pixelscodex\Agent;

$agent = new Agent( 'refair-theme' );

$agent->init();

$agent->add_header_css( 'refair-admin-style', 'admin.css', 'admin' );



if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	$agent->add_header_vendor_script( 'chart', 'chartjs/chart.js', 'public', array() );
	$agent->add_header_vendor_script( 'chart-datalabels', 'chartjs-plugin-datalabels/chartjs-plugin-datalabels.js', 'public', array() );
} else {
	$agent->add_header_vendor_script( 'chart', 'chartjs/chart.min.js', 'public', array() );
	$agent->add_header_vendor_script( 'chart-datalabels', 'chartjs-plugin-datalabels/chartjs-plugin-datalabels.min.js', 'public', array() );
}

$agent->add_header_vendor_script( 'd3chart', 'https://cdn.jsdelivr.net/npm/d3@7', 'public', array() );

$agent->add_header_script( 'scripts-public', 'scripts_public.js', 'public', array() );
$agent->add_footer_script( 'scripts-admin', 'scripts_admin.js', 'admin', array( 'jquery' ) );
$agent->add_header_script( 'bundle-public', 'bundle.js', 'public', array() );


$script_properties = array(
	'img_url'                => get_stylesheet_directory_uri() . '/images',
	'ajax_url'               => admin_url( 'admin-ajax.php' ),
	'rest_url'               => get_rest_url( null, 'wp/v2/' ),
	'woo_rest_url'           => get_rest_url( null, 'wc-api/v3/' ),
	'rest_materials'         => get_rest_url( null, 'materials/get/' ),
	'rest_materials_filters' => get_rest_url( null, 'materials/filters/' ),
	'rest_deposits'          => get_rest_url( null, 'wp/v2/deposit/' ),
	'rest_locations'         => get_rest_url( null, 'locations/get/' ),
);

if ( false !== get_theme_mod( 'custom_logo' ) ) {
	$script_properties['default_thumbnail'] = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];

}

$agent->set_script_properties(
	'scripts-public',
	'admin_objects',
	$script_properties
);

$json         = file_get_contents( get_template_directory() . '/geojson/IRIS_COM_centroids.json' );
$outline_json = file_get_contents( get_template_directory() . '/geojson/Outline_COM.json' );
$agent->set_script_properties(
	'bundle-public',
	'geojson',
	array(
		'iris'     => $json,
		'outlines' => $outline_json,
		'styles'   => array(
			'default'   => array(
				'stroke'       => '#205c66',
				'str_opacity'  => 0.6,
				'fill'         => 'white',
				'fill_opacity' => 0.3,
			),
			'highlight' => array(
				'stroke'       => '#153d44',
				'str_opacity'  => 0.8,
				'fill'         => 'white',
				'fill_opacity' => 0.7,
			),
		),
	)
);
$map_url = WP_CONTENT_URL . '/uploads/carte_BM_refair';

$agent->set_script_properties(
	'bundle-public',
	'map',
	array(
		'url' => $map_url,
	)
);

$theme_cfgs_files = array(
	'theme-init.php',
	'theme-setup.php',
	'theme-settings.php',
	'theme-posts-types.php',
	'theme-templates-metas.php',
);

foreach ( $theme_cfgs_files as $cfg_file ) {
	require $theme_cfg_dir . '/' . $cfg_file;
}



/**
 * Enqueue scripts and styles.
 */
function refair_scripts() {
	wp_enqueue_style( 'refair-style', get_stylesheet_uri(), array(), gmdate( 'Ymd' ) );
}
add_action( 'wp_enqueue_scripts', 'refair_scripts' );

remove_action( 'wp_head', '_block_template_viewport_meta_tag', 0 );
add_action( 'wp_head', 'refair_template_viewport_meta_tag', 0 );

/**
 * Add meta viewport in pages header.
 *
 * @return void
 */
function refair_template_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1" />' . "\n";
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';



/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

if ( ! file_exists( get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php' ) ) {
	// file does not exist... return an error.
	return new WP_Error( 'class-wp-bootstrap-navwalker-missing', __( 'It appears the class-wp-bootstrap-navwalker.php file may be missing.', 'wp-bootstrap-navwalker' ) );
} else {
	// file exists... require it.
	require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';
}


/**
 * Load woocommerce hooks
 */

require get_template_directory() . '/woocommerce/hooks.php';
require get_template_directory() . '/woocommerce/hooks_single-product.php';


if ( file_exists( get_template_directory() . '/inc/class-editor-custom-control.php' ) ) {
	require_once get_template_directory() . '/inc/class-editor-custom-control.php';
}


/* Mots courts mentions longues */

/**
 * Rendering 'mention_longue_inscription' shortcode function
 *
 * @return string html code replacing shortcode.
 */
function mention_longue_inscription() {

	$page_url  = get_theme_mod( 'pagedementionlongueinscription' );
	$link_text = __( 'here', 'refair-theme' );

	return "<a href='$page_url' target=_blank >$link_text</a>";
}
add_shortcode( 'mention_longue_inscription', 'mention_longue_inscription' );

/**
 * Rendering 'mention_longue_validation_liste' shortcode function
 *
 * @return string html code replacing shortcode.
 */
function mention_longue_validation_liste() {
	$page_url  = get_theme_mod( 'pagedementionlonguevalidation' );
	$link_text = __( 'here', 'refair-theme' );
	return "<a href='$page_url' target=_blank >$link_text</a>";
}
add_shortcode( 'mention_longue_validation_liste', 'mention_longue_validation_liste' );

/**
 * Modify content provided woocommerce_get_privacy_policy_text filter
 *
 * @param  string $text Policy text.
 * @param  string $type Location of the policy text.
 * @return string Policy text modified with shotcode.
 */
function manage_long_mentions( $text, $type ) {

	$find_replace_idx   = 0;
	$page_id            = 0;
	$long_mentions_link = '';
	$find_replace       = array(
		'[mention_longue_inscription]' => 'pagedementionlongueinscription',
		'[mention_longue_validation]'  => 'pagedementionlonguevalidation',
	);

	switch ( $type ) {
		case 'checkout':
			$find_replace_idx = 1;
			break;
		case 'registration':
			$find_replace_idx = 0;
			break;
	}

	$str_to_search      = array_keys( $find_replace )[ $find_replace_idx ];
	$str_theme_mod      = array_values( $find_replace )[ $find_replace_idx ];
	$page_id            = get_theme_mod( $str_theme_mod );
	$long_mentions_link = '<a href="' . esc_url( get_permalink( $page_id ) ) . '" class="woocommerce-terms-and-conditions-link" target="_blank">' . __( 'here', 'refair-theme' ) . '</a>';

	return str_replace( $str_to_search, $long_mentions_link, $text );
}
add_filter( 'woocommerce_get_privacy_policy_text', 'manage_long_mentions', 10, 2 );

/**
 * Set the "more link" at the end of the excerpt
 *
 * @return string The more link for the excerpt.
 */
function refair_customize_excerpt_more() {
	return sprintf(
		' <a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: post title */
		sprintf( __( 'Read more %s', 'refair-theme' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
	);
}
add_filter( 'excerpt_more', 'refair_customize_excerpt_more' );


function refair_addallowed_img_attr( $allowed_tags ) {
	if ( is_array( $allowed_tags ) && array_key_exists( 'img', $allowed_tags ) ) {
		$allowed_tags['img']['srcset'] = true;
		$allowed_tags['img']['sizes']  = true;
	}
	return $allowed_tags;
}
add_filter( 'wp_kses_allowed_html', 'refair_addallowed_img_attr', 10, 1 );
