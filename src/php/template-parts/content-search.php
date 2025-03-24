<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package refair
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$thumnail = '';
	ob_start();
	refair_post_thumbnail();
	$thumbnail = ob_get_clean();
	if ( '' === $thumbnail ) {
		ob_start();
		?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<div class="default-thumbnail">
				<?php
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
				$image[0];
				?>
				<img src=<?php echo esc_url( $image[0] ); ?> alt="<?php the_title(); ?>">
			</div>
		</a>
		<?php
		$thumbnail = ob_get_clean();
	}
	echo wp_kses_post( $thumbnail );
	?>
	<header class="entry-header">
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<div class="result-link">
			<div class="result-link-text"><span>Lire la suite</span></div><a class="read-more" href="<?php the_permalink(); ?>"><i class="icono-arrow-left"></i></a>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
