<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package refair
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			?>
			<div class="container framed">
			<?php
			the_post_navigation(
				array(
					'prev_text' => "<i class='icono-arrow-right'></i><span>%title</span>",
					'next_text' => "<span>%title</span><i class='icono-arrow-left'></i>",
				)
			);
			?>
			</div>
			<?php
		endwhile; // End of the loop.
		?>
		<?php
		get_template_part( 'template-parts/partials/pre-footer-padding' );
		?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
