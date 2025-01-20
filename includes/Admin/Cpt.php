<?php

namespace Pixelese\WPRM\Admin;

class Cpt{

    function __construct(){

        add_action( 'init', [$this, 'register_wprm_post_type'] );

        add_action( 'add_meta_boxes', [$this, 'wprm_cpt_meta_boxes'] );

        add_action( 'save_post', [$this, 'wprm_cpt_capability_metabox_data_save'] );

        add_action('wp_trash_post', [$this, 'pxls_wprm_handle_role_trash']);

        add_action('before_delete_post', [$this, 'pxls_wprm_handle_role_deletion']);

        add_filter('post_row_actions', [$this, 'pxls_wprm_remove_view_link'], 10, 2);


    }


    /**
     * Summary of register_wprm_post_type
     * 
     * 
     * @return void
     */
    public function register_wprm_post_type(){

        $labels = array(

            'name'  => __('Roles', 'user-role-maker'),
            'singular_name' => __('Role', 'user-role-maker'),

            'add_new'            => __('Add New', 'user-role-maker'),
            'add_new_item'       => __('Add New Role', 'user-role-maker'),
            'new_item'           => __('New Role', 'user-role-maker'),
            'edit_item'          => __('Edit Role', 'user-role-maker'),
            'view_item'          => __('View Role', 'user-role-maker'),
            'all_items'          => __('All Roles', 'user-role-maker'),
            'search_items'       => __('Search Role', 'user-role-maker'),
            'parent_item_colon'  => __('Parent Role:', 'user-role-maker'),
            'not_found'          => __('No Role found.', 'user-role-maker'),
            'not_found_in_trash' => __('No Role found in Trash.', 'user-role-maker')

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


    /**
     * MetaBox for capability
     * 
     * @param mixed $post
     * @return void
     */
    public function wprm_cpt_meta_boxes($post){

        add_meta_box( 

            'wprm_cpt_capability_metabox', 
            __('Add Capabilities for this Role ', 'user-role-maker'), 
            [$this, 'wprm_cpt_capability_metabox_callback'], 
            'pxls-wprm', 
            'normal', 
            'default', 

        );

       

    }


    /**
     * Metabox Callback
     * 
     * @param mixed $post
     * @return void
     */
    public function wprm_cpt_capability_metabox_callback($post){

        wp_nonce_field( 

            'pxls_wprm_capabilities_metabox_data_action', 
            'pxls_wprm_capabilies_metabox_nonce' 

        );

        $capabilities = get_post_meta( $post->ID, 'wprm_user_caps', true ) ? : [];

        $capabilities_list = pxls_wprm_get_all_capabilities_dynamically();

        ?>

            <div class="all-caps">

                <style>
                    .all-caps label{
                        display: inline-block;
                        min-width: 200px;
                        margin-bottom: 6px;
                    }
                </style>

                <?php 

                    foreach( $capabilities_list as $capability ){

                        $checked = in_array($capability, $capabilities) ? 'checked' : '';

                        $read_cap_message = ($capability === 'read' ? ' (Mandatory)' : '');

                        echo '<label for="'. esc_attr($capability) .'"> <input type="checkbox" name="capabilities[]" id="'. esc_attr( $capability ) .'" value="'. esc_attr( $capability ) .'" '. esc_attr( $checked ) .' /> '. esc_html( $capability . $read_cap_message ) .' </label>';

                    }

                ?>
                
               
            </div>

        <?php

    }


    public function wprm_cpt_capability_metabox_data_save($post_id){


         // Check if nonce is set
        if ( ! isset( $_POST['pxls_wprm_capabilies_metabox_nonce'] ) ) {
            return;
        }
        

        // if (
        //     ! isset($_POST['pxls_wprm_capabilies_metabox_nonce']) ||! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['pxls_wprm_capabilies_metabox_nonce'], 'pxls_wprm_capabilities_metabox_data_action' ) ) )){

        //     return;

        // }

        // Verify nonce and sanitize
        $nonce = sanitize_text_field( wp_unslash( $_POST['pxls_wprm_capabilies_metabox_nonce'] ) );

        if ( ! wp_verify_nonce( $nonce, 'pxls_wprm_capabilities_metabox_data_action' ) ) {

            return;

        }

        // Check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {

            return;

        }
    
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {

            return;

        }
        
        $role_slug = strtolower(str_replace(' ', '_', get_the_title($post_id)));

        if (isset($_POST['capabilities'])) {

            // Sanitize input capabilities
            $new_capabilities = array_map('sanitize_text_field', wp_unslash( $_POST['capabilities'] ));

            // Update post meta
            update_post_meta($post_id, 'wprm_user_caps', $new_capabilities);

            // Get or create the role
            $role = get_role($role_slug);

            if ($role === null) {
                // Role doesn't exist, create it
                add_role(
                    $role_slug,
                    get_the_title($post_id),
                    array_fill_keys($new_capabilities, true)
                );

            } else {

                // Role exists, sync capabilities
                $existing_capabilities = $role->capabilities;

                // Add new capabilities
                foreach ($new_capabilities as $capability) {
                    if (!isset($existing_capabilities[$capability]) || !$existing_capabilities[$capability]) {
                        $role->add_cap($capability);
                    }
                }

                // Remove unchecked capabilities
                foreach ($existing_capabilities as $capability => $granted) {
                    if ($granted && !in_array($capability, $new_capabilities)) {
                        $role->remove_cap($capability);
                    }
                }

            }
        } else {

            // If no capabilities are submitted, delete all meta and role
            delete_post_meta($post_id, 'wprm_user_caps');

            // Optionally delete the role
            remove_role($role_slug);

        }


    }

    // Handle when the post is moved to trash
    function pxls_wprm_handle_role_trash($post_id) {

        if (get_post_type($post_id) !== 'pxls-wprm') {

            return;

        }

        // Generate the role slug from the post title
        $role_slug = strtolower(str_replace(' ', '_', get_the_title($post_id)));

        // Get the role object
        $role = get_role($role_slug);

        if ($role) {

            // Reassign users with this role to 'subscriber'
            $users = get_users(['role' => $role_slug]);

            foreach ($users as $user) {

                // Assign the 'subscriber' role to the user
                $user->set_role('subscriber');

            }

            // Remove the role
            remove_role($role_slug);
        }
    }

    // Handle when the post is permanently deleted
    function pxls_wprm_handle_role_deletion($post_id) {

        if (get_post_type($post_id) !== 'pxls-wprm') {

            return;

        }

        // Cleanup: Remove the custom post meta for the deleted role
        delete_post_meta($post_id, 'wprm_user_caps');

    }


    //remove View link 
    function pxls_wprm_remove_view_link($actions, $post) {

        // Check if the post type is the custom post type
        if ($post->post_type == 'pxls-wprm') {

            // Remove the "View" link from the actions
            unset($actions['view']);

        }

        return $actions;
    }
    
    

}
