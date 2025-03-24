<div class="popup-content se-loger">
	<div class="popup-header">
		<p class="popup-title"><?php the_title();?></p>
	</div>
	<div class="popup-body">
		<?php if(get_the_post_thumbnail_url(get_the_ID(),'medium') != false ){?> 
		<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'medium');?>" alt="<?php the_title();?>">
		<?php }?>
		<?php the_content();?>
	</div>
	<div class="popup-footer">
		Description détaillée <a href="<?php the_permalink();?>">ici</a>
	</div>
</div>