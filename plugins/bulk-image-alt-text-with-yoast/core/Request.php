<?php
namespace Pagup\Bialty\Core;

class Request
{
    public static function post($val, $safe)
    {

        if ( isset( $_POST[$val] ) && in_array( $_POST[$val], $safe ) ) 
        { 
            
            return sanitize_text_field( $_POST[$val] );

        } else {

            return "";

        }
        
        
    }

    public static function check($val)
    {

        return isset( $_POST[$val] ) && !empty( $_POST[$val] ); 
        
    }
}