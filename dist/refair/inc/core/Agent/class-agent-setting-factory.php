<?php
/**
 * Factory to créate setting view builders.
 *
 * @package Pixelscodex
 */

/* Exit if accessed directly.*/
defined( 'ABSPATH' ) || exit;

use Pixelscodex\Setting;
use Pixelscodex\Agent_Utils;

/**
 * Setting Factory of the Agent.
 */
class Agent_Setting_Factory {

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $template = '';

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	private static $setting_instances = array();

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $settings_root_dir = '';

	/**
	 * Class constructor
	 *
	 * @param  string $post_type post type
	 * @param  string $template 
	 */
	public function __construct( string $post_type, string $template = '' ) {
		if ( '' !== $template ) {
			$this->template = $template;
		}

		$settings_root_dir = get_template_directory() . '/inc/core/Pixelscodex/Settings';

		Agent_Utils::require( $settings_root_dir );

		$classes = Agent_Utils::file_get_php_classes( $settings_root_dir );
		foreach ( $classes as $class ) {
			$this->setting_instances[ $class::type ] = $class;
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param  Setting $setting
	 * @return void
	 */
	protected function create( Setting $setting ) {

		if ( ! isset( $setting->options ) ) {
			$setting->options = array();}
		if ( isset( $this->template ) ) {
			$setting->options['template'] = $this->template;}

		if ( $this::setting_instances.containsKey( $setting->type ) ) {
			return new $this->$setting_instances[ $setting->type ]( $setting->name, $setting->title, $this->post_type, $setting->options );
		}
	}
}
