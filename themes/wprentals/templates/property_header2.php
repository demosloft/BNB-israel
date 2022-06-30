<?php
global $post;
global $property_action_terms_icon;
global $property_action;
global $property_category_terms_icon;
global $property_category;
global $guests;
global $bedrooms;
global $bathrooms;
global $favorite_text;
global $favorite_class;
global $wpestate_options;

?>--><div class="property_header property_header2  <?php print'wprentals_show_description_'.wprentals_get_option('wprentals_hide_description'); ?> ">
        <div class="property_categs ">
            
            <div class="property_header_wrapper 
                <?php 
                if ( $wpestate_options['content_class']=='col-md-12' || $wpestate_options['content_class']=='none'){
                    print 'col-md-8';
                }else{
                   print  esc_attr($wpestate_options['content_class']); 
                }?> 
            ">
            
                <div class="category_wrapper ">
                    <div class="category_details_wrapper">
                        <?php 
                        
                        if(wprentals_get_option( 'wp_estate_use_custom_icon_area') =='yes' ){ 
                            wprentals_icon_bar_design();
                        }else{
                            wprentals_icon_bar_classic($property_action,$property_category,$rental_type,$guests,$bedrooms,$bathrooms);
                        } 
                        ?>
                        
                    </div>
                    
                    <a href="#listing_calendar" class="check_avalability"><?php esc_html_e('Check Availability','wprentals');?></a>
                </div>
                
                <?php
             

                if( wprentals_get_option('wprentals_hide_description') =='no'){
                ?>
                
                    <div  id="listing_description">
                    <?php
                        $content = get_the_content();
                        $content = apply_filters('the_content', $content);
                        $content = str_replace(']]>', ']]&gt;', $content);
                        $wpestate_property_description_text         =  wprentals_get_option('wp_estate_property_description_text');
                        if (function_exists('icl_translate') ){
                            $wpestate_property_description_text     =   icl_translate('wprentals','wp_estate_property_description_text', esc_html( wprentals_get_option('wp_estate_property_description_text') ) );
                        }
                        
                        if($content!=''){   
                            print '<h4 class="panel-title-description">'.esc_html($wpestate_property_description_text).'</h4>';
                            print '<div itemprop="description" id="listing_description_content"   class="panel-body">'.$content;
                            get_template_part ('/templates/download_pdf');
                            print'</div>'; //escaped above      
                        }
                    ?>
                    </div>        
                    <div id="view_more_desc"><?php esc_html_e('View more','wprentals');?></div>        
                
                <?php
                }
                ?>    
                



        </div>
    <?php  
        $post_id=$post->ID; 
        $guest_no_prop ='';
        if(isset($_GET['guest_no_prop'])){
            $guest_no_prop = intval($_GET['guest_no_prop']);
        }
        $guest_list= wpestate_get_guest_dropdown('noany');
    ?>

   
    <?php echo wpestate_show_booking_form($post_id,$wpestate_options,$favorite_class,$favorite_text); ?>
    
    
    
    
    </div>
</div>