<?php
/**
 * File containing Agent_Meta_Factory Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

use Pixelscodex\Agent_Meta_Parameters;
use Pixelscodex\Agent_Utils;
use Metas;
use PixelscodexCore\MetaView;
use PixelscodexCore\Meta;
use Pixelscodex\Metas\Utils\Meta_Wrappers;

/**
 * Class building Agent Meta Objects for further meta control duisplay.
 */
class Agent_Meta_Factory {

	/**
	 * Template name used to match at meta box creation.
	 *
	 * @var string
	 */
	private $template = '';

	/**
	 * All meta classes views instance in an array.
	 *
	 * @var array
	 */
	private static $meta_classes = array();

	/**
	 * Post type accosiated with this factory.
	 *
	 * @var string
	 */
	private $post_type = '';

	/**
	 * Slug of the factory.
	 *
	 * @var string
	 */
	protected $slug = '';

	/**
	 * Namespace of Classes used to create Metas
	 *
	 * @var string
	 */
	private static $metas_views_namespace = '\\Pixelscodex\\Metas\\Views\\';

	/**
	 * Meta factory constructor.
	 *
	 * @param  string $post_type Post type wich meta is applied for.
	 * @param  array  $options OPtions of Meta box creation.
	 */
	public function __construct( string $post_type, array $options = array() ) {

		if ( isset( $post_type ) && ! empty( $post_type ) ) {
			if ( isset( $options['template'] ) && ! empty( $options['template'] ) ) {
				$this->template = $options['template'];
				$this->slug     = $post_type . '_' . $this->template;
			} else {
				$this->slug = $post_type;
			}
			$this->post_type = $post_type;

			$metas_views_dir = get_template_directory() . '/inc/core/Pixelscodex/Metas/Views';

			$files = Agent_Utils::require( $metas_views_dir );

			foreach ( $files as $file ) {
				$classes = Agent_Utils::file_get_php_classes( $file );
				foreach ( $classes as $class ) {
					try {
						$full_class_name = self::$metas_views_namespace . $class;
						if ( property_exists( $full_class_name, 'type' ) ) {
							self::$meta_classes[ $full_class_name::$type ] = $full_class_name::get_instance();
						}
					} catch ( \Exception $exception ) {
						trigger_error( 'Meta control has no type:' . wp_kses_post( $full_class_name ) );
					}
				}
			}
		} else {
			trigger_error( 'No post type supplied' );
		}
	}

	/**
	 * Get the slug of the meta factory.
	 *
	 * @return string Slug of the factory.
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Template slug accessor.
	 *
	 * @return string template slug
	 */
	public function get_template() {
		return $this->template;
	}

	/**
	 * Creation of Meta according to input parameters.
	 *
	 * @param  Agent_Meta_Parameters $meta Meta parameters used for Meta box creation.
	 * @return Meta Meta instance created.
	 */
	public function create( Agent_Meta_Parameters $meta ) {

		$views = array();

		if ( ! isset( $meta->options ) ) {
			$meta->options = array();}
		$meta->options['template'] = $this->template;

		$meta = new Meta( $this->post_type, $meta );

		return $meta;
	}

	/**
	 * Initialize view instances
	 *
	 * @param  string $type Type of meta to build.
	 * @param  string $name Name of the meta.
	 * @param  array  $options Options used to create the meta.
	 * @return Meta_View Meta_View object
	 */
	public function build_view( $type, $name, $options ) {
		if ( array_key_exists( $type, self::$meta_classes ) ) {
			$view = new self::$meta_classes[ $type ]( $name, null, $options );
		}
		return $view;
	}
}
