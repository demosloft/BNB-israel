<?php

namespace Pagup\Bialty\Controllers;

use  Pagup\Bialty\Core\Request ;
use  Pagup\Bialty\Core\Plugin ;
class SettingsController
{
    public function add_settings()
    {
        add_menu_page(
            __( 'Bulk Image Alt Text Settings', 'bulk-image-alt-text-with-yoast' ),
            __( 'Bulk Image Alt Text', 'bulk-image-alt-text-with-yoast' ),
            'manage_options',
            'bialty',
            array( &$this, 'page' ),
            'dashicons-editor-textcolor'
        );
    }
    
    public function page()
    {
        $progress_bar = '';
        
        if ( isset( $_POST['update'] ) ) {
            if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) ) {
                die( 'Sorry, not allowed...' );
            }
            $safe = [
                "alt_empty_fkw",
                "alt_empty_title",
                "alt_empty_imagename",
                "alt_empty_both",
                "alt_not_empty_fkw",
                "alt_not_empty_title",
                "alt_not_empty_imagename",
                "alt_not_empty_both",
                "woo_alt_empty_fkw",
                "woo_alt_empty_title",
                "woo_alt_empty_imagename",
                "woo_alt_empty_both",
                "woo_alt_not_empty_fkw",
                "woo_alt_not_empty_title",
                "woo_alt_not_empty_imagename",
                "woo_alt_not_empty_both",
                "woo_disable_gallery",
                "add_site_title",
                "debug_mode",
                "remove_settings",
                "promo"
            ];
            $options = [
                'alt_empty'       => Request::post( 'alt_empty', $safe ),
                'alt_not_empty'   => Request::post( 'alt_not_empty', $safe ),
                'add_site_title'  => Request::post( 'add_site_title', $safe ),
                'debug_mode'      => Request::post( 'debug_mode', $safe ),
                'remove_settings' => Request::post( 'remove_settings', $safe ),
                'promo_robot'     => Request::post( 'promo_robot', $safe ),
                'promo_mobilook'  => Request::post( 'promo_mobilook', $safe ),
                'promo_vidseo'    => Request::post( 'promo_vidseo', $safe ),
            ];
            update_option( 'bialty', $options );
            // update options
            echo  '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved.', 'bialty' ) . '</strong></p></div>' ;
            $progress_bar = '<div class="meter animate"><span style="width: 100%"><span>All Done</span></span></div>';
        }
        
        $options = new \Pagup\Bialty\Core\Option();
        $notices = new \Pagup\Bialty\Controllers\NoticeController();
        //var_dump($options::all());
        echo  $notices->support() ;
        //set active class for navigation tabs
        $active_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'bialty-settings' );
        // purchase notification
        $purchase_url = bialty_fs()->get_upgrade_url();
        $get_pro = sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable', 'bialty' ), array(
            'a' => array(
            'href'   => array(),
            'target' => array(),
        ),
        ) ), esc_url( $purchase_url ) );
        // Return Views
        if ( $active_tab == 'bialty-settings' ) {
            return Plugin::view( 'settings', compact(
                'active_tab',
                'options',
                'get_pro',
                'progress_bar'
            ) );
        }
        if ( $active_tab == 'bialty-faq' ) {
            return Plugin::view( "faq", compact( 'active_tab' ) );
        }
        if ( $active_tab == 'bialty-recs' ) {
            return Plugin::view( "recommendations", compact( 'active_tab' ) );
        }
    }

}
$settings = new SettingsController();