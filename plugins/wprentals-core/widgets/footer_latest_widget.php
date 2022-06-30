<?php
class Wpestate_footer_latest_widget extends WP_Widget {
	
	//function Wpestate_footer_latest_widget(){
    	function __construct(){
		$widget_ops = array('classname' => 'latest_listings', 'description' => 'Show latest listings.');
		$control_ops = array('id_base' => 'wpestate_footer_latest_widget');
                parent::__construct('wpestate_footer_latest_widget', 'Wp Estate: Latest Listing ', $widget_ops, $control_ops);
	}
	 
	function form($instance){
		$defaults = array('title'                       =>  'Latest Listing',
                                  'listing_no'                  =>  3,
                                   'adv_filter_search_action'   =>  '',
                                   'adv_filter_search_category' =>  '',
                                   'current_adv_filter_city'    =>  '',
                                   'current_adv_filter_area'    =>  '',
                                   'show_featured_only'         =>  ''
                    );
		$instance = wp_parse_args((array) $instance, $defaults);
                
                
                $args = array(
                    'hide_empty'    => false 
                );  

                $actions_select     =   '';
                $categ_select       =   '';
                $taxonomy           =   'property_action_category';
                $tax_terms          =   get_terms($taxonomy,$args);

                $current_adv_filter_search_action = $instance['adv_filter_search_action'];
                if($current_adv_filter_search_action==''){
                    $current_adv_filter_search_action=array();
                }


                $all_selected='';
                if(!empty($current_adv_filter_search_action) &&  in_array  (esc_html__( 'all','wprentals-core'),$current_adv_filter_search_action)  ){
                  $all_selected=' selected="selected" ';  
                }

                $actions_select.='<option value="all" '.$all_selected.'>'.esc_html__( 'all','wprentals-core').'</option>';
                if( !empty( $tax_terms ) ){                       
                    foreach ($tax_terms as $tax_term) {
                        $actions_select .= '<option value="'.$tax_term->name.'" ';
                        if( in_array  ( $tax_term->name,$current_adv_filter_search_action) ){
                          $actions_select .= ' selected="selected" ';  
                        }
                        $actions_select .=' >'.$tax_term->name.'</option>';      
                    } 
                }



                //////////////////////////////////////////////////////////////////////////////////////////
                $taxonomy           =   'property_category';
                $tax_terms          =   get_terms($taxonomy,$args);

                $current_adv_filter_search_category = $instance['adv_filter_search_category'];
                if($current_adv_filter_search_category==''){
                    $current_adv_filter_search_category=array();
                }

                $all_selected='';
                if( !empty($current_adv_filter_search_category) && $current_adv_filter_search_category[0]=='all'){
                  $all_selected=' selected="selected" ';  
                }

                $categ_select.='<option value="all" '.$all_selected.'>'.esc_html__( 'all','wprentals-core').'</option>';
                if( !empty( $tax_terms ) ){                       
                    foreach ($tax_terms as $tax_term) {
                        $categ_select.='<option value="'.$tax_term->name.'" ';
                        if( in_array  ( $tax_term->name, $current_adv_filter_search_category) ){
                          $categ_select.=' selected="selected" ';  
                        }
                        $categ_select.=' >'.$tax_term->name.'</option>';      
                    } 
                }


             //////////////////////////////////////////////////////////////////////////////////////////   

                $select_city='';
                $taxonomy = 'property_city';
                $tax_terms_city = get_terms($taxonomy,$args);
                $current_adv_filter_city =  $instance['current_adv_filter_city'];

                if($current_adv_filter_city==''){
                    $current_adv_filter_city=array();
                }

                $all_selected='';
                if( !empty($current_adv_filter_city) && $current_adv_filter_city[0]=='all'){
                  $all_selected=' selected="selected" ';  
                }

                $select_city.='<option value="all" '.$all_selected.' >'.esc_html__( 'all','wprentals-core').'</option>';
                foreach ($tax_terms_city as $tax_term) {

                    $select_city.= '<option value="' . $tax_term->name . '" ';
                    if( in_array  ( $tax_term->name, $current_adv_filter_city) ){
                          $select_city.=' selected="selected" ';  
                    }
                    $select_city.= '>' . $tax_term->name . '</option>';
                } 


             //////////////////////////////////////////////////////////////////////////////////////////   

                $select_area='';
                $taxonomy = 'property_area';
                $tax_terms_area = get_terms($taxonomy,$args);
                $current_adv_filter_area =  $instance['current_adv_filter_area']; 
                if($current_adv_filter_area==''){
                    $current_adv_filter_area=array();
                }

                $all_selected='';
                if(!empty($current_adv_filter_area) && $current_adv_filter_area[0]=='all'){
                  $all_selected=' selected="selected" ';  
                }

                $select_area.='<option value="all" '.$all_selected.'>'.esc_html__( 'all','wprentals-core').'</option>';
                foreach ($tax_terms_area as $tax_term) {
                    $term_meta=  get_option( "taxonomy_$tax_term->term_id");
                    $select_area.= '<option value="' . $tax_term->name . '" ';
                    if( in_array  ( $tax_term->name, $current_adv_filter_area) ){
                          $select_area.=' selected="selected" ';  
                    }
                    $select_area.= '>' . $tax_term->name . '</option>';
                } 

                //////////////////////////////////   
    
                   
 
                $cache_array                =   array('yes','no');
                $show_featured_only_select  =   '';
                $show_featured_only         =   $instance['show_featured_only'];
                foreach($cache_array as $value){
                    $show_featured_only_select.='<option value="'.$value.'" ';
                    if ( $show_featured_only == $value ){
                        $show_featured_only_select.=' selected="selected" ';
                    }
                    $show_featured_only_select.='>'.$value.'</option>';
                }

                 //////////////////////////////////   
                
		$display='
                <p>
                    <label for="'.$this->get_field_id('title').'">Title:</label> </br>  
                    <input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p>

                <p>
                   <label for="'.$this->get_field_id('listing_no').'">How many Listings:</label> </br>  		
                   <input id="'.$this->get_field_id('listing_no').'" name="'.$this->get_field_name('listing_no').'" value="'.$instance['listing_no'].'" />
		</p>
                
                <p>               
                    <label   for="'.$this->get_field_id('adv_filter_search_action').'">Pick actions</label> </br>            
                    <select id="'.$this->get_field_id('adv_filter_search_action').'" name="'.$this->get_field_name('adv_filter_search_action').'[]"   multiple="multiple" style="width:250px;" >
                        '.$actions_select.'
                    </select>
                </p>

                <p>
                    <label for="'.$this->get_field_id('adv_filter_search_category').'">Pick category</label> </br>          
                    <select id="'.$this->get_field_id('adv_filter_search_category').'"  name="'.$this->get_field_name('adv_filter_search_category').'[]"  multiple="multiple" style="width:250px;" >
                        '.$categ_select.'
                    </select>
                </p>
                
                <p>
                    <label for="'.$this->get_field_id('current_adv_filter_city').'">Pick City</label> </br>
                    <select  id="'.$this->get_field_id('current_adv_filter_city').'" name="'.$this->get_field_name('current_adv_filter_city').'[]"  multiple="multiple" style="width:250px;" >
                        '.$select_city.'
                    </select>
                </p>
                
                 <p>
                    <label for="'.$this->get_field_id('current_adv_filter_area').'">Pick Area</label> </br>
                    <select id="'.$this->get_field_id('current_adv_filter_area').'"  name="'.$this->get_field_name('current_adv_filter_area').'[]"  multiple="multiple" style="width:250px;" >
                        '.$select_area.'
                    </select>
                </p>
                
                <p>
                    <label for="'.$this->get_field_id('show_featured_only').'">Show featured only </label><br />
                    <select id="'.$this->get_field_id('show_featured_only').'"  name="'.$this->get_field_name('show_featured_only').'" style="width:250px;" >
                        '.$show_featured_only_select.'
                    </select>
                </p>';
                
		print $display;
	}


	function update($new_instance, $old_instance){
		$instance                               =   $old_instance;
		$instance['title']                      =   $new_instance['title'];
		$instance['listing_no']                 =   $new_instance['listing_no'];
                $instance['adv_filter_search_action']   =   $new_instance['adv_filter_search_action'];
		$instance['adv_filter_search_category'] =   $new_instance['adv_filter_search_category'];
                $instance['current_adv_filter_city']    =   $new_instance['current_adv_filter_city'];
                $instance['current_adv_filter_area']    =   $new_instance['current_adv_filter_area'];
                $instance['show_featured_only']         =   $new_instance['show_featured_only'];
		return $instance;
	}



	function widget($args, $instance){
		global $is_widget;
                global $current_user;
                global $wpestate_currency;
                global $wpestate_where_currency;
                global $wpestate_property_unit_slider;
                global $wpestate_listing_type;
                $wpestate_property_unit_slider       =   esc_html ( wprentals_get_option('wp_estate_prop_list_slider','') ); 
                $wpestate_listing_type   =   wprentals_get_option('wp_estate_listing_unit_type','');
                extract($args);
                $wpestate_currency = wprentals_get_option('wp_estate_currency_label_main', '');
                $wpestate_where_currency = wprentals_get_option('wp_estate_where_currency_symbol', '');
                
                $display='';
                $title = apply_filters('widget_title', $instance['title']);
                
		print $before_widget;
                if($title) {
			print $before_title.$title.$after_title;
		}
                
                $display.='<div class="latest_listings"> ';
                
                $transient_name= 'wpestate_widget_recent_query_output_'; 
                ///adding custom parameters
                
           
                $current_user = wp_get_current_user();
                $wpestate_currency                   =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
                $wpestate_where_currency             =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
                $prop_no                    =   intval( wprentals_get_option('wp_estate_prop_no', '') );
                $userID                     =   $current_user->ID;
                $user_option                =   'favorites'.$userID;
                $wpestate_curent_fav                 =   get_option($user_option);
                $icons                      =   array();
                $taxonomy                   =   'property_action_category';
                $tax_terms                  =   get_terms($taxonomy);
                $taxonomy_cat               =   'property_category';
                $categories                 =   get_terms($taxonomy_cat);
                $show_compare=1;


                $current_adv_filter_search_action       = $instance['adv_filter_search_action'];
                $current_adv_filter_search_category     = $instance['adv_filter_search_category'];
                $current_adv_filter_area                = $instance['current_adv_filter_area'];
                $current_adv_filter_city                = $instance['current_adv_filter_city'];
                $show_featured_only                     = $instance['show_featured_only'];
               


                $area_array =   $city_array =   $action_array   =   $categ_array    ='';

                /////////////////////////////////////////////////////////////////////////action


                if (!empty($current_adv_filter_search_action) && $current_adv_filter_search_action[0]!='all'){
                    $taxcateg_include   =   array();

                    foreach($current_adv_filter_search_action as $key=>$value){
                        $taxcateg_include[]=sanitize_title($value);
                        $transient_name.='_'.sanitize_title($value);
                    }

                    $categ_array=array(
                        'taxonomy'  => 'property_action_category',
                        'field'     => 'slug',
                        'terms'     => $taxcateg_include
                    );

                    $current_adv_filter_search_label    =   $current_adv_filter_search_action[0];
                }else{
                    $current_adv_filter_search_label    =    wpestate_category_labels_dropdowns('second');
                }



                /////////////////////////////////////////////////////////////////////////category

                if ( !empty($current_adv_filter_search_category) && $current_adv_filter_search_category[0]!='all' ){
                    $taxaction_include   =   array();   

                    foreach( $current_adv_filter_search_category as $key=>$value){
                        $taxaction_include[]=sanitize_title($value);
                        $transient_name.='_'.sanitize_title($value);
                    }

                    $action_array=array(
                         'taxonomy' => 'property_category',
                         'field'    => 'slug',
                         'terms'    => $taxaction_include
                    );
                    $current_adv_filter_category_label=$current_adv_filter_search_category[0];
                }else{
                    $current_adv_filter_category_label= wpestate_category_labels_dropdowns('main');
                }
                /////////////////////////////////////////////////////////////////////////////

                if ( !empty( $current_adv_filter_city ) && $current_adv_filter_city[0]!='all' ) {
                     $taxaction_include   =   array();   

                    foreach( $current_adv_filter_city as $key=>$value){
                        $taxaction_include[]=sanitize_title($value);
                        $transient_name.='_'.sanitize_title($value);
                    }

                    $city_array = array(
                        'taxonomy'  => 'property_city',
                        'field'     => 'slug',
                        'terms'     => $taxaction_include
                    );

                    $current_adv_filter_city_label=$current_adv_filter_city[0];
                }else{
                    $current_adv_filter_city_label=esc_html__( 'All Cities','wprentals-core');
                }
                /////////////////////////////////////////////////////////////////////////////

                if ( !empty( $current_adv_filter_area ) && $current_adv_filter_area[0]!='all' ) {
                     $taxaction_include   =   array();   

                    foreach( $current_adv_filter_area as $key=>$value){
                        $taxaction_include[]=sanitize_title($value);
                         $transient_name.='_'.sanitize_title($value);
                    }

                    $area_array = array(
                        'taxonomy'  => 'property_area',
                        'field'     => 'slug',
                        'terms'     => $taxaction_include
                    );

                    $current_adv_filter_area_label=$current_adv_filter_area[0];
                }else{
                    $current_adv_filter_area_label=esc_html__( 'All Areas','wprentals-core');
                }



                /////////////////////////////////////////////////////////////////////////////

                $meta_query=array();                
                if($show_featured_only=='yes'){
                    $compare_array=array();
                    $compare_array['key']        = 'prop_featured';
                    $compare_array['value']      = 1;
                    $compare_array['type']       = 'numeric';
                    $compare_array['compare']    = '=';
                    $meta_query[]                = $compare_array;
                }
                $transient_name.='_'.$show_featured_only.'_'.$instance['listing_no'];


                $meta_directions='DESC';
                $meta_order='prop_featured';
             
                $args = array(
                    'post_type'         => 'estate_property',
                    'post_status'       => 'publish',
                    'paged'             => 1,
                    'posts_per_page'    => $instance['listing_no'],
                    'orderby'           => 'id',
                    'meta_key'          => $meta_order,
                    'order'             => $meta_directions,
                    'meta_query'        => $meta_query,
                    'tax_query'         => array(
                                                'relation' => 'AND',
                                                $categ_array,
                                                $action_array,
                                                $city_array,
                                                $area_array
                                            )
                );
                
               
                $transient_name =   wpestate_add_language_currency_cache($transient_name);
                
                $output   =   false;
                if(function_exists('wpestate_request_transient_cache')){
                    $output         =   wpestate_request_transient_cache( $transient_name);
                }
    
                if( $output === false){
                    ob_start();
                    $the_query = new WP_Query( $args );
                    $is_widget=1;

                    while ($the_query->have_posts()): $the_query->the_post(); 
                        get_template_part('templates/property_unit_widget');
                    endwhile;
                    
                    $output = ob_get_contents();
                    ob_end_clean();
                    if(function_exists('wpestate_set_transient_cache')){
                        wpestate_set_transient_cache($transient_name,$output,4*60*60);
                    }
                    wp_reset_query();
                }
                print $output;
		$display.='</div>';
		print $display;
		print $after_widget;
	 }

 function action_select_dropn(){
     
 }


}

?>