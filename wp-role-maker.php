<?php
/**
 * Plugin Name: WP Role Maker
 * Plugin URI: https://wordpress.org/plugins/wp-role-maker
 * Author: Ashikur Rahman
 * Description: WordPress Role Maker and Editor
 * Tags: wp role maker, Wordpress Role maker, role editor
 * Version: 1.0
 * License: GPLv2 or later
 * Text Domain: wp-role-maker
*/

if ( ! defined('ABSPATH')  ) {

    exit;

}

require_once __DIR__ . '/vendor/autoload.php';

final class Pixlese_Wp_role_maker{

    const version = '1.0';

    private function __construct(){

        $this->define_constants();

        register_activation_hook( __FILE__, [$this, 'plugin_activate'] );

        add_action( 'plugins_loaded', [$this, 'load_dependencies'] );

    }


    /**
     * Class singleton instance
     * 
     * @return \Pixlese_Wp_role_maker
     */
    public static function get_instance(){

        static $instance = null;

        if ( ! $instance ) {

            $instance = new self();

        }

        return $instance;

    }

    /**
     * Define all the constants
    */

    public function define_constants(){

        define('PXLS_WPRM_VERSION', self::version );

        define( 'PXLS_WPRM_FILE', __FILE__ );

        define( 'PXLS_WPRM_PATH', __DIR__ );

        define( 'PXLS_WPRM_URL', plugins_url( '', __FILE__ ) );

        define( 'PXLS_WPRM_ASSETS', PXLS_WPRM_URL . '/assets' );

    }


    /**
     * Plugin activation task
     *  
    */

    public function plugin_activate(){

        $pixlese_wprm_installed = get_option( 'pxls_wprm_installed');

        if ( ! $pixlese_wprm_installed ) {

            update_option( 'pxls_wprm_installed', time() );

        }

        update_option( 'pxls_wprm_version', PXLS_WPRM_VERSION );

    }


    /**
     * Load dependencies
    */

    public function load_dependencies(){

        if( is_admin() ){

            new Pixelese\WPRM\Admin();

        }

    }


}

/**
 * return the main instance of the class
 */

 function pixelese_wp_role_maker(){

    return Pixlese_Wp_role_maker::get_instance();

}

/**
 * kick of the  plugin
 */
pixelese_wp_role_maker();


