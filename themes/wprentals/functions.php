<?php
require_once get_theme_file_path('/libs/css_js_include.php');
require_once get_theme_file_path('/libs/css_js_include_helper.php');
require_once get_theme_file_path('/libs/plugins.php');
require_once get_theme_file_path('/libs/help_functions.php');
require_once get_theme_file_path('/libs/pin_management.php');
require_once get_theme_file_path('/libs/ajax_functions.php');
require_once get_theme_file_path('/libs/ajax_functions_edit.php');
require_once get_theme_file_path('/libs/ajax_functions_booking.php');
require_once get_theme_file_path('/libs/ajax_upload.php');
require_once get_theme_file_path('/libs/3rdparty.php');
require_once get_theme_file_path('/libs/theme-setup.php');
require_once get_theme_file_path('/libs/general-settings.php');
require_once get_theme_file_path('/libs/listing_functions.php');
require_once get_theme_file_path('/libs/theme-slider.php');
require_once get_theme_file_path('/libs/events.php');
require_once get_theme_file_path('/libs/icalendar.php');
require_once get_theme_file_path('/libs/reviews.php');
require_once get_theme_file_path('/libs/megamenu.php');
require_once get_theme_file_path('/libs/search_functions.php');
require_once get_theme_file_path('/word_remove.php');
require_once get_theme_file_path('/libs/dashboard_widgets.php');
require_once get_theme_file_path('/world_manage.php');
require_once get_theme_file_path('/libs/search_functions2.php');
require_once get_theme_file_path('/libs/search_functions3.php');
require_once get_theme_file_path('/libs/search_functions4.php');
require_once get_theme_file_path('/libs/theme-import.php');
require_once get_theme_file_path('/libs/multiple_sidebars.php');
require_once get_theme_file_path('/libs/stats.php');
require_once get_theme_file_path('/libs/global_functions.php');
require_once get_theme_file_path('/libs/footer_filter_functions.php');

require_once get_theme_file_path('/dashboard/dashboard-functions.php');

require_once get_theme_file_path('/classes/rentalsSearch.php');
require_once get_theme_file_path('/classes/search_settings.php');
require_once get_theme_file_path('/libs/search_functions_elementor.php');

load_theme_textdomain('wprentals', get_template_directory() . '/languages');

define('ULTIMATE_NO_EDIT_PAGE_NOTICE', true);
define('ULTIMATE_NO_PLUGIN_PAGE_NOTICE', true);
define('CLUBLINK', 'rentalsclub.org');
define('CLUBLINKSSL', 'https');
# Disable check updates -
define('BSF_6892199_CHECK_UPDATES',false);

# Disable license registration nag -
define('BSF_6892199_NAG', false);

global $search_object;

/** REMOVE REDUX MESSAGES */
function wpestate_remove_redux_messages() {
	if(class_exists('ReduxFramework')){
		remove_action( 'admin_notices', array( get_redux_instance('theme_options'), '_admin_notices' ), 99);
	}
}

/** HOOK TO REMOVE REDUX MESSAGES */
add_action('init', 'wpestate_remove_redux_messages');


function wpestate_admin_notice() {
    global $pagenow;
    global $typenow;
    global $post;
    $current_user   =   wp_get_current_user();
   
    
    $page_template='';
    if(isset($post->ID)){
       $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
    }

    wpestate_show_license_form();

    if ($current_user->has_cap('create_users') ) {
        add_action('admin_notices', 'wpestate_admin_display_verifications');
    }


    if (!empty($_GET['post'])) {
        $allowed_html   =   array();
        $post           =   get_post(wp_kses($_GET['post'],$allowed_html));
        $typenow        =   $post->post_type;
    }

    $wpestate_notices   =  get_option('wp_estate_notices');





    if( !is_array($wpestate_notices) ||
        !isset($wpestate_notices['wp_estate_cache_notice']) ||
        ( isset($wpestate_notices['wp_estate_cache_notice']) && $wpestate_notices['wp_estate_cache_notice']!='yes')  ){
        print '<div  id ="setting-error-wprentals-cache" data-notice-type="wp_estate_cache_notice" data-dismissible="disable-done-notice-forever" class="wpestate_notices updated settings-error notice is-dismissible">
            <p>'.esc_html__( 'For better speed results, the theme offers a built-in caching system for properties and categories.Because of that, properties or categories may not appear immediately on your site. Use the Clear Wp Rentals Cache button from the admin bar to see the changes made instantly. Automatic updates happen every 4 hours.','wprentals').'</p>
        </div>';
    }

    if( esc_html( wprentals_get_option('wp_estate_api_key') =='' ) ){
        if( !is_array($wpestate_notices) ||
                !isset($wpestate_notices['wp_estate_api_key']) ||
                ( isset($wpestate_notices['wp_estate_api_key']) && $wpestate_notices['wp_estate_api_key']!='yes')  ){
            print '<div data-notice-type="wp_estate_api_key"  class="wpestate_notices updated settings-error error notice is-dismissible">
                <p>'.esc_html__( 'Google Maps will NOT WORK without a correct Api Key. Get one from ','wprentals').'<a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">'.esc_html__('here','wprentals').'</a></p>
            </div>';
        }
    }


    if ( WP_MEMORY_LIMIT < 96 ) {
          if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_memory_notice']) ||
            ( isset($wpestate_notices['wp_estate_memory_notice']) && $wpestate_notices['wp_estate_memory_notice']!='yes')  ){
            print '<div data-notice-type="wp_estate_memory_notice"  class="wpestate_notices updated settings-error error notice is-dismissible">
            <p>'.esc_html__( 'Wordpress Memory Limit is set to ', 'wprentals' ).' '.WP_MEMORY_LIMIT.' '.esc_html__( 'Recommended memory limit should be at least 96MB. Please refer to : ','wprentals').'<a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">'.esc_html__('Increasing memory allocated to PHP','wprentals').'</a></p>
        </div>';
        }
    }

    if (!defined('PHP_VERSION_ID')) {
        $version = explode('.', PHP_VERSION);
        define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
    }

    if(PHP_VERSION_ID<50600){
        if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_php_version']) ||
            ( isset($wpestate_notices['wp_estate_php_version']) && $wpestate_notices['wp_estate_php_version']!='yes')  ){

            $version = explode('.', PHP_VERSION);
            print '<div data-notice-type="wp_estate_php_version"  class="wpestate_notices updated settings-error error notice is-dismissible">
            <p>'.__( 'Your PHP version is ', 'wprentals' ).' '.$version[0].'.'.$version[1].'.'.$version[2].'. We recommend upgrading the PHP version to at least 5.6.1. The upgrade should be done on your server by your hosting company. </p>
            </div>';
        }
    }


    if ( !extension_loaded('mbstring')) {
        if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_mb_string']) ||
            ( isset($wpestate_notices['wp_estate_mb_string']) && $wpestate_notices['wp_estate_mb_string']!='yes')  ){
                print '<div data-notice-type="wp_estate_mb_string"  class="wpestate_notices updated settings-error error notice is-dismissible">
                    <p>'.esc_html__( 'MbString extension not detected. Please contact your hosting provider in order to enable it.', 'wprentals' ).'</p>
                </div>';
        }
    }


    if (is_admin() &&   $pagenow=='post.php' && $typenow=='page' && $page_template=='property_list_half.php' ){
        $header_type    =   get_post_meta ( $post->ID, 'header_type', true);

        if ( $header_type != 5){
            if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_header_half']) ||
            ( isset($wpestate_notices['wp_estate_header_half']) && $wpestate_notices['wp_estate_header_half']!='yes')  ){

                print '<div data-notice-type="wp_estate_header_half"  class="wpestate_notices updated settings-error error notice is-dismissible">
                <p>'.esc_html__( 'Half Map Template - make sure your page has the "media header type" set as google map ', 'wprentals' ).'</p>
                </div>';
            }
        }

    }


    if (is_admin() &&   $pagenow=='post.php' && get_post_type($post->ID)=='wpestate_booking' ){
        $header_type    =   get_post_meta ( $post->ID, 'header_type', true);

        if ( $header_type != 5){
            if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_booking_notice']) ||
            ( isset($wpestate_notices['wp_estate_booking_notice']) && $wpestate_notices['wp_estate_booking_notice']!='yes')  ){

                print '<div data-notice-type="wp_estate_booking_notice"  class="wpestate_notices wp_estate_booking_notice updated settings-error error notice ">
                <p>'.esc_html__( 'Do NOT edit booking details from admin!', 'wprentals' ).'</p>
                </div>';
            }
        }

    }




     if (is_admin() &&   $pagenow=='edit-tags.php'  && $typenow=='estate_property') {

        if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_prop_slugs']) ||
            ( isset($wpestate_notices['wp_estate_prop_slugs']) && $wpestate_notices['wp_estate_prop_slugs']!='yes')  ){

            print '<div data-notice-type="wp_estate_prop_slugs"  class="wpestate_notices updated settings-error error notice is-dismissible">
                <p>'.esc_html__( 'Please do not manually change the slugs when adding new terms. If you need to edit a term name copy the new name in the slug field also.', 'wprentals' ).'</p>
            </div>';
        }
    }



    if (is_admin() &&  ( $pagenow=='post-new.php' || $pagenow=='post.php') && $typenow=='estate_property') {

        if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_add_prop']) ||
            ( isset($wpestate_notices['wp_estate_add_prop']) && $wpestate_notices['wp_estate_add_prop']!='yes')  ){

            print '<div data-notice-type="wp_estate_add_prop"  class="wpestate_notices updated settings-error error notice is-dismissible">
                <p>'.esc_html__( 'Please add properties from front end interface using a user account with subscriber level registered in front end.', 'wprentals' ).'</p>
            </div>';
        }

    }

    if(wpestate_get_template_link('ical.php')==esc_url( home_url('/') )){

         if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_ical_feed']) ||
            ( isset($wpestate_notices['wp_estate_ical_feed']) && $wpestate_notices['wp_estate_ical_feed']!='yes')  ){

            print '<div data-notice-type="wp_estate_ical_feed"  class="wpestate_notices updated settings-error error notice is-dismissible">
                <p>'.esc_html__( 'You need to create a page with the template ICAL FEED (if you want to use icalendar export/import feature)', 'wprentals' ).'</p>
            </div>';
        }
    }



    if(wpestate_get_template_link('user_dashboard_allinone.php')==esc_url( home_url('/') )){
        if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_allinone']) ||
            ( isset($wpestate_notices['wp_estate_allinone']) && $wpestate_notices['wp_estate_allinone']!='yes')  ){

            print '<div data-notice-type="wp_estate_allinone"  class="wpestate_notices updated settings-error error notice is-dismissible">
                <p>'.esc_html__( 'You need to create a page with the template All in one calendar (if you want to use all in one calendar feature)', 'wprentals' ).'</p>
            </div>';
        }
    }


    $current_tz= date_default_timezone_get();
    if( wpestate_isValidTimezoneId2($current_tz)!= 1 ){
        if( !is_array($wpestate_notices) ||
            !isset($wpestate_notices['wp_estate_timezone']) ||
            ( isset($wpestate_notices['wp_estate_timezone']) && $wpestate_notices['wp_estate_timezone']!='yes')  ){

            print '<div data-notice-type="wp_estate_timezone"  class="wpestate_notices updated settings-error error notice is-dismissible">
            <p>'.esc_html__( 'It looks like you may have a problem with the server date.timezone settings and may encounter errors like the one described here:', 'wprentals' ).'<a href="http://help.wprentals.org/2015/12/21/calendar-doesnt-work-calendar-issues/">http://help.wprentals.org/2015/12/21/calendar-doesnt-work-calendar-issues/</a> '.esc_html__('Please resolve these issues with your hosting provider.','wprentals').' </p>
        </div>';
        }
    }


    $ajax_nonce = wp_create_nonce( "wpestate_notice_nonce" );
    print '<input type="hidden" id="wpestate_notice_nonce" value="'.esc_html($ajax_nonce).'"/>';
}


function wpestate_isValidTimezoneId2($tzid){
    $valid = array();
    $tza = timezone_abbreviations_list();

    foreach ($tza as $zone)
        foreach ($zone as $item)
            $valid[$item['timezone_id']] = true;
    unset($valid['']);
    return !!$valid[$tzid];
}

add_action( 'admin_notices', 'wpestate_admin_notice' );

add_action('after_setup_theme', 'wp_estate_init');
if (!function_exists('wp_estate_init')):

    function wp_estate_init() {

        global $content_width;
        if (!isset($content_width)) {
            $content_width = 1800;
        }

        load_theme_textdomain('wprentals', get_template_directory() . '/languages');
        set_post_thumbnail_size(940, 198, true);
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('custom-background');
        add_theme_support("title-tag");
        add_theme_support( 'align-wide' );
        add_theme_support(
            'gutenberg',
            array( 'wide-images' => true )
        );

        add_action('widgets_init', 'wpestate_widgets_init');
        wp_oembed_add_provider('#https?://twitter.com/\#!/[a-z0-9_]{1,20}/status/\d+#i', 'https://api.twitter.com/1/statuses/oembed.json', true);
        wpestate_image_size();
       // add_filter('excerpt_length', 'wpestate_excerpt_length');
        add_filter('excerpt_more', 'wpestate_new_excerpt_more');
        add_action('tgmpa_register', 'wpestate_required_plugins');
        add_action('wp_enqueue_scripts', 'wpestate_scripts');
        add_action('admin_enqueue_scripts', 'wpestate_admin');
        update_option( 'image_default_link_type', 'file' );

        if( get_option('wprentals_convert_to_redux_ammenities','')!='yes' ){
            wpestate_convert_to_redux_framework_ammenities();
            update_option('wprentals_convert_to_redux_ammenities','yes');
        }


    }

endif; // end   wp_estate_init




///////////////////////////////////////////////////////////////////////////////////////////
/////// If admin create the menu
///////////////////////////////////////////////////////////////////////////////////////////
if (is_admin()) {
    add_action('admin_menu', 'wpestate_manage_admin_menu');
}

if (!function_exists('wpestate_manage_admin_menu')):
    function wpestate_manage_admin_menu() {
        $theme = wp_get_theme();
        add_submenu_page( $theme->get( 'Name' ),'Import Demo', 'Import Demo', 'administrator', 'themes.php?page=pt-one-click-demo-import', '',999999999);
        add_submenu_page( 'libs/theme-admin.php','Import Demo', 'Import Demo', 'administrator', 'themes.php?page=pt-one-click-demo-import', '' );
        require_once get_theme_file_path('/libs/property-admin.php');
        require_once get_theme_file_path('/libs/pin-admin.php');
        require_once get_theme_file_path('/libs/theme-admin.php');
    }
endif; // end   wpestate_manage_admin_menu




add_action( 'admin_post_wpestate_purge_cache', 'wpestate_purge_cache' );

function wpestate_purge_cache(){
    if ( isset( $_GET['action'], $_GET['_wpnonce'] ) ) {

            if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'theme_purge_cache' ) ) {
                wp_nonce_ays( '' );
            }

            wpestate_delete_cache();
            wp_redirect( wp_get_referer() );
            die();
	}
}

if ( !function_exists('wpestate_add_language_currency_cache')):
    function wpestate_add_language_currency_cache($name,$only_lang=0){
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $name.='_'. ICL_LANGUAGE_CODE;
        }

        if ( isset($_COOKIE['my_custom_curr_symbol'] )  && $only_lang==0 ){
            $name.='_'.$_COOKIE['my_custom_curr_symbol'];
        }
        return $name;
    }
endif;



//////////////////////////////////////////////////////////////////////////////////////////////
// page details : setting sidebar position etc...
//////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_page_details')):

    function wpestate_page_details($post_id) {
        $return_array = array();


        if ($post_id != '' && !is_home() && !is_tax() && !is_search()) {
            $sidebar_name   = esc_html(get_post_meta($post_id, 'sidebar_select', true));
            $sidebar_status = esc_html(get_post_meta($post_id, 'sidebar_option', true));
        } else {
            $sidebar_name   = esc_html(wprentals_get_option('wp_estate_blog_sidebar_name', ''));
            $sidebar_status = esc_html(wprentals_get_option('wp_estate_blog_sidebar', ''));
        }

        if ('' == $sidebar_name) {
            $sidebar_name = 'primary-widget-area';
        }
        if ('' == $sidebar_status) {
            $sidebar_status = 'right';
        }


        if ('left' == $sidebar_status) {
            $return_array['content_class'] = 'col-md-8 col-md-push-4 ';
            $return_array['sidebar_class'] = 'col-md-4 col-md-pull-8 ';
        } else if ($sidebar_status == 'right') {
            $return_array['content_class'] = 'col-md-8 ';
            $return_array['sidebar_class'] = 'col-md-4 ';
        } else {
            $return_array['content_class'] = 'col-md-12';
            $return_array['sidebar_class'] = 'none';
        }

        $return_array['sidebar_name'] = $sidebar_name;

        return $return_array;
    }

endif; // end   wpestate_page_details



///////////////////////////////////////////////////////////////////////////////////////////
/////// generate custom css
///////////////////////////////////////////////////////////////////////////////////////////

add_action('wp_head', 'wpestate_generate_options_css');
if (!function_exists('wpestate_generate_options_css')):

    function wpestate_generate_options_css() {
        $general_font   = esc_html(wprentals_get_option('wp_estate_general_font'));
        $custom_css     = html_entity_decode(stripslashes(wprentals_get_option('wp_estate_custom_css')));
        $color_scheme   = esc_html(wprentals_get_option('wp_estate_color_scheme'));
        $on_child_theme = esc_html ( wprentals_get_option('wp_estate_on_child_theme') );
        echo "<style type='text/css'>";


            if($on_child_theme!=1){

                if ( $general_font != '' && $general_font != 'x'){
                    require_once get_theme_file_path('/libs/custom_general_font.php');
                }
                require_once get_theme_file_path('/libs/customcss.php');
                print trim($custom_css);

            }
        echo "</style>";

    }

endif; // end   generate_options_css
///////////////////////////////////////////////////////////////////////////////////////////
///////  Display navigation to next/previous pages when applicable
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wp_estate_content_nav')) :
    function wp_estate_content_nav($html_id) {
        global $wp_query;

        if ($wp_query->max_num_pages > 1) :
            ?>
            <nav id="<?php echo esc_attr($html_id); ?>">
                <h3 class="assistive-text"><?php esc_html_e('Post navigation', 'wprentals'); ?></h3>
                <div class="nav-previous"><?php next_posts_link(esc_html__( '<span class="meta-nav">&larr;</span> Older posts', 'wprentals')); ?></div>
                <div class="nav-next"><?php previous_posts_link(esc_html__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wprentals')); ?></div>
            </nav><!-- #nav-above -->
            <?php
        endif;
    }

endif; // wpestate_content_nav

///////////////////////////////////////////////////////////////////////////////////////////
///////  Comments
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_comment')) :

    function wpestate_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                ?>
                <li class="post pingback">
                    <p><?php esc_html_e('Pingback:', 'wprentals'); ?> <?php comment_author_link(); ?><?php edit_comment_link(esc_html__( 'Edit', 'wprentals'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                ?>


        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

            <?php
                $avatar =esc_url( wpestate_get_avatar_url(get_avatar($comment, 55)));
                print '<div class="blog_author_image singlepage" style="background-image: url(' . esc_url($avatar) . ');">';
                print '</div>';
                ?>

                <div id="comment-<?php comment_ID(); ?>" class="comment">
                <?php edit_comment_link(esc_html__( 'Edit', 'wprentals'), '<span class="edit-link">', '</span>'); ?>
                    <div class="comment-meta">
                        <div class="comment-author vcard">
                        <?php
                        print '<div class="comment_name">' . get_comment_author_link() . '</div>';
                        print '<span class="comment_date">' . esc_html__( ' on ', 'wprentals') . ' ' . get_comment_date() . '</span>';
                        ?>
                        </div><!-- .comment-author .vcard -->

                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'wprentals'); ?></em>
                    <br />
                <?php endif; ?>

                </div>

                <div class="comment-content">
                <?php comment_text(); ?>

                <?php comment_reply_link(array_merge($args, array('reply_text' => '<i class="fas fa-reply"></i> ' . esc_html__( 'Reply', 'wprentals'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>

            </div><!-- #comment-## -->
            <?php
            break;
        endswitch;
    }

endif; // ends check for  wpestate_comment



if (!current_user_can('activate_plugins')) {

    if (!function_exists('wpestate_admin_bar_render')):
        function wpestate_admin_bar_render() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('edit-profile', 'user-actions');
        }
    endif;

    add_action('wp_before_admin_bar_render', 'wpestate_admin_bar_render');

    add_action('admin_init', 'wpestate_stop_access_profile');
    if (!function_exists('wpestate_stop_access_profile')):
        function wpestate_stop_access_profile() {
            global $pagenow;

            if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE === true) {
                wp_die(esc_html__( 'Please edit your profile page from site interface.', 'wprentals'));
            }

            if ($pagenow == 'user-edit.php') {
                wp_die(esc_html__( 'Please edit your profile page from site interface.', 'wprentals'));
            }
        }
    endif; // end   wpestate_stop_access_profile
}// end user can activate_plugins


///////////////////////////////////////////////////////////////////////////////////////////
// get attachment info
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_attachment')):
    function wpestate_get_attachment($attachment_id) {

        $attachment = get_post($attachment_id);
        return array(
            'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => esc_url( get_permalink($attachment->ID) ),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        );
    }
endif;


add_action('get_header', 'wpestate_my_filter_head');
if (!function_exists('wpestate_my_filter_head')):
    function wpestate_my_filter_head() {
        remove_action('wp_head', '_admin_bar_bump_cb');
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// loosing session fix
///////////////////////////////////////////////////////////////////////////////////////////
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

///////////////////////////////////////////////////////////////////////////////////////////
// forgot pass action
///////////////////////////////////////////////////////////////////////////////////////////

add_action('wp_head', 'wpestate_hook_javascript');
if (!function_exists('wpestate_hook_javascript')):
    function wpestate_hook_javascript() {
        global $wpdb;
        $allowed_html = array();
        if (isset($_GET['key']) && isset($_GET['action']) && $_GET['action'] == "reset_pwd") {
            $reset_key  =   sanitize_text_field ( wp_kses($_GET['key'], $allowed_html) );
            $user_login =   sanitize_text_field( wp_kses($_GET['login'], $allowed_html) );
            $user_data  =   $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users
    WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));


            if (!empty($user_data)) {
                $user_login     =   $user_data->user_login;
                $user_email     =   $user_data->user_email;
                $user_mobile    =   get_the_author_meta( 'mobile' , $user_data->ID );

                if (!empty($reset_key) && !empty($user_data)) {
                    $new_password = wp_generate_password(7, false);
                    wp_set_password($new_password, $user_data->ID);

                    $arguments=array(
                        'user_pass'        =>  $new_password,
                        'user_login'       =>  $user_login,

                    );
                    wpestate_select_email_type($user_email,'password_reseted',$arguments);

                    $mess = '<div class="login-alert">' . esc_html__( 'A new password was sent via email!', 'wprentals') .esc_html($user_mobile).'</div>';

                } else {
                    exit('Not a Valid Key.');
                }
            }// end if empty
            print '<div class="login_alert_full" id="forgot_notice">' . esc_html__( 'We have just sent you a new password. Please check your email!', 'wprentals') . '</div>';
        }
    }
endif;


if ( !function_exists('wpestate_get_pin_file_path_read')):

    function wpestate_get_pin_file_path_read(){
        if (function_exists('icl_translate') ) {
            $path=trailingslashit( get_template_directory_uri() ).'/pins-'.apply_filters( 'wpml_current_language', 'en' ).'.txt';
        }else{
            $path=trailingslashit( get_template_directory_uri() ).'/pins.txt';
        }

        return $path;
    }

endif;


if ( !function_exists('wpestate_get_pin_file_path_write')):

    function wpestate_get_pin_file_path_write(){
        $path=get_template_directory().'/pins.txt';
      
      
        if (function_exists('icl_translate') && file_exists(get_template_directory().'/pins-'.apply_filters( 'wpml_current_language', NULL ).'.txt') ) {
            $path=get_template_directory().'/pins-'.apply_filters( 'wpml_current_language', NULL ).'.txt';
        }
        return $path;

       
    }

endif;


add_filter( 'redirect_canonical','wpestate_disable_redirect_canonical',10,2 );
function wpestate_disable_redirect_canonical( $redirect_url ,$requested_url){
    global $post;
    $page_template='';
    if(isset($post->ID)){
       $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
    }

    if ( $page_template=='property_list.php' || $page_template='property_list_half.php' ){
        $redirect_url = false;
    }

    return $redirect_url;
}



if ( !function_exists('wpestate_check_user_level')):
    function wpestate_check_user_level(){
        $current_user                   =   wp_get_current_user();
        $userID                         =   $current_user->ID;
        $user_login                     =   $current_user->user_login;
        $separate_users_status          =   esc_html ( wprentals_get_option('wp_estate_separate_users') );
        $publish_only                   =   esc_html ( wprentals_get_option('wp_estate_publish_only') );
        global $post;
        $page_template='';
        if(isset($post->ID)){
           $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }

        if (trim($publish_only) != '' ){
            $user_array=explode(',',$publish_only);

            if ( in_array ($user_login,$user_array)){
                return true;
            }else{
                return false;
            }

        }
        $dashboard_pages=array(
            'user_dashboard_main.php',
            'user_dashboard.php' ,
            'user_dashboard_add_step1.php',
            'user_dashboard_edit_listing.php',
            'user_dashboard_my_bookings.php',
            'user_dashboard_packs.php',
            'user_dashboard_searches.php',
            'user_dashboard_allinone.php',
            
        );

        if($separate_users_status=='no'){
            return true;
        }else{
            $user_level = intval( get_user_meta($userID,'user_type',true));

            if($user_level==0){ // user can book and rent
                return true;
            }else{
                // user can only book
                if( in_array($page_template, $dashboard_pages)   )    {
                    return false;
                }
            }
        }

    }
endif;


function estate_create_onetime_nonce($action = -1) {
    $time = time();
    $nonce = wp_create_nonce($time.$action);
    return $nonce . '-' . $time;
}


function estate_verify_onetime_nonce( $_nonce, $action = -1) {
    $parts  =   explode( '-', $_nonce );
    $nonce  =   $toadd_nonce    = $parts[0];
    $generated = $parts[1];

    $nonce_life = 60*60;
    $expires    = (int) $generated + $nonce_life;
    $time       = time();

    if( ! wp_verify_nonce( $nonce, $generated.$action ) || $time > $expires ){
        return false;
    }

    $used_nonces = get_option('_sh_used_nonces');

    if( isset( $used_nonces[$nonce] ) ) {
        return false;
    }

    if(is_array($used_nonces)){
        foreach ($used_nonces as $nonce=> $timestamp){
            if( $timestamp > $time ){
                break;
            }
            unset( $used_nonces[$nonce] );
        }
    }

    $used_nonces[$toadd_nonce] = $expires;
    asort( $used_nonces );
    update_option( '_sh_used_nonces',$used_nonces );
    return true;
}




function estate_verify_onetime_nonce_login( $_nonce, $action = -1) {
    $parts = explode( '-', $_nonce );
    $nonce =$toadd_nonce= $parts[0];
    $generated = $parts[1];

    $nonce_life = 60*60;
    $expires    = (int) $generated + $nonce_life;
    $expires2   = (int) $generated + 120;
    $time       = time();

    if( ! wp_verify_nonce( $nonce, $generated.$action ) || $time > $expires ){
        return false;
    }

    //Get used nonces
    $used_nonces = get_option('_sh_used_nonces');

    if( isset( $used_nonces[$nonce] ) ) {
        return false;
    }

    if(is_array($used_nonces)){
        foreach ($used_nonces as $nonce=> $timestamp){
            if( $timestamp > $time ){
                break;
            }
            unset( $used_nonces[$nonce] );
        }
    }

    //Add nonce in the stack after 2min
    if($time > $expires2){
        $used_nonces[$toadd_nonce] = $expires;
        asort( $used_nonces );
        update_option( '_sh_used_nonces',$used_nonces );
    }
    return true;
}




///////////////////////////////////////////////////////////////////////////////////////////
// prevent changing the author id when admin hit publish
///////////////////////////////////////////////////////////////////////////////////////////

add_action( 'transition_post_status', 'wpestate_correct_post_data',10,3 );

if( !function_exists('wpestate_correct_post_data') ):

function wpestate_correct_post_data( $strNewStatus,$strOldStatus,$post) {
  
    /* Only pay attention to posts (i.e. ignore links, attachments, etc. ) */
    if( $post->post_type !== 'estate_property' )
        return;

    if( $strOldStatus === 'new' ) {
        update_post_meta( $post->ID, 'original_author', $post->post_author );
    }

    /* If this post is being published, try to restore the original author */
    if( $strNewStatus === 'publish' ) {


            $originalAuthor_id =$post->post_author;
            $user = get_user_by('id',$originalAuthor_id);
            if(!$user){
                return;
            }
            $user_email=$user->user_email;

            if( $user->roles[0]=='subscriber'){
                $arguments=array(
                    'post_id'           =>  $post->ID,
                    'property_url'      =>  esc_url ( get_permalink($post->ID) ),
                    'property_title'    =>  get_the_title($post->ID),
                    'listing_author'    =>  $post->post_author,
                );

                if($strOldStatus=='pending'){

                    if( $user->roles[0]=='subscriber'){
                        $arguments=array(
                            'post_id'           =>  $post->ID,
                            'property_url'      =>  esc_url ( get_permalink($post->ID) ),
                            'property_title'    =>  get_the_title($post->ID),
                            'listing_author'    =>  get_the_author_meta( 'display_name', $post->post_author),
                        );

                        wpestate_select_email_type($user_email,'approved_listing',$arguments);


                    }

                }
            }
    }
}
endif; // end   wpestate_correct_post_data



function wpestate_double_tax_cover($property_area,$property_city,$post_id){
        $prop_city_selected                  =   get_term_by('name', $property_city, 'property_city');
        $prop_area_selected                  =   get_term_by('name', $property_area, 'property_area');


        if(isset($prop_area_selected->term_id)){ // we have this tax
            $term_meta = get_option( "taxonomy_$prop_area_selected->term_id");
            if( $term_meta['cityparent'] !=  $property_city){
                $new_property_area=$property_area.', '.$property_city;
            }else{
                $new_property_area=$property_area;
            }
            wp_set_object_terms($post_id,$new_property_area,'property_area');
            return $new_property_area;
        }else{
            wp_set_object_terms($post_id,$property_area,'property_area');
            return $property_area;
        }

}


function wpestate_search_by_title_only( $search, $wp_query ) {
    if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
        global $wpdb;

        $q = $wp_query->query_vars;
        $n = ! empty( $q['exact'] ) ? '' : '%';

        $search = array();
        foreach ( ( array ) $q['search_terms'] as $term )
            $search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
        if ( ! is_user_logged_in() )
            $search[] = "$wpdb->posts.post_password = ''";
        $search = ' AND ' . implode( ' AND ', $search );
    }

    return $search;
}



function wpestate_file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $max_size = wpestate_parse_size(ini_get('post_max_size'));

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = wpestate_parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function wpestate_parse_size($size) {
  $wpestate_unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($wpestate_unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $wpestate_unit[0])));
  }
  else {
    return round($size);
  }
}




if(!function_exists('wpestate_check_admin_role')):
    function wpestate_check_admin_role(){
        $roles          =   array('administrator');
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;

        if( array_intersect($roles, $current_user->roles )){
           //is admin - do not check
            return true;
        }else{
            return false;
        }
    }
endif;



add_filter( 'manage_posts_columns', 'wpestate_add_id_column', 5 );
add_action( 'manage_posts_custom_column', 'wpestate_id_column_content', 5, 2 );
add_filter( 'manage_pages_columns', 'wpestate_add_id_column', 5 );
add_action( 'manage_pages_custom_column', 'wpestate_id_column_content', 5, 2 );
add_filter( 'manage_media_columns', 'wpestate_add_id_column', 5 );
add_action( 'manage_media_custom_column', 'wpestate_id_column_content', 5, 2 );
add_action( 'manage_edit-category_columns', 'wpestate_add_id_column',5 );
add_filter( 'manage_category_custom_column', 'wpestate_categoriesColumnsRow',10,3 );
add_action( 'manage_edit-property_category_columns', 'wpestate_add_id_column',5 );
add_filter( 'manage_property_category_custom_column', 'wpestate_categoriesColumnsRow',10,3 );
add_action( 'manage_edit-property_action_category_columns', 'wpestate_add_id_column',5 );
add_filter( 'manage_property_action_category_custom_column', 'wpestate_categoriesColumnsRow',10,3 );
add_action( 'manage_edit-property_city_columns', 'wpestate_add_id_column',5 );
add_filter( 'manage_property_city_custom_column', 'wpestate_categoriesColumnsRow',10,3 );

function wpestate_add_id_column( $columns ) {
   $columns['revealid_id'] = 'ID';
   return $columns;
}

function wpestate_id_column_content( $column, $id ) {
  if( 'revealid_id' == $column ) {
    print intval($id);
  }
}


function wpestate_categoriesColumnsRow($argument, $columnName, $categoryID){
    if($columnName == 'revealid_id'){
            return $categoryID;
    }
}



function wpestate_wpml_logout_url(){
    $logout_url = esc_url( home_url('/') );
    if(function_exists('icl_translate')){
        $logout_url = apply_filters( 'wpml_home_url', esc_url( home_url('/') ) );
    }
    return $logout_url;
}


if ( function_exists('icl_object_id') ) {
    add_action( 'add_attachment', 'wpestate_sync_menu_order', 100 );
    add_action( 'edit_attachment', 'wpestate_sync_menu_order', 100 );
    function wpestate_sync_menu_order( $post_ID ) {
            $post = get_post( $post_ID );
            $menu_order = $post->menu_order;
            $trid = apply_filters( 'wpml_element_trid', false, $post_ID, 'post_attachment' );
            $translations = apply_filters( 'wpml_get_element_translations', false, $trid, 'post_attachment' );
            $translated_ids = wp_list_pluck( $translations, 'element_id' );
            if ( $menu_order !== null && (bool) $translated_ids !== false ) {
                    global $wpdb;
                    $query = $wpdb->prepare(
                            "UPDATE {$wpdb->posts}
                               SET menu_order=%s
                               WHERE ID IN (" . wpml_prepare_in( $translated_ids, '%d' ) . ')',
                            $menu_order
                    );
                    $wpdb->query( $query );
            }
    }
}


add_action( 'admin_menu', 'remove_redux_menu',12 );
function remove_redux_menu() {
    remove_submenu_page('tools.php','redux-about');
}



//////////////////////////////weglot fixed


add_filter('weglot_active_translation_before_treat_page', 'ajax_weglot_active_translation');

function ajax_weglot_active_translation(){
    if ( isset($_POST)  && isset($_POST['action']) && ( $_POST['action'] === 'wpestate_ajax_check_booking_valability' ) ) {
        return false;
    }
    return true;
}

add_action('admin_init','wprentals_convert_redux_action',50);
function wprentals_convert_redux_action(){
    if( wpestate_secondary_lic_plugin() ){
        if(function_exists('wpestate_convert_to_redux_framework')){
            if ( class_exists( 'Redux' ) ) {
                if( get_option('wprentals_convert_to_redux','')!='yes' ){
                    wpestate_convert_to_redux_framework();
                    update_option('wprentals_convert_to_redux','yes');
                }

                if( get_option('wprentals_convert_to_redux_ammenities','')!='yes' ){
                    wpestate_convert_to_redux_framework_ammenities();
                    update_option('wprentals_convert_to_redux_ammenities','yes');
                }


               //update_option('wprentals_convert_to_new_search','no');

                if( get_option('wprentals_convert_to_new_search','')!='yes' ){
                    wpestate_convert_regular_to_half();
                    update_option('wprentals_convert_to_new_search','yes');
                }

            }
        }

    }
}



add_action( 'admin_init', 'wprentals_deactivate_21_plugin' );
add_action( 'muplugins_loaded', 'wprentals_deactivate_21_plugin' ,0);
function wprentals_deactivate_21_plugin(){

    $my_theme = wp_get_theme();
    $version = floatval( $my_theme->get( 'Version' ));
    if($version< 2.2 && $version!=1){
        deactivate_plugins( 'wprentals-core/wprentals-core.php' );
    }
    if(!class_exists('Redux')){
    class Redux{

        static function init(){

        }
        static function setOption(){

        }
   }
}

}



function wpestate_is_top_bar_class(){
    global $post;
   
    $page_template='';
    if(isset($post->ID)){
       $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
    }
    
    $wpestate_is_top_bar_class = "";
    if (wpestate_show_top_bar()) {
        $wpestate_is_top_bar_class = " top_bar_on";
    }

    $property_list_type_status      =    esc_html(wprentals_get_option('wp_estate_property_list_type'));
    $property_list_type_status_adv  =    esc_html(wprentals_get_option('wp_estate_property_list_type_adv'));
    $transparent_menu_global        =    wprentals_get_option('wp_estate_transparent_menu');
    if($transparent_menu_global == 'yes'){

        if( !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) &&   $page_template == 'property_list_half.php' ){
            $wpestate_is_top_bar_class=$wpestate_is_top_bar_class.' is_half_map ';
        }

        if (  !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) &&   $page_template == 'advanced_search_results.php' && $property_list_type_status_adv == 2 ){
            $wpestate_is_top_bar_class=$wpestate_is_top_bar_class.' is_half_map ';
        }

        if ( is_tax() && isset($property_list_type_status) && $property_list_type_status == 2 ){
            $wpestate_is_top_bar_class=$wpestate_is_top_bar_class.' is_half_map ';
        }

    }else{
        if(  !is_404() && !is_tax() && !is_category() && !is_tag()  && isset($post->ID) &&   $page_template == 'property_list_half.php' ){
            $wpestate_is_top_bar_class=$wpestate_is_top_bar_class.' is_half_map ';
        }

        if (  !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) &&   $page_template == 'advanced_search_results.php' && isset($property_list_type_status_adv) && $property_list_type_status_adv == 2 ){
            $wpestate_is_top_bar_class=$wpestate_is_top_bar_class.' is_half_map ';
        }

        if ( is_tax() && isset($property_list_type_status) && $property_list_type_status == 2 ){
            $wpestate_is_top_bar_class=$wpestate_is_top_bar_class.' is_half_map ';
        }

    }


    if( is_page() && wpestate_check_if_admin_page($post->ID) && is_user_logged_in()  ){
        if( wprentals_get_option('wp_estate_show_menu_dashboard','') =='no'){
            $wpestate_is_top_bar_class.=" no_header_dash ";
        }
    }

    return $wpestate_is_top_bar_class;
}





/*
 * 
 *   body class for gutemberg
 * 
 * 
 * 
 * 
 */



function wprentals_body_class_blocks( $classes ) {
    global $post;
    $classes[] = wpestate_is_top_bar_class();
    if ( is_singular() &&  has_blocks() ) {
        $classes[] = 'has-gutenberg-blocks';
    }
    if(  isset($post->ID) && wpestate_check_if_admin_page($post->ID) ){
        $classes[] = ' wprentals_dashboard_page ';
    }
    if( wprentals_get_option('wp_estate_mobile_sticky_header')=='yes'){
        $classes[]='using-mobile-header-sticky';
    }

    
    return $classes;


    return $classes;
}

add_filter( 'body_class', 'wprentals_body_class_blocks' );

/*
 * 
 *   elementor comptibility - load themes
 * 
 * 
 * 
 * 
 */

function wprentals_register_elementor_locations( $elementor_theme_manager ) {

    $elementor_theme_manager->register_location( 'header' );
    $elementor_theme_manager->register_location( 'footer' );
    $elementor_theme_manager->register_location( 'single' );
    $elementor_theme_manager->register_location( 'archive' );

}
add_action( 'elementor/theme/register_locations', 'wprentals_register_elementor_locations' );


