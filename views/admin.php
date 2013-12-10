<?php
/*
 * Display admin form.
 */
?>
<p>
	<label for="<?php _e( $this->get_field_id('title'), 'posts_by_cat_widget' ); ?>"><?php _e( 'Title:' ); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</label>
</p>

<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Number of posts to display (default: 10):', 'posts_by_cat_widget' ); ?><br />
<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" /></label></p>

<p><strong><?php _e('Select categories:', 'posts_by_cat_widget'); ?></strong></p>
<ul id="categorychecklist" class="list:category categorychecklist form-no-clear" style="list-style-type: none; margin-left: 5px; padding-left: 0px; margin-bottom: 20px;">
	<li id="<?php $this->get_field_id('category--1'); ?>" class="popular-category">
		<label for="<?php $this->get_field_id('post_category'); ?>" class="selectit">
			<input value="all" id="<?php echo $this->get_field_id('post_category'); ?>"
			name="<?php echo $this->get_field_name('post_category') . '[all]'; ?>"
			type="checkbox"
			<?php echo (FALSE === $selected_cats ? ' CHECKED' : ''); ?>>
			<?php _e('All Categories', 'posts_by_cat_widget'); ?>
		</label>
	</li>

	<?php ob_start();

	$this->bsd_wp_category_checklist($selected_cats, $this->number);

	ob_end_flush(); ?>
</ul>

<p>
	<label for="<?php _e( $this->get_field_id('order'), 'posts_by_cat_widget' ); ?>"><?php _e( 'Order:' ); ?></label>
	
	<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>">
		<option value="desc" <?php selected( $order, 'desc' ); ?>>Descending (Z-A)</option>
		<option value="asc" <?php selected( $order, 'asc' ); ?>>Ascending (A-Z)</option>
	</select>
</p>

<p>
	<label for="<?php _e( $this->get_field_id('orderby'), 'posts_by_cat_widget' ); ?>"><?php _e( 'Order By:' ); ?></label>
	
	<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>">
		<option value="date" <?php selected( $orderby, 'date' ); ?>>Date</option>
		<option value="author" <?php selected( $orderby, 'author' ); ?>>Author</option>
		<option value="modified" <?php selected( $orderby, 'modified' ); ?>>Modified</option>
		<option value="name" <?php selected( $orderby, 'name' ); ?>>Name</option>
		<option value="id" <?php selected( $orderby, 'id' ); ?>>Post ID</option>
		<option value="title" <?php selected( $orderby, 'title' ); ?>>Title</option>
		<option value="rand" <?php selected( $orderby, 'rand' ); ?>>Random</option>
	</select>
</p>