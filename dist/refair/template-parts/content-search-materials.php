<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package refair
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'material' ); ?>>
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
		<?php
		$deposit_ref  = get_post_meta( get_the_ID(), 'deposit', true );
		$deposit_post = get_deposit_by_ref( $deposit_ref );
		$availability = get_post_meta( $deposit_post->ID, 'availability_details', true );
		$mat_prdct    = wc_get_product( get_the_ID() );

		$unit = get_post_meta( get_the_ID(), 'unit', true );
		?>
		<div><a href="<?php the_permalink( $deposit_post->ID ); ?>"><?php echo wp_kses_post( $deposit_post->post_title ); ?></a></div>
		<div><?php echo wp_kses_post( $availability ); ?></div>
		<div> Stock: 
		<?php
		echo wp_kses_post( $mat_prdct->get_stock_quantity() );
		if ( 'u' !== $unit ) {
			echo wp_kses_post( $unit );
		}
		?>
		</div>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<div class="result-link">
			<div class="result-link-text"><span>Voir le matériau</span></div><a class="read-more" href="<?php the_permalink(); ?>"><i class="icono-arrow-left"></i></a>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
