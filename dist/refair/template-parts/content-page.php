<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package refair
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="page-header">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</header>

	<?php refair_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'refair-theme' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	
</article><!-- #post-<?php the_ID(); ?> -->
