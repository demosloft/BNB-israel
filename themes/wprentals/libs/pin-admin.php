<?php


///////////////////////////////////////////////////////////////////////////////////////////
// category icons
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_pins') ):


function wpestate_show_pins(){
    $pins       =   array();
    $taxonomy = 'property_action_category';
    $tax_terms = get_terms($taxonomy,'hide_empty=0');

    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat,'hide_empty=0');

    // add only actions
    if(is_array($tax_terms)){
        foreach ($tax_terms as $tax_term) {
            $name                    =  sanitize_key ( wpestate_limit64('wp_estate_'.$tax_term->slug) );
            $limit54                 =  sanitize_key ( wpestate_limit54($tax_term->slug) );
            $pins[$limit54]          =  esc_html( wpestate_autocomplete_data($name) );  
        } 
    }

    // add only categories
    if(is_array($categories)){
        foreach ($categories as $categ) {
            $name                           =   sanitize_key( wpestate_limit64('wp_estate_'.$categ->slug));
            $limit54                        =   sanitize_key(wpestate_limit54($categ->slug));
            $pins[$limit54]                 =   esc_html( wpestate_autocomplete_data($name) );
        }
    }
    
    // add combinations
    if(is_array($tax_terms) && is_array($categories) ){
        foreach ($tax_terms as $tax_term) {
            foreach ($categories as $categ) {
                $limit54            =   sanitize_key ( wpestate_limit27($categ->slug).wpestate_limit27($tax_term->slug) );
                $name               =   'wp_estate_'.$limit54;
                $pins[$limit54]     =   esc_html( wpestate_autocomplete_data($name) ) ;        
            }
        }
    }

  
    $name='wp_estate_idxpin';
    $pins['idxpin']=esc_html( wpestate_autocomplete_data($name) );  

    $name='wp_estate_userpin';
    $pins['userpin']=esc_html( wpestate_autocomplete_data($name) );  
    
    $taxonomy = 'property_action_category';
    $tax_terms = get_terms($taxonomy,'hide_empty=0');

    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat,'hide_empty=0');

   print '<p class="admin-exp">'.__('Add new Google Maps pins for single actions / single categories. For speed reason, you MUST add pins if you change categories and actions names.','wprentals').'</p>';
   print '<p class="admin-exp">'.__('If you add images directly into the input fields (without Upload button) please use the full image path. For ex: http://www.wprentals..... . If you use the "upload"  button use also "Insert into Post" button from the pop up window.','wprentals').'</p>';
   print '<p class="admin-exp">'.__('Pins retina version must be uploaded at the same time (same folder) as the original pin and the name of the retina file should be with_2x at the end.  ','wprentals').'<a href="http://help.wprentals.org/2016/08/01/retina-pins/" target="_blank">'.esc_html__('Help here','wprentals').'</a>'.'</p>';

   
   $cache_array=array('no','yes');
    $use_price_pins                 =   wpestate_dropdowns_theme_admin($cache_array,'use_price_pins');
    
    print'<div class="estate_option_row">
    <div class="label_option_row">'.__('Use price Pins ?','wprentals').'</div>
    <div class="option_row_explain">'.__('Use price Pins ?(The css class for price pins is "wpestate_marker" . Each pin has also receive a class with the name of the category or action: For example "wpestate_marker apartments sales")','wprentals').'</div>    
        <select id="use_price_pins" name="use_price_pins">
            '.$use_price_pins.'
        </select>
    </div>';
    
    
    $use_price_pins_full_price                 =   wpestate_dropdowns_theme_admin($cache_array,'use_price_pins_full_price ');
    print'<div class="estate_option_row">
    <div class="label_option_row">'.__('Use Full Price Pins ?','wprentals').'</div>
    <div class="option_row_explain">'.__('If not we will show prices without before and after label and in this format : 5,23m or 6.83k ','wprentals').'</div>    
        <select id="use_price_pins_full_price" name="use_price_pins_full_price">
            '.$use_price_pins_full_price.'
        </select>
    </div>';
   
   

    foreach ($tax_terms as $tax_term) { 
            $limit54   =  $post_name  =   sanitize_key(wpestate_limit54($tax_term->slug));
            print'<div class="estate_option_row">
            <div class="label_option_row">'.__('For action ','wprentals').'<strong>'.$tax_term->name.' </strong></div>
            <div class="option_row_explain">'.__('Image size must be 44px x 50px. ','wprentals').'</div>    
                <input type="text"    class="pin-upload-form" size="36" name="'.$post_name.'" value="'.$pins[$limit54].'" />
                <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wprentals').'" />
            </div>';
               
    }
     
    
    foreach ($categories as $categ) {  
            $limit54   =   $post_name  =   sanitize_key(wpestate_limit54($categ->slug));
            print'<div class="estate_option_row">
            <div class="label_option_row">' . __('For category: ', 'wprentals') . '<strong>' . $categ->name . ' </strong>  </div>
            <div class="option_row_explain">' . __('Image size must be 44px x 50px. ', 'wprentals') . '</div>    
                <input type="text"    class="pin-upload-form" size="36" name="'.$post_name.'" value="'.$pins[$limit54].'" />
                <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wprentals').'"  />
            </div>';
     
    }
    
    
    print '<p class="admin-exp">'.__('Add new Google Maps pins for actions & categories combined (example: \'apartments in sales\')','wprentals').'</p>';  
      
    foreach ($tax_terms as $tax_term) {
    
    foreach ($categories as $categ) {
        $limit54=sanitize_key(wpestate_limit27($categ->slug)).sanitize_key( wpestate_limit27($tax_term->slug) );

        print'<div class="estate_option_row">
            <div class="label_option_row">'.__('For action','wprentals').' <strong>'.$tax_term->name.'</strong>, '.__('category','wprentals').': <strong>'.$categ->name.'</strong>   </div>
            <div class="option_row_explain">'.__('Image size must be 44px x 50px.','wprentals').'  </div>    
               <input id="'.$limit54.'" type="text" size="36" name="'. $limit54.'" value="'.$pins[$limit54].'" />
               <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wprentals').'" />
            </div>'; 
        }
           
    }
  
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Userpin in geolocation','wprentals').'</div>
        <div class="option_row_explain">'.__('Userpin in geolocation','wprentals').'</div>    
            <input id="userpin" type="text" size="36" name="userpin" value="'.$pins['userpin'].'" />
            <input type="button"  class="upload_button button pin-upload" value="'.esc_html__( 'Upload Pin','wprentals').'" />
        </div>';

    
    print ' <div class="estate_option_row_submit">
        <input type="submit" name="submit"  class="new_admin_submit " value="'.__('Save Changes','wprentals').'" />
        </div>'; 
   
}
endif; // end   wpestate_show_pins  

?>