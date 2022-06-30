<?php

/*
* Plugin Name: BIALTY - Bulk Image Alt Text (Alt tag, Alt Attribute) with Yoast SEO + WooCommerce
* Description: Auto-add Alt texts, also called Alt Tags or Alt Attributes, from YOAST SEO Focus Keyword field (or page/post/product title) with your page/post/product title, to all images contained on your pages, posts, products, portfolios for better Google Ranking on search engines – Fully compatible with Woocommerce
* Author: Pagup
* Version: 1.4.5.1
* Author URI: https://pagup.com/
* Text Domain: bulk-image-alt-text-with-yoast
* Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'bialty_fs' ) ) {
    bialty_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'bialty_fs' ) ) {
        if ( !defined( 'BIALTY_PLUGIN_BASE' ) ) {
            define( 'BIALTY_PLUGIN_BASE', plugin_basename( __FILE__ ) );
        }
        if ( !defined( 'BIALTY_PLUGIN_DIR' ) ) {
            define( 'BIALTY_PLUGIN_DIR', plugins_url( '', __FILE__ ) );
        }
        require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
        /******************************************
                   Freemius Init
           *******************************************/
        function bialty_fs()
        {
            global  $bialty_fs ;
            
            if ( !isset( $bialty_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $bialty_fs = fs_dynamic_init( array(
                    'id'              => '2602',
                    'slug'            => 'bulk-image-alt-text-with-yoast',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_a805c7e6685744c85d7e720fd230d',
                    'is_premium'      => false,
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'has_affiliation' => 'all',
                    'menu'            => array(
                    'slug'       => 'bialty',
                    'first-path' => 'admin.php?page=bialty',
                    'support'    => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $bialty_fs;
        }
        
        // Init Freemius.
        bialty_fs();
        // Signal that SDK was initiated.
        do_action( 'bialty_fs_loaded' );
        function bialty_fs_settings_url()
        {
            return admin_url( 'admin.php?page=bialty&tab=bialty-settings' );
        }
        
        bialty_fs()->add_filter( 'connect_url', 'bialty_fs_settings_url' );
        bialty_fs()->add_filter( 'after_skip_url', 'bialty_fs_settings_url' );
        bialty_fs()->add_filter( 'after_connect_url', 'bialty_fs_settings_url' );
        bialty_fs()->add_filter( 'after_pending_connect_url', 'bialty_fs_settings_url' );
        // freemius opt-in
        function bialty_fs_custom_connect_message(
            $message,
            $user_first_name,
            $product_title,
            $user_login,
            $site_link,
            $freemius_link
        )
        {
            $break = "<br><br>";
            return sprintf( esc_html__( 'Hey %1$s, %2$s Click on Allow & Continue to start optimizing your images with ALT tags :)!  Don\'t spend hours at adding manually alt tags to your images. BIALTY will use your YOAST settings automatically to get better results on search engines and improve your SEO. %2$s Never miss an important update -- opt-in to our security and feature updates notifications. %2$s See you on the other side.', 'bulk-image-alt-text-with-yoast' ), $user_first_name, $break );
        }
        
        bialty_fs()->add_filter(
            'connect_message',
            'bialty_fs_custom_connect_message',
            10,
            6
        );
        class Bialty
        {
            function __construct()
            {
                register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
                add_action( 'init', array( &$this, 'bialty_textdomain' ) );
            }
            
            public function deactivate()
            {
                if ( \Pagup\Bialty\Core\Option::check( 'remove_settings' ) ) {
                    delete_option( 'bialty' );
                }
            }
            
            function bialty_textdomain()
            {
                load_plugin_textdomain( \Pagup\Bialty\Core\Plugin::domain(), false, basename( dirname( __FILE__ ) ) . '/languages' );
            }
        
        }
        $bialty = new Bialty();
        /*-----------------------------------------
                        DOM CONTROLLER
          ------------------------------------------*/
        require_once \Pagup\Bialty\Core\Plugin::path( 'admin/controllers/DomController.php' );
        /*-----------------------------------------
                          Settings
          ------------------------------------------*/
        if ( is_admin() ) {
            include_once \Pagup\Bialty\Core\Plugin::path( 'admin/Settings.php' );
        }
    }

}
