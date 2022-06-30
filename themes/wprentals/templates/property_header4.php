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
$rental_type=wprentals_get_option('wp_estate_item_rental_type');
?>
  
    
    <div class="property_categs ">

    <div class="category_wrapper ">

        <h1 itemprop="name" class="entry-title ">
            <span class="property_ratings listing_slider">
                <?php  
                    if(wpestate_has_some_review($post->ID)!==0){
                        print wpestate_display_property_rating( $post->ID ); 
                    } 
                ?>
            </span> 
            <?php the_title(); ?>
        </h1> 

        <div class="category_details_wrapper">
            <?php 
            
            if(wprentals_get_option( 'wp_estate_use_custom_icon_area') =='yes' ){ 
                wprentals_icon_bar_design();
            }else{
                wprentals_icon_bar_classic($property_action,$property_category,$rental_type,$guests,$bedrooms,$bathrooms);
            } 
            ?>
            
        </div>
        
    </div>
                
              
    <?php   if( wprentals_get_option('wprentals_hide_description') =='no'){ ?>
        <div  id="listing_description_type3">
        <?php
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            $wpestate_property_description_text         =  wprentals_get_option('wp_estate_property_description_text');
            if (function_exists('icl_translate') ){
                $wpestate_property_description_text     =   icl_translate('wprentals','wp_estate_property_description_text', esc_html( wprentals_get_option('wp_estate_property_description_text') ) );
            }
            
            if($content!=''){   
                print '<h4 id="listing_description" class="panel-title-description">'.esc_html($wpestate_property_description_text).'</h4>';
                print '<div itemprop="description" id="listing_description_content"   class="panel-body">'.$content;
                    get_template_part ('/templates/download_pdf');
                print '</div>'; //escaped above      
            }
        ?>
        </div>  
    <?php } ?>      
                  
    
    <?php  
        $post_id=$post->ID; 
        $guest_no_prop ='';
        if(isset($_GET['guest_no_prop'])){
            $guest_no_prop = intval($_GET['guest_no_prop']);
        }
        $guest_list= wpestate_get_guest_dropdown('noany');
    ?>

   
    
    
    
    
    </div>
