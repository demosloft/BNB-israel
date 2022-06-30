<?php

namespace Pagup\Bialty\Controllers;

use  Pagup\Bialty\Core\Option ;
use  Pagup\Bialty\Traits\DomHelper ;
class DomController
{
    use  DomHelper ;
    public function __construct()
    {
        add_filter( 'the_content', array( &$this, 'bialty' ), 100 );
        add_filter( 'woocommerce_single_product_image_thumbnail_html', array( &$this, 'bialty_woocommerce_gallery' ), 100 );
        add_filter( 'post_thumbnail_html', array( &$this, 'bialty' ), 100 );
    }
    
    public function bialty( $content )
    {
        $dom = new \DOMDocument( '1.0', 'UTF-8' );
        
        if ( Option::check( 'debug_mode' ) ) {
            @$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        } else {
            @$dom->loadHTML( mb_convert_encoding( "<div class='bialty-container'>{$content}</div>", 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        }
        
        $html = new \DOMXPath( $dom );
        foreach ( $html->query( "//img" ) as $node ) {
            // Return image URL
            $img_url = $node->getAttribute( "src" );
            // Set alt for Post & Pages
            
            if ( is_singular( array( 'post', 'page' ) ) ) {
                
                if ( empty($node->getAttribute( 'alt' )) ) {
                    if ( Option::check( 'alt_empty' ) ) {
                        $this->setEmpty( 'alt_empty', $node, $img_url );
                    }
                } else {
                    if ( Option::check( 'alt_not_empty' ) ) {
                        $this->setNotEmpty( 'alt_not_empty', $node, $img_url );
                    }
                }
                
                // Set custom keyword for all alt tags
                if ( Option::post_meta( 'use_bialty_alt' ) == true && !empty(Option::post_meta( 'bialty_cs_alt' )) ) {
                    $node->setAttribute( "alt", Option::post_meta( 'bialty_cs_alt' ) );
                }
            }
        
        }
        // Set alt for Post/Pages
        if ( is_singular( array( 'post', 'page' ) ) ) {
            if ( empty(Option::post_meta( 'disable_bialty' )) ) {
                $content = $dom->saveHtml();
            }
        }
        return $content;
    }
    
    public function bialty_woocommerce_gallery( $content )
    {
        $dom = new \DOMDocument( '1.0', 'UTF-8' );
        @$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        $html = new \DOMXPath( $dom );
        return $content;
    }

}
$DomController = new DomController();