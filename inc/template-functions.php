<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package bcaf
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bcaf_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}


	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'bcaf_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function bcaf_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'bcaf_pingback_header' );



/* 
* Get ACF image attached to custom taxonomy in "award_categories"
* Used ona single post 
*/
function bcaf_category_acf_image() {

	// Fix loop error here
/*
	$terms = get_the_terms( get_the_ID(), 'award_categories'); 

	foreach($terms as $term) {
		$term_id = $term->term_id; 
		// Returns image ID
		$image_id = get_field('logo_image', 'category_' . $term_id);

	}

	$image = wp_get_attachment_image($image_id, array('300'), "", array( "class" => "brand-logo" ));

	return $image;
*/
}


// Add shortcode for use in Elementor
add_shortcode( 'brand_taxonomy_image', 'bcaf_category_acf_image' );


function bcaf_purhcase_tickets() {
//
}
add_shortcode( 'short_bcaf_purhcase_tickets', 'bcaf_purhcase_tickets' );



// Filter "post" and "award" post title color based on taxonomy
// Add in a CSS class hook
function bcaf_set_title_color ( $title, $id = null ) {

	if (is_admin()) {
		return $title;
	}

	if( get_post_type($id) == 'post' || get_post_type($id) == 'award') {


		if (get_post_type($id) == 'post') {
			$terms = get_the_terms( $id, 'category' ); 
		} else if ( get_post_type($id) == 'award') {
			$terms = get_the_terms( $id, 'award_categories' ); 
		}


		foreach($terms as $term) {
			$title = sprintf('<span class="tax-%s">%s</span>', $term->slug, $title );
		}
		
	}

    return $title;
}

add_filter( 'the_title', 'bcaf_set_title_color', 10, 2 );


add_shortcode('gf-loader','_gf_loader');

function _gf_loader() {

//    extract(shortcode_atts(array(
  //      'type' => 'type'
  //  ), $type));

	//if (!isset($type)) {
	//	$type = get_field('gf_form_id');
	//}
if (!is_admin()) {
	if (get_field('gf_form_id')) {
		$form_id = get_field('gf_form_id'); 
		
		//@params 
		// $id, $display_title, $display_description, $display_inactive, $field_values, $ajax, $tabindex, $echo
		gravity_form( $form_id,false, false, false, false, true );
	} else {
		die();
	}
}
}

//add_shortcde('accordion-start','_accordion_start');
/*
function _accordion_start() {

	$js = <<<MY_MARKER
	<script> 
	jQuery(document).ready(function($) { 
	var delay = 100; setTimeout(function() { 
	$('.elementor-tab-title').removeClass('elementor-active');
 	$('.elementor-tab-content').css('display', 'none'); }, delay); 
	}); 
</script>	
	MY_MARKER;

	return $js;
} */


// Editor permissions 
function add_gf_cap()
{
    $role = get_role( 'editor' );
    $role->add_cap( 'gform_full_access' );
}
 
add_action( 'admin_init', 'add_gf_cap' );


add_action( 'init', 'cp_change_post_object' );
// Change dashboard Posts to News
function cp_change_post_object() {
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
        $labels->name = 'News';
        $labels->singular_name = 'News';
        $labels->add_new = 'Add News';
        $labels->add_new_item = 'Add News';
        $labels->edit_item = 'Edit News';
        $labels->new_item = 'News';
        $labels->view_item = 'View News';
        $labels->search_items = 'Search News';
        $labels->not_found = 'No News found';
        $labels->not_found_in_trash = 'No News found in Trash';
        $labels->all_items = 'All News';
        $labels->menu_name = 'News';
        $labels->name_admin_bar = 'News';
}


/* Custom post title using ACF first / last name if applicable */


function awardee_post_title( $title, $id = null ) {

	// Check if Awardee CPT and do not override admin post titles
    if('awardee' == get_post_type($id) && !is_admin() && !get_field('multiple_people')) {
    		
    		// If Awardee has last name
    		if ( get_field('last_name')) {

    			$prefix = get_field('title');
    			$first_name = get_field('first_name');
    			$last_name = get_field('last_name');
    			$middle_name = get_field('middle_name');
    			$post_nominal_title = get_field('post_nominal_title');

    			$title = sprintf('%s %s %s %s %s', $prefix, $first_name, $middle_name, $last_name, $post_nominal_title); 

    			return $title;
    		}
    }

    return $title;
}

// Replace Howdy with Welcome
add_filter( 'the_title', 'awardee_post_title', 10, 2 );

add_filter( 'admin_bar_menu', 'replace_wordpress_howdy', 25 );
function replace_wordpress_howdy( $wp_admin_bar ) {
$my_account = $wp_admin_bar->get_node('my-account');
$newtext = str_replace( 'Howdy,', 'Welcome,', $my_account->title );
$wp_admin_bar->add_node( array(
'id' => 'my-account',
'title' => $newtext,
) );
}

