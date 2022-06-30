<?php

namespace Pagup\Bialty\Core;

class Plugin
{
    public static function url($filePath)
    {
        return plugins_url('', __DIR__ ) . "/{$filePath}";
    }

    public static function path($filePath)
    {
        return plugin_dir_path( __DIR__ ) . "{$filePath}";
    }

    public static function view($file, $data = [])
    {
        extract($data);
        require static::path("admin/views/{$file}.view.php");
    }
    
    public static function domain()
    {
        return "bulk-image-alt-text-with-yoast";
    }
}