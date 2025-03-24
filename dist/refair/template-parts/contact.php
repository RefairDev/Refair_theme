<?php
/**
 *  Contact form Template part
 *
 * @package refair
 */

global $post;
?>

<div class="container">
	<div class="row">
		<div class="col">
		<h1 class="page-title blue"><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="contact-content">
				<?php
				$args  = array(
					'p'         => get_the_id(),
					'post_type' => 'any',
				);
				$query = new WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						the_content();
					}
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
	<form id="form" action="<?php echo esc_url( get_bloginfo( 'url' ) . '/wp-admin/admin-post.php' ); ?>" method="post">
	
		<input type="hidden" name="action" value="contact_form"/>
	
		<div class="form-block">
			<label class="field-title"><?php echo wp_kses_post( $page['who_is_title'][0] ); ?></label>
			<div>
				
				<?php
				$a_who = maybe_unserialize( $page['who_is'][0] );
				foreach ( $a_who as $who ) {
					?>
				<input type="radio" name="who" id="<?php echo esc_attr( $who['key'] ); ?>" value="<?php echo esc_attr( $who['key'] ); ?>"><label class="contact-who <?php echo esc_attr( $who['value'] ); ?>" for="<?php echo esc_attr( $who['key'] ); ?>"><?php echo wp_kses_post( $who['key'] ); ?></label>
				<?php } ?>
			</div>
		</div>
		<div class="form-block">
			<label class="field-title"><?php echo wp_kses_post( $page['contact_object_title'][0] ); ?></label>
			<div>
				<?php
				$a_contact_object = maybe_unserialize( $page['contact_object'][0] );
				foreach ( $a_contact_object as $contact_object ) {
					?>
				<input type="radio" name="contact-object" id="<?php echo esc_attr( $contact_object['key'] ); ?>" value="<?php echo esc_attr( $contact_object['key'] ); ?>" ><label class="contact-object <?php echo esc_attr( $contact_object['value'] ); ?>" for="<?php echo esc_attr( $contact_object['key'] ); ?>"><?php echo wp_kses_post( $contact_object['key'] ); ?></label>
				<?php } ?>
				
			</div>
		</div>
		<div class="form-block">
			<label class="field-title" for="fname"><?php echo wp_kses_post( $page['fname_title'][0] ); ?></label>
			<input type="text" id="fname" name="fname">
		</div>
		<div class="form-block">
			<label class="field-title" for="lname"><?php echo wp_kses_post( $page['lname_title'][0] ); ?></label>
			<input type="text" id="lname" name="lname">
		</div>	
		<div class="form-block">
			<label class="field-title" for="email"><?php echo wp_kses_post( $page['email_title'][0] ); ?></label>
			<input type="text" id="email" name="email">
		</div>			
		<div class="form-block">
			<label class="field-title" for="message"><?php echo wp_kses_post( $page['message_title'][0] ); ?></label>
			<textarea id="message" name="message"></textarea>
		</div>		
		<div class="form-block">
			<input type="checkbox" name="consent">
			<span class="consent-text"><?php echo wp_kses_post( $page['consent_text'][0] ); ?></span>
		</div>
		<div class="submit-div">
			<button type="submit" >Envoyer</button>
		</div>
	</form>
	<div id="contact-message-statut"></div>
	<script type="text/javascript">
	
		var moduleCONTACT = (function($){
	
			var $alert = $('#contact-message-statut');
	
			var alertHTML = function(message, error) {
	
				var alertClass = (error) ? 'message-statut error' : 'message-statut';
	
				return "<div class=\"" + alertClass + "\"><p>"+message+"</p></div>";
			}
	
			return function(e){
				e.preventDefault();
				var $form = $(this);
	
   
	
				var $consent = $form.find('[name="consent"]');
	
				if (!$consent.is(':checked')) {
					$consent.addClass('error');
	
					setTimeout(function() {
						$consent.removeClass('error');
					}, 300);
					
					return;
				}
	
	
				$.ajax({
					url: $form.attr('action'),
					method: $form.attr('method'),
					data: $form.serialize(),
					success: function(response){
	
						$alert.html(alertHTML('Votre message a été envoyé, nous vous répondrons dans les plus brefs délais.', false));
	
						$form.find('input, textarea').each(function(){
							$(this).val('');
						})
					},
					error: function(){
						$alert.html(alertHTML('Votre message n\'a pas pu être envoyé, veuillez véfirier que le formulaire ne comporte pas d\'erreurs.', true));
					}
				})
			}
		})(jQuery);
	
		jQuery('#form').on('submit', moduleCONTACT);
	
	</script>    
</div>    