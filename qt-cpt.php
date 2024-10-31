<?php

function qt_register_post_type()
{
    $singular = 'Quote';
    $plural = 'Quotes & Testimonials';

    $labels = array(
      'name' => $plural,
      'singular_name' => $singular,
      'add_name' => 'Add New',
      'add_new_item' => 'Add New '.$singular,
      'edit' => 'Edit',
      'edit_item' => 'Edit '.$singular,
      'new_item' => 'New '.$singular,
      'view' => 'view '.$singular,
      'view_item' => 'view '.$singular,
      'search_term' => 'Search '.$plural,
      'parent' => 'Parent '.$singular,
      'not_found' => 'No '.$plural.'found',
      'not_found_in_trash' => 'No '.$plural.' in Trash',
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'exclude_from_search' => false,
      'show_in_nav_menus' => true,
      'show_in_ui' => true,
      'menu_position' => 6,
      'menu_icon' => 'dashicons-testimonial',
      'can_export' => true,
      'delete_with_user' => false,
      'hierarchical' => false,
      'has_archive' => true,
      'query_var' => true,
      'capability_type' => 'post',
      'map_meta_cap' => true,
      // 'capabilities' => array(),
      'rewrite' => array(
        'slug' => 'quote',
        'with_front' => true,
        'pages' => true,
        'feeds' => true,
      ),
      'supports' => array(
        'title',
      ),
  );

    register_post_type('quote', $args);
}
add_action('init', 'qt_register_post_type');
