<?php

namespace Pixelese\WPRM\Admin;


class Menu{

    /**
     * Summary of __construct
     * 
     * initialzing all the hooks and binding them to functions
    */
    function __construct(){

        add_action( 'admin_menu', [$this, 'add_wprm_menu_page'] );

    }


    /**
     * Summary of add_wprm_menu_page
     * @return void
     */
    public function add_wprm_menu_page(){

        

        $menu_slug = 'pxls-wprm';

        add_menu_page( 

            __('WP Role Maker', 'wp-role-maker'), 
            __('WP Role Maker', 'wp-role-maker'),
            'manage_options', 
            $menu_slug, 
            [$this, 'plugin_menu_page'], 
            'dashicons-privacy', 

        );


        add_submenu_page( 
            
            $menu_slug, 
            'Add New Role', 
            'Add New', 
            'manage_options',
            'post-new.php?post_type=pxls-wprm'
        );


        // add_submenu_page( 
            
        //     $menu_slug, 
        //     'Test Menu', 
        //     'Test Menu', 
        //     'manage_options',
        //     'pxls-test-menu',
        //     [$this, 'test_menu_callback']

        // );

    
        

    }

    // public function test_menu_callback(){

    //     //for testing or dumping

    // }

    
    public function plugin_menu_page(){

        /**
         * Just a Place holder no need to add anything
         */

        

    }


}
