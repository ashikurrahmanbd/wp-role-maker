<?php

namespace Pixelese\WPRM\Admin;

class Cpt{

    function __construct(){

        add_action( 'init', [$this, 'register_wprm_post_type'] );

    }


    public function register_wprm_post_type(){

        $labels = array(

            'name'  => __('Roles', 'wp-role-maker'),
            'singular_name' => __('Role', 'wp-role-maker'),

            'add_new'            => __('Add New', 'wp-role-maker'),
            'add_new_item'       => __('Add New Role', 'wp-role-maker'),
            'new_item'           => __('New Role', 'wp-role-maker'),
            'edit_item'          => __('Edit Role', 'wp-role-maker'),
            'view_item'          => __('View Role', 'wp-role-maker'),
            'all_items'          => __('All Roles', 'wp-role-maker'),
            'search_items'       => __('Search Role', 'wp-role-maker'),
            'parent_item_colon'  => __('Parent Role:', 'wp-role-maker'),
            'not_found'          => __('No Role found.', 'wp-role-maker'),
            'not_found_in_trash' => __('No Role found in Trash.', 'wp-role-maker')

        );

        $args = array(

            'labels' => $labels,

            'public' => true,

            'show_ui'   => true,

            'show_in_menu'  => 'pxls-wprm',

            'capability_type'   => 'post',

            'supports'  => array('title'),

            'has_archive' => false

        );

        register_post_type( 'pxls-wprm', $args );

    }




}
