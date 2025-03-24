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
 * Class managing display of various extensible type metabox.
 */
class Extensible_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'extensible';

	/**
	 * Constructor of the meta class.
	 *
	 * @param  array $options Options used to create the meta.
	 */
	public function __construct(
		$options = array()
	) {
		parent::__construct(
			$options
		);
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

		$extensible_view = $view_content;
		if ( isset( $data['options']['default'] ) ) {
			$child_defaults_values = $data['options']['default'];}

		if ( is_array( $value ) ) {
			$value_count = count( $value );
		} elseif ( isset( $child_defaults_values ) ) {
			$value_count = count( $child_defaults_values );
		} else {
			$value_count = 1;
		}

		$child_meta = $data['options']['meta'];

		$child_meta_obj = new \ArrayObject( $child_meta );
		$child_meta_arr = $child_meta_obj->getArrayCopy();
		$meta_name      = $data['options']['meta']->name;

		ob_start();
		?>
		<div id="<?php echo esc_attr( $data['id'] . '_extensible' ); ?>" data-extensible="<?php echo esc_attr( $data['meta_name'] ); ?>">
			<?php
			for ( $idx = 0; $idx < $value_count; $idx++ ) {

				$meta_value = null;
				if ( isset( $value[ $idx ] ) ) {
					$meta_value = $value[ $idx ];
				} elseif ( isset( $child_defaults_values ) && is_array( $child_defaults_values ) && isset( $child_defaults_values[ $idx ] ) ) {
					$meta_value = $child_defaults_values[ $idx ];
				}
				$child_meta_arr['meta_name']      = $meta_name;
				$child_meta_arr['full_meta_name'] = Agent_Utils::dash_to_camel_case( $data['id'] . '-' . $meta_name );
				$child_meta_arr['name']           = $data['name'] . '[' . $idx . ']';
				$child_meta_arr['id']             = $data['id'] . '-' . $idx;

				$script_function_name = Agent_Utils::dash_to_camel_case( $data['meta_name'] );
				if ( array_key_exists( 'full_meta_name', $data ) ) {
					$script_function_name = $data['full_meta_name'];
				}
				?>
				<div id="<?php echo esc_attr( $data['id'] . '-' . $idx ); ?>-block" class="extensible-meta-item collapsed">
					<div class="extensible-meta-item-handle">
						<div class="burger-icon">
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
					<div class="extensible-meta-item-handle-padding"></div>
					<div class="extensible-meta-item-number">
						<label for="<?php echo esc_attr( $data['id'] . '-' . $idx ); ?>"><?php echo wp_kses_post( $idx + 1 ); ?></label>
					</div>
					<div class="extensible-meta-item-number-padding"></div>
					<div class="extensible-meta-item-menu-bar">
						<span class="reduced-value"><?php echo wp_kses( apply_filters( 'theme_render_reduced_view_' . $child_meta_arr['type'], '', $child_meta_arr, $meta_value ), wp_kses_allowed_html( 'strip' ) ); ?></span>
						<div class="extensible-meta-item-actions">
							<button type="button" class="collapse-indicator" onclick="toggleHeightItem(this)"><span></span></button>
							<button type="button" class="remove-indicator" onclick="removeItem(this)" ><span>&times;</span></button>
						</div>
					</div>
					<div class="extensible-meta-item-content meta-content collapsed">
						<?php echo wp_kses( apply_filters( 'theme_meta_renderview_' . $child_meta_arr['type'], $view_content, $child_meta_arr, $meta_value ), wp_kses_allowed_html( 'strip' ) ); ?>
					</div>
				</div>
				<?php
			}
			?>
			
		</div>
		<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
		<script>

			var el = document.getElementById('<?php echo esc_attr( $data['id'] . '_extensible' ); ?>');
			var <?php echo esc_attr( $data['meta_name'] ); ?>sortable = Sortable.create(el,{
				animation: 150,
				handle: '.extensible-meta-item-handle',
				forceFallback: true, // This is it!
				onChoose: function(e) {
					e.target.classList.add('grabbing');
				},
				onUnchoose: function(e) {
					e.target.classList.remove('grabbing');
					renumberItems(e.target.closest("#<?php echo esc_attr( $data['id'] . '_extensible' ); ?>"));
				},
				onStart: function(e) {
					e.target.classList.add('grabbing');
				},
				onEnd: function(e) {
					e.target.classList.remove('grabbing');
					renumberItems(e.target.closest("#<?php echo esc_attr( $data['id'] . '_extensible' ); ?>"));
				},
				onMove: function(e) {
					e.target.classList.add('grabbing');
				},                
			});

			function extensible<?php echo esc_attr( $script_function_name ); ?>Script(){
				<?php $meta = $data['options']['meta']; ?>
					if (typeof <?php echo esc_attr( $meta->type . Agent_Utils::dash_to_camel_case( $data['id'] . '-' . $meta->name ) ); ?>Script == 'function'){<?php echo esc_attr( $meta->type . Agent_Utils::dash_to_camel_case( $data['id'] . '-' . $meta_name ) ); ?>Script()};
			}
	
			function replaceIdx(currentNode, currentIndex, nodesList){
				let rootIdx = this.hasOwnProperty('rootIdx')? this.rootIdx : currentIndex;
				let replacementTarget = this.hasOwnProperty('replacementTarget')? this.replacementTarget : '';				
				const regexArrArr = new RegExp(`\\[${replacementTarget}\\]\\[\\d+\\]`,'i');
				const regexArr = new RegExp(`${replacementTarget}\\[\\d+\\]`,'i');
				const regexDash = new RegExp(`${replacementTarget}-\\d+`,'i');
				const regex = /^\d+$/i;
				attrs = ["htmlFor","id","name","data-target"];
				attrs.forEach((item) => {
					if(currentNode[item]){
						currentNode[item] = currentNode[item].replace(regexArrArr,"["+replacementTarget+"]["+rootIdx+"]");
						currentNode[item] = currentNode[item].replace(regexArr,replacementTarget+"["+rootIdx+"]");
						currentNode[item] = currentNode[item].replace(regexDash,replacementTarget+"-"+rootIdx);
						if (item=="htmlFor"){ currentNode.innerText = currentNode.innerText.replace(regex,(parseInt(rootIdx)+1));}
					}
					if (item.startsWith('data-') && currentNode.attributes && currentNode.attributes[item]){
						let newAttributeValue = currentNode.attributes[item].value;
						newAttributeValue = currentNode.attributes[item].value.replace(regexArrArr,"["+replacementTarget+"]["+rootIdx+"]");
						newAttributeValue = currentNode.attributes[item].value.replace(regexArr,replacementTarget+"["+rootIdx+"]");
						newAttributeValue = currentNode.attributes[item].value.replace(regexDash,replacementTarget+"-"+rootIdx);
						currentNode.setAttribute( item, newAttributeValue );


					}
				})
				if (currentNode.attributes && currentNode.attributes['data-meta-type'] && (typeof currentNode.attributes['data-meta-type']+'Script' == 'function') ){
					call_user_func(currentNode.attributes['data-meta-type']+'Script',currentNode);
				}

				if (currentNode.childNodes.length >0){
					currentNode.childNodes.forEach(replaceIdx, {'rootIdx': rootIdx, 'replacementTarget':replacementTarget });
				}
			}   
			
			function addItem( inputBlock ) {

				let newKey = inputBlock.firstElementChild.cloneNode( true );
				inputBlock.appendChild( newKey );
				renumberItems(inputBlock);
			}

			function removeItem( rmBtnNode) {

				let itemRootNode = rmBtnNode.closest(".extensible-meta-item");

				itemListNode = itemRootNode.parentNode;
				itemRootNode.remove();

				renumberItems(itemListNode);
			}

			function toggleHeightItem( itemToReduce ) {

				let item = itemToReduce.parentNode.parentNode.parentNode;
				let itemContent = item.querySelector(".extensible-meta-item-content");

				itemContent.classList.toggle("collapsed");
				item.classList.toggle("collapsed");
				//setTimeout(function(){ item.classList.toggle("hidden")},500);

			}

			function renumberItems(itemsList){
				let children = itemsList.querySelectorAll(':scope > .extensible-meta-item');
				let childrenPartToReplace = itemsList.attributes['data-extensible'].value;
				children.forEach( replaceIdx,  {'replacementTarget':childrenPartToReplace} );
				extensible<?php echo esc_attr( $script_function_name ); ?>Script();
			}            
		</script>    
		<?php
		$meta_extensible_view = ob_get_clean();
		$extensible_view      = $extensible_view . $meta_extensible_view;
		return $extensible_view;
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
		$target = $data['id'] . '_extensible';
		return "<button type='button' onClick='addItem(document.querySelector(this.attributes[\"data-target\"].value))' data-target='#$target'>Ajouter</button>";
	}
}
