<?php
/**
 * File containing Button_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;

/**
 * Class managing display of button type metabox.
 */
class Button_Meta_View extends Meta_View {

		/**
		 * Type of the meta box.
		 *
		 * @var string
		 */
	public static $type = 'button';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct(
			$options
		);
	}

	/**
	 * View generated to display the meta control.
	 *
	 * @param  string $view_content previous content of the view.
	 * @param  array  $data Data used to generate the meta view.
	 * @param  mixed  $value Previous saved value(s) to set the meta.
	 * @return string view with current meta view content added.
	 */
	public function get_view( $view_content, $data, $value = array(
		'text' => '',
		'link' => '',
	) ) {

		$value_sanitized = array(
			'text' => '',
			'link' => '',
		);
		if ( is_array( $value ) && array_key_exists( 'text', $value ) && array_key_exists( 'link', $value ) ) {
			$value_sanitized = $value;
		}
		ob_start();
		?>
		<p>
			<span>Button text:</span> 
			<input type="text" 
			name="<?php echo esc_attr( $data['name'] ); ?>[text]" 
			id="<?php echo esc_attr( $data['id'] ); ?>" 
			class="meta-video regular-text" 
			value="<?php echo esc_attr( $value_sanitized['text'] ); ?>"/>
		</p>
		<p>
			<span>Button link:</span>
			<input 
				type="text" 
				name="<?php echo esc_attr( $data['name'] ); ?>[link]" 
				id="<?php echo esc_attr( $data['id'] ); ?>" 
				class="meta-video regular-text" 
				value="<?php echo esc_attr( $value_sanitized['link'] ); ?>"/>
		</p>
		<?php
		$meta_view = ob_get_clean();
		return $view_content . $meta_view;
	}

	/**
	 * View on reduced part such as groups item header.
	 *
	 * @param  string $reduced_view_content Previous view content.
	 * @param  array  $data Data used to generate the meta view.
	 * @param  mixed  $value Previous saved value(s) to set the meta.
	 * @return string view with current meta view content added.
	 */
	public function get_reduced_view( string $reduced_view_content, array $data, $value ) {
		return '';
	}

	/**
	 * View to add near Meta title.
	 *
	 * @param  string $side_title_content Previous view content.
	 * @param  array  $data Data used to generate the meta view.
	 * @return string view with current meta view content added.
	 */
	public function get_side_title_content( $side_title_content, $data ) {
		return $side_title_content;
	}
}
