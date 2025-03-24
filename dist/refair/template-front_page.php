<?php
/**
 * Template Name: Page d'accueil
 *
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package refair
 */

$post_metas = get_post_meta( get_the_id() );
get_header();
?>
<main class="page-container">
	<?php
	set_query_var( 'section', maybe_unserialize( $post_metas['section_title'][0] ) );
	get_template_part( 'template-parts/home-sections/home-section', 'title' );
	set_query_var( 'section', maybe_unserialize( $post_metas['section_search'][0] ) );
	get_template_part( 'template-parts/home-sections/home-section', 'search' );
	set_query_var( 'section', maybe_unserialize( $post_metas['section_news'][0] ) );
	get_template_part( 'template-parts/home-sections/home-section', 'news' );
	set_query_var( 'section', maybe_unserialize( $post_metas['section_approach'][0] ) );
	get_template_part( 'template-parts/home-sections/home-section', 'approach' );
	get_template_part( 'template-parts/partials/pre-footer-padding' );
	?>
</main>
<?php
get_footer();
