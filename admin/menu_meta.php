<?php
add_action('add_meta_boxes', 'anps_jumppage_content_add_custom_box');
function anps_jumppage_content_add_custom_box() {
    $screens = array('page');
    foreach ($screens as $screen) {
        add_meta_box('jumppage_menu_meta', __('Jumppage'), 'jumppage_display_menu_meta_box_content', $screen, 'anps', 'high');
        add_meta_box('jumppage_menu_side_meta', __('Pages'), 'jumppage_display_menu_side_meta_box_content', $screen, 'side', 'high');
    }
    add_meta_box('jumppage_menu_meta', __('Jumppage'), 'jumppage_display_menu_post_meta_box_content', 'post', 'anps', 'high');
    add_meta_box('jumppage_menu_posts_side_meta', __('Posts'), 'jumppage_display_menu_post_side_meta_box_content', "post", 'side', 'high');
}
function jumppage_select_menu_on_top() {
        global $post, $wp_meta_boxes;

        do_meta_boxes( get_current_screen(), 'anps', $post );
        unset($wp_meta_boxes['post']['anps']);
    }
add_action('edit_form_top', 'jumppage_select_menu_on_top');
/* Post categories */
function jumppage_display_menu_post_meta_box_content($post) {
    $data = "";
    $data .= "<ul class='site-navigation'>";
    foreach(get_categories() as $item){ 
        $data .= "<li><a>".$item->cat_name."</a>";
        $args = array(
            'category' => $item->term_id
        );
        $data .= "<ul class='sub-menu'>";
        foreach(get_posts($args) as $post_item) { 
            $data .= "<li><a href='".get_admin_url()."post.php?post=".$post_item->ID."&action=edit"."'>".$post_item->post_title."</a></li>";
        }
        $data .= "</ul></li>";
    }
    $data .= "</ul>";
    echo $data;
}
function jumppage_display_menu_meta_box_content($post) { 
    /* Check if menu is selected */
    $walker = '';
    $menus = get_terms('nav_menu', array( 'hide_empty' => true)); 
    $menu_data = get_option("menu_item", array("-1"));
    if(in_array("-1", $menu_data)) {
        $locations = $menus;
    } else {
            $locations = get_terms('nav_menu', array( 'hide_empty' => true, "include"=>$menu_data));
    } 
    $walker = new jumppage_description_walker();
    $data = "";
    $data .= "<ul class='tabnav nav'>";
    $i=0;
    foreach($locations as $menu) {
        $data .= '<li class="nav-'.$i.'"><a href="#menu-'.$menu->slug.'" class="current">' . $menu->name . '</a></li>';
        $i++;       
    }
    $data .= '</ul><div class="list-wrap">';
    echo $data;
    foreach($locations as $menu) {
        wp_nav_menu( array(
            'container' => false,
            'menu_class' => 'site-navigation hide',
            'echo' => true,
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'depth' => 0,
            'walker' => $walker,
            'menu'=>$menu
        ));
        
    }  
    $data = '</div>';
    echo $data;  
}
function jumppage_display_menu_post_side_meta_box_content() {
    wp_enqueue_script("jumppage_admin_js");
    wp_enqueue_script("jumppage_admin_js_menu");
    $pages = get_posts(array("post_type"=>"post"));
    $data = "<select class='anps_all_pages' name='anps_all_pages'>";
    $data .= "<option value='0'>*** Select post ***</option>";
    foreach ($pages as $page) {
        $data .= "<option value='$page->ID' data-url='".get_admin_url()."post.php?post=".$page->ID."&action=edit"."'>".esc_html($page->post_title)."</option>";
    }
    $data .= "</select>";
    echo $data;
}
function jumppage_display_menu_side_meta_box_content() {
    wp_enqueue_script("jumppage_admin_js");
    wp_enqueue_script("jumppage_admin_js_menu");
    $pages = get_pages();
    $data = "<select class='anps_all_pages' name='anps_all_pages'>";
    $data .= "<option value='0'>*** Select page ***</option>";
    foreach ($pages as $page) {
        $data .= "<option value='$page->ID' data-url='".get_admin_url()."post.php?post=".$page->ID."&action=edit"."'>".esc_html($page->post_title)."</option>";
    }
    $data .= "</select>";
    echo $data;
}
class jumppage_description_walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) { 
        $menu_separate = get_post_meta($item->object_id, $key ='anps_menu_separate', $single = true); 
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';
        if($item->object=="page") {
            $item->url = get_admin_url()."post.php?post=".$item->object_id."&action=edit";
        } else {
            $item->url = "";
        }
        $output .= $indent . '<li' . $value . $class_names .'>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url       ) .'"' : '';

        $append = "";
        $prepend = "";

        if($depth==0) {
            $description = $append = $prepend = "";
        }
        $locations = get_theme_mod('nav_menu_locations');

        $item_output = "";
        $item_output .= $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
        $item_output .= '</a>';
        $item_output .= $args->link_after;
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth = 0, $args, $args, $current_object_id = 0 );
    }
}