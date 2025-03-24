<?php
/**
 *
 * Template Name: Search form
 * The template for displaying search form
 *
 * @package refair
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( get_home_url() ); ?>">
	<label>
		<span class="screen-reader-text">Rechercher&nbsp;:</span>
		<input type="search" class="search-field" placeholder="…" value="" name="s">
	</label>
	<button type="submit" class="search-submit"><i class="icono-arrow-left"></i></button>
</form>