<?php
/**
 * 
 * A full list of Capablities as an Array
 * @return array
 */
function pxls_wprm_get_all_capabilities_dynamically() {

    global $wp_roles;

    // Load all roles and their capabilities
    $roles = $wp_roles->roles;

    $capabilities = [];

    foreach ($roles as $role => $details) {
        $capabilities = array_merge($capabilities, array_keys($details['capabilities']));
    }

    // Remove duplicates and return
    return array_unique($capabilities);

}