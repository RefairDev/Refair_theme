<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package refair
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Page not found', 'refair-theme' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p class="prompt"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'refair-theme' ); ?></p>
					<div class="two-columns left-50">
						<div class="column b-right-green-400 b-top-green-400">
							<?php
								the_widget( 'WP_Widget_Recent_Posts' );
								get_search_form();
							?>
						</div>
						<div class="column b-top-green-400">
						<div class="widget">
							<h2 class="widget-title"><?php esc_html_e( 'Desposits and materials', 'refair-theme' ); ?></h2>
							<p><?php wp_kses_post( __( 'All deposits', 'refair-theme' ) ); ?> <a href="<?php echo esc_url( get_the_permalink( get_theme_mod( 'pagedegisement' ) ) ); ?>"><?php wp_kses_post( __( 'Here', 'refair-theme' ) ); ?></a></p>
							<h3> <?php wp_kses_post( __( 'Lastest Deposits', 'refair-theme' ) ); ?>:</h3>
								<ul>
									<?php
									$args = array(
										'post_type'      => array( 'deposit' ),
										'nopaging'       => false,
										'posts_per_page' => '5',
									);

									$query = new WP_Query( $args );

									if ( $query->have_posts() ) {
										while ( $query->have_posts() ) {
											$query->the_post();
											?>
											<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
											<?php
										}
									} else {
										?>
										<li><?php esc_html_e( 'No deposit found', 'refair-theme' ); ?></li>
										<?php
									}

									wp_reset_postdata();
									?>
								</ul>

							</div><!-- .widget -->
						</div>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
