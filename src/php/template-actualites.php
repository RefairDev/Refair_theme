<?php
/**
 * Template Name: Page Actualités
 * WordPress template for news page.
 *
 * @package refair
 */

get_header();
$all_metas = get_post_meta( get_the_id() );
?>

<header class="page-header">
	<h1 class="page-title"><?php the_title(); ?></h1>
</header>
<main>
	<div class="section-wrapper bg-white">
		<section class="framed b-green-600">

		<?php

			$args = array(
				'post_status'    => array( 'publish' ),
				'nopaging'       => true,
				'posts_per_page' => '9',
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part( 'template-parts/partials/news' );
				}
			}

			wp_reset_postdata();
			?>

		</section>
	</div>
</main>
<?php
get_footer();
?>