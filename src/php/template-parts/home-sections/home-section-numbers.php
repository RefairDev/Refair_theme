<?php
/**
 * Template part of the home page to display key figures.
 *
 * @package refair
 */

$background_color = 'mrn_sl_blue_2';
$carousel_id      = 'numbers_slider';
?>
<section class="section-key-numbers">
	<div class="container">
		<div class="row">
			<div class="col">
				<div id="<?php echo esc_attr( $carousel_id ); ?>">
					<?php
					if ( is_array( $section['numbers_slider'] ) ) {
						$sliders_count = count( $section['numbers_slider'] );
						for ( $item_idx = 0; $item_idx < $sliders_count; $item_idx++ ) {
							$slide_data = $section['numbers_slider'][ $item_idx ];
							?>
							<div >
								<div class="mx-auto">
										<img src="<?php echo esc_url( $slide_data['url'] ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
									</div>
							</div>
							<?php
						}
					} else {
						?>
							<img class="d-block" src="http://via.placeholder.com/1920x800?text=''" alt="void slide">
						<?php
					}
					?>
									</div>
			</div>
		</div>
	</div>
</section>    
