<?php
/**
 * Template Name: Page Sites
 * WordPress template for "Base du réemploi" static page.
 *
 * @package refair
 */

get_header();
?>
<div id="materials-notifications"></div>
<main class="container-fluid">
	<header class="page-header">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</header>
	<section class="section-deposits-map">
		<div class="section-title-wrapper">
		<div id="gisements" class="anchor"></div>
			<h2 class="section-title">Je recherche sur la carte :</h2>
		</div>
		<div class='section-body'>
		<?php get_template_part( 'template-parts/materials/deposits', 'map' ); ?>
		</div>
	</section>
	<section class="section-deposits-list">
		<?php get_template_part( 'template-parts/materials/deposits', 'list' ); ?>
	</section>
	
</main>
<?php
get_footer();
?>
