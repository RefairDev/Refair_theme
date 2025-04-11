<?php
/**
 * File defining Agent class
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Exception;
use stdClass;
use PixelscodexCore\Live_Queries;
use PixelscodexCore\Ajax_Queries;
use PixelscodexCore\Section;
use Pixelscodex\Agent_Meta_Parameters;
use Pixelscodex\Agent_Meta_Factory;
use PixelscodexCore\Term_Meta;
use WP_Query;
/**
 * PixelscodexModule
 */
class Agent {

	/**
	 * ID of the site.
	 *
	 * @var string
	 */
	public $site_name = '';

	/**
	 * Images Directory.
	 *
	 * @var string
	 */
	public $img_dir = '';

	/**
	 * Css stylesheet directory.
	 *
	 * @var string
	 */
	public $css_dir = '';

	/**
	 * Css stylesheet url.
	 *
	 * @var string
	 */
	public $css_link = '';

	/**
	 * Js files directory.
	 *
	 * @var string
	 */
	public $js_dir = '';

	/**
	 * Js files url.
	 *
	 * @var string
	 */
	public $js_link = '';

	/**
	 * Vendors files directory.
	 *
	 * @var string
	 */
	public $vendor_dir = '';

	/**
	 * Vendors files url.
	 *
	 * @var string
	 */
	public $vendor_link = '';

	/**
	 * Languages files directory.
	 *
	 * @var string
	 */
	public $lang_dir = '';

	/**
	 * Core files directory.
	 *
	 * @var string
	 */
	public $core_dir = '';

	/**
	 * Current language.
	 *
	 * @var string
	 */
	public $lang = '';

	/**
	 * Details stored for navigation location creation.
	 *
	 * @var array
	 */
	public $nav = array();

	/**
	 * Array of scripts to enqueue on public and admin sides.
	 *
	 * @var array
	 */
	private $r_scripts = array(
		'admin'  => array(),
		'public' => array(),
	);

	/**
	 * Array of vendors scripts to enqueue on public and admin sides.
	 *
	 * @var array
	 */
	private $r_vendor_scripts = array(
		'admin'  => array(),
		'public' => array(),
	);

	/**
	 * Array of scripts to enqueue in the footer on public and admin sides.
	 *
	 * @var array
	 */
	private $r_footer_scripts = array(
		'admin'  => array(),
		'public' => array(),
	);

	/**
	 * Array of vendors scripts to enqueue in footer on public and admin sides.
	 *
	 * @var array
	 */
	private $r_footer_vendor_scripts = array(
		'admin'  => array(),
		'public' => array(),
	);

	/**
	 * Array of stylesheets to enqueues on public and admin sides.
	 *
	 * @var array
	 */
	private $r_stylesheets = array(
		'admin'  => array(),
		'public' => array(),
	);

	/**
	 * Array of vendors stylesheets to enqueues on public and admin sides.
	 *
	 * @var array
	 */
	private $r_vendor_stylesheets = array(
		'admin'  => array(),
		'public' => array(),
	);

	/**
	 * Array of scripts additional properties.
	 *
	 * @var array
	 */
	private $r_scripts_properties = array();

	/**
	 * Mime files types.
	 *
	 * @var array
	 */
	private $mimes = array();

	/**
	 * Post type of the core.
	 *
	 * @var array
	 */
	private $post_types = array();

	/**
	 * Array of Agent_Meta_Factory for diférrents post types.
	 *
	 * @var array
	 */
	private $metas_factories;

	/**
	 * Meta factory for post type.
	 *
	 * @var Agent_Meta_Factory
	 */
	public $post_metas_factory;

	/**
	 * Constructor of the Agent Class.
	 *
	 * @param  string $site_name Name of the theme.
	 */
	public function __construct( $site_name = '' ) {
		$this->site_name = $site_name;

		$this->img_dir = get_template_directory() . '/img';

		$this->css_dir = get_template_directory() . '/css';

		$this->css_link = get_template_directory_uri() . '/css/';

		$this->js_dir = get_template_directory() . '/js';

		$this->js_link = get_template_directory_uri() . '/js/';

		$this->vendor_dir = get_template_directory() . '/vendor';

		$this->vendor_link = get_template_directory_uri() . '/vendor/';

		$this->lang_dir = get_template_directory() . '/lang';

		$this->core_dir = get_template_directory() . '/inc/core';

		$this->lang = substr( get_locale(), 0, 2 );
	}


	/**
	 * Initialization function of the Agent.
	 *
	 * @return void
	 */
	public function init() {
		/* require core classes*/
		$this->require_core();

		/*require derived classes*/
		$this->require_classes();

		/*SVG Mime Type*/
		$this->add_mime_type( 'svg', 'image/svg+xml' );

		/*add action hooks*/
		$this->add_hooks();

		/* custom menus*/
		add_action( 'init', array( $this, 'register_initial_nav' ) );

		/*JS scritps*/
		add_action( 'init', array( $this, 'header_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'header_admin_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'footer_admin_scripts' ) );

		/*custom post types*/
		add_action( 'init', array( $this, 'custom_post_type' ) );
		add_action( 'rest_api_init', array( $this, 'create_api_posts_meta_field' ) );
		add_action( 'rest_api_init', array( $this, 'create_api_product_fields' ) );

		/* CSS stylesheets*/
		add_action( 'wp_enqueue_scripts', array( $this, 'header_css' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'header_admin_css' ) );

		/* Mime Types*/
		add_filter( 'upload_mimes', array( $this, 'cc_mime_types' ) );

		/* mail content type */
		add_filter( 'wp_mail_content_type', array( $this, 'mail_content_type' ) );

		/* add query var to rest queries */
		add_filter( 'rest_query_vars', array( $this, 'json_api_add_filters' ) );

		load_theme_textdomain( 'pixelscodex', get_template_directory() . '/languages' );

		$this->add_mail_rest_endpoint();

		$this->post_metas_factory = $this->create_meta_factory( 'post' );
	}

	/**
	 * Setup hooks.
	 *
	 * @return void
	 */
	private function add_hooks() {
		add_action( 'admin_print_footer_scripts', array( $this, 'image_meta_script' ) );
	}

	/**
	 * Require images browsing scripts for metas.
	 *
	 * @return void
	 */
	public function image_meta_script() {
		require_once __DIR__ . '/script-image-meta.php';
	}

	/**
	 * Create Factory for a post type and its options.
	 *
	 * @param  string $post_type Post type.
	 * @param  array  $options Options of factory creation.
	 * @return Agent_Meta_Factory Instance of the post type meta factory.
	 */
	private function create_meta_factory( $post_type, $options = array() ) {
		$factory                                       = new Agent_Meta_Factory( $post_type, $options );
		$this->metas_factories[ $factory->get_slug() ] = $factory;
		return $factory;
	}

	/**
	 * Create Factory for a page according to its template and its options.
	 *
	 * @param  string $template Template of the page.
	 * @return Agent_Meta_Factory Instance of the page meta factory.
	 */
	public function create_page_template_metas_factory( $template = '' ) {
		$factory = $this->create_meta_factory( 'page', array( 'template' => $template ) );
		return $factory;
	}


	/**
	 * Initialize text editor for control.
	 *
	 * @param  string $content Content to insert in the editor.
	 * @param  string $editor_id Id of the editor in the edit page.
	 * @param  array  $settings Settings used to initialize the editor.
	 * @return string Editor html code.
	 */
	public static function wp_editor( $content, $editor_id, $settings ) {
		ob_start();
		wp_editor( htmlspecialchars_decode( $content, ENT_QUOTES ), $editor_id, $settings );
		$out           = ob_get_contents();
		$js            = wp_json_encode( $out );
		$id_editor_ctn = $editor_id . '-ctn';
		ob_clean(); ?>
		<div id="<?php echo esc_attr( $id_editor_ctn ); ?>"></div>
		<script>
		setTimeout(function() {
				// inject editor later
				var id_ctn = '#<?php echo esc_attr( $id_editor_ctn ); ?>';
				jQuery(id_ctn).append(<?php echo $js; ?>); 
				// init editor
				setTimeout(function() {
					// find the editor button and simulate a click on it
					jQuery('#<?php echo esc_attr( $editor_id ); ?>-tmce').trigger('click');
				},   700);
		}, 1000);
		</script>
		<?php
		$out = ob_get_contents();
		ob_get_clean();
		return $out;
	}

	/**
	 * Add form action.
	 *
	 * @param  string $name Name of the action.
	 * @param  string $cb Name of the callback.
	 * @return Live_Queries Live_Queries instance.
	 */
	public function add_form_action( $name, $cb = '' ) {
		return new Live_Queries( $name, $cb );
	}

	/**
	 * Add ajax action.
	 *
	 * @param  string $name Name of the action.
	 * @param  string $cb Name of the callback.
	 * @return Ajax_Queries Ajax_Queries instance.
	 */
	public function add_ajax_action( $name, $cb = '' ) {
		return new Ajax_Queries( $name, $cb );
	}

	/**
	 *  CUSTOM TYPE MANAGMENT
	 */

	/**
	 * Create all custom post types.
	 *
	 * @return void
	 */
	public function custom_post_type() {
		foreach ( $this->post_types as $post_type ) {
			$this->create_post_type( $post_type[0], $post_type[1], $post_type[2], $post_type[3] );
		}
	}

	/**
	 * REGISTER a custom post type
	 *
	 * @param  string $type Type of the post type.
	 * @param  string $name Name.
	 * @param  array  $supports Supports applied to the post type.
	 * @param  array  $options Options used at post type creation.
	 * @return Agent_Meta_Factory Return instance of the factory.
	 */
	public function add_post_type( $type, $name, $supports, $options = array() ) {
		array_push( $this->post_types, array( $type, $name, $supports, $options ) );
		return $this->create_meta_factory( $type );
	}

	/**
	 * CREATE custom post type.
	 *
	 * @param  string $type Slug of the custom post type.
	 * @param  string $name Name of the custom post type.
	 * @param  array  $supports capabilities supported by the post type.
	 * @param  array  $options options used to create the post type.
	 * @return void
	 */
	private function create_post_type( $type, $name, $supports, $options ) {

		$options['category'] = ( isset( $options['category'] ) ) ? $options['category'] : false;

		$options['post_tag'] = ( isset( $options['post_tag'] ) ) ? $options['post_tag'] : false;

		$taxonomies = array();

		if ( false !== $options['category'] ) {
			if ( is_string( $options['category'] ) ) {
				$labels = array(
					'name'                       => $options['category'] . 's',
					'singular_name'              => $options['category'],
					'search_items'               => __( 'Search ', 'refair-theme' ) . $options['category'],
					'popular_items'              => __( 'Popular ', 'refair-theme' ) . $options['category'],
					'all_items'                  => __( 'All ', 'refair-theme' ) . $options['category'],
					'parent_item'                => null,
					'parent_item_colon'          => null,
					'edit_item'                  => __( 'Edit ', 'refair-theme' ) . $options['category'],
					'update_item'                => __( 'Update ', 'refair-theme' ) . $options['category'],
					'add_new_item'               => __( 'Add New ', 'refair-theme' ) . $options['category'],
					'new_item_name'              => __( 'New ', 'refair-theme' ) . $options['category'] . __( ' Name', 'refair-theme' ),
					'separate_items_with_commas' => __( 'Separate writers with commas', 'refair-theme' ),
					'add_or_remove_items'        => __( 'Add or remove', 'refair-theme' ) . $options['category'],
					'choose_from_most_used'      => __( 'Choose from the most used ', 'refair-theme' ) . $options['category'],
					'not_found'                  => __( 'No ', 'refair-theme' ) . $options['category'] . __( ' found.', 'refair-theme' ),
					'menu_name'                  => $options['category'] . 's',
				);

				$args = array(
					'label'        => $options['category'] . 's',
					'labels'       => $labels,
					'hierarchical' => true,
					'show_in_rest' => true,
				);

				register_taxonomy( sanitize_key( $options['category'] ), $type, $args );

				register_taxonomy_for_object_type( sanitize_key( $options['category'] ), $type );

				array_push( $taxonomies, sanitize_key( $options['category'] ) );
			} else {
				register_taxonomy_for_object_type( 'category', $type );

				array_push( $taxonomies, 'category' );
			}
		}

		if ( $options['post_tag'] ) {
			register_taxonomy_for_object_type( 'post_tag', $type );

			array_push( $taxonomies, 'post_tag' );
		}

		register_post_type(
			$type,
			array(
				'labels'       => array(
					'name'               => $name,
					'singular_name'      => $name,
					'add_new'            => __( 'Add New', 'Pixelscodex' ),
					'add_new_item'       => __( 'Add New ', 'Pixelscodex' ) . $name,
					'edit'               => __( 'Edit', 'Pixelscodex' ),
					'edit_item'          => __( 'Edit', 'Pixelscodex' ) . ' ' . $name,
					'new_item'           => __( 'New', 'Pixelscodex' ) . ' ' . $name,
					'view'               => __( 'View', 'Pixelscodex' ) . ' ' . $name,
					'view_item'          => __( 'View', 'Pixelscodex' ) . ' ' . $name,
					'search_items'       => __( 'Search', 'Pixelscodex' ) . ' ' . $name,
					/* translators: %s is replaced with the name of custom post type */
					'not_found'          => sprintf( __( 'No %s found', 'Pixelscodex' ), $name ),
					/* translators: %s is replaced with the name of custom post type */
					'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'Pixelscodex' ), $name ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'hierarchical' => true,
				'has_archive'  => true,
				'supports'     => $supports,
				'can_export'   => true,
				'taxonomies'   => $taxonomies,
			)
		);
	}

	/**
	 * Add extra post fields to REST response.
	 *
	 * @return void
	 */
	public function create_api_posts_meta_field() {

		foreach ( $this->post_types as $post_type ) {
			register_rest_field(
				$post_type[0],
				'post-meta-fields',
				array(
					'get_callback' => array( $this, 'get_post_meta_for_api' ),
					'schema'       => null,
				)
			);
		}
	}

	/**
	 * Gather values for the extra field of the REST response.
	 *
	 * @param  array $post_object Object to get metas as extra fields.
	 * @return array Metas to add to REST response.
	 */
	public function get_post_meta_for_api( $post_object ) {

		$post_id = $post_object['id'];

		$raw_metas = get_post_meta( $post_id );
		$metas     = array();
		foreach ( $raw_metas as $key => $raw_meta ) {
			if ( is_serialized( $raw_meta[0] ) ) {
				$metas[ $key ] = maybe_unserialize( $raw_meta[0] );
			} else {
				$metas[ $key ] = $raw_meta[0];
			}
		}
		return $metas;
	}

	/**
	 * Register extra fields in REST response for products
	 *
	 * @return void
	 */
	public function create_api_product_fields() {
		register_rest_field(
			'product',
			'woocommerce',
			array(
				'get_callback' => array( $this, 'get_product_data' ),
				'schema'       => null,
			)
		);
		register_rest_field(
			'product',
			'taxonomy',
			array(
				'get_callback' => array( $this, 'get_product_categories' ),
				'schema'       => null,
			)
		);
		register_rest_field(
			'product',
			'deposit',
			array(
				'get_callback' => array( $this, 'get_product_deposit' ),
				'schema'       => null,
			)
		);
	}

	/**
	 * Get all product data.
	 *
	 * @param  array $product_object Product whose meta have to be gathered.
	 * @return array Metas of the product.
	 */
	public function get_product_data( $product_object ) {
		$post_id    = $product_object['id'];
		$product    = wc_get_product( $post_id );
		$conditions = array();
		$dimensions = array();

		if ( 'variable' === $product->get_type() ) {
			foreach ( $product->get_available_variations() as $variation_array ) {
				$var_cond = get_post_meta( $variation_array['variation_id'], 'condition', true );
				if ( ! in_array( $var_cond, $conditions, true ) ) {
					$conditions[] = $var_cond;}
				if ( count( $conditions ) > 1 ) {
					$conditions = array( 'Divers' );}
				$dimensions = array( 'Tailles Divers' );
			}
		} else {
			$conditions[] = get_post_meta( $product->id, 'condition', true );
			if ( ! empty( $product->get_length() ) ) {
				$dimensions[] = 'L' . $product->get_length();}
			if ( ! empty( $product->get_width() ) ) {
				$dimensions[] = 'l' . $product->get_width();}
			if ( ! empty( $product->get_height() ) ) {
				$dimensions[] = 'h' . $product->get_height();}
			if ( count( $dimensions ) < 1 ) {
				$dimensions[] = '-';}
		}

		$srcs = wp_get_attachment_image_src( $product->get_image_id(), 'large_thumbnail' );
		if ( false !== $srcs ) {
			$src = $srcs[0];
		} else {
			$src = get_stylesheet_directory_uri() . '/images/logo_thumbnail.png';
		}

		$metas = array(
			'link'             => get_permalink( $product->get_id() ),
			'name'             => $product->get_name(),
			'stock_qty'        => $product->get_stock_quantity(),
			'stock_status'     => $product->get_stock_status(),
			'variations'       => $product->get_children(),
			'attributes'       => $this->extract_attributes( $product ),
			'cats'             => $product->get_categories(),
			'featured_img'     => $src,
			'gallery_ids'      => $product->get_gallery_image_ids(),
			'add_to_cart_link' => $product->add_to_cart_url(),
			'conditions'       => $conditions,
			'dimensions'       => $dimensions,
		);

		return $metas;
	}

	/**
	 * Get product attributes as array.
	 *
	 * @param  WC_product $product Product whose attroibute have to be gathered.
	 * @return array Product attributes.
	 */
	public function extract_attributes( $product ) {
		$full_attributes = $product->get_attributes();
		$attributes      = array();
		foreach ( $full_attributes  as $attribute ) {
			$attributes[ sanitize_title( $attribute->get_name() ) ] = $attribute->get_options();
		}
		return $attributes;
	}

	/**
	 * Get all terms associated to material.
	 *
	 * @param  WC_Product $product_obj input material object.
	 * @return array material terms ( family and category ) associated to input object.
	 */
	public function get_product_categories( $product_obj ) {
		$terms     = array(
			'family'   => null,
			'category' => null,
		);
		$post_id   = $product_obj['id'];
		$raw_terms = get_the_terms( $post_id, 'product_cat' );
		foreach ( $raw_terms as $term ) {
			if ( 0 === $term->parent ) {
				$terms['family'] = $term;
			} else {
				$terms['category'] = $term;
			}
		}
		return $terms;
	}

	/**
	 * Add extra data from metas to deposit data.
	 *
	 * @param  array $deposit_obj Array of deposit data.
	 * @return array Deposit data with metas fields added.
	 */
	public function get_product_deposit( $deposit_obj ) {
		$deposit = array(
			'name'         => 'Product Inventory',
			'id'           => 0,
			'link'         => '#',
			'availability' => 'Terminé',
		);
		$post_id = $deposit_obj['id'];

		$inv_ref   = get_post_meta( $post_id, 'deposit', true );
		$inv_posts = get_posts(
			array(
				'post_type'  => 'deposit',
				'meta_key'   => 'reference',
				'meta_value' => $inv_ref,
			)
		);
		if ( false !== $inv_posts && 0 !== $inv_posts ) {

			$inv_post        = $inv_posts[0];
			$deposit['name'] = $inv_post->post_title;
			$deposit['id']   = $inv_post->ID;
			$deposit['link'] = get_permalink( $inv_post->ID );
			$inv_loc         = get_post_meta( $inv_post->ID, 'location', true );
			if ( false !== $inv_loc ) {
				$re      = '/, [0-9]{5} (.*), /m';
				$matches = array();
				preg_match_all( $re, $inv_loc['location'], $matches, PREG_SET_ORDER, 0 );
				$deposit['location'] = $matches[0][1];
			}
			$inv_av = get_post_meta( $inv_post->ID, 'dismantle_date', true );
			if ( false !== $inv_av && 0 !== $inv_av ) {
				$deposit['availability'] = $inv_av;
			}

			$inv_iris = get_post_meta( $inv_post->ID, 'iris', true );
			if ( false !== $inv_iris && '' !== $inv_iris ) {
				$deposit['iris'] = $inv_iris;
			}
		}
		return $deposit;
	}


	/**
	 * Add authorized filters for API
	 *
	 * @param  array $valid_vars valid vars to REST requests.
	 * @return array valid_vars valid vars with news elements
	 */
	public function json_api_add_filters( $valid_vars ) {

		$my_filters = array( 'meta_key', 'meta_value', 'meta_compare', 'date_query' );
		$valid_vars = array_merge( $valid_vars, $my_filters );

		return $valid_vars;
	}



	/**
	 *
	 *  NEWSLETTER MANAGMENT
	 */


	/*
	*   SET html as mail content type
	*/
	/**
	 * Add mail content type.
	 *
	 * @return string Mail content type.
	 */
	public function mail_content_type() {
		return 'text/html';
	}

	/**
	 *
	 *  MIME TYPES MANAGMENT
	 */

	/*
	*   REGISTER a new Mime Type
	*/
	/**
	 * Store mime type to add at page generation.
	 *
	 * @param  string $mime Mime name.
	 * @param  string $type Type of the mime.
	 * @return void
	 */
	public function add_mime_type( $mime, $type ) {
		array_push( $this->mimes, array( $mime, $type ) );
	}

	/*
	*   ADD registered Mime Types
	*/
	/**
	 * Add stored mime types.
	 *
	 * @param  array $mimes Mimes types used by WordPress.
	 * @return array New mimes types list.
	 */
	public function cc_mime_types( $mimes ) {
		foreach ( $this->mimes as $key => $mime ) {
			$mimes[ $mime[0] ] = $mime[1];
		}

		return $mimes;
	}


	/**
	 *
	 *     CONTACT MAIL MANAGEMENT
	 */
	/**
	 * Split part of mail address in array to get Id of the mail address.
	 *
	 * @param  string $mail_address mail address to split.
	 * @return array part of the mail address.
	 */
	public function get_mail_id( $mail_address ) {
		return explode( '@', $mail_address );
	}

	/**
	 * Get mail address from splitted parts.
	 *
	 * @param  array $mail_address_id Parts of the mail address.
	 * @return string Mail address.
	 */
	public function get_mail_address( $mail_address_id ) {
		return implode( '@', $mail_address_id );
	}

	/**
	 * Add REST route to manage mail address.
	 *
	 * @return void
	 */
	public function add_mail_rest_endpoint() {

		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'mail',
					'redirect',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'get_mail_redirection' ),
						'args'                => array(
							array( 'id' => array( 'default' => false ) ),
							array( 'subject' => array( 'default' => '' ) ),
							array( 'body' => array( 'default' => '' ) ),
						),
						'permission_callback' => function () {
							return true;},
					)
				);
			}
		);
	}


	/**
	 * Redirect to mail address.
	 *
	 * @param  WP_REST_Request $request Request received from client.
	 * @return void
	 */
	public function get_mail_redirection( $request ) {

		$mail_subject = $request->get_param( 'subject' );
		$mail_body    = $request->get_param( 'body' );
		$mail_address = $this->get_mail_address( array( $request->get_param( 'local' ), $request->get_param( 'domain' ) ) );

		if ( ! empty( $mail_subject ) ) {
			$mail_subject_formatted = '?subject=' . rawurlencode( $mail_subject );
		} else {
			$mail_subject_formatted = '';}
		if ( ! empty( $mail_body ) ) {
			$mail_body_formatted = '&body=' . rawurlencode( $mail_body );
		} else {
			$mail_body_formatted = '';}

		$mailto_url = $mail_address . $mail_subject_formatted . $mail_body_formatted;

		header( 'Location: mailto:' . $mailto_url );
		exit;
	}


	/**
	 *
	 *  STYLESHEET MANAGMENT
	 */

	/*
	*   REGISTER a stylesheet
	*/

	/**
	 * Add stylesheet to be stored for further enqueue.
	 *
	 * @param  string $name Name of the stylesheet.
	 * @param  string $file filepath of the stylesheet.
	 * @param  string $side Between admin and public side.
	 * @param  array  $dep Dependencies of the stylesheet.
	 * @return void
	 */
	public function add_header_css( $name, $file, $side, $dep = array() ) {
		$this->add_header_all_css( $name, $file, $side, $this->r_stylesheets, $dep );
	}

	/**
	 * Add vendor stylesheet to be stored for further enqueue.
	 *
	 * @param  string $name Name of the stylesheet.
	 * @param  string $file filepath of the stylesheet.
	 * @param  string $side Between admin and public side.
	 * @param  array  $dep Dependencies of the stylesheet.
	 * @return void
	 */
	public function add_header_vendor_css( $name, $file, $side, $dep = array() ) {
		$this->add_header_all_css( $name, $file, $side, $this->r_vendor_stylesheets, $dep );
	}


	/**
	 * Store in a list css files to be enqueue on page rendering.
	 *
	 * @param  string $name Name of the css stylesheet.
	 * @param  string $file filepath to the stylesheet.
	 * @param  string $side Between admin/public side to enqueue.
	 * @param  array  $stylesheets_list List where to enqueue.
	 * @param  array  $dep Dependencies of the stylesheet.
	 * @return void
	 */
	public function add_header_all_css( $name, $file, $side, &$stylesheets_list, $dep = array() ) {
		switch ( $side ) {
			case 'both':
				array_push(
					$stylesheets_list['admin'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				/* No break go to public case */
			case 'public':
				array_push(
					$stylesheets_list['public'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				break;
			case 'admin':
				array_push(
					$stylesheets_list['admin'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				break;
		}
	}


	/**
	 * ADD registered public stylesheets.
	 *
	 * @return void
	 */
	public function header_css() {
		foreach ( $this->r_stylesheets['public'] as $stylesheet ) {
			wp_enqueue_style( $stylesheet['name'], get_template_directory_uri() . '/' . $stylesheet['file'], $stylesheet['dep'], '1.0.0' );
		}

		foreach ( $this->r_vendor_stylesheets['public'] as $stylesheet ) {

			if ( ! filter_var( $stylesheet['file'], FILTER_VALIDATE_URL ) === false ) {
				$vendor_link = $stylesheet['file'];
			} else {
				$vendor_link = $this->vendor_link . $stylesheet['file'];
			}

			wp_enqueue_style( $stylesheet['name'], $vendor_link, $stylesheet['dep'], '1.0.0' );

		}
	}

	/**
	 * ADD registered stylesheets.
	 *
	 * @return void
	 */
	public function header_admin_css() {
		foreach ( $this->r_stylesheets['admin'] as $stylesheet ) {
			wp_enqueue_style( $stylesheet['name'], get_template_directory_uri() . '/' . $stylesheet['file'], $stylesheet['dep'], '1.0.0' );
		}

		foreach ( $this->r_vendor_stylesheets['admin'] as $stylesheet ) {

			if ( ! filter_var( $stylesheet['file'], FILTER_VALIDATE_URL ) === false ) {
				$vendor_link = $stylesheet['file'];
			} else {
				$vendor_link = $this->vendor_link . $stylesheet['file'];
			}

			wp_enqueue_style( $stylesheet['name'], $vendor_link, $stylesheet['dep'], '1.0.0' );

		}
	}

	/**
	 *
	 *  SCRIPTS MANAGMENT
	 */

	/**
	 * REGISTER a JS script
	 *
	 * @param  string $name Name of the script.
	 * @param  string $file Filepath to the script.
	 * @param  string $side Between admin/public side.
	 * @param  array  $dep Dependencies of the script.
	 * @return void
	 */
	public function add_header_script( $name, $file, $side, $dep = array() ) {
		$this->add_header_all_script( $name, $file, $side, $this->r_scripts, $dep );
	}

	/**
	 * REGISTER a Vendor JS script
	 *
	 * @param  string $name Name of the script.
	 * @param  string $file Filepath to the script.
	 * @param  string $side Between admin/public side.
	 * @param  array  $dep Dependencies of the script.
	 * @return void
	 */
	public function add_header_vendor_script( $name, $file, $side, $dep = array() ) {
		$this->add_header_all_script( $name, $file, $side, $this->r_vendor_scripts, $dep );
	}

	/**
	 * Store in a list scripts files to be enqueue on page rendering.
	 *
	 * @param  string $name Name of the script.
	 * @param  string $file Filepath to the script.
	 * @param  string $side Between admin/public side.
	 * @param  array  $scripts_list array of registered scripts.
	 * @param  array  $dep Dependencies of the script.
	 * @return void
	 */
	public function add_header_all_script( $name, $file, $side, &$scripts_list, $dep = array() ) {

		switch ( $side ) {
			case 'both':
				array_push(
					$scripts_list['admin'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				/* no break go to public case */
			case 'public':
				array_push(
					$scripts_list['public'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				break;

			case 'admin':
				array_push(
					$scripts_list['admin'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				break;
		}
	}

	/**
	 * Store properties to be add scripts.
	 *
	 * @param  string $script_name Name of the script targeted.
	 * @param  string $property_name Name of the property to be added to script.
	 * @param  string $property_value Value of the property to be added to script.
	 * @return void
	 */
	public function set_script_properties( $script_name, $property_name, $property_value ) {
		array_push(
			$this->r_scripts_properties,
			array(
				'script' => $script_name,
				'name'   => $property_name,
				'value'  => $property_value,
			)
		);
	}

	/**
	 * ADD registered JS scripts on public side.
	 *
	 * @return void
	 */
	public function header_scripts() {
		wp_enqueue_script( 'jquery' );

		if ( 'wp-login.php' !== $GLOBALS['pagenow'] && ! is_admin() ) {

			foreach ( $this->r_scripts['public'] as $script ) {

				wp_enqueue_script( $script['name'], $this->js_link . $script['file'], $script['dep'], '1.0.0', array( 'in_footer' => false ) );

			}

			foreach ( $this->r_vendor_scripts['public'] as $script ) {

				if ( ! filter_var( $script['file'], FILTER_VALIDATE_URL ) === false ) {
					$vendor_link = $script['file'];
				} else {
					$vendor_link = $this->vendor_link . $script['file'];
				}

				wp_enqueue_script( $script['name'], $vendor_link, $script['dep'], '1.0.0', array( 'in_footer' => false ) );

			}

			foreach ( $this->r_scripts_properties as $property ) {
				$return = wp_localize_script( $property['script'], $property['name'], $property['value'] );
			}
		}
	}

	/**
	 * ADD registered JS scripts on admin side.
	 *
	 * @return void
	 */
	public function header_admin_scripts() {
		wp_enqueue_script( 'jquery' );

		if ( 'wp-login.php' !== $GLOBALS['pagenow'] && is_admin() ) {

			foreach ( $this->r_scripts['admin'] as $script ) {

				wp_enqueue_script( $script['name'], $this->js_link . $script['file'], $script['dep'], '1.0.0', array( 'in_footer' => false ) );

			}

			foreach ( $this->r_vendor_scripts['admin'] as $script ) {

				if ( ! filter_var( $script['file'], FILTER_VALIDATE_URL ) === false ) {
					$vendor_link = $script['file'];
				} else {
					$vendor_link = $this->vendor_link . $script['file'];
				}
				wp_enqueue_script( $script['name'], $vendor_link, $script['dep'] || array(), '1.0.0', array( 'in_footer' => false ) );

			}

			foreach ( $this->r_scripts_properties as $property ) {
				$return = wp_localize_script( $property['script'], $property['name'], $property['value'] );
			}
		}
	}


	/**
	 * Store scripts for further enqueue in footer.
	 *
	 * @param  string $name Name of the script.
	 * @param  string $file Filepath of the script.
	 * @param  string $side Public or admin.
	 * @param  array  $dep Dependencies of the script.
	 * @return void
	 */
	public function add_footer_script( $name, $file, $side, $dep = array() ) {
		$this->add_footer_all_script( $name, $file, $side, $this->r_footer_scripts, $dep );
	}

	/**
	 * Store vendors scripts for further enqueue in footer.
	 *
	 * @param  string $name Name of the script.
	 * @param  string $file Filepath of the script.
	 * @param  string $side Public or admin.
	 * @param  array  $dep Dependencies of the script.
	 * @return void
	 */
	public function add_footer_vendor_script( $name, $file, $side, $dep = array() ) {
		$this->add_footer_all_script( $name, $file, $side, $this->r_footer_vendor_scripts, $dep );
	}

	/**
	 * Store script details to be added in the footer.
	 *
	 * @param  string $name Name of the script.
	 * @param  string $file Filepath of the script.
	 * @param  string $side Public or admin.
	 * @param  array  $scripts_list List where the script is stored.
	 * @param  array  $dep Dependencies of the script.
	 * @return void
	 */
	public function add_footer_all_script( $name, $file, $side, &$scripts_list, $dep = array() ) {

		switch ( $side ) {
			case 'both':
				array_push(
					$scripts_list['admin'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				/* next go to 'public' case */
			case 'public':
				array_push(
					$scripts_list['public'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				break;
			case 'admin':
				array_push(
					$scripts_list['admin'],
					array(
						'name' => $name,
						'file' => $file,
						'dep'  => $dep,
					)
				);
				break;
		}
	}

	/**
	 * Store script details to be added in the footer.
	 *
	 * @return void
	 */
	public function footer_admin_scripts() {
		if ( 'wp-login.php' !== $GLOBALS['pagenow'] && is_admin() ) {

			foreach ( $this->r_footer_scripts['admin'] as $script ) {

				wp_enqueue_script( $script['name'], $this->js_link . $script['file'], $script['dep'], '1.0.0', true );

			}

			foreach ( $this->r_footer_vendor_scripts['admin'] as $script ) {

				if ( ! filter_var( $script['file'], FILTER_VALIDATE_URL ) === false ) {
					$vendor_link = $script['file'];
				} else {
					$vendor_link = $this->vendor_link . $script['file'];
				}
				wp_enqueue_script( $script['name'], $vendor_link, $script['dep'] || array(), '1.0.0', true );

			}
		}
	}


	/**
	 *
	 *  NAV MANAGMENT
	 */

	/**
	 * REGISTER new navbar
	 *
	 * @param  array $nav Navigation data for further registering.
	 * @return void
	 */
	public function register_nav( $nav ) {

		if ( isset( $this->nav ) && $this->nav ) {

			if ( is_array( $nav ) ) {
				$this->nav = array_merge( $this->nav, $nav );
			}
			if ( is_string( $nav ) ) {
				array_push( $this->nav, $nav );
			}
		} else {
			if ( is_array( $nav ) ) {
				$this->nav = $nav;
			}
			if ( is_string( $nav ) ) {
				$this->nav = array( $nav );
			}
		}
	}

	/**
	 * SAVE all nav previously registered
	 *
	 * @return void
	 */
	public function register_initial_nav() {

		if ( isset( $this->nav ) && $this->nav ) {
			register_nav_menus(
				$this->nav
			);
		}
	}

	/**
	 * RETURN registered nav
	 *
	 * @param  string $location Location of the nav menu.
	 * @param  string $class_name Class name of the ul wrapper tag.
	 * @return string Menu output.
	 */
	public function get_nav( $location, $class_name = '' ) {
		return wp_nav_menu(
			array(
				'theme_location'  => $location,
				'menu'            => '',
				'container'       => 'div',
				'container_class' => 'menu-{menu slug}-container',
				'container_id'    => '',
				'menu_class'      => 'menu',
				'menu_id'         => '',
				'echo'            => false,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul class="' . $class_name . '">%3$s</ul>',
				'depth'           => 0,
				'walker'          => '',
			)
		);
	}


	/**
	 * RETURN parsed JSON
	 *
	 * @param  string  $raw raw data to be parsed.
	 * @param  integer $options Options for json_decoding.
	 * @return string Json parsed.
	 * @throws Exception Exception throw on parse error.
	 */
	public static function parse_json_data( $raw, $options = 0 ) {
		$json       = json_decode( $raw, $options );
		$error_code = json_last_error();
		if ( $error_code > 0 ) {
			throw new Exception( "JSON ERROR\nJSON ERROR CODE: " . wp_kses_post( $error_code ), 1 );
		} else {
			return $json;
		}
	}

	/**
	 * SETTINGS MANAGMENT
	 */
	/**
	 * Build settings according to parameters.
	 *
	 * @param  array $settings Settings details used to create settings controls.
	 * @return void
	 */
	public function build_settings( $settings ) {
		if ( isset( $settings['sections'] ) ) {
			foreach ( $settings['sections'] as $section ) {
				$section_obj = new Section( $section['name'], $section['description'] );
				foreach ( $section['settings'] as $setting ) {
					$setting_obj = null;
					if ( ! isset( $setting['options'] ) ) {
						$setting['options'] = array();
					}
					switch ( $setting['type'] ) {
						case 'color':
							$setting_obj = new Color_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
						case 'radio':
							$setting_obj = new Radio_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
						case 'image':
							$setting_obj = new Image_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
						case 'text':
						case 'url':
						case 'email':
						case 'number':
						case 'date':
							$setting_obj = new Text_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
						case 'map':
							$setting_obj = new Map_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
						case 'page':
							$setting_obj = new Page_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
						case 'extensible':
							$setting_obj = new Extensible_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
						case 'editor':
							$setting_obj = new Editor_Setting( $setting['name'], $setting['description'], $setting['options'] );
							break;
					}
					if ( isset( $setting_obj ) ) {
						$section_obj->add_setting( $setting_obj );}
				}
			}
		}
	}

	/**
	 *
	 *  REQUIREMENT MANAGEMENT
	 */

	/**
	 * REQUIRE Core classes
	 *
	 * @return void
	 */
	public function require_core() {
		$this->require( $this->core_dir . '/Core' );
		$this->require( $this->core_dir . '/Pixelscodex/Metas/Utils' );

		require_once __DIR__ . '/class-agent-meta-factory.php';
		require_once __DIR__ . '/class-agent-utils.php';
	}

	/**
	 * REQUIRE Pixelscodex settings derived classes
	 *
	 * @return void
	 */
	public function require_classes() {
		$this->require( $this->core_dir . '/Pixelscodex/Settings' );
	}

	/**
	 * READ from a file and RETURN it
	 *
	 * @param  string $path Path for file to read.
	 * @return string Return file content.
	 */
	public static function read_file_sync( $path ) {
		$fd = fopen( $path, 'r' );

		$file = '';

		while ( $buffer = fread( $fd, 4096 ) ) {
			$file .= $buffer;
		}

		fclose( $fd );

		return $file;
	}

	/**
	 * REQUIRE recursively all files from specified directory
	 *
	 * @param  string $dir Root directory to scan for requirement.
	 * @return void
	 */
	private function require( $dir ) {
		$files = array_diff( scandir( $dir, 1 ), array( '.', '..', 'index.php' ) );

		foreach ( $files as $file ) {
			require_once $dir . '/' . $file;
		}
	}

	/**
	 * Convert string to UTF-8
	 *
	 * @param  string $converted_string String to convert.
	 * @return string Converted string.
	 */
	public function convert_string( $converted_string ) {
		static $use_mb = null;

		if ( is_null( $use_mb ) ) {
			$use_mb = function_exists( 'mb_convert_encoding' );
		}

		if ( $use_mb ) {

			$encoding = mb_detect_encoding( $converted_string, mb_detect_order(), true );

			if ( $encoding ) {
				return mb_convert_encoding( $converted_string, 'UTF-8', $encoding );
			} else {
				return mb_convert_encoding( $converted_string, 'UTF-8', 'UTF-8' );
			}
		} else {
			return $converted_string;
		}
	}

	/**
	 * Write in file.
	 *
	 * @param  string $file Filepath.
	 * @param  string $content Content to write.
	 * @return void
	 * @throws Exception Exception throw on file write error.
	 */
	public function fwrite( $file, $content ) {
		$fd = fopen( $file, 'w' );

		$content_length = strlen( $content );

		if ( false === $fd ) {
			throw new Exception( 'File not writable.', 1 );
		}

		$written = fwrite( $fd, $content );

		fclose( $fd );

		if ( false === $written ) {
			throw new Exception( 'Unable to write to file.', 1 );
		}
	}
}


/**
 *  Log function
 */
if ( ! function_exists( 'log_it' ) ) {
	/**
	 * Message to log.
	 *
	 * @param  string $message message to log.
	 * @return void
	 */
	function log_it( $message ) {
		if ( WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}
