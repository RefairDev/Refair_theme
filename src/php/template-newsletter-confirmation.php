<?php
/**
 * Template name: Confirmation inscription Newsletter
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="container">
				<div class="entry-content framed">
					<?php
					the_content(
						sprintf(
							wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'refair-theme' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							get_the_title()
						)
					);
					?>
				</div><!-- .entry-content -->
			</div>
		</article><!-- #post-<?php the_ID(); ?> -->
			<?php
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();