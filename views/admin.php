<?php 

// Display admin form ?>
<p><label for="<?php echo __( $this->get_field_id('title'), 'sh'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<p><label for="<?php echo __( $this->get_field_id('btntext'), 'sh'); ?>">Button Text: <input class="widefat" id="<?php echo $this->get_field_id('btntext'); ?>" name="<?php echo $this->get_field_name('btntext'); ?>" type="text" value="<?php echo esc_attr($btntext); ?>" /></label></p>
<p><label for="<?php echo __( $this->get_field_id('btnurl'), 'sh'); ?>">Button URL: <input class="widefat" id="<?php echo $this->get_field_id('btnurl'); ?>" name="<?php echo $this->get_field_name('btnurl'); ?>" type="text" value="<?php echo esc_url($btnurl); ?>" /></label></p>

<p><label for="<?php echo $this->get_field_id('count'); ?>">Number of posts to display (default: 5):<br />
<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" /></label></p>

<?php
echo '<p><strong>'.__('Select categories', 'avh-ec') . '</strong></p>';
echo '<ul id="categorychecklist" class="list:category categorychecklist form-no-clear" style="list-style-type: none; margin-left: 5px; padding-left: 0px; margin-bottom: 20px;">';
echo '<li id="' . $this->get_field_id('category--1') . '" class="popular-category">';
echo '<label for="' . $this->get_field_id('post_category') . '" class="selectit">';
echo '<input value="all" id="' . $this->get_field_id('post_category') . '" name="' . $this->get_field_name('post_category') . '[all]" type="checkbox" ' . (FALSE === $selected_cats ? ' CHECKED' : '') . '> ';
_e('All Categories', 'avh-ec');
echo '</label>';
echo '</li>';
ob_start();
$this->sh_wp_category_checklist($selected_cats, $this->number);
ob_end_flush();
echo '</ul>';
echo '</p>';
