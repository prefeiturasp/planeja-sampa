<?php
/*
Plugin Name: WP Super FAQ
Plugin URI: http://plugins.swampedpublishing.com/wp-super-faq
Description: A lightweight Wordpress Plugin that implements an FAQ page on your site using simple jQuery animation for a clean, usable interface.
Version: 0.5.6
Author: rfrankel
Author URI: http://plugins.swampedpublishing.com/
License: GPL2

  	Copyright 2011  Ryan S. Frankel  (email : ryan.frankel@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
/* *****************************************************************************
	ENQUEUE STYLES AND SCRIPTS
***************************************************************************** */
add_action('init', 'wp_super_faq_enqueue');
function wp_super_faq_enqueue() {
    wp_enqueue_script( 'jquery' );
    
    
    // wp_super_faq
    wp_deregister_script( 'wp_super_faq' );
    wp_register_script( 'wp_super_faq', plugins_url('wp_super_faq.js', __FILE__), array('jquery'), false, true );
    wp_enqueue_script( 'wp_super_faq' );
}    

/* *****************************************************************************
	CREATE A CUSTOM POST TYPE (wp_super_faq type)
***************************************************************************** */
add_action('init', 'wp_super_faq_custom_post_type_init');
function wp_super_faq_custom_post_type_init() 
{
  $labels = array(
    'name' => _x('FAQ', 'post type general name'),
    'singular_name' => _x('FAQ', 'post type singular name'),
    'add_new' => _x('Add New Question', 'faq'),
    'add_new_item' => __('Add New Question'),
    'edit_item' => __('Edit Question'),
    'new_item' => __('New Question'),
    'all_items' => __('All Questions'),
    'view_item' => __('View Question'),
    'search_items' => __('Search Questions'),
    'not_found' =>  __('No questions found'),
    'not_found_in_trash' => __('No questions found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'FAQ'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','author')
  ); 
  register_post_type('wp_super_faq',$args);
}

/* *****************************************************************************
	ADD A TAXONOMY FOR USE WITH FAQ
***************************************************************************** */
add_action('init', 'wp_super_faq_custom_taxonomy_type_init');
function wp_super_faq_custom_taxonomy_type_init() {
	$labels = array(
		'name' => _x( 'FAQ Category', 'taxonomy general name' ),
		'singular_name' => _x( 'FAQ Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search FAQ Categories' ),
		'popular_items' => __( 'Popular FAQ Categories' ),
		'all_items' => __( 'All FAQ Categories' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit FAQ Category' ), 
		'update_item' => __( 'Update FAQ Category' ),
		'add_new_item' => __( 'Add New FAQ Category' ),
		'new_item_name' => __( 'New FAQ Category Name' ),
		'separate_items_with_commas' => __( 'Choose an appropriate FAQ Category for this question (one per question)' ),
		'add_or_remove_items' => __( 'Add or remove FAQ Categories' ),
		'choose_from_most_used' => __( 'Choose from the most used FAQ Categories' ),
		'menu_name' => __( 'FAQ Categories' ),
	); 
	
	register_taxonomy('wp_super_faq_category','wp_super_faq',array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'wp-super-faq-category' ),
	));
}  

/* *****************************************************************************
	ADMIN PAGE DEFAULT TEXT
***************************************************************************** */
// Change title text in editor
function wp_super_faq_custom_title_text( $title ){
	if (function_exists ('get_current_screen')) {
		$screen = get_current_screen();
		if ( 'wp_super_faq' == $screen->post_type ) {
			$title = 'Enter question here';
		}
		return $title;
	}
}
add_filter( 'enter_title_here', 'wp_super_faq_custom_title_text' );

// Add default content text 
function wp_super_faq_custom_content_text( $content ) {
	if (function_exists ('get_current_screen')) {
		$screen = get_current_screen();
		if ( 'wp_super_faq' == $screen->post_type ) {
			$content = 'Enter answer here';
		}
		return $content;
	}
}
add_filter( 'default_content', 'wp_super_faq_custom_content_text' );

/* *****************************************************************************
	ADD A SHORTCODE TO DISPLAY FAQ
***************************************************************************** */
// [wp_super_faq]
add_shortcode( 'wp_super_faq', 'wp_super_faq_shortcode' );
function wp_super_faq_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'show_categories' => false,
		'show_specific_category' => false,
	), $atts ) );
	
	// Counter for post IDs
	$wp_super_faq_i = 0;
	// Return String
	$returner = '';
	// Post Category Slug
	$post_category_slug = '';
	
	if ( $show_categories ){
		// get the categories possible for 'wp_super_faq_category'
		$args = array(
			'taxonomy' => 'wp_super_faq_category',
		);
		$terms = get_terms('wp_super_faq_category');
		
		foreach ( $terms as $term ) {
			$returner .=  "<h2>" . $term->name ."</h2>";
			$post_category_slug = urldecode($term->slug);
			// Custom Loop
			$wp_super_faq_query = new WP_Query( "taxonomy=wp_super_faq_category&term=$term->slug&posts_per_page=-1&showposts=-1" );
			// The Loop
			while ($wp_super_faq_query->have_posts()) : $wp_super_faq_query->the_post();
				$returner .= the_title( "<h4><a class='wp-super-faq-question-closed' id='wp-super-faq-question-$post_category_slug-$wp_super_faq_i' href='" . get_permalink() . "' title='" . the_title_attribute( 'echo=0' ) . "' rel='question'><span class='wp-super-faq-triangle'>&#9654;</span> ", '</a></h4>', false );
		
				$returner .= "<div class='wp-super-faq-answer' id='wp-super-faq-question-$post_category_slug-$wp_super_faq_i-answer' style=' zoom: 1;'>";
				$returner .= get_the_content();
				$returner .= "</div>";
				$wp_super_faq_i++;
			endwhile;
		}
	} else {
		if ( $show_specific_category ) { 
			$wp_super_faq_query = new WP_Query( "taxonomy=wp_super_faq_category&term=$show_specific_category&posts_per_page=-1&showposts=-1"  );
		} else {
			$wp_super_faq_query = new WP_Query( 'post_type=wp_super_faq&posts_per_page=-1&showposts=-1' );
		}
		
		// The Loop
		while ($wp_super_faq_query->have_posts()) : $wp_super_faq_query->the_post();
			if ( $show_specific_category ) {
				$post_taxonomy = wp_get_post_terms( get_the_ID(), 'wp_super_faq_category');
				$post_category_slug = urldecode($post_taxonomy[0]->slug);
			}
			$returner .= the_title( '<h4><a class="wp-super-faq-question-closed" id="wp-super-faq-question-' . $post_category_slug . '-' . $wp_super_faq_i . '" href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="question"><span class="wp-super-faq-triangle">&#9654;</span> ', '</a></h4>', false );
				
			$returner .= "<div class='wp-super-faq-answer' id='wp-super-faq-question-$post_category_slug-$wp_super_faq_i-answer' style='  zoom: 1;'>";
			$returner .= get_the_content();
			$returner .= "</div>";
			// Increase the count
			$wp_super_faq_i++;
		endwhile;
	} //end if
	wp_reset_query();
	return $returner;
}
?>