<?php
    add_menu_page( $page_title,$menu_title, $capability, $menu_slug, $function,$icon_url, $position );
    add_action( 'admin_init', 'update_extra_post_info' );
    if( !function_exists("update_extra_post_info") ) {
        function update_extra_post_info() {
            register_setting( 'extra-post-info-settings', 'extra_post_info' );
        }
    }
?>