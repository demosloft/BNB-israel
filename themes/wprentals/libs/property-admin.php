<?php

if( !function_exists('wpestate_fields_type_select') ):
    function wpestate_fields_type_select($real_value){

        $select = '<select id="field_type" name="add_field_type[]" style="width:140px;">';
        $values = array('short text','long text','numeric','date','dropdown');

        foreach($values as $option){
            $select.='<option value="'.$option.'"';
                if( $option == $real_value ){
                     $select.= ' selected="selected"  ';
                }       
            $select.= ' > '.$option.' </option>';
        }   
        $select.= '</select>';
        return $select;
    }
endif; // end   wpestate_fields_type_select  





if( !function_exists('wpestate_custom_fields') ):
    function wpestate_custom_fields(){

        $custom_fields = get_option( 'wp_estate_custom_fields', true);     
        $current_fields='';


        $i=0;
        if( !empty($custom_fields)){    
            while($i< count($custom_fields) ){
                $current_fields.='
                    <div class=field_row>
                    <div    class="field_item"><strong>'.__('Field Name','wprentals').'</strong></br><input  type="text" name="add_field_name[]"   value="'.stripslashes( $custom_fields[$i][0] ).'"  ></div>
                    <div    class="field_item"><strong>'.__('Field Label','wprentals').'</strong></br><input  type="text" name="add_field_label[]"   value="'.stripslashes ( $custom_fields[$i][1] ).'"  ></div>
                    <div    class="field_item"><strong>'.__('Field Type','wprentals').'</strong></br>'.wpestate_fields_type_select($custom_fields[$i][2]).'</div>
                    <div    class="field_item"><strong>'.__('Field Order','wprentals').'</strong></br><input  type="text" name="add_field_order[]" value="'.stripslashes( $custom_fields[$i][3]) .'"></div>     
                    <div    class="field_item"><strong>'.__('Dropdown values','wprentals').'</strong></br><textarea name="add_dropdown_order[]">';
                    
                    if( isset($custom_fields[$i][4]) ){
                           $current_fields.= stripslashes( $custom_fields[$i][4] );
                    } 
                    
                        
                    $current_fields.='</textarea></div>     
             
                    <a class="deletefieldlink" href="#">'.esc_html__( 'delete','wprentals').'</a>
                </div>';    
                $i++;
            }
        }
        
         print'<div class="estate_option_row" style="border:none;">
    <div class="label_option_row">'.__('Custom Fields list','wprentals').'</div>
    <div class="option_row_explain" >'.__('Add, edit or delete property custom fields.','wprentals').'</div>    
        <div id="custom_fields_wrapper">
        '.$current_fields.'
        <input type="hidden" name="is_custom" value="1">   
        </div>
    </div>';

  print'<div class="estate_option_row">
    <div class="label_option_row">' . __('Add New Custom Field', 'wprentals') . '</div>
    <div class="option_row_explain">' . __('Fill the form in order to add a new custom field', 'wprentals') . '</div>  
     
        <div class="add_curency" >
            <div class="cur_explanations">' . __('Field name', 'wprentals') . '</div>
            <input  type="text" id="field_name"  name="field_name"   value="" size="40"/>
            
            <div class="cur_explanations">' . __('Field Label', 'wprentals') . '</div>
             <input  type="text" id="field_label"  name="field_label"   value="" size="40" />
            
            <div class="cur_explanations">' . __('Field Type', 'wprentals') . '</div>
                <select id="field_type" name="field_type">
                    <option value="short text">short text</option>
                    <option value="long text">long text</option>
                    <option value="numeric">numeric</option>
                    <option value="date">date</option>
                    <option value="dropdown">dropdown</option>
                </select>



 
            <div class="cur_explanations">' . __('Dropdown values separated by "," (only for dropdown field type)','wprentals') . '</div>
            <textarea cols="45" id="drodown_values"  name="drodown_values"  ></textarea>
                    

    <div class="cur_explanations">'.__(' Order in listing page','wprentals').'</div>
            <input  type="text" id="field_order"  name="field_order"   value="" size="40" />    
    </br>
    <a href="#" id="add_field">' . __(' click to add field', 'wprentals') . '</a>
    </div>
         
    </div>'; 
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.__('Save Changes','wprentals').'" />
    </div>';  
    }
endif; // end   wpestate_custom_fields  









if( !function_exists('wpestate_display_labels') ):
    function wpestate_display_labels(){
        $cache_array                            =   array('yes','no');
        $status_list                            =   esc_html( get_option('wp_estate_status_list') );
        $status_list                            =   str_replace(', ',',&#13;&#10;',$status_list);
        $status_list                            =   stripslashes($status_list);
        $wpestate_property_adr_text             =   stripslashes ( esc_html( get_option('wp_estate_property_adr_text') ) );
        $wpestate_property_description_text     =   stripslashes ( esc_html( get_option('wp_estate_property_description_text') ) );
        $wpestate_property_details_text         =   stripslashes ( esc_html( get_option('wp_estate_property_details_text') ) );
        $wpestate_property_details_text         =   stripslashes ( esc_html( get_option('wp_estate_property_features_text') ) );
        $wpestate_property_price_text           =   stripslashes ( esc_html( get_option('wp_estate_property_price_text') ) );
        $wpestate_property_pictures_text        =   stripslashes ( esc_html( get_option('wp_estate_property_pictures_text') ) );

        
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Property Address Label','wprentals').'</div>
        <div class="option_row_explain">'.__('Custom title instead of Features and Amenities label.','wprentals').'</div>    
            <input  type="text" id="property_adr_text"  name="property_adr_text"   value="'.$wpestate_property_adr_text.'"/>
        </div>';
              
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Property Features Label','wprentals').'</div>
        <div class="option_row_explain">'.__('Custom title instead of Features and Amenities label.','wprentals').'</div>    
            <input  type="text" id="property_features_text"  name="property_features_text"   value="'.$wpestate_property_details_text.'" size="40"/>
        </div>';
                
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Property Description Label','wprentals').'</div>
        <div class="option_row_explain">'.__('Custom title instead of Description label.','wprentals').'</div>    
            <input  type="text" id="property_description_text"  name="property_description_text"   value="'.$wpestate_property_description_text.'" size="40"/>
        </div>';

    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Property Details Label','wprentals').'</div>
        <div class="option_row_explain">'.__('Custom title instead of Property Details label. ','wprentals').'</div>    
            <input  type="text" id="property_details_text"  name="property_details_text"   value="'.$wpestate_property_details_text.'" size="40"/>
        </div>';

 
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Property Price Label','wprentals').'</div>
        <div class="option_row_explain">'.__('Custom title instead of Property Price label. ','wprentals').'</div>    
            <input  type="text" id="property_price_text"  name="property_price_text"   value="'.$wpestate_property_price_text.'" size="40"/>
        </div>';

    
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Property Status ','wprentals').'</div>
        <div class="option_row_explain">'.__('Property Status (* you may need to add new css classes - please see the help files) ','wprentals').'</div>    
            <input  type="text" id="new_status"  name="new_status"   value="'.__('type here the new status... ','wprentals').'"/></br>
            <a href="#new_status" id="add_status">'.__('click to add new status','wprentals').'</a><br>
            <textarea id="status_list" name="status_list" rows="7" style="width:300px;">'.$status_list.'</textarea>  
        </div>';
    
    print ' <div class="estate_option_row_submit">
        <input type="submit" name="submit"  class="new_admin_submit " value="'.__('Save Changes','wprentals').'" />
        </div>';
        
        
        
    }
endif; // end   wpestate_display_labels  



?>