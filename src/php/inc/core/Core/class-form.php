<?php
/**
 * File defining Form class.
 *
 * @package Pixelscodex
 */

namespace PixelscodexCore;

/**
 * Form
 */
class Form extends Live_Queries {

	/**
	 * Callback for the content of the form body.
	 *
	 * @var callable
	 */
	protected $body_cb = null;


	/**
	 * Constructor where internal variables are set.
	 *
	 * @param  string  $action Name of the action.
	 * @param  string  $cb Callback of the action.
	 * @param  boolean $priv Is public od admin form query.
	 * @param  string  $body_cb Body of the form ( all input etc.).
	 */
	public function __construct( $action, $cb = '', $priv = false, $body_cb = '' ) {
		parent::__construct( $action, $cb, $priv );

		if ( empty( $body_cb ) ) {
			$this->body_cb = array( $this, 'formBody' );
		} else {
			$this->body_cb = $body_cb;
		}
	}

	/**
	 * Display form function wrapping around body callback return.
	 *
	 * @return void
	 */
	public function displayForm() {
		$this->addStyle();
		$this->formHeader();

		call_user_func_array( $this->body_cb, array() );

		$this->formFooter();
	}

	/**
	 * Styles applied to form.
	 *
	 * @return void
	 */
	public function addStyle() {
		?>
			<style type="text/css"></style>
		<?php
	}

	/**
	 * Header of the form displayed.
	 *
	 * @return void
	 */
	public function formHeader() {
		?>
			<form
				id="<?php echo $this->name; ?>" 
				action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" 
				method="post"
			>
				<input type="hidden" name="action" value="<?php echo $this->name; ?>">
		<?php
	}

	/**
	 * Footer of the form displayed.
	 *
	 * @return void
	 */
	public function formFooter() {
		?>
					</form>
		<?php
	}

	/**
	 * Default body of the form displayed.
	 *
	 * @return void
	 */
	public function formBody() {
		echo '';
	}
}