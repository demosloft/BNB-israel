<?php



if( !function_exists('wpestate_show_advanced_search_options') ):
function  wpestate_show_advanced_search_options($i,$adv_search_what){
    $return_string='';

    $curent_value='';
    if(isset($adv_search_what[$i])){
        $curent_value=$adv_search_what[$i];
    }

   // $curent_value=$adv_search_what[$i];
    $admin_submission_array=array(  'Location'          =>  esc_html('Location','wprentals'),
                                    'check_in'          =>  esc_html('check_in','wprentals'),
                                    'check_out'         =>  esc_html('check_out','wprentals'),
                                    'property_category'         =>  esc_html('First Category','wprentals'),
                                    'property_action_category'  =>  esc_html('Second Category','wprentals'),
                                    'property_city'             =>  esc_html('Cities','wprentals'),
                                    'property_area'             =>  esc_html('Areas','wprentals'),
                                    'guest_no'          =>  esc_html('guest_no','wprentals'),
                                    'property_price'    =>  esc_html('Price','wprentals'),
                                    'property_size'     =>  esc_html('Size','wprentals'),
                                    'property_rooms'    =>  esc_html('Rooms','wprentals'),
                                    'property_bedrooms' =>  esc_html('Bedroms','wprentals'),
                                    'property_bathrooms'=>  esc_html('Bathrooms','wprentals'),
                                    'property_address'  =>  esc_html('Adress','wprentals'),
                                    'property_county'   =>  esc_html('County','wprentals'),
                                    'property_state'    =>  esc_html('State','wprentals'),
                                    'property_zip'      =>  esc_html('Zip','wprentals'),
                                    'property_country'  =>  esc_html('Country','wprentals'),


                                );

    foreach($admin_submission_array as $key=>$value){

        $return_string.='<option value="'.$key.'" ';
        if($curent_value==$key){
             $return_string.= ' selected="selected" ';
        }
        $return_string.= '>'.$value.'</option>';
    }

    $i=0;
    $custom_fields = get_option( 'wp_estate_custom_fields', true);
    if( !empty($custom_fields)){
        while($i< count($custom_fields) ){
            $name =   $custom_fields[$i][0];
            $type =   $custom_fields[$i][1];
            $slug =   str_replace(' ','-',$name);

            $return_string.='<option value="'.$slug.'" ';
            if($curent_value==$slug){
               $return_string.= ' selected="selected" ';
            }
            $return_string.= '>'.$name.'</option>';
            $i++;
        }
    }
    $slug='none';
    $name='none';
    $return_string.='<option value="'.$slug.'" ';
    if($curent_value==$slug){
        $return_string.= ' selected="selected" ';
    }
    $return_string.= '>'.$name.'</option>';


    return $return_string;
}
endif; // end   wpestate_show_advanced_search_options



if( !function_exists('wpestate_show_advanced_search_how') ):
function  wpestate_show_advanced_search_how($i,$adv_search_how){
    $return_string='';
    $curent_value='';
    if (isset($adv_search_how[$i])){
         $curent_value=$adv_search_how[$i];
    }



    $admin_submission_how_array=array('equal',
                                      'greater',
                                      'smaller',
                                      'like',
                                      'date bigger',
                                      'date smaller');

    foreach($admin_submission_how_array as $value){
        $return_string.='<option value="'.$value.'" ';
        if($curent_value==$value){
             $return_string.= ' selected="selected" ';
        }
        $return_string.= '>'.$value.'</option>';
    }
    return $return_string;
}
endif; // end   wpestate_show_advanced_search_how


function wpestate_unstrip_array($array){
    $stripped=array();
    foreach($array as $val){

            $stripped[] = stripslashes($val);

    }
    return $stripped;
}


?>
