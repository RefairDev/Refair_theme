<?php
/**
 * Template name: Page Pour aller plus loin
 * WordPress template for "Pour aller plus loin" static page.
 *
 * @package refair
 */

get_header();
$all_metas = get_post_meta( get_the_id() );
?>
<header class="page-header">
	<h1 class="page-title"><?php the_title(); ?></h1>
</header>
<main class="framed b-green-500 grid-two-columns-lg">
	<?php $section = maybe_unserialize( $all_metas['section_platform_sale'][0] ); ?>
	<section class="platforms-sale">
		<h2 class="section-title">
			<?php echo wp_kses_post( $section['title'] ); ?>
		</h2>
		<div class="section-body">
			<ul class="platforms-list icons">
				<?php foreach ( $section['platforms'] as $platform ) { ?>
					<li class="platform-item"><a class="text-highlighted green-600" href="<?php echo esc_url( $platform['link']['link'] ); ?>" target="_blank">
					<div class="img-wrapper"><img src="<?php echo esc_url( $platform['img']['url'] ); ?>" alt="<?php echo esc_attr( $platform['link']['text'] ); ?>"></div>
						<?php
						if ( ! empty( $platform['link']['text'] ) ) {
							?>
							<span><?php echo wp_kses_post( $platform['link']['text'] ); ?></span><?php } ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php $section = maybe_unserialize( $all_metas['section_platform_reclycling'][0] ); ?>
	<section class="platforms-reclycling">
		<h2 class="section-title">
			<?php echo wp_kses_post( $section['title'] ); ?>
		</h2>
		<div class="section-body">
		<ul class="platforms-list list">
				<?php foreach ( $section['platforms'] as $platform ) { ?>
					<li class="platform-item"><a class="text-highlighted green-600" href="<?php echo esc_url( $platform['link']['link'] ); ?>" target="_blank"><span><?php echo wp_kses_post( $platform['link']['text'] ); ?></span></a>
					<div class="platform-content">
					<?php
					if ( array_key_exists( 'img', $platform ) && array_key_exists( 'url', $platform['img'] ) && ( '#' !== $platform['img']['url'] ) ) {
						?>
						<div class="left-side"><img src="<?php echo esc_url( $platform['img']['url'] ); ?>" alt="" class="platform-img"></div><?php } ?><div class="right-side"><p><?php echo wp_kses_post( $platform['text'] ); ?></p></div></div></li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php $section = maybe_unserialize( $all_metas['section_studies'][0] ); ?>
	<section class="studies two-columns">
		<h2 class="section-title">
			<?php echo wp_kses_post( $section['title'] ); ?>
		</h2>
		<div class="section-body">
		<ul class="studies-list">
				<?php foreach ( $section['studies'] as $study ) { ?>
					<li><a class="text-highlighted green-600" href="<?php echo esc_url( $study['link']['link'] ); ?>" target="_blank"><span><?php echo wp_kses_post( $study['link']['text'] ); ?></span></a>
					<p><?php echo wp_kses_post( $study['text'] ); ?></p></li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php
		get_template_part( 'template-parts/partials/pre-footer-padding' );
	?>
</main>
<?php
get_footer();
?>