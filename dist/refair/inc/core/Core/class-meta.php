<?php
/**
 * File containg Meta class
 *
 * @package Pixelscodex
 */

namespace PixelscodexCore;

use Pixelscodex\Agent_Meta_Parameters;
use Pixelscodex\Metas\Views;
use Pixelscodex\Metas\Utils\Meta_Wrappers;
/**
 * Meta Class managing registration display and saving.
 */
class Meta {

	/**
	 * Name of the meta.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * Type of the meta.
	 *
	 * @var string
	 */
	public $type = '';

	/**
	 * Post type targeted by the meta.
	 *
	 * @var string
	 */
	public $post_type = '';

	/**
	 * Description of the meta.
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Title of the meta.
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * Nonce for saving values.
	 *
	 * @var string
	 */
	public $nonce = '';

	/**
	 * Options used at its creation.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * View of the meta for display.
	 *
	 * @var string
	 */
	protected $view = '';

	/**
	 * Constructor of the meta setting internal variables, initialize actions and wakeup Meta_Wrappers singleton.
	 *
	 * @param  string                $post_type Post type meat applied to.
	 * @param  Agent_Meta_Parameters $meta Input parameter for meta creation.
	 */
	public function __construct( $post_type, $meta ) {
		$this->name = $meta->name;

		$this->description = $meta->title;
		$this->title       = $meta->title;

		$this->post_type = $post_type;

		$this->options = $meta->options;

		$this->type = $meta->type;

		$this->nonce = str_replace( '_meta', '', $this->name ) . '_nonce';

		\Pixelscodex\Metas\Utils\Meta_Wrappers::get_instance();

		$this->init();
	}

	/**
	 * Initialization of actions.
	 *
	 * @return void
	 */
	private function init() {
		add_action( 'current_screen', array( $this, 'set_edit_actions' ) );
	}

	/**
	 * Action set form others actions.
	 *
	 * @param  WP_Screen $screen Current screen displayed for situation analysis.
	 * @return void
	 */
	public function set_edit_actions( $screen ) {

		if ( $screen->post_type === $this->post_type && 'post' === $screen->base ) {

			add_action( 'add_meta_boxes', array( $this, 'exec' ) );
			if ( 'post' !== $this->post_type ) {
				$save_post_hook = 'save_post_' . $this->post_type;
			} else {
				$save_post_hook = 'save_post';
			}
			add_action( $save_post_hook, array( $this, 'save' ) );
		}
	}

	/**
	 * Set view to display on rendering.
	 *
	 * @param  callable $views View for rendering.
	 * @return void
	 */
	public function set_view( $views ) {
		$this->view = $views;
	}

	/**
	 * Save meta values
	 *
	 * @param  int    $post_id Post id whose meta is linked.
	 * @param  string $meta_name Meta name for value identification.
	 * @param  string $nonce nonce value for client authentification.
	 * @return mixed Return true on success or post_id on fail.
	 */
	private function save_meta( $post_id, $meta_name, $nonce ) {

		/*
		if ( !wp_verify_nonce( $_POST[$nonce], 'my-action_'.$post_id ) ) {
			return $post_id;
		}
		*/

		if ( isset( $_POST['action'] ) && 'inline-save' === $_POST['action'] ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		$old = get_post_meta( $post_id, $meta_name, true );
		$new = ( isset( $_POST[ $meta_name ] ) && ! empty( $_POST[ $meta_name ] ) ) ? $_POST[ $meta_name ] : '';

		if ( $new && $new !== $old ) {
			update_post_meta( $post_id, $meta_name, $new );
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $post_id, $meta_name, $old );
		}
		return true;
	}

	/**
	 * Save meta callback action.
	 *
	 * @param  int $post_id Post Id meta saved.
	 * @return mixed Return true on success or post_id on fail.
	 */
	public function save( $post_id ) {
		return $this->save_meta( $post_id, $this->name, $this->nonce );
	}

	/**
	 * Display meta content.
	 *
	 * @return void
	 */
	public function show_meta() {

		global $post;

		$value = null;
		$meta  = get_post_meta( $post->ID, $this->name, true );

		if ( ! empty( $meta ) ) {
			$value = $meta;
		}

		$meta_data = array(
			'id'             => $this->name,
			'name'           => $this->name,
			'meta_name'      => $this->name,
			'full_meta_name' => $this->name,
			'type'           => $this->type,
			'title'          => $this->title,
			'options'        => $this->options,
		);

		$view_content = '';
		$view_content = apply_filters(
			'theme_meta_renderview_' . $this->type,
			$view_content,
			$meta_data,
			$value
		);

		$view_content              = $view_content . $this->get_script_launcher();
		$wrapped_meta_view_content = apply_filters(
			'theme_wrapmeta_with_title',
			$view_content,
			$this->title,
			$meta_data
		);

		echo apply_filters(
			'wrapview',
			$wrapped_meta_view_content,
			$this->name,
			$post->ID
		);
	}

	/**
	 * Prepare and return script display.
	 *
	 * @return string
	 */
	protected function get_script_launcher() {
		ob_start();
		?>
			<script>
				window.addEventListener("load", function(){
					if (typeof <?php echo esc_attr( $this->type . $this->name ); ?>Script == 'function'){<?php echo esc_attr( $this->type . $this->name ); ?>Script()};
				}
			);            
			</script>
		<?php
		return ob_get_clean();
	}

	/**
	 * Setup metaboxes for current screen according to internal variables.
	 *
	 * @return void
	 */
	public function exec() {
		global $post;
		if ( 'page' === $this->post_type ) {

			$page_template = get_post_meta( $post->ID, '_wp_page_template', true );

			if ( is_array( $this->options ) && isset( $this->options['template'] ) ) {
				if ( $page_template === $this->options['template'] ) {
					add_meta_box(
						$this->name, // $id
						$this->description, // $title
						array( $this, 'show_meta' ), // $callback
						$this->post_type, // $screen
						'normal', // $context
						'high' // $priority
					);
				}
			} else {
				add_meta_box(
					$this->name, // $id
					$this->description, // $title
					array( $this, 'show_meta' ), // $callback
					$this->post_type, // $screen
					'normal', // $context
					'high' // $priority
				);
			}
		} else {
			add_meta_box(
				$this->name, // $id
				$this->description, // $title
				array( $this, 'show_meta' ), // $callback
				$this->post_type, // $screen
				'normal', // $context
				'high' // $priority
			);
		}
	}
}
