<?php

/*
*
*
* Featured places
*
*
*/

if( !function_exists('wpestate_featured_place') ):

function wpestate_featured_place($attributes, $content = null) {
    $place_id           =   '';
    $return_string      =   '';
    $extra_class_name   =   '';
    $type_class         =   '';
    $places_label       =   '';
    $category_count     =   '';

    $attributes = shortcode_atts(
        array(
            'id'                       => 0,
            'places_label'             => '',
            'type'                     => "type1",
            'places_height'             =>  '',
        ), $attributes) ;



    if ( isset($attributes['id']) ){
        $place_id=$attributes['id'];
    }


    if ( isset($attributes['type']) && $attributes['type']=='type1' ){
        $type_class=' type_1_class ';
    }

    if ( isset($attributes['type']) && $attributes['type']=='type3' ){
        $type_class=' type_3_class ';

    }

    if (isset($attributes['places_label'])) {
        $places_label = $attributes['places_label'];
    }

    $places_style="";
    if (isset($attributes['places_height'])) {
        $places_height = $attributes['places_height'];
        if(is_array($places_height)){
           $places_height=$attributes['places_height']['size'];
         }
        if($places_height!=''){
            $places_style  = "height:".$places_height."px;";
        }
    }





    if( isset($attributes['extra_class_name'])){
        $extra_class_name=$attributes['extra_class_name'];
    }

        $place_id=intval($place_id);
        $category_attach_id='';
        $category_tax='';
        $category_featured_image='';
        $category_name='';
        $category_featured_image_url='';
        $term_meta = get_option( "taxonomy_$place_id");
        $category_tagline='';

        if(isset($term_meta['category_featured_image'])){
            $category_featured_image=$term_meta['category_featured_image'];
        }

        if(isset($term_meta['category_attach_id'])){
            $category_attach_id=$term_meta['category_attach_id'];
            $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'wpestate_property_featured');
            $category_featured_image_url=$category_featured_image[0];
        }

        if(isset($term_meta['category_tax'])){
            $category_tax=$term_meta['category_tax'];
            $term= get_term( $place_id, $category_tax);
            if( isset($term->name) ){
                $category_name=$term->name;
                $category_count = $term->count;
            }

        }

         if(isset($term_meta['category_tagline'])){
            $category_tagline=$term_meta['category_tagline'];
        }

        $term_link =  get_term_link( $place_id, $category_tax );
        if ( is_wp_error( $term_link ) ) {
            $term_link='';
        }


        if($category_featured_image_url==''){
            $category_featured_image_url=get_template_directory_uri().'/img/defaultimage.jpg';
        }

        $return_string .='<div class="places_wrapper '.$extra_class_name.' '.$type_class.' "   data-link="'.$term_link.'"><div class="listing-hover-gradient"></div><div class="listing-hover"></div>';
        $return_string .= '<div class="places1 featuredplace" style="background-image:url('.$category_featured_image_url.');'.$places_style.'"></div>';

        $return_string .= '<div class="category_name">';
          if ( isset($attributes['type']) && $attributes['type']=='type1' ){
            $return_string .='<div class="featured_place_count">'.$category_count .' '.__('Listings','wprentals-core' ).'</div>';
        }
        $return_string .='<a class="featured_listing_title" href="'.$term_link.'">'.stripslashes($category_name).'</a>';

        $return_string .= '<div class="category_tagline">'.stripslashes($category_tagline).'</div>';

            if ( isset($attributes['type']) && $attributes['type']=='type3' ){
                $return_string .='<div class="featured_place_count">'.$category_count .' '.__('Listings','wprentals-core' ).'</div>';
                $return_string .= '<div class="featured_more"><a href="' . $term_link . '">' . __('Discover', 'wprentals-core') . '</a> <i class="fas fa-chevron-right"></i></div>';
            }

        $return_string .= '</div>';

        if ( isset($attributes['type']) && $attributes['type']=='type3' ){
            $return_string .='<div class="places_label">' . $places_label . ' </div>';
        }

        $return_string .= '';

        $return_string .='</div>';

    return $return_string;


}

endif; // end   wpestate_featured_agent


/*
*
*
* Featured places list
*
*
*/

if( !function_exists('wpestate_places_list_function') ):
function wpestate_places_list_function($attributes, $content = null) {
    $place_list         =   '';
    $return_string      =   '';
    $extra_class_name   =   '';
    $spaces_unit        =   '';

    $attributes = shortcode_atts(
        array(
            'place_list'                       => '',
            'place_per_row'                    => 4,
            'spaces_unit'                      => 8,
            'extra_class_name'                 => '',
            'design_type'                      => 'type1',
        ), $attributes) ;



    if ( isset($attributes['place_list']) ){
        $place_list=$attributes['place_list'];
    }

    if ( isset($attributes['place_per_row']) ){
        $place_per_row=$attributes['place_per_row'];
    }

    if ( isset($attributes['spaces_unit']) ){
        $spaces_unit=intval($attributes['spaces_unit']);
    }



    if($place_per_row>5){
        $place_per_row=5;
    }

    if( isset($attributes['extra_class_name'])){
        $extra_class_name=$attributes['extra_class_name'];
    }

    $all_places_array=  explode(',', $place_list);
    foreach($all_places_array as $place_id){

        $place_id=intval($place_id);
        if( $place_id!=0 || $place_id!=''){
            $category_attach_id='';
            $category_tax='';
            $category_featured_image='';
            $category_name='';
            $category_featured_image_url='';
            $term_meta = get_option( "taxonomy_$place_id");
            $category_tagline='';

            if(isset($term_meta['category_featured_image'])){
                $category_featured_image=$term_meta['category_featured_image'];
            }

            if(isset($term_meta['category_attach_id'])){
                $category_attach_id=$term_meta['category_attach_id'];
                if($attributes['design_type']=='type1'){
                    $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'wpestate_property_places');
                }else{
                    $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'wpestate_property_listings');
                }
                $category_featured_image_url=$category_featured_image[0];
            }
            $category_count=0;
            if(isset($term_meta['category_tax'])){
                $category_tax=$term_meta['category_tax'];
                $term= get_term( $place_id, $category_tax);
                $category_name='';
                if(isset($term->name)){
                    $category_name=$term->name;
                }
                $category_count='';
                if(isset($term->count)){
                    $category_count=$term->count;
                }
            }

             if(isset($term_meta['category_tagline'])){
                $category_tagline=  stripslashes( $term_meta['category_tagline'] );
            }

            $term_link =  get_term_link( $place_id, $category_tax );
            if ( is_wp_error( $term_link ) ) {
                $term_link='';
            }


            if($category_featured_image_url==''){
                $category_featured_image_url=get_template_directory_uri().'/img/defaultimage.jpg';
            }


            if(intval($spaces_unit)!= 0 ){
                $extra_class_name.=' places_wrapper_no_shadow ';
            }

            if($attributes['design_type']=='type1'){
                $return_string .= '<div class="places_wrapper_elementor  places_wrapper'.$place_per_row.' " style="">';
                $return_string .= '<div class="places_wrapper'.$extra_class_name.'" data-link="'.esc_url($term_link).'" >';
                $return_string .= '<div class="listing-hover-gradient"></div><div class="listing-hover" style="left:'.$spaces_unit.'px;right:'.$spaces_unit.'px;"></div>';
                $return_string .= '<div class="places_height places'.$place_per_row.'" style="background-image:url('.$category_featured_image_url.')"></div>';
                $return_string .= '<div class="category_name"><a class="featured_listing_title" href="'.$term_link.'">'.$category_name.'</a>';
                $return_string .= '<div class="category_tagline">'.$category_tagline.'</div></div>';
                $return_string .= '</div>';
                $return_string .= '</div>';

            }else{
                $place_per_row_class='col-md-4';
                if($place_per_row==1){
                    $place_per_row_class='col-md-12';
                }else if($place_per_row==2){
                     $place_per_row_class='col-md-6';
                }else if($place_per_row==3){
                     $place_per_row_class='col-md-4';
                }else if($place_per_row==4){
                     $place_per_row_class='col-md-3';
                }else if($place_per_row==5){
                    $place_per_row_class='col-md-24';
                }

                
                
                $return_string .= '<div class="places_wrapper_design_2_wrapper '.esc_attr($place_per_row_class).'" data-link="'.esc_url($term_link).'"><div class="places_wrapper places_wrapper_design_2" data-link="'.$term_link.'" >';
                $return_string .= '<div class="listing-hover"></div>';
                $return_string .= '<div class="places" style="background-image:url('.$category_featured_image_url.')"></div>';
             
                $return_string .= '</div>';
                
                $return_string .= '<div class="category_name">';
                $return_string .= '<a class="featured_listing_title" href="'.$term_link.'">'.$category_name.'</a>'; 
                 
                $return_string .= '<div class="place_counter">';
            
            $return_string .=sprintf(  _n('%d listing', '%d listings', $category_count, 'wprentals'), $category_count );
            
                $return_string .= '</div>';
                $return_string .= '</div></div>';

            }
            
          
        }

    }


    return $return_string;

}
endif;
