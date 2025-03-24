<?php
/**
 * File containing Image_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;

if ( ! class_exists( 'Input_Meta_View' ) ) {
	/**
	 * Class managing display of input type metabox.
	 */
	class Input_Meta_View extends Meta_View {

		/**
		 * Type of the input handle by the meta box.
		 *
		 * @var string
		 */
		protected $input_type = '';

		/**
		 * Value of the input.
		 *
		 * @var mixed
		 */
		protected $value = null;

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
		 * Set type of the input.
		 *
		 * @param  string $type input type (text, number, date, etc.).
		 * @return void
		 */
		public function set_type( $type ) {
			$this->input_type = $type;
		}

		/**
		 * Set value of the input.
		 *
		 * @param  mixed $value Input value.
		 * @return void
		 */
		public function set_value( $value ) {
			$this->value = $value;
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
			if ( isset( $data['id'] ) ) {
				$id = $data['id'];} else {
				$re    = '/(.*)\[([0-9]+)\]/m';
				$subst = '$1-$2';
				$id    = preg_replace( $re, $subst, $data['name'] );
				}
				ob_start();
				?><p>
				<input type="<?php echo esc_attr( $this->input_type ); ?>" name="<?php echo esc_attr( $data['name'] ); ?>" id="<?php echo esc_attr( $id ); ?>" class="meta-video regular-text" value="<?php echo wp_kses_post( $value ); ?>"/>
			</p>
			<?php
			$meta_viewcontent = ob_get_clean();
			return $view_content . $meta_viewcontent;
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
}
