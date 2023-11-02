<?php
/* start function For Display site Details in Admin*/
if (!function_exists("extra_site_info_page")) {
    function extra_site_info_page()
    {
        $blog_name = get_bloginfo('name');
        $admin = admin_url();
        $admin_mail = get_bloginfo('admin_email');
        $post_count = wp_count_posts();
        $published_posts = $post_count->publish;
        $trash_posts = $post_count->trash;
        $draft_posts = $post_count->draft;
        $page_count = wp_count_posts('page');
        $total_pages = $page_count->publish;
        $draft_pages = $page_count->draft;
        $trash_pages = $page_count->trash;
        $usercount = count_users();
        global $current_user;
        wp_get_current_user();
        $username = $current_user->user_login;
        $urlparts = parse_url(home_url());
        $domain = $urlparts['host'];
        $allPlugins = get_plugins();
        $activePlugins = get_option('active_plugins');
        $total_active_plugin = count($activePlugins);
        ?>
        <h2>Site Details</h2>  
        <table border="1" cellspacing="0" cellpadding="5">  
            <tr>
                <th>Blog Name</th>
                <td><?php echo $blog_name; ?></td>
            </tr>
            <tr>
                <th>Domain Name</th>
                <td><?php echo $domain; ?></td>
            </tr>
            <tr>
                <th>Admin</th>
                <td><?php echo $admin; ?></td>
            </tr>
            <tr>
                <th>Admin Email</th>
                <td><?php echo $admin_mail; ?></td>
            </tr>
            <tr>
                <th>Number of Publish Posts</th>
                <td>
                    <?php
                    if ($post_count) {
                        echo $published_posts;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Number of Draft posts</th>
                <td>
                    <?php echo $draft_posts; ?>
                </td>
            </tr>
            <tr>
                <th>Number of Publish Pages</th>
                <td>
                    <?php echo $total_pages; ?>
                </td>
            </tr>
            <tr>
                <th>Number of Draft Pages</th>
                <td>
                    <?php echo $draft_pages; ?>
                </td>
            </tr>
            <tr>
                <th>Number of Trash Pages</th>
                <td>
                    <?php echo $trash_pages; ?>
                </td>
            </tr>
            <tr>
                <th>UserName</th>
                <td>
                    <?php echo $username; ?>
                </td>
            </tr>
            <tr>
                <th>Number of Active Plugin</th>
                <td>
                    <?php echo $total_active_plugin; ?>
                </td>
            </tr>
            </table>
        <!--***********************Wordpress Details*************************** -->
        <h2>Wordpress Details</h2>
        <?php
        $wp_version = apply_filters('update_footer', '');
        $structure = get_option('permalink_structure');
        $url = site_url();
        $comment_status = get_default_comment_status();
        $environment_type = wp_get_environment_type();
        $user_lang = get_bloginfo("language");
        $site_lang = get_locale();
        $result = $usercount['total_users'];
        $timezone = wp_timezone_string();
        ?>
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Site URL</th>
                <td><?php echo $url; ?></td>
            </tr>
            <tr>
                <th>Home URL</th>
                <td><?php echo $url; ?></td>
            </tr>
            <tr>
                <th>Wordpress Version</th>
                <td>
                    <?php
                    echo $wp_version;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Permalink structure	</th>
                <td><?php echo $structure; ?></td>
            </tr>
            <tr>
                <th>Default comment status</th>
                <td><?php echo $comment_status; ?></td>
            </tr>
            <tr>
                <th>Environment type</th>
                <td><?php echo $environment_type; ?></td>
            </tr>
            <tr>
                <th>User count</th>
                <td><?php echo $result; ?></td>
            </tr>
            <tr>
                <th>User Language</th>
                <td><?php echo $user_lang; ?></td>
            </tr>
            <tr>
                <th>Site Language</th>
                <td><?php echo $site_lang; ?></td>
            </tr>
            <tr>
                <th>Timezone</th>
                <td><?php echo $timezone; ?></td>
            </tr>
        </table>
     <!--*********************** Active Theme Details *************************** -->
    <?php
            $theme = wp_get_theme();
            $theme_version = $theme->get('Version');
            $author_site = esc_html($theme->get('AuthorURI'));
            $text_domain = esc_html($theme->get('TextDomain'));
            $theme_descri = esc_html($theme->get('Description'));
            $author_name = $theme->Author;
            $dir_loc = get_stylesheet_directory();
            ?>
        <h2>Active Theme Details</h2>  
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Name</th>
                <td><?php echo $theme; ?></td>
            </tr>
            <tr>
                <th>Version</th>
                <td><?php echo $theme_version; ?></td>
            </tr>
            <tr>
                <th>Author website</th>
                <td><?php echo $author_site; ?></td>
            </tr>
            <tr>
                <th>Theme TextDomain</th>
                <td><?php echo $text_domain; ?></td>
            </tr>
            <tr>
                <th>Theme description</th>
                <td><?php echo $theme_descri; ?></td>
            </tr>
            <tr>
                <th>Author</th>
                <td><?php echo $author_name; ?></td>
            </tr>
            <tr>
                <th>Theme directory location</th>
                <td><?php echo $dir_loc; ?></td>
            </tr>
        </table>
     <!--*********************** Active Plugin Details *************************** -->
        <h2>Active PLugin Details</h2>  
        <table border="1" cellspacing="0" cellpadding="5">
                <?php
                foreach ($allPlugins as $key => $value) {
                    if (in_array($key, $activePlugins)) { // display active only
                        echo '<tr>';
                        echo "<td>{$value['Name']}</td>";
                        echo "<td>Version : {$value['Version']}</td>";
                        $slug = explode('/', $key)[0]; // get active plugin's slug
                        echo "<td>Author : {$value['Author']}</td>";
                        echo "<td>Description : {$value['Description']}</td>";
                        echo '</tr>';
                    }
                }
                ?>
        </table>
     <!--*********************** Server Details *************************** -->
        <?php
        $php_version = phpversion();
        $sapi = php_sapi_name();
        $up_max_size = ini_get("upload_max_filesize");
        $post_max_size = ini_get('post_max_size');
        $memory_limit = ini_get('memory_limit');
        $execution_time = ini_get('max_execution_time');
        $max_input = ini_get('max_input_time');
        $admin_memory_limit = wp_raise_memory_limit('admin');
        $curl_version = curl_version();
        ?>
        <h2>Server Details</h2>
        <table border="1" cellspacing="0" cellpadding="5">  
            <tr>
                <th>PHP Version</th>
                <td><?php echo $php_version; ?></td>
            </tr>
            <tr>
                <th>PHP SAPI</th>
                <td><?php echo $sapi; ?></td>
            </tr>
            <tr>
                <th>Upload max filesize	</th>
                <td><?php echo $up_max_size; ?></td>
            </tr>
            <tr>
                <th>PHP post max size</th>
                <td><?php echo $post_max_size; ?></td>
            </tr>
            <tr>
                <th>PHP memory limit</th>
                <td><?php echo $memory_limit; ?></td>
            </tr>
            <tr>
                <th>PHP time limit	</th>
                <td><?php echo $execution_time; ?></td>
            </tr>
            <tr>
                <th>Max input time	</th>
                <td><?php echo $max_input; ?></td>
            </tr>
            <tr>
                <th>PHP memory limit (only for admin screens)</th>
                <td><?php echo $admin_memory_limit; ?></td>
            </tr>
            <tr>
                <th>cURL version</th>
                <td><?php echo $curl_version["version"]; ?></td>
            </tr>
        </table>
        <h2>Database Details</h2>  
        <table border="1" cellspacing="0" cellpadding="5">
            <?php
            global $wpdb;
            $db_name = $wpdb->dbname;
            ?>
            <tr>
                <th>Database Name </th>
                <td><?php echo $db_name; ?></td>
            </tr>
            <tr>
                <th>Table Prefix</th>
                <td><?php echo $wpdb->prefix; ?></td>
            </tr>
            <tr>
                <th>Database User</th>
                <td><?php echo $wpdb->dbuser; ?></td>
            </tr>
            <tr>
                <th>Database Password</th>
                <td><?php echo $wpdb->dbpassword; ?></td>
            </tr>
            <tr>
                <th>Database Host</th>
                <td><?php echo $wpdb->dbhost; ?></td>
            </tr>
            <tr>
                <th>Database charset</th>
                <td><?php echo $wpdb->charset; ?></td>
            </tr>
            <tr>
                <th>Database collation</th>
                <td><?php echo $wpdb->collate; ?></td>
            </tr>
        </table>
        <h2>Directories and Sizes</h2>  
        <table border="1" cellspacing="0" cellpadding="5">
            <?php
            $plugin_url = get_home_path();
            $theme_directory = get_theme_root();
            $plugin_directory_path = plugin_dir_path(__DIR__);
            $upload_directory_location = wp_upload_dir();

            $wp_directory_size = get_dirsize($plugin_url);
            $plugin_directory_size = get_dirsize($plugin_directory_path);
            $upload_directory_size = get_dirsize($upload_directory_location['basedir']);
            $theme_directory_size = get_dirsize($theme_directory);
            $total_size = $wp_directory_size + $theme_directory_size + $plugin_directory_size + $upload_directory_size;
            ?>
            <tr>
                <th>WordPress directory location</th>
                <td><?php echo $plugin_url; ?></td>
            </tr>
            <tr>
                <th>WordPress directory size</th>
                <td><?php echo number_format($wp_directory_size / (1024 * 1024), 1) . ' MB'; ?></td>
            </tr>
            <tr>
                <th>Themes directory location</th>
                <td><?php echo $theme_directory; ?></td>
            </tr>
            <tr>
                <th>Themes directory size</th>
                <td><?php echo number_format($theme_directory_size / (1024 * 1024), 1) . ' MB'; ?></td>
            </tr>
            <tr>
                <th>Plugins directory location</th>
                <td><?php echo $plugin_directory_path; ?></td>
            </tr>
            <tr>
                <th>Plugins directory size</th>
                <td><?php echo number_format($plugin_directory_size / (1024 * 1024), 1) . ' MB'; ?></td>
            </tr>
            <tr>
                <th>Uploads directory location</th>
                <td><?php echo $upload_directory_location['basedir']; ?></td>
            </tr>
            <tr>
                <th>Uploads directory size</th>
                <td><?php echo number_format($upload_directory_size / (1024 * 1024), 1) . ' MB'; ?></td>
            </tr>
            
            <tr>
                <th>Total installation size</th>
                <td><?php echo number_format($total_size / (1024 * 1024), 1) . ' MB'; ?></td>
            </tr>
        </table>
        <h2>WordPress Constants</h2>  
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>ABSPATH</th>
                <td><?php echo ABSPATH; ?></td>
            </tr>
            <tr>
                <th>WP_CONTENT_DIR</th>
                <td><?php echo WP_CONTENT_DIR; ?></td>
            </tr>
            <tr>
                <th>WP_PLUGIN_DIR</th>
                <td><?php echo WP_PLUGIN_DIR; ?></td>
            </tr>
            <tr>
                <th>WP_MEMORY_LIMIT</th>
                <td><?php echo WP_MEMORY_LIMIT; ?></td>
            </tr>
            <tr>
                <th>WP_MAX_MEMORY_LIMIT	</th>
                <td><?php echo WP_MAX_MEMORY_LIMIT; ?></td>
            </tr>
            <tr>
                <th>WP_DEBUG</th>
                <td><?php echo WP_DEBUG; ?></td>
            </tr>
            <tr>
                <th>WP_DEBUG_DISPLAY</th>
                <td><?php echo WP_DEBUG_DISPLAY; ?></td>
            </tr>   
            <tr>
                <th>DB_CHARSET</th>
                <td><?php echo DB_CHARSET; ?></td>
            </tr>
            <tr>
                <th>Hello</th>
                <td>Hello</td>
            </tr>
        </table>
        <br>
        <form method="POST">
            <input type="button" name="generate_pdf" value="Generate PDF" class="pdf-generate">
        </form>
    <?php

    }
}
/* End function For Display site Details in Admin*/

?>
