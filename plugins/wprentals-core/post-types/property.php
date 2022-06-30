<?php
// register the custom post type
add_action('setup_theme', 'wpestate_create_property_type',20);

if( !function_exists('wpestate_create_property_type') ):
function wpestate_create_property_type() {
    $rewrites   =   wpestate_safe_rewite();
    if(isset($rewrites[0])){
        $slug=$rewrites[0];
    }else{
        $slug='properties';
    }
    register_post_type('estate_property', array(
        'labels' => array(
            'name'                  => esc_html__( 'Listings','wprentals-core'),
            'singular_name'         => esc_html__( 'Listing','wprentals-core'),
            'add_new'               => esc_html__( 'Add New Listing','wprentals-core'),
            'add_new_item'          => esc_html__( 'Add Listing','wprentals-core'),
            'edit'                  => esc_html__( 'Edit','wprentals-core'),
            'edit_item'             => esc_html__( 'Edit Listings','wprentals-core'),
            'new_item'              => esc_html__( 'New Listing','wprentals-core'),
            'view'                  => esc_html__( 'View','wprentals-core'),
            'view_item'             => esc_html__( 'View Listings','wprentals-core'),
            'search_items'          => esc_html__( 'Search Listings','wprentals-core'),
            'not_found'             => esc_html__( 'No Listings found','wprentals-core'),
            'not_found_in_trash'    => esc_html__( 'No Listings found in Trash','wprentals-core'),
            'parent'                => esc_html__( 'Parent Listings','wprentals-core')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => $slug),
        'supports' => array('title', 'editor', 'thumbnail', 'comments','excerpt'),
        'can_export' => true,
        'register_meta_box_cb' => 'wpestate_add_property_metaboxes',
        'menu_icon'=>WPESTATE_PLUGIN_DIR_URL.'/img/properties.png'
         )
    );



////////////////////////////////////////////////////////////////////////////////////////////////
// Add custom taxomies
////////////////////////////////////////////////////////////////////////////////////////////////
    $category_main_label        =   stripslashes( esc_html(wprentals_get_option('wp_estate_category_main', '')));
    $category_second_label      =   stripslashes( esc_html(wprentals_get_option('wp_estate_category_second', '')));

    $name_label             =   esc_html__( 'Categories','wprentals-core');
    $add_new_item_label     =   esc_html__( 'Add New Listing Category','wprentals-core');
    $new_item_name_label    =   esc_html__( 'New Listing Category','wprentals-core');

    if($category_main_label!=''){
        $name_label             =   $category_main_label;
        $add_new_item_label     =   esc_html__( 'Add New','wprentals-core').' '.$category_main_label;
        $new_item_name_label    =   esc_html__( 'New','wprentals-core').' '.$category_main_label;
    }


    $rewrites   =   wpestate_safe_rewite();
    if(isset($rewrites[1])){
        $slug=$rewrites[1];
    }else{
        $slug='listings';
    }


    register_taxonomy('property_category', 'estate_property', array(
        'labels' => array(
            'name'              => $name_label,
            'add_new_item'      => $add_new_item_label,
            'new_item_name'     => $new_item_name_label
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => $slug )
        )
    );

    $action_name              = esc_html__( 'What do you rent ?','wprentals-core');
    $action_add_new_item      = esc_html__( 'Add new option for "What do you rent" ','wprentals-core');
    $action_new_item_name     = esc_html__( 'Add new option for "What do you rent"','wprentals-core');

    if($category_second_label!=''){
        $action_name              =     $category_second_label;
        $action_add_new_item      =     esc_html__( 'Add New','wprentals-core').' '.$category_second_label;
        $action_new_item_name     =     esc_html__( 'New','wprentals-core').' '.$category_second_label;

    }



    $rewrites   =   wpestate_safe_rewite();
    if(isset($rewrites[2])){
        $slug=$rewrites[2];
    }else{
        $slug='action';
    }

    // add custom taxonomy
    register_taxonomy('property_action_category', 'estate_property', array(
        'labels' => array(
            'name'              =>  $action_name,
            'add_new_item'      =>  $action_add_new_item,
            'new_item_name'     =>  $action_new_item_name
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => $slug )
       )
    );


    $rewrites   =   wpestate_safe_rewite();
    if(isset($rewrites[3])){
        $slug=$rewrites[3];
    }else{
        $slug='city';
    }


    // add custom taxonomy
    register_taxonomy('property_city', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'City','wprentals-core'),
            'add_new_item'      => esc_html__( 'Add New City','wprentals-core'),
            'new_item_name'     => esc_html__( 'New City','wprentals-core')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => $slug )
        )
    );


    $rewrites   =   wpestate_safe_rewite();
    if(isset($rewrites[4])){
        $slug=$rewrites[4];
    }else{
        $slug='area';
    }


    // add custom taxonomy
    register_taxonomy('property_area', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'Neighborhood / Area','wprentals-core'),
            'add_new_item'      => esc_html__( 'Add New Neighborhood / Area','wprentals-core'),
            'new_item_name'     => esc_html__( 'New Neighborhood / Area','wprentals-core')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => $slug )

        )
    );


    $rewrites   =   wpestate_safe_rewite();
    if(isset($rewrites[5])){
        $slug=$rewrites[5];
    }else{
        $slug='features';
    }

    // add custom taxonomy
    register_taxonomy('property_features', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'Features & Amenities','wprentals-core'),
            'add_new_item'      => esc_html__( 'Add New Feature','wprentals-core'),
            'new_item_name'     => esc_html__( 'New Feature','wprentals-core')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => $slug )

        )
    );


    $rewrites   =   wpestate_safe_rewite();
    if(isset($rewrites[6])){
        $slug=$rewrites[6];
    }else{
        $slug='status';
    }

    // add custom taxonomy
    register_taxonomy('property_status', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'Property Status','wprentals-core'),
            'add_new_item'      => esc_html__( 'Add New Status','wprentals-core'),
            'new_item_name'     => esc_html__( 'New Status','wprentals-core')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' =>$slug )

        )
    );




    wprentals_convert_features_status_to_tax();

}// end create property type
endif; // end   wpestate_create_property_type



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Add metaboxes for Property
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_property_metaboxes') ):
function wpestate_add_property_metaboxes() {
   add_meta_box('new_tabbed_interface',         esc_html__('Property Details', 'wprentals-core'),             'estate_tabbed_interface', 'estate_property', 'normal', 'default' );
}
endif; // end   wpestate_add_property_metaboxes


if( !function_exists('estate_tabbed_interface') ):
    function estate_tabbed_interface(){
        global $post;
        //<div class="property_tab_item " data-content="property_media">'.esc_html__('Property Media','wprentals-core').'</div>
        print'<div class="property_options_wrapper meta-options">

             <div class="property_options_wrapper_list">
                <div class="property_tab_item active_tab" data-content="property_details">'.esc_html__('Property General Details','wprentals-core').'</div>
                <div class="property_tab_item" data-content="property_price">'.esc_html__('Property Price','wprentals-core').'</div>
                <div class="property_tab_item" data-content="property_media">'.esc_html__('Property Media','wprentals-core').'</div>
                <div class="property_tab_item " data-content="property_specific_details">'.esc_html__('Property Specific Details','wprentals-core').'</div>

                <div class="property_tab_item" data-content="property_map" id="property_map_trigger">'.esc_html__('Map','wprentals-core').'</div>
                <div class="property_tab_item" data-content="property_agent">'.esc_html__('Owner','wprentals-core').'</div>
                <div class="property_tab_item" data-content="property_paid">'.esc_html__('Paid Submission','wprentals-core').'</div>

            </div>



            <div class="property_options_content_wrapper">
                <div class="property_tab_item_content active_tab" id="property_details"><h3>'.esc_html__('General Details','wprentals-core').'</h3>';
                wpestate_listing_details_box($post);
                print'</div>


                <div class="property_tab_item_content " id="property_price"><h3>'.esc_html__('Property Price','wprentals-core').'</h3>';
                wpestate_property_price_admin($post);
                print'</div>

                <div class="property_tab_item_content " id="property_media"><h3>'.esc_html__('Property Media','wprentals-core').'</h3>';
                wpestate_property_add_media($post);
                print'</div>


                <div class="property_tab_item_content " id="property_specific_details"><h3>'.esc_html__('Specific Details','wprentals-core').'</h3>';
                wpestate_listing_details_specific_box($post);
                print'</div>



                <div class="property_tab_item_content" id="property_map"><h3>'.esc_html__('Place It On The Map','wprentals-core').'</h3>';
                wpestate_map_estate_box($post);
                print'</div>';

                print'<div class="property_tab_item_content" id="property_agent"><h3>'.esc_html__('Owner','wprentals-core').'</h3>';
                wpestate_agentestate_box($post);
                print'</div>

                <div class="property_tab_item_content" id="property_paid"><h3>'.esc_html__('Paid Submission','wprentals-core').'</h3>';
                wpestate_paid_submission($post);
                print'</div>

            </div>

        </div>';
    }
endif;


// <div class="property_tab_item_content " id="property_media"><h3>'.esc_html__('Property Media','wprentals-core').'</h3>';
//                wpestate_property_add_media();
//                print'</div>

if( !function_exists('wpestate_add_property_metaboxes') ):
function wpestate_add_property_metaboxes() {
    return;
    add_meta_box('estate_property-sectionid',       esc_html__( 'Listing Settings', 'wprentals-core'),      'estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-googlemap',       esc_html__( 'Place It On The Map', 'wprentals-core'),    'map_estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-propdetails',     esc_html__( 'Listing Details', 'wprentals-core'),       'details_estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-custom',          esc_html__( 'Listing Custom', 'wprentals-core'),        'wpestate_custom_details_box', 'estate_property', 'normal', 'default');

    add_meta_box('estate_property-agent',           esc_html__( 'Owner', 'wprentals-core'),      'agentestate_box', 'estate_property', 'normal', 'default' );
    add_meta_box('wpestate-paid-submission',        esc_html__( 'Paid Submission',   'wprentals-core'),      'estate_paid_submission', 'estate_property', 'side', 'high' );
    //add_meta_box('estate_property-user',            esc_html__( 'Assign property to user', 'wprentals-core'), 'userestate_box', 'estate_property', 'normal', 'default' );

}
endif; // end   wpestate_add_property_metaboxes





///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property Custom details  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_custom_details_box') ):
function wpestate_custom_details_box(){
    global $post;
    $i=0;
    $custom_fields = wprentals_get_option('wpestate_custom_fields_list','');

    if( !empty($custom_fields)){
        print '<h3>'.esc_html__('Custom Details','wprentals-core').'</h3>';

        while($i< count($custom_fields) ){
            $name               =   $custom_fields[$i][0];
            $label              =   $custom_fields[$i][1];
            $type               =   $custom_fields[$i][2];
            if(isset( $custom_fields[$i][4])){
                $dropdown_values    =   $custom_fields[$i][4];
            }
            $slug               =   wpestate_limit45(sanitize_title( $name ));
            $slug               =   sanitize_key($slug);

            print '<div class="metacustom">';
            if ( $type =='long text' ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*text) </label>';
                print '<textarea type="text" id="'.$slug.'"  size="0" name="'.$slug.'" rows="3" cols="42">' . esc_html(get_post_meta($post->ID, $slug, true)) . '</textarea>';
            }else if( $type =='short text' ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*text) </label>';
                print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
            }else if( $type =='numeric'  ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*numeric) </label>';
                $numeric_value=get_post_meta($post->ID,$slug, true);
                if($numeric_value!=''){
                    $numeric_value=  floatval($numeric_value);
                }
                print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $numeric_value . '">';
            }else if( $type =='date' ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*date) </label>';
                print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
                print '<script type="text/javascript">
                      //<![CDATA[
                      jQuery(document).ready(function(){
                           '.wpestate_date_picker_translation($slug).'
                      });
                      //]]>
                      </script>';

            }else if( $type =='dropdown' ){
                $dropdown_values_array=explode(',',$dropdown_values);

                print '<label for="'.$slug.'">'.stripslashes($label).' </label>';
                print '<select id="'.$slug.'"  name="'.$slug.'" >';
                print '<option value="">'.esc_html__('Not Available','wprentals-core').'</option>';
                $value = esc_html(get_post_meta($post->ID,$slug, true));
                foreach($dropdown_values_array as $key=>$value_drop){
                    print '<option value="'.trim($value_drop).'"';
                    if( trim( htmlspecialchars_decode($value) ) === trim( htmlspecialchars_decode ($value_drop) ) ){
                        print ' selected ';
                    }
                    if (function_exists('icl_translate') ){
                        $value_drop = apply_filters('wpml_translate_single_string', $value_drop,'custom field value','custom_field_value'.$value_drop );
                    }

                    print '>'.stripslashes( trim( $value_drop ) ).'</option>';
                }
                print '</select>';
            }
            print '</div>';
            $i++;
        }
    }


    $details =   get_post_meta($post->ID, 'property_custom_details', true);

    if(is_array($details)){
        print '   <div class="extra_detail_option_wrapper_admin"> <h3>'.esc_html__('Extra Details','wprentals-core').'</h3>';
        foreach ($details as $label=>$value){
            print '

                <div class="extra_detail_option ">
                    <label class="extra_detail_option_label">'.esc_html__('Label','wprentals-core').'</label>
                    <input type="text" name="property_custom_details_admin_label[]" class=" extra_option_name form-control" value="'.$label.'">
                </div>

                <div class="extra_detail_option ">
                    <label class="extra_detail_option_label">'.esc_html__('Value','wprentals-core').'</label>
                    <input type="text" name="property_custom_details_admin_value[]" class=" extra_option_value form-control" value="'.$value.'">
                </div>';

        }
        print' </div>';

    }


    print '<div style="clear:both"></div>';

}
endif; // end



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Agent box function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('userestate_box') ):
function userestate_box($post) {
    global  $post;
    $mypost         =   $post->ID;
    $originalpost   =   $post;
    $blog_list      =   '';
    $original_user  =   wpsestate_get_author();



    $blogusers = get_users( 'blog_id=1&orderby=nicename&role=subscriber' );

    foreach ( $blogusers as $user ) {

        $the_id=$user->ID;
        $blog_list  .=  '<option value="' . $the_id . '"  ';
            if ($the_id == $original_user) {
                $blog_list.=' selected="selected" ';
            }
        $blog_list.= '>' .$user->user_login . '</option>';
    }




    print '
    <label for="property_user">'.esc_html__( 'Users: ','wprentals-core').'</label><br />
    <select id="property_user" style="width: 237px;" name="property_user">
          <option value="1">admin</option>
          <option value=""></option>
          '. $blog_list .'
    </select>';

}
endif;


///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property Pay Submission  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_paid_submission') ):

function wpestate_paid_submission($post){
    global $post;

    $paid_submission_status= esc_html ( wprentals_get_option('wp_estate_paid_submission','') );

    if($paid_submission_status=='no'){

        esc_html_e('Paid Submission is disabled','wprentals-core');

    }else if($paid_submission_status=='membership'){

        esc_html_e('Part of membership package','wprentals-core');

    }else if($paid_submission_status=='per listing'){

        esc_html_e('Pay Status: ','wprentals-core');
        $pay_status           = get_post_meta($post->ID, 'pay_status', true);
        if($pay_status=='paid'){
            esc_html_e('PAID','wprentals-core');
        }
        else{
            esc_html_e('Not Paid','wprentals-core');
        }
    }

}
endif; // end   estate_paid_submission




///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property details  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_details_estate_box') ):

function wpestate_details_estate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    $week_days=array(
    '0'=>esc_html__('All','wprentals-core'),
    '1'=>esc_html__('Monday','wprentals-core'),
    '2'=>esc_html__('Tuesday','wprentals-core'),
    '3'=>esc_html__('Wednesday','wprentals-core'),
    '4'=>esc_html__('Thursday','wprentals-core'),
    '5'=>esc_html__('Friday','wprentals-core'),
    '6'=>esc_html__('Saturday','wprentals-core'),
    '7'=>esc_html__('Sunday','wprentals-core')

    );


    $options_array=array(
            0   =>  esc_html__('Single Fee','wprentals-core'),
            1   =>  esc_html__('Per Night','wprentals-core'),
            2   =>  esc_html__('Per Guest','wprentals-core'),
            3   =>  esc_html__('Per Night per Guest','wprentals-core')
        );

    $mypost             =   $post->ID;

    $checkin_change_over            =   floatval   ( get_post_meta($mypost, 'checkin_change_over', true) );
    $checkin_checkout_change_over   =   floatval   ( get_post_meta($mypost, 'checkin_checkout_change_over', true) );
    $city_fee_per_day               =   floatval   ( get_post_meta($mypost, 'city_fee_per_day', true) );
    $cleaning_fee_per_day           =   floatval   ( get_post_meta($mypost, 'cleaning_fee_per_day', true) );
    $city_fee_percent               =   floatval   ( get_post_meta($mypost, 'city_fee_percent', true) );

    print'
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price: ','wprentals-core').'</label><br />
            <input type="text" id="property_price" size="40" name="property_price" value="' . intval(get_post_meta($mypost, 'property_price', true)) . '">
            </p>
        </td>';

        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price_before_label">'.esc_html__( 'Before Price Label: ','wprentals-core').'</label><br />
            <input type="text" id="property_price_before_label" size="40" name="property_price_before_label" value="' . esc_html(get_post_meta($mypost, 'property_price_before_label', true)) . '">
            </p>
        </td>';

        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price_after_label">'.esc_html__( 'After Price Label: ','wprentals-core').'</label><br />
            <input type="text" id="property_price_after_label" size="40" name="property_price_after_label" value="' . esc_html(get_post_meta($mypost, 'property_price_after_label', true)) . '">
            </p>
        </td>';

    print'
    </tr>

    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_taxes">'.esc_html__( 'Taxes in % (taxes are considered included in the daily price): ','wprentals-core').'</label><br />
            <input type="text" id="property_taxes" size="40" name="property_taxes" value="' . esc_html(get_post_meta($mypost, 'property_taxes', true)) . '">
            </p>
        </td>';

        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="security_deposit">'.esc_html__( 'Security Deposit ','wprentals-core').'</label><br />
            <input type="text" id="security_deposit" size="40" name="security_deposit" value="' . esc_html(get_post_meta($mypost, 'security_deposit', true)) . '">
            </p>
        </td>';

    print'
    </tr>

    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="early_bird_percent">'.esc_html__( 'Early Bird Discount value- in % from the price per night','wprentals-core').'</label><br />
            <input type="text" id="early_bird_percent" size="40" name="early_bird_percent" value="' . esc_html(get_post_meta($mypost, 'early_bird_percent', true)) . '">
            </p>
        </td>';

        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="early_bird_days">'.esc_html__( 'Early Bird Discount no of days ','wprentals-core').'</label><br />
            <input type="text" id="early_bird_days" size="40" name="early_bird_days" value="' . esc_html(get_post_meta($mypost, 'early_bird_days', true)) . '">
            </p>
        </td>';


    print'
    </tr>


    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="cleaning_fee">'.esc_html__( 'Cleaning Fee:','wprentals-core').'</label><br />
                <input type="text" id="cleaning_fee" size="40" name="cleaning_fee" value="' . floatval(get_post_meta($mypost, 'cleaning_fee', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
                <p class="meta-options">
                    <label for="cleaning_fee_per_day">'.esc_html__( 'Cleaning Fee for:','wprentals-core').'</label><br />
                        <select id="cleaning_fee_per_day" name="cleaning_fee_per_day" class="select-select_submit_price">';
                            foreach($options_array as $key=>$option){
                                print '   <option value="'.$key.'"';
                                if( $key==$cleaning_fee_per_day){
                                    print ' selected="selected" ';
                                }
                                print '>'.$option.'</option>';
                            }
                        print'
                        </select>
                </p>
         </td>
    </tr>

    <tr>

    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="city_fee">'.esc_html__( 'City Fee:','wprentals-core').'</label><br />
                <input type="text" id="city_fee" size="40" name="city_fee" value="' . floatval(get_post_meta($mypost, 'city_fee', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="city_fee_per_day">'.esc_html__( 'City Fee for:','wprentals-core').'</label><br />
                    <select id="city_fee_per_day" name="city_fee_per_day" class="select_submit_price">';
                        foreach($options_array as $key=>$option){
                            print '   <option value="'.$key.'"';
                            if( $key==$city_fee_per_day){
                                print ' selected="selected" ';
                            }
                            print '>'.$option.'</option>';
                        }
                    print'
                    </select>
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <input type="hidden" name="city_fee_percent" value="0">
                <input type="checkbox"  id="city_fee_percent" name="city_fee_percent" value="1" ';
                if (intval(get_post_meta($mypost, 'city_fee_percent', true)) == 1) {
                    print'checked="checked"';
                }
                print' />
                <label for="city_fee_percent">'.esc_html__( 'City Fee is a % of the daily fee','wprentals-core').'</label>
            </p>
        </td>
    </tr>


    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="price_per_weekeend">'. esc_html__('Price per weekend (Saturday and Sundays)','wprentals-core').'</label><br />
                <input type="text" id="price_per_weekeend" size="40" name="price_per_weekeend" value="' . floatval(get_post_meta($mypost, 'price_per_weekeend', true)) . '">
            </p>
        </td>

        <td>
            <p class="meta-options">
            <label for="min_days_booking">'.esc_html__('Minimum days of booking (only numbers) ','wprentals-core').'</label></br>
            <input type="text" id="min_days_booking" class="form-control" size="40" name="min_days_booking" value="' . floatval(get_post_meta($mypost, 'min_days_booking', true)) . '">
            </p>
        </td>

    </tr>


    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price per night (7d+): ','wprentals-core').'</label><br />
            <input type="text" id="property_price_per_week" size="40" name="property_price_per_week" value="' . esc_html(get_post_meta($mypost, 'property_price_per_week', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price per night (30d+): ','wprentals-core').'</label><br />
            <input type="text" id="property_price_per_month" size="40" name="property_price_per_month" value="' . esc_html(get_post_meta($mypost, 'property_price_per_month', true)) . '">
            </p>
        </td>
    </tr>

    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="extra_price_per_guest">'.esc_html__( 'Extra Price per guest per night','wprentals-core').'</label><br />
            <input type="text" id="extra_price_per_guest" size="40" name="extra_price_per_guest" value="' . esc_html(get_post_meta($mypost, 'extra_price_per_guest', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
           <p class="meta-options">
                <input type="hidden" name="overload_guest" value="0">
                <input type="checkbox"  id="overload_guest" name="overload_guest" value="1" ';
                if (intval(get_post_meta($mypost, 'overload_guest', true)) == 1) {
                    print'checked="checked"';
                }
                print' />
                <label for="overload_guest">'.esc_html__( 'Allow guests above capacity?','wprentals-core').'</label>
            </p>
        </td>
    </tr>

    <tr>
        <td valign="top" align="left">
        '.esc_html__('These options do not work together - choose only one and leave the other one on "All"','wprentals-core').'
        </td>
    </tr>


      <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="checkin_change_over">'. esc_html__('Allow only bookings starting with the check-in on:','wprentals-core').'</label></br>
            <select id="checkin_change_over" name="checkin_change_over" class="select-submit2">';

                foreach($week_days as $key=>$value){
                    print '   <option value="'.$key.'"';
                    if( $key==$checkin_change_over){
                        print ' selected="selected" ';
                    }
                    print '>'.$value.'</option>';
                }
            print'
            </select>
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="checkin_checkout_change_over">'. esc_html__('Allow only bookings with the check-in/check-out on: ','wprentals-core').'</label></br>
            <select id="checkin_checkout_change_over" name="checkin_checkout_change_over" class="select-submit2">';

                foreach($week_days as $key=>$value){
                   print '   <option value="'.$key.'"';
                    if( $key==$checkin_checkout_change_over){
                        print ' selected="selected" ';
                    }
                    print '>'.$value.'</option>';
                }
              print'
            </p>
        </td>
    </tr>




    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_size">'.esc_html__( 'Size: ','wprentals-core').'</label><br />
            <input type="text" id="property_size" size="40" name="property_size" value="' . esc_html(get_post_meta($mypost, 'property_size', true)) . '">
            </p>
        </td>



        <td valign="top" align="left">
            <p class="meta-options">
            <label for="property_rooms">'.esc_html__( 'Rooms: ','wprentals-core').'</label><br />
            <input type="text" id="property_rooms" size="40" name="property_rooms" value="' . esc_html(get_post_meta($mypost, 'property_rooms', true)) . '">
            </p>
        </td>
    </tr>

    <tr>
        <td valign="top" align="left">
            <p class="meta-options">
            <label for="property_bedrooms">'.esc_html__( 'Bedrooms: ','wprentals-core').'</label><br />
            <input type="text" id="property_bedrooms" size="40" name="property_bedrooms" value="' . esc_html(get_post_meta($mypost, 'property_bedrooms', true)) . '">
            </p>
        </td>

        <td valign="top" align="left">
            <p class="meta-options">
            <label for="property_bedrooms">'.esc_html__( 'Bathrooms: ','wprentals-core').'</label><br />
            <input type="text" id="property_bathrooms" size="40" name="property_bathrooms" value="' . esc_html(get_post_meta($mypost, 'property_bathrooms', true)) . '">
            </p>
        </td>
    </tr>

    <tr>
    <td valign="top" align="left">
        <p class="meta-options">
        <label for="guest_no">'.esc_html__( 'Guests: ','wprentals-core').'</label><br />
        <input type="text" id="guest_no" size="40" name="guest_no" value="' . esc_html(get_post_meta($mypost, 'guest_no', true)) . '">
        </p>
    </td>

    </tr>
    <tr>';

     $option_video='';
     $video_values = array('vimeo', 'youtube');
     $video_type = get_post_meta($mypost, 'embed_video_type', true);

     foreach ($video_values as $value) {
         $option_video.='<option value="' . $value . '"';
         if ($value == $video_type) {
             $option_video.='selected="selected"';
         }
         $option_video.='>' . $value . '</option>';
     }

    print'<tr>
    <td valign="top" align="left">
        <p class="meta-options">
        <label for="embed_video_type">'.esc_html__( 'Affiliate Link. User will be redirected to this link when he wants to make a booking. ','wprentals-core').'</label><br />
        <input type="text" id="property_affiliate" name="property_affiliate" size="40" value="'.esc_html( get_post_meta($mypost, 'property_affiliate', true) ).'">
        </p>
    </td></tr>';


    print'
    <td valign="top" align="left">
        <p class="meta-options">
        <label for="embed_video_type">'.esc_html__( 'Video from ','wprentals-core').'</label><br />
        <select id="embed_video_type" name="embed_video_type" style="width: 237px;">
                ' . $option_video . '
        </select>
        </p>
    </td>';


    print'
    <td valign="top" align="left">
      <p class="meta-options">
      <label for="embed_video_id">'.esc_html__( 'Video id: ','wprentals-core').'</label> <br />
        <input type="text" id="embed_video_id" name="embed_video_id" size="40" value="'.esc_html( get_post_meta($mypost, 'embed_video_id', true) ).'">
      </p>
    </td>
    </tr>';


    print'
    <td valign="top" align="left">
      <p class="meta-options">
      <label for="embed_video_id">'.esc_html__( 'Virtual Tour. Copy/paste the iframe code.','wprentals-core').'</label> <br />
        <textarea id="virtual_tour" name="virtual_tour" rows="7" > '.esc_html( get_post_meta($mypost, 'virtual_tour', true) ).'</textarea>
      </p>
    </td>
    </tr>';


     print'
    <td valign="top" align="left">
      <p class="meta-options">
      <label for="private_notes">'.esc_html__( 'Private Notes.','wprentals-core').'</label> <br />
        <textarea id="private_notes" name="private_notes" rows="7" > '.esc_html( get_post_meta($mypost, 'private_notes', true) ).'</textarea>
      </p>
    </td>
    </tr>';

    print '</table>';
}
endif; // end   details_estate_box



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Google map function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_map_estate_box') ):

function wpestate_map_estate_box($post) {
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    global $post;

    $mypost                 =   $post->ID;
    $gmap_lat               =   floatval(get_post_meta($mypost, 'property_latitude', true));
    $gmap_long              =   floatval(get_post_meta($mypost, 'property_longitude', true));
    $google_camera_angle    =   intval( esc_html(get_post_meta($mypost, 'google_camera_angle', true)) );
    $cache_array            =   array('yes','no');
    $keep_min_symbol        =   '';
    $keep_min_status        =   esc_html ( get_post_meta($post->ID, 'keep_min', true) );

    foreach($cache_array as $value){
            $keep_min_symbol.='<option value="'.$value.'"';
            if ($keep_min_status==$value){
                    $keep_min_symbol.=' selected="selected" ';
            }
            $keep_min_symbol.='>'.$value.'</option>';
    }

    print '<script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function(){
        '.wpestate_date_picker_translation("property_date").'
    });
    //]]>
    </script>
    <p class="meta-options">
    <div id="googleMap" style="width:100%;height:380px;margin-bottom:30px;"></div> ';

    if(  wprentals_get_option('wp_estate_kind_of_places')!=2){
        print '<p class="meta-options">
            <a class="button" href="#" id="admin_place_pin">'.esc_html__( 'Place Pin with Listing Address','wprentals-core').'</a>
        </p>';
    }

    print esc_html__( 'Latitude:','wprentals-core').'  <input type="text" id="property_latitude" style="margin-right:20px;" size="40" name="property_latitude" value="' . $gmap_lat . '">
    '.esc_html__( 'Longitude:','wprentals-core').' <input type="text" id="property_longitude" style="margin-right:20px;" size="40" name="property_longitude" value="' . $gmap_long . '">
    <p>
    <p class="meta-options">
    <label for="google_camera_angle" >'.esc_html__( 'Google View Camera Angle','wprentals-core').'</label>
    <input type="text" id="google_camera_angle" style="margin-right:0px;" size="5" name="google_camera_angle" value="'.$google_camera_angle.'">

    </p>';

    $page_custom_zoom  = get_post_meta($mypost, 'page_custom_zoom', true);
    if ($page_custom_zoom==''){
        $page_custom_zoom=16;
    }

    print '
     <p class="meta-options">
       <label for="page_custom_zoom">'.esc_html__( 'Zoom Level for map (1-20)','wprentals-core').'</label><br />
       <select name="page_custom_zoom" id="page_custom_zoom">';

      for ($i=1;$i<21;$i++){
           print '<option value="'.$i.'"';
           if($page_custom_zoom==$i){
               print ' selected="selected" ';
           }
           print '>'.$i.'</option>';
       }

     print'
       </select>
    ';



}
endif; // end   map_estate_box






///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Agent box function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_agentestate_box') ):
function wpestate_agentestate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');

    $mypost         =   $post->ID;
    $originalpost   =   $post;
    $agent_list     =   '';
    $picked_agent   =   wpsestate_get_author($mypost);
    $blogusers = get_users( 'blog_id=1&orderby=nicename' );

    foreach ( $blogusers as $user ) {
        $the_id       =  $user->ID;
        $agent_list  .=  '<option value="' . $the_id . '"  ';
        if ($the_id == $picked_agent) {
           $agent_list.=' selected="selected" ';
        }
        $user_info = get_userdata($the_id);
        $username = $user_info->user_login;
        $first_name = $user_info->first_name;
        $last_name = $user_info->last_name;
        $agent_list.= '>' .  $user->user_login .' - '.$first_name.' '.$last_name.'</option>';
    }



    wp_reset_postdata();
    $post = $originalpost;
    $originalAuthor = get_post_meta($mypost, 'original_author',true );
    //print ($originalAuthor);
    print '
    <label for="property_zip">'.esc_html__( 'Listing Owner: ','wprentals-core').'</label><br />
    <select id="property_agent" style="width: 237px;" name="property_agent">
        <option value="">none</option>
        <option value=""></option>
        '. $agent_list .'
    </select>';
}
endif; // end   agentestate_box





function wpestate_display_admin_item($item,$edit_id){
    $return='';
    $css_class='property_prop_half';
    if(isset($item['iscssfull']) && $item['iscssfull']=='yes'){
        $css_class='property_prop_full';
    }

    if( $item['type']=='input'){

        $return = '<div class="'.$css_class.'">
            <label for="'.$item['name'].'">'.$item['label'].'</label><br>
            <input type="text" id="'.$item['name'].'" size="40" name="'.$item['name'].'" value="'.get_post_meta($edit_id,$item['name'],true).'">
        </div>';

    }else if(  $item['type']=='select' ){

        $return = '<div class="'.$css_class.'">
            <label for="'.$item['name'].'">'.$item['label'].'</label><br>
            <select id="'.$item['name'].'"  name="'.$item['name'].'" >';

            $selected=get_post_meta($edit_id,$item['name'],true);
            foreach($item['defaults'] as $key=>$value){
                $return.='<option value="' . $key . '"';
                if (strtolower($selected) == strtolower ($key) ) {
                    $return.='selected="selected"';
                }
                $return.='>' . $value . '</option>';
            }

        $return.='</select>
        </div>';


    }else if(  $item['type']=='checkbox' ){

        $return.='<div class="'.$css_class.'">
            <input type="hidden" name="'.$item['name'].'" value="0">
            <input type="checkbox"  id="'.$item['name'].'" name="'.$item['name'].'" value="'.$item['defaults'].'" ';
            if (intval(get_post_meta($edit_id, $item['name'], true)) == 1) {
                $return.='checked="checked"';
            }
            $return.=' />
            <label for="'.$item['name'].'">'.$item['label'].'</label>
        </div>';
    }else if($item['type']=='textarea'){
            $return = '<div class="'.$css_class.'">
            <label for="'.$item['name'].'">'.$item['label'].'</label><br>
            <textarea type="text" id=">'.$item['name'].'"  name="'.$item['name'].'" > '.get_post_meta($edit_id,$item['name'],true).'</textarea>
        </div>';

    }  else if($item['type']=='radio'){

        $value = esc_html(get_post_meta($edit_id,$item['name'],true));
        if($value=='yes' ){
            $check_yes = ' checked ';
            $check_no  = ' ';
        }else{
            $check_yes = '  ';
            $check_no  = ' checked ';
        }


         $return = '<div class="'.$css_class.'">
            <label for="smoking_allowed">'.$item['label'].'</label>
            <input type="radio"   name="'.$item['name'].'" value="yes"    '.$check_yes.' >'.$item['radio_label'][0].'
            <input type="radio"  class="second_radio"  name="'.$item['name'].'" '.$check_no.' value="no">'.$item['radio_label'][1].'
        </div>';
    }
    return $return;

}




///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Property custom fields
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_listing_details_box') ):
function wpestate_listing_details_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');


    $items=array(
        array(
            'name'  =>  'guest_no',
            'label' => esc_html__('Guest Number','wprentals-core'),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'property_address',
            'label' => esc_html__('Property Address','wprentals-core'),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'property_county',
            'label' => esc_html__('Property County','wprentals-core'),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'property_state',
            'label' => esc_html__('Property State','wprentals-core'),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'property_zip',
            'label' => esc_html__('Property Zip','wprentals-core'),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'property_country',
            'label' => esc_html__('Property Country','wprentals-core'),
            'type'  => 'select',
            'defaults'=>wpestate_country_list_only_array(),
        ),

        array(
            'name'  =>  'prop_featured',
            'label' => esc_html__('Make it Featured','wprentals-core'),
            'type'  => 'checkbox',
            'defaults'=>1,
        ),
        array(
            'name'  =>  'property_affiliate',
            'label' => esc_html__('Affiliate Link','wprentals-core'),
            'type'  => 'input',
            'defaults'=>1,
            'iscssfull'=>'yes'
        ),
        array(
            'name'  =>  'private_notes',
            'label' => esc_html__('Private Notes','wprentals-core'),
            'type'  => 'textarea',
            'defaults'=>1,
            'iscssfull'=>'yes'
        ),

        array(
            'name'  =>  'instant_booking',
            'label' => esc_html__('Allow instant booking?','wprentals-core'),
            'type'  => 'checkbox',
            'defaults'=>1,
        ),


    );

    foreach($items as $item){
        print wpestate_display_admin_item($item,$post->ID);
    }




}
endif; // end   estate_box




if( !function_exists('wpestate_property_price_admin') ):
    function wpestate_property_price_admin($post){
        global $post;
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
        $measure_sys            = esc_html ( wprentals_get_option('wp_estate_measure_sys','') );
        $booking = array(
                    1 => __("Per Day/Night","wprentals"),
                    2 => __("Per Hour","wprentals"),

                    );
        $week_days=array(
            '0'=>esc_html__('All','wprentals'),
            '1'=>esc_html__('Monday','wprentals'),
            '2'=>esc_html__('Tuesday','wprentals'),
            '3'=>esc_html__('Wednesday','wprentals'),
            '4'=>esc_html__('Thursday','wprentals'),
            '5'=>esc_html__('Friday','wprentals'),
            '6'=>esc_html__('Saturday','wprentals'),
            '7'=>esc_html__('Sunday','wprentals')

            );
        $wp_estate_currency_symbol = esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
        $setup_weekend_status= esc_html ( wprentals_get_option('wp_estate_setup_weekend','') );
        $weekedn = array(
                0 => __("Sunday and Saturday","wprentals"),
                1 => __("Friday and Saturday","wprentals"),
                2 => __("Friday, Saturday and Sunday","wprentals")
                );


        $rental_type=wprentals_get_option('wp_estate_item_rental_type');
        $booking_type           =   wprentals_return_booking_type($post->ID);
        $options_array=array(
            0   =>  esc_html__('Single Fee','wprentals'),
            1   =>  ucfirst( wpestate_show_labels('per_night',$rental_type,$booking_type) ),
            2   =>  esc_html__('Per Guest','wprentals'),
            3   =>  ucfirst( wpestate_show_labels('per_night',$rental_type,$booking_type)).' '.esc_html__('per Guest','wprentals')
        );

        $items=array(
            array(
                'name'  =>  'local_booking_type',
                'label' => esc_html__('Booking Type','wprentals-core'),
                'type'  => 'select',
                'defaults'=>$booking
            ),
            array(
                'name'  =>  'property_price',
                'label' => esc_html__('Property Price','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'property_price_before_label',
                'label' => esc_html__('Before Label','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'property_price_after_label',
                'label' => esc_html__('After Label','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'property_taxes',
                'label' => esc_html__('Property Taxes in %','wprentals-core'),
                'type'  => 'input',
            ),
             array(
                'name'  =>  'property_price_per_week',
                'label' => esc_html__('Price per night (7d+)','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'property_price_per_month',
                'label' => esc_html__('Price per night (30d+)','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'price_per_weekeend',
                'label' => esc_html__('Price per weekened','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'cleaning_fee',
                'label' => esc_html__('Cleaning Fee','wprentals-core'),
                'type'  => 'input',
            ),

             array(
                'name'  =>  'cleaning_fee_per_day',
                'label' => esc_html__('Cleaning Fee calculation','wprentals-core'),
                'type'  => 'select',
                'defaults'=>$options_array,
            ),

            array(
                'name'  =>  'city_fee',
                'label' => esc_html__('City Fee','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'city_fee_per_day',
                'label' => esc_html__('City Fee calculation','wprentals-core'),
                'type'  => 'select',
                'defaults'=>$options_array,
            ),

            array(
                'name'  =>  'min_days_booking',
                'label' => esc_html__('Minimum Days of booking','wprentals-core'),
                'type'  => 'input',
            ),

            array(
                'name'  =>  'security_deposit',
                'label' => esc_html__('Security Deposit','wprentals-core'),
                'type'  => 'input',
            ),

            array(
                'name'  =>  'early_bird_percent',
                'label' => esc_html__('Early bird discount','wprentals-core'),
                'type'  => 'input',
            ),

            array(
                'name'  =>  'early_bird_days',
                'label' => esc_html__('Early bird days before','wprentals-core'),
                'type'  => 'input',
            ),

            array(
                'name'  =>  'extra_price_per_guest',
                'label' => esc_html__('Extra Price per Guest','wprentals-core'),
                'type'  => 'input',
            ),

            array(
                'name'  =>  'overload_guest',
                'label' => esc_html__('Allow guests above capacity?','wprentals-core'),
                'type'  => 'checkbox',
                'defaults'=>1
            ),
            array(
                'name'=>'max_extra_guest_no',
                 'label' => esc_html__('Maximum extra guests above capacity(if extra guest are allowed)','wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  =>  'price_per_guest_from_one',
                'label' => esc_html__('Pay by the no of guests (room prices will NOT be used anymore and billing will be done by guest no only)','wprentals-core'),
                'type'  => 'checkbox',
                'defaults'=>1
            ),

              array(
                'name'  =>  'checkin_change_over',
                'label' => esc_html__('Allow only bookings starting with the check-in on','wprentals-core'),
                'type'  => 'select',
                'defaults'=>$week_days,
            ),

              array(
                'name'  =>  'checkin_checkout_change_over',
                'label' => esc_html__('Allow only bookings with the check-in/check-out on','wprentals-core'),
                'type'  => 'select',
                'defaults'=>$week_days,
            ),
        );







        foreach($items as $item){
            print wpestate_display_admin_item($item,$post->ID);
        }
    }
endif;







if( !function_exists('wpestate_listing_details_specific_box') ):
function wpestate_listing_details_specific_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    $measure_sys            = esc_html ( wprentals_get_option('wp_estate_measure_sys','') );

    $items=array(
        array(
            'name'  =>  'property_size',
            'label' => esc_html__('Property Size in','wprentals-core').' '.esc_html($measure_sys),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'property_rooms',
            'label' => esc_html__('Property Rooms','wprentals-core'),
            'type'  => 'input',
        ),

        array(
            'name'  =>  'property_bedrooms',
            'label' => esc_html__('Property Bedrooms','wprentals-core'),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'property_bathrooms',
            'label' => esc_html__('Property Bathrooms','wprentals-core'),
            'type'  => 'input',
        ),
        array(
            'name'  =>  'cancellation_policy',
            'label' => esc_html__('Cancellation Policy','wprentals-core'),
            'type'  => 'textarea',
            'iscssfull'=>'yes'
        ),
        array(
            'name'  =>  'other_rules',
            'label' => esc_html__('Other Rules','wprentals-core'),
            'type'  => 'textarea',
            'iscssfull'=>'yes'
        ),

        array(
            'name'  =>  'smoking_allowed',
            'label' => esc_html__('Smoking Allowed','wprentals-core'),
            'type'  => 'radio',
            'radio_label' =>array('yes','no'),
        ),



         array(
            'name'  =>  'party_allowed',
            'label' => esc_html__('Party Allowed','wprentals-core'),
            'type'  => 'radio',
            'radio_label' =>array('yes','no'),
        ),

        array(
            'name'  =>  'pets_allowed',
            'label' => esc_html__('Pets Allowed','wprentals-core'),
            'type'  => 'radio',
            'radio_label' =>array('yes','no'),
        ),

        array(
            'name'  =>  'children_allowed',
            'label' => esc_html__('Children Allowed','wprentals-core'),
            'type'  => 'radio',
            'radio_label' =>array('yes','no'),
        ),
    );

    foreach($items as $item){
        print wpestate_display_admin_item($item,$post->ID);
    }

   wpestate_custom_details_box($post);


}
endif; // end   estate_box




if( !function_exists('wpestate_country_list_only_array') ):
function wpestate_country_list_only_array() {
     $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals-core'),
                            'Albania'               => esc_html__('Albania','wprentals-core'),
                            'Algeria'               => esc_html__('Algeria','wprentals-core'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals-core'),
                            'Andorra'               => esc_html__('Andorra','wprentals-core'),
                            'Angola'                => esc_html__('Angola','wprentals-core'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals-core'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals-core'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals-core'),
                            'Argentina'             => esc_html__('Argentina','wprentals-core'),
                            'Armenia'               => esc_html__('Armenia','wprentals-core'),
                            'Aruba'                 => esc_html__('Aruba','wprentals-core'),
                            'Australia'             => esc_html__('Australia','wprentals-core'),
                            'Austria'               => esc_html__('Austria','wprentals-core'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals-core'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals-core'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals-core'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals-core'),
                            'Barbados'              => esc_html__('Barbados','wprentals-core'),
                            'Belarus'               => esc_html__('Belarus','wprentals-core'),
                            'Belgium'               => esc_html__('Belgium','wprentals-core'),
                            'Belize'                => esc_html__('Belize','wprentals-core'),
                            'Benin'                 => esc_html__('Benin','wprentals-core'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals-core'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals-core'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals-core'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals-core'),
                            'Botswana'              => esc_html__('Botswana','wprentals-core'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals-core'),
                            'Brazil'                => esc_html__('Brazil','wprentals-core'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals-core'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals-core'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals-core'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals-core'),
                            'Burundi'               => esc_html__('Burundi','wprentals-core'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals-core'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals-core'),
                            'Canada'                => esc_html__('Canada','wprentals-core'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals-core'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals-core'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals-core'),
                            'Chad'                  => esc_html__('Chad','wprentals-core'),
                            'Chile'                 => esc_html__('Chile','wprentals-core'),
                            'China'                 => esc_html__('China','wprentals-core'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals-core'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals-core'),
                            'Colombia'              => esc_html__('Colombia','wprentals-core'),
                            'Comoros'               => esc_html__('Comoros','wprentals-core'),
                            'Congo'                 => esc_html__('Congo','wprentals-core'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals-core'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals-core'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals-core'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals-core'),
                            'Croatia'               => esc_html__('Croatia','wprentals-core'),
                            'Cuba'                  => esc_html__('Cuba','wprentals-core'),
                            'Curacao'               => esc_html__('Curacao','wprentals-core'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals-core'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals-core'),
                            'Denmark'               => esc_html__('Denmark','wprentals-core'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals-core'),
                            'Dominica'              => esc_html__('Dominica','wprentals-core'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals-core'),
                            'East Timor'            => esc_html__('East Timor','wprentals-core'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals-core'),
                            'Egypt'                 => esc_html__('Egypt','wprentals-core'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals-core'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals-core'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals-core'),
                            'Estonia'               => esc_html__('Estonia','wprentals-core'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals-core'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals-core'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals-core'),
                            'Fiji'                  => esc_html__('Fiji','wprentals-core'),
                            'Finland'               => esc_html__('Finland','wprentals-core'),
                            'France'                => esc_html__('France','wprentals-core'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals-core'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals-core'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals-core'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals-core'),
                            'Gabon'                 => esc_html__('Gabon','wprentals-core'),
                            'Gambia'                => esc_html__('Gambia','wprentals-core'),
                            'Georgia'               => esc_html__('Georgia','wprentals-core'),
                            'Germany'               => esc_html__('Germany','wprentals-core'),
                            'Ghana'                 => esc_html__('Ghana','wprentals-core'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals-core'),
                            'Greece'                => esc_html__('Greece','wprentals-core'),
                            'Greenland'             => esc_html__('Greenland','wprentals-core'),
                            'Grenada'               => esc_html__('Grenada','wprentals-core'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals-core'),
                            'Guam'                  => esc_html__('Guam','wprentals-core'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals-core'),
                            'Guinea'                => esc_html__('Guinea','wprentals-core'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals-core'),
                            'Guyana'                => esc_html__('Guyana','wprentals-core'),
                            'Haiti'                 => esc_html__('Haiti','wprentals-core'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals-core'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals-core'),
                            'Honduras'              => esc_html__('Honduras','wprentals-core'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals-core'),
                            'Hungary'               => esc_html__('Hungary','wprentals-core'),
                            'Iceland'               => esc_html__('Iceland','wprentals-core'),
                            'India'                 => esc_html__('India','wprentals-core'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals-core'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals-core'),
                            'Iraq'                  => esc_html__('Iraq','wprentals-core'),
                            'Ireland'               => esc_html__('Ireland','wprentals-core'),
                            'Israel'                => esc_html__('Israel','wprentals-core'),
                            'Italy'                 => esc_html__('Italy','wprentals-core'),
                            'Island of Saba'        => esc_html__('Island of Saba','wprentals-core'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals-core'),
                            'Japan'                 => esc_html__('Japan','wprentals-core'),
                            'Jordan'                => esc_html__('Jordan','wprentals-core'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals-core'),
                            'Kenya'                 => esc_html__('Kenya','wprentals-core'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals-core'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals-core'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals-core'),
                            'Kosovo'                => esc_html__('Kosovo', 'wprentals-core'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals-core'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals-core'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals-core'),
                            'Latvia'                => esc_html__('Latvia','wprentals-core'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals-core'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals-core'),
                            'Liberia'               => esc_html__('Liberia','wprentals-core'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals-core'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals-core'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals-core'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals-core'),
                            'Macau'                 => esc_html__('Macau','wprentals-core'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals-core'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals-core'),
                            'Malawi'                => esc_html__('Malawi','wprentals-core'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals-core'),
                            'Maldives'              => esc_html__('Maldives','wprentals-core'),
                            'Mali'                  => esc_html__('Mali','wprentals-core'),
                            'Malta'                 => esc_html__('Malta','wprentals-core'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals-core'),
                            'Martinique'            => esc_html__('Martinique','wprentals-core'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals-core'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals-core'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals-core'),
                            'Mexico'                => esc_html__('Mexico','wprentals-core'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals-core'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals-core'),
                            'Monaco'                => esc_html__('Monaco','wprentals-core'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals-core'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals-core'),
                            'Morocco'               => esc_html__('Morocco','wprentals-core'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals-core'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals-core'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals-core'),
                            'Namibia'               => esc_html__('Namibia','wprentals-core'),
                            'Nauru'                 => esc_html__('Nauru','wprentals-core'),
                            'Nepal'                 => esc_html__('Nepal','wprentals-core'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals-core'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals-core'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals-core'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals-core'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals-core'),
                            'Niger'                 => esc_html__('Niger','wprentals-core'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals-core'),
                            'Niue'                  => esc_html__('Niue','wprentals-core'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals-core'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals-core'),
                            'Norway'                => esc_html__('Norway','wprentals-core'),
                            'Oman'                  => esc_html__('Oman','wprentals-core'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals-core'),
                            'Palau'                 => esc_html__('Palau','wprentals-core'),
                            'Panama'                => esc_html__('Panama','wprentals-core'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals-core'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals-core'),
                            'Peru'                  => esc_html__('Peru','wprentals-core'),
                            'Philippines'           => esc_html__('Philippines','wprentals-core'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals-core'),
                            'Poland'                => esc_html__('Poland','wprentals-core'),
                            'Portugal'              => esc_html__('Portugal','wprentals-core'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals-core'),
                            'Qatar'                 => esc_html__('Qatar','wprentals-core'),
                            'Reunion'               => esc_html__('Reunion','wprentals-core'),
                            'Romania'               => esc_html__('Romania','wprentals-core'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals-core'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals-core'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals-core'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals-core'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals-core'),
                            'Saint Barthélemy'      => esc_html__('Saint Barthélemy','wprentals'),
                            'Saint Martin'          => esc_html__('Saint Martin','wprentals'),
                            'Sint Maarten'          => esc_html__('Sint Maarten','wprentals'),
                            'Samoa'                 => esc_html__('Samoa','wprentals-core'),
                            'San Marino'            => esc_html__('San Marino','wprentals-core'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals-core'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals-core'),
                            'Serbia'                => esc_html__('Serbia','wprentals-core'),
                            'Senegal'               => esc_html__('Senegal','wprentals-core'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals-core'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals-core'),
                            'Singapore'             => esc_html__('Singapore','wprentals-core'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals-core'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals-core'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals-core'),
                            'Somalia'               => esc_html__('Somalia','wprentals-core'),
                            'South Africa'          => esc_html__('South Africa','wprentals-core'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals-core'),
                            'Spain'                 => esc_html__('Spain','wprentals-core'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals-core'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals-core'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals-core'),
                            'Sudan'                 => esc_html__('Sudan','wprentals-core'),
                            'Suriname'              => esc_html__('Suriname','wprentals-core'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals-core'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals-core'),
                            'Sweden'                => esc_html__('Sweden','wprentals-core'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals-core'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals-core'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals-core'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals-core'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals-core'),
                            'Thailand'              => esc_html__('Thailand','wprentals-core'),
                            'Togo'                  => esc_html__('Togo','wprentals-core'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals-core'),
                            'Tonga'                 => esc_html__('Tonga','wprentals-core'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals-core'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals-core'),
                            'Turkey'                => esc_html__('Turkey','wprentals-core'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals-core'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals-core'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals-core'),
                            'Uganda'                => esc_html__('Uganda','wprentals-core'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals-core'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals-core'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals-core'),
                            'United States'         => esc_html__('United States','wprentals-core'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals-core'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals-core'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals-core'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals-core'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals-core'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals-core'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals-core'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals-core'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals-core'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals-core'),
                            'Yemen'                 => esc_html__('Yemen','wprentals-core'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals-core'),
                            'Zambia'                => esc_html__('Zambia','wprentals-core'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals-core')
        );

        return $countries;
}
endif;



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Country list function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_country_list') ):
function wpestate_country_list($selected,$class='') {
    //$countries = array(esc_html__('Afghanistan','wprentals-core'),esc_html__('Albania','wprentals-core'),esc_html__('Algeria','wprentals-core'),esc_html__('American Samoa','wprentals-core'),esc_html__('Andorra','wprentals-core'),esc_html__('Angola','wprentals-core'),esc_html__('Anguilla','wprentals-core'),esc_html__('Antarctica','wprentals-core'),esc_html__('Antigua and Barbuda','wprentals-core'),esc_html__('Argentina','wprentals-core'),esc_html__('Armenia','wprentals-core'),esc_html__('Aruba','wprentals-core'),esc_html__('Australia','wprentals-core'),esc_html__('Austria','wprentals-core'),esc_html__('Azerbaijan','wprentals-core'),esc_html__('Bahamas','wprentals-core'),esc_html__('Bahrain','wprentals-core'),esc_html__('Bangladesh','wprentals-core'),esc_html__('Barbados','wprentals-core'),esc_html__('Belarus','wprentals-core'),esc_html__('Belgium','wprentals-core'),esc_html__('Belize','wprentals-core'),esc_html__('Benin','wprentals-core'),esc_html__('Bermuda','wprentals-core'),esc_html__('Bhutan','wprentals-core'),esc_html__('Bolivia','wprentals-core'),esc_html__('Bosnia and Herzegowina','wprentals-core'),esc_html__('Botswana','wprentals-core'),esc_html__('Bouvet Island','wprentals-core'),esc_html__('Brazil','wprentals-core'),esc_html__('British Indian Ocean Territory','wprentals-core'),esc_html__('Brunei Darussalam','wprentals-core'),esc_html__('Bulgaria','wprentals-core'),esc_html__('Burkina Faso','wprentals-core'),esc_html__('Burundi','wprentals-core'),esc_html__('Cambodia','wprentals-core'),esc_html__('Cameroon','wprentals-core'),esc_html__('Canada','wprentals-core'),esc_html__('Cape Verde','wprentals-core'),esc_html__('Cayman Islands','wprentals-core'),esc_html__('Central African Republic','wprentals-core'),esc_html__('Chad','wprentals-core'),esc_html__('Chile','wprentals-core'),esc_html__('China','wprentals-core'),esc_html__('Christmas Island','wprentals-core'),esc_html__('Cocos (Keeling) Islands','wprentals-core'),esc_html__('Colombia','wprentals-core'),esc_html__('Comoros','wprentals-core'),esc_html__('Congo','wprentals-core'),esc_html__('Congo, the Democratic Republic of the','wprentals-core'),esc_html__('Cook Islands','wprentals-core'),esc_html__('Costa Rica','wprentals-core'),esc_html__('Cote dIvoire','wprentals-core'),esc_html__('Croatia (Hrvatska)','wprentals-core'),esc_html__('Cuba','wprentals-core'),esc_html__('Curacao','wprentals-core'),esc_html__('Cyprus','wprentals-core'),esc_html__('Czech Republic','wprentals-core'),esc_html__('Denmark','wprentals-core'),esc_html__('Djibouti','wprentals-core'),esc_html__('Dominica','wprentals-core'),esc_html__('Dominican Republic','wprentals-core'),esc_html__('East Timor','wprentals-core'),esc_html__('Ecuador','wprentals-core'),esc_html__('Egypt','wprentals-core'),esc_html__('El Salvador','wprentals-core'),esc_html__('Equatorial Guinea','wprentals-core'),esc_html__('Eritrea','wprentals-core'),esc_html__('Estonia','wprentals-core'),esc_html__('Ethiopia','wprentals-core'),esc_html__('Falkland Islands (Malvinas)','wprentals-core'),esc_html__('Faroe Islands','wprentals-core'),esc_html__('Fiji','wprentals-core'),esc_html__('Finland','wprentals-core'),esc_html__('France','wprentals-core'),esc_html__('France Metropolitan','wprentals-core'),esc_html__('French Guiana','wprentals-core'),esc_html__('French Polynesia','wprentals-core'),esc_html__('French Southern Territories','wprentals-core'),esc_html__('Gabon','wprentals-core'),esc_html__('Gambia','wprentals-core'),esc_html__('Georgia','wprentals-core'),esc_html__('Germany','wprentals-core'),esc_html__('Ghana','wprentals-core'),esc_html__('Gibraltar','wprentals-core'),esc_html__('Greece','wprentals-core'),esc_html__('Greenland','wprentals-core'),esc_html__('Grenada','wprentals-core'),esc_html__('Guadeloupe','wprentals-core'),esc_html__('Guam','wprentals-core'),esc_html__('Guatemala','wprentals-core'),esc_html__('Guinea','wprentals-core'),esc_html__('Guinea-Bissau','wprentals-core'),esc_html__('Guyana','wprentals-core'),esc_html__('Haiti','wprentals-core'),esc_html__('Heard and Mc Donald Islands','wprentals-core'),esc_html__('Holy See (Vatican City State)','wprentals-core'),esc_html__('Honduras','wprentals-core'),esc_html__('Hong Kong','wprentals-core'),esc_html__('Hungary','wprentals-core'),esc_html__('Iceland','wprentals-core'),esc_html__('India','wprentals-core'),esc_html__('Indonesia','wprentals-core'),esc_html__('Iran (Islamic Republic of)','wprentals-core'),esc_html__('Iraq','wprentals-core'),esc_html__('Ireland','wprentals-core'),esc_html__('Israel','wprentals-core'),esc_html__('Italy','wprentals-core'),esc_html__('Jamaica','wprentals-core'),esc_html__('Japan','wprentals-core'),esc_html__('Jordan','wprentals-core'),esc_html__('Kazakhstan','wprentals-core'),esc_html__('Kenya','wprentals-core'),esc_html__('Kiribati','wprentals-core'),esc_html__('Korea, Democratic People Republic of','wprentals-core'),esc_html__('Korea, Republic of','wprentals-core'),esc_html__('Kuwait','wprentals-core'),esc_html__('Kyrgyzstan','wprentals-core'),esc_html__('Lao, People Democratic Republic','wprentals-core'),esc_html__('Latvia','wprentals-core'),esc_html__('Lebanon','wprentals-core'),esc_html__('Lesotho','wprentals-core'),esc_html__('Liberia','wprentals-core'),esc_html__('Libyan Arab Jamahiriya','wprentals-core'),esc_html__('Liechtenstein','wprentals-core'),esc_html__('Lithuania','wprentals-core'),esc_html__('Luxembourg','wprentals-core'),esc_html__('Macau','wprentals-core'),esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals-core'),esc_html__('Madagascar','wprentals-core'),esc_html__('Malawi','wprentals-core'),esc_html__('Malaysia','wprentals-core'),esc_html__('Maldives','wprentals-core'),esc_html__('Mali','wprentals-core'),esc_html__('Malta','wprentals-core'),esc_html__('Marshall Islands','wprentals-core'),esc_html__('Martinique','wprentals-core'),esc_html__('Mauritania','wprentals-core'),esc_html__('Mauritius','wprentals-core'),esc_html__('Mayotte','wprentals-core'),esc_html__('Mexico','wprentals-core'),esc_html__('Micronesia, Federated States of','wprentals-core'),esc_html__('Moldova, Republic of','wprentals-core'),esc_html__('Monaco','wprentals-core'),esc_html__('Mongolia','wprentals-core'),esc_html__('Montserrat','wprentals-core'),esc_html__('Morocco','wprentals-core'),esc_html__('Mozambique','wprentals-core'),esc_html__('Montenegro','wprentals-core'),esc_html__('Myanmar','wprentals-core'),esc_html__('Namibia','wprentals-core'),esc_html__('Nauru','wprentals-core'),esc_html__('Nepal','wprentals-core'),esc_html__('Netherlands','wprentals-core'),esc_html__('Netherlands Antilles','wprentals-core'),esc_html__('New Caledonia','wprentals-core'),esc_html__('New Zealand','wprentals-core'),esc_html__('Nicaragua','wprentals-core'),esc_html__('Niger','wprentals-core'),esc_html__('Nigeria','wprentals-core'),esc_html__('Niue','wprentals-core'),esc_html__('Norfolk Island','wprentals-core'),esc_html__('Northern Mariana Islands','wprentals-core'),esc_html__('Norway','wprentals-core'),esc_html__('Oman','wprentals-core'),esc_html__('Pakistan','wprentals-core'),esc_html__('Palau','wprentals-core'),esc_html__('Panama','wprentals-core'),esc_html__('Papua New Guinea','wprentals-core'),esc_html__('Paraguay','wprentals-core'),esc_html__('Peru','wprentals-core'),esc_html__('Philippines','wprentals-core'),esc_html__('Pitcairn','wprentals-core'),esc_html__('Poland','wprentals-core'),esc_html__('Portugal','wprentals-core'),esc_html__('Puerto Rico','wprentals-core'),esc_html__('Qatar','wprentals-core'),esc_html__('Reunion','wprentals-core'),esc_html__('Romania','wprentals-core'),esc_html__('Russian Federation','wprentals-core'),esc_html__('Rwanda','wprentals-core'),esc_html__('Saint Kitts and Nevis','wprentals-core'),esc_html__('Saint Lucia','wprentals-core'),esc_html__('Saint Vincent and the Grenadines','wprentals-core'),esc_html__('Samoa','wprentals-core'),esc_html__('San Marino','wprentals-core'),esc_html__('Sao Tome and Principe','wprentals-core'),esc_html__('Saudi Arabia','wprentals-core'),esc_html__('Serbia','wprentals-core'),esc_html__('Senegal','wprentals-core'),esc_html__('Seychelles','wprentals-core'),esc_html__('Sierra Leone','wprentals-core'),esc_html__('Singapore','wprentals-core'),esc_html__('Slovakia (Slovak Republic)','wprentals-core'),esc_html__('Slovenia','wprentals-core'),esc_html__('Solomon Islands','wprentals-core'),esc_html__('Somalia','wprentals-core'),esc_html__('South Africa','wprentals-core'),esc_html__('South Georgia and the South Sandwich Islands','wprentals-core'),esc_html__('Spain','wprentals-core'),esc_html__('Sri Lanka','wprentals-core'),esc_html__('St. Helena','wprentals-core'),esc_html__('St. Pierre and Miquelon','wprentals-core'),esc_html__('Sudan','wprentals-core'),esc_html__('Suriname','wprentals-core'),esc_html__('Svalbard and Jan Mayen Islands','wprentals-core'),esc_html__('Swaziland','wprentals-core'),esc_html__('Sweden','wprentals-core'),esc_html__('Switzerland','wprentals-core'),esc_html__('Syrian Arab Republic','wprentals-core'),esc_html__('Taiwan, Province of China','wprentals-core'),esc_html__('Tajikistan','wprentals-core'),esc_html__('Tanzania, United Republic of','wprentals-core'),esc_html__('Thailand','wprentals-core'),esc_html__('Togo','wprentals-core'),esc_html__('Tokelau','wprentals-core'),esc_html__('Tonga','wprentals-core'),esc_html__('Trinidad and Tobago','wprentals-core'),esc_html__('Tunisia','wprentals-core'),esc_html__('Turkey','wprentals-core'),esc_html__('Turkmenistan','wprentals-core'),esc_html__('Turks and Caicos Islands','wprentals-core'),esc_html__('Tuvalu','wprentals-core'),esc_html__('Uganda','wprentals-core'),esc_html__('Ukraine','wprentals-core'),esc_html__('United Arab Emirates','wprentals-core'),esc_html__('United Kingdom','wprentals-core'),esc_html__('United States','wprentals-core'),esc_html__('United States Minor Outlying Islands','wprentals-core'),esc_html__('Uruguay','wprentals-core'),esc_html__('Uzbekistan','wprentals-core'),esc_html__('Vanuatu','wprentals-core'),esc_html__('Venezuela','wprentals-core'),esc_html__('Vietnam','wprentals-core'),esc_html__('Virgin Islands (British)','wprentals-core'),esc_html__('Virgin Islands (U.S.)','wprentals-core'),esc_html__('Wallis and Futuna Islands','wprentals-core'),esc_html__('Western Sahara','wprentals-core'),esc_html__('Yemen','wprentals-core'),esc_html__('Yugoslavia','wprentals-core'),esc_html__('Zambia','wprentals-core'),esc_html__('Zimbabwe','wprentals-core'));



    $countries = wpestate_country_list_only_array();


    if ($selected == '') {
        $selected = wprentals_get_option('wp_estate_general_country');
    }

    $country_select = '<select id="property_country"  name="property_country" class="'.$class.'">';


    foreach ($countries as $key=>$country) {
        $country_select.='<option value="' . $key . '"';
        if (strtolower($selected) == strtolower ($key) ) {
            $country_select.='selected="selected"';
        }
        $country_select.='>' . $country . '</option>';
    }

    $country_select.='</select>';
    return $country_select;
}
endif; // end   wpestate_country_list



if( !function_exists('wpestate_agent_list') ):
    function wpestate_agent_list($mypost) {
        return $agent_list;
    }
endif; // end   wpestate_agent_list



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Manage property lists
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'manage_edit-estate_property_columns', 'wpestate_my_columns' );

if( !function_exists('wpestate_my_columns') ):
    function wpestate_my_columns( $columns ) {
        $slice=array_slice($columns,2,2);
        unset( $columns['comments'] );
        unset( $slice['comments'] );
        $splice=array_splice($columns, 2);
        $columns['estate_id']   = esc_html__( 'Id','wprentals-core');
        $columns['estate_image']   = esc_html__( 'Image','wprentals-core');
        $columns['estate_action']   = esc_html__( 'Action','wprentals-core');
        $columns['estate_category'] = esc_html__( 'Category','wprentals-core');
        $columns['estate_autor']    = esc_html__( 'User','wprentals-core');
        $columns['estate_status']   = esc_html__( 'Status','wprentals-core');
        $columns['estate_price']    = esc_html__( 'Price night/day/hour','wprentals-core');
        $columns['estate_featured'] = esc_html__( 'Featured','wprentals-core');

        return  array_merge($columns,array_reverse($slice));
    }
endif; // end   wpestate_my_columns


add_action( 'manage_posts_custom_column', 'wpestate_populate_columns' );
if( !function_exists('wpestate_populate_columns') ):
    function wpestate_populate_columns( $column ) {
        $the_id=get_the_ID();

        if ( 'estate_id' == $column ) {
           print $the_id;
        }

        if ( 'estate_image' == $column ) {
           echo get_the_post_thumbnail($the_id,'wpestate_user_thumb');
        }

        if ( 'estate_featured' == $column ) {
            if (intval(get_post_meta(get_the_ID(), 'prop_featured', true)) == 1) {
                print'Yes';
            }else{
                print 'No';
            }
        }


        if ( 'estate_status' == $column ) {
            $estate_status = get_post_status(get_the_ID());
            if($estate_status=='publish'){
                echo esc_html__( 'published','wprentals-core');
            }else{
                print $estate_status;
            }

            $pay_status    = get_post_meta(get_the_ID(), 'pay_status', true);
            if($pay_status!=''){
                echo " | ".$pay_status;
            }

        }

        if ( 'estate_autor' == $column ) {
            $user_id=wpsestate_get_author(get_the_ID());
            $estate_autor = get_the_author_meta('display_name');;
            echo '<a href="'.get_edit_user_link($user_id).'" >'. $estate_autor.'</a>';
        }

        if ( 'estate_action' == $column ) {
            $estate_action = get_the_term_list( get_the_ID(), 'property_action_category', '', ', ', '');
            print $estate_action;
        }
        elseif ( 'estate_category' == $column ) {
            $estate_category = get_the_term_list( get_the_ID(), 'property_category', '', ', ', '');
            print $estate_category ;
        }

        if ( 'estate_price' == $column ) {
            $wpestate_currency                   =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
            $wpestate_where_currency             =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
            wpestate_show_price(get_the_ID(),$wpestate_currency,$wpestate_where_currency,0);
        }
    }
endif; // end   wpestate_populate_columns






add_filter( 'manage_edit-estate_property_sortable_columns', 'wpestate_sort_me' );
if( !function_exists('wpestate_sort_me') ):
    function wpestate_sort_me( $columns ) {

        $columns['estate_autor'] = 'estate_autor';
        $columns['estate_price'] = 'estate_price';
        return $columns;
    }
endif; // end   wpestate_sort_me


add_filter( 'request', 'bs_event_date_column_orderby' );
function bs_event_date_column_orderby( $vars ) {

    if ( isset( $vars['orderby'] ) && 'estate_price' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'property_price',
            'orderby' => 'meta_value_num'
        ) );
    }


      if ( isset( $vars['orderby'] ) && 'estate_autor' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'orderby' => 'author'
        ) );
    }



    return $vars;
}
add_action( 'property_features_edit_form_fields',   'wpestate_property_features_callback_function', 10, 2);
add_action( 'property_features_add_form_fields',    'wpestate_property_features_callback_add_function', 10, 2 );
add_action( 'created_property_features',            'wpestate_property_features_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_features',             'wpestate_property_features_save_extra_fields_callback', 10, 2);

if( !function_exists('wpestate_property_features_save_extra_fields_callback') ):
    function wpestate_property_features_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif;

if( !function_exists('wpestate_property_features_callback_add_function') ):
    function wpestate_property_features_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $category_attach_id         =   '';
            $category_featured_image    =   '';

        }

        print'
        <div class="form-field">


        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'SVG ICON - SVG ONLY!','wprentals-core').' - <a target="_blank" href="https://help.wprentals.org/article/how-to-add-icons-to-features-and-amenities/">'.esc_html__('Video Tutorial','wprentals-core').'</a></label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload SVG','wprentals-core').'" />
            <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>


        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_features" /></div>
        ';
    }
endif;



if( !function_exists('wpestate_property_features_callback_function') ):
    function wpestate_property_features_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $category_featured_image    =   (isset($term_meta['category_featured_image'] ) && $term_meta['category_featured_image']) ? $term_meta['category_featured_image'] : '';
            $category_attach_id         =   (isset($term_meta['category_attach_id'] ) && $term_meta['category_attach_id']) ? $term_meta['category_attach_id'] : '';
        }else{

            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>
            <tr class="form-field">

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'SVG ICON - SVG ONLY!','wprentals-core').' - <a target="_blank" href="https://help.wprentals.org/article/how-to-add-icons-to-features-and-amenities/">'.esc_html__('Video Tutorial','wprentals-core').'</a>
                </label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload SVG','wprentals-core').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr>

                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_features" />


            </tr>
        </tbody>
        </table>';
    }
endif;




add_action( 'property_city_edit_form_fields',   'wpestate_property_city_callback_function', 10, 2);
add_action( 'property_city_add_form_fields',    'wpestate_property_city_callback_add_function', 10, 2 );
add_action( 'created_property_city',            'wpestate_property_city_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_city',             'wpestate_property_city_save_extra_fields_callback', 10, 2);

if( !function_exists('wpestate_property_city_callback_function') ):
    function wpestate_property_city_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals-core').'</label></th>
                <td>
                    <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
                    <p class="description">'.esc_html__( 'Page id for this term','wprentals-core').'</p>
                </td>

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'Featured Image','wprentals-core').'</label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label></th>
                    <td>
                        <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                    </td>
                </tr>



                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_city" />


            </tr>
        </tbody>
        </table>';
    }
endif;



if( !function_exists('wpestate_property_city_callback_add_function') ):
    function wpestate_property_city_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
        <div class="form-field">
        <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals-core').'</label>
            <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
        </div>

        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals-core').'</label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
           <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>

        <div class="form-field">
        <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label>
            <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
        </div>
        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_city" />
        ';
    }
endif;

if( !function_exists('wpestate_property_city_save_extra_fields_callback') ):
    function wpestate_property_city_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif;
///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tie area with city
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action( 'property_area_edit_form_fields',   'wpestate_property_area_callback_function', 10, 2);
add_action( 'property_area_add_form_fields',    'wpestate_property_area_callback_add_function', 10, 2 );
add_action( 'created_property_area',            'wpestate_property_area_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_area',             'wpestate_property_area_save_extra_fields_callback', 10, 2);
add_filter('manage_edit-property_area_columns', 'ST4_columns_head');
add_filter('manage_property_area_custom_column','ST4_columns_content_taxonomy', 10, 3);




if( !function_exists('ST4_columns_head') ):
    function ST4_columns_head($new_columns) {
        $new_columns = array(
            'cb'            => '<input type="checkbox" />',
            'name'          => esc_html__( 'Name','wprentals-core'),

            'city'          => esc_html__( 'City','wprentals-core'),
            'header_icon'   => '',
            'slug'          => esc_html__( 'Slug','wprentals-core'),
            'posts'         => esc_html__( 'Posts','wprentals-core'),
            'id'            => __('ID','wprentals-core'),
            );
        return $new_columns;
    }
endif; // end   ST4_columns_head


if( !function_exists('ST4_columns_content_taxonomy') ):
    function ST4_columns_content_taxonomy($out, $column_name, $term_id) {
        if ($column_name == 'city') {
            $term_meta= get_option( "taxonomy_$term_id");
            print $term_meta['cityparent'] ;
        }
        if ($column_name == 'id') {
            print $term_id;
        }
    }
endif; // end   ST4_columns_content_taxonomy




if( !function_exists('wpestate_property_area_callback_add_function') ):
    function wpestate_property_area_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $cityparent                 =   $term_meta['cityparent'] ? $term_meta['cityparent'] : '';
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';

            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $cityparent                 =   wpestate_get_all_cities();
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
            <div class="form-field">
            <label for="term_meta[cityparent]">'. esc_html__( 'Which city has this area','wprentals-core').'</label>
                <select name="term_meta[cityparent]" class="postform">
                    '.$cityparent.'
                </select>
            </div>
            ';

         print'
            <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals-core').'</label>
                <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
            </div>

            <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals-core').'</label>
                <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
                <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

            </div>


            <div class="form-field">
            <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label>
                <input id="category_featured_image" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
            </div>
            <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_area" />
            ';
    }
endif; // end




if( !function_exists('wpestate_property_area_callback_function') ):
    function wpestate_property_area_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $cityparent                 =   $term_meta['cityparent'] ? $term_meta['cityparent'] : '';
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   '';
            if(isset( $term_meta['category_featured_image'])){
                $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            }
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';

            $cityparent =   wpestate_get_all_cities($cityparent);
        }else{
            $cityparent                 =   wpestate_get_all_cities();
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
            <table class="form-table">
            <tbody>
                    <tr class="form-field">
                            <th scope="row" valign="top"><label for="term_meta[cityparent]">'. esc_html__( 'Which city has this area','wprentals-core').'</label></th>
                            <td>
                                <select name="term_meta[cityparent]" class="postform">
                                 '.$cityparent.'
                                    </select>
                                <p class="description">'.esc_html__( 'City that has this area','wprentals-core').'</p>
                            </td>
                    </tr>

                   <tr class="form-field">
                            <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals-core').'</label></th>
                            <td>
                                <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
                                <p class="description">'.esc_html__( 'Page id for this term','wprentals-core').'</p>
                            </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="logo_image">'.esc_html__( 'Featured Image','wprentals-core').'</label></th>
                        <td>
                            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
                            <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label></th>
                        <td>
                          <input id="category_featured_image" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                        </td>
                    </tr>


                    <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_area" />




              </tbody>
             </table>';
    }
endif; // end



if( !function_exists('wpestate_get_all_cities') ):
    function wpestate_get_all_cities($selected=''){
        $taxonomy       =   'property_city';
        $args = array(
            'hide_empty'    => false
        );
        $tax_terms      =   get_terms($taxonomy,$args);
        $select_city    =   '';

        foreach ($tax_terms as $tax_term) {
            $select_city.= '<option value="' . $tax_term->name.'" ';
            if($tax_term->name == $selected){
                $select_city.= ' selected="selected" ';
            }
            $select_city.= ' >' . $tax_term->name . '</option>';
        }
        return $select_city;
    }
endif; // end   wpestate_get_all_cities




if( !function_exists('wpestate_property_area_save_extra_fields_callback') ):
    function wpestate_property_area_save_extra_fields_callback($term_id ){
          if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end


add_action( 'init', 'wpestate_my_custom_post_status' );
if( !function_exists('wpestate_my_custom_post_status') ):
    function wpestate_my_custom_post_status(){
        register_post_status( 'expired', array(
                'label'                     => esc_html__(  'expired', 'wprentals-core' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Membership Expired <span class="count">(%s)</span>', 'Membership Expired <span class="count">(%s)</span>','wprentals-core' ),
        ) );

        register_post_status( 'disabled', array(
                    'label'                     => esc_html__(  'disabled', 'wprentals-core' ),
                    'public'                    => false,
                    'exclude_from_search'       => false,
                    'show_in_admin_all_list'    => true,
                    'show_in_admin_status_list' => true,
                    'label_count'               => _n_noop( 'Disabled by user <span class="count">(%s)</span>', 'Disabled by user <span class="count">(%s)</span>','wprentals-core' ),
            ) );

    }
endif; // end   wpestate_my_custom_post_status







///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tie area with city
// property_category
//property_action_category
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action('property_category_edit_form_fields',   'wpestate_property_category_callback_function', 10, 2);
add_action('property_category_add_form_fields',    'wpestate_property_category_callback_add_function', 10, 2 );
add_action('created_property_category',            'wpestate_property_category_save_extra_fields_callback', 10, 2);
add_action('edited_property_category',             'wpestate_property_category_save_extra_fields_callback', 10, 2);



if( !function_exists('wpestate_property_category_callback_function') ):
    function wpestate_property_category_callback_function($tag){
       if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals-core').'</label></th>
                <td>
                    <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
                    <p class="description">'.esc_html__( 'Page id for this term','wprentals-core').'</p>
                </td>

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'Featured Image','wprentals-core').'</label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label></th>
                    <td>
                        <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                    </td>
                </tr>



                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_category" />


            </tr>
        </tbody>
        </table>';
    }
endif; // end


if( !function_exists('wpestate_property_category_callback_add_function') ):
    function wpestate_property_category_callback_add_function($tag){
         if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
        <div class="form-field">
        <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals-core').'</label>
            <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
        </div>

        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals-core').'</label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
           <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>

        <div class="form-field">
        <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label>
            <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
        </div>
        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_category" />
        ';
    }
endif; // end


if( !function_exists('wpestate_property_category_save_extra_fields_callback') ):
    function wpestate_property_category_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end


add_action( 'property_action_category_edit_form_fields',   'wpestate_property_action_category_callback_function', 10, 2);
add_action( 'property_action_category_add_form_fields',    'wpestate_property_action_category_callback_add_function', 10, 2 );
add_action( 'created_property_action_category',            'wpestate_property_action_category_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_action_category',             'wpestate_property_action_category_save_extra_fields_callback', 10, 2);



if( !function_exists('wpestate_property_action_category_callback_function') ):
    function wpestate_property_action_category_callback_function($tag){
       if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals-core').'</label></th>
                <td>
                    <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
                    <p class="description">'.esc_html__( 'Page id for this term','wprentals-core').'</p>
                </td>

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'Featured Image','wprentals-core').'</label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label></th>
                    <td>
                        <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                    </td>
                </tr>



                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_action_category" />


            </tr>
        </tbody>
        </table>';
    }
endif; // end


if( !function_exists('wpestate_property_action_category_callback_add_function') ):
    function wpestate_property_action_category_callback_add_function($tag){
       if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
        <div class="form-field">
        <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals-core').'</label>
            <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">
        </div>

        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals-core').'</label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
           <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>

        <div class="form-field">
        <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals-core').'</label>
            <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
        </div>
        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_action_category" />
        ';

    }
endif; // end


if( !function_exists('wpestate_property_action_category_save_extra_fields_callback') ):
    function wpestate_property_action_category_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end




if( !function_exists('wpestate_return_country_list_translated') ):
function wpestate_return_country_list_translated($selected='') {
    $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals-core'),
                            'Albania'               => esc_html__('Albania','wprentals-core'),
                            'Algeria'               => esc_html__('Algeria','wprentals-core'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals-core'),
                            'Andorra'               => esc_html__('Andorra','wprentals-core'),
                            'Angola'                => esc_html__('Angola','wprentals-core'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals-core'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals-core'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals-core'),
                            'Argentina'             => esc_html__('Argentina','wprentals-core'),
                            'Armenia'               => esc_html__('Armenia','wprentals-core'),
                            'Aruba'                 => esc_html__('Aruba','wprentals-core'),
                            'Australia'             => esc_html__('Australia','wprentals-core'),
                            'Austria'               => esc_html__('Austria','wprentals-core'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals-core'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals-core'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals-core'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals-core'),
                            'Barbados'              => esc_html__('Barbados','wprentals-core'),
                            'Belarus'               => esc_html__('Belarus','wprentals-core'),
                            'Belgium'               => esc_html__('Belgium','wprentals-core'),
                            'Belize'                => esc_html__('Belize','wprentals-core'),
                            'Benin'                 => esc_html__('Benin','wprentals-core'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals-core'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals-core'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals-core'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals-core'),
                            'Botswana'              => esc_html__('Botswana','wprentals-core'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals-core'),
                            'Brazil'                => esc_html__('Brazil','wprentals-core'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals-core'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals-core'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals-core'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals-core'),
                            'Burundi'               => esc_html__('Burundi','wprentals-core'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals-core'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals-core'),
                            'Canada'                => esc_html__('Canada','wprentals-core'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals-core'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals-core'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals-core'),
                            'Chad'                  => esc_html__('Chad','wprentals-core'),
                            'Chile'                 => esc_html__('Chile','wprentals-core'),
                            'China'                 => esc_html__('China','wprentals-core'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals-core'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals-core'),
                            'Colombia'              => esc_html__('Colombia','wprentals-core'),
                            'Comoros'               => esc_html__('Comoros','wprentals-core'),
                            'Congo'                 => esc_html__('Congo','wprentals-core'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals-core'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals-core'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals-core'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals-core'),
                            'Croatia (Hrvatska)'    => esc_html__('Croatia (Hrvatska)','wprentals-core'),
                            'Cuba'                  => esc_html__('Cuba','wprentals-core'),
                            'Curacao'               => esc_html__('Curacao','wprentals-core'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals-core'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals-core'),
                            'Denmark'               => esc_html__('Denmark','wprentals-core'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals-core'),
                            'Dominica'              => esc_html__('Dominica','wprentals-core'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals-core'),
                            'East Timor'            => esc_html__('East Timor','wprentals-core'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals-core'),
                            'Egypt'                 => esc_html__('Egypt','wprentals-core'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals-core'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals-core'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals-core'),
                            'Estonia'               => esc_html__('Estonia','wprentals-core'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals-core'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals-core'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals-core'),
                            'Fiji'                  => esc_html__('Fiji','wprentals-core'),
                            'Finland'               => esc_html__('Finland','wprentals-core'),
                            'France'                => esc_html__('France','wprentals-core'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals-core'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals-core'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals-core'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals-core'),
                            'Gabon'                 => esc_html__('Gabon','wprentals-core'),
                            'Gambia'                => esc_html__('Gambia','wprentals-core'),
                            'Georgia'               => esc_html__('Georgia','wprentals-core'),
                            'Germany'               => esc_html__('Germany','wprentals-core'),
                            'Ghana'                 => esc_html__('Ghana','wprentals-core'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals-core'),
                            'Greece'                => esc_html__('Greece','wprentals-core'),
                            'Greenland'             => esc_html__('Greenland','wprentals-core'),
                            'Grenada'               => esc_html__('Grenada','wprentals-core'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals-core'),
                            'Guam'                  => esc_html__('Guam','wprentals-core'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals-core'),
                            'Guinea'                => esc_html__('Guinea','wprentals-core'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals-core'),
                            'Guyana'                => esc_html__('Guyana','wprentals-core'),
                            'Haiti'                 => esc_html__('Haiti','wprentals-core'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals-core'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals-core'),
                            'Honduras'              => esc_html__('Honduras','wprentals-core'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals-core'),
                            'Hungary'               => esc_html__('Hungary','wprentals-core'),
                            'Iceland'               => esc_html__('Iceland','wprentals-core'),
                            'India'                 => esc_html__('India','wprentals-core'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals-core'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals-core'),
                            'Iraq'                  => esc_html__('Iraq','wprentals-core'),
                            'Ireland'               => esc_html__('Ireland','wprentals-core'),
                            'Israel'                => esc_html__('Israel','wprentals-core'),
                            'Italy'                 => esc_html__('Italy','wprentals-core'),
                            'Island of Saba'        => esc_html__('Island of Saba','wprentals-core'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals-core'),
                            'Japan'                 => esc_html__('Japan','wprentals-core'),
                            'Jordan'                => esc_html__('Jordan','wprentals-core'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals-core'),
                            'Kenya'                 => esc_html__('Kenya','wprentals-core'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals-core'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals-core'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals-core'),
                            'Kosovo'                => esc_html__('Kosovo', 'wprentals-core'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals-core'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals-core'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals-core'),
                            'Latvia'                => esc_html__('Latvia','wprentals-core'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals-core'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals-core'),
                            'Liberia'               => esc_html__('Liberia','wprentals-core'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals-core'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals-core'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals-core'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals-core'),
                            'Macau'                 => esc_html__('Macau','wprentals-core'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals-core'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals-core'),
                            'Malawi'                => esc_html__('Malawi','wprentals-core'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals-core'),
                            'Maldives'              => esc_html__('Maldives','wprentals-core'),
                            'Mali'                  => esc_html__('Mali','wprentals-core'),
                            'Malta'                 => esc_html__('Malta','wprentals-core'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals-core'),
                            'Martinique'            => esc_html__('Martinique','wprentals-core'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals-core'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals-core'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals-core'),
                            'Mexico'                => esc_html__('Mexico','wprentals-core'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals-core'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals-core'),
                            'Monaco'                => esc_html__('Monaco','wprentals-core'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals-core'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals-core'),
                            'Morocco'               => esc_html__('Morocco','wprentals-core'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals-core'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals-core'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals-core'),
                            'Namibia'               => esc_html__('Namibia','wprentals-core'),
                            'Nauru'                 => esc_html__('Nauru','wprentals-core'),
                            'Nepal'                 => esc_html__('Nepal','wprentals-core'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals-core'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals-core'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals-core'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals-core'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals-core'),
                            'Niger'                 => esc_html__('Niger','wprentals-core'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals-core'),
                            'Niue'                  => esc_html__('Niue','wprentals-core'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals-core'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals-core'),
                            'Norway'                => esc_html__('Norway','wprentals-core'),
                            'Oman'                  => esc_html__('Oman','wprentals-core'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals-core'),
                            'Palau'                 => esc_html__('Palau','wprentals-core'),
                            'Panama'                => esc_html__('Panama','wprentals-core'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals-core'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals-core'),
                            'Peru'                  => esc_html__('Peru','wprentals-core'),
                            'Philippines'           => esc_html__('Philippines','wprentals-core'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals-core'),
                            'Poland'                => esc_html__('Poland','wprentals-core'),
                            'Portugal'              => esc_html__('Portugal','wprentals-core'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals-core'),
                            'Qatar'                 => esc_html__('Qatar','wprentals-core'),
                            'Reunion'               => esc_html__('Reunion','wprentals-core'),
                            'Romania'               => esc_html__('Romania','wprentals-core'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals-core'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals-core'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals-core'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals-core'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals-core'),
                            'Samoa'                 => esc_html__('Samoa','wprentals-core'),
                            'San Marino'            => esc_html__('San Marino','wprentals-core'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals-core'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals-core'),
                            'Serbia'                => esc_html__('Serbia','wprentals-core'),
                            'Senegal'               => esc_html__('Senegal','wprentals-core'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals-core'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals-core'),
                            'Singapore'             => esc_html__('Singapore','wprentals-core'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals-core'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals-core'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals-core'),
                            'Somalia'               => esc_html__('Somalia','wprentals-core'),
                            'South Africa'          => esc_html__('South Africa','wprentals-core'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals-core'),
                            'Spain'                 => esc_html__('Spain','wprentals-core'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals-core'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals-core'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals-core'),
                            'Sudan'                 => esc_html__('Sudan','wprentals-core'),
                            'Suriname'              => esc_html__('Suriname','wprentals-core'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals-core'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals-core'),
                            'Sweden'                => esc_html__('Sweden','wprentals-core'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals-core'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals-core'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals-core'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals-core'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals-core'),
                            'Thailand'              => esc_html__('Thailand','wprentals-core'),
                            'Togo'                  => esc_html__('Togo','wprentals-core'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals-core'),
                            'Tonga'                 => esc_html__('Tonga','wprentals-core'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals-core'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals-core'),
                            'Turkey'                => esc_html__('Turkey','wprentals-core'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals-core'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals-core'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals-core'),
                            'Uganda'                => esc_html__('Uganda','wprentals-core'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals-core'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals-core'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals-core'),
                            'United States'         => esc_html__('United States','wprentals-core'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals-core'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals-core'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals-core'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals-core'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals-core'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals-core'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals-core'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals-core'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals-core'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals-core'),
                            'Yemen'                 => esc_html__('Yemen','wprentals-core'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals-core'),
                            'Zambia'                => esc_html__('Zambia','wprentals-core'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals-core')
        );
    if($selected!=''){
        $countries= array_change_key_case($countries, CASE_LOWER);
        if ( isset( $countries[$selected]) ) {
            return $countries[$selected];
        }
    }else{
        return $countries;
    }
}
endif;

if( !function_exists('wpestate_show_custom_field')):
    function wpestate_show_custom_field( $show,$slug,$name,$label,$type,$order,$dropdown_values,$post_id,$value=''){

        // get value
        if($value ==''){
            $value          =   esc_html(get_post_meta($post_id, $slug, true));
            if( $type == 'numeric'  ){

                $value          =   (get_post_meta($post_id, $slug, true));
                if($value!==''){
                   $value =  floatval ($value);
                }


            }else{
                $value          =   esc_html(get_post_meta($post_id, $slug, true));
            }

        }


        $template='';
        if ( $type =='long text' ){
            $template.= '<label for="'.$slug.'">'.$label.' '.__('(*text)','wprentals-core').' </label>';
            $template.= '<textarea type="text" class="form-control" id="'.$slug.'"  size="0" name="'.$slug.'" rows="3" cols="42">' .$value. '</textarea>';
        }else if( $type =='short text' ){
            $template.=  '<label for="'.$slug.'">'.$label.' '.__('(*text)','wprentals-core').' </label>';
            $template.=  '<input type="text" class="form-control" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $value . '">';
        }else if( $type =='numeric'  ){
            $template.=  '<label for="'.$slug.'">'.$label.' '.__('(*numeric)','wprentals-core').' </label>';
            $template.=  '<input type="text" class="form-control" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $value . '">';
        }else if( $type =='date' ){
            $template.=  '<label for="'.$slug.'">'.$label.' '.__('(*date)','wprentals-core').' </label>';
            $template.=  '<input type="text" class="form-control" id="'.$slug.'" size="40" name="'.$slug.'" value="' .$value . '">';
            $template.= wpestate_date_picker_translation_return($slug);
        }else if( $type =='dropdown' ){
            $dropdown_values_array=explode(',',$dropdown_values);

            $template.= '<label for="'.$slug.'">'.$label.' </label>';
            $template.= '<select id="'.$slug.'"  name="'.$slug.'" >';
            $template.= '<option value="">'.esc_html__('Not Available','wprentals-core').'</option>';
            foreach($dropdown_values_array as $key=>$value_drop){
                $value_drop= stripslashes($value_drop);

                $template.= '<option value="'.trim($value_drop).'"';
                if( trim( html_entity_decode($value,ENT_QUOTES) ) == trim( html_entity_decode ($value_drop,ENT_QUOTES) ) ){

                    $template.=' selected ';
                }
                if (function_exists('icl_translate') ){
                    $value_drop = apply_filters('wpml_translate_single_string', $value_drop,'custom field value','custom_field_value'.$value_drop );
                }


                $template.= '>'.trim($value_drop).'</option>';
            }
            $template.= '</select>';
        }

        if($show==1){
            print $template;
        }else{
            return $template;
        }

    }
endif;


if( !function_exists('wpestate_property_add_media') ):
function wpestate_property_add_media() {


global $post;

$arguments      = array(
    'numberposts' => -1,
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'post_parent' => $post->ID,
    'post_status' => null,
    'exclude' => get_post_thumbnail_id(),
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'orderby' => 'menu_order',
    'order' => 'ASC'
    );

$already_in='';
$post_attachments   = get_posts($arguments);

print '<div class="property_uploaded_thumb_wrapepr" id="property_uploaded_thumb_wrapepr">';
$ajax_nonce = wp_create_nonce( "wpestate_attach_delete" );
print'<input type="hidden" id="wpestate_attach_delete" value="'.esc_html($ajax_nonce).'" />    ';
foreach ($post_attachments as $attachment) {

    $already_in         =   $already_in.$attachment->ID.',';
    $preview            =   wp_get_attachment_image_src($attachment->ID, 'thumbnail');
    print '<div class="uploaded_thumb" data-imageid="'.$attachment->ID.'">
        <img  src="'.$preview[0].'"  alt="slider" />
        <a target="_blank" href="'.esc_url(admin_url() ) .'post.php?post='.$attachment->ID.'&action=edit" class="attach_edit"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
        <span class="attach_delete"><i class="fas fa-trash" aria-hidden="true"></i></span>
    </div>';
}

print '<input type="hidden" id="image_to_attach" name="image_to_attach" value="'.$already_in.'"/>';
$ajax_nonce = wp_create_nonce( "wpestate_image_upload" );
print'<input type="hidden" id="wpestate_image_upload" value="'.esc_html($ajax_nonce).'" />    ';

print '</div>';

print '<button class="upload_button button" id="button_new_image" data-postid="'.$post->ID.'">'.esc_html__('Upload new Image','wprentals-core').'</button>';


    $mypost = $post->ID;
    $option_video='';
    $video_values = array('vimeo', 'youtube');
    $video_type = get_post_meta($mypost, 'embed_video_type', true);
    $property_custom_video= get_post_meta($mypost, 'property_custom_video', true);

    foreach ($video_values as $value) {
        $option_video.='<option value="' . $value . '"';
        if ($value == $video_type) {
            $option_video.='selected="selected"';
        }
        $option_video.='>' . $value . '</option>';
    }




    print'
    <div class="property_prop_half" style="clear: both;">
        <label for="embed_video_id">'.esc_html__('Video From: ','wprentals-core').'</label> <br />
         <select id="embed_video_type" name="embed_video_type" >
                ' . $option_video . '
        </select>
    </div>


    <div class="property_prop_half">
        <label for="embed_video_id">'.esc_html__('Embed Video id: ','wprentals-core').'</label> <br />
        <input type="text" id="embed_video_id" name="embed_video_id" size="40" value="'.esc_html( get_post_meta($mypost, 'embed_video_id', true) ).'">
    </div>';




    print'
    <div class="property_prop_half">
        <label for="embed_video_type">'.esc_html__('Virtual Tour ','wprentals-core').'</label><br />
        <textarea id="virtual_tour" name="virtual_tour">'.( get_post_meta($mypost, 'virtual_tour', true) ).'</textarea>
    </div>';
}
endif;
?>
