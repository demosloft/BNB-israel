<?php
global $edit_id;
global $submit_title;
global $submit_description;
global $private_notes;
global $property_price;
global $property_label;
global $prop_action_category;
global $prop_action_category_selected;
global $prop_category_selected;
global $property_city;
global $property_area;
global $guestnumber;
global $property_country;
global $property_admin_area;
global $edit_link_price;
global $instant_booking;    
global $children_as_guests;
global $submission_page_fields;
global $submit_affiliate;
$show_adv_search_general            =   wprentals_get_option('wp_estate_wpestate_autocomplete','');

?>



<div class="col-md-12" id="new_post2">

    <h4 class="user_dashboard_panel_title"><?php  esc_html_e('Description','wprentals');?></h4>

    <?php wpestate_show_mandatory_fields();?>


    <div class="col-md-12" id="profile_message"></div>
    <div class="row">
        <div class="col-md-12">

            <div class="col-md-3 dashboard_chapter_label">
                <p>
                   <label for="title"><?php esc_html_e('Title','wprentals'); ?> </label>
                </p>
            </div>

            <div class="col-md-6">
                <p>
                   <label for="title"><?php esc_html_e('Title','wprentals'); ?> </label>
                   <input type="text" id="title" class="form-control" value="<?php print esc_html($submit_title); ?>" size="20" name="title" />
                </p>
            </div>

        </div>


       <?php


        $category_main_label        =   stripslashes( esc_html(wprentals_get_option('wp_estate_category_main', '')));
        $category_second_label      =   stripslashes( esc_html(wprentals_get_option('wp_estate_category_second', '')));
        $item_description_label     =   stripslashes( esc_html(wprentals_get_option('wp_estate_item_description_label', '')));
        $item_rental_type           =   esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));



        if($category_main_label===''){
            $category_main_label = esc_html__('Category','wprentals');
        }

        if($category_second_label===''){
            $category_second_label = esc_html__('Listed In/Room Type','wprentals');
        }

        if($item_description_label==''){
            $item_description_label=esc_html__('Property Description','wprentals');
        }


        if(   is_array($submission_page_fields) &&
            (    in_array('prop_category_submit', $submission_page_fields) ||
                in_array('prop_action_category_submit', $submission_page_fields) )
        ) { ?>
        <div class="col-md-12">

            <div class="col-md-3 dashboard_chapter_label">
                <p>
                    <label for="prop_category">
                    <?php
                        if($item_rental_type==0){
                            esc_html_e('Category and Listed In/Room Type','wprentals');
                        }
                        if($item_rental_type==1){
                            esc_html_e('Listing Categories','wprentals');
                        }
                    ?>
                    </label>
                </p>
            </div>

            <?php  if(   is_array($submission_page_fields) && in_array('prop_category_submit', $submission_page_fields)) { ?>
                <div class="col-md-3">
                    <p>
                        <label for="prop_category"><?php print esc_html($category_main_label); ?></label>
                        <?php
                            $args=array(
                                    'class'       => 'select-submit2',
                                    'hide_empty'  => false,
                                    'selected'    => $prop_category_selected,
                                    'name'        => 'prop_category',
                                    'id'          => 'prop_category_submit',
                                    'orderby'     => 'NAME',
                                    'order'       => 'ASC',
                                    'show_option_none'   => esc_html__( 'None','wprentals'),
                                    'taxonomy'    => 'property_category',
                                    'hierarchical'=> true
                                );
                            wp_dropdown_categories( $args );
                        ?>
                    </p>
                </div>
            <?php } ?>

            <?php  if(   is_array($submission_page_fields) && in_array('prop_action_category_submit', $submission_page_fields)) { ?>
                <div class="col-md-3">
                    <p>
                        <label for="prop_action_category"> <?php print esc_html($category_second_label); ?></label>
                        <?php
                        $args=array(
                                'class'       => 'select-submit2',
                                'hide_empty'  => false,
                                'selected'    => $prop_action_category_selected,
                                'name'        => 'prop_action_category',
                                'id'          => 'prop_action_category_submit',
                                'orderby'     => 'NAME',
                                'order'       => 'ASC',
                                'show_option_none'   => esc_html__( 'None','wprentals'),
                                'taxonomy'    => 'property_action_category',
                                'hierarchical'=> true
                            );

                           wp_dropdown_categories( $args );  ?>
                    </p>
                </div>
            <?php }?>

        </div>
        <?php }

        $show_guest_number     =   stripslashes( esc_html(wprentals_get_option('wp_estate_show_guest_number', '')));
        if ( $show_guest_number=='yes' ) { ?>
            <div class="col-md-12">
                <div class="col-md-3 dashboard_chapter_label ">
                    <p>
                        <label for="guest_no"><?php esc_html_e('Guest No','wprentals');?></label>
                    </p>
                </div>

                <div class="col-md-3">
                    <p>
                        <label for="guest_no"><?php esc_html_e('Guest No (mandatory)','wprentals');?></label>
                        <select id="guest_no" name="guest_no">
                            <?php
                            $guest_dropdown_no                    =   intval   ( wprentals_get_option('wp_estate_guest_dropdown_no','') );

                            for($i=0; $i<=$guest_dropdown_no; $i++) {
                                print '<option value="'.$i.'" ';
                                    if ( $guestnumber==$i){
                                        print ' selected="selected" ';
                                    }
                                print '>'.$i.'</option>';
                            } ?>
                        </select>
                    </p>
                </div>
                
                
                <?php  if(   is_array($submission_page_fields) && in_array('children_as_guests', $submission_page_fields)) { ?>
                <div class="col-md-3" style="padding-top:30px;">
                    <p>
                         <input style="float:left;" type="checkbox" class="form-control" value="1"  id="children_as_guests" name="children_as_guests" <?php print esc_html($children_as_guests); ?> >
                        <label for="children_as_guests"> <?php print esc_html__('Children(ages 2-12) will not be charged','wprentals'); ?></label>
                       
                    </p>
                </div>
                <?php }?>
                
              <?php
        if(is_array($submission_page_fields) && in_array('max_extra_guest_no', $submission_page_fields)) {
        ?>
        <div class="col-md-12">
            <div class="col-md-3"></div>
            
             <div class="col-md-3 extra_guest_label">
                <input style="float:left;" type="checkbox" class="form-control" value="1"  id="overload_guest" name="overload_guest" <?php print esc_html($overload_guest); ?> >
                <label style="display: inline;" for="overload_guest"><?php esc_html_e('Allow guests above capacity?','wprentals');?></label>
            </div>

             <div class="col-md-3">
                <label for="extra_price_per_guest">
                    <?php esc_html_e('Maximum extra guests above capacity (if extra guests are allowed)','wprentals');  ?>
                </label>
                <input type="text" id="max_extra_guest_no" class="form-control" size="40" name="max_extra_guest_no" value="<?php print esc_html($max_extra_guest_no);?>">
            </div>
        </div>
        <?php } ?>

                
                
                
                
                
            </div>
        <?php } ?>



        <?php
        if(is_array($submission_page_fields) &&
            ( in_array('property_city_front', $submission_page_fields) ||
              in_array('property_area_front', $submission_page_fields) )
        ) { ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <p>
                    <label for="property_city_front">
                    <?php
                        if($item_rental_type==0){
                            esc_html_e('City and Neighborhood','wprentals');
                        }
                        if($item_rental_type==1){
                            esc_html_e('Listing Location','wprentals');
                        }
                    ?>
                    </label>
                </p>
            </div>

            <?php  if(   is_array($submission_page_fields) && in_array('property_city_front', $submission_page_fields)) { ?>
            <div class="col-md-3 " id="property_city_front_md">
                <p>
                    <?php
                    $wpestate_internal_search           =   '';
                    if($show_adv_search_general=='no'){
                        $wpestate_internal_search='_autointernal';
                    }
                    ?>
                    <label for="property_city_front"><?php esc_html_e('City (mandatory)','wprentals');?></label>



                    <?php
                    if( wprentals_get_option('wp_estate_show_city_drop_submit')=='no'){
                    ?>
                        <input type="text"   id="property_city_front<?php print esc_attr($wpestate_internal_search);?>" name="property_city_front" placeholder="<?php esc_html_e('Type the city name','wprentals');?>" value="<?php print esc_html($property_city);?>" class="advanced_select  form-control">
                    <?php
                    }else{
                    ?>
                        <select id="property_city_front_autointernal" name="property_city_front" >
                            <?php echo wpestate_city_submit_dropdown('property_city',$property_city);?>
                        </select>
                    <?php
                    }
                    ?>

                    <?php  if($show_adv_search_general!='no'){ ?>
                    <input type="hidden" id="property_country" name="property_country" value="<?php print esc_html($property_country);?>">
                    <?php } ?>
                    <input type="hidden" id="property_city" name="property_city"  value="<?php print esc_html($property_city);?>" >
                    <input type="hidden" id="property_admin_area" name="property_admin_area" value="<?php print esc_html($property_admin_area);?>">
                    <input type="hidden" id="z" name="z" value="<?php echo get_post_meta($edit_id,'property_country',true);?>">
                </p>
            </div>
            <?php } ?>

            <?php  if(   is_array($submission_page_fields) && in_array('property_area_front', $submission_page_fields)) { ?>
                <div class="col-md-3">
                    <label for="property_area_front"><?php esc_html_e('Neighborhood / Area','wprentals');?></label>



                    <?php
                        if( wprentals_get_option('wp_estate_show_city_drop_submit')=='no'){
                    ?>
                        <input type="text"   id="property_area_front" name="property_area_front" placeholder="<?php esc_html_e('Type the neighborhood name','wprentals');?>" value="<?php print esc_html($property_area);?>" class="advanced_select  form-control">
                    <?php
                        }else{
                    ?>
                            <select id="property_area_front" name="property_area_front" >
                                <?php echo wpestate_city_submit_dropdown('property_area',$property_area);?>
                            </select>
                    <?php
                    }
                    ?>

                </div>
            <?php } ?>
        </div>
        <?php } ?>



        <?php  if($show_adv_search_general=='no'){ ?>
            <div class="col-md-12">
                 <div class="col-md-3 dashboard_chapter_label">
                    <label for="property_country"><?php esc_html_e('Country','wprentals');?></label>
                </div>

                <div class="col-md-3 property_country">
                    <label for="property_country"><?php esc_html_e('Country','wprentals');?></label>
                    <?php print wpestate_country_list(esc_html(get_post_meta($edit_id, 'property_country', true))); ?>
                </div>
            </div>
        <?php } ?>


        <?php
        if(is_array($submission_page_fields) && in_array('property_description', $submission_page_fields)) {
        ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="property_description"><?php  print esc_html($item_description_label);?></label>
            </div>

            <div class="col-md-6">
                <label for="property_description"><?php  print esc_html($item_description_label);?></label>
                <textarea rows="4" id="property_description" name="property_description"  class="advanced_select  form-control"
                           placeholder="<?php esc_html_e('Describe your listing','wprentals');?>"><?php print esc_textarea($submit_description); ?></textarea>
            </div>
        </div>

        <?php
        }
        ?>


        <?php
        if(is_array($submission_page_fields) && in_array('property_affiliate', $submission_page_fields)) {
        ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="property_description"><?php  print esc_html__('Affiliate Link','wprentals');?></label>
            </div>

            <div class="col-md-6">
                <label for="property_description"><?php  print esc_html__('Affiliate Link. User will be redirected to this link when he wants to make a booking.','wprentals');?></label>
                <input type="text" id="property_affiliate" class="form-control" value="<?php print esc_html($submit_affiliate); ?>" size="20" name="property_affiliate" />
            </div>
        </div>

        <?php
        }
        ?>

        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="private notes"><?php   esc_html_e('Private Notes','wprentals');?></label>
            </div>

            <div class="col-md-6">
                <label for="private notes"><?php  esc_html_e('Private Notes','wprentals');?></label>
                <textarea rows="4" id="private_notes" name="private notes"   class="advanced_select  form-control"
                           placeholder="<?php esc_html_e('Private Notes','wprentals');?>"><?php print esc_textarea($private_notes); ?></textarea>
            </div>
        </div>








    </div>
    <div class="row">
        <div class="col-md-12">
            <input style="float:left;" type="checkbox" class="form-control" value="1"  id="instant_booking" name="instant_booking" <?php print esc_html($instant_booking); ?> >
            <label style="display: inline;" for="instant_booking"><?php esc_html_e('Allow instant booking? If checked, you will not have the option to reject a booking request.','wprentals');?></label>
        </div>
    </div>
    <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">

    <div class="col-md-12" style="display: inline-block;">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_1" value="<?php esc_html_e('Save', 'wprentals') ?>" />
        <a href="<?php print esc_url($edit_link_price);?>" class="next_submit_page"><?php esc_html_e('Go to Price settings.','wprentals');?></a>

        <?php
        $ajax_nonce = wp_create_nonce( "wprentals_edit_prop_1_nonce" );
        print'<input type="hidden" id="wprentals_edit_prop_1_nonce" value="'.esc_html($ajax_nonce).'" />    ';
        ?>
    </div>
</div>
