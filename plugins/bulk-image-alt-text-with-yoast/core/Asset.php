<?php
namespace Pagup\Bialty\Core;
use Pagup\Bialty\Core\Plugin;

class Asset 
{

    public static function style( $name, $file )
    {
        wp_register_style( $name, Plugin::url("admin/assets/{$file}"), array(), filemtime( Plugin::path("admin/assets/{$file}") ) );

        wp_enqueue_style( $name );

    }

    public static function script( $name, $file )
    {
        wp_register_script( $name, Plugin::url("admin/assets/{$file}"), array(), filemtime( Plugin::path("admin/assets/{$file}") ) );

        wp_enqueue_script( $name );

    }

}