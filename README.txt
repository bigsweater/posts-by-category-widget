=== Posts By Category Widget ===

Contributors: volfro
Tags: widget, posts, category
Requires at least: 3.5
Tested up to: 3.7
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A widget which displays posts in category, order, and quantity of your choosing.

== Description ==

This widget displays posts in the category, order, and quantity of your choosing.

After installation, drag-and-drop the new "Category Widget" to a widget area, choose the categories you wish to display, and tell it the order and quantity in which you wish to display them. 

The default template is a simple unordered list, but you can create your own template, if you're a theme developer. Simply create a new template in the root of your theme's directory called "widget_cats.php" (or copy the existing template from `posts-by-cat-widget/views/template.php` to `your-theme/widget_cats.php` and use it as a starting point). 

It calls post data just like any other custom WordPress loop, so you have access to whatever data exists inside your post object. (E.g. `$post->the_title()`, `$post->the_permalink()`, `$post->your_custom_field()`).

== Installation ==

1. Upload `posts-by-cat-widget` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the "Category Widget" in any of your sidebars/widget areas

== Changelog ==

= 1.0 =
* Release