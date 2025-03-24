<?php
/**
 * File containing Radio_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;
/**
 * Class managing display of radio input type metabox.
 */
class Radio_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'radio';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct( $options );
	}

	/**
	 * View generated to display the meta control.
	 *
	 * @param  string $view_content previous content of the view.
	 * @param  array  $data Data used to generate the meta view.
	 * @param  mixed  $value Previous saved value(s) to set the meta.
	 * @return string view with current meta view content added.
	 */
	public function get_view( $view_content, $data, $value = null ) {

		ob_start();
		?>
		<p>
		<?php
		foreach ( $data['options']['choices'] as $choice_name => $choice_value ) {

			?>
				<label for="<?php echo esc_attr( $data['name'] . '-' . $choice_value ); ?>">
					<input 
						type="radio" 
						name="<?php echo esc_attr( $data['name'] ); ?>"
						id="<?php echo esc_attr( $data['name'] . '-' . $choice_value ); ?>"
						value="<?php echo wp_kses_post( $value ); ?>"
					<?php if ( $choice_value === $value ) : ?>
							checked
						<?php endif; ?>
						/>
				<?php echo wp_kses_post( $choice_name ); ?>
				</label>
				<?php
		}
		?>
		</p>
		<?php
		$local_view_content = ob_get_clean();
		return $view_content . $local_view_content;
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
