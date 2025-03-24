<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package refair
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">
		<div class="post-header-wrapper">
			<header class="
			<?php
			if ( is_singular() ) {
				echo 'framed';
			}
			?>
			">
				<?php
				if ( is_singular() ) :

					the_title( '<h1 class="post-title">', '</h1>' );
				else :
					the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;
				?>

			</header><!-- .entry-header -->
		</div>
	</div>
	<div class="container">
		<div class="
		<?php
		if ( is_singular() ) {
			echo 'entry-content framed';}
		?>
		">
			<div class="deposit-img">
				<a href="<?php the_permalink(); ?>"><img alt="" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'container_third_width_sqr' ) ); ?>"></a>
			</div> 
			<div class="deposit-excerpt">
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

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'refair-theme' ),
					'after'  => '</div>',
				)
			);
			?>
			</div> 
		</div><!-- .entry-content -->
	</div>
	<div class="container">
		<footer class="entry-footer">
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
