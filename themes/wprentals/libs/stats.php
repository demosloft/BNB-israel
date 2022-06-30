<?php


if( !function_exists('wp_estate_count_page_stats') ):
    function wp_estate_count_page_stats($prop_id) {

        // get the total no of
        $total_views=  intval( get_post_meta($prop_id, 'wpestate_total_views', true) );
        if($total_views==''){
            $total_views=1;
        }else{
            $total_views++;
        }
        update_post_meta($prop_id, 'wpestate_total_views', $total_views );

        $today = date('m-d-Y', time() );
        $detailed_views=  get_post_meta($prop_id, 'wpestate_detailed_views', true);


        if($detailed_views=='' || !is_array($detailed_views)){
            $detailed_views         =   array();
            $detailed_views[$today] =   1;


        }else{
            if( !isset( $detailed_views[$today] ) ){

                if( count($detailed_views) > 60 ){
                    array_shift($detailed_views);
                }

                $detailed_views[$today]=1;

            }else{

                $detailed_views[$today]=intval($detailed_views[$today])+1;
            }

        }

        $detailed_views=  update_post_meta($prop_id, 'wpestate_detailed_views', $detailed_views);



    }
endif;
 ?>
