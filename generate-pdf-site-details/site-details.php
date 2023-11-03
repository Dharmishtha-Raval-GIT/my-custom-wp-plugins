<?php
/* 
Plugin name: Generate PDF of Site Details
Description: A Simple Wordpress Plugin to Display Site Details in PDF Format. 
Author: Dharmishtha Raval
Version: 0.1 
*/

/* start Register scripts for site details plugin */
function utm_user_scripts()
{
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('style', $plugin_url . "/css/style.css", array(), true);
    wp_enqueue_script('html2pdf', $plugin_url . "/js/html2pdf.js", array(), true);
    wp_localize_script('html2pdf', 'jsData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ]);
    wp_enqueue_script('jquery-min', $plugin_url . "/js/jquery.min.js", array(), true);
}
add_action('admin_print_styles', 'utm_user_scripts');
add_action('wp_enqueue_scripts', 'utm_user_scripts');

add_action('wp_enqueue_scripts', 'wp_enqueue_style');
add_action('wp_enqueue_scripts', 'wp_enqueue_script');

/* End Register scripts for site details plugin */

/* Start Site Information function */
if (!function_exists("extra_site_info_menu")) {
    function extra_site_info_menu()
    {
        $page_title = 'WordPress SiteInfo';
        $menu_title = 'SiteInfo';
        $capability = 'manage_options';
        $menu_slug = 'siteinfo';
        $function = 'extra_site_info_page';
        $icon_url = 'dashicons-plugins-checked';
        $position = 25;
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            $position
        );
    }
    add_action('admin_menu', 'extra_site_info_menu');
}
include('display_details.php');

function get_data()
{

    $blog_name = get_bloginfo('name');
    $admin = admin_url();
    $admin_mail = get_bloginfo('admin_email');
    $post_count = wp_count_posts();
    $page_count = wp_count_posts('page');
    $published_posts = $post_count->publish;
    $trash_posts = $post_count->trash;
    $draft_posts = $post_count->draft;
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

    /* Wordpress Details */
    $wp_version = apply_filters('update_footer', '');
    $structure = get_option('permalink_structure');
    $url = site_url();
    $comment_status = get_default_comment_status();
    $environment_type = wp_get_environment_type();
    $user_lang = get_bloginfo("language");
    $site_lang = get_locale();
    $result = $usercount['total_users'];
    $timezone = wp_timezone_string();

    /* Active Theme Details */
    $theme = wp_get_theme();
    $theme_version = $theme->get('Version');
    $author_site = esc_html($theme->get('AuthorURI'));
    $text_domain = esc_html($theme->get('TextDomain'));
    $theme_descri = esc_html($theme->get('Description'));
    $author_name = $theme->Author;
    $dir_loc = get_stylesheet_directory();

    /* Server Details */
    $php_version = phpversion();
    $sapi = php_sapi_name();
    $up_max_size = ini_get("upload_max_filesize");
    $post_max_size = ini_get('post_max_size');
    $memory_limit = ini_get('memory_limit');
    $execution_time = ini_get('max_execution_time');
    $max_input = ini_get('max_input_time');
    $admin_memory_limit = wp_raise_memory_limit('admin');
    $curl_version = curl_version();

    global $wpdb;
    $db_name = $wpdb->dbname;

    /* Directories and Sizes */
    $plugin_url = ABSPATH;
    $theme_directory = get_theme_root();
    $plugin_directory_path = WP_PLUGIN_DIR;
    $upload_directory_location = wp_upload_dir();

    $wp_directory_size = get_dirsize($plugin_url);
    $plugin_directory_size = get_dirsize($plugin_directory_path);
    $upload_directory_size = get_dirsize($upload_directory_location['basedir']);
    $theme_directory_size = get_dirsize($theme_directory);
    $total_size = $wp_directory_size + $theme_directory_size + $plugin_directory_size + $upload_directory_size;

    foreach ($allPlugins as $key => $value) {
        if (in_array($key, $activePlugins)) {
        }
    }

    /* start Generate Dynamic PDF using TCPDF Library */
    include plugin_dir_path(__FILE__) . "tcpdf/tcpdf.php";
    $pdf_2 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf_2 = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf_2->SetCreator(PDF_CREATOR);
    $pdf_2->SetTitle("Exported site details data to PDF");
    $pdf_2->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf_2->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf_2->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf_2->SetDefaultMonospacedFont('helvetica');
    $pdf_2->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf_2->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    $pdf_2->setPrintHeader(false);
    $pdf_2->setPrintFooter(false);
    $pdf_2->SetAutoPageBreak(TRUE, 10);
    $pdf_2->SetFont('helvetica', '', 12);
    $pdf_2->AddPage();
    date_default_timezone_set("Asia/Calcutta");
    $content_2 = '';
    $content_2 .= '
    <b><h1><center>' . $blog_name . ' system reports exported on ' . date("d-m-Y") . '_' . date("h:i A") . '</center></h1></b>
    <h2>Site Details</h2>
            <table border="1" cellspacing="0" cellpadding="5">  
            <tr>
                <th>Blog Name</th>
                <td>' . $blog_name . '</td>
            </tr>
            <tr>
                <th>Domain Name</th>
                <td>' . $domain . '</td>
            </tr>
            <tr>
                <th>Admin</th>
                <td>' . $admin . '</td>
            </tr>
            <tr>
                <th>Admin Email</th>
                <td>' . $admin_mail . '</td>
            </tr>
            <tr>
                <th>Number of Admin</th>
                <td>' . $result . '</td>
            </tr>
            <tr>
            <th>Number of Active Plugin</th>
            <td>' . $total_active_plugin . '</td>
            </tr>
        </table>
        <h2>POST and PAGES Details</h2>
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Post Name</th>
                <th>Publish</th>
                <th>Draft</th>
                <th>Trash</th>
            </tr>
            <tr>
                <td>Page</td>
                <td>' . $total_pages . '</td>
                <td>' . $draft_pages . '</td>
                <td>' . $trash_pages . '</td>
            </tr>
            <tr>
                <td>Post</td>
                <td>' . $published_posts . '</td>
                <td>' . $draft_posts . '</td>
                <td>' . $trash_posts . '</td>
            </tr>
        </table>
        <h2>Wordpress Details</h2>
        <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>Site URL</th>
            <td>' . $url . '</td>
        </tr>
        <tr>
            <th>Home URL</th>
            <td>' . $url . '</td>
        </tr>
        <tr>
            <th>Wordpress Version</th>
            <td>' . $wp_version . '</td>
        </tr>
        <tr>
            <th>Permalink structure	</th>
            <td>' . $structure . '</td>
        </tr>
        <tr>
            <th>Default comment status</th>
            <td>' . $comment_status . '</td>
        </tr>
        <tr>
            <th>Environment type</th>
            <td>' . $environment_type . '</td>
        </tr>
        <tr>
            <th>User count</th>
            <td>' . $result . '</td>
        </tr>
        <tr>
            <th>User Language</th>
            <td>' . $user_lang . '</td>
        </tr>
        <tr>
            <th>Site Language</th>
            <td>' . $site_lang . '</td>
        </tr>
        <tr>
            <th>Timezone</th>
            <td>' . $timezone . '</td>
        </tr>
    </table>
    
    <h2>Active Theme Details</h2>  
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>Name</th>
            <td>' . $theme . '</td>
        </tr>
        <tr>
            <th>Version</th>
            <td>' . $theme_version . '</td>
        </tr>
        <tr>
            <th>Author website</th>
            <td>' . $author_site . '</td>
        </tr>
        <tr>
            <th>Theme TextDomain</th>
            <td>' . $text_domain . '</td>
        </tr>
        <tr>
            <th>Theme description</th>
            <td>' . $theme_descri . '</td>
        </tr>
        <tr>
            <th>Author</th>
            <td>' . $author_name . '</td>
        </tr>
        <tr>
            <th>Theme directory location</th>
            <td>' . $dir_loc . '</td>
        </tr>
    </table>
    
    <h2>Active Plugin Details</h2>  
    <table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Name</th>
        <th>Verion</th>
        <th>Auhor</th>
        <th>Description</th>
    </tr>';
    foreach ($allPlugins as $key => $value) {
        if (in_array($key, $activePlugins)) { // display active only
            $content_2 .= '
                <tr>
                <td>' . $value['Name'] . '</td>
                <td>' . $value['Version'] . '</td>
                <td>' . $value['Author'] . '</td>
                <td>' . $value['Description'] . '</td>
            </tr>';
        }
    }
    $content_2 .= '</table>

        <h2>Server Details</h2>
        <table border="1" cellspacing="0" cellpadding="5">  
            <tr>
                <th>PHP Version</th>
                <td>' . $php_version . '</td>
            </tr>
            <tr>
                <th>PHP SAPI</th>
                <td>' . $sapi . '</td>
            </tr>
            <tr>
                <th>Upload max filesize	</th>
                <td>' . $up_max_size . '</td>
            </tr>
            <tr>
                <th>PHP post max size</th>
                <td>' . $post_max_size . '</td>
            </tr>
            <tr>
                <th>PHP memory limit</th>
                <td>' . $memory_limit . '</td>
            </tr>
            <tr>
                <th>PHP time limit	</th>
                <td>' . $execution_time . '</td>
            </tr>
            <tr>
                <th>Max input time	</th>
                <td>' . $max_input . '</td>
            </tr>
            <tr>
                <th>PHP memory limit (only for admin screens)</th>
                <td>' . $admin_memory_limit . '</td>
            </tr>
            <tr>
                <th>cURL version</th>
                <td>' . $curl_version["version"] . '</td>
            </tr>
        </table>
    <h2>Database Details</h2>  
    <table border="1" cellspacing="0" cellpadding="5">
    <tr>
            <th>Database Name </th>
            <td>' . $db_name . '</td>
        </tr>
        <tr>
            <th>Table Prefix</th>
            <td>' . $wpdb->prefix . '</td>
        </tr>
        <tr>
            <th>Database User</th>
            <td>' . $wpdb->dbuser . '</td>
        </tr>
        <tr>
            <th>Database Password</th>
            <td>' . $wpdb->dbpassword . '</td>
        </tr>
        <tr>
            <th>Database Host</th>
            <td>' . $wpdb->dbhost . '</td>
        </tr>
        <tr>
            <th>Database charset</th>
            <td>' . $wpdb->charset . '</td>
        </tr>
        <tr>
            <th>Database collation</th>
            <td>' . $wpdb->collate . '</td>
        </tr>
    </table>
    <h2>Directories and Sizes</h2>  
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>WordPress directory location</th>
            <td>' . $plugin_url . '</td>
        </tr>
        <tr>
            <th>WordPress directory size</th>
            <td>' . number_format($wp_directory_size / (1024 * 1024), 1) . ' MB' . '</td>
        </tr>
        <tr>
            <th>Themes directory location</th>
            <td>' . $theme_directory . '</td>
        </tr>
        <tr>
            <th>Themes directory size</th>
            <td>' . number_format($theme_directory_size / (1024 * 1024), 1) . ' MB' . '</td>
        </tr>
        <tr>
            <th>Plugins directory location</th>
            <td>' . $plugin_directory_path . '</td>
        </tr>
        <tr>
            <th>Plugins directory size</th>
            <td>' . number_format($plugin_directory_size / (1024 * 1024), 1) . ' MB' . '</td>
        </tr>
        <tr>
            <th>Uploads directory location</th>
            <td>' . $upload_directory_location['basedir'] . '</td>
        </tr>
        <tr>
            <th>Uploads directory size</th>
            <td>' . number_format($upload_directory_size / (1024 * 1024), 1) . ' MB' . '</td>
        </tr>
        
        <tr>
            <th>Total installation size</th>
            <td>' . number_format($total_size / (1024 * 1024), 1) . ' MB' . '</td>
        </tr>
    </table>
    <h2>WordPress Constants</h2>  
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>ABSPATH</th>
            <td>' . ABSPATH . '</td>
        </tr>
        <tr>
            <th>WP_CONTENT_DIR</th>
            <td>' . WP_CONTENT_DIR . '</td>
        </tr>
        <tr>
            <th>WP_PLUGIN_DIR</th>
            <td>' . WP_PLUGIN_DIR . '</td>
        </tr>
        <tr>
            <th>WP_MEMORY_LIMIT</th>
            <td>' . WP_MEMORY_LIMIT . '</td>
        </tr>
        <tr>
            <th>WP_MAX_MEMORY_LIMIT	</th>
            <td>' . WP_MAX_MEMORY_LIMIT . '</td>
        </tr>
        <tr>
            <th>WP_DEBUG</th>';
    if (WP_DEBUG) {
        $content_2 .= '<td>true</td>';
    } else {
        $content_2 .= '<td>False</td>';
    }
    $content_2 .= '</tr>
        <tr>
            <th>WP_DEBUG_DISPLAY</th>
            <td>' . WP_DEBUG_DISPLAY . '</td>
        </tr>
        <tr>
            <th>DB_CHARSET</th>
            <td>' . DB_CHARSET . '</td>
        </tr>
    </table>';
    $pdf_2->writeHTML($content_2);
    date_default_timezone_set("Asia/Calcutta");
    // $filename= date("H-i-s").'_'.$blog_name.'_'.date("d-m-Y").'.pdf';
    $pdf_folder = ABSPATH . 'pdf';
    $folder = wp_mkdir_p($pdf_folder);
    $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($blog_name . '_' . date("d-m-Y") . " time " . date("h-i A"))) . '.pdf';
    echo $filename;
    $filelocation = ABSPATH . "pdf";
    // echo $filelocation; die;
    $fileNL = $filelocation . "\\" . $filename;
    $pdf_2->Output($fileNL, 'F');
    $pdf_2->Output($filename, 'I');
    wp_die();
    /* End Generate Dynamic PDF using TCPDF Library */
}
add_action('wp_ajax_nopriv_get_data', 'get_data');
add_action('wp_ajax_get_data', 'get_data');

/* End Site Information function */
