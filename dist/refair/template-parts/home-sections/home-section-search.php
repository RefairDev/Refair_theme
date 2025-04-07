<?php
/**
 * Template part of the home page to display search materials.
 *
 * @package refair
 */

?>
<section class="section-search">
	<div class="section-body">
		<div class="search-vectors">
			<?php
			foreach ( $section['search_types'] as $vector ) {
				?>
			<div class="vector">
				<a href="<?php echo $vector['page_link']; ?>">
					<div class="vector-preview">
						<div class="big-letter b-green">
							<div class="sub-mask left"></div>	
							<div class="mask-wrapper">
							<?php
							get_mask_letter( $vector['over_letter'], 'var(--refair_green_600)', true );
							// echo wp_kses(
							// get_responsive_image( $vector['feature']['id'], array( 480, 320 ), array( 'alt' => $vector['feature']['caption'] ) ),
							// array_merge(
							// wp_kses_allowed_html( 'post' ),
							// array(
							// 'svg'   => array(),
							// 'style' => array(),
							// )
							// )
							// );
							echo (
								get_responsive_image( $vector['feature']['id'], array( 480, 320 ), array( 'alt' => $vector['feature']['caption'] ) )
							);
							?>
							</div>
							<div class="sub-mask right"></div>
						</div>
					</div>
					<div class="vector-title"><h3><?php echo wp_kses_post( $vector['feature']['caption'] ); ?></h3></div>
				</a>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
