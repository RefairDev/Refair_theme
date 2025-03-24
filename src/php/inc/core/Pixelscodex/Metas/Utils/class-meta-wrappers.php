<?php
/**
 * File containing Meta_Wrappers Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Utils;

if ( ! class_exists( 'Meta_Wrappers' ) ) {
	/**
	 * Class Managing Metabox wrapping before display.
	 */
	class Meta_Wrappers {

		/**
		 * Instance for singleton.
		 *
		 * @var array
		 */
		private static $instances = array();

		/**
		 * Constructor set action hooks.
		 */
		public function __construct() {
			add_filter( 'wrapview', array( $this, 'wrap_view' ), 10, 3 );
			add_filter( 'theme_wrapmeta_with_title', array( $this, 'wrap_meta_with_title' ), 10, 3 );
		}

		/**
		 * Get current instance for singleton.
		 *
		 * @return Meta_Wrappers Instance of Meta_Wrappers.
		 */
		public static function get_instance() {

			$class = get_called_class();
			if ( ! isset( self::$instances[ $class ] ) ) {
				self::$instances[ $class ] = new static();
			}
			return self::$instances[ $class ];
		}

		/**
		 * Wrap view with a div and a nonce.
		 *
		 * @param  string $view Meta box content.
		 * @param  string $name Name/slug fo the meta.
		 * @return string wrapped Meta box.
		 */
		public function wrap_view( $view, $name ) {

			$html  = '<div><input type="hidden" name="' . $name . '_nonce" value="' . wp_create_nonce( 'meta-nonce-' . $name ) . '">';
			$html .= $view;
			$html .= '</div>';

			return $html;
		}

		/**
		 * Wrap meta with its title.
		 *
		 * @param  string $view Meta box content.
		 * @param  string $title Title to display.
		 * @param  array  $data Data used to fill wrapper attributs and title formatting.
		 * @return string wrapped Meta box.
		 */
		public function wrap_meta_with_title( $view, $title, $data ) {

			$side_title_content = '';

			$side_title_content = apply_filters( 'theme_side_title_content_' . $data['type'], $side_title_content, $data );

			$data_type = $data['type'];
			$html      = "<div class='meta-edit-block' ><div class='meta-title-bar'><h3>$title</h3><div class='title-side-content'>$side_title_content</div></div><div class='meta-content' data-meta-type='$data_type' >$view</div></div>";

			return $html;
		}
	}
}
