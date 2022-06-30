<?php
namespace Pagup\Bialty;
use Pagup\Bialty\Core\Asset;
use Pagup\Bialty\Controllers\DomController;
use Pagup\Bialty\Controllers\NoticeController;
use Pagup\Bialty\Controllers\MetaboxController;
use Pagup\Bialty\Controllers\SettingsController;

//require \Pagup\Bialty\Core\Plugin::path('vendor/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php');

class Settings {

    protected $dom;

    public function __construct()
    {
        $settings = new SettingsController;
        $metabox = new MetaboxController;
        $notice = new NoticeController;
        $this->dom = new DomController;

        // Add settings page
        add_action( 'admin_menu', array( &$settings, 'add_settings' ) );

        // Add metabox to post-types
        add_action( 'add_meta_boxes', array(&$metabox, 'add_metabox') );

        // Save meta data
        add_action( 'save_post', array(&$metabox, 'metadata'));

        // Add setting link to plugin page
        $plugin_base = BIALTY_PLUGIN_BASE;
        add_filter( "plugin_action_links_{$plugin_base}", array( &$this, 'setting_link' ) );

        // Add styles and scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'assets') );

        // Add notices with disable functionality
        //add_action( 'admin_init', array( 'PAnD', 'init' ) );
        //add_action( 'admin_notices', array(&$notice, 'resetSettings') );
    }

    public function setting_link( $links ) {

        array_unshift( $links, '<a href="admin.php?page=bialty">Settings</a>' );
        return $links;
    }

    public function assets() {

        Asset::style('bialty_styles', 'app.css');
        Asset::script('bialty_script', 'app.js');
    
    }
}

$settings = new Settings;
