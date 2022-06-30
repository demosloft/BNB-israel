<?php
/*
 *  Plugin Name: WpRentals -Theme Core Functionality
 *  Plugin URI:  https://themeforest.net/user/wpestate
 *  Description: Adds functionality to WpRentals
 *  Version:     3.7
 *  Author:      wpestate
 *  Author URI:  https://wpestate.org
 *  License:     GPL2
 *  Text Domain: wprentals-core
 *  Domain Path: /languages
 *
*/

define('WPESTATE_PLUGIN_URL',  plugins_url() );
define('WPESTATE_PLUGIN_DIR_URL',  plugin_dir_url(__FILE__) );
define('WPESTATE_PLUGIN_PATH',  plugin_dir_path(__FILE__) );
define('WPESTATE_PLUGIN_BASE',  plugin_basename(__FILE__) );

add_action( 'wp_enqueue_scripts', 'wpestate_rentals_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'wpestate_rentals_enqueue_styles_admin');
add_action( 'plugins_loaded', 'wpestate_rentals_functionality_loaded' );
register_activation_hook( __FILE__, 'wpestate_rentals_functionality_plugin_activated' );
register_deactivation_hook( __FILE__, 'wpestate_rentals_deactivate' );

require_once WPESTATE_PLUGIN_PATH.'/widgets/twitter-api-wordpress.php';



function wpestate_rentals_functionality_loaded(){
    $my_theme   =   wp_get_theme();
    $version    =   floatval( $my_theme->get( 'Version' ));
    $theme_name =   $my_theme->name;
    $deactivate =   false;

    if($version< 2 && $version!=1){
        $deactivate=true;
    }
    if (strpos(strtolower($theme_name), 'wprentals') === false) {
         $deactivate=true;
    }

    if($deactivate){
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( 'WpRentals Core plugin requires  WpRentals 2.01 or higher.','wprentals-core' );
    }
    load_plugin_textdomain( 'wprentals-core', false, dirname( WPESTATE_PLUGIN_BASE ) . '/languages' );
    wpestate_shortcodes();
    add_action('widgets_init', 'register_wpestate_widgets' );
    add_action('wp_footer', 'wpestate_core_add_to_footer');



}

function wpestate_rentals_functionality_plugin_activated(){

}

function wpestate_rentals_deactivate(){
}


function wpestate_rentals_enqueue_styles() {
}


function wpestate_rentals_enqueue_styles_admin(){
}


require_once(WPESTATE_PLUGIN_PATH . 'misc/metaboxes.php');
require_once(WPESTATE_PLUGIN_PATH . 'misc/plugin_help_functions.php');
require_once(WPESTATE_PLUGIN_PATH . 'misc/emailfunctions.php');
require_once(WPESTATE_PLUGIN_PATH . 'misc/sms_functions.php');
require_once(WPESTATE_PLUGIN_PATH . 'misc/3rd_party_code.php');
require_once(WPESTATE_PLUGIN_PATH . 'misc/update_functions.php');
require_once(WPESTATE_PLUGIN_PATH . 'resources/rcapi_functions.php');

require_once(WPESTATE_PLUGIN_PATH . 'widgets.php');
require_once(WPESTATE_PLUGIN_PATH . 'shortcodes/shortcodes_install.php');
require_once(WPESTATE_PLUGIN_PATH . 'shortcodes/shortcodes.php');
require_once(WPESTATE_PLUGIN_PATH . 'post-types/agents.php');
require_once(WPESTATE_PLUGIN_PATH . 'post-types/booking.php');
require_once(WPESTATE_PLUGIN_PATH . 'post-types/invoices.php');
require_once(WPESTATE_PLUGIN_PATH . 'post-types/membership.php');
require_once(WPESTATE_PLUGIN_PATH . 'post-types/messages.php');
require_once(WPESTATE_PLUGIN_PATH . 'post-types/property.php');
require_once(WPESTATE_PLUGIN_PATH . 'post-types/searches.php');
require_once WPESTATE_PLUGIN_PATH.'resources/src/Google_Client.php';
require_once WPESTATE_PLUGIN_PATH.'resources/src/contrib/Google_Oauth2Service.php';





add_action('init','wprentals_init_redux',30);
function wprentals_init_redux(){

    require_once WPESTATE_PLUGIN_PATH . 'admin/admin-init.php';
    Redux::init("wprentals_admin");


    $enable_stripe_status   =   esc_html ( wprentals_get_option('wp_estate_enable_stripe','') );

    if($enable_stripe_status==='yes'){
        require_once(WPESTATE_PLUGIN_PATH.'resources/stripe-php-master/init.php');
    }

    $yelp_client_id         =   wprentals_get_option('wp_estate_yelp_client_id','');
    $yelp_client_secret     =   wprentals_get_option('wp_estate_yelp_client_secret','');
    if($yelp_client_id!=='' || $yelp_client_secret!=='' ){
        require_once(WPESTATE_PLUGIN_PATH.'resources/yelp_fusion.php');
    }


    $facebook_status    =   esc_html( wprentals_get_option('wp_estate_facebook_login','') );
    if($facebook_status=='yes'){
        require_once WPESTATE_PLUGIN_PATH.'resources/facebook_sdk5/Facebook/autoload.php';
    }

    $twiter_status       =   esc_html( wprentals_get_option('wp_estate_twiter_login','') );
    if($twiter_status=='yes'){
        require_once WPESTATE_PLUGIN_PATH.'resources/twitteroauth/autoload.php';
    }

    $google_status              = esc_html( wprentals_get_option('wp_estate_google_login','') );


    if($facebook_status=='yes' ||$twiter_status=='yes' ||  $google_status =='yes'){
        require_once WPESTATE_PLUGIN_PATH.'classes/tweet_login.php';
        global $wpestate_social_login;
        $wpestate_social_login =new Wpestate_Social_Login();

    }


    require_once WPESTATE_PLUGIN_PATH.'classes/wpestate_global_payments.php';


    global $wpestate_global_payments;
    $wpestate_global_payments =new Wpestate_Global_Payments();



}





function wpestate_return_imported_data(){
    return  @unserialize(base64_decode( trim($_POST['import_theme_options']) ) );
}

function wpestate_return_imported_data_encoded($return_exported_data){
    return base64_encode( serialize( $return_exported_data) );
}



add_action( 'plugins_loaded', 'wpestate_check_current_user' );
function wpestate_check_current_user() {
    $current_user = wp_get_current_user();
    if (!current_user_can('manage_options') ) {
        show_admin_bar(false);
    }
}

function wpestate_check_license_plugin(){
    $theme_activated    =   get_option('is_theme_activated','');

    if($theme_activated!='is_active'){
        return false;
    }else{
        return true;
    }

}
add_action( 'after_setup_theme', 'wprentals_create_helper_content' );
function wprentals_create_helper_content() {

    global $pagenow;

    $my_theme = wp_get_theme();
    $theme_version= floatval( $my_theme->get( 'Version' ));



    if ( get_option('wprentals_theme_setup')!=='yes') {


         ////////////////////  insert sales and rental categories
        $actions = array(   'Entire home',
                            'Private room',
                            'Shared room'
                        );

        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );


            if(!term_exists($key, 'property_action_category', $my_cat) ){
                $return =  wp_insert_term($key, 'property_action_category',$my_cat);
            }
        }

        ////////////////////  insert listings type categories
        $actions = array(   'Apartment',
                            'B & B',
                            'Cabin',
                            'Condos',
                            'Dorm',
                            'House',
                            'Condos',
                            'Villa',
                        );

        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );

            if(!term_exists($key, 'property_category') ){
                wp_insert_term($key, 'property_category');
            }
        }


        wprentals_create_default_pages_on_install();

        update_option('wprentals_theme_setup','yes');
    }// end if activated



}


/*
/
/ Create default pages on installation
/
*/


function wprentals_create_default_pages_on_install(){


       $page_creation=array(
               array(
                   'name'      =>'Advanced Search',
                   'template'  =>'advanced_search_results.php',
               ),

               array(
                   'name'      =>'My Listings',
                   'template'  =>'user_dashboard.php',
               ),
                array(
                   'name'      =>'Edit Listing',
                   'template'  =>'user_dashboard_edit_listing.php',
               ),
               array(
                   'name'      =>'Add New Listing',
                   'template'  =>'user_dashboard_add_step1.php',
               ),
               array(
                   'name'      =>'Favorites',
                   'template'  =>'user_dashboard_favorite.php',
               ),
               array(
                   'name'      =>'My Inbox',
                   'template'  =>'user_dashboard_inbox.php',
               ),
               array(
                   'name'      =>'Dashboard',
                   'template'  =>'user_dashboard_main.php',
               ),
               array(
                   'name'      =>'Invoices',
                   'template'  =>'user_dashboard_invoices.php',
               ),
               array(
                   'name'      =>'My Profile',
                   'template'  =>'user_dashboard_profile.php',
               ),

               array(
                   'name'      =>'Dashboard - Subscriptions',
                   'template'  =>'user_dashboard_packs.php',
               ),
               array(
                   'name'      =>'My Bookings',
                   'template'  =>'user_dashboard_my_bookings.php',
               ),
               array(
                    'name'      =>'My Reservations',
                    'template'  =>'user_dashboard_my_reservations.php',
                ),
                array(
                     'name'      =>'All in One Calendar',
                     'template'  =>'user_dashboard_allinone.php',
                 ),


       );


       foreach($page_creation as $key=>$template){
         if (wpestate_get_template_link($template['template'],1 )=='' ||
              wpestate_get_template_link($template['template'],1 )==home_url('/') ){

               $my_post = array(
                   'post_title'    => $template['name'],
                   'post_type'     => 'page',
                   'post_status'   => 'publish',
               );
               $new_id = wp_insert_post($my_post);
               update_post_meta($new_id, '_wp_page_template', $template['template'] );

           }
       }

}













add_action( 'redux/loaded', 'remove_demo' );
/**
 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
 */
if ( ! function_exists( 'remove_demo' ) ) {
    function remove_demo() {
        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }
}


function noo_enable_vc_auto_theme_update() {
	if( function_exists('vc_updater') ) {
		$vc_updater = vc_updater();
		remove_filter( 'upgrader_pre_download', array( $vc_updater, 'preUpgradeFilter' ), 10 );
		if( function_exists( 'vc_license' ) ) {
			if( !vc_license()->isActivated() ) {
				remove_filter( 'pre_set_site_transient_update_plugins', array( $vc_updater->updateManager(), 'check_update' ), 10 );
			}
		}
	}
}
add_action('vc_after_init', 'noo_enable_vc_auto_theme_update');



add_action('wp_head', 'wpestate_add_custom_meta_to_header');

function wpestate_add_custom_meta_to_header(){
    global $post;
    if( is_tax() ) {
        print '<meta name="description" content="'.strip_tags( term_description('', get_query_var( 'taxonomy' ) )).'" >';
    }

    if(is_singular('wpestate_invoice') || is_singular('wpestate_message')){
        echo '<meta name="robots" content="noindex">';
    }

   if(is_singular('wpestate_booking') || is_singular('wpestate_invoice')){
        print '<meta name="robots" content="noindex">';
    }

    if ( is_singular('estate_property') ){
        $image_id       =   get_post_thumbnail_id();
        $share_img      =   wp_get_attachment_image_src( $image_id, 'full');
        $the_post       =   get_post($post->ID); 
        $share_img_path =   '';
        
        if( isset($share_img[0]) ){
            $share_img_path= $share_img[0];
        }
        ?>

        

        <meta property="og:image" content="<?php print esc_url($share_img_path); ?>"/>
        <meta property="og:image:secure_url" content="<?php print esc_url($share_img_path); ?>" />
        <meta property="og:description"  content=" <?php print wp_strip_all_tags( $the_post->post_content);?>" />
    <?php }

}
