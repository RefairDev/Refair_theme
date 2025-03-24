<?php
/**
 * Template part of the home page to display approach section
 *
 * @package refair
 */

?>
<section class="section-approach" >
	<div class="section-header">
		<div class="section-header-inner">
			<h2><?php echo wp_kses_post( $section['title'] ); ?>
			<div class="actions">
				<a href="<?php echo esc_url( $section['link']['link'] ); ?>">
					<div class="action-label"><?php echo esc_textarea( $section['link']['text'] ); ?></div>
					<div class="action-btn"><div><i class="icono-arrow-left"></i></div></div>
				</a>
			</div>
			</h2>
		</div>
	</div>
	<div class="section-body">
		<div class="section-body-inner">
		<?php
		$only_desktop_class = '';
		if ( array_key_exists( 'mobile_img_approach', $section ) && array_key_exists( 'id', $section['mobile_img_approach'] ) && 0 !== intval( $section['mobile_img_approach']['id'] ) ) {
			$only_desktop_class = ' desktop';
		}
		?>
			<div class="approach_infographic<?php echo esc_attr( $only_desktop_class ); ?>">
				<?php
				$alt_text  = $section['desktop_img_approach']['caption'];
				$attch_alt = get_post_meta( $section['desktop_img_approach']['id'], '_wp_attachment_image_alt', true );
				if ( empty( $alt_text ) && false !== $attch_alt ) {
					$alt_text = $attch_alt;
				}
				$img_str = get_responsive_image(
					$section['desktop_img_approach']['id'],
					'container_width',
					array(
						'sizes' => '(min-width: 1200px) 1140px, (min-width: 992px) 960px, 100vw',
						'alt'   => $alt_text,
					)
				);
				echo wp_kses_post( $img_str );
				?>
								
			</div>
			<?php if ( array_key_exists( 'mobile_img_approach', $section ) && array_key_exists( 'id', $section['mobile_img_approach'] ) && 0 !== intval( $section['mobile_img_approach']['id'] ) ) { ?>
			<div class="approach_infographic mobile">
				<?php
				$alt_text  = $section['mobile_img_approach']['caption'];
				$attch_alt = get_post_meta( $section['mobile_img_approach']['id'], '_wp_attachment_image_alt', true );
				if ( empty( $alt_text ) && false !== $attch_alt ) {
					$alt_text = $attch_alt;
				}
				$img_str = get_responsive_image(
					$section['mobile_img_approach']['id'],
					'container_width',
					array(
						'sizes' => '(min-width: 1200px) 1140px, (min-width: 992px) 960px, 100vw',
						'alt'   => $alt_text,
					)
				);
				echo wp_kses_post( $img_str );
				?>
								
			</div>
				<?php
			}
			?>
			<div class="key-figures">

			<?php
			foreach ( $section['key_figures'] as $key_figure ) {
				?>
				<div class="key-figure">
					<div class="key-figure-value"><?php echo wp_kses_post( $key_figure['figure'] ); ?></div>
					<div class="key-figure-desc"><?php echo wp_kses_post( $key_figure['description'] ); ?></div>
				</div>
				<?php
			}
			?>
			</div>
		</div>
	</div>
</section>
