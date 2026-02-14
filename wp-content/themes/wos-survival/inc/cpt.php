<?php
/**
 * Register Custom Post Types and Taxonomies
 *
 * @package WOS_Frost_Fire
 */

function wos_register_cpt_hero() {
    $labels = array(
        'name'                  => _x( 'Heroes', 'Post Type General Name', 'wos-frost-fire' ),
        'singular_name'         => _x( 'Hero', 'Post Type Singular Name', 'wos-frost-fire' ),
        'menu_name'             => __( 'Heroes', 'wos-frost-fire' ),
        'name_admin_bar'        => __( 'Hero', 'wos-frost-fire' ),
        'archives'              => __( 'Hero Archives', 'wos-frost-fire' ),
        'attributes'            => __( 'Hero Attributes', 'wos-frost-fire' ),
        'all_items'             => __( 'All Heroes', 'wos-frost-fire' ),
        'add_new_item'          => __( 'Add New Hero', 'wos-frost-fire' ),
        'add_new'               => __( 'Add New', 'wos-frost-fire' ),
        'new_item'              => __( 'New Hero', 'wos-frost-fire' ),
        'edit_item'             => __( 'Edit Hero', 'wos-frost-fire' ),
        'update_item'           => __( 'Update Hero', 'wos-frost-fire' ),
        'view_item'             => __( 'View Hero', 'wos-frost-fire' ),
        'view_items'            => __( 'View Heroes', 'wos-frost-fire' ),
        'search_items'          => __( 'Search Hero', 'wos-frost-fire' ),
    );
    $args = array(
        'label'                 => __( 'Hero', 'wos-frost-fire' ),
        'description'           => __( 'Whiteout Survival Heroes', 'wos-frost-fire' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'hero_generation', 'hero_type', 'hero_rarity' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-shield',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Enable Gutenberg
    );
    register_post_type( 'wos_hero', $args );
}
add_action( 'init', 'wos_register_cpt_hero', 0 );


// Taxonomies
function wos_register_taxonomies() {
    // Hero Generation
    register_taxonomy( 'hero_generation', 'wos_hero', array(
        'label'        => __( 'Generation', 'wos-frost-fire' ),
        'rewrite'      => array( 'slug' => 'generation' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );
    
    // Hero Type (Infantry, Lancer, Marksman)
    register_taxonomy( 'hero_type', 'wos_hero', array(
        'label'        => __( 'Class Type', 'wos-frost-fire' ),
        'rewrite'      => array( 'slug' => 'type' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    // Hero Rarity
    register_taxonomy( 'hero_rarity', 'wos_hero', array(
        'label'        => __( 'Rarity', 'wos-frost-fire' ),
        'rewrite'      => array( 'slug' => 'rarity' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'wos_register_taxonomies', 0 );

// Event CPT
function wos_register_cpt_event() {
    $labels = array(
        'name'                  => _x( 'Events', 'Post Type General Name', 'wos-frost-fire' ),
        'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'wos-frost-fire' ),
        'menu_name'             => __( 'Events', 'wos-frost-fire' ),
        'add_new_item'          => __( 'Add New Event', 'wos-frost-fire' ),
    );
    $args = array(
        'label'                 => __( 'Event', 'wos-frost-fire' ),
        'description'           => __( 'Game Events', 'wos-frost-fire' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-calendar',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
    );
    register_post_type( 'wos_event', $args );
}
add_action( 'init', 'wos_register_cpt_event', 0 );
