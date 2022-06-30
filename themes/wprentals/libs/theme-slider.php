<?php

if( !function_exists('wpestate_theme_slider') ):

function  wpestate_theme_slider(){
    $theme_slider           =   wprentals_get_option( 'wp_estate_theme_slider'); 
    $slider_cycle           =   wprentals_get_option( 'wp_estate_slider_cycle'); 
    $theme_slider_manual    =   wprentals_get_option( 'wp_estate_theme_slider_manual'); 
   
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Select Properties ','wprentals').'</div>
        <div class="option_row_explain">'.__('Select properties for slider - *hold CTRL for multiple select.For speed reason we show only the first 50 listings. If you want to add litings that are not present in this section use the "manual" field below.  ','wprentals').'</div>'; 
    $args = array(      'post_type'         =>  'estate_property',
                        'post_status'       =>  'publish',
                        'paged'             =>  0,
                        'posts_per_page'    =>  50,
                        'cache_results'           =>    false,
                        'update_post_meta_cache'  =>    false,
                        'update_post_term_cache'  =>    false,
                 );

        $recent_posts = new WP_Query($args);
        print '<select name="theme_slider[]"  id="theme_slider"  multiple="multiple">';
        while ($recent_posts->have_posts()): $recent_posts->the_post();
             $theid=get_the_ID();
             print '<option value="'.$theid.'" ';
             if( is_array($theme_slider) && in_array($theid, $theme_slider) ){
                 print ' selected="selected" ';
             }
             print'>'.get_the_title().'</option>';
        endwhile;
        print '</select>';
        
    print '</div>';    
    
     print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Add Properties in theme slider by ID. Place here the Listings Id separated by comma.','wprentals').'</div>
        <div class="option_row_explain">'.__('Place here the Listings Id separated by comma. Will Overwrite the above selection!','wprentals').'</div>    
            <input  type="text" id="theme_slider_manual" name="theme_slider_manual"  value="'.$theme_slider_manual.'"/> 
        </div>';
     
     
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Number of milisecons before auto cycling an item','wprentals').'</div>
        <div class="option_row_explain">'.__('Number of milisecons before auto cycling an item (5000=5sec).Put 0 if you don\'t want to autoslide. ','wprentals').'</div>    
            <input  type="text" id="slider_cycle" name="slider_cycle"  value="'.$slider_cycle.'"/> 
        </div>';
    
    
    $cache_array = array('type1', 'type2');
    $theme_slider_type_select = wpestate_dropdowns_theme_admin($cache_array, 'theme_slider_type');
    print'<div class="estate_option_row">
        <div class="label_option_row">' . __('Design Type?', 'wprentals') . '</div>
        <div class="option_row_explain">' . __('Select the design type.', 'wprentals') . '</div>    
            <select id="theme_slider_type" name="theme_slider_type">
                ' . $theme_slider_type_select . '
            </select>
        </div>';


    print ' <div class="estate_option_row_submit">
        <input type="submit" name="submit"  class="new_admin_submit " value="'.__('Save Changes','wprentals').'" />
        </div>';
     
}

endif; // end wpestate_theme_slider





if( !function_exists('wpestate_present_theme_slider') ):
    function wpestate_present_theme_slider(){
        $attr=array(
            'class'	=>'img-responsive'
        );
        $theme_slider   =   wprentals_get_option( 'wp_estate_theme_slider_type'); 

        
        if($theme_slider=='type2'){
            wpestate_present_theme_slider_type2();
            return;
        }
        $theme_slider   =   wprentals_get_option( 'wp_estate_theme_slider', ''); 
        if(empty($theme_slider)){
            return; // no listings in slider - just return
        }
        
        
        $wpestate_currency          =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
        $wpestate_where_currency    =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );

        $counter    =   0;
        $slides     =   '';
        $indicators =   '';
        $args = array(  
                    'post_type'        => 'estate_property',
                    'post_status'      => 'publish',
                    'post__in'         => $theme_slider,
                    'posts_per_page' => -1
                  );


        $recent_posts = new WP_Query($args);
        $slider_cycle   =   wprentals_get_option( 'wp_estate_slider_cycle'); 
        if($slider_cycle == 0){
            $slider_cycle = false;
        }
        
        $extended_search    =   wprentals_get_option('wp_estate_show_adv_search_extended','');
        $extended_class     =   '';

        if ( $extended_search =='yes' ){
            $extended_class='theme_slider_extended';
        }

        $search_type        =   wprentals_get_option('wp_estate_adv_search_type','');
        $theme_slider_class =   '';
        if($search_type != 'oldtype'){ 
            $theme_slider_class = 'theme_slider_wrapper_type2';
        }
        
        print '<div class=" '.$theme_slider_class.' '.$extended_class.'   " id="estate-carousel_slick">';

        while ($recent_posts->have_posts()): $recent_posts->the_post();
            $theid=get_the_ID();
            $slide= get_the_post_thumbnail_url( $theid, 'wpestate_property_full_map' );

          
            $rental_type    =   wprentals_get_option('wp_estate_item_rental_type');       
            $rental_type    =   wprentals_get_option('wp_estate_item_rental_type');
            $booking_type   =   wprentals_return_booking_type($theid);
   
          
 $slides.= '
             <div class="item-slick ">
               
                <div class="slider-content-wrapper">  
                  <div class="item-version">'.sprintf("%02d",  ($counter+1) ).'</div>
                    <div class="slider-content">
                    
                        <div class="theme-slider-price">
                            <div class="price-slider-wrapper">
                                <span class="price-slider">';
                                $price_per_guest_from_one       =   floatval( get_post_meta($theid, 'price_per_guest_from_one', true) ); 
                                if($price_per_guest_from_one==1){
                                    $slides.=  wpestate_show_price($theid,$wpestate_currency,$wpestate_where_currency,1).'</span>/'.esc_html__( 'guest','wprentals');
                                }else{
                                    $slides.=  wpestate_show_price($theid,$wpestate_currency,$wpestate_where_currency,1).'</span>/'.wpestate_show_labels('per_night',$rental_type,$booking_type);
                                }
            
                            $slides.='
                            </div>        
                        </div>
                        

                        <div class="slider-title">
                            <h2><a href="'.esc_url ( get_permalink() ).'">'.wpestate_strip_words(get_the_title(),10).'</a> </h2>
                        </div>

                        <div class="listing-desc-slider"> 
                            <span>'.  get_the_excerpt().'  </span>
                        </div>
    
                        <a class="theme-slider-view" href="'.esc_url ( get_permalink() ).'">'.esc_html__( 'View more','wprentals').'</a>
                            
                    </div> 
                </div>  
                
                <div class="slider-content-cover"></div>  
                
                <div class="slider-content-image" style="background-image:url('.$slide.')"></div>

            </div>';                       
            $counter++;
        endwhile;
        wp_reset_query();
        print ''.$slides.'</div>';
        
        
        
    } 
endif;






if( !function_exists('wpestate_present_theme_slider_type2') ):
    function wpestate_present_theme_slider_type2(){
      $attr=array(
            'class'	=>'img-responsive'
        );

        $theme_slider   =   wprentals_get_option( 'wp_estate_theme_slider', '');
        

        if(empty($theme_slider)){
            return; // no listings in slider - just return
        }
        $wpestate_currency           =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
        $wpestate_where_currency     =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );

        $counter    =   0;
        $slides     =   '';
        $indicators =   '';
        $args = array(  
                    'post_type'        => 'estate_property',
                    'post_status'      => 'publish',
                    'post__in'         => $theme_slider,
                    'posts_per_page' => -1
                  );


        $recent_posts = new WP_Query($args);
        $slider_cycle   =   wprentals_get_option( 'wp_estate_slider_cycle'); 
        if($slider_cycle == 0){
            $slider_cycle = false;
        }
        
        $extended_search    =   wprentals_get_option('wp_estate_show_adv_search_extended','');
        $extended_class     =   '';

        if ( $extended_search =='yes' ){
            $extended_class='theme_slider_extended';
        }
        $theme_slider_type_select   =   wprentals_get_option( 'wp_estate_theme_slider_type'); 
        $search_type        =   wprentals_get_option('wp_estate_adv_search_type','');
        $theme_slider_class =   '';
        if($search_type != 'oldtype'){ 
            $theme_slider_class = 'theme_slider_search_type1';
        }
        
        print '<div class="theme_slider_wrapper theme_slider_'.$theme_slider_type_select.' '.$theme_slider_class.' carousel  slide" data-ride="carousel" data-interval="'.esc_attr($slider_cycle).'" id="estate-carousel">';

        while ($recent_posts->have_posts()): $recent_posts->the_post();
            $theid=get_the_ID();
   

            if($counter==0){
                $active=" active ";
            }else{
                $active=" ";
            }
            
            $slide= get_the_post_thumbnail_url( $theid, 'wpestate_property_full_map' );
            $rental_type    =   wprentals_get_option('wp_estate_item_rental_type');
          

    

            $rental_type                        =   wprentals_get_option('wp_estate_item_rental_type');
            $booking_type                       =   wprentals_return_booking_type($theid);
   
            $slides.= '
            <div class="item '.$active.'">
               
                <div class="slider-content-wrapper">  
                    <div class="slider-content">
                    
                        <div class="theme-slider-price">
                            <div class="price-slider-wrapper">
                                <span class="price-slider">';
                                $price_per_guest_from_one       =   floatval( get_post_meta($theid, 'price_per_guest_from_one', true) ); 
                                if($price_per_guest_from_one==1){
                                    $slides.=  wpestate_show_price($theid,$wpestate_currency,$wpestate_where_currency,1).'</span>/'.esc_html__( 'guest','wprentals');
                                }else{
                                    $slides.=  wpestate_show_price($theid,$wpestate_currency,$wpestate_where_currency,1).'</span>/'.wpestate_show_labels('per_night',$rental_type,$booking_type);
                                }
            
                            $slides.='
                            </div>        
                        </div>

                        <div class="slider-title">
                            <h2><a href="'.esc_url ( get_permalink() ).'">'.wpestate_strip_words(get_the_title(),10).'</a> </h2>
                        </div>

                        <div class="listing-desc-slider"> 
                           '.  get_the_excerpt().'
                        </div>
    
                    </div> 
                </div>  
                  
                <div class="slider-content-cover"></div>  
                <div class="slider-content-image" style="background-image:url('.$slide.')"></div>
                
               

            </div>';

            $indicators.= '
            <li data-target="#estate-carousel" data-slide-to="'.esc_attr($counter).'" class="'. esc_attr($active).'">
            </li>';

            $counter++;
        endwhile;
        wp_reset_query();
        print ' 
            <div class="carousel-inner">
              '.$slides.'
            </div>

            <ol class="carousel-indicators">
                '.$indicators.'
            </ol>
            
            <div class="carousel_type2_control_wrapper">
                <a id="carousel-control-theme-next"  class="carousel-control-theme-next" href="#estate-carousel" data-slide="next"><i class="fas fa-arrow-left"></i></a>
                <a id="carousel-control-theme-prev"  class="carousel-control-theme-prev" href="#estate-carousel" data-slide="prev"><i class="fas fa-arrow-right"></i></a>
            </div>
            
            </div>';
    } 
endif;


?>
