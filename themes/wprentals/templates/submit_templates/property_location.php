<?php
global $property_latitude;
global $property_longitude;
global $google_camera_angle;
global $property_address;
global $property_zip;
global $property_latitude;
global $property_longitude;
global $google_view_check;
global $google_camera_angle;
global $property_area;
global $property_city;
global $property_country;
global $edit_id;
global $property_county;
global $property_state;
global $edit_link_amenities;
global $submission_page_fields;
?>

<div class="col-md-12">
    <h4 class="user_dashboard_panel_title"><?php  esc_html_e('Listing Location','wprentals');?></h4>
    <?php wpestate_show_mandatory_fields();?>
    <div class="col-md-12" id="profile_message"></div>

    <div class="row">
        <?php
        if(is_array($submission_page_fields) &&
            ( in_array('property_address', $submission_page_fields) ||
              in_array('property_zip', $submission_page_fields) )
        ) { ?>

        <div class="col-md-12">

            <div class="col-md-3 dashboard_chapter_label"> <?php  esc_html_e('Listing location details','wprentals');?></div>
            <?php
            if(is_array($submission_page_fields) && in_array('property_address', $submission_page_fields)) {
            ?>
                <div class="col-md-3">
                    <p>
                        <label for="property_address"><?php esc_html_e('Address','wprentals');?></label>
                        <input type="text" id="property_address" class="form-control" size="40" name="property_address" value="<?php print esc_html($property_address);?>">
                   </p>
                </div>
            <?php } ?>

            <?php
            if(is_array($submission_page_fields) && in_array('property_zip', $submission_page_fields)) {
            ?>
                <div class="col-md-3">
                    <p>
                        <label for="property_zip"><?php esc_html_e('Zip','wprentals');?></label>
                        <input type="text" id="property_zip" class="form-control" size="40" name="property_zip" value="<?php print esc_html($property_zip);?>">
                    </p>
                </div>
            <?php } ?>
        </div>

        <?php } ?>


        <?php
        if(is_array($submission_page_fields) &&
            ( in_array('property_county', $submission_page_fields) ||
              in_array('property_state', $submission_page_fields) )
        ) { ?>

            <div class="col-md-12">
                <div class="col-md-3 dashboard_chapter_label"></div>
                <?php
                if(is_array($submission_page_fields) && in_array('property_state', $submission_page_fields)) {
                ?>
                <div class="col-md-3 ">
                    <p>
                        <label for="property_state"><?php esc_html_e('State','wprentals');?></label>
                        <input type="text" id="property_state" class="form-control" size="40" name="property_state" value="<?php print esc_html($property_state);?>">
                    </p>
                </div>
                <?php } ?>

                <?php
                if(is_array($submission_page_fields) && in_array('property_county', $submission_page_fields)) {
                ?>
                <div class="col-md-3">
                     <p>
                         <label for="property_county"><?php esc_html_e('County','wprentals');?></label>
                         <input type="text" id="property_county" class="form-control" size="40" name="property_state" value="<?php print esc_html($property_county);?>">
                     </p>
                 </div>
                <?php } ?>

            </div>
        <?php } ?>


        <?php
        if(is_array($submission_page_fields) && in_array('property_map', $submission_page_fields)) {
        ?>
            <?php    if(  wprentals_get_option('wp_estate_kind_of_places')!=2){ ?>
                <div class="col-md-12">
                    <div class="col-md-3 dashboard_chapter_label"></div>
                    <div class="col-md-6">
                        <div id="google_capture"  class="wpb_btn-small   vc_button"><?php esc_html_e('Place Pin with Address','wprentals');?></div>
                    </div>
                </div>
            <?php } ?>

            <div class="col-md-12 leaflet_submit_map_wrapper">
                 <div class="col-md-3 dashboard_chapter_label"> <?php  esc_html_e('Place the listing pin on the map','wprentals');?></div>
                <div class="col-md-6">
                    <div id="googleMapsubmit" ></div>
                </div>
            </div>
        <?php } ?>


         <?php
        if(is_array($submission_page_fields) &&
            ( in_array('property_latitude', $submission_page_fields) ||
              in_array('property_longitude', $submission_page_fields) )
        ) { ?>
            <div class="col-md-12">
               <div class="col-md-3 dashboard_chapter_label"></div>
               <div class="col-md-3">
                   <p>
                        <label for="property_latitude"><?php esc_html_e('Latitude (for Maps Pin Position)','wprentals'); ?></label>
                        <input type="text" id="property_latitude" class="form-control" style="margin-right:20px;" size="40" name="property_latitude" value="<?php print esc_html($property_latitude); ?>">
                   </p>
               </div>

               <div class="col-md-3">
                   <p>
                       <label for="property_longitude"><?php esc_html_e('Longitude (for Maps Pin Position)','wprentals');?></label>
                       <input type="text" id="property_longitude" class="form-control" style="margin-right:20px;" size="40" name="property_longitude" value="<?php print esc_html($property_longitude);?>">
                   </p>
               </div>


            </div>
        <?php } ?>

        <?php
        if(  wprentals_get_option('wp_estate_kind_of_places')!=2){
            if(is_array($submission_page_fields) && in_array('google_camera_angle', $submission_page_fields)) {
                if(  wprentals_get_option('wp_estate_kind_of_places')!=2){
                    ?>
                    <div class="col-md-12">
                        <div class="col-md-3 dashboard_chapter_label"></div>
                            <div class="col-md-3">
                            <p>
                                <label for="google_camera_angle"><?php esc_html_e('Street View - Camera Angle (value from 0 to 360)','wprentals');?></label>
                                <input type="text" id="google_camera_angle" class="form-control" style="margin-right:0px;" size="5" name="google_camera_angle" value="<?php print esc_html($google_camera_angle);?>">
                            </p>
                        </div>
                    </div>
                    <?php }
            }
        }
        ?>

    </div>
    <input type="hidden" id="property_city_submit" value="<?php print esc_html($property_city);?>">
    <input id="property_country" type="hidden" value="<?php print esc_html($property_country);?>">

    <div class="col-md-12" style="display: inline-block;">
        <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_locations" value="<?php esc_html_e('Save', 'wprentals') ?>" />
        <a href="<?php echo  esc_url($edit_link_amenities);?>" class="next_submit_page"><?php esc_html_e('Go to Amenities settings.','wprentals');?></a>

        <?php

        $ajax_nonce = wp_create_nonce( "wprentals_edit_prop_locations_nonce" );
        print'<input type="hidden" id="wprentals_edit_prop_locations_nonce" value="'.esc_html($ajax_nonce).'" />    ';

        ?>
    </div>
</div>
