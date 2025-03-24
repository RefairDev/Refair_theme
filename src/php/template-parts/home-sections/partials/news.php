<?php
/**
 * Template part of news item
 *
 * @package refair
 */

global $post;
?>
<div class="swiper-slide news-item">
	<div class="news-img">
		<a href="<?php the_permalink(); ?>"><?php echo wp_kses_post( get_responsive_image( get_post_thumbnail_id(), array( 680, 0 ), array( 'alt' => get_the_title() ) ) ); ?></a>
	</div> 
	<h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<div class="date"><?php the_date(); ?></div>
	<div class="news-excerpt">
		<?php the_excerpt(); ?>
	</div>
</div>
