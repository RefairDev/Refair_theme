<?php
/**
 *
 * Template Name: Search Page
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package refair
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search result for: %s', 'refair-theme' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<div class="page-body results-list framed">
			<?php
			$deposit_outputs = '';
			$product_outputs = '';
			$page_outputs    = '';
			$post_outputs    = '';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				ob_start();
				get_template_part( 'template-parts/content', 'search' );
				$post_output = ob_get_clean();

				switch ( $post->post_type ) {
					case 'deposit':
						$deposit_outputs .= $post_output;
						break;
					case 'product':
						ob_start();
						get_template_part( 'template-parts/content-search', 'materials' );
						$product_outputs .= ob_get_clean();
						break;
					case 'page':
						$page_outputs .= $post_output;
						break;
					case 'post':
						$post_outputs .= $post_output;
						break;
					default:
						$others_outputs .= $post_output;
				}

			endwhile;

			if ( ! empty( $deposit_outputs ) ) {
				?>
				<section class='search-result-section'>
				<h2>Les sites d'inventaire</h2>
				<div class='search-result-items-list'>
				<?php echo wp_kses_post( $deposit_outputs ); ?>
				</div>
				</section>
				<?php
			}
			if ( ! empty( $product_outputs ) ) {
				?>
				<section class='search-result-section'>
				<h2>Les matériaux</h2>
				<div class='search-result-items-list'>
				<?php echo wp_kses_post( $product_outputs ); ?>
				</div>
				</section>
				<?php
			}
			if ( ! empty( $page_outputs ) ) {
				?>
				<section class='search-result-section'>
				<h2>Les pages</h2>
				<div class='search-result-items-list'>
				<?php echo wp_kses_post( $page_outputs ); ?>
				</div>
				</section>
				<?php
			}
			if ( ! empty( $post_outputs ) ) {
				?>
				<section class='search-result-section'>
				<h2>Les actualités</h2>
				<div class='search-result-items-list'>
				<?php echo wp_kses_post( $post_outputs ); ?>
				</div>
				</section>
				<?php
			}
			?>
			<div class="bottom-padding"></div>
			</div>
			<?php

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
		

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
