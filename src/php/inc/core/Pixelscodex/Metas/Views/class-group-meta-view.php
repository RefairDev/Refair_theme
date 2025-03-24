<?php
/**
 * File containing Extensible_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;
use Pixelscodex\Agent_Utils;

/**
 * Class managing display of group type metabox.
 */
class Group_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'group';

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

		$groupview = $view_content;

		$script_function_name = Agent_Utils::dash_to_camel_case( $data['meta_name'] );
		$full_meta_name       = Agent_Utils::dash_to_camel_case( $data['meta_name'] );
		if ( array_key_exists( 'full_meta_name', $data ) ) {
			$script_function_name = $data['full_meta_name'];
			$full_meta_name       = $data['full_meta_name'];
		}

		foreach ( $data['options']['metas'] as $meta ) {

			$meta_value = '';

			if ( isset( $value[ $meta->name ] ) ) {
				$meta_value = $value[ $meta->name ];
			}

			$meta_data = array(
				'id'             => $data['id'] . '-' . $meta->name,
				'name'           => $data['name'] . '[' . $meta->name . ']',
				'full_meta_name' => Agent_Utils::dash_to_camel_case( $full_meta_name . '-' . $meta->name ),
				'meta_name'      => $meta->name,
				'type'           => $meta->type,
				'title'          => $meta->title,
				'options'        => $meta->options,
			);

			$meta_view_content         = apply_filters(
				'theme_meta_renderview_' . $meta->type,
				$view_content,
				$meta_data,
				$meta_value
			);
			$wrapped_meta_view_content = apply_filters( 'theme_wrapmeta_with_title', $meta_view_content, $meta->title, $meta_data );
			$groupview                 = $groupview . $wrapped_meta_view_content;
		}

		ob_start();
		?>
		<script>
			function group<?php echo esc_attr( $script_function_name ); ?>Script(){
				<?php
				foreach ( $data['options']['metas'] as $meta ) {
					$group_item_script_name = $meta->type . Agent_Utils::dash_to_camel_case( $script_function_name . '-' . $meta->name );
					?>
					if (typeof <?php echo esc_attr( $group_item_script_name ); ?>Script == 'function'){<?php echo esc_attr( $group_item_script_name ); ?>Script()};
					<?php
				}
				?>
			}
		</script>
		<?php
		$group_script = ob_get_clean();
		$groupview    = $groupview . $group_script;

		return $groupview;
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
