<?php
namespace Pagup\Bialty\Controllers;

use Pagup\Bialty\Core\Plugin;

class NoticeController
{

    public function support() 
    {
        return Plugin::view('notices/support');
    }
    
    public function resetSettings() 
    {
        
        if ( ! \PAnD::is_admin_notice_active( 'bialty-reset-120' ) ) 
        {
            return;
        }

        return Plugin::view('notices/reset');
    }
}