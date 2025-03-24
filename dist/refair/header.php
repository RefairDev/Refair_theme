<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package refair
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<script>
		window.addEventListener('load', function(){
			var _paq = window._paq = window._paq || [];
			// require user cookie consent before storing and using any cookies
			_paq.push(['requireCookieConsent']);
		})
	</script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'refair-theme' ); ?></a>

	<div id="masthead" class="site-header container-fluid no-padding">
		<div class="site-header-wrapper">
			<div class="site-header-inner">
				<div class="main-menu">
					<div class="mobile-mask"></div>
					<div class="user-actions">
						<div class="mobile-access">
							<a class="mobile-deposit-material" >
								<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/materials.svg' ); ?>">
								<span>Les matériaux</span>
							</a>
						</div>
						<div class="deposit-access">
							<a href='<?php the_permalink( get_theme_mod( 'pagedegisement', get_the_ID() ) ); ?>'>
								<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/deposit_.svg' ); ?>">
								<span>Les<br>sites</span>
							</a>
						</div>
						<div class="materials-access">
							<a href='<?php the_permalink( get_theme_mod( 'pagedesmatriaux', get_the_ID() ) ); ?>'>
								<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/deposit.svg' ); ?>">
								<span>Les<br>matériaux</span>
							</a>
						</div>
						<div class="providers-access">
							<a href='<?php the_permalink( get_theme_mod( 'pagedesfournisseurs', get_the_ID() ) ); ?>'>
								<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/providers.svg' ); ?>">
								<span>Les<br>fournisseurs</span>
							</a>
						</div>						
					</div>
					<div class="user-actions desktop">
						<div class="user-action read-manual">
							<a class="user-action-link manual-close">
								<svg id="manual" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 47.5 47.5"><defs></defs>
									<path class="user-action-svg" d="M23.8,0h0C10.6,0,0,10.6,0,23.7s10.6,23.7,23.7,23.7,23.7-10.6,23.7-23.7C47.5,10.6,36.9,0,23.8,0ZM36.2,34.4c-.1.8-.6,1.5-1.3,1.9,0,0,0,0,0,.1,0,.2,0,.2.1.3,0,.2,0,.4,0,1,0,.8-6.9,1.8-13,2.1-1.8,0-3.5,0-5.3,0-1.2,0-3,.2-4-.6s-.9-2.6-1.1-3.8c-.2-1.5-.3-2.9-.5-4.4-.6-6.4-1-17.6-.4-19.2.3-.7,1.2-1.9,1.8-2.6.1-.2.3-.4.5-.5.2-.1.4-.2.6-.2,4-.4,7.9-.7,11.9-.7s4,0,6,.1c1.5,0,2.9.2,3.3,1.8.3,1.5,0,3.2,0,4.7,0,1.7.1,3.3.4,5,.5,3.3,1.3,6.5,1.4,9.8,0,1.7,0,3.3-.4,5Z"/>
									<path class="user-action-svg" d="M15.7,32.6c0,.6.1,1.2.2,1.8,0,.3.1.5.1.8.8,0,1.6,0,2.3.1h2.2c1.4,0,2.7,0,4.1,0,1.5,0,3-.2,4.6-.4.8,0,1.5-.2,2.3-.3.7,0,1.4-.1,2-.3.7-.6.6-2.9.6-3.8,0-1.4-.1-2.8-.3-4.2-.4-2.7-1-5.4-1.3-8.2-.1-1.6-.2-3.1-.2-4.7,0-.6,0-1.3,0-1.9,0-.2,0-.4,0-.6,0,0-.4,0-.6-.1-5.6-.2-11.3-.1-16.9.4-.3,5.7-.1,11.4.4,17.1.1,1.5.3,2.9.5,4.4ZM25.2,30.3c-.2.1-.3.2-.5.2s-.3,0-.4,0c-.4-.1-.7-.4-.8-.7-.2-.4,0-.9.3-1.2.2-.3.6-.5,1.1-.5.4,0,.7.5.8,1,.1.6,0,1.1-.4,1.3ZM20.5,14.8c1.6-1.9,4.4-2.5,6-1.3,1.6,1.2,3,3.6,2.3,5.9-.8,2.3-1.8,2.8-2.7,3.1-.3.1-.4.1-.6.7-.2.7,0,1.4,0,1.9h0c.1.8-.3,1.4-1,1.6,0,0-.2,0-.2,0-.6,0-1.1-.4-1.2-1h0c-.1-.8-.3-1.9,0-3.2.5-1.7,1.3-2.1,2.2-2.4.4-.1.7-.2,1.1-1.5.4-1.1-.4-2.4-1.4-3.1-.5-.3-1.8,0-2.6.9-.3.3-.7,1.7-1.1,2.1-.4.4-1.6.6-2,.2-.4-.4,0-2.5,1.2-3.9Z"/>
								</svg>								
								<span>Mode d'emploi</span>
							</a>
						</div>
					</div>		
					<div class="site-branding">
						<?php

							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
							$logo_img       = $image[0];
						if ( is_front_page() && is_home() ) :
							?>
								<h1 class="site-title">
							<?php
							else :
								?>
								<p class="site-title">
								<?php
							endif;
							?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<img src="<?php echo esc_url( $logo_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="brand">
								</a>
							<?php
							if ( is_front_page() && is_home() ) :
								?>
								</h1>
								<?php
							else :
								?>
								</p>
								<?php
							endif;
							?>
									
					</div>
					<div class="user-actions left desktop">
						<div class="user-action read-more">
							<a href="javascript:{}" class="user-action-link">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.59 47.59"><defs></defs><path class="user-action-svg" d="M23.79,0a23.8,23.8,0,1,0,23.8,23.79A23.79,23.79,0,0,0,23.79,0ZM38.44,27.37h-11V38.61H20.1V27.37h-11V20.22h11V9h7.38V20.22h11Z"/></svg>
								<span>en savoir plus</span>
							</a>
						</div>
						<div class="user-action user-account">
							<?php
							$btn_label  = 'me connecter';
							$link_title = __( 'Login / Register', 'woothemes' );
							if ( is_user_logged_in() ) {
								$btn_label  = 'mon compte';
								$link_title = __( 'My Account', 'woothemes' );}
							?>
							<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" title="<?php echo esc_attr( $link_title ); ?>" class="user-action-link">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48.67 48.67"><defs></defs><g><path class="user-action-svg" d="M24.33,0A24.34,24.34,0,1,0,48.67,24.33,24.33,24.33,0,0,0,24.33,0ZM40.11,31.62c-.52,4-4.74,4.77-8.06,5.1A67.92,67.92,0,0,1,20.12,37a23.16,23.16,0,0,1-10.63-3.2,1.31,1.31,0,0,1-.44-.47,1.08,1.08,0,0,1-.43-.67c-1.09-4.48,1.81-9.82,6.55-10.42,1.33-.17,2.7,0,4-.08a.78.78,0,0,0,.23-.07c0-.12-.06-.35-.08-.42a14.19,14.19,0,0,1-.69-2.73c-.16-3.25,1.92-7.37,5.52-7.55,5.45-.27,6.49,6.12,5.34,10.57q1.2-.15,2.4-.27a6.25,6.25,0,0,1,5.26,1.43A10.08,10.08,0,0,1,40.11,31.62Z"/></g></svg>
								<span><?php echo esc_html( $btn_label ); ?></span>
							</a>
						</div>
						<div class="user-action user-list">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="user-action-link">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.46 47.46"><defs></defs><path class="user-action-svg" d="M33.72,26.17c-.41-2.74-1-5.44-1.27-8.2a46.12,46.12,0,0,1-.15-4.7c0-.63,0-1.26.06-1.88a4.33,4.33,0,0,0,0-.64c-.09,0-.42-.1-.62-.11a126.92,126.92,0,0,0-16.89.41,130.45,130.45,0,0,0,.35,17.12c.13,1.47.27,2.94.45,4.4.07.61.15,1.21.23,1.81a5.09,5.09,0,0,1,.12.79,9.72,9.72,0,0,1,2.35.14c.72,0,1.44,0,2.17,0,1.36,0,2.73,0,4.09-.09,1.53-.07,3.05-.19,4.57-.36.77-.09,1.53-.18,2.3-.3a6.58,6.58,0,0,0,2-.33c.74-.59.57-2.9.56-3.84A29.08,29.08,0,0,0,33.72,26.17ZM18.21,22.42a1.26,1.26,0,0,1,1.25-1.25c2.62-.1,5.25-.26,7.87-.49a1.26,1.26,0,0,1,1.25,1.25,1.28,1.28,0,0,1-1.25,1.25c-2.62.23-5.25.39-7.87.49A1.26,1.26,0,0,1,18.21,22.42Zm9.66,6.45a28.92,28.92,0,0,1-7.88.47,1.29,1.29,0,0,1-1.25-1.25A1.26,1.26,0,0,1,20,26.84a26.64,26.64,0,0,0,7.22-.38,1.29,1.29,0,0,1,1.54.88A1.25,1.25,0,0,1,27.87,28.87ZM28,17.38H18.88a1.25,1.25,0,0,1,0-2.5H28A1.25,1.25,0,0,1,28,17.38Z"/><path class="user-action-svg" d="M23.73,0A23.73,23.73,0,1,0,47.46,23.73,23.72,23.72,0,0,0,23.73,0ZM36.22,34.43a2.89,2.89,0,0,1-2.37,2.33,55,55,0,0,1-10.16,1.05c-1.77.07-3.53.07-5.3,0-1.19,0-3,.19-4-.6s-.92-2.58-1.08-3.77c-.18-1.46-.34-2.92-.47-4.38A130.38,130.38,0,0,1,12.43,9.9a1.22,1.22,0,0,1,.66-1.05,1.33,1.33,0,0,1,.59-.2C17.63,8.26,21.6,8,25.57,8q3,0,6,.11c1.46.07,2.91.16,3.29,1.85.32,1.46,0,3.21,0,4.7a38.57,38.57,0,0,0,.36,5c.48,3.26,1.26,6.5,1.42,9.81A17.51,17.51,0,0,1,36.22,34.43Z"/></svg>
								<span>ma liste</span>
							</a>
						</div>
					</div>
					<div class="user-actions collapsed mobile-menu">
						<div class="user-action read-more">
							<a href="javascript:{}" class="user-action-link">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.59 47.59"><defs></defs><path class="user-action-svg" d="M23.79,0a23.8,23.8,0,1,0,23.8,23.79A23.79,23.79,0,0,0,23.79,0ZM38.44,27.37h-11V38.61H20.1V27.37h-11V20.22h11V9h7.38V20.22h11Z"/></svg>
								<span>en savoir plus</span>
							</a>
						</div>

						<div class="user-action user-account">
							<?php
							$btn_label  = 'me connecter';
							$link_title = __( 'Login / Register', 'woothemes' );
							if ( is_user_logged_in() ) {
								$btn_label  = 'mon compte';
								$link_title = __( 'My Account', 'woothemes' );}
							?>
							<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" title="<?php echo esc_attr( $link_title ); ?>" class="user-action-link">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48.67 48.67"><defs></defs><g id="Calque_2" data-name="Calque 2"><path class="user-action-svg" d="M24.33,0A24.34,24.34,0,1,0,48.67,24.33,24.33,24.33,0,0,0,24.33,0ZM40.11,31.62c-.52,4-4.74,4.77-8.06,5.1A67.92,67.92,0,0,1,20.12,37a23.16,23.16,0,0,1-10.63-3.2,1.31,1.31,0,0,1-.44-.47,1.08,1.08,0,0,1-.43-.67c-1.09-4.48,1.81-9.82,6.55-10.42,1.33-.17,2.7,0,4-.08a.78.78,0,0,0,.23-.07c0-.12-.06-.35-.08-.42a14.19,14.19,0,0,1-.69-2.73c-.16-3.25,1.92-7.37,5.52-7.55,5.45-.27,6.49,6.12,5.34,10.57q1.2-.15,2.4-.27a6.25,6.25,0,0,1,5.26,1.43A10.08,10.08,0,0,1,40.11,31.62Z"/></g></svg>
								<span><?php echo esc_html( $btn_label ); ?></span>
							</a>
						</div>
						<div class="user-action user-list">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="user-action-link">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.46 47.46"><defs></defs><path class="user-action-svg" d="M33.72,26.17c-.41-2.74-1-5.44-1.27-8.2a46.12,46.12,0,0,1-.15-4.7c0-.63,0-1.26.06-1.88a4.33,4.33,0,0,0,0-.64c-.09,0-.42-.1-.62-.11a126.92,126.92,0,0,0-16.89.41,130.45,130.45,0,0,0,.35,17.12c.13,1.47.27,2.94.45,4.4.07.61.15,1.21.23,1.81a5.09,5.09,0,0,1,.12.79,9.72,9.72,0,0,1,2.35.14c.72,0,1.44,0,2.17,0,1.36,0,2.73,0,4.09-.09,1.53-.07,3.05-.19,4.57-.36.77-.09,1.53-.18,2.3-.3a6.58,6.58,0,0,0,2-.33c.74-.59.57-2.9.56-3.84A29.08,29.08,0,0,0,33.72,26.17ZM18.21,22.42a1.26,1.26,0,0,1,1.25-1.25c2.62-.1,5.25-.26,7.87-.49a1.26,1.26,0,0,1,1.25,1.25,1.28,1.28,0,0,1-1.25,1.25c-2.62.23-5.25.39-7.87.49A1.26,1.26,0,0,1,18.21,22.42Zm9.66,6.45a28.92,28.92,0,0,1-7.88.47,1.29,1.29,0,0,1-1.25-1.25A1.26,1.26,0,0,1,20,26.84a26.64,26.64,0,0,0,7.22-.38,1.29,1.29,0,0,1,1.54.88A1.25,1.25,0,0,1,27.87,28.87ZM28,17.38H18.88a1.25,1.25,0,0,1,0-2.5H28A1.25,1.25,0,0,1,28,17.38Z"/><path class="user-action-svg" d="M23.73,0A23.73,23.73,0,1,0,47.46,23.73,23.72,23.72,0,0,0,23.73,0ZM36.22,34.43a2.89,2.89,0,0,1-2.37,2.33,55,55,0,0,1-10.16,1.05c-1.77.07-3.53.07-5.3,0-1.19,0-3,.19-4-.6s-.92-2.58-1.08-3.77c-.18-1.46-.34-2.92-.47-4.38A130.38,130.38,0,0,1,12.43,9.9a1.22,1.22,0,0,1,.66-1.05,1.33,1.33,0,0,1,.59-.2C17.63,8.26,21.6,8,25.57,8q3,0,6,.11c1.46.07,2.91.16,3.29,1.85.32,1.46,0,3.21,0,4.7a38.57,38.57,0,0,0,.36,5c.48,3.26,1.26,6.5,1.42,9.81A17.51,17.51,0,0,1,36.22,34.43Z"/></svg>
								<span>ma liste</span>
							</a>
						</div>
					</div>
					<div class="burger-toggle-wrapper">
						<div class="burger-toggle collapsed">
							<svg id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.17 9.7">
								<defs></defs>
								<path class="burger-icon-bars" d="M1,1H15.17"/>
								<path class="burger-icon-bars" d="M1,4.85H15.17"/>
								<path class="burger-icon-bars" d="M1,8.7H15.17"/>
							</svg>
						</div>
					</div>
				</div>
			</div>
			<div class="partner-logo">
				<a class="fab" href="https://lafab-bm.fr/" target="_blank" rel="external"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/logo_fab.svg' ); ?>" alt="Fabrication urbaine de Bordeaux"></a>
				<a class="bm" href="https://www.bordeaux-metropole.fr/" target="_blank" rel="external"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/logo_BM.svg' ); ?>" alt="Bordeaux Métropole"></a>
			</div>
			<div class="top-inset">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/inset_fab.svg' ); ?>" alt="background image">
			</div>
		</div>
		<div class="sub-menu-wrapper collapsed" data-menu="read-more">
			<div class="sub-menu">
				<div class="menu-container right">
					<nav id="site-navigation" class="main-navigation" role="navigation">    	
						<?php
						wp_nav_menu(
							array(
								'theme_location'  => 'menu-1',
								'menu_id'         => 'main-menu',
								'container_class' => '',
								'menu_class'      => 'main-menu nav',
								'container'       => 'div',
							)
						);
						?>
					</nav>
				</div>
			</div>
		</div>
		<div class="sub-menu-wrapper collapsed" data-menu="search">
			<div class="sub-menu">
				<div class="menu-container mobile">
					<a class="menu-button" href='<?php the_permalink( get_theme_mod( 'pagedegisement' ) ); ?>'>
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/deposit_alt.svg' ); ?>">
						<span>Les sites</span>
					</a>
					<a class="menu-button" href='<?php the_permalink( get_theme_mod( 'pagedesmatriaux' ) ); ?>'>
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/materials_alt.svg' ); ?>">
						<span>Les matériaux</span>
					</a>
					<a class="menu-button" href='<?php the_permalink( get_theme_mod( 'pagedesfournisseurs' ) ); ?>'>
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/providers_alt.svg' ); ?>">
						<span>Les fournisseurs</span>
					</a>
				</div>
				<div class="menu-container left mobile">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>	
		<?php
		$args = array( 'hide_manual_expand_btn' => true );
		get_template_part( 'template-parts/partials/manual', null, $args );
		?>
		<script>
			window.addEventListener('load',function(){
				let userActionsHeight = 0;
				if (window.innerWidth < 992){
					userActionsHeight = getComputedStyle(document.querySelector('.user-actions.mobile-menu')).height;
				}
				let collapsedPosition = "-101%";


				document.querySelectorAll('.user-action.read-more').forEach(elt=>elt.addEventListener('click',function(e){

					toggleSubMenu('read-more','search');

				}));
				
				document.querySelectorAll('.user-action.search').forEach(elt=>elt.addEventListener('click',function(e){

					toggleSubMenu('search','read-more');


				}));

				document.querySelectorAll('.mobile-deposit-material').forEach(elt=>elt.addEventListener('click',function(e){
					toggleSubMenu('search','read-more');
				}));

				function toggleSubMenu(main, other){
					let mainMenu = document.querySelector(`.sub-menu-wrapper[data-menu=${main}]`);
					let otherMenu = document.querySelector(`.sub-menu-wrapper[data-menu=${other}]`);
					let currentUserActionsHeight = document.querySelector('.user-actions.mobile-menu').classList.contains("collapsed")? "0px" : userActionsHeight;
					let translateHeight = currentUserActionsHeight;


					//collpase if needed
					mainMenu.classList.toggle("collapsed");
					translateHeight = mainMenu.classList.contains("collapsed")? collapsedPosition :currentUserActionsHeight;
					mainMenu.style.transform= `translateY(${translateHeight})`;

					//collapse other if openning
					if (!mainMenu.classList.contains("collapsed") && !otherMenu.classList.contains("collapsed")){
						otherMenu.classList.add("collapsed");
						otherMenu.style.transform= `translateY(${collapsedPosition})`;
					}
				}

				document.querySelector('.burger-toggle').addEventListener('click',function(e){
					let searchMenu = document.querySelector(".sub-menu-wrapper[data-menu=search]");
					let readMoreMenu = document.querySelector(".sub-menu-wrapper[data-menu=read-more]");
					document.querySelector('.burger-toggle-wrapper').classList.toggle("collapsed");

					document.querySelectorAll('.user-actions.mobile-menu').forEach(function(elt){					
						if(!elt.classList.contains("collapsed")){
							readMoreMenu.classList.add("collapsed");
							readMoreMenu.style.transform= `translateY(${collapsedPosition})`;
							searchMenu.classList.add("collapsed");
							searchMenu.style.transform= `translateY(${collapsedPosition})`;
							document.querySelectorAll('.user-action.search').forEach(elt=>elt.classList.add("collapsed"));
						}
						elt.classList.toggle("collapsed");
					});
				});

			})
		</script>
	</div><!-- #masthead -->
	<div id="content" class="site-content">
