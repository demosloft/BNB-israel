<?php

if( !function_exists('wpestate_show_search_field') ):
         
    function  wpestate_show_search_field($position,$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key){
        $adv_search_what        =   wprentals_get_option('wp_estate_adv_search_what');
        $adv_search_label       =   wprentals_get_option('wp_estate_adv_search_label'); 
        $adv_search_how         =   wprentals_get_option('wp_estate_adv_search_how');
        $adv_search_icon        =   wprentals_get_option('wp_estate_search_field_label');
        
        $allowed_html=array();
        if($position=='mainform'){
            $appendix='';
        }else if($position=='sidebar') {
            $appendix='sidebar-';
        }else if($position=='shortcode') {
            $appendix='shortcode-';  
        }else if($position=='mobile') {
            $appendix='mobile-';
        }else if($position=='half') {
            $appendix='half-';
        }
     
        $return_string='';
        $icons_css='';
           
        if($search_field=='none'){
            $return_string=''; 
        }else if(strtolower($search_field)=='location'){
            
            $return_string.='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
            $return_string.=wpestate_search_location_field(wp_kses($adv_search_label[$key],$allowed_html));
            
        }
        else if($search_field=='types'){
           
            if(isset($_GET['filter_search_action'][0]) && trim($_GET['filter_search_action'][0])!='' && $_GET['filter_search_action'][0]!='all'){
                $full_name          =   get_term_by('slug', ( ( $_GET['filter_search_action'][0] ) ),'property_action_category');
                $adv_actions_value  =   $adv_actions_value1 = $full_name->name;
            }else{
                $adv_actions_value  =   wpestate_category_labels_dropdowns('second');
                $adv_actions_value1 =   'all';
            } 

            $return_string='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
            $return_string  .=   wpestate_build_dropdown_adv($appendix,'actionslist','adv_actions',$adv_actions_value,$adv_actions_value1,'filter_search_action',$action_select_list);
           


        }else if($search_field=='categories'){
            
            if( isset($_GET['filter_search_type'][0]) && trim($_GET['filter_search_type'][0])!=''  && $_GET['filter_search_type'][0]!='all' ){
                $full_name = get_term_by('slug', esc_html( wp_kses($_GET['filter_search_type'][0], $allowed_html) ),'property_category');
                $adv_categ_value    =   $adv_categ_value1   =   $full_name->name;
            }else{
                $adv_categ_value    =    wpestate_category_labels_dropdowns('main');
                $adv_categ_value1   =   'all';
            }
            
            $return_string='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
            $return_string.=wpestate_build_dropdown_adv($appendix,'categlist','adv_categ',$adv_categ_value,$adv_categ_value1,'filter_search_type',$categ_select_list);
          

        }  else if($search_field=='cities'){
            
            if(isset($_GET['advanced_city']) && trim($_GET['advanced_city'])!='' && $_GET['advanced_city']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses( $_GET['advanced_city'], $allowed_html) ),'property_city');
                $advanced_city_value    =   $advanced_city_value1=$full_name->name;
            }else{
                $advanced_city_value    =   __('All Cities','wprentals');
                $advanced_city_value1   =   'all';
            } 
            
            $return_string='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
            $return_string.=wpestate_build_dropdown_adv($appendix,'adv-search-city','advanced_city',$advanced_city_value,$advanced_city_value1,'advanced_city',$select_city_list);
           
            
        }else if($search_field=='areas'){

            if(isset($_GET['advanced_area']) && trim($_GET['advanced_area'])!=''  && $_GET['advanced_area']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses($_GET['advanced_area'], $allowed_html) ),'property_area');
                $advanced_area_value    =   $advanced_area_value1= $full_name->name;
            }else{
                $advanced_area_value    =   __('All Areas','wprentals');
                $advanced_area_value1   =   'all';
            }
            $return_string='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
            $return_string.=wpestate_build_dropdown_adv($appendix,'adv-search-area','advanced_area',$advanced_area_value,$advanced_area_value1,'advanced_area',$select_area_list);
         
        } else if($search_field=='guest_no'){
                if(isset($_GET['guest_no'])){
                    $guest_list             =   wpestate_get_guest_dropdown('', intval($_GET['guest_no']) );
                }else{
                    $guest_list             =   wpestate_get_guest_dropdown();
                }
                $return_string='<div class="dropdown form-control">
                <div data-toggle="dropdown" id="guest_no" class="filter_menu_trigger" 
                    data-value="';
                    if(isset($_GET['guest_no'])){
                        $return_string.= intval(esc_attr($_GET['guest_no']));
                    }else{
                        $return_string.= 'all';
                    }
                $return_string.='">'; 
                
                if( isset($_GET['guest_no']) && intval($_GET['guest_no'])!=0 ){
                   $return_string.= intval($_GET['guest_no']).' '.esc_html__('guests','wprentals');
                }else{
                    $return_string.=esc_html__('Guests','wprentals');
                }
                
                $return_string.='<span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="guest_no" id="guest_no_main" 
                    value="'; 
                    if(isset($_GET['guest_no'])){
                        $return_string.= intval(esc_attr($_GET['guest_no']));
                    }
                $return_string.='">
                <ul class="dropdown-menu filter_menu"  id="guest_no_main_list" role="menu" aria-labelledby="guest_no">
                    '.$guest_list.'
                </ul>        
            </div>';
        }else {
                $show_dropdowns          =   wprentals_get_option('wp_estate_show_dropdowns','');
                $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                $slug         =   sanitize_key($string);
              
                $label=$adv_search_label[$key];
                if (function_exists('icl_translate') ){
                    $label     =   icl_translate('wprentals','wp_estate_custom_search_'.$label, $label ) ;
                }
            
              //  print '--- '.$adv_search_what[$key];
                
                if ( $adv_search_what[$key]=='property country'){
                    ////////////////////////////////  show country list
                    $return_string='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
                    $return_string .=  wpestate_country_list_adv_search($appendix,$slug);
                     
                } else if ( $adv_search_what[$key]=='property price'){
                    ////////////////////////////////  show price form
                    $return_string = wpestate_price_form_adv_search($position,$slug,$label);
                
                    
                } else if ( $show_dropdowns=='yes' && ( $adv_search_what[$key]=='property rooms' ||  $adv_search_what[$key]=='property bedrooms' ||  $adv_search_what[$key]=='property bathrooms' ) ){
                    $i=0;
                    if (function_exists('icl_translate') ){
                        $label     =   icl_translate('wprentals','wp_estate_custom_search_'.$adv_search_label[$key], $adv_search_label[$key] ) ;
                    }else{
                       $label= $adv_search_label[$key];
                    }
                    $rooms_select_list =   ' <li role="presentation" data-value="all">'.  $label.'</li>';
                    while($i < 10 ){
                        $i++;
                        $rooms_select_list.='<li data-value="'.esc_attr($i).'"  value="'.esc_attr($i).'">'.esc_html($i).'</li>';
                    }
                    $return_string='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
                    $return_string.=wpestate_build_dropdown_adv($appendix,'search-'.$slug,$slug,$label,'all',$slug,$rooms_select_list);
                   
                }else{ 
                    $custom_fields = wprentals_get_option('wpestate_custom_fields_list','');
                 
                    $i=0;
                    $found_dropdown=0;
                    ///////////////////////////////// dropdown check
                    if( !empty($custom_fields)){  
                        while($i< count($custom_fields) ){          
                            $name       =   $custom_fields[$i][0];
                          
                            $slug_drop       =   str_replace(' ','-',$name);

                            if( $slug_drop == $adv_search_what[$key] && $custom_fields[$i][2]=='dropdown' ){
                              
                                $found_dropdown=1;
                                $front_name=sanitize_title($adv_search_label[$key]);
                                if (function_exists('icl_translate') ){
                                    $initial_key = apply_filters('wpml_translate_single_string', trim($adv_search_label[$key]),'custom field value','custom_field_value'.cc );
                                    $action_select_list =   ' <li role="presentation" data-value="all"> '. stripslashes($initial_key) .'</li>';  
                                }else{
                                    $action_select_list =   ' <li role="presentation" data-value="all">'. stripslashes( $adv_search_label[$key]).'</li>';
                                }
                                
                                $dropdown_values_array=explode(',',$custom_fields[$i][4]);
                             
                                foreach($dropdown_values_array as $drop_key=>$value_drop){
                                    $original_value_drop    =$value_drop;
                                    if (function_exists('icl_translate') ){
                                        
                                        $value_drop = apply_filters('wpml_translate_single_string', trim($value_drop),'custom field value','custom_field_value'.$value_drop );
                                    }
                                    $action_select_list .=   ' <li role="presentation" data-value="'.esc_attr($original_value_drop).'">'. stripslashes(trim($value_drop)).'</li>';
                                }
                                $front_name=sanitize_title($adv_search_label[$key]);
                                if(isset($_GET[$front_name]) && $_GET[$front_name]!='' && $_GET[$front_name]!='all'){
                                    $advanced_drop_value= esc_attr( wp_kses( $_GET[$front_name], $allowed_html) );
                                    $advanced_drop_value1='';
                                }else{
                                    $advanced_drop_value= $label;
                                    $advanced_drop_value1='all';
                                } 
                                $front_name=  wpestate_limit45($front_name);
                                               
                                $return_string='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
                                $return_string.=wpestate_build_dropdown_adv($appendix,$front_name,$front_name,$advanced_drop_value,$advanced_drop_value1,$front_name,$action_select_list);
                            
                              
                            }
                            $i++;
                        }
                    }  
                    ///////////////////// end dropdown check
                    
                    if($found_dropdown==0){
                        //////////////// regular field 
                        $return_string='';
                       
                        
                        $return_string.='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
                        
                        if($search_field=='check_in'){
                            $item_input_id='check_in';
                        }else if($search_field=='check_out'){
                            $item_input_id='check_out';
                        }else{
                            $item_input_id=wp_kses($appendix.$slug,$allowed_html);
                        }
                        
                        
                        $return_string.='<input type="text"  xxxy  id="'.$item_input_id.'"  name="'.wp_kses($slug,$allowed_html).'"'
                                . ' placeholder="'. stripslashes(wp_kses($label,$allowed_html)).'" '
                                . 'class="advanced_select form-control custom_icon_class_input" value="';
                        if (isset($_GET[$slug])) {
                            $return_string.=  esc_attr( $_GET[$slug] );
                        }
                        $return_string.='"  />';
                        
                
                        
                        
                        ////////////////// apply datepicker if is the case
                        if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller' || $search_field=='check_in' || $search_field=='check_out'){
                            wpestate_date_picker_translation($appendix.$slug);
                        }
                    }
                    
                }

            } 
            print trim($return_string.'<style>'.trim($icons_css).'</style>');//escaped above
        }
endif; // 



if( !function_exists('wprentals_show_extended_search') ): 
    function wprentals_show_extended_search($tip){
       
        $terms = get_terms( array(
            'taxonomy' => 'property_features',
            'hide_empty' => false,
        ) );
        
        foreach($terms as $key => $term){
            if (defined('ICL_SITEPRESS_VERSION')) {
                $term_name = $term->name;
                $term_slug = $term->slug;

                $current_language = apply_filters('wpml_current_language', NULL);
                $default_language = apply_filters('wpml_default_language', NULL);
                if ($current_language != $default_language) {
                    $trid = apply_filters('wpml_element_trid', NULL, $term->term_id, 'tax_property_features');
                    $term_translations = apply_filters('wpml_get_element_translations', NULL, $trid, 'tax_property_features');
                    $original_term_id = $term_translations[$default_language]->element_id;
                    do_action('wpml_switch_language', $default_language);
                    $original_term = get_term($original_term_id);
                    do_action('wpml_switch_language', $current_language);
                    $term_slug = $original_term->slug;
                }
                $label_array[$term_slug]=$term_name;
            }else{
                $label_array[ $term->slug ]=$term->name;
            }
            
            
            
            
            
        }
    
       
        
        print '<div class="adv_extended_options_text" id="adv_extended_options_text_'.$tip.'">'.__('More Search Options','wprentals').'</div>';
               print '<div class="extended_search_check_wrapper">';
               print '<span id="adv_extended_close_'.$tip.'" class="adv_extended_close_button" ><i class="fas fa-times"></i></span>';

               $advanced_exteded   =   wprentals_get_option( 'wp_estate_advanced_exteded'); 

               foreach($advanced_exteded as $value){
                   
                    $input_name =   $value;
                    $label      =   $label_array[$value];
                    
                   
                    if($value!='none' && $label!=''){
                        
                        
                        
                        
                        
                        
                        $check_selected='';
                        if( isset($_GET[$input_name]) && $_GET[$input_name]=='1'  ){
                            $check_selected=' checked ';  
                        }
                        print
                        '<div class="extended_search_checker">
                            <input type="checkbox" id="'.$input_name.$tip.'" name="'.$value.'" value="1" '.$check_selected.'>
                            <label for="'.$input_name.$tip.'">'.($label).'</label>
                        </div>';
                    }
               }

        print '</div>';    
    }
endif;



if( !function_exists('wpestate_build_dropdown_adv') ):
function wpestate_build_dropdown_adv($appendix,$ul_id,$toogle_id,$values,$values1,$get_var,$select_list){
    $extraclass='';
    $caret_class='';
    $wrapper_class='';
    $return_string='';
    $is_half=0;
    $allowed_html =array();
            
    if($appendix==''){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
    }else  if($appendix=='sidebar-'){
        $extraclass=' sidebar_filter_menu  ';
        $caret_class= ' caret_sidebar '; 
    } else  if($appendix=='shortcode-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = 'listing_filter_select';
    } else  if($appendix=='mobile-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = '';
    }else  if($appendix=='half-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = '';
      
        $appendix='';
        $is_half=1;
    }
    

        if ($get_var=='filter_search_type' || $get_var== 'filter_search_action'){
            if (isset(  $_GET[$get_var] ) && trim( $_GET[$get_var][0] ) !='' ){
                $getval         =   ucwords( esc_html( $_GET[$get_var][0] ) ); 
                $real_title     =   wpestate_return_title_from_slug($get_var,$getval);
                //remved09.02
                // $real_slug      =   esc_attr( wp_kses(  $_GET[$get_var] ,$allowed_html) );
                $getval         =   str_replace('-', ' ', $getval); 
                $show_val       =   $real_title;
                $current_val    =   $getval;
                $current_val1   =   $real_title;
            }else{
                $current_val  = $values;
                $show_val     = $values;
                $current_val1 = $values1;
            }
        }else{
            $get_var=sanitize_key($get_var);
           
            if (isset(  $_GET[$get_var] ) && trim( $_GET[$get_var]) !='' ){
                $getval         =   ucwords( esc_html ( wp_kses ( $_GET[$get_var] ,$allowed_html )  )   );
                $real_title     =   wpestate_return_title_from_slug($get_var,$getval);
                //removed09.02
                // $real_slug      =   esc_html( wp_kses( $_GET[$get_var], $allowed_html) );
                $getval         =   str_replace('-', ' ', $getval);
                $current_val    =   $getval;
                $show_val       =   $real_title;
                $current_val1   =   $real_title;
            }else{
                $current_val = $values;
                $show_val     = $values;
                $current_val1 = $values1;
            }
        }
                

 
        $return_string.=  '<div class="dropdown  custom_icon_class icon_'.$ul_id.' form-control '.$wrapper_class.'">
        <div data-toggle="dropdown" id="'.sanitize_key( $appendix.$toogle_id ).'" class="'.$extraclass.'" xx '.$values1.' '.$values.' data-value="'.( esc_attr( $current_val1) ).'">';
          
     
            
            if (  $get_var=='filter_search_type' || $get_var=='filter_search_action' || $get_var=='advanced_city' || $get_var=='advanced_area' || $get_var=='advanced_conty' || $get_var=='advanced_contystate'){
                if($show_val=='All'){
                    //sorry for this ugly fix
                    if($get_var=='filter_search_type'){
                        $return_string.=   wpestate_category_labels_dropdowns('main');
                    }else if($get_var=='filter_search_action'){
                        $return_string.=  wpestate_category_labels_dropdowns('second');
                    }else if($get_var=='advanced_city' ){
                        $return_string.= __('All Cities','wprentals');
                    }else if($get_var=='advanced_area'){
                        $return_string.=__('All Areas','wprentals');
                    }else if($get_var=='advanced_conty'){
                        $return_string.= __('All Actions','wprentals');
                    }else if($get_var=='advanced_contystate'){
                        $return_string.= __('All Counties/States','wprentals');
                    }
                    
                    
                    
                }else{
                    $return_string.= $show_val;     
                }
                
            }else{
                //$return_string.= str_replace('-',' ',$show_val);
                if (function_exists('icl_translate') ){
                    $show_val = apply_filters('wpml_translate_single_string', trim($show_val),'custom field value','custom_field_value'.$show_val );
                }
               //$return_string.= $show_val;
                //$return_string.= $values;
                
                if($show_val=='all' || $show_val=='All'){
                    $return_string.= stripslashes( $values );
                }else{
                    $return_string.= stripslashes( $show_val );
                }
            }
                    

            $return_string.= '
            <span class="caret '.$caret_class.'"></span>
            </div>';           
                     
                    
            if ($get_var=='filter_search_type' || $get_var== 'filter_search_action'){
                $return_string.=' <input type="hidden" name="'.$get_var.'[]" value="';
                if(isset($_GET[$get_var][0])){
                    $return_string.= strtolower(  esc_attr( $_GET[$get_var][0] ) );
                }
            }else{
                $return_string.=' <input type="hidden" name="'.sanitize_key( $get_var ).'" value="';
                if(isset($_GET[$get_var])){
                    $return_string.= strtolower( esc_attr ( $_GET[$get_var] ) );
                }
            }

                $return_string.='">
                <ul  id="'.$appendix.$ul_id.'" class="dropdown-menu filter_menu" role="menu" aria-labelledby="'.$appendix.$toogle_id.'">
                    '.$select_list.'
                </ul>        
            </div>';
                    
                       
    return $return_string;                
}
endif;


if( !function_exists('wpestate_price_form_adv_search') ): 
    function wpestate_price_form_adv_search($position,$slug,$label){
        $return_string='';
      
        
        if($position=='mainform'){
            $slider_id      =   'slider_price';
            $price_low_id   =   'price_low';
            $price_max_id   =   'price_max';
            $ammount_id     =   'amount';
            
        }else if($position=='sidebar') {
            $slider_id      =   'slider_price_widget';
            $price_low_id   =   'price_low_widget';
            $price_max_id   =   'price_max_widget';
            $ammount_id     =   'amount_wd';
            
        }else if($position=='shortcode') {
            $slider_id      =   'slider_price_sh';
            $price_low_id   =   'price_low_sh';
            $price_max_id   =   'price_max_sh';
            $ammount_id     =   'amount_sh';
            
        }else if($position=='mobile') {
            $slider_id      =   'slider_price_mobile';
            $price_low_id   =   'price_low_mobile';
            $price_max_id   =   'price_max_mobile';
            $ammount_id     =   'amount_mobile';
           
        }else if($position=='half') {
            $slider_id='slider_price';
            $price_low_id   =   'price_low';
            $price_max_id   =   'price_max';
            $ammount_id     =   'amount';
            
        }
        
   
        $min_price_slider   = ( floatval(wprentals_get_option('wp_estate_show_slider_min_price','')) );
        $max_price_slider   = ( floatval(wprentals_get_option('wp_estate_show_slider_max_price','')) );

        if(isset($_GET['price_low'])){
            $min_price_slider   =  floatval($_GET['price_low']) ;
        }

        if(isset($_GET['price_low'])){
            $max_price_slider=  floatval($_GET['price_max']) ;
        }

        $wpestate_where_currency=   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
        $wpestate_currency      =   esc_html( wprentals_get_option('wp_estate_currency_symbol', '') );

        $price_slider_label = wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$wpestate_currency,$wpestate_where_currency);




        $return_string.='<div class="adv_search_slider">';

        $return_string.=' 
            <p>
                <label for="amount">'. __('Price range:','wprentals').'</label>
                <span id="'.$ammount_id.'"  style="border:0;  font-weight:bold;">'.$price_slider_label.'</span>
            </p>
            <div id="'.$slider_id.'"></div>';
        $custom_fields = wprentals_get_option('wpestate_currency',''); 
        if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
            $i=intval($_COOKIE['my_custom_curr_pos']);

            if( !isset($_GET['price_low']) && !isset($_GET['price_max'])  ){
                $min_price_slider       =   $min_price_slider * $custom_fields[$i][2];
                $max_price_slider       =   $max_price_slider * $custom_fields[$i][2];
            }
        }

        $return_string.='
            <input type="hidden" id="'.$price_low_id.'"  name="price_low"  value="'.$min_price_slider.'"/>
            <input type="hidden" id="'.$price_max_id.'"  name="price_max"  value="'.$max_price_slider.'"/>
        </div>';

        
        return $return_string;
}
endif;


if( !function_exists('wpestate_return_title_from_slug') ):
function wpestate_return_title_from_slug($get_var,$getval){
    if ( $get_var=='filter_search_type' ){ 
        if( $getval!=='All'){
            $taxonomy   =   "property_category"; 
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
       
    }
    else if( $get_var== 'filter_search_action' ){
        $taxonomy="property_action_category"; 
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }
    else if( $get_var== 'advanced_city' ){
        $taxonomy="property_city";
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }
    else if( $get_var== 'advanced_area'){
        $taxonomy="property_area";
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }
    else if( $get_var== 'advanced_contystate' ){
        $taxonomy="property_county_state";
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }else{
        return $getval;
    }
    
    
    
    
    
};
endif;


function wpestate_search_location_field($label,$position=''){
    $return                             =   '';
    $show_adv_search_general            =   wprentals_get_option('wp_estate_wpestate_autocomplete','');
    $wpestate_internal_search           =   '';
    $search_location                    =   '';
    $search_location_tax                =   'tax';
    $advanced_city                      =   '';
    $advanced_area                      =   '';
    $advanced_country                   =   '';
    $property_admin_area                =   '';
    
    if($position=='half' || $position=='mainform'){
        $position='';
    }
        
    if(isset($_GET['search_location'])){
        $search_location = sanitize_text_field($_GET['search_location']);
    }


    if(isset($_GET['stype']) && $_GET['stype']=='meta'){
        $search_location_tax = 'meta';
    }
    
    if(isset($_GET['advanced_city']) ){
        $advanced_city = sanitize_text_field($_GET['advanced_city']);
    }
    
    if(isset($_GET['advanced_area']) ){
        $advanced_area = sanitize_text_field($_GET['advanced_area']);
    }
    
    if(isset($_GET['advanced_country']) ){
        $advanced_country = sanitize_text_field($_GET['advanced_country']);
    }
    
     if(isset($_GET['property_admin_area']) ){
        $property_admin_area = sanitize_text_field($_GET['property_admin_area']);
    }
    
    
    if($show_adv_search_general=='no'){
        $wpestate_internal_search='_autointernal';
        $return.= '<input type="hidden" class="stype" id="stype" name="stype" value="'.$search_location_tax.'">';
    }
    $wpestate_autocomplete_use_list             =   wprentals_get_option('wp_estate_wpestate_autocomplete_use_list','');  
    if ($wpestate_autocomplete_use_list=='yes' && $show_adv_search_general=='no'){
        $return.= wprentals_location_custom_dropwdown($_REQUEST,$label);
    }else{
        $return.=  '<input type="text"    id="search_location'.$position.$wpestate_internal_search.'"      class="form-control" name="search_location" placeholder="'.esc_html__('Where do you want to go ?','wprentals').'" value="'.$search_location.'"  >';              
    } 
                
            
    $return.='  <input type="hidden" id="advanced_city'.esc_attr($position).'"      class="form-control" name="advanced_city" data-value=""   value="'.esc_attr($advanced_city).'" >              
                <input type="hidden" id="advanced_area'.esc_attr($position).'"      class="form-control" name="advanced_area"   data-value="" value="'.esc_attr($advanced_area).'" >              
                <input type="hidden" id="advanced_country'.esc_attr($position).'"   class="form-control" name="advanced_country"   data-value="" value="'.esc_attr($advanced_country).'" >              
                <input type="hidden" id="property_admin_area'.esc_attr($position).'" name="property_admin_area" value="'.esc_attr($property_admin_area).'">';
    return $return;
    include(locate_template('libs/internal_autocomplete_wpestate.php'));
}




if( !function_exists('wpestate_country_list_adv_search') ): 
    function wpestate_country_list_adv_search($appendix,$selected,$label){
        $country_list=wpestate_country_list_search($selected);
        $allowed_html = array();
        if(isset($_GET['property_country']) && $_GET['property_country']!='' && $_GET['property_country']!='all'){
            $advanced_country_value=  esc_html( wp_kses($_GET['property_country'], $allowed_html ) );
        }else{
            $advanced_country_value=__('All Countries','wprentals');

        } 
        $return_string=wpestate_build_dropdown_adv_new($appendix,'property_country',$advanced_country_value,$country_list,$label);
        return $return_string;         
    }
endif;  


if( !function_exists('wpestate_country_list_search') ): 
function wpestate_country_list_search($selected) {
   
    $countries              =   wpestate_return_country_list_translated();
    $country_select_list    =   '';
    foreach ($countries as $country) {
        $country_select_list.='<li role="presentation" data-value="'.esc_attr($country).'">'.$country.'</li>';
    }
    
    return $country_select_list;
}
endif; // end   wpestate_country_list 
