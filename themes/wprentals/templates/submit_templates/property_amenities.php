<?php
global $feature_list_array;
global $edit_id;
global $moving_array;
global $edit_link_calendar;
global $submission_page_fields;

$list_to_show='';
$terms = get_terms( array(
    'taxonomy' => 'property_features',
    'hide_empty' => false,
) );

$parsed_features        =   wpestate_build_terms_array();
$single_return_string   =   '';
$multi_return_string    =   '';

if(is_array($parsed_features)):
    foreach($parsed_features as $key => $item){



        if( count( $item['childs']) >0 ){

                $multi_return_string_part=  '<div class="listing_detail col-md-12 feature_block_'.$item['name'].' ">';
                $multi_return_string_part.=  '<div class="feature_chapter_name col-md-12">'.$item['name'].'</div>';

                    $multi_return_string_part_check='';
                    if( is_array($item['childs']) ){
                        foreach($item['childs'] as $key_ch=>$child){
                            $term=get_term_by('name',$child,'property_features');
                            $temp   = wpestate_display_feature_submit($edit_id,$moving_array,$term,$submission_page_fields);
                            $multi_return_string_part .=$temp;
                            $multi_return_string_part_check.=$temp;
                        }
                    }
                    $multi_return_string_part.=  '</div>';

                    if($multi_return_string_part_check!=''){
                        $multi_return_string.=$multi_return_string_part;
                    }


        }else{
            $term=get_term_by('name',$item['name'],'property_features');
            $single_return_string .=  wpestate_display_feature_submit($edit_id,$moving_array,$term,$submission_page_fields);
        }
    }
endif;
$list_to_show=$multi_return_string;
if($single_return_string!=''){
    $list_to_show=$list_to_show.'<div class="listing_detail col-md-12 feature_block_others "><div class="feature_chapter_name col-md-12">'.esc_html__('Other Features','wprentals').'</div>'.$single_return_string.'</div>';
}






    function wpestate_display_feature_submit($edit_id,$moving_array,$term,$submission_page_fields){
        $post_var_name  =   $term->slug;
        $list_to_show   =   '';
        /////////////////////////////////////////////////
	    $original_term = $term;
	   if (defined('ICL_SITEPRESS_VERSION') && !is_admin()) {
                $current_language = apply_filters('wpml_current_language', NULL);
                $default_language = apply_filters('wpml_default_language', NULL);

                if ($current_language != $default_language) {
                    $trid = apply_filters('wpml_element_trid', NULL, $term->term_id, 'tax_property_features');
                    $term_translations = apply_filters('wpml_get_element_translations', NULL, $trid, 'tax_property_features');
                    $original_term_id = $term_translations[$default_language]->element_id;
                    do_action('wpml_switch_language', $default_language);
                    $original_term = get_term($original_term_id);
                    do_action('wpml_switch_language', $current_language);
                }
            }
	    /////////////////////////////////////////////////

        if(  in_array($original_term -> slug, $submission_page_fields) ) {
            $value_label= $term->name;


            $list_to_show.= ' <div class="col-md-4" ><p>
                   <input type="hidden"    name="'.esc_attr($post_var_name).'" value="" style="display:block;">
                   <input type="checkbox" class="feature_list_save"  id="'.esc_attr($post_var_name).'" name="'.esc_attr($post_var_name).'" value="1"   data-feature="'.intval($term->term_id).'"  ';

            if (has_term( $post_var_name, 'property_features',$edit_id )  ) {
                $list_to_show.=' checked="checked" ';
            }else{
                if(is_array($moving_array) ){
                    if( in_array($post_var_name,$moving_array) ){
                          $list_to_show.=' checked="checked" ';
                    }
                }
            }
            $list_to_show.=' /><label for="'.esc_attr($post_var_name).'">'. esc_html( stripslashes($value_label) ).'</label></p></div>';
        }

        return $list_to_show;

}



?>


<div class="col-md-12">

    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Amenities and Features','wprentals');?></h4>
     <?php wpestate_show_mandatory_fields();?>
    <div class="col-md-12" id="profile_message"></div>


    <?php

    if ( $list_to_show!='' ){ ?>
        <div class="row">
            <div class="col-md-12 dashboard_amenities">
                <div class="col-md-3 dashboard_chapter_label"><?php esc_html_e('Select the amenities and features that apply for your listing','wprentals');?></div>
                <div class="col-md-9">
                    <?php print trim($list_to_show);//escaped above?>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="col-md-12" style="display: inline-block;">
        <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_ammenities" value="<?php esc_html_e('Save', 'wprentals') ?>" />
        <a href="<?php echo  esc_url($edit_link_calendar);?>" class="next_submit_page"><?php esc_html_e('Go to Calendar settings.','wprentals');?></a>

        <?php
        $ajax_nonce = wp_create_nonce( "wprentals_amm_features_nonce" );
        print'<input type="hidden" id="wprentals_amm_features_nonce" value="'.esc_html($ajax_nonce).'" />    ';

        ?>

    </div>
</div>
