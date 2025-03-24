<?php
/**
 * Template part of the home page displaying title.
 *
 * @package refair
 */

$background_color = 'mrn_sl_blue_2';
$carousel_id      = 'numbers_slider';
?>
<section class="section-title">
	<div class="img-bg"><?php echo wp_kses( get_responsive_image( $section['background_image']['id'], array( 1920, 900 ), array( 'alt' => get_the_title() ) ), wp_kses_allowed_html( 'post' ) ); ?></div>
	<div class="bg-overlay">
		<div class="logo"><img src="<?php echo esc_url( $section['logo']['url'] ); ?>" alt="<?php the_title(); ?>"></div>
		<div class="acronym"><?php echo wp_kses_post( $section['acronym'] ); ?></div>
		<div class="sub-title"><?php echo wp_kses_post( $section['slogan'] ); ?></div>
	</div>
</section>    
<script>
	if (window.innerWidth > 991){
		window.addEventListener('load',function(){
			updateLogo();
			document.addEventListener("scroll", updateLogo);
		})
	}


	function updateLogo(){
		let logoHeaderHeight = (document.querySelector(".site-title").offsetHeight);
		let logoBigHeight = (document.querySelector(".site-header-inner").offsetWidth)*.1;
		let topThreshold = parseFloat( document.querySelector(".bg-overlay").offsetTop ) + document.querySelector(".bg-overlay .logo").offsetHeight;
		let halfHeight = document.querySelector('.section-title').offsetHeight/2;
			if (window.pageYOffset > topThreshold){
				document.querySelector(".bg-overlay .logo img").style.display = "none";
				document.querySelector(".site-branding img").style.opacity = 1;
			}else{
				document.querySelector(".site-branding img").style.opacity = 0;
				document.querySelector(".bg-overlay .logo img").style.display = "block";
				document.querySelector(".bg-overlay .logo img").style.height = (((logoBigHeight-logoHeaderHeight)*((halfHeight-window.pageYOffset) / halfHeight))+logoHeaderHeight).toString()+"px";
			}

			if (window.pageYOffset > topThreshold){
				document.querySelector(".bg-overlay .acronym").style.display="none";                
				document.querySelector(".bg-overlay .sub-title").style.display="none";
			}else{
				document.querySelector(".bg-overlay .acronym").style.display="block";
				document.querySelector(".bg-overlay .sub-title").style.display="block";
				document.querySelector(".bg-overlay .acronym").style.opacity = (halfHeight-window.pageYOffset) / (halfHeight);
				document.querySelector(".bg-overlay .sub-title").style.opacity = (halfHeight-window.pageYOffset) / (halfHeight);
			}
	}
</script>
