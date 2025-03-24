<?php
/**
 * Template name: Page Fournisseurs
 * WordPress template for providers static page.
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
	<section class="section-providers-list">
		<?php get_template_part( 'template-parts/materials/providers', 'list' ); ?>
	</section>
	
</main>

<?php
get_footer();
?>
