<?php
/**
 * File containing Locmap_Meta_View Class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex\Metas\Views;

use PixelscodexCore\Meta_View;

/**
 * Class managing display of location (map) type metabox.
 */
class Locmap_Meta_View extends Meta_View {

		/**
		 * Type of the meta box.
		 *
		 * @var string
		 */
	public static $type = 'locmap';

	/**
	 * Unique identifier for the map.
	 *
	 * @var string
	 */
	protected $module_uuid = '';

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
	 * Javascript to generate metabox map.
	 *
	 * @param  string $apikey google api to used google services.
	 * @param  array  $data Data used to custimze script tags arrtibutes and values.
	 * @return void
	 */
	protected function script( $apikey, $data ) {
		?>
		<style>
			<?php echo esc_attr( '#' . $data['name'] ); ?>-<?php echo esc_attr( $this->module_uuid ); ?>-map{
				height: 350px;
				width: 400px;
			}
			p>label + input{
			margin-left:7px;
			}
			.ui-dialog.location-result{
				z-index:900;
			}
		</style>
		<script type="text/javascript">

			var moduleLOCMAP_<?php echo esc_attr( $this->module_uuid ); ?> = (function($){

				var $lat = $('[name="<?php echo esc_attr( $data['name'] ); ?>[lat]"]');
				var $lng = $('[name="<?php echo esc_attr( $data['name'] ); ?>[lng]"]');
				var $location = $('[name="<?php echo esc_attr( $data['name'] ); ?>[location]"]');
				var locationResults=[];
				var resultsDialog={};
				var map={};
				var mapDefaultCenter = [44.8368, -0.5896];
				var mapDefaultZoom = 11;
				var locMarker={};

				this.selectLocationResult = jQuery.proxy(this.selectLocationResult,this);

				return {

					init: function(){
						jQuery('#search-locmap-<?php echo esc_attr( $this->module_uuid ); ?>').on('click', moduleLOCMAP_<?php echo esc_attr( $this->module_uuid ); ?>.manageSearchClick)
						
						resultsDialog = jQuery(".<?php echo esc_attr( $data['name'] ); ?>-location-results-dialog").dialog({
							autoOpen : false,
							title: "Choisissez votre résultat",
							dialogClass: "no-close location-result",
							buttons: [{
								text: "Valider",
								click: function() {
									moduleLOCMAP_<?php echo esc_attr( $this->module_uuid ); ?>.selectLocationResult();
									$( this ).dialog( "close" );
									}
								}],
						});

						map = L.map("<?php echo esc_attr( $data['id'] ); ?>-<?php echo esc_attr( $this->module_uuid ); ?>-map", {center:mapDefaultCenter, zoom:mapDefaultZoom  });
						L.tileLayer('https://a.tile.openstreetmap.org/{z}/{x}/{y}.png', {
							attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
						}).addTo(map);

						if ($lat.val() != "" && $lng.val() !=""){
							this.locMarker = L.marker([$lat.val(),$lng.val()]).addTo(map);
						}
						map.on("click",this.manageClickEvent,this);

					},

					manageSearchClick: function(e){
						
						e.preventDefault();

						var value = $(this).prev().val();
						
						jQuery.ajax({
							url: "https://maps.googleapis.com/maps/api/geocode/json?address="+value+"&language=fr&region=fr&key=<?php echo esc_attr( $apikey ); ?>",
							success: function(resp) {
								if(! resultsDialog.dialog( "isOpen" )){
									switch(resp.status)
									{
										case "OK":
										{
											locationResults = resp.results;
		
											locationResults.forEach(function(valeurCourante,index ,resultArray){
												jQuery(".<?php echo esc_attr( $data['name'] ); ?>-result-list").append('<li class="location-result"><input type="radio" name="<?php echo esc_attr( $data['name'] ); ?>-location-choice" value="'+ index +'">'+ valeurCourante.formatted_address +'</li>');
											});
											resultsDialog.dialog( "open" );
											break;
										}
										case"ZERO_RESULTS":
										{
											alert("No result returned, correct and renew your request"); 
											break;
										}
										default:
										{
											alert('Error: Google geocode returned "'+ resp.status +'"' );
											
										}
									}
								}
							}
						});
					},

					selectLocationResult: function(){
						var resultValue = jQuery("[name='<?php echo esc_attr( $data['name'] ); ?>-location-choice']").val();

						this.placeMarker(locationResults[resultValue].geometry.location.lat,locationResults[resultValue].geometry.location.lng);

						if(jQuery("[name='keep-label-<?php echo esc_attr( $this->module_uuid ); ?>']").prop("checked")!==true){
							$location.val(locationResults[resultValue].formatted_address);
						}
						
						jQuery(".<?php echo esc_attr( $data['name'] ); ?>-result-list").empty();
						resultsDialog.dialog("close");
					},

					manageClickEvent: function(e){
						this.placeMarker(e.latlng.lat,e.latlng.lng);
					},
					placeMarker: function(lat,lng){

						if (this.locMarker instanceof L.Marker){
							this.locMarker.removeFrom(map);
						}
						this.locMarker = L.marker([lat,lng]).addTo(map);
						$lat.val(lat);
						$lng.val(lng);
					}
					
					

				} 			
					

			})(jQuery);


			moduleLOCMAP_<?php echo esc_attr( $this->module_uuid ); ?>.init();

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
		ob_start();
		?>
		<p>
			<label for="<?php echo esc_attr( $data['name'] ); ?>[location]">Adresse</label><input type="text" name="<?php echo esc_attr( $data['name'] ); ?>[location]" id="<?php echo esc_attr( $data['name'] ); ?>[location]" class="meta-video regular-text" value="<?php echo wp_kses_post( $value['location'] ); ?>"/>
			<button id="search-<?php echo esc_attr( $this->module_uuid ); ?>">Search</button>
		</p>
		<p>
			<label for="<?php echo esc_attr( $data['name'] ); ?>[lat]">Latitude</label><input type="text" name="<?php echo esc_attr( $data['name'] ); ?>[lat]" id="<?php echo esc_attr( $data['name'] ); ?>[lat]" class="meta-video regular-text" value="<?php echo esc_attr( $value['lat'] ); ?>"/>
			<label for="<?php echo esc_attr( $data['name'] ); ?>[lng]">Longitude</label><input type="text" name="<?php echo esc_attr( $data['name'] ); ?>[lng]" id="<?php echo esc_attr( $data['name'] ); ?>[lng]" class="meta-video regular-text" value="<?php echo esc_attr( $value['lng'] ); ?>"/>
		</p>
		<div class="clearfix">
			<div id="<?php echo esc_attr( $data['name'] ); ?>-<?php echo esc_attr( $this->module_uuid ); ?>-map">
			
			</div>
		</div>
		<div class="<?php echo esc_attr( $data['name'] ); ?>-location-results-dialog">
		<ul class="<?php echo esc_attr( $data['name'] ); ?>-result-list">   		
		</ul>
		<input id="keep-label-<?php echo esc_attr( $this->module_uuid ); ?>" type="checkbox" name="keep-label-<?php echo esc_attr( $this->module_uuid ); ?>" value="keep_label"><label for="keep-label-<?php echo esc_attr( $this->module_uuid ); ?>">Garder la description</label>
		</div>
		<?php

		$this->script( get_option( 'apikey' ), $data );
		$locmap_meta_view = ob_get_clean();

		return $view_content . $locmap_meta_view;
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
