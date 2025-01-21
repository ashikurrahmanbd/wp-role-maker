<?php

namespace PXLS\WPRM\Admin;


class Menu{

    /**
     * Summary of __construct
     * 
     * initialzing all the hooks and binding them to functions
    */
    function __construct(){

        add_action( 'admin_menu', [$this, 'pxls_wprm_add_menu_page'] );

    }


    /**
     * Summary of add_wprm_menu_page
     * @return void
     */
    public function pxls_wprm_add_menu_page(){

        $menu_slug = 'pxls-wprm';

        add_menu_page( 

            __('User Role Maker', 'user-role-maker'), 
            __('User Role Maker', 'user-role-maker'),
            'manage_options', 
            $menu_slug, 
            [$this, 'pxls_wprm_plugin_menu_page'], 
            'dashicons-privacy', 

        );


        add_submenu_page( 
            
            $menu_slug, 
            'Add New Role', 
            'Add New', 
            'manage_options',
            'post-new.php?post_type=pxls-wprm'
        );   

    }

    
    public function pxls_wprm_plugin_menu_page(){

        /**
         * Just a Place holder no need to add anything
        */

        

        
    }


}
