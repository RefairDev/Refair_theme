<?php
/**
 * File containing Array_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;
/**
 * Class managing display of array type metabox.
 */
class Array_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'array';

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

		if ( ! array_key_exists( 'cols', $data['options'] ) || ! array_key_exists( 'rows', $data['options'] ) ) {
			return $view_content;
		}

		$cols = $data['options']['cols'];
		$rows = $data['options']['rows'];

		if ( ! is_array( $value ) ) {
			/* Full fill of the meta with dummy*/
			$dummy_col = array_fill( 0, $cols, '' );
			$value     = array_fill( 0, $rows, $dummy_col );
		} else {
			/* complete rows number*/
			if ( $rows >= count( $value ) ) {
				array_merge( $value, array_fill( 0, $rows - count( $value ), array_fill( 0, $cols - 1, '' ) ) );
			}
			/* complete col number*/
			for ( $meta_row_idx = 0; $meta_row_idx < $rows; $meta_row_idx++ ) {
				if ( $cols >= count( $value[ $meta_row_idx ] ) ) {
					array_merge( $value[ $meta_row_idx ], array_fill( 0, $cols - count( $value[ $meta_row_idx ] ), '' ) );
				}
			}
		}

		ob_start();
		?>
		<table>
			<thead>
				<tr>
					<?php
					for ( $i = 0; $i < $cols; $i++ ) :
						echo wp_kses_post( '<th>Col #' . ( $i + 1 ) . '</th>' );
						endfor;
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				for ( $i = 0; $i < $rows; $i++ ) :
					?>
							<tr>
							<?php
							for ( $j = 0; $j < $cols; $j++ ) :
								?>
										<td>
											<input 
											type="text" 
											name="<?php echo esc_attr( "{$data['name']}[{$i}][{$j}]" ); ?>" 
											id="<?php echo esc_attr( "{$data['id']}[{$i}][{$j}]" ); ?>" 
											class="meta-video regular-text" 
											value="<?php echo esc_attr( $value[ $i ][ $j ] ); ?>"/>
										</td>
									<?php
								endfor;
							?>
							</tr>
						<?php
					endfor;
				?>
			</tbody>
		</table>

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
