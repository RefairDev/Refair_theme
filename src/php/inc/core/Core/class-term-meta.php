<?php
/**
 *  File containing Term_Meta Class
 *
 * @package Pixelscodex
 */

namespace PixelscodexCore;

use Pixelscodex\Agent_Meta_Parameters;

/**
 * Class for Terms Meta control management
 */
class Term_Meta {

	/**
	 * Name of the meta term.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Description of the meta term.
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Taxonomy of the term.
	 *
	 * @var string
	 */
	protected $taxonomy = '';

	/**
	 * Postype associted to the taxonomy.
	 *
	 * @var string
	 */
	protected $post_type = '';

	/**
	 * Options used at term meta creation.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Type of Meta created.
	 *
	 * @var string
	 */
	protected $type = '';

	/**
	 * Nonce for saving.
	 *
	 * @var string
	 */
	protected $nonce = null;

	/**
	 * Constructor of the class. Set internal vairables according to input parameters and setup hooks.
	 *
	 * @param  string                $post_type Post type to which the meta is applied.
	 * @param  string                $taxonomy Taxonomy which term is linked.
	 * @param  Agent_Meta_Parameters $meta Creation parameters.
	 */
	public function __construct( $post_type, $taxonomy, $meta ) {
		$this->name = $meta->name;

		$this->description = $meta->title;

		$this->taxonomy = $taxonomy;

		$this->post_type = $post_type;

		$this->options = $meta->options;

		$this->type = $meta->type;

		$this->nonce = str_replace( '_meta', '', $this->name ) . '_nonce';

		$this->init();
	}

	/**
	 * Set all actions hooks.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'init', array( $this, '___register_term_meta_text' ) );
		add_action( "{$this->taxonomy}_add_form_fields", array( $this, '___add_form_field_term_meta_text' ) );
		add_action( "{$this->taxonomy}_edit_form_fields", array( $this, '___edit_form_field_term_meta_text' ) );
		add_action( "edit_{$this->taxonomy}", array( $this, '___save_term_meta_text' ) );
		add_action( "create_{$this->taxonomy}", array( $this, '___save_term_meta_text' ) );

		add_filter( "manage_edit-{$this->taxonomy}_columns", array( $this, '___edit_term_columns' ), 10, 3 );
		add_filter( "manage_{$this->taxonomy}_custom_column", array( $this, '___manage_term_custom_column' ), 10, 3 );
	}

	/**
	 * REGISTER TERM META
	 *
	 * @return void
	 */
	public function ___register_term_meta_text() {
		register_meta( 'term', $this->name, '___sanitize_term_meta_text' );
	}

	/**
	 * SANITIZE DATA
	 *
	 * @param  string $value value of the meta input(s).
	 * @return string Sanitized value of the term meta.
	 */
	public function ___sanitize_term_meta_text( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * GETTER (will be sanitized)
	 *
	 * @param  int $term_id Term id of the meta.
	 * @return string Sanitized value of the term meta.
	 */
	public function ___get_term_meta_text( $term_id ) {
		$value = get_term_meta( $term_id, $this->name, true );
		$value = $this->___sanitize_term_meta_text( $value );
		return $value;
	}

	/**
	 * ADD FIELD TO CATEGORY TERM PAGE
	 *
	 * @return void
	 */
	public function ___add_form_field_term_meta_text() {
		?>
		<?php wp_nonce_field( basename( __FILE__ ), '<?php echo $this->name;?>_nonce' ); ?>
		<div class="form-field <?php echo $this->name; ?>-wrap">
			<label for="<?php echo esc_attr( $this->name ); ?>"><?php echo wp_kses_post( $this->description ); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->name ); ?>" id="<?php echo esc_attr( $this->name ); ?>" value="" class="<?php echo esc_attr( $this->name ); ?>-field" />
		</div>
		<?php
	}


	/**
	 * ADD FIELD TO CATEGORY EDIT PAGE
	 *
	 * @param  WP_Term $term Term Object to display meta value.
	 * @return void
	 */
	public function ___edit_form_field_term_meta_text( $term ) {

		$value = $this->___get_term_meta_text( $term->term_id );

		if ( ! $value ) {
			$value = '';
		}
		?>

		<tr class="form-field term-meta-text-wrap">
			<th scope="row"><label for="term-meta-text"><?php $this->description; ?></label></th>
			<td>
				<?php wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' ); ?>
				<input type="text" name="<?php echo esc_attr( $this->name ); ?>" id="<?php echo esc_attr( $this->name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $this->name ); ?>-field"  />
			</td>
		</tr>
		<?php
	}


	/**
	 * SAVE TERM META (on term edit & create)
	 *
	 * @param  int $term_id Term id to get meta.
	 * @return void
	 */
	public function ___save_term_meta_text( $term_id ) {

		if ( ! isset( $_POST['term_meta_text_nonce'] ) || ! wp_verify_nonce( $_POST['term_meta_text_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		$old_value = $this->___get_term_meta_text( $term_id );
		$new_value = isset( $_POST[ $this->name ] ) ? $this->___sanitize_term_meta_text( $_POST[ $this->name ] ) : '';

		if ( $old_value && '' === $new_value ) {
			delete_term_meta( $term_id, $this->name );

		} elseif ( $old_value !== $new_value ) {
			update_term_meta( $term_id, $this->name, $new_value );
		}
	}

	/**
	 * MODIFY COLUMNS (add our meta to the list)
	 *
	 * @param  array $columns initial list of columns display on edit list table.
	 * @return array Modified list of columns display on edit list table.
	 */
	public function ___edit_term_columns( $columns ) {

		$columns[ $this->name ] = $this->description;

		return $columns;
	}

	/**
	 * RENDER COLUMNS (render the meta data on a column)
	 *
	 * @param  int    $out initial content of the column content.
	 * @param  string $column column name/slug.
	 * @param  int    $term_id Term id link to column cell.
	 * @return string output content of column cell.
	 */
	public function ___manage_term_custom_column( $out, $column, $term_id ) {

		if ( $this->name === $column ) {

			$value = $this->___get_term_meta_text( $term_id );

			if ( ! $value ) {
				$value = '';
			}

			$out = sprintf( '<span class="term-meta-text-block" style="" >%s</div>', esc_attr( $value ) );
		}

		return $out;
	}
}
