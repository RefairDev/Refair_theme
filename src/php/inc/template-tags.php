<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package refair
 */

if ( ! function_exists( 'refair_post_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function refair_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;}

		if ( is_singular() ) {
			?>
			<div class="post-thumbnail">
			<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->
			
			<?php
		} else {
			?>
				<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
						</a>						
					<?php
		}
	}
}


if ( ! function_exists( 'refair_display_mail_address' ) ) {
	/**
	 * Offuscate mail address
	 *
	 * @param  string $mail_address Original mail address.
	 * @return string Offuscatd mail address.
	 */
	function refair_display_mail_address( $mail_address ) {
		$pattern = '/(.+)@(.+)\.(.+)/i';

		$replacement = '${1}<!--dummy@dumy.fr-->@<!--dummy@dumy.fr-->${2}.<!--dummy@dumy.fr-->${3}';
		return preg_replace( $pattern, $replacement, $mail_address );
	}
}


if ( ! function_exists( 'get_responsive_image' ) ) {
	/**
	 * Get img tag filled with srcset and sizes attributes
	 *
	 * @param  int    $attachment_id Attachment ID.
	 * @param  string $size WordPress size designation (full, large, etc ).
	 * @param  array  $attr img complémentary attributes (alt, etc.).
	 * @return string Html img tag in string.
	 */
	function get_responsive_image( $attachment_id, $size, $attr = array() ) {
		$image = wp_get_attachment_image_src( $attachment_id, $size );

		if ( $image ) {
			list( $src, $width, $height ) = $image;
			$image_meta                   = wp_get_attachment_metadata( $attachment_id );

			if ( is_array( $image_meta ) ) {
				$size_array = array( absint( $width ), absint( $height ) );
				$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $attachment_id );
				$sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id );

				if ( $srcset && ( $sizes || ! empty( $attr['sizes'] ) ) ) {
					$attr['srcset'] = $srcset;

					if ( empty( $attr['sizes'] ) ) {
						$attr['sizes'] = $sizes;
					}
				}
			}

			$attr = array_map( 'esc_attr', $attr );
			$html = rtrim( '<img' );
			foreach ( $attr as $name => $value ) {
				$html .= " $name=" . '"' . $value . '"';
			}

			$html .= ' />';
			return $html;
		}
		return '';
	}
}
if ( ! function_exists( 'get_mask_letter' ) ) {
	/**
	 * Get svg mask corresponding to character
	 *
	 * @param  string  $letter Character to get svg mask.
	 * @param  string  $color Hexadecimal code of the color.
	 * @param  boolean $echo_mask Does function have to echo result.
	 * @return string Svg mask corresponding to input character.
	 */
	function get_mask_letter( $letter, $color, $echo_mask = false ) {
		$class_id = wp_generate_uuid4();
		ob_start();
		switch ( $letter ) {
			case 'A':
				?>
							<svg id="mask-a" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 450"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="M-2,0V450H340V0ZM309.8,234.52c-4.17,89.27-76,161.81-163,163.79-38.91.89-77.85.15-118.8.15V52.79c47.51,0,92.92-4,137.43.85C251.71,63,314,143.79,309.8,234.52Z"/></svg>
							<?php
				break;

			case 'D':
				?>
							<svg id="mask-d" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 450"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="M-2,0V450H340V0ZM309.8,234.52c-4.17,89.27-76,161.81-163,163.79-38.91.89-77.85.15-118.8.15V52.79c47.51,0,92.92-4,137.43.85C251.71,63,314,143.79,309.8,234.52Z"/></svg>
							<?php
				break;

			case 'E':
				?>
							<svg id="mask-e" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="M-.5-.5v81h56V-.5ZM50.2,28.83H26.84V30.5H48.5v19H26.84v1.67H50.2V72.5H4.5V7.5H50.2Z"/></svg>
							<?php
				break;

			case 'F':
				?>
							<svg id="mask-f" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>"  d="M0,0v81h56V0H0ZM50.7,29.3h-23.4v1.7h21.7v19h-21.7v23s-7.1,0-22.3,0V8h45.7v21.3Z"/></svg>
							<?php
				break;

			case 'L':
				?>
							<svg id="mask-l" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="M-1.5-1.5v83h58v-83ZM50,72.5H5V7.5H27v43H50C50,59.39,50.14,64.18,50,72.5Z"/></svg>
							<?php
				break;

			case 'M':
				?>
							<svg id="mask-m" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 75 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="M-.49-.5v81h76V-.5Zm70,73h-64V7.5l32,15.09,32-14.95Z"/></svg>
							<?php
				break;

			case 'N':
				?>
							<svg id="mask-n" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="m0-.5v81h55V-.5H0Zm27.5,18.5V7h23v66l-23-11v11H4.5V7l23,11Z"/></svg>
							<?php
				break;

			case 'P':
				?>
							<svg id="mask-p" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="M0-.5v81H55V-.5ZM51.08,33.32A24.72,24.72,0,0,1,44,49.5a24.79,24.79,0,0,1-17,7v16H4V7.5H15c10.06,0,10.24-.19,12,0,3.41.36,11.85,1.27,18,8C51.45,22.56,51.18,31.45,51.08,33.32Z"/></svg>
							<?php
				break;

			case 'S':
				?>
							<svg id="mask-s" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="M-.5-.5v81h56V-.5ZM47.16,60A21.77,21.77,0,0,1,23.39,72.08C13.15,70,6.48,62.13,5.92,50.72H21.26C7.19,42,2.75,32.29,7.19,21.08c4-10,14.66-15.44,25.56-13a20.57,20.57,0,0,1,16,20.77H35.2c3.93,4.24,8.79,8.21,12,13.23C50.62,47.55,49.93,54,47.16,60Z"/></svg>
							<?php
				break;

			case '?':
				?>
							<svg id="mask-pt-int" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 80"><defs><style>.cls-<?php echo esc_attr( $class_id ); ?>{fill:<?php echo esc_attr( $color ); ?>;}</style></defs><path class="cls-<?php echo esc_attr( $class_id ); ?>" d="m-.5-.5v81h56V-.5H-.5Zm40.5,72.5H13v-10h27v10Zm0-25v14H13.01c0-.34,0-8.16,0-10-.02-4.01,1.76-9.04,3-11,3.17-5.02,8.06-6.76,12-11-3.93,0-16.95-.15-21.74-.15-.36-9.91,7.74-20.85,21.74-20.85,8,0,22,4,22,21,0,5,0,12-10,18Z"/></svg>
							<?php
				break;

			default:
		}
		$svg = ob_get_clean();
		if ( true === $echo_mask ) {
			echo wp_kses(
				$svg,
				array_merge(
					wp_kses_allowed_html( 'post' ),
					array(
						'svg'   => array(
							'id'          => array(),
							'xmlns'       => array(),
							'fill'        => array(),
							'viewbox'     => array(),
							'role'        => array(),
							'aria-hidden' => array(),
							'focusable'   => array(),
							'height'      => array(),
							'width'       => array(),
						),
						'defs'  => array(),
						'path'  => array(
							'class' => array(),
							'd'     => array(),
							'fill'  => array(),
						),
						'style' => array(),
					)
				)
			);
		}
		return $svg;
	}
}

