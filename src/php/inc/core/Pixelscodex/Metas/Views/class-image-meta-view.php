<?php
/**
 * File containing Image_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;
use Pixelscodex\Agent_Utils;

/**
 * Class managing display of image type metabox.
 */
class Image_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'image';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct( $options = array() ) {
		parent::__construct( $options );
	}

	/**
	 * Redirect image url to current site domain add scheme (http/https) to prevent 404 error on site migration.
	 *
	 * @param  string $src Original image url.
	 * @return string corrected image url.
	 */
	public static function url_img( $src ) {
		$url      = wp_parse_url( $src );
		$site_url = wp_parse_url( get_site_url() );

		return trim( $site_url['scheme'] . '://' . $site_url['host'] . $url['path'] );
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

		$plugin_name = 'refair';

		if ( ! array_key_exists( 'name', $data ) || ! array_key_exists( 'id', $data ) ) {
			return $view_content;
		}
		/* TODO: error management "no name / id in data" */
		$meta_short_name = $data['meta_name'];
		$meta_full_name  = $data['name'];
		$meta_id         = $data['id'];
		$meta_value      = array(
			'url'     => '#',
			'caption' => '',
			'id'      => 0,
		);

		if ( isset( $value ) && is_array( $value ) && ! empty( $value ) ) {
			$meta_value = array_merge( $meta_value, $value );
		}

		$data_name_url     = sprintf( '%s[url]', $data['name'] );
		$data_name_id      = sprintf( '%s[id]', $data['name'] );
		$data_name_caption = sprintf( '%s[caption]', $data['name'] );
		$data_id_url       = sprintf( '%s-url', $data['id'] );
		$data_id_id        = sprintf( '%s-id', $data['id'] );
		$data_id_caption   = sprintf( '%s-caption', $data['id'] );

		$meta_value_url     = array_key_exists( 'url', $meta_value ) ? $meta_value['url'] : '#';
		$meta_value_id      = array_key_exists( 'id', $meta_value ) ? $meta_value['id'] : 0;
		$meta_value_caption = array_key_exists( 'caption', $meta_value ) ? $meta_value['caption'] : '';

		$img_src          = get_template_directory_uri() . '/images/250x150.jpg';
		$remove_btn_class = ' hidden';
		if ( isset( $meta_value_url ) && '#' !== $meta_value_url ) {
			$img_src          = $this->url_img( $meta_value_url );
			$remove_btn_class = '';
		}

		ob_start();?>
		
		<input type="hidden" name="<?php echo esc_attr( $data_name_url ); ?>" id="<?php echo esc_attr( $data_id_url ); ?>" class="media-url" value="<?php echo esc_url( $meta_value_url ); ?>"/>
		<input type="hidden" name="<?php echo esc_attr( $data_name_id ); ?>" id="<?php echo esc_attr( $data_id_id ); ?>" class="media-id" value="<?php echo esc_attr( $meta_value_id ); ?>"/>
		<div class="meta-input-controls">
			<label for="<?php echo esc_attr( $meta_id ); ?>-browse-btn">Fichier</label>
			<div>
				<input type="button" id="<?php echo esc_attr( $meta_id ); ?>-browse-btn" class="button browse-image" value="Browse" onClick="handleOnClickBrowseMetaImage(this.id)"/>
				<input type="button" id="<?php echo esc_attr( $meta_id ); ?>-remove-btn" class="button remove-media<?php echo esc_attr( $remove_btn_class ); ?>" value="<?php esc_html_e( 'Remove', 'deuxdegres' ); ?>" onClick="handleOnClickRemoveMetaImage(this.id)"/>
				<span class="media-url-display"><?php echo esc_url( $meta_value_url ); ?></span>
			</div>
			<label for="<?php echo esc_attr( $meta_id . '-caption' ); ?>">Légende</label>
			<div>
				<input type="text" name="<?php echo esc_attr( $data_name_caption ); ?>" id="<?php echo esc_attr( $data_id_caption ); ?>" class="regular-text" value="<?php echo wp_kses_post( $meta_value_caption ); ?>"/>
			</div>
			<label for="<?php echo esc_attr( $meta_id . '-preview' ); ?>">Prévisualisation</label>
			<div class="image-preview">
				
				<img style="max-width: 250px; box-shadow: 1px 2px 4px 0 #324664" src="<?php echo esc_attr( $img_src ); ?>">
			</div>            
		</div>
		<script  type="text/javascript">
			<?php
			$script_function_name = Agent_Utils::dash_to_camel_case( $data['meta_name'] );
			if ( array_key_exists( 'full_meta_name', $data ) ) {
				$script_function_name = $data['full_meta_name'];
			}
			?>
			function image<?php echo esc_attr( $script_function_name ); ?>Script(){
				//Remove Browse handled directly with onClick Attr Btn.
			}
		</script>

		<?php
		$image_meta_view = ob_get_clean();

		return $view_content . $image_meta_view;
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
		$returned = "Pas d'image";
		if ( is_array( $value ) && array_key_exists( 'url', $value ) && array_key_exists( 'id', $value ) ) {
			$returned = '<img class="reduced-value-thumbnail" src="' . wp_get_attachment_image_src( $value['id'] )[0] . '" ><span>' . $value['url'] . '</span>';
		}
		return $returned;
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

