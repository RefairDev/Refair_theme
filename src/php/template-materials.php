<?php
/**
 * Template Name: Page Matériaux
 * WordPress template for materials static page.
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
	<section class="section-materials">
		<?php get_template_part( 'template-parts/materials/materials' ); ?>
	</section>
	
</main>
<?php
get_footer();
?>