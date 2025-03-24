<?php
/**
 * File containing Meta_View class.
 *
 * @package Pixelscodex
 */

namespace PixelscodexCore;

/**
 * Class Meta_View for html content generation of the metabox content.
 */
abstract class Meta_View {

	/**
	 * Type of the metabox view.
	 *
	 * @var string
	 */
	public static $type = '';

	/**
	 * Instance of the metabox view generator.
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * Options for the metabox view generation.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Class constructor, set custom filters.
	 *
	 * @param  array $options for Metabox Creation.
	 */
	public function __construct(
		$options = array()
	) {
		$this->options = $options;

		$filter_name = 'theme_meta_renderview_' . static::$type;
		add_filter( $filter_name, array( $this, 'get_view' ), 10, 3 );

		$filter_name = 'theme_side_title_content_' . static::$type;
		add_filter( $filter_name, array( $this, 'get_side_title_content' ), 10, 2 );

		$filter_name = 'theme_render_reduced_view_' . static::$type;
		add_filter( $filter_name, array( $this, 'get_reduced_view' ), 10, 3 );
	}

	/**
	 * Get singleton of the Meta class.
	 *
	 * @return Meta_View Instance of the Meta.
	 */
	public static function get_instance() {

		$class = get_called_class();
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static();
		}
		return self::$instances[ $class ];
	}

	/**
	 * Get the view content of the Meta.
	 *
	 * @param  string $view_content input string of html structure of the metabox.
	 * @param  array  $data Data used to fill metabox and its subcontent.
	 * @param  mixed  $value Value of the metabox.
	 * @return string view content with metabox content added.
	 */
	public function get_view( $view_content, $data, $value = null ) {
		return $view_content;
	}

	/**
	 * Generate reduced view of the meta.
	 *
	 * @param string $reduced_view_content view content in its small version (ie for extensible header).
	 * @param  array  $data Meta data for view generation.
	 * @param  string $value Previous html content of the view.
	 * @return Html content with the meta content.
	 */
	abstract public function get_reduced_view( string $reduced_view_content, array $data, $value );


	/**
	 * Generate title for the side.
	 *
	 * @param  string $side_title_content get from the Meta_View generation content for the title.
	 * @param  array  $data Meta data for view generation.
	 * @return Html content with the meta content.
	 */
	abstract public function get_side_title_content( $side_title_content, $data );
}
