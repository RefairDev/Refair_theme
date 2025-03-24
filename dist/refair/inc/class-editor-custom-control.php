<?php
/**
 * Class defining a control using tinymce text editor
 *
 * @package refair
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class to create a custom tags control
 */
class Editor_Custom_Control extends WP_Customize_Control {

	/**
	 * Constructor of the class.
	 *
	 * @param  WP_Customize_Manager $manager Customizer Control Manager.
	 * @param  int                  $id Id of the control.
	 * @param  array                $options Options applied to the control.
	 */
	public function __construct( $manager, $id, $options ) {
		parent::__construct( $manager, $id, $options );

		global $num_customizer_teenies_initiated;
		$num_customizer_teenies_initiated = empty( $num_customizer_teenies_initiated ) ? 1 : $num_customizer_teenies_initiated + 1;
	}

	/**
	 * Queue Tinymce editor scripts.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_editor();
	}

	/**
	 * Pass our TinyMCE toolbar config to JavaScript
	 */
	public function to_json() {
		parent::to_json();

		$this->json['auroobamakes_tinymce_toolbar1'] = isset( $this->input_attrs['toolbar1'] ) ? esc_attr( $this->input_attrs['toolbar1'] ) : 'bold italic bullist numlist alignleft aligncenter alignright link';

		$this->json['auroobamakes_tinymce_toolbar2'] = isset( $this->input_attrs['toolbar2'] ) ? esc_attr( $this->input_attrs['toolbar2'] ) : '';

		$this->json['auroobamakes_tinymce_mediabuttons'] = isset( $this->input_attrs['mediaButtons'] ) && ( true === $this->input_attrs['mediaButtons'] ) ? true : false;

		$this->json['auroobamakes_tinymce_height'] = isset( $this->input_attrs['height'] ) ? esc_attr( $this->input_attrs['height'] ) : 200;
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="tinymce-control">
			<span class="customize-control-title">
			<?php echo esc_html( $this->label ); ?>
			</span>

		<?php if ( ! empty( $this->description ) ) { ?>
			<span class="customize-control-description">
				<?php echo esc_html( $this->description ); ?>
			</span>
			<?php } ?>

			<textarea id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-tinymce-editor" <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
		</div>
		<?php
	}
}
?>
