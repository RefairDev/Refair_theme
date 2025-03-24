<?php
/**
 *  Template part of the home page displaying le news section.
 *
 * @package refair
 */

?>
<div class="section-wrapper news-actors">
	<div class="section-header-news">
		<div class="section-inner">
			<h2><?php echo wp_kses_post( $section['title_news'] ); ?>
				<div class="actions">
					<a href="<?php echo esc_url( $section['news_link']['link'] ); ?>" >
						<div class="action-label"><?php echo wp_kses_post( $section['news_link']['text'] ); ?></div>
						<div class="action-btn"><div><i class="icono-arrow-left"></i></div></div>
					</a>
				</div>
			</h2>
		</div>
	</div>
	<div class="section-body-news">
		<div class="swiper">	
			<div class="swiper-wrapper">
				<?php
				$bullets = array();

				$args = array(
					'post_status'    => array( 'publish' ),
					'nopaging'       => false,
					'posts_per_page' => '3',
					'paged'          => '1',
				);


				$query = new WP_Query( $args );
				set_query_var( 'news_link', $section['news_link'] );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part( 'template-parts/home-sections/partials/news' );
					}
				}

				wp_reset_postdata();
				?>
			</div>
			<div class="news-controls">
				<div class="news-bullets"></div>
			</div>				
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/swiper@11.0.5/swiper-bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/swiper@11.0.5/swiper-bundle.min.css" rel="stylesheet">
	<script>
		window.addEventListener('load', function(){
			const swiper = new Swiper(".swiper", {
				// other parameters
				setWrapperSize:true,
				centeredSlides: true,
				loop: true,
				autoplay: {
					delay: 5000,
				},
				// If we need pagination
				pagination: {
					horizontalClass: "news-horizontal-pagination",
					el: '.news-bullets',
					clickable: true
				},
			});
		});
	</script>
</div>
