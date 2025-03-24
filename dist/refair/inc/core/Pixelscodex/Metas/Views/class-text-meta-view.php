<?php
/**
 * File containing Text_Meta_View, Number_Meta_View, Date_Meta_View, Time_Meta_View, Email_Meta_View, Textarea_Meta_View, MultiText_Meta_View, Editor_Meta_View, KeyValue_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;
require_once 'class-input-meta-view.php';

/**
 * Class managing display of text input type metabox.
 */
class Text_Meta_View extends Input_Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'text';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct( $options );
		$this->set_type( 'text' );
	}
}

/**
 * Class managing display of number input type metabox.
 */
class Number_Meta_View extends Input_Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'number';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct( $options );
		$this->set_type( 'number' );
	}
}

/**
 * Class managing display of date input type metabox.
 */
class Date_Meta_View extends Input_Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'date';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct( $options );
		$this->set_type( 'date' );
	}
}

/**
 * Class managing display of time input type metabox.
 */
class Time_Meta_View extends Input_Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'time';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct( $options );
		$this->set_type( 'time' );
	}
}

/**
 * Class managing display of email input type metabox.
 */
class Email_Meta_View extends Input_Meta_View {


	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'mail';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct( $options );
		$this->set_type( 'email' );
	}
}

/**
 * Class managing display of textarea type metabox.
 */
class Textarea_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'textarea';

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
		if ( isset( $data['id'] ) ) {
			$id = $data['id'];} else {
			$re    = '/(.*)\[([0-9]+)\]/m';
			$subst = '$1-$2';
			$id    = preg_replace( $re, $subst, $data['name'] );
			}
			ob_start();
			?><p>
			<textarea name="<?php echo esc_attr( $data['name'] ); ?>" id="<?php echo esc_attr( $id ); ?>" cols=50 style="resize:both"><?php echo esc_textarea( $value ); ?></textarea>
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

/**
 * Class managing display of multitext input type metabox.
 */
class MultiText_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'multi-text';

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
		for ( $i = 0; $i < $this->options['size']; $i++ ) {
			if ( isset( $meta[ $i ] ) ) {
				$meta_value = $meta[ $i ];
			} else {
				$meta_value = '';
			}
			?>
					<p>
						<?php echo wp_kses_post( ( $i + 1 ) . '. ' ); ?><input type="text" name="<?php echo esc_attr( $data['name'] . '[' . $i . ']' ); ?>" id="<?php echo esc_attr( $data['name'] . '[' . $i . ']' ); ?>" class="meta-video regular-text" value="<?php echo wp_kses_post( $meta_value ); ?>"/>
					</p>
				<?php
		}
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


/**
 * Class managing display of editor type metabox.
 */
class Editor_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'editor';

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
		global $post;
		$settings = array(
			'textarea_name' => $data['name'],
			'quicktags'     => array( 'buttons' => 'em,strong,link' ),
			'tinymce'       => array(
				'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
				'theme_advanced_buttons2' => '',
			),
			'editor_css'    => '<style>.wp-editor-area{height:175px; width:100%;}</style>',
		);
		return $view_content . \Pixelscodex\Agent::wp_editor( $value, $data['id'] . '-' . $post->ID, $settings );
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

/**
 * Class managing display of Key/value pair type metabox.
 */
class KeyValue_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'pair';

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
		$key_value   = '';
		$value_value = '';
		if ( ! empty( $value ) && is_array( $value ) ) {
			if ( array_key_exists( 'key', $value ) ) {
				$key_value = 'value="' . $value['key'] . '"';}
			if ( array_key_exists( 'value', $value ) ) {
				$value_value = 'value="' . $value['value'] . '"';}
		}
		$viewcontent = '<p>
        <input type="text" name="' . $data['name'] . '[key]" id="' . $data['name'] . '-key" ' . $key_value . '/>
        <input type="text" name="' . $data['name'] . '[value]" id="' . $data['name'] . '-value" ' . $value_value . '/>
        </p>';
		return $viewcontent;
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
