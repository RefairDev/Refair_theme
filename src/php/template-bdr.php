<?php
/**
 * Template Name: Page Base du réemploi
 * WordPress template for "Base du réemploi" static page.
 *
 * @package refair
 */

get_header();
$all_metas = get_post_meta( get_the_id() );

$logo_url = '';
if ( array_key_exists( 'logo_bdr', $all_metas ) ) {
	$raw_logo_data = maybe_unserialize( $all_metas['logo_bdr'][0] );
	if ( array_key_exists( 'url', $raw_logo_data ) ) {
		$logo_url = $raw_logo_data['url'];
	}
}
?>
<header class="page-header">
	<section class="splash-screen">
		<div class="img-bg"><?php the_post_thumbnail( 'full' ); ?></div>
		<h1 class="splash-screen-title"><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php the_title(); ?>"></h1>
	</section>
</header>
<main>
	<section class="introduction">
		<div class="section-container">
			<div class="section-title">
				<h2><?php echo wp_kses_post( $all_metas['introduction_title'][0] ); ?></h2>
			</div>
			<hr>
			<div class="section-content with-half-circle-list-style inset">
				<div class="section-details">
					<?php
					foreach ( maybe_unserialize( $all_metas['introduction_details'][0] ) as $intro_detail ) {
						?>
						<div class="desc-header"><?php echo wp_kses_post( $intro_detail['header'] ); ?></div>
						<div class="desc-desc"><?php echo wp_kses_post( $intro_detail['description'] ); ?></div>
						<?php
					}
					?>

				</div>
				<div class="section-main-content"><?php the_content(); ?></div>
			</div>
		</div>
	</section>
	<section class="axonometry ">
		<div class="section-container">
			<div class="section-content">
				<div class="axono-wrapper">
					<?php
						$section        = maybe_unserialize( $all_metas['section_axonometrie'][0] );
						$layers         = '';
						$hover_triggers = '';
						$triggers       = '';
						$axono_info     = array();

					foreach ( $section['axono_layers'] as $layer ) {

						$layers .= "<img id='layer-" . sanitize_html_class( $layer['title'] ) . "'class='axono-layer' src='" . esc_url( $layer['axono_image']['url'] ) . "' alt='" . $layer['title'] . "'>";

						$hover_triggers .= "<div class='hover-trigger' data-layer='layer-" . sanitize_html_class( $layer['title'] ) . "' ></div>";

						$triggers .= "<a class='click-trigger' data-layer='layer-" . sanitize_html_class( $layer['title'] ) . "' >" . $layer['title'] . '</a>';

						$axono_info[ 'layer-' . sanitize_html_class( $layer['title'] ) ] = array(
							'title'       => $layer['title'],
							'surface'     => $layer['surface'],
							'description' => $layer['description'],
						);
					}
					echo $hover_triggers;
					?>
					<img  class='axono-foreground' src="<?php echo esc_url( $section['main_infographic']['url'] ); ?>" alt="">
					<?php
					echo $layers;
					?>
					<img id="axonometrie" class='axo-background' src="<?php echo esc_url( $section['main_infographic_background']['url'] ); ?>" alt="">

				</div>
				<div class="axono-info">
					<h3></h3>
					<p class="surface"></p>
					<p class="content" ></p>
				</div>
				<div class='triggers-list'>
				<?php
				echo wp_kses_post( $triggers );
				?>
				</div>
			</div>
			<script>
				const axonoInfoJson = '<?php echo addslashes( wp_json_encode( $axono_info, JSON_UNESCAPED_LINE_TERMINATORS ) ); ?>';
				const axonoInfo     = JSON.parse(axonoInfoJson);
				window.addEventListener('load', function(){
					let clickTriggers = document.querySelectorAll(".click-trigger");
					clickTriggers.forEach(
						node => node.addEventListener('click',
						e => {
							document.querySelectorAll('.click-trigger.active').forEach(node=> node.classList.remove('active'));
							e.target.classList.add('active');
							document.querySelectorAll('.axono-layer.show').forEach(node=> node.classList.remove('show'));
							document.querySelector('#'+e.target.attributes['data-layer'].value).classList.toggle('show');
							document.querySelector('.axono-info h3').innerHTML = axonoInfo[e.target.attributes['data-layer'].value]['title'];
							document.querySelector('.axono-info p.surface').innerHTML = axonoInfo[e.target.attributes['data-layer'].value]['surface'];
							document.querySelector('.axono-info p.content').innerHTML  = axonoInfo[e.target.attributes['data-layer'].value]['description'];
							document.querySelector('.axono-info').classList.add('active');
						}
						)
						);
					let hoverTriggers = document.querySelectorAll(".hover-trigger");
					hoverTriggers.forEach(
						node => {node.addEventListener('mouseover',
						e => {
							document.querySelectorAll('.click-trigger.active').forEach(node=> node.classList.remove('active'));
							document.querySelector('.click-trigger[data-layer=' + e.target.attributes['data-layer'].value + ']').classList.add('active');
							document.querySelectorAll('.axono-layer.show').forEach(node=> node.classList.remove('show'));
							document.querySelector('#'+e.target.attributes['data-layer'].value).classList.toggle('show');
							document.querySelector('.axono-info h3').innerHTML = axonoInfo[e.target.attributes['data-layer'].value]['title'];
							document.querySelector('.axono-info p.surface').innerHTML = axonoInfo[e.target.attributes['data-layer'].value]['surface'];
							document.querySelector('.axono-info p.content').innerHTML = axonoInfo[e.target.attributes['data-layer'].value]['description'];
							document.querySelector('.axono-info').classList.add('active');
						}
						);
						node.addEventListener('mouseout',
						e => {
							document.querySelectorAll('.click-trigger.active').forEach(node=> node.classList.remove('active'));
							document.querySelectorAll('.axono-layer.show').forEach(node=> node.classList.remove('show'));
							document.querySelector('.axono-info h3').innerHTML = "";
							document.querySelector('.axono-info p.surface').innerHTML = "";
							document.querySelector('.axono-info p.content').innerHTML = "";
							document.querySelector('.axono-info').classList.remove('active');
						}
						);
						node.addEventListener('click',
						e => {
							document.querySelectorAll('.click-trigger.active').forEach(node=> node.classList.remove('active'));
							document.querySelector('.click-trigger[data-layer=' + e.target.attributes['data-layer'].value + ']').classList.add('active');
							document.querySelectorAll('.axono-layer.show').forEach(node=> node.classList.remove('show'));
							document.querySelector('#'+e.target.attributes['data-layer'].value).classList.toggle('show');
							document.querySelector('.axono-info h3').innerText = axonoInfo[e.target.attributes['data-layer'].value]['title'];
							document.querySelector('.axono-info p.surface').innerHTML = axonoInfo[e.target.attributes['data-layer'].value]['surface'];
							document.querySelector('.axono-info p.content').innerHTML = axonoInfo[e.target.attributes['data-layer'].value]['description'];
							document.querySelector('.axono-info').classList.add('active');
						}
						)
					}
						);					
				})
			</script>
		</div>
	</section>
	<section class="occupants">
		<div class="section-container">
			<?php $section = maybe_unserialize( $all_metas['occupants'][0] ); ?>
			<div class="section-title"><h2><?php echo wp_kses_post( $section['block_title'] ); ?></h2></div>
			<hr>
			<div class="section-content">
				<?php
				foreach ( $section['block_items'] as $block_item ) {
					?>
					<div class="section-item">
						<div class="section-item-image">
							<?php $img_alt = get_post_meta( $block_item['block_item_image']['id'], '_wp_attachment_image_alt', true ); ?>
							<img src="<?php	echo esc_url( $block_item['block_item_image']['url'] ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
						</div>
						<h3 class="section-item-title">
							<a href="<?php echo esc_url( $block_item['block_item_title']['link'] ); ?>">
								<span>
									<?php
									echo wp_kses_post( $block_item['block_item_title']['text'] );
									?>
								</span>
							</a>
						</h3>
						<div class="section-item-text with-half-circle-list-style inset">
							<?php
							echo wp_kses_post( $block_item['block_item_text'] );
							?>
					</div>							
					</div>
					<?php
				}
				?>
			</div>

		</div>
	</section>
	<section class="carousel">
		<div class="section-container">
			<div class="bdr-gallery">
				<?php $section = maybe_unserialize( $all_metas['bdr_gallery'][0] ); ?>
				<?php
				foreach ( $section as $image ) {
					?>
					<div class='gallery-item'>
						<img src="<?php echo esc_url( $image['url'] ); ?>" alt="" />
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</section>
	<?php $section = maybe_unserialize( $all_metas['key_figures'][0] ); ?>
	<section class="key-figures">
		<div class="section-container">
			<div class="section-title"><h2>La base du réemploi en chiffres</h2></div>
			<hr>
			<div class="section-content">
				<?php
				foreach ( $section as $key_number ) {
					?>
					<div class="key-figure">
						<div class="value"><?php echo wp_kses_post( $key_number['value'] ); ?></div>
						<div class="legend"><?php echo wp_kses_post( $key_number['legend'] ); ?></div>
					</div>
					<?php
				}
				?>
			</div>

			
		</div>
	</section>	
	<section class="demarche">
		<div class="section-container">
			<?php $section = maybe_unserialize( $all_metas['block_demarche'][0] ); ?>

			<div class="block-title"><h2><?php echo wp_kses_post( $section['block_title'] ); ?></h2></div>
			<hr>
			<div class="block-content">
				<div class="block-text">
					<div class="block-text-inner with-half-circle-list-style inset">
						<?php echo wp_kses_post( $section['block_text'] ); ?>
					</div>					
				</div>
				<div class="block-links with-half-circle-list-style inset">
					<h3 class="block-links-title">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.46 47.46"><defs></defs><path class="user-action-svg" d="M33.72,26.17c-.41-2.74-1-5.44-1.27-8.2a46.12,46.12,0,0,1-.15-4.7c0-.63,0-1.26.06-1.88a4.33,4.33,0,0,0,0-.64c-.09,0-.42-.1-.62-.11a126.92,126.92,0,0,0-16.89.41,130.45,130.45,0,0,0,.35,17.12c.13,1.47.27,2.94.45,4.4.07.61.15,1.21.23,1.81a5.09,5.09,0,0,1,.12.79,9.72,9.72,0,0,1,2.35.14c.72,0,1.44,0,2.17,0,1.36,0,2.73,0,4.09-.09,1.53-.07,3.05-.19,4.57-.36.77-.09,1.53-.18,2.3-.3a6.58,6.58,0,0,0,2-.33c.74-.59.57-2.9.56-3.84A29.08,29.08,0,0,0,33.72,26.17ZM18.21,22.42a1.26,1.26,0,0,1,1.25-1.25c2.62-.1,5.25-.26,7.87-.49a1.26,1.26,0,0,1,1.25,1.25,1.28,1.28,0,0,1-1.25,1.25c-2.62.23-5.25.39-7.87.49A1.26,1.26,0,0,1,18.21,22.42Zm9.66,6.45a28.92,28.92,0,0,1-7.88.47,1.29,1.29,0,0,1-1.25-1.25A1.26,1.26,0,0,1,20,26.84a26.64,26.64,0,0,0,7.22-.38,1.29,1.29,0,0,1,1.54.88A1.25,1.25,0,0,1,27.87,28.87ZM28,17.38H18.88a1.25,1.25,0,0,1,0-2.5H28A1.25,1.25,0,0,1,28,17.38Z"/><path class="user-action-svg" d="M23.73,0A23.73,23.73,0,1,0,47.46,23.73,23.72,23.72,0,0,0,23.73,0ZM36.22,34.43a2.89,2.89,0,0,1-2.37,2.33,55,55,0,0,1-10.16,1.05c-1.77.07-3.53.07-5.3,0-1.19,0-3,.19-4-.6s-.92-2.58-1.08-3.77c-.18-1.46-.34-2.92-.47-4.38A130.38,130.38,0,0,1,12.43,9.9a1.22,1.22,0,0,1,.66-1.05,1.33,1.33,0,0,1,.59-.2C17.63,8.26,21.6,8,25.57,8q3,0,6,.11c1.46.07,2.91.16,3.29,1.85.32,1.46,0,3.21,0,4.7a38.57,38.57,0,0,0,.36,5c.48,3.26,1.26,6.5,1.42,9.81A17.51,17.51,0,0,1,36.22,34.43Z"/></svg>
						<span>Télécharger</span>
					</h3>
					<ul>
					<?php
					foreach ( $section['block_links'] as $block_link ) {
						$block_text = $block_link['text'];
						?>
						<li>
							<a class="block-link" href="<?php echo esc_url( $block_link['document']['url'] ); ?>">
								<span class="main-desc">
									<?php echo wp_kses_post( $block_link['document']['caption'] ); ?>
								</span>
								<br>
								<span class="details-desc">
									<?php echo wp_kses_post( $block_link['doc_details'] ); ?>
								</span>
							</a>
						</li>
						<?php
					}
					?>
					</ul>
			</div>				

		</div>
	</section>
	
</main>
	<link rel="stylesheet" href="https://unpkg.com/flickity-fade@1/flickity-fade.css">
	<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
	<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
	<script src="https://unpkg.com/flickity-fade@1/flickity-fade.js"></script>
	<script>
		window.addEventListener('load', (e) =>{
			var flkty = new Flickity( '.bdr-gallery', {
				wrapAround: true,
				imagesLoaded: true,
				prevNextButtons: false,
				pageDots: false,
				autoPlay: true,
				fade: true
			});			
		})
	</script>
<?php
get_footer();
?>
