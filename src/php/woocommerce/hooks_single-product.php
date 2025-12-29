<?php

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

/**
 * Display back button.
 *
 * @return void
 */

function refair_template_back_button() {
	$referer = '';
	if ( array_key_exists( 'HTTP_REFERER', $_SERVER ) && '' !== $_SERVER['HTTP_REFERER'] ) {
		$referer = esc_url( $_SERVER['HTTP_REFERER'] );
	} else {
		$referer = get_bloginfo( 'url' );
	}
	?>
	<div class="referer-section-wrapper bg-green-400">
		<div class="referer-section framed">
			<a class="referer-link" href="<?php echo esc_url( $referer ); ?>" ><i class="icono-arrow-right"></i> <?php esc_html_e( 'Back', 'refair-theme' ); ?></a>
		</div>
	</div>
	<?php
}

add_action( 'woocommerce_before_single_product', 'refair_template_back_button', 9 );

/**
 * Display Title with sku if any.
 */

function refair_template_single_title() {
	global $product;
	$sku = $product->get_sku();
	?>
	<h1 class="product_title entry-title"><?php echo esc_html( $product->get_name() ); ?><?php if ( '' !== $sku && false !== $sku ) { ?><small><?php echo ' (' . esc_html( $sku ) . ')'; ?></small><?php } ?></h1>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'refair_template_single_title', 5 );


/**
 * Display Categories
 *
 * @return void
 */
function refair_template_single_cats() {
	global $product;
	$product_cat  = '';
	$product_cats = get_the_terms( $product->get_id(), 'product_cat' );
	if ( false !== $product_cats && ! is_wp_error( $product_cats ) && isset( $product_cats[1] ) && is_a( $product_cats[1], 'WP_Term', true ) && property_exists( $product_cats[1], 'name' ) ) {
		$product_cat = $product_cats[1]->name;
	}
	?>
	<div class="product-cat-wrapper">
		<div class="product-cat framed"><?php echo esc_html( $product_cat ); ?></div>
	</div>
	<?php
}

add_action( 'woocommerce_before_single_product_summary', 'refair_template_single_cats', 4 );

/**
 * Display product description.
 *
 * @return void
 */
function refair_template_single_product_description() {
	$content = get_the_content();
	if ( '' === $content ) {
		$content = __( 'No description', 'refair-theme' );
	}
	?>
	<div><?php echo wp_kses( $content, wp_kses_allowed_html( 'post' ) ); ?></div>
	<?php
}
add_action( 'woocommerce_single_product_sub-summary_left_1', 'refair_template_single_product_description', 10 );

/**
 * Display product details.
 *
 * @return void
 */
function refair_template_single_product_left_2() {
	global $product;
	$rq = get_post_meta( $product->get_id(), 'remarques', true );
	if ( false === $rq ) {
		$rq = 'Aucune';
	}
	?>
	<div class="left-2-wrapper"><h2 class="left-2-heading"><?php esc_html_e( 'Comments', 'refair-theme' ); ?></h2><div class="left-2-body"><?php echo wp_kses_post( wpautop( $rq ) ); ?></div></div>
	<?php
}
add_action( 'woocommerce_single_product_sub-summary_left_2', 'refair_template_single_product_left_2', 10 );

/**
 * Display product others details.
 *
 * @return void
 */
function refair_template_single_product_summary_left_3() {
	global $product;
	$re            = '/, [0-9]{5} (.*), /m';
	$matches       = array();
	$location_city = '-';
	$av_str        = '-';
	$deposit        = '-';
	$deposit_ref   = get_post_meta( $product->get_id(), 'deposit', true );
	$posts         = get_posts(
		array(
			'post_type'  => 'deposit',
			'meta_key'   => 'reference',
			'meta_value' => $deposit_ref,
		)
	);
	if ( isset( $posts[0] ) && property_exists( $posts[0], 'ID' ) ) {

		$loc = get_post_meta( $posts[0]->ID, 'location', true );
		preg_match_all( $re, $loc['location'], $matches, PREG_SET_ORDER, 0 );
		$location_city = $matches[0][1];

		$deposit = $posts[0]->post_title . ' (' . $deposit_ref . ')';

		$av = get_post_meta( $posts[0]->ID, 'dismantle_date', true );
		if ( false !== $av ) {
			$re     = '/^([0-9]{4})-([0-9]{2})-[0-9]{2}$/';
			$av_str = $av;
			$m      = null;
			preg_match( $re, $av, $m, PREG_OFFSET_CAPTURE, 0 );
			if ( count( $m ) > 0 ) {
				$q_nb   = ceil( intVal( $m[2][0] ) / 3 );
				$av_str = __( 'Quarter', 'refair-theme' ) . $q_nb . ' ' . $m[1][0];
			}
		}
	}

	?>
	<div  class="left-3-wrapper">
		<div class="deposit">
			<span><?php echo esc_html( $deposit ); ?></span>			
		</div>
		<div class="location">
			<span><?php echo esc_html( $location_city ); ?></span>
		</div>
		<div class="availability">
			<span><?php echo esc_html( $av_str ); ?></span>
		</div>
	</div>
	<?php
}

add_action( 'woocommerce_single_product_sub-summary_left_3', 'refair_template_single_product_summary_left_3', 10 );

function refair_template_single_product_summary_right_1() {
	global $product;
	$str_dim = '-';
	$str_mat = '-';
	if ( 'variable' === $product->get_type() ) {
		$str_dim = __( 'VARIOUS<br>see variations', 'refair-theme' );
		$str_mat = __( 'VARIOUS<br>see variations', 'refair-theme' );
	} else {
		$p_width  = ! empty( $product->get_width() ) ? $product->get_width() . 'cm' : '-';
		$p_length = ! empty( $product->get_length() ) ? $product->get_length() . 'cm' : '-';
		$p_height = ! empty( $product->get_height() ) ? $product->get_height() . 'cm' : '-';

		$str_dim     = __( 'l', 'refair-theme' ) . " {$p_width} / " . __( 'L', 'refair-theme' ) . " {$p_length} / " . __( 'h', 'refair-theme' ) . " {$p_height}";
		$str_mat_raw = get_post_meta( $product->get_id(), 'material', true );
		if ( '' !== $str_mat_raw && false !== $str_mat_raw ) {
			$str_mat = $str_mat_raw;
		}
	}

	?>
	<div class="right-1-wrapper">
		<h2><?php _e( 'Materials', 'refair-theme' ); ?></h2><div><?php echo $str_mat; ?></div>
		<h2><?php _e( 'Dimensions', 'refair-theme' ); ?></h2><div><?php echo $str_dim; ?></div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_sub-summary_right_1', 'refair_template_single_product_summary_right_1', 10 );

function refair_template_single_product_summary_right_2() {
	global $product;
	if ( 'variable' === $product->get_type() ) {
		$conditions = array();
		foreach ( $product->get_available_variations() as $variation_array ) {
			$var_cond = get_post_meta( $variation_array['variation_id'], 'condition', true );
			if ( ! in_array( $var_cond, $conditions, true ) ) {
				$conditions[] = $var_cond;}
		}
		$str_conditions = implode( '<br>', $conditions );
	} else {
		$str_conditions     = '-';
		$str_conditions_raw = get_post_meta( $product->get_id(), 'condition', true );
		if ( false !== $str_conditions_raw && '' !== $str_conditions_raw ) {
			$str_conditions = $str_conditions_raw;
		}
	}
	?>
	<div class="right-2-wrapper"><span><?php echo esc_html( $str_conditions ); ?></span></div>
													<?php
}
add_action( 'woocommerce_single_product_sub-summary_right_2', 'refair_template_single_product_summary_right_2', 10 );


function refair_template_single_product_summary_right_3() {
	global $product;
	$qty = 0;
	if ( 'variable' === $product->get_type() ) {
		foreach ( $product->get_available_variations() as $variation_array ) {
			$qty += intval( $variation_array['max_qty'] );
		}
	} else {
		$qty = $product->get_stock_quantity();
	}

	$unit_meta = get_post_meta( $product->get_id(), 'unit', true );
	$unit      = '';

	if ( $unit_meta === false || $unit_meta === 'u' ) {
		$unit = 'unité';
		if ( $qty > 1 ) {
			$unit .= 's';}
	} else {
		$unit = $unit_meta;
	}

	?>
	<div class="right-3-wrapper"><p><span class="qty-count"><?php echo esc_html( $qty ); ?></span><br><span><?php echo esc_html( $unit ); ?></span></p></div>
																		<?php
}
add_action( 'woocommerce_single_product_sub-summary_right_3', 'refair_template_single_product_summary_right_3', 10 );


function refair_template_single_product_summary_bottom() {
	global $product;
	?>
	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php
	do_action( 'woocommerce_before_add_to_cart_quantity' );

	woocommerce_quantity_input(
		array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		)
	);

	do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>

	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>
	<?php
}
add_action( 'woocommerce_single_product_sub-summary_bottom', 'refair_template_single_product_summary_bottom', 10 );


function refair_add_variations_table() {
	global $product;
	if ( 'variable' === $product->get_type() ) {
		?>
		<section class="variations-section">
		<h2 class="variations-title"><?php _e( 'Variations', 'refair-theme' ); ?></h2>
		<div class="variations-table">
		<div class="variation-row header">
		<div class="variation-elts">
		<div class="variation-elt variation-designation"><?php esc_html_e( 'Designation', 'refair-theme' ); ?></div>
		<div class="variation-elt variation-material center"><?php esc_html_e( 'Type of material', 'refair-theme' ); ?></div>
		<div class="variation-elt variation-dimensions"><?php esc_html_e( 'Dimensions', 'refair-theme' ); ?></div>
		<div class="variation-elt variation-stock center"><?php esc_html_e( 'Stock', 'refair-theme' ); ?></div>
		<div class="variation-elt variation-condition center"><?php esc_html_e( 'Condition', 'refair-theme' ); ?></div>
		</div>
		</div>
		<?php
		foreach ( $product->get_available_variations() as $variation_array ) {
			$dimensions = '';
			if ( ! empty( $variation_array['dimensions']['length'] ) ) {
				$dimensions .= __( 'L', 'refair-theme' ) . "{$variation_array['dimensions']['length']}cm";}
			if ( ! empty( $variation_array['dimensions']['width'] ) ) {
				if ( '' !== $dimensions ) {
					$dimensions .= ' ';
				} $dimensions .= __( 'l', 'refair-theme' ) . "{$variation_array['dimensions']['width']}cm";}
			if ( ! empty( $variation_array['dimensions']['height'] ) ) {
				if ( '' !== $dimensions ) {
					$dimensions .= ' ';
				} $dimensions .= __( 'h', 'refair-theme' ) . "{$variation_array['dimensions']['height']}cm";}
			$type_of_material = get_post_meta( $variation_array['variation_id'], 'material', true );
			if ( false === $type_of_material || empty( $type_of_material ) ) {
				$type_of_material = '-';
			}
			?>
			<div class="variation-row">
			<div class="variation-elts">
			<div class="variation-elt variation-designation"><label><?php esc_html_e( 'Designation', 'refair-theme' ); ?></label><?php echo get_post_meta( $variation_array['variation_id'], 'designation', true ); ?></div>
			<div class="variation-elt variation-material"><label><?php esc_html_e( 'Type of material', 'refair-theme' ); ?></label><?php echo $type_of_material; ?></div>
			<div class="variation-elt variation-dimensions"><label><?php esc_html_e( 'Dimensions', 'refair-theme' ); ?></label><?php echo $dimensions; ?></div>
			<div class="variation-elt variation-stock center"><label><?php esc_html_e( 'Stock', 'refair-theme' ); ?></label><?php echo $variation_array['max_qty']; ?></div>
			<div class="variation-elt variation-condition center"><label><?php esc_html_e( 'Condition', 'refair-theme' ); ?></label><?php echo get_post_meta( $variation_array['variation_id'], 'condition', true ); ?></div>
			</div>
			<div class="variation-elts-actions">
			<form class="add_to_cart">

			<input type=hidden name="product_id" value="<?php echo $product->get_id(); ?>" >
			<input type=hidden name="variation_id" value="<?php echo $variation_array['variation_id']; ?>" >
			<input name="quantity" type="number" class="variation-elt-qty" placeholder=0 step=1 min=0 max=<?php echo $variation_array['max_qty']; ?>>
			<button type="submit" class="add-to-cart"  value=""><span class="plus">+</span><span class="text"><?php _e( 'Add to my list', 'refair-theme' ); ?></span></button>
			</form>
			</div>
			</div>
			<?php
		}
		?>
		</div>
	</section>
		<?php
	}
}

add_action( 'woocommerce_single_product_summary_right', 'woocommerce_show_product_images' );


add_action( 'woocommerce_after_single_product_summary', 'refair_add_variations_table' );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


function refair_add_padding_block() {
	?>
	<div class="padding-block">
	</div>
	<?php
}

add_action( 'woocommerce_after_single_product', 'refair_add_padding_block' );



// To change add to cart text on single product page.
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' );
function woocommerce_custom_single_add_to_cart_text() {
	return __( 'Add to my list', 'refair-theme' );
}
// To change add to cart text on product archives(Collection) page.
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );
function woocommerce_custom_product_add_to_cart_text() {
	return __( 'Add to my list', 'refair-theme' );
}
