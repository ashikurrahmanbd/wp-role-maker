<?php

namespace PXLS\WPRM\Admin;

class EnqueAssets{

    function __construct(){

        add_action( 'admin_enqueue_scripts', array($this, 'pxls_wprm_admin_asset_register'));

    }

    public function pxls_wprm_admin_asset_register(){

        wp_register_style( 
            'pxls-wprm-admin-style', 
            PXLS_WPRM_ASSETS . '/css/admin/admin-custotm-style.css', 
            [], 
            filemtime( PXLS_WPRM_PATH . '/assets/css/admin/admin-custotm-style.css')
        );

        wp_enqueue_style( 'pxls-wprm-admin-style' );


    }


}