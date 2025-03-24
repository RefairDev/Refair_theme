<?php
/**
 * File containing Location_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;

/**
 * Class managing display of location (lat/lng) type metabox.
 */
class Location_Meta_View extends Meta_View {

	/**
	 * Type of the meta box.
	 *
	 * @var string
	 */
	public static $type = 'location';

		/**
		 * Constructor of the meta class.
		 *
		 * @param  array $options Options used to create the meta.
		 */
	public function __construct(
		$options = array()
	) {
		parent::__construct(
			$options = array()
		);
	}

    /**
     * Display script used to dynamiquely handle the input.
     *
     * @return void
     */
	protected function script() {
		?>
			<script type="text/javascript">
	
				var moduleLOC = (function($){
	
					var API_KEY = "[Google API Key]";
	
					var $lat = $('[name="<?php echo $data['name']; ?>[lat]"]');
					var $lng = $('[name="<?php echo $data['name']; ?>[lng]"]');
					var $location = $('[name="<?php echo $data['name']; ?>[location]"]');
	
					return function(e) {    
						e.preventDefault();    
						var value = $(this).prev().val();    
						jQuery.ajax({
							url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + encodeURI(value) + '&key=' + API_KEY,
							success: function(resp) {
								var result = resp.results[0];
								var address = result.formatted_address;
	
								var location = result.geometry.location;
	
								$lat.val(location.lat);
								$lng.val(location.lng);
	
								$location.val(address);
							}
						});
	
					}
	
				})(jQuery);
	
				jQuery('#search').on('click', moduleLOC)                
	
				/*jQuery.ajax({
					url: 'https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=' + API_KEY,
					success: function(resp) {
						console.log(resp)
					}
				})*/
			</script>
		<?php
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
		?>
		<p>
			<input type="text" name="<?php echo $data['name']; ?>[location]" id="<?php echo $data['id']; ?>[location]" class="meta-video regular-text" value="<?php echo $meta['location']; ?>"/>
			<button id="search">Search</button>

		</p>
		<p>
			<input type="text" name="<?php echo $data['name']; ?>[lat]" id="<?php echo $data['id']; ?>[lat]" class="meta-video regular-text" value="<?php echo $meta['lat']; ?>"/>

			<input type="text" name="<?php echo $data['name']; ?>[lng]" id="<?php echo $data['id']; ?>[lng]" class="meta-video regular-text" value="<?php echo $meta['lng']; ?>"/>
		</p>
		<?php
		$this->script();
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
