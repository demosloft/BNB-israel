<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'wpestate_chld_thm_cfg_parent_css' ) ):
   function wpestate_chld_thm_cfg_parent_css() {

    $parent_style = 'wpestate_style'; 
    wp_enqueue_style('bootstrap',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
    wp_enqueue_style('bootstrap-theme',get_template_directory_uri().'/css/bootstrap-theme.css', array(), '1.0', 'all');
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css',array('bootstrap','bootstrap-theme'),'all' );
    wp_enqueue_style( 'wpestate-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    
   }    
    
endif;
add_action( 'wp_enqueue_scripts', 'wpestate_chld_thm_cfg_parent_css' );
load_child_theme_textdomain('wprentals', get_stylesheet_directory().'/languages');

// Add BNBDashboard widget.
function bnb_dashboard_widgets() {
    global $wp_meta_boxes;
    
    wp_add_dashboard_widget('bnb_help_widget', 'BNB Sync', 'bnb_dashboard_help');
}
add_action('wp_dashboard_setup', 'bnb_dashboard_widgets');

function bnb_dashboard_help() {
    echo '<p>Welcome to BNB Sync! Please click the button below to start the sync process which may take few hours<br /><br /><a href="'.get_site_url().'/api/start.php" class="button button-primary">Start Sync</a></p>';
}
 
// add_filter( 'cron_schedules', 'isa_add_every_five_minutes' );
// function isa_add_every_five_minutes( $schedules ) {
// $schedules['every_day_sync'] = array(
//     'interval'  => 60 * 1, // 1440
//     'display'   => __( 'Set cron for BNB sync!', 'bnb' )
// );
// return $schedules;
// }
// if ( ! wp_next_scheduled( 'every_day_sync' ) ) {
 
//     wp_schedule_event( time(), 'every_day_sync', 'every_day_sync');
// }
// function every_day_sync(){
//  wp_remote_get( get_site_url().'/api/start.php', $args);
// }
add_action('cron_script_order','cron_script_order');
function cron_script_order(){
    $link = get_site_url().'/api/start.php';
    $request  = wp_remote_get( $link );
    $response = wp_remote_retrieve_body( $request );
    $res = json_decode($response);
   // wp_remote_get( get_site_url().'/api/start.php', $args);   
}

// add_action('wp_head', 'change_this_name_of_your_function');
// function change_this_name_of_your_function(){
// //echo ' Access-Control-Allow-Origin: https://www.bnbisrael.com/  ';
// echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
// ';
// }

