<?php

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );


function refair_customize_account_nav( $menu_links ) {
	$menu_links['orders'] = __( 'My lists', 'refair-theme' );
	unset( $menu_links['downloads'] );
	return $menu_links;
}
add_filter( 'woocommerce_account_menu_items', 'refair_customize_account_nav' );

function refair_customize_orders_columns( $columns ) {
	$columns['order-number'] = esc_html__( 'List', 'refair-theme' );
	unset( $columns['order-total'] );
	return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'refair_customize_orders_columns' );

function refair_customize_account_order_page_title( $title ) {
	return str_replace( __( 'Order', 'woocommerce' ), __( 'List', 'refair-theme' ), $title );
}
add_filter( 'woocommerce_endpoint_orders_title', 'refair_customize_account_order_page_title' );
add_filter( 'woocommerce_endpoint_view-order_title', 'refair_customize_account_order_page_title' );

add_action( 'wp_footer', 'single_product_ajax_add_to_cart_js_script' );

function single_product_ajax_add_to_cart_js_script() {
	?>
	<script>
	(function() {
		document.querySelectorAll("form.add_to_cart").forEach( elt => {
			elt.addEventListener(
				'submit',
				function(e) {
					e.preventDefault();

					var form = elt,
					mainId   = "",
					fData    = {};

					//form.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

					if ( mainId === '' ) {
						mainId = form.querySelector('input[name="product_id"]').value;
					}

					if ( typeof wc_add_to_cart_params === 'undefined' ){
						return false;
					}

					form.childNodes.forEach(element => {
						if (element.nodeType==1 && element.tagName.toLowerCase() =="input" && element.name !=""){
							fData[element.name] = element.value;
						}
					});

					const data = new FormData();
					data.append( 'product_id', mainId);
					data.append('form_data' , JSON.stringify(fData));

					fetch(
						wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'custom_add_to_cart' ), {
							method: "POST",
							credentials: 'same-origin',
							body: data
						}
						).then((response) =>
						response.text()
						)
						.then((data) => {
							document.body.dispatchEvent(new Event("wc_fragment_refresh"));
							let wcMsg = document.querySelectorAll('.woocommerce-error,.woocommerce-message');
							if (wcMsg.length >0){
								wcMsg.forEach(function(elt){elt.detach();})
							}
							form.querySelector('input[name="quantity"]').value= 0;
							let parentVariation = document.querySelector('.variations-section');
							let anchorVariation = document.querySelector('.variations-table');
							if (anchorVariation != null && parentVariation !=null){
								let div = document.createElement('div');
								div.innerHTML = data.trim();
								parentVariation.insertBefore(div,anchorVariation);
							}

							//form.unblock();
							// console.log(response);
						})
						.catch(function(error) {

						});
					});
				});
			})();
			</script>
			<?php
}

		add_action( 'wc_ajax_custom_add_to_cart', 'custom_add_to_cart_handler' );
		add_action( 'wc_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart_handler' );

/**
 * Custom handle to add to cart request.
 *
 * @return void
 */
function custom_add_to_cart_handler() {
	if ( isset( $_POST['product_id'] ) && isset( $_POST['form_data'] ) ) {
		$product_id    = $_POST['product_id'];
		$variation     = $cart_item_data = $custom_data = array(); // Initializing
		$variation_id  = 0; // Initializing.
		$form_data_raw = stripslashes( $_POST['form_data'] );
		$form_data     = json_decode( $form_data_raw, true );

		foreach ( $form_data as $name => $value ) {
			if ( strpos( $name, 'attributes_' ) !== false ) {
				$variation[ $name ] = $value;
			} elseif ( $name === 'quantity' ) {
				$quantity = $value;
			} elseif ( $name === 'variation_id' ) {
				$variation_id = $value;
			} elseif ( $name !== 'add_to_cart' ) {
				$custom_data[ $value ] = esc_attr( $value );
			}
		}

		$product = wc_get_product( $variation_id ? $variation_id : $product_id );

		// Allow product custom fields to be added as custom cart item data from $custom_data additional array variable
		$cart_item_data = (array) apply_filters( 'woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id, $quantity, $custom_data );

		// Add to cart
		$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );

		if ( $cart_item_key ) {
			// Add to cart successful notice
			wc_add_notice( sprintf( '<a href="%s" class="button wc-forward">%s</a> %d &times; "%s" %s', wc_get_cart_url(), __( 'See my list', 'refair-theme' ), $quantity, $product->get_name(), __( 'have been added to your list', 'refair-theme' ) ) );
		}

		wc_print_notices(); // Return printed notices to jQuery response.
		wp_die();
	}
}


// function refair_return_to_deposit_redirect() {
// return get_the_permalink ( get_theme_mod( 'pagedegisement' ) );
// }
// add_filter( 'woocommerce_return_to_shop_redirect', 'refair_return_to_deposit_redirect' );


function refair_return_to_deposit_redirect_text() {
	return 'Return to deposits';
}
add_filter( 'woocommerce_return_to_shop_text', 'refair_return_to_deposit_redirect_text' );

function refair_empty_cart_message() {
	return 'Your list is currently empty.';
}
add_filter( 'wc_empty_cart_message', 'refair_empty_cart_message' );


add_filter( 'gettext', 'customizing_variable_product_message', 97, 3 );
function customizing_variable_product_message( $translated_text, $untranslated_text, $domain ) {
	$returned = str_replace( 'un panier', 'une liste', $translated_text );
	$returned = str_replace( 'au panier', 'à la liste', $returned );
	$returned = str_replace( 'du panier', 'de la liste', $returned );
	$returned = str_replace( 'le panier', 'la liste', $returned );
	$returned = str_replace( 'panier', 'liste', $returned );
	$returned = str_replace( 'Panier', 'Liste', $returned );

	$returned = str_replace( 'commander', 'enregistrer', $returned );
	$returned = str_replace( 'Commander', 'Enregistrer', $returned );
	$returned = str_replace( 'commandes', "manifestations d'intérêts", $returned );
	$returned = str_replace( 'Commandes', "Manifestations d'intérêts", $returned );
	$returned = str_replace( 'commande', "manifestation d'intérêt", $returned );
	$returned = str_replace( 'Commande', "Manifestation d'intérêt", $returned );

	if ( $returned != $translated_text ) {
		$yes = true;
	}
	return $returned;
}



add_action( 'woocommerce_register_form', 'refair_add_registration_terms_of_service', 11 );

function refair_add_registration_terms_of_service() {

	$tos_url = get_the_permalink( get_theme_mod( 'conditionsgeneralesdutilisation' ) );
	woocommerce_form_field(
		'terms_of_service_reg',
		array(
			'type'        => 'checkbox',
			'class'       => array( 'form-row terms-of-service' ),
			'label_class' => array( 'woocommerce-form__label woocommerce-form__label-for-checkbox checkbox' ),
			'input_class' => array( 'woocommerce-form__input woocommerce-form__input-checkbox input-checkbox' ),
			'required'    => true,
			'label'       => sprintf( __( "I've read and accept the <a href='%s'>Terms of service</a>", 'refair-theme' ), $tos_url ),
		)
	);
}

// Show error if user does not tick

add_filter( 'woocommerce_registration_errors', 'refair_validate_privacy_registration', 10, 3 );

function refair_validate_privacy_registration( $errors, $username, $email ) {
	if ( ! is_checkout() ) {
		if ( ! (int) isset( $_POST['terms_of_service_reg'] ) ) {
			$errors->add( 'terms_of_service_reg_error', __( 'Terms of service consent is required!', 'refair-theme' ) );
		}
	}
	return $errors;
}


function add_order_sheet_pickup_direction( $order, $sent_to_admin, $plain_text, $email ) {
	?>
	<p>
	<?php
	printf( esc_html__( 'You can download your manifestation of interest ( list summary + material sheet ) from your account category "my lists"', 'refair-theme' ) );
	?>
	</p>
	<?php
}

// add_action( 'woocommerce_email_order_details', 'add_order_sheet_pickup_direction', 9, 4 );
