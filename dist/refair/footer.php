<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package refair
 */

$custom_logo_id = get_theme_mod( 'custom_logo' );
$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
$logo_img       = $image[0];
$logo_alt       = get_bloginfo( 'name' );

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		
		<div class="bottom-insert">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/inset_fab.svg' ); ?>" alt="background image">
		</div>
		<div class="macaron entreprendre"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/Entreprendre_LaFab5_FondTransparent_rouge_rz.png' ); ?>" alt="entreprendre"></div>
		<div class="macaron habiter"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/Habiter_Programme_LaFab_2019_01_rouge_rz.png' ); ?>" alt="habiter"></div>
		<div class="site-footer-inner">

			<div class="newsletter">
				<div class="newsletter-main-part">
				<?php
				if ( is_active_sidebar( 'footer' ) ) {
					dynamic_sidebar( 'footer' );
				} else {
					?>
					<div class="newsletter-prompt">Continuez à recevoir les informations</div>
					<div class="newsletter-actions">
						<div><input type="text" class="addr-newsletter" placeholder="votre adresse mail"><button class="newsletter-valid" type="submit"><i class="icono-arrow-left"></i></button></div>
						<input type="checkbox" id="newsletter-consent-cb" class="consent-cb"><label for="newsletter-consent-cb" class="consent-cb-label"><?php echo esc_html( get_theme_mod( 'mentioncocher' ) ); ?></label>
					</div>
					<?php
				}
				?>
				</div>
				<div class="newsletter-short-mentions"><?php echo  get_theme_mod( 'mentioncourtenewsletter' ) ; ?></div>
			</div>
			<div class="footer-links">
				<div class="left-part">
					<div class="part-title"> À PROPOS</div>
					<div class="part-menu">
						<?php
						wp_nav_menu(
							array(
								'theme_location'  => 'menu-footer-left',
								'menu_id'         => 'footer-left-menu',
								'container_class' => '',
								'menu_class'      => 'footer-left-menu nav',
								'container'       => 'div',
							)
						);
						?>
					</div>
				</div>
				<div class="right-part">
					<div class="part-title">PLUS D'INFORMATIONS</div>
					<div class="part-menu">
					<?php
						wp_nav_menu(
							array(
								'theme_location'  => 'menu-footer-right',
								'menu_id'         => 'footer-right-menu',
								'container_class' => '',
								'menu_class'      => 'footer-right-menu nav',
								'container'       => 'div',
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->


<!-- flying-box -->
<?php
wp_footer();
?>

</body>
</html>
