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
		<div class="<?php echo ( is_singular() ) ? 'page-header' : 'entry-header'; ?>">
			<?php
			if ( 'post' === get_post_type() ) {
				$cats = wp_get_post_categories();
				if ( 0 === count( $cats ) ) {
					$post_banner_title = __( 'news', 'refair-theme' );
				} else {
					$post_banner_title = $cats[0]->name;
				}
				?>
			<div class="post-banner">
				<span><?php echo wp_kses_post( $post_banner_title ); ?></span>
			</div>
			<?php } else { ?>					
				<header>
				<?php
				if ( is_singular() ) :
					the_title( '<h1 class="post-title">', '</h1>' );
				else :
					the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;
				?>
			</header><!-- .entry-header -->
			<?php } ?>
		</div>
		<?php if ( is_singular() ) { ?>
		<div class="framed">
			<a class="referer-link" href="
			<?php
			$http_referer = '';
			if ( array_key_exists( 'HTTP_REFERER', $_SERVER ) ) {
				$http_referer = esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
			}

			if ( '' !== $http_referer ) {
				echo esc_url( $http_referer );
			} else {
				bloginfo( 'url' );}
			?>
			" ><i class="icono-arrow-right"></i> Retour</a>
			<div class="featured-img-wrapper">
				<?php
				echo wp_kses_post(
					get_responsive_image(
						get_post_thumbnail_id(),
						'container_width',
						array(
							'alt'   => get_the_title(),
							'class' => 'featured-img',
						)
					)
				);
				?>
			</div>
		</div>
		<?php } ?>
		<div class="post-header-wrapper">
			<header class="framed">
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

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'refair-theme' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
	</div>
	<div class="container">
		<footer class="entry-footer">
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
