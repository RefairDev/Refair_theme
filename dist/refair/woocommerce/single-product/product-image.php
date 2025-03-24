<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.0.0
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$gallery_length = count($attachment_ids);
$slides=array();
$anchors=array();
$bullets=array();
$prev_next= array();
foreach( $attachment_ids as $idx => $attachment_id ) 
	{
		// Display the image URL
		$Original_image_url = wp_get_attachment_url( $attachment_id );

		// Display Image instead of URL
		$active="";
		if ( $idx == 0 ){ $active=" active"; }
		$idx == count($attachment_ids)-1 ? $idx_next=1 : $idx_next = $idx + 2;
		$idx == 0 ? $idx_prev = count($attachment_ids) : $idx_prev = $idx ;
		$slides[]="<div class='slide".$active."' id='slide-".strval($idx+1)."' data-next='slide-".strval($idx_next)."' data-prev='slide-".strval($idx_prev)."' >".wp_get_attachment_image($attachment_id, 'container_half_width_sqr')."</div>";
		$bullets[]="<a id='bullet-slide-".strval($idx+1)."' class='slider-bullet".$active."' data-target='slide-".strval($idx+1)."'></a>";

		$prev_next[]= "<div><a class='slider-arrow left'><i class='icono-arrow-right'></i></a><a class='slider-arrow right'><i class='icono-arrow-left'></i></a></div>";
	}
?>
<div class="CSSgal">


		<div class="slider">
			<?php echo implode("",$slides);?>
		</div>
		<?php if (count($prev_next)>1){ ?>
		<div class="prevNext">
			<?php echo implode("",$prev_next);?>
		</div>
		<?php } ?>
		<div class="bullets">
			<?php echo implode("",$bullets);?>
		</div>


</div>
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
