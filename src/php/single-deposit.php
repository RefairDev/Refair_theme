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
 * Get meta of the deposit with possible default value.
 *
 * @param  string $key Meta key of the deposit.
 * @param  mixed  $default_value Default value of the meta.
 * @return mixed meta value
 */
function get_deposit_meta( $key, $default_value ) {
	global $all_metas;
	$returned = $default_value;
	if ( array_key_exists( $key, $all_metas ) ) {
		$returned = $all_metas[ $key ][0];
	}
	return $returned;
}
?>
<div id="materials-notifications"></div>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'deposit' ); ?> data-id="<?php the_ID(); ?>" data-reference="<?php echo esc_attr( get_post_meta( get_the_ID(), 'reference', true ) ); ?>">
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

	<?php
	$attachments    = maybe_unserialize( $all_metas['galery']['0'] );
	$gallery_length = count( $attachments );
	$slides         = array();
	$anchors        = array();
	$bullets        = array();
	$prev_next      = array();
	foreach ( $attachments as $idx => $attachment ) {
			$active = '';
		if ( 0 === intval( $idx ) ) {
			$active = ' active';
		}
		if ( ( count( $attachments ) - 1 ) === $idx ) {
			$idx_next = 1;
		} else {
			$idx_next = $idx + 2;
		}
		if ( 0 === $idx ) {
			$idx_prev = count( $attachments );
		} else {
			$idx_prev = $idx;
		}
		$slides[]  = "<div class='slide" . $active . "' id='slide-" . strval( $idx + 1 ) . "' data-next='slide-" . strval( $idx_next ) . "' data-prev='slide-" . strval( $idx_prev ) . "' >" . get_responsive_image( $attachment['id'], 'container_two_third_width' ) . '</div>';
		$bullets[] = "<a id='bullet-slide-" . strval( $idx + 1 ) . "' class='slider-bullet" . $active . "' data-target='slide-" . strval( $idx + 1 ) . "'></a>";
		$prev_idx  = $idx + 1;
		if ( $prev_idx < 1 ) {
			$prev_idx = count( $attachments );
		}
			$next_idx    = $idx + 2;
			$prev_next[] = "<div><a class='slider-arrow left'><i class='icono-arrow-right'></i></a><a class='slider-arrow right' ><i class='icono-arrow-left'></i></a></div>";

	}
	?>
	
	<script>
		window.addEventListener("load", function(){
			let bullets = document.querySelectorAll(".slider-bullet");
			bullets.forEach(function(bullet) {
				bullet.addEventListener("click",function(e){
					let activeSlide = document.querySelector('.slide.active')
					let activeBullet = document.querySelector('.slider-bullet.active')
					if (activeSlide){
						activeSlide.classList.remove("active");
					}
					if(activeBullet){activeBullet.classList.remove("active");}
					let newActiveSlide = document.querySelector("#"+e.currentTarget.attributes["data-target"].value);
					if (newActiveSlide){
						newActiveSlide.classList.add("active");
						e.currentTarget.classList.add("active");
					}
				})
			});
			let arrows = document.querySelectorAll(".slider-arrow");
			arrows.forEach(function(arrow) {
				arrow.addEventListener("click",function(e){
					let activeSlide = document.querySelector('.slide.active')
					let activeBullet = document.querySelector('.slider-bullet.active')
					if (activeSlide){
						activeSlide.classList.remove("active");
					}
					if(activeBullet){activeBullet.classList.remove("active");}

					let newActiveSlideId = activeSlide.attributes["data-next"].value;
					if (e.currentTarget.classList.contains("left")){
						newActiveSlideId = activeSlide.attributes["data-prev"].value;
					}
					let newActiveSlide = document.querySelector('#'+newActiveSlideId);
					if (newActiveSlide){
						newActiveSlide.classList.add("active");
						let newBullet = document.querySelector("#bullet-"+newActiveSlideId);
						if (newBullet){
							newBullet.classList.add("active");
						}
					}
				})
			});			
		})
		
	</script>
	<div class="section-wrapper bg-green-600">
		<section class="deposit-desc b-top-black b-left-black b-right-black">
			<div class="section-body framed b-right-black b-left-black">
				<?php
				$src                     = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];
				$default_thumbnail_class = ' default';
				if ( has_post_thumbnail( get_the_ID() ) ) {
					$src                     = get_the_post_thumbnail_url( null, 'news_single_thumbnail' );
					$default_thumbnail_class = '';
				}
				?>
				<div class="feature-img<?php echo esc_attr( $default_thumbnail_class ); ?>">
					<div class="deposit-gal CSSgal">
						<div class="slider">
							<?php echo wp_kses_post( implode( '', $slides ) ); ?>
						</div>
						<div class="prevNext">
							<?php echo wp_kses_post( implode( '', $prev_next ) ); ?>
						</div>
						<div class="bullets">
							<?php echo wp_kses_post( implode( '', $bullets ) ); ?>
						</div>
					</div>
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
				<div class="plus-heading b-bottom-black text-block">les +</div>
				<div class="plus-body text-block b-bottom-black "><?php echo wp_kses_post( nl2br( get_deposit_meta( 'plus_details', 'Non renseigné' ) ) ); ?></div>
				<div class="availability-heading b-right-black text-block">Date de récupération</div>
				<div class="availability-body text-block"><?php echo wp_kses_post( wpautop( get_deposit_meta( 'availability_details', 'Non renseigné' ) ) ); ?></div>
				<?php
					$ressources_url = get_post_meta( get_the_ID(), 'ressources_url', true );
				if ( empty( $ressources_url ) && is_user_logged_in() ) {
					$ressources_url = get_rest_url( null, 'deposit/' . get_the_ID() . '/download' );
				}
				if ( ! empty( $ressources_url ) ) {
					?>
					<div class="dl-ressources "> <a href="<?php echo esc_url( $ressources_url ); ?>">Télécharger les ressources</a></div>
					<?php
				}
				?>

			</div>
		</section>
	</div>

	<div class="section-wrapper">
		<section class="deposit-materials b-left-black b-right-black">
			<header class="section-title b-top-black b-bottom-black">
				<div class="section-title-inner framed b-right-black b-left-black">
					<h2>Les Matériaux</h2>
				</div>
			</header>
			<div class="section-body b-bottom-black">
				<div class="materials">
					<aside class="aside-materials">
						<div class="aside-materials-inner">
							<div id="materials-sorting" class="materials-sorting collapsed"></div>
							<div id="materials-filters" class="materials-filters collapsed"></div>
						</div>
					</aside>
					<div id="materials-display" class="materials-display">
					</div>
				</div>	
			</div>
		</section>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->

<?php
get_footer();
?>
