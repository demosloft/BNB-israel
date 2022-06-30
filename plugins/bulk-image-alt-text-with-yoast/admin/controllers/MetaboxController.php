<?php

namespace Pagup\Bialty\Controllers;

use  Pagup\Bialty\Core\Option ;
use  Pagup\Bialty\Core\Plugin ;
use  Pagup\Bialty\Core\Request ;
class MetaboxController
{
    public function add_metabox()
    {
        $post_types = array( 'post', 'page' );
        foreach ( $post_types as $post_type ) {
            add_meta_box(
                'bialty_post_options',
                // id, used as the html id att
                __( 'Bulk Image Alt Texts' ),
                // meta box title
                array( &$this, 'metabox' ),
                // callback function, spits out the content
                $post_type,
                // post type or page. This adds to posts only
                'side',
                // context, where on the screen
                'low'
            );
        }
    }
    
    function metabox( $post )
    {
        $data = [
            'use_bialty_alt' => get_post_meta( $post->ID, 'use_bialty_alt', true ),
            'bialty_cs_alt'  => get_post_meta( $post->ID, 'bialty_cs_alt', true ),
            'disable_bialty' => get_post_meta( $post->ID, 'disable_bialty', true ),
        ];
        //$meta = get_post_meta($post->ID);
        //var_dump($meta);
        //var_dump(Option::post_meta('disable_bialty'));
        return Plugin::view( 'metabox', $data );
    }
    
    public function metadata( $postid )
    {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return false;
        }
        if ( !current_user_can( 'edit_pages', $postid ) ) {
            return false;
        }
        if ( empty($postid) ) {
            return false;
        }
        $safe = array( "use_bialty_alt_yes", "disable_bialty_yes" );
        ( Request::post( 'use_bialty_alt', $safe ) ? update_post_meta( $postid, 'use_bialty_alt', true ) : delete_post_meta( $postid, 'use_bialty_alt' ) );
        ( Request::check( 'bialty_cs_alt' ) ? update_post_meta( $postid, 'bialty_cs_alt', sanitize_text_field( $_POST['bialty_cs_alt'] ) ) : delete_post_meta( $postid, 'bialty_cs_alt' ) );
        ( Request::post( 'disable_bialty', $safe ) ? update_post_meta( $postid, 'disable_bialty', true ) : delete_post_meta( $postid, 'disable_bialty' ) );
    }
    
    public function check( $val )
    {
        return isset( $option[$val] ) && !empty($option[$val]);
    }

}
$metabox = new MetaboxController();