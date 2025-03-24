<?php
/**
 * Set theme supports, navigation menu registering, images sizes, i18n and theme content witdh.
 *
 * @package refair
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'add_theme_supports' ) ) :

	/**
	 * Set theme supports
	 *
	 * @return void
	 */
	function add_theme_supports() {

		add_theme_support( 'custom-logo' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
endif;



if ( ! function_exists( 'register_navs' ) ) :
	/**
	 * Theme navigation registering.
	 *
	 * @return void
	 */
	function register_navs() {
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1'            => esc_html__( 'Principal', 'refair-theme' ),
				'menu-footer-left'  => esc_html__( 'Left footer', 'refair-theme' ),
				'menu-footer-right' => esc_html__( 'Right footer', 'refair-theme' ),
			)
		);
	}
endif;

if ( ! function_exists( 'add_images_sizes' ) ) :
	/**
	 * Set theme media sizes auto-generation.
	 *
	 * @return void
	 */
	function add_images_sizes() {
		add_image_size( 'large_thumbnail', 180, 180, true );
		add_image_size( 'fullhd', 1920, 0, false );
		add_image_size( 'container_width', 1140, 0, false );
		add_image_size( 'container_two_third_width', 800, 0, false );
		add_image_size( 'container_half_width', 570, 0, false );
		add_image_size( 'container_third_width', 380, 0, false );
		add_image_size( 'container_third_width_sqr', 380, 250, true );

		add_image_size( 'container_half_width_sqr', 570, 570, true );
	}
endif;


if ( ! function_exists( 'theme_setup' ) ) :
	/**
	 * Call all initialization et setting function
	 *
	 * @return void
	 */
	function theme_setup() {
		load_theme_textdomain( 'refair-theme', get_template_directory() . '/languages' );
		add_theme_supports();
		register_navs();
		add_images_sizes();
	}

endif;
add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function theme_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'theme_content_width', 0 );
