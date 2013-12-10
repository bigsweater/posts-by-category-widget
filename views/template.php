<?php 

if ( $cat_query->have_posts() ) { ?>
<ul>
	<?php while ( $cat_query->have_posts() ) {
		$cat_query->the_post(); ?>
	<li><?php the_title(); ?></li>
	<?php } ?>
</ul>
<?php } else { ?>
<p><?php _e( 'Nothing has been posted in the selected categories.', 'posts_by_cat_widget' ); ?></p>
<?php }
