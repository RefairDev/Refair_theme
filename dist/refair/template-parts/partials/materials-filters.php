<?php
/**
 * Filter for materials
 *
 * @package refair
 */

?>
<div class="title">Filtrer</div>
<form action="">
	<div class="filter-block">
		<label for="materials-family-select">Familles</label>
		<select name="materials-family" id="materials-family-select">
			<?php
			$terms = get_terms(
				array(
					'post_type' => 'product',
					'taxonomy'  => 'family',
				)
			);
			foreach ( $terms as $terms_idx => $family_term ) {
				?>
					<option value="<?php echo esc_attr( $family_term->term_id ); ?>" ><?php echo wp_kses_post( $family_term->name ); ?></option>
				<?php
			}
			?>
			</option>
		</select>
	</div>
	<div class="filter-block">
		<label for="materials-category-select">Catégorie</label>
		<select name="materials-category" id="materials-category-select">
			<?php
			$terms = get_terms(
				array(
					'post_type' => 'product',
					'taxonomy'  => 'product-category',
				)
			);
			foreach ( $terms as $terms_idx => $product_cat_term ) {
				?>
				<option value="<?php echo esc_attr( $product_cat_term->term_id ); ?>""><?php echo wp_kses_post( $product_cat_term->name ); ?></option>
				<?php
			}
			?>
			</option>
		</select>
	</div>    
	<div class="filter-block">
		<label for="materials-availabalities-start">Dates de disponibilités</label>
		<span>Du</span><input name="materials-availabality-start" id="materials-availabality-start" type="date"><span>au</span><input type="date" name="materials-availabality-end" id="materials-availabality-end">
	</div>
	<div class="filter-validation">  
		<a class="filter-validation-btn">rechercher</a>
	</div>
</form>
