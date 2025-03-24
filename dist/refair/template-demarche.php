<?php
/**
 * Template Name: Page Démarche
 * WordPress template for "Base du réemploi" static page.
 *
 * @package refair
 */

get_header();

$all_metas = get_post_meta( get_the_id() );
?>
<header class="page-header">
	<h1 class="page-title"><?php the_title(); ?></h1>
</header>
<main>
	<?php $section = maybe_unserialize( $all_metas['section_description'][0] ); ?>
	<div class="section-wrapper ">
		<section class="framed b-green-400 description b-bottom-green-400">
			<header class="section-title">
				<div class="section-title-inner b-bottom-green-400">
					<h2><?php echo wp_kses_post( $section['title'] ); ?></h2>
				</div>
			</header>
			<div class="introduction b-bottom-green">
				<div class="big-letter b-green">
					<div class="sub-mask left"></div>
					<div class="mask-wrapper">
						<?php get_mask_letter( '?', 'white', true ); ?>
						<?php echo wp_kses_post( get_responsive_image( $section['side_image']['id'], array( 295, 430 ), array( 'alt' => $section['title'] ) ) ); ?>
					</div>
					<div class="sub-mask right"></div>
				</div>
				<div class="intro-text b-left-green half-circle-list-style outset"><?php echo wp_kses_post( wpautop( $section['desc'] ) ); ?></div>
			</div>			
		</section>
	</div>	
	<?php $section = maybe_unserialize( $all_metas['section_principles'][0] ); ?>
	<section class="principles">
		<header class="section-title">
			<div class="section-title-inner framed b-bottom-green-400">
				<h2><?php echo wp_kses_post( $section['title'] ); ?></h2>
			</div>
		</header>
		<div class="section-body framed b-green-400 b-bottom-green-400">
			<div class="principles-list">
			<?php
			foreach ( $section['principles'] as $idx => $principle ) {
				?>
				<div class="principle">
					<h3 class="principle-title round-border-shape">
						<div class="principle-idx-wrapper"><div class="principle-idx"><?php echo wp_kses_post( $idx + 1 ); ?></div></div>
						<?php echo wp_kses_post( $principle['title'] ); ?>
					</h3>
					<div class="principle-desc">
						<?php echo wp_kses_post( $principle['desc'] ); ?>
					</div>
				</div>				
				<?php
			}
			?>
			</div>
		</div>
	</section>
	<?php $section = maybe_unserialize( $all_metas['section_tools'][0] ); ?>
	<section class="tools">
		<header class="section-title">
			<div class="section-title-inner framed b-bottom-green-400">
				<h2><?php echo wp_kses_post( $section['title'] ); ?></h2>
			</div>
		</header>
		<div class="section-body framed b-bottom-green-400 ">
				<?php
				foreach ( $section['tools'] as $tool ) {
					?>
					<div class="tool">
						<div class="tool-illustration b-right-green-400"><img src="<?php echo wp_kses_post( $tool['image']['url'] ); ?>"></div>
						<h3 class="title b-bottom-green-400 b-green-400"><?php echo wp_kses_post( $tool['title'] ); ?></h3>
						<div class="desc b-green-400"><?php echo wp_kses_post( wpautop( $tool['desc'] ) ); ?></div>
						<?php
						if ( is_array( $tool['read_more'] ) && '' !== $tool['read_more']['link'] ) {
							?>
							<div class="read-more b-top-green-400"><a href="<?php echo esc_url( $tool['read_more']['link'] ); ?>"><?php echo wp_kses_post( $tool['read_more']['text'] ); ?></a></div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
						</div>
	</section>
	<?php $section = maybe_unserialize( $all_metas['section_ressources'][0] ); ?>
	<section class="platform bg-white">
		<header class="section-title">
			<div class="section-title-inner framed b-green-400">
				<h2><?php echo wp_kses_post( $section['title'] ); ?></h2>
			</div>
		</header>
		<div class="section-body framed b-green-400">
			<div class="ressources-lists">
				<?php
				foreach ( $section['ressources_categories'] as $section_category ) {
					?>
					<div class="ressources-category">
						<h3 class="ressource-category-title round-border-shape">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.46 47.46"><defs></defs><path class="user-action-svg" d="M33.72,26.17c-.41-2.74-1-5.44-1.27-8.2a46.12,46.12,0,0,1-.15-4.7c0-.63,0-1.26.06-1.88a4.33,4.33,0,0,0,0-.64c-.09,0-.42-.1-.62-.11a126.92,126.92,0,0,0-16.89.41,130.45,130.45,0,0,0,.35,17.12c.13,1.47.27,2.94.45,4.4.07.61.15,1.21.23,1.81a5.09,5.09,0,0,1,.12.79,9.72,9.72,0,0,1,2.35.14c.72,0,1.44,0,2.17,0,1.36,0,2.73,0,4.09-.09,1.53-.07,3.05-.19,4.57-.36.77-.09,1.53-.18,2.3-.3a6.58,6.58,0,0,0,2-.33c.74-.59.57-2.9.56-3.84A29.08,29.08,0,0,0,33.72,26.17ZM18.21,22.42a1.26,1.26,0,0,1,1.25-1.25c2.62-.1,5.25-.26,7.87-.49a1.26,1.26,0,0,1,1.25,1.25,1.28,1.28,0,0,1-1.25,1.25c-2.62.23-5.25.39-7.87.49A1.26,1.26,0,0,1,18.21,22.42Zm9.66,6.45a28.92,28.92,0,0,1-7.88.47,1.29,1.29,0,0,1-1.25-1.25A1.26,1.26,0,0,1,20,26.84a26.64,26.64,0,0,0,7.22-.38,1.29,1.29,0,0,1,1.54.88A1.25,1.25,0,0,1,27.87,28.87ZM28,17.38H18.88a1.25,1.25,0,0,1,0-2.5H28A1.25,1.25,0,0,1,28,17.38Z"/><path class="user-action-svg" d="M23.73,0A23.73,23.73,0,1,0,47.46,23.73,23.72,23.72,0,0,0,23.73,0ZM36.22,34.43a2.89,2.89,0,0,1-2.37,2.33,55,55,0,0,1-10.16,1.05c-1.77.07-3.53.07-5.3,0-1.19,0-3,.19-4-.6s-.92-2.58-1.08-3.77c-.18-1.46-.34-2.92-.47-4.38A130.38,130.38,0,0,1,12.43,9.9a1.22,1.22,0,0,1,.66-1.05,1.33,1.33,0,0,1,.59-.2C17.63,8.26,21.6,8,25.57,8q3,0,6,.11c1.46.07,2.91.16,3.29,1.85.32,1.46,0,3.21,0,4.7a38.57,38.57,0,0,0,.36,5c.48,3.26,1.26,6.5,1.42,9.81A17.51,17.51,0,0,1,36.22,34.43Z"/></svg>
							<span><?php echo wp_kses_post( $section_category['title'] ); ?></span>
						</h3>
						<ul class="ressources-list half-circle-list-style inset">
						<?php
						foreach ( $section_category['list'] as $key => $ressource ) {
							if ( '' !== $ressource['doc']['url'] && '#' !== $ressource['doc']['url'] ) {
								?>
								<li><a href="<?php echo esc_url( $ressource['doc']['url'] ); ?>"><?php echo wp_kses_post( $ressource['doc']['caption'] ); ?></a><span><?php echo wp_kses_post( $ressource['year'] ); ?></span></li>
								<?php
							}
						}
						?>
						</ul>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
?>
