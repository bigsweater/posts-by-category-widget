<?php 

if (!empty($title)) {
	echo $before_title . $title . $after_title;
}

if ( $cat_query->have_posts() ) { ?>
<ul>
	<?php while ( $cat_query->have_posts() ) {
		$cat_query->the_post();
		if ( $btnurl ) { 
			
			$category = get_the_category();
			$catname = sanitize_title( $category[0]->cat_name );
			$articleid = $catname . '-' . get_the_ID(); ?>
	<li><a href="<?php echo $btnurl . '#' . $articleid; ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
	<?php } else { ?>
	<li><?php the_title(); ?></li>
	<?php } ?>
	<?php } ?>
</ul>
<?php } else { ?>
<p>Nothing has been posted in the selected categories.</p>
<?php }

if ( $btntext && $btnurl ) { ?>

<p><a class="btn" href="<?php echo $btnurl; ?>" title="<?php echo $btntext; ?>"><?php echo $btntext; ?></a></p>
<?php }