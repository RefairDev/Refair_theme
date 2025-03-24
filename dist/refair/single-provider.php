<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package refair
 */

get_header();
$all_metas = get_post_meta( get_the_id() );

/**
 * Get meta value according to $key parameter and $default_value
 *
 * @param  string $key Meta key of the wanted value.
 * @param  mixed  $default_value Default value used if no value gathered from database.
 * @return string meta value
 */
function get_provider_meta( $key, $default_value ): string {
	global $all_metas;
	$returned = $default_value;
	if ( array_key_exists( $key, $all_metas ) ) {
		$returned = $all_metas[ $key ][0];
	}
	return $returned;
}
?>
<div id="materials-notifications"></div>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'provider' ); ?> data-provider-id="<?php echo esc_attr( get_post_meta( get_the_ID(), 'deposit_type_term', true ) ); ?>" >
	<header class="<?php echo ( is_singular() ) ? 'page-header' : 'entry-header'; ?>">
	<?php
	if ( is_singular() ) :
		the_title( '<h1 class="page-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->
	<?php
	if ( is_singular() ) :
		?>
			<div class="referer-section-wrapper b-top-black">
				<div class="referer-section framed b-right-black b-left-black">
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
				</div>
			</div>
			<?php
		endif;
	?>
	<div class="section-wrapper bg-green-600">
		<section class="section-provider-desc b-top-black b-left-black b-right-black">
			<div class="section-body framed b-right-black b-left-black">
				<?php
				$src                     = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];
				$default_thumbnail_class = ' default';
				if ( has_post_thumbnail( get_the_ID() ) ) {
					$src                     = get_the_post_thumbnail_url( null, 'news_single_thumbnail' );
					$default_thumbnail_class = '';
				}
				?>
				<?php get_template_part( 'template-parts/materials/deposits', 'map' ); ?>
				<div class="feature-img<?php echo esc_attr( $default_thumbnail_class ); ?>">
					<?php echo wp_kses_post( get_responsive_image( get_post_thumbnail_id(), array( 570, 0 ) ) ); ?>
				</div>
				<div class="content-heading b-bottom-black text-block">Descriptif</div>
				<?php
				$raw_content     = get_the_content();
				$deposit_content = '';
				if ( ! empty( $raw_content ) ) {
					$deposit_content = wpautop( $raw_content );
				}
				?>
				<div class="content b-bottom-black text-block"><?php echo wp_kses_post( $deposit_content ); ?></div>				
			</div>
		</section>
	</div>

	<div class="section-wrapper">
		<section class="provider-deposits b-left-black b-right-black">
			<header class="section-title b-top-black b-bottom-black">
				<div class="section-title-inner framed b-right-black b-left-black">
					<h2>Les sites</h2>
				</div>
			</header>
			<div class="section-body b-bottom-black">
				<div class="deposits">
					<aside class="aside-deposits">
						<div class="aside-deposits-inner">
							<div id="deposits-sorting" class="deposits-sorting collapsed"></div>
							<div id="deposits-filters" class="deposits-filters collapsed"></div>
						</div>
					</aside>
					<div id="deposits-display" class="deposits-display">
					</div>
				</div>	
			</div>
		</section>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->

<?php
get_footer();
?>
