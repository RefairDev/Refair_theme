<?php
/**
 * Template part to display news item
 *
 * @package refair
 */

global $post;
$post_metas = get_post_meta( $post->ID );
if ( isset( $post_metas['external_link'] ) ) {
	$e_l = maybe_unserialize( $post_metas['external_link'][0] );
}
?>
<article class="news-item">
	<div class="news-img">
		<a href="<?php the_permalink(); ?>"><img alt="" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'container_third_width_sqr' ) ); ?>"></a>
	</div> 
	<h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<div class="date"><?php echo get_the_date(); ?></div>
	<div class="news-excerpt">
		<?php the_excerpt(); ?>
	</div>
	<?php if ( isset( $e_l ) && '' === $e_l['link'] ) { ?>
	<div class="news-link">
		<div class="news-link-text"><span><?php echo wp_kses_post( $e_l['text'] ); ?></span></div><a class="read-more" href="<?php echo esc_url( $e_l['link'] ); ?>"><i class="icono-arrow-left"></i></a>
	</div>
	<?php } ?>
</article>
