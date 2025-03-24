<?php
/**
 *   Template part displaying providers in a list.
 *
 * @package refair
 */

?>
<div class="section-title-wrapper framed">
	<div id="fournisseurs" class="anchor"></div>
	<h2 class="section-title">Je recherche par fournisseurs :</h2>
</div>
<div class='section-body framed'>
	<div class="providers-list">
		<?php
		$args = array(
			'post_type' => array( 'provider' ),
			'nopaging'  => true,
			'orderby'   => 'meta_value_num',
			'meta_key'  => 'provider_order',
			'order'     => 'ASC',
		);

		$providers = new WP_Query( $args );

		if ( $providers->have_posts() ) {
			while ( $providers->have_posts() ) {
				$providers->the_post();
				?>
				<div class="provider-card">
					<div class="provider-details">
					<h2><?php the_title(); ?></h2>                    
					<?php
						$deposit_type_term = get_post_meta( get_the_ID(), 'deposit_type_term', true );

						$deposit_type_term_data = get_term( $deposit_type_term );

						$count_displayed = $deposit_type_term_data->count;
						$negation        = '';
						$plural          = '';
					if ( ( 0 === $deposit_type_term_data->count ) || is_wp_error( $deposit_type_term_data ) ) {
						$count_displayed = 'aucun';
						$negation        = 'ne ';
					}
					if ( 1 < $deposit_type_term_data->count ) {
						$plural = 's';
					}
					?>
					<p>Le fournisseur <?php echo wp_kses_post( $negation ); ?> compte actuellement <?php echo wp_kses_post( $count_displayed ); ?> site<?php echo wp_kses_post( $plural ); ?></p>
					</div>
					<div class="provider-actions">
						<a class="round-border-shape" href="<?php the_permalink(); ?>">Consulter le fournisseur</a>
					</div>
				</div>
				<?php
			}
		}

		wp_reset_postdata();
		?>
	</div>
	<?php $footer_content = get_post_meta( get_the_ID(), 'content_footer', true ); ?>
	<div class="providers-footer">
		<?php
		echo wp_kses_post( $footer_content );
		?>
	</div>
</div>	
