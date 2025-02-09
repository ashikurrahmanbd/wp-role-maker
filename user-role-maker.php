<?php
/**
 * Plugin Name: User Role Maker
 * Plugin URI: https://wordpress.org/plugins/wp-role-maker
 * Description: WordPress User Role Maker and Editor. Making Roles is now more easy!
 * Tags: WP Role Maker, WordPress Role maker, role editor, user role editor, user role maker
 * Author: Ashikur Rahman
 * Author URI: https://ashikurrahmanbd.github.io/
 * Version: 1.2.0
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: user-role-maker
*/

if ( ! defined('ABSPATH')  ) {

    exit;

}

// Composer Autolaod File
require_once __DIR__ . '/vendor/autoload.php';


/**
 * Plugin Core Class
 */
final class PXLS_WPRM_Role_Maker{

    const version = '1.2.0';

    private function __construct(){

        $this->define_constants();

        register_activation_hook( __FILE__, [$this, 'plugin_activate'] );

        add_action( 'plugins_loaded', [$this, 'load_dependencies'] );

    }


    /**
     * Class singleton instance
     * 
     * @return \PXLS_WPRM_Role_Maker
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

        define( 'PXLS_WPRM_PATH',  plugin_dir_path( __FILE__ ));

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


            new PXLS\WPRM\Admin();

        }

    }


}

/**
 * return the main instance of the class
*/

 function pxls_wprm_role_maker(){

    return PXLS_WPRM_Role_Maker::get_instance();

}

/**
 * kick of the  plugin
 */
pxls_wprm_role_maker();