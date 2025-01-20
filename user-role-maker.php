<?php
/**
 * Plugin Name: User Role Maker
 * Plugin URI: https://wordpress.org/plugins/wp-role-maker
 * Author: Ashikur Rahman
 * Author URI: https://ashikurrahmanbd.github.io/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Description: WordPress User Role Maker and Editor. Making Roles is now more easy!
 * Tags: wp role maker, Wordpress Role maker, role editor, user role editor, user role maker
 * Version: 1.0.0
 * Requires PHP: 5.0 
 * Requires at least: 5.0 
 * Text Domain: user-role-maker
 * Domain Path: /languages
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

    const version = '1.0.0';

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

    return PXLS_WPRM_Role_Maker::get_instance();

}

/**
 * kick of the  plugin
 */
pixelese_wp_role_maker();

add_action('init', 'testtt');

function testtt(){


    $caps = pxls_wprm_get_all_capabilities_dynamically();
    
    foreach( $caps as $cap_index => $cap_value ){

        echo $cap_value . '<br />';

    }
}