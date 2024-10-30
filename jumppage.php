<?php
/*
Plugin Name: Jumppage
Plugin URI: http://www.anpsthemes.com
Description: Jumppage is a simple Wordpress plugin, that allows you to jump directly from one page to another, from simple menu that feels like the menu on your web site that generates directly from your menu page.
Author: Anpsthemes
Version: 1.0
Author URI: http://www.anpsthemes.com
*/
if(!defined('WPINC')) {
    die;
}
include_once "admin/menu_meta.php";

/* Settings menu item */
/* If user is admin, he will see theme options */
add_action('admin_menu', 'anps_jumppage_options_add_page');
function anps_jumppage_options_add_page() {
    global $current_user; 
    if($current_user->user_level==10) {
        add_options_page('Jumppage', 'Jumppage', 'read', 'anps_jumppage', 'anps_jumppage_options_do_page');
    }
}
function anps_jumppage_options_do_page() {
    include_once "admin/global_settings_view.php";
}
function jumppage_admin_script_style() {
    wp_register_script('jumppage_admin_js', plugin_dir_url( __FILE__ ). 'admin/assets/js/admin.js');
    wp_enqueue_script('jumppage_admin_js_menu', plugin_dir_url( __FILE__ ). 'admin/assets/js/jquery.dropdownPlain.js');
    wp_enqueue_script('jumppage_admin_js_menu_tabs', plugin_dir_url( __FILE__ ). 'admin/assets/js/organictabs.jquery.js');

    wp_enqueue_style('jumppage_style', plugin_dir_url( __FILE__ ). 'admin/assets/css/style.css');
}
add_action('admin_enqueue_scripts', 'jumppage_admin_script_style');

