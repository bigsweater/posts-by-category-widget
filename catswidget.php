<?php
/* 
 * This widget uses wp_category_checklist() to create a loop of recent posts wh-
 * ich belong to the categories you specify in a checklist. 
*/

// New widget class
class Cats_Loop_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/
	
	/**
	 * Specifies the classname and description, instantiates the widget, 
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {
	
		// load plugin text domain
		add_action( 'init', array( $this, 'widget_textdomain' ) );
		
		// Hooks fired when the Widget is activated and deactivated
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		
		// Create a new widget, name it, give it a description, IDs, and classes
		parent::__construct(
			'cats-loop-widget',
			__( 'TMAC: Posts by Category', 'sh' ),
			array(
				'classname'		=>	'cats-loop-widget',
				'description'	=>	__( 'Creates a list of posts within the selected categories.', 'sh' )
			)
		);
		
		// Register admin styles and scripts
		//add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	
		// Register site styles and scripts
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
		
	} // end constructor

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/
	
	/**
	 * Outputs the content of the widget.
	 *
	 * @param	array	args		The array of form elements
	 * @param	array	instance	The current instance of the widget
	 */
	public function widget( $args, $instance ) {
	
		extract( $args, EXTR_SKIP );
		
		$before_widget = str_replace('class="', 'class="widget_posts-by-cat ', $before_widget);
		
		echo $before_widget;
    
		//	Here is where you manipulate your widget's values based on their input fields
		
		$included_cats = '';
		if ($instance['post_category']) {
			$post_category = unserialize($instance['post_category']);
			$children = array();
			foreach ($post_category as $cat_id) {
				$children = array_merge($children, get_term_children($cat_id, 'category'));
			}
			$included_cats = implode(",", array_merge($post_category, $children));
		}
		
		$title	= esc_html( $instance['title'] );
		$count	= intval( $instance['count'] );
		$btntext= esc_html( $instance['btntext']);
		$btnurl	= trailingslashit( esc_url( $instance['btnurl']) );
		if ( $instance['order'] ) {
			$order	= sanitize_key( $instance['order'] );	
		}
		if ( $instance['orderby'] ) {
			$orderby= sanitize_key( $instance['orderby'] );
		}
		
		$cat_args = array( 
			'cat'				=> $included_cats,
			'posts_per_page'	=> $count,
			'order'				=> $order,
			'orderby'			=> $orderby
		);
		
		$cat_query = new WP_Query( $cat_args ); 
   
		include( plugin_dir_path( __FILE__ ) . '/views/template.php' );
		
		wp_reset_postdata();
		
		echo $after_widget;
		
	} // end widget
	
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param	array	new_instance	The previous instance of values before the update.
	 * @param	array	old_instance	The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance['title']		= strip_tags(stripslashes($new_instance['title']));
		$instance['count']		= $new_instance['count'];
		$instance['btntext']	= $new_instance['btntext'];
		$instance['btnurl']		= $new_instance['btnurl'];
		$instance['order']		= $new_instance['order'];
		$instance['orderby']	= $new_instance['orderby'];
		
		if (array_key_exists('all', $new_instance['post_category'])) {
			$instance['post_category'] = FALSE;
		} else {
			$instance['post_category'] = serialize($new_instance['post_category']);
		}
		
		return $instance;
		
	} // end widget
	
	/**
	 * Generates the administration form for the widget.
	 *
	 * @param	array	instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {
	
    	// Define default values for your variables
		$instance = wp_parse_args(
			(array) $instance, array( 
				'title'			=> '',
				'count'			=> '10',
				'post_category'	=> '',
				'btnurl'		=> '',
				'btntext'		=> '',
				'order'			=> 'DESC',
				'orderby'		=> 'date'
			)
		);
		
		$title			= esc_html( $instance['title'] );
		$count			= intval( $instance['count'] );
		$selected_cats	= ($instance['post_category'] != '') ? unserialize($instance['post_category']) : FALSE;
		$btntext		= esc_html( $instance['btntext'] );
		$btnurl			= esc_url( $instance['btnurl'] );
		$order			= sanitize_key( $instance['order'] );
		$orderby		= sanitize_key( $instance['orderby'] );
		
		// Display the admin form
		include( plugin_dir_path(__FILE__) . '/views/admin.php' );	
		
	} // end form

	/**
	 * Creates the categories checklist
	 *
	 * @param int $post_id
	 * @param int $descendants_and_self
	 * @param array $selected_cats
	 * @param array $popular_cats
	 * @param int $number
	 */
	function sh_wp_category_checklist ($selected_cats, $number)
	{

		$walker = new SH_Walker_Category_Checklist();
		$walker->number = $number;
		$walker->input_id = $this->get_field_id('post_category');
		$walker->input_name = $this->get_field_name('post_category');
		$walker->li_id = $this->get_field_id('category--1');

		$args = array ( 'taxonomy' => 'category', 'descendants_and_self' => 0, 'selected_cats' => $selected_cats, 'popular_cats' => array (), 'walker' => $walker, 'checked_ontop' => true, 'popular_cats' => array () );

		if (is_array($selected_cats))
			$args['selected_cats'] = $selected_cats;
		else
			$args['selected_cats'] = array ();

		$categories = getCategories();
		$_categories_id = getCategoriesId($categories);

		// Post process $categories rather than adding an exclude to the get_terms() query to keep the query the same across all posts (for any query cache)
		$checked_categories = array ();
		foreach ($args['selected_cats'] as $key => $value) {
			if (isset($_categories_id[$key])) {
				$category_key = $_categories_id[$key];
				$checked_categories[] = $categories[$category_key];
				unset($categories[$category_key]);
			}
		}

		// Put checked cats on top
		echo $walker->walk_cats($checked_categories, 0, array ( $args ));
		// Then the rest of them
		echo $walker->walk_cats($categories, 0, array ( $args ));
	}

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/
	
	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {
	
		// TODO be sure to change 'widget-name' to the name of *your* plugin
		load_plugin_textdomain( 'sh', false, plugin_dir_path( __FILE__ ) . '/lang/' );
		
	} // end widget_textdomain
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param		boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here		
	} // end deactivate
	
	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_style( 'widget-name-admin-styles', plugins_url( 'widget-name/css/admin.css' ) );
	
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_script( 'widget-name-admin-script', plugins_url( 'widget-name/js/admin.js' ) );
		
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_style( 'widget-name-widget-styles', plugins_url( 'widget-name/css/widget.css' ) );
		
	} // end register_widget_styles
	
	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_script( 'widget-name-script', plugins_url( 'widget-name/js/widget.js' ) );
		
	} // end register_widget_scripts
	
} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("Cats_Loop_Widget");' ) ); 





/**
 * Class that will display the categories
 *
 */
class SH_Walker_Category_Checklist extends Walker
{
	var $tree_type = 'category';
	var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id' ); //TODO: decouple this
	var $number;
	var $input_id;
	var $input_name;
	var $li_id;

	/**
	 * Display array of elements hierarchically.
	 *
	 * It is a generic function which does not assume any existing order of
	 * elements. max_depth = -1 means flatly display every element. max_depth =
	 * 0 means display all levels. max_depth > 0  specifies the number of
	 * display levels.
	 *
	 * @since 2.1.0
	 *
	 * @param array $elements
	 * @param int $max_depth
	 * @param array $args;
	 * @return string
	 */
	function walk_cats($elements, $max_depth, $args)
	{
		$output = '';

		if ($max_depth < - 1) //invalid parameter
			return $output;

		if (empty($elements)) //nothing to walk
			return $output;

		$id_field = $this->db_fields['id'];
		$parent_field = $this->db_fields['parent'];

		// flat display
		if (- 1 == $max_depth) {
			$empty_array = array ();
			foreach ($elements as $e)
				$this->display_element($e, $empty_array, 1, 0, $args, $output);
			return $output;
		}

		/*
		 * need to display in hierarchical order
		 * separate elements into two buckets: top level and children elements
		 * children_elements is two dimensional array, eg.
		 * children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array ();
		$children_elements = array ();
		foreach ($elements as $e) {
			if (0 == $e->$parent_field)
				$top_level_elements[] = $e;
			else
				$children_elements[$e->$parent_field][] = $e;
		}

		/*
		 * when none of the elements is top level
		 * assume the first one must be root of the sub elements
		 */
		if (empty($top_level_elements)) {

			$first = array_slice($elements, 0, 1);
			$root = $first[0];

			$top_level_elements = array ();
			$children_elements = array ();
			foreach ($elements as $e) {
				if ($root->$parent_field == $e->$parent_field)
					$top_level_elements[] = $e;
				else
					$children_elements[$e->$parent_field][] = $e;
			}
		}

		foreach ($top_level_elements as $e)
			$this->display_element($e, $children_elements, $max_depth, 0, $args, $output);

			/*
		 * if we are displaying all levels, and remaining children_elements is not empty,
		 * then we got orphans, which should be displayed regardless
		 */
		if (($max_depth == 0) && count($children_elements) > 0) {
			$empty_array = array ();
			foreach ($children_elements as $orphans)
				foreach ($orphans as $op)
					$this->display_element($op, $empty_array, 1, 0, $args, $output);
		}

		return $output;
	}

	function start_lvl ( &$output, $depth = 0, $args = array() )
	{
		$indent = str_repeat("\t", $depth);
		$output .= $indent . '<ul class="children">' . "\n";
	}

	function end_lvl ( &$output, $depth = 0, $args = array() )
	{
		$indent = str_repeat("\t", $depth);
		$output .= $indent . '</ul>' . "\n";
	}

	function start_el ( &$output, $category, $depth = 0, $args = array(), $id = 0 )
	{
		extract($args);
		$input_id = $this->input_id . '-' . $category->term_id;
		$output .= "\n" . '<li id="' . $this->li_id . '">';
		$output .= '<label for="' . $input_id . '" class="selectit">';
		$output .= '<input value="' . $category->term_id . '" type="checkbox" name="' . $this->input_name . '[' . $category->term_id . ']" id="' . $input_id . '"' . (in_array($category->term_id, $selected_cats) ? ' checked="checked"' : "") . '/> ' . esc_html(apply_filters('the_category', $category->name)) . '</label>';
	}

	function end_el ( &$output, $category, $depth = 0, $args = array() )
	{
		$output .= "</li>\n";
	}
}

function getCategories () {
	static $_categories = NULL;
	if (NULL === $_categories) {
		$_categories = get_categories('get=all');
	}
	return $_categories;
}

function getCategoriesId ($categories) {
	static $_categories_id = NULL;
	if (NULL == $_categories_id) {
		foreach ($categories as $key => $category) {
			$_categories_id[$category->term_id] = $key;
		}
	}
	return $_categories_id;
}