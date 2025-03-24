<?php
/**
 *
 * Template part of the home page to display project section.
 *
 * @package refair
 */

$background_color   = 'green_2';
$background_color_2 = 'green_1';
$background_color_3 = 'green_3';
$carousel_id        = 'project_goals';
$key_numbers_id     = 'numbers_slider';
$step               = 2;
?>
<div class="container-fluid no-padding shaped <?php echo esc_attr( $background_color ); ?>">
	<div class="shape-top overflow <?php echo esc_attr( $background_color ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1917.4 27.98"><defs><style>.a{fill:#74b54f;}</style></defs><path class="a" d="M0,28V5.74a19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0A19,19,0,0,1,350,5.7a19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,18.82,18.82,0,0,0,26.75.17,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0V28Z"/></svg>
	</div>
	<div class="container">
		<div class='row justify-content-center'>
			<div class='col-lg-10'>
				<p class="catchphrase"><?php echo wp_kses_post( $section['catchphrase'] ); ?></p>
			</div>
		</div>
	</div>
	<div class="shape-bottom overflow <?php echo esc_attr( $background_color ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 27.98"><defs><style>.a{fill:#74b54f;}</style></defs><path class="a" d="M0,0V22.24a19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,18.82,18.82,0,0,1,26.75-.17,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0V0Z"/></svg>
	</div>
</div>
<div class="container-fluid no-padding shaped <?php echo esc_attr( $background_color_3 ); ?>">
	<div class="container key-numbers-slider">
		<div class="col">
			<div id="<?php echo esc_attr( $key_numbers_id ); ?>">			      			    			
				<?php
				if ( is_array( $section_plus['numbers_slider'] ) ) {
					$slider_count = count( $section_plus['numbers_slider'] );
					for ( $item_idx = 0; $item_idx < $slider_count; $item_idx++ ) {
						$slide_data = $section_plus['numbers_slider'][ $item_idx ];
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
	<div class="shape-bottom overflow <?php echo esc_attr( $background_color_3 ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 27.98"><defs><style>.a{fill:#74b54f;}</style></defs><path class="a" d="M0,0V22.24a19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,18.82,18.82,0,0,1,26.75-.17,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0V0Z"/></svg>
	</div>    
</div>
<div class="container-fluid no-padding shaped <?php echo esc_attr( $background_color_2 ); ?>">
	<div class="container project-slider">
		<?php
		if ( is_array( $section['project_slider'] ) ) {
			?>
		<div class="slider-mask"></div>
		<div id="<?php echo esc_attr( $carousel_id ); ?>">
			<?php
			foreach ( $section['project_slider'] as $idx => $slide ) {
				?>
				<div>
					<div class="slide-inner">
						<img class="slide-img" src="<?php echo esc_url( $slide['url'] ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
							<?php
							if ( '' !== $slide['caption'] ) {
								?>
							<p class="slide-lgd" ><?php echo wp_kses_post( $slide['caption'] ); ?></p>
								<?php
							}
							?>
					</div>
				</div>
				<?php
			}
		}
		?>
		</div>
	</div>
	<div class="shape-bottom overflow <?php echo esc_attr( $background_color_2 ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 27.98"><defs><style>.a{fill:#74b54f;}</style></defs><path class="a" d="M0,0V22.24a19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,18.82,18.82,0,0,1,26.75-.17,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19.05,19.05,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19.05,19.05,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.93,0,19,19,0,0,0,26.92,0,19,19,0,0,1,26.92,0,19,19,0,0,0,26.93,0,19,19,0,0,1,26.92,0V0Z"/></svg>
	</div>
</div>
