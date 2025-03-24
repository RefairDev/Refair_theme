<?php
/**
 * Template part to display manual
 *
 * @package refair
 */

?>
<section class="manual collapsed">
		<div class="manual-inner">
			<?php if ( is_active_sidebar( 'manual' ) ) { ?>
				<?php dynamic_sidebar( 'manual' ); ?>
			<?php } ?>
		</div>
		
		<?php if ( ! array_key_exists( 'hide_manual_expand_btn', $args ) ) { ?>
		<div class="manual-actions-wrapper">
			<div class="manual-action-btn-wrapper">
				<div class="manual-action-btn-label">Mode d'emploi</div>
				<div class="manual-close collapsed">&gt;</div>
			</div>
		</div>
		<?php } ?>
</section>
