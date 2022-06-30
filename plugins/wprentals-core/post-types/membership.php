<?php
// register the custom post type
add_action( 'after_setup_theme', 'wpestate_create_membership_type' ,20);

if( !function_exists('wpestate_create_membership_type') ):
    function wpestate_create_membership_type() {
        register_post_type( 'membership_package',
                    array(
                            'labels' => array(
                                    'name'          => esc_html__(  'Membership Packages','wprentals-core'),
                                    'singular_name' => esc_html__(  'Membership Packages','wprentals-core'),
                                    'add_new'       => esc_html__( 'Add New Membership Package','wprentals-core'),
                    'add_new_item'          =>  esc_html__( 'Add Membership Packages','wprentals-core'),
                    'edit'                  =>  esc_html__( 'Edit Membership Packages' ,'wprentals-core'),
                    'edit_item'             =>  esc_html__( 'Edit Membership Package','wprentals-core'),
                    'new_item'              =>  esc_html__( 'New Membership Packages','wprentals-core'),
                    'view'                  =>  esc_html__( 'View Membership Packages','wprentals-core'),
                    'view_item'             =>  esc_html__( 'View Membership Packages','wprentals-core'),
                    'search_items'          =>  esc_html__( 'Search Membership Packages','wprentals-core'),
                    'not_found'             =>  esc_html__( 'No Membership Packages found','wprentals-core'),
                    'not_found_in_trash'    =>  esc_html__( 'No Membership Packages found','wprentals-core'),
                    'parent'                =>  esc_html__( 'Parent Membership Package','wprentals-core')
                            ),
                    'public' => false,
                    'show_ui'=>true,
                    'show_in_nav_menus'=>true,
                    'show_in_menu'=>true,
                    'show_in_admin_bar'=>true,
                    'has_archive' => true,
                    'rewrite' => array('slug' => 'package'),
                    'supports' => array('title','thumbnail'),
                    'can_export' => true,
                    'register_meta_box_cb' => 'wpestate_add_pack_metaboxes',
                    'menu_icon'=> WPESTATE_PLUGIN_DIR_URL.'/img/membership.png'
                    )
            );
    }
endif; // end   wpestate_create_membership_type




/////////////////////////////////////////////////////////////////////////////////////
// custom options for property
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_pack_metaboxes') ):
    function wpestate_add_pack_metaboxes() {
        add_meta_box(  'estate_membership-sectionid',  esc_html__(  'Package Details', 'wprentals-core' ),'membership_package','membership_package' ,'normal','default'
    );
}
endif; // end   wpestate_add_pack_metaboxes


if( !function_exists('membership_package') ):
    function membership_package( $post ) {
	    wp_nonce_field( plugin_basename( __FILE__ ), 'estate_pack_noncename' );
	    global $post;
                $unlimited_days     =   esc_html(get_post_meta($post->ID, 'mem_days_unl', true));
                $unlimited_lists    =   esc_html(get_post_meta($post->ID, 'mem_list_unl', true));
                $billing_periods    =   array('Day','Week','Month','Year');

                $billng_saved       =   esc_html(get_post_meta($post->ID, 'biling_period', true));
                $billing_select     =   '<select name="biling_period" width="200px" id="billing_period">';
                foreach($billing_periods as $period){
                    $billing_select.='<option value="'.$period.'" ';
                    if($billng_saved==$period){
                         $billing_select.=' selected="selected" ';
                    }
                    $billing_select.='>'.$period.'</option>';
                }
                $billing_select.='</select>';

                $check_unlimited_lists='';
                if($unlimited_lists==1){
                    $check_unlimited_lists=' checked="checked"  ';
                }


                $visible_array=array('yes','no');
                $visible_saved=get_post_meta($post->ID, 'pack_visible', true);
                $visible_select='<select id="pack_visible" name="pack_visible">';

                foreach($visible_array as $option){
                    $visible_select.='<option value="'.$option.'" ';
                    if($visible_saved==$option){
                        $visible_select.=' selected="selected" ';
                    }
                    $visible_select.='>'.$option.'</option>';
                }
                $visible_select.='</select>';




                print'
                <p class="meta-options">
                    <label for="biling_period">'.esc_html__( 'Billing Time Unit :','wprentals-core').'</label><br />
                    '.$billing_select.'
                </p>

                <p class="meta-options">
                    <label for="billing_freq">'.esc_html__( 'Bill every x units','wprentals-core').' </label><br />
                    <input type="text" id="billing_freq" size="58" name="billing_freq" value="'.  intval(get_post_meta($post->ID, 'billing_freq', true)).'">
                </p>

                <p class="meta-options">
                    <label for="pack_listings">'.esc_html__( 'How many listings are included?','wprentals-core').'</label><br />
                    <input type="text" id="pack_listings" size="58" name="pack_listings" value="'.  esc_html(get_post_meta($post->ID, 'pack_listings', true)).'">

                    <input type="hidden" name="mem_list_unl" value=""/>
                    <input type="checkbox"  id="mem_list_unl" name="mem_list_unl" value="1" '.$check_unlimited_lists.'  />
                    <label for="mem_list_unl">'.esc_html__( 'Unlimited listings ?','wprentals-core').'</label>
                </p>

                <p class="meta-options">
                    <label for="pack_featured_listings">'.esc_html__( 'How many Featured listings are included?','wprentals-core').'</label><br />
                    <input type="text" id="pack_featured_listings" size="58" name="pack_featured_listings" value="'.  esc_html(get_post_meta($post->ID, 'pack_featured_listings', true)).'">
                </p>


                <p class="meta-options">
                    <label for="pack_price">'.esc_html__( 'Package Price in ','wprentals-core'). ' ' . wpestate_curency_submission_pick().'</label><br />
                    <input type="text" id="pack_price" size="58" name="pack_price" value="'.  esc_html(get_post_meta($post->ID, 'pack_price', true)).'">
		</p>

                <p class="meta-options">
                    <label for="pack_visible">'.esc_html__( 'Is visible? ','wprentals-core').'</label><br />
                    '.$visible_select.'
		</p>

                <p class="meta-options">
                    <label for="pack_stripe_id">Package Stripe id (ex:gold-pack) </label><br>
                    <input type="text" id="pack_stripe_id" size="58" name="pack_stripe_id" value="'.esc_html(get_post_meta($post->ID, 'pack_stripe_id', true)).'">
                </p>
         ';
    }
endif; // end   membership_package

////////////////////////////////////////////////////////////////////////////////
/// Get a list of all visible packages
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_all_packs') ):
    function wpestate_get_all_packs(){
        $args = array(
                'post_type'         => 'membership_package',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                                            array(
                                                'key' => 'pack_visible',
                                                'value' => 'yes',
                                                'compare' => '='
                                            )

                 )

         );
        $pack_selection = new WP_Query($args);

        while ($pack_selection->have_posts()): $pack_selection->the_post();
            $return_string.='<option value="'.$post->ID.'">'.get_the_title().'</option>';
        endwhile;
        wp_reset_query();
        return $return_string;
    }
endif; // end   wpestate_get_all_packs


////////////////////////////////////////////////////////////////////////////////
/// Get a package details from user top profile
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_pack_data_for_user_top') ):
    function wpestate_get_pack_data_for_user_top($userID,$user_pack,$user_registered,$user_package_activation){
            print '<div class="pack_description">
                ';
            $remaining_lists=wpestate_get_remain_listing_user($userID,$user_pack);
            if($remaining_lists==-1){
                $remaining_lists=' &#8734';
            }


            if ($user_pack!=''){
                $title              = get_the_title($user_pack);
                $pack_time          = get_post_meta($user_pack, 'pack_time', true);
                $pack_list          = get_post_meta($user_pack, 'pack_listings', true);
                $pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
                $pack_price         = get_post_meta($user_pack, 'pack_price', true);
                $unlimited_lists    = get_post_meta($user_pack, 'mem_list_unl', true);
                $date               = strtotime ( get_user_meta($userID, 'package_activation',true) );
                $biling_period      = get_post_meta($user_pack, 'biling_period', true);
                $billing_freq       = get_post_meta($user_pack, 'billing_freq', true);


                $seconds=0;
                switch ($biling_period){
                   case 'Day':
                       $seconds=60*60*24;
                       break;
                   case 'Week':
                       $seconds=60*60*24*7;
                       break;
                   case 'Month':
                       $seconds=60*60*24*30;
                       break;
                   case 'Year':
                       $seconds=60*60*24*365;
                       break;
               }

               $time_frame      =   $seconds*$billing_freq;
               $expired_date    =   $date+$time_frame;
               $expired_date    =   date('Y-m-d',$expired_date);






                print '<div class="pack-name">'.$title.' <span>'.esc_html__( 'Current Subscription','wprentals-core').'</span></div>';
                $extra_pack_class='';
                if($pack_list>999){
                    $extra_pack_class= ' extrapackclass ';
                }

                print '<div class="pack-info '.$extra_pack_class.'">';


                 if($unlimited_lists==1){
                    print '<div class="normal_list_no">  &#8734 <span>'.esc_html__( 'Listings Included','wprentals-core').'</span></div>';
                    print '<div class="normal_list_no">  &#8734 <span>'.esc_html__( 'Remaining Listings','wprentals-core').'</span></div>';
                }else{
                    print '<div class="normal_list_no">'.$pack_list      .'<span>'.esc_html__( 'Listings Included','wprentals-core').'</span></div>';
                    print '<div class="normal_list_no">'.$remaining_lists.'<span>'.esc_html__( 'Remaining Listings','wprentals-core').'</span></div>';

                }

                print '<div class="normal_list_no">'.$pack_featured.'<span>'.esc_html__( 'Featured included','wprentals-core').'</span></div>';
                print '<div class="normal_list_no extend_normal_list_no">'.wpestate_get_remain_featured_listing_user($userID).'<span>'.esc_html__( 'Featured remaining','wprentals-core').'</span></div>';
                print '<div class="pack-date-wrapper">'.esc_html__( 'Expiration date','wprentals-core').': <span class="pack-date-wrapper-date">'.$expired_date.'</span></div></div>';

            }else{
///////////////////////////
                $free_mem_list      =   esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                $free_feat_list     =   esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
                $free_mem_list_unl  =   intval(wprentals_get_option('wp_estate_free_mem_list_unl', '' ));
                $extra_pack_class='';

                if( intval($free_mem_list)>999 || $free_mem_list_unl==1){

                    $extra_pack_class= ' extrapackclass ';
                }
                print '<div class="pack-name"  >'.esc_html__( 'Free Membership','wprentals-core').'<span>'.esc_html__( 'Current Subscription','wprentals-core').'</span></div>';


                print '<div class="pack-info '.$extra_pack_class.'"><div class="normal_list_no"> ';
                 if($free_mem_list_unl==1){
                    print esc_html__( '-','wprentals-core');
                }else{
                    print $free_mem_list;
                }
                print '<span>'.esc_html__( 'Listings Included','wprentals-core').'</span></div>';
                print '<div class="normal_list_no">'.$remaining_lists.'<span>'.esc_html__( 'Remaining listings','wprentals-core').'</span></div>';
                print '<div class="normal_list_no">'.$free_feat_list.'<span>'.esc_html__( 'Featured listings included','wprentals-core').'</span></div>';
                print '<div class="normal_list_no extend_normal_list_no">'.wpestate_get_remain_featured_listing_user($userID).'<span>'.esc_html__( 'Featured Listings remaining','wprentals-core').'</span></div>';
                print '<div class="pack-date-wrapper">'.esc_html__( 'Expiration date','wprentals-core').' -</div></div>';



            }
            print '</div>';
    }
endif; // end   wpestate_get_pack_data_for_user_top

////////////////////////////////////////////////////////////////////////////////
/// Show billing period
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_show_bill_period') ):
    function wpestate_show_bill_period($biling_period){

            if($biling_period=='Day' || $biling_period=='Days'){
                return  esc_html__( 'days','wprentals-core');
            }
            else if($biling_period=='Week' || $biling_period=='Weeks'){
               return  esc_html__( 'weeks','wprentals-core');
            }
            else if($biling_period=='Month' || $biling_period=='Months'){
                return  esc_html__( 'months','wprentals-core');
            }
            else if($biling_period=='Year'){
                return  esc_html__( 'year','wprentals-core');
            }

    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// Get a package details from user
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_pack_data_for_user') ):
function wpestate_get_pack_data_for_user($userID,$user_pack,$user_registered,$user_package_activation){

            if ($user_pack!=''){
                $title              = get_the_title($user_pack);
                $pack_time          = get_post_meta($user_pack, 'pack_time', true);
                $pack_list          = get_post_meta($user_pack, 'pack_listings', true);
                $pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
                $pack_price         = get_post_meta($user_pack, 'pack_price', true);

                $unlimited_lists    = get_post_meta($user_pack, 'mem_list_unl', true);

                print '<strong>'.esc_html__( 'Your Current Package: ','wprentals-core').'</strong></br><strong>'.$title.'</strong></br> ';
                print '<p class="full_form-nob">';
                if($unlimited_lists==1){
                    print esc_html__( '  Unlimited listings','wprentals-core');
                }else{
                    print $pack_list.esc_html__( ' Listings','wprentals-core');
                    print ' - '.wpestate_get_remain_listing_user($userID,$user_pack).esc_html__( ' remaining ','wprentals-core').'</p>';
                }

                print ' <p class="full_form-nob"> <span id="normal_list_no">'.$pack_featured.esc_html__( ' Featured listings','wprentals-core').'</span>';
                print ' - <span id="featured_list_no">'.wpestate_get_remain_featured_listing_user($userID).'</span>'.esc_html__( ' remaining','wprentals-core').' </p>';


            }else{

                $free_mem_list      =   esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                $free_feat_list     =   esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
                $free_mem_list_unl  =   wprentals_get_option('wp_estate_free_mem_list_unl', '' );
                print '<strong>'.esc_html__( 'Your Current Package: ','wprentals-core').'</strong></br><strong>'.esc_html__( 'Free Membership','wprentals-core').'</strong>';
                print '<p class="full_form-nob">';
                if($free_mem_list_unl==1){
                     print esc_html__( 'Unlimited listings','wprentals-core');
                }else{
                     print $free_mem_list.esc_html__( ' Listings','wprentals-core');
                     print ' - <span id="normal_list_no">'.wpestate_get_remain_listing_user($userID,$user_pack).'</span>'.esc_html__( ' remaining','wprentals-core').'</p>';

                }
                print '<p class="full_form-nob">';
                print $free_feat_list.esc_html__( ' Featured listings','wprentals-core');
                print ' - <span id="featured_list_no">'.wpestate_get_remain_featured_listing_user($userID).'</span>'.esc_html__( '  remaining','wprentals-core').' </p>';
            }

}
endif; // end   wpestate_get_pack_data_for_user




if( !function_exists('wpestate_get_remain_days_user') ):
function wpestate_get_remain_days_user($userID,$user_pack,$user_registered,$user_package_activation){

    if ($user_pack!=''){
        $pack_time  = get_post_meta($user_pack, 'pack_time', true);
        $now        = time();

        $user_date  = strtotime($user_package_activation);
        $datediff   = $now - $user_date;
        if( floor($datediff/(60*60*24)) > $pack_time){
            return 0;
        }else{
            return floor($pack_time-$datediff/(60*60*24));
        }


    }else{
        $free_mem_days      = esc_html( wprentals_get_option('wp_estate_free_mem_days','') );
        $free_mem_days_unl  = wprentals_get_option('wp_estate_free_mem_days_unl', '');
        if($free_mem_days_unl==1){
            return;
        }else{
             $now = time();
             $user_date = strtotime($user_registered);
             $datediff = $now - $user_date;
             if(  floor($datediff/(60*60*24)) >$free_mem_days){
                 return 0;
             }else{
                return floor($free_mem_days-$datediff/(60*60*24));
             }
        }
    }
}
endif; // end   wpestate_get_remain_days_user





if( !function_exists('wpestate_get_remain_listing_user') ):
    function wpestate_get_remain_listing_user($userID,$user_pack){
        if ( $user_pack !='' ){
          $current_listings   = wpestate_get_current_user_listings($userID);
          $pack_listings      = get_post_meta($user_pack, 'pack_listings', true);

           return $current_listings;
        }else{
          $free_mem_list      = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
          $free_mem_list_unl  = wprentals_get_option('wp_estate_free_mem_list_unl', '' );
          if($free_mem_list_unl==1){
                return -1;
          }else{
              $current_listings=wpestate_get_current_user_listings($userID);
              return $current_listings;
          }
        }
    }
endif; // end   wpestate_get_remain_listing_user



///////////////////////////////////////////////////////////////////////////////////////////
// return no of featuerd listings available for current pack
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_remain_featured_listing_user') ):
    function wpestate_get_remain_featured_listing_user($userID){
        $count  =   get_the_author_meta( 'package_featured_listings' , $userID );
        return $count;
    }
endif; // end   wpestate_get_remain_featured_listing_user




///////////////////////////////////////////////////////////////////////////////////////////
// return no of listings available for current pack
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_current_user_listings') ):
    function wpestate_get_current_user_listings($userID){
        $count  =   get_the_author_meta( 'package_listings' , $userID );
        return $count;
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// update listing no
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_listing_no') ):
    function wpestate_update_listing_no($userID){
        $current  =   get_the_author_meta( 'package_listings' , $userID );
        if($current==''){
            //do nothing
        }else if($current==-1){ // if unlimited
            //do noting
        }else if($current-1>=0){
            update_user_meta( $userID, 'package_listings', $current-1) ;
        }else if( $current==0 ){
             update_user_meta( $userID, 'package_listings', 0) ;
        }
    }
endif; // end   wpestate_update_listing_no



///////////////////////////////////////////////////////////////////////////////////////////
// update featured listing no
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_featured_listing_no') ):
    function wpestate_update_featured_listing_no($userID){
        $current  =   get_the_author_meta( 'package_featured_listings' , $userID );

        if($current-1>=0){
            update_user_meta( $userID, 'package_featured_listings', $current-1) ;
        }else{
              update_user_meta( $userID, 'package_featured_listings', 0) ;
        }
    }
endif; // end   wpestate_update_featured_listing_no



///////////////////////////////////////////////////////////////////////////////////////////
// update old users that don;t have membership details
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_old_users') ):
    function wpestate_update_old_users($userID){
        $paid_submission_status    = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
        if($paid_submission_status == 'membership' ){

            $curent_list   =   get_user_meta( $userID, 'package_listings', true) ;
            $cur_feat_list =   get_user_meta( $userID, 'package_featured_listings', true) ;

                if($curent_list=='' || $cur_feat_list=='' ){
                     $package_listings           = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                     $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
                       if($package_listings==''){
                           $package_listings=0;
                       }
                       if($featured_package_listings==''){
                          $featured_package_listings=0;
                       }

                     update_user_meta( $userID, 'package_listings', $package_listings) ;
                     update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;

                   $time = time();
                   $date = date('Y-m-d H:i:s',$time);
                   update_user_meta( $userID, 'package_activation', $date);
                }

        }// end if memebeship
    }
endif; // end   wpestate_update_old_users




///////////////////////////////////////////////////////////////////////////////////////////
// update user profile on register
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_profile') ):
    function wpestate_update_profile($userID){
        if(1==1){ // if membership is on

            if( wprentals_get_option('wp_estate_free_mem_list_unl', '' ) ==1 ){
                $package_listings =-1;
                $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
            }else{
                $package_listings           = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );

                if($package_listings==''){
                    $package_listings=0;
                }
                if($featured_package_listings==''){
                    $featured_package_listings=0;
                }
            }
            update_user_meta( $userID, 'package_listings', $package_listings) ;
            update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;
            $time = time();
            $date = date('Y-m-d H:i:s',$time);
            update_user_meta( $userID, 'package_activation', $date);
            //package_id no id since the pack is free

        }

    }
endif; // end   wpestate_update_profile





///////////////////////////////////////////////////////////////////////////////////////////
// update user profile on register
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_display_packages') ):
    function wpestate_display_packages(){
        global $post;
        $args = array(
                        'post_type'     => 'membership_package',
                        'posts_per_page'=> -1,
                        'meta_query'    => array(
                                                array(
                                                    'key' => 'pack_visible',
                                                    'value' => 'yes',
                                                    'compare' => '=',
                                                )
                                            )
        );
        $pack_selection = new WP_Query($args);

        $return='<select name="pack_select" id="pack_select" class="select-submit2"><option value="">'.esc_html__( 'Select package','wprentals-core').'</option>';
        while($pack_selection->have_posts() ){

            $pack_selection->the_post();
            $title=get_the_title();
            $return.='<option value="'.$post->ID.'"  data-price="'.get_post_meta(get_the_id(),'pack_price',true).'" data-pick="'.sanitize_title($title).'" >'.$title.'</option>';
        }
        $return.='</select>';

        print $return;

    }
endif; // end   wpestate_display_packages


/////////////////////////////////////////////////////////////////////////////////////
/// downgrade to pack
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_downgrade_to_pack') ):
    function wpestate_downgrade_to_pack( $user_id, $pack_id ){

        $future_listings                  =   get_post_meta($pack_id, 'pack_listings', true);
        $future_featured_listings         =   get_post_meta($pack_id, 'pack_featured_listings', true);
        update_user_meta( $user_id, 'package_listings', $future_listings) ;
        update_user_meta( $user_id, 'package_featured_listings', $future_featured_listings);

        $args = array(
                   'post_type' => 'estate_property',
                   'author'    => $user_id,
                   'post_status'   => 'any'
            );

        $query = new WP_Query( $args );
        global $post;
        while( $query->have_posts()){
                $query->the_post();

                $prop = array(
                        'ID'            => $post->ID,
                        'post_type'     => 'estate_property',
                        'post_status'   => 'expired'
                );

                wp_update_post($prop );
                update_post_meta($post->ID, 'prop_featured', 0);
          }
        wp_reset_query();

        $user = get_user_by('id',$user_id);
        $user_email=$user->user_email;

        $arguments=array();
        wpestate_select_email_type($user_email,'account_downgraded',$arguments);


    }
endif; // end   wpestate_downgrade_to_pack



/////////////////////////////////////////////////////////////////////////////////////
/// downgrade to free
/////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_downgrade_to_free') ):
    function wpestate_downgrade_to_free($user_id){
        global $post;

        $free_pack_listings        = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
        $free_pack_feat_listings   = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );

        update_user_meta( $user_id, 'package_id', '') ;
        update_user_meta( $user_id, 'package_listings', $free_pack_listings) ;
        update_user_meta( $user_id, 'package_featured_listings', $free_pack_feat_listings);
        update_user_meta(  $user_id  , 'stripe'                ,  '' );
        update_user_meta( $user_id  , 'stripe_subscription_id',  '' );
        $args = array(
                'post_type' => 'estate_property',
                'author'    => $user_id,
                'post_status'   => 'any'
         );

        $query = new WP_Query( $args );
        while( $query->have_posts()){
                $query->the_post();

                $prop = array(
                        'ID'            => $post->ID,
                        'post_type'     => 'estate_property',
                        'post_status'   => 'expired'
                );

                wp_update_post($prop );
          }
        wp_reset_query();

        $user = get_user_by('id',$user_id);
        $user_email=$user->user_email;

        $arguments=array();
        wpestate_select_email_type($user_email,'membership_cancelled',$arguments);

    }
 endif; // end   wpestate_downgrade_to_free





/////////////////////////////////////////////////////////////////////////////////////
/// upgrade user
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_upgrade_user_membership') ):
    function wpestate_upgrade_user_membership($user_id,$pack_id,$type,$paypal_tax_id){

        $available_listings                  =   floatval(get_post_meta($pack_id, 'pack_listings', true) );
        $featured_available_listings         =   floatval(get_post_meta($pack_id, 'pack_featured_listings', true) );
        $pack_unlimited_list                 =   floatval(get_post_meta($pack_id, 'mem_list_unl', true) );


        $current_used_listings               =   floatval( get_user_meta($user_id, 'package_listings',true) );
        $curent_used_featured_listings       =   floatval( get_user_meta($user_id, 'package_featured_listings',true) );
        $current_pack                        =   floatval( get_user_meta($user_id, 'package_id',true) );


        $user_curent_listings               =   wpestate_get_user_curent_listings_no_exp ( $user_id );
        $user_curent_future_listings        =   wpestate_get_user_curent_future_listings_no_exp( $user_id );


        if( wpestate_check_downgrade_situation($user_id,$pack_id) ){
            $new_listings           =   $available_listings;
            $new_featured_listings  =   $featured_available_listings;
        }else{
            $new_listings            =  $available_listings - $user_curent_listings ;
            $new_featured_listings   =  $featured_available_listings-  $user_curent_future_listings ;
        }


        // in case of downgrade
        if($new_listings<0){
            $new_listings=0;
        }

        if($new_featured_listings<0){
            $new_featured_listings=0;
        }


        if ($pack_unlimited_list==1){
            $new_listings=-1;
        }


        update_user_meta( $user_id, 'package_listings', $new_listings) ;
        update_user_meta( $user_id, 'package_featured_listings', $new_featured_listings);


        $time = time();
        $date = date('Y-m-d H:i:s',$time);
        update_user_meta( $user_id, 'package_activation', $date);
        update_user_meta( $user_id, 'package_id', $pack_id);


        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $message  = esc_html__( 'Hi there,','wprentals-core') . "\r\n\r\n";
        $message .= sprintf( esc_html__( "Your new membership on  %s is activated! You should go check it out.",'wprentals-core'), get_option('blogname')) . "\r\n\r\n";

        $user = get_user_by('id',$user_id);
        $user_email=$user->user_email;

        $arguments=array();
        wpestate_select_email_type($user_email,'membership_activated',$arguments);


        $billing_for='Package';
        $invoice_id = wpestate_insert_invoice($billing_for,$type,$pack_id,$date,$user_id,0,0,$paypal_tax_id);
        update_post_meta($invoice_id, 'invoice_status', 'confirmed');
    }

endif; // end   wpestate_upgrade_user_membership




if( !function_exists('wpestate_upgrade_user_membership_on_wiretransfer') ):
    function wpestate_upgrade_user_membership_on_wiretransfer($user_id,$pack_id,$type,$paypal_tax_id){

        $available_listings                  =   get_post_meta($pack_id, 'pack_listings', true);
        $featured_available_listings         =   get_post_meta($pack_id, 'pack_featured_listings', true);
        $pack_unlimited_list                 =   get_post_meta($pack_id, 'mem_list_unl', true);


        $current_used_listings               =   get_user_meta($user_id, 'package_listings',true);
        $curent_used_featured_listings       =   get_user_meta($user_id, 'package_featured_listings',true);
        $current_pack=get_user_meta($user_id, 'package_id',true);


        $user_curent_listings               =   wpestate_get_user_curent_listings_no_exp ( $user_id );
        $user_curent_future_listings        =   wpestate_get_user_curent_future_listings_no_exp( $user_id );


        if( wpestate_check_downgrade_situation($user_id,$pack_id) ){
            $new_listings           =   $available_listings;
            $new_featured_listings  =   $featured_available_listings;
        }else{
            $new_listings            =  $available_listings - $user_curent_listings ;
            $new_featured_listings   =  $featured_available_listings-  $user_curent_future_listings ;
        }


        // in case of downgrade
        if($new_listings<0){
            $new_listings=0;
        }

        if($new_featured_listings<0){
            $new_featured_listings=0;
        }


        if ($pack_unlimited_list==1){
            $new_listings=-1;
        }


        update_user_meta( $user_id, 'package_listings', $new_listings) ;
        update_user_meta( $user_id, 'package_featured_listings', $new_featured_listings);


        $time = time();
        $date = date('Y-m-d H:i:s',$time);
        update_user_meta( $user_id, 'package_activation', $date);
        update_user_meta( $user_id, 'package_id', $pack_id);


        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $message  = esc_html__( 'Hi there,','wprentals-core') . "\r\n\r\n";
        $message .= sprintf( esc_html__( "Your new membership on  %s is activated! You should go check it out.",'wprentals-core'), get_option('blogname')) . "\r\n\r\n";

        $user = get_user_by('id',$user_id);
        $user_email=$user->user_email;

        $arguments=array();
        wpestate_select_email_type($user_email,'membership_activated',$arguments);



    }

endif; // end   wpestate_upgrade_user_membership



/////////////////////////////////////////////////////////////////////////////////////
/// check for downgrade
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_check_downgrade_situation') ):
    function  wpestate_check_downgrade_situation($user_id,$new_pack_id){

        $future_listings                  =   get_post_meta($new_pack_id, 'pack_listings', true);
        $future_featured_listings         =   get_post_meta($new_pack_id, 'pack_featured_listings', true);
        $unlimited_future                 =   get_post_meta($new_pack_id, 'mem_list_unl', true);
        $curent_list                      =   get_user_meta( $user_id, 'package_listings', true) ;

        if($unlimited_future==1){
            return false;
        }

        if ($curent_list == -1 && $unlimited_future!=1 ){ // if is unlimited and go to non unlimited pack
            return true;
        }

        if ( (wpestate_get_user_curent_listings_published($user_id) > $future_listings ) || ( wpestate_get_user_curent_future_listings($user_id) > $future_featured_listings ) ){
            return true;
        }else{
            return false;
        }
    }
endif; // end   wpestate_check_downgrade_situation


/////////////////////////////////////////////////////////////////////////////////////
/// get the number of listings
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_user_curent_listings') ):
    function wpestate_get_user_curent_listings($userid) {
      $args = array(
            'post_type'     =>  'estate_property',
            'post_status'   =>  'any',
            'author'        =>  $userid,

        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();
    }
endif; // end   get_user_curent_listings


if( !function_exists('wpestate_get_user_curent_listings_published') ):
function wpestate_get_user_curent_listings_published($userid) {
  $args = array(
        'post_type'     =>  'estate_property',
        'post_status'     => 'publish',
        'author'        =>  $userid,

    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
}
endif; // end   get_user_curent_listings

if( !function_exists('wpestate_get_user_curent_listings_no_exp') ):
    function wpestate_get_user_curent_listings_no_exp($userid) {
        $args = array(
            'post_type'     => 'estate_property',
            'post_status' => array( 'pending', 'publish' ),
            'author'        =>$userid,

        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();

    }
endif; // end   wpestate_get_user_curent_listings_no_exp


/////////////////////////////////////////////////////////////////////////////////////
/// get the number of featured listings
/////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_user_curent_future_listings_no_exp') ):
    function wpestate_get_user_curent_future_listings_no_exp($user_id){

        $args = array(
            'post_type'     =>  'estate_property',
            'post_status'   =>  array( 'pending', 'publish' ),
            'author'        =>  $user_id,
            'meta_query'    =>  array(
                                    array(
                                        'key'   => 'prop_featured',
                                        'value' => 1,
                                        'meta_compare '=>'='
                                    )
                                )
        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();

    }
endif; // end   wpestate_get_user_curent_future_listings_no_exp


if( !function_exists('wpestate_get_user_curent_future_listings') ):
    function wpestate_get_user_curent_future_listings($user_id){

        $args = array(
            'post_type'     =>  'estate_property',
            'post_status'   =>  'any',
            'author'        =>  $user_id,
            'meta_query'    =>  array(
                                    array(
                                        'key'   => 'prop_featured',
                                        'value' => 1,
                                        'meta_compare '=>'='
                                    )
                            )
        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();

    }
endif; // end   wpestate_get_user_curent_future_listings

/////////////////////////////////////////////////////////////////////////////////////
/// update user with paypal profile id
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_update_user_recuring_profile') ):
    function wpestate_update_user_recuring_profile( $profile_id,$user_id ){
          $profile_id=  str_replace('-', 'xxx', $profile_id);
          $profile_id=  str_replace('%2d', 'xxx', $profile_id);

          update_user_meta( $user_id, 'profile_id', $profile_id);
    }
endif; // end   wpestate_update_user_recuring_profile


////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function
////////////////////////////////////////////////////////////////////////////////

add_action( 'wp_ajax_wpestate_ajax_make_prop_featured', 'wpestate_ajax_make_prop_featured' );

if( !function_exists('wpestate_ajax_make_prop_featured') ):
    function  wpestate_ajax_make_prop_featured(){
        check_ajax_referer( 'wprentals_property_actions_nonce', 'security' );
        $prop_id=intval($_POST['propid']);
        $current_user = wp_get_current_user();
        $userID =   $current_user->ID;

        if ( !is_user_logged_in() ) {
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }



        $post   =   get_post($prop_id);

        if( $post->post_author != $userID){
            exit('get out of my cloud');
        }else{
            if(wpestate_get_remain_featured_listing_user($userID) >0 ){
               wpestate_update_featured_listing_no($userID);
               update_post_meta($prop_id, 'prop_featured', 1);
               print 'done';
               die();
            }else{
                print 'no places';
                die();
            }
        }

    }
endif; // end   wpestate_ajax_make_prop_featured

////////////////////////////////////////////////////////////////////////////////
/// Check user status durin cron
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_check_user_membership_status_function') ):
    function wpestate_check_user_membership_status_function(){
        $blogusers = get_users('role=subscriber');
        foreach ($blogusers as $user) {
            $user_id=$user->ID;
            $pack_id= get_user_meta ( $user_id, 'package_id', true);


            if( $pack_id !='' ){ // if the pack is ! free
                $date =  strtotime ( get_user_meta($user_id, 'package_activation',true) );

                $biling_period  =   get_post_meta($pack_id, 'biling_period', true);
                $billing_freq   =   get_post_meta($pack_id, 'billing_freq', true);

                $seconds=0;
                switch ($biling_period){
                   case 'Day':
                       $seconds=60*60*24;
                       break;
                   case 'Week':
                       $seconds=60*60*24*7;
                       break;
                   case 'Month':
                       $seconds=60*60*24*30;
                       break;
                   case 'Year':
                       $seconds=60*60*24*365;
                       break;
               }
               $time_frame=$seconds*$billing_freq;

               $now=time();

               if( $now >$date+$time_frame ){ // if this moment is bigger than pack activation + billing period
                     wpestate_downgrade_to_free($user_id);
               }

            } // end if if pack !- free

        }// end foreach
        wpestate_check_free_listing_expiration();
    }
endif; // end   wpestate_check_user_membership_status_function




////////////////////////////////////////////////////////////////////////////////
//  check_free_listing_expiration
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_check_free_listing_expiration') ):
    function wpestate_check_free_listing_expiration(){

        $free_feat_list_expiration= intval ( wprentals_get_option('wp_estate_free_feat_list_expiration','') );

        if($free_feat_list_expiration!=0 && $free_feat_list_expiration!=''){
            $blogusers = get_users('role=subscriber');
            $users_with_free='';
            $author_array=array();
            $author_array[]=0;
            foreach ($blogusers as $user) {
                $user_id=$user->ID;
                $pack_id= get_user_meta ( $user_id, 'package_id', true);

                if( $pack_id =='' ){ // if the pack is ! free
                    //$users_with_free .= $user_id.',';
                    $author_array[]=$user_id;
                }
            }


            $args = array(
                'post_type'        =>  'estate_property',
                'author__in'           =>  $author_array,
                'post_status'      =>  'publish'
            );
            $prop_selection = new WP_Query($args);

            while ($prop_selection->have_posts()): $prop_selection->the_post();

                $the_id=get_the_ID();
                $pfx_date = strtotime ( get_the_date("Y-m-d",  $the_id ) );
                $expiration_date=$pfx_date+$free_feat_list_expiration*24*60*60;
                $today=time();

                if ($expiration_date < $today){
                    wpestate_listing_set_to_expire($the_id);
                }

            endwhile;
        }
    }
endif;

////////////////////////////////////////////////////////////////////////////////
//  expire free listings
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_listing_set_to_expire') ):
    function wpestate_listing_set_to_expire($post_id){
        $prop = array(
                'ID'            => $post_id,
                'post_type'     => 'estate_property',
                'post_status'   => 'expired'
        );

        wp_update_post($prop );

        $user_id    =   wpsestate_get_author( $post_id );
        $user       =   get_user_by('id',$user_id);
        $user_email =   $user->user_email;

        $arguments=array(
            'expired_listing_url'  => esc_url ( get_permalink($post_id)),
            'expired_listing_name' => get_the_title($post_id)
        );
        wpestate_select_email_type($user_email,'free_listing_expired',$arguments);
    }
endif;

////////////////////////////////////////////////////////////////////////////////
//  member verification functions
////////////////////////////////////////////////////////////////////////////////

if ( ! function_exists('wpestate_get_verification_users')) {
	/**
	 * Get all users with verification meta
	 *
	 * @return array
	 */
	function wpestate_get_verification_users() {
		$verification_users = get_users(array(
			'meta_key' => 'user_id_verified',
			'fields' => 'all_with_meta'
		));
		return $verification_users;
	}
}




if ( ! function_exists( 'wpestate_admin_display_verifications' ) ) {
	/**
	 * Display verification widget
	 */
	function wpestate_admin_display_verifications() {
		global $current_user;
		if ( 'profile' == get_current_screen()->id ) {
			$verifications = '';
			$verification_users = '';

			$v_users = wpestate_get_verification_users();

			foreach ( $v_users as $user_o ) {
				$verification_users .= wpestate_render_single_userid($user_o);
			}

			$verifications .= '<div class="user-verifications">ccc' . PHP_EOL;
			$verifications .= $verification_users;
			$verifications .= '</div> <!-- end .user-verifications -->' . PHP_EOL;

			print $verifications;
		}
	}

	// display verification widget only for admin users on the admin user edit page

}

if ( ! function_exists( 'wpestate_render_single_userid' ) ) {
	/**
	 * Constructs and returns verification
	 * widget part for a single user
	 *
	 * @param $user_o
	 *
	 * @return string
	 */
	function wpestate_render_single_userid( $user_o ) {
		$verification_users = '';
		if ( ! empty( $user_o ) ) {
			$useridimageid      = get_user_meta( $user_o->ID, 'user_id_image', TRUE );
			$user_id_verified   = get_user_meta( $user_o->ID, 'user_id_verified', TRUE );
			$verify_label       = ( $user_id_verified == 0 ) ? esc_html__( 'Validate user ID', 'wprentals-core' ) : esc_html__( 'Remove user ID validation', 'wprentals-core' );
			$verification_class = ( $user_id_verified == 1 ) ? ' verified' : '';
			$verification_users .= sprintf( '<div class="verify-user%s">', esc_attr( $verification_class ) ) . PHP_EOL;
			$verification_users .= sprintf( '<div class="user-ID"><img src="%1$s" alt="%2$s"></div>', esc_url( $useridimageid ), esc_html( $user_o->display_name ) );
                   
			if ( 'user-edit' !== get_current_screen()->id ) {
				$verification_users .= sprintf( '<div class="eit-profile"><span class="user-display-name">%3$s</span> <a href="%1$s">%2$s</a></div>', esc_url( get_edit_user_link( $user_o->ID ) ), esc_html__( 'Edit/view user profile', 'wprentals-core' ), esc_html( $user_o->display_name ) );
			}
			$verification_users .= sprintf( '<div class="verification-checkbox"><label>%3$s <input type="checkbox" %1$s value="1" name="verified-users[]" class="user_verification_check" data-userid="%2$d"></label></div>', checked( 1, $user_id_verified, FALSE ), esc_attr( $user_o->ID ), $verify_label );
			$verification_users .= esc_html__('Please move or delete the ID Scan image - some SEO plugins may help search engines index these images and they can become public. ','wprentals-core');
                        $verification_users .= '<br>'.esc_html__(' Image path:','wprentals-core').' '.$useridimageid;
                        $verification_users .= '</div> <!-- end .verify-user -->' . PHP_EOL;
		}

		return $verification_users;
	}
}

if ( ! function_exists( 'wpestate_display_userID' ) ) {
	/**
	 * Displays verification widget for a user
	 *
	 * @param $profileuser
	 */
	function wpestate_display_userID( $profileuser ) {
		$verifications      = '';
		$verification_users = wpestate_render_single_userid( $profileuser );
		// add div to trigger javascript ajax calls
		if ( $verification_users ) {
			$verifications .= sprintf( '<h2>%s</h2>', esc_html__( 'User ID', 'wprentals-core' ) ) . PHP_EOL;
			$verifications .= '<div class="user-verifications">' . PHP_EOL;
			$verifications .= $verification_users;
			$verifications .= '</div> <!-- end .user-verifications -->' . PHP_EOL;
                        $ajax_nonce = wp_create_nonce( "wprentals_user_verfication_nonce" );
                        $verifications .= '<input type="hidden" id="wprentals_user_verfication" value="'.esc_html($ajax_nonce).'" />    ';

		}

		print $verifications;
	}
}
add_action( 'edit_user_profile', 'wpestate_display_userID' );

if ( ! function_exists( 'wpestate_display_verification_badge' ) ) {
	/**
	 * Display simeple "verified" HTML structure
	 *
	 * @param $userID
	 *
	 * @return string
	 */
	function wpestate_display_verification_badge( $userID,$type='' ) {
		$verified = '';
		$user_verified = wpestate_userid_verified( $userID );
		if ( $user_verified ) {
                    if($type==2){
                        $verified='<div class="verified_userid">
                        <svg width="61" height="76" viewBox="0 0 61 76" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M32.2695 75.496C37.8048 73.1439 42.9157 69.7927 47.4164 65.8137C49.8392 63.6738 51.9879 61.2952 53.9509 58.731C55.7017 56.4408 57.2137 53.965 58.3455 51.3123C59.0087 49.7649 59.61 48.2175 60.0256 46.5905C60.45 44.9635 60.7064 43.2923 60.8479 41.6211C60.9894 39.9853 60.9363 38.3317 60.9363 36.6871C60.9363 34.9451 60.9363 33.1944 60.9363 31.4524C60.9363 26.8456 60.9363 22.2387 60.9363 17.6319C60.9363 16.058 60.9805 14.484 60.9363 12.9189C60.9363 12.9012 60.9363 12.8836 60.9363 12.8659C60.9363 11.2301 59.8222 9.9656 58.3367 9.45274C55.2772 8.39167 52.209 7.33059 49.1495 6.26951C44.3304 4.58947 39.5025 2.91828 34.6746 1.24708C33.6047 0.875701 32.5436 0.468955 31.456 0.132947C30.1032 -0.291485 28.7326 0.398216 27.4858 0.831489C22.8613 2.43195 18.2456 4.03241 13.6211 5.63287C10.0046 6.88847 6.39692 8.13524 2.78042 9.39085C2.71852 9.40853 2.66547 9.43506 2.60357 9.45274C1.11806 9.9656 0.00392992 11.2389 0.00392992 12.8659C0.00392992 14.1126 0.00392992 15.3594 0.00392992 16.6062C0.00392992 20.824 0.00392992 25.0417 0.00392992 29.2595C0.00392992 32.708 -0.0049124 36.1565 0.00392992 39.605C0.0127722 42.6822 0.543312 45.8035 1.56018 48.7126C3.64696 54.69 7.39611 59.96 11.9234 64.337C16.2738 68.5459 21.3758 72.1094 26.858 74.6913C27.4593 74.9743 28.0783 75.2484 28.6884 75.5137C29.0951 75.7701 29.5284 75.9116 30.0059 75.9293C30.4745 76.0354 30.9432 76.0177 31.4207 75.8674C32.243 75.6375 33.1272 75.0097 33.534 74.2404C33.9673 73.418 34.1883 72.4277 33.8877 71.5169C33.6224 70.7035 33.083 69.7485 32.2607 69.4036C28.0606 67.6175 24.1081 65.2654 20.4916 62.4801C20.7303 62.6658 20.9691 62.8515 21.2078 63.0372C18.0246 60.5702 15.1066 57.7583 12.6308 54.5662C12.8164 54.805 13.0021 55.0437 13.1878 55.2825C11.3751 52.9304 9.8189 50.3838 8.66055 47.6427C8.7755 47.9257 8.8993 48.2086 9.01425 48.4916C8.12117 46.3606 7.48453 44.1235 7.1662 41.8333C7.21041 42.1428 7.25463 42.4611 7.29 42.7706C7.07778 41.1436 7.07778 39.5255 7.07778 37.8808C7.07778 36.0151 7.07778 34.1405 7.07778 32.2748C7.07778 29.3568 7.07778 26.4388 7.07778 23.5297C7.07778 20.3995 7.07778 17.2694 7.07778 14.1392C7.07778 13.7236 7.07778 13.308 7.07778 12.8924C6.21123 14.0331 5.34469 15.1649 4.47814 16.3055C7.5199 15.2533 10.5528 14.2011 13.5946 13.1488C18.4313 11.4688 23.2681 9.7976 28.1048 8.11756C29.2101 7.73734 30.3154 7.35712 31.4118 6.96806C30.784 6.96806 30.1562 6.96806 29.5284 6.96806C32.5613 8.02029 35.6031 9.07253 38.636 10.1248C43.4727 11.8048 48.3006 13.476 53.1374 15.156C54.2427 15.5363 55.3391 15.9165 56.4444 16.3055C55.5779 15.1649 54.7113 14.0331 53.8448 12.8924C53.8448 14.2718 53.8448 15.6424 53.8448 17.0218C53.8448 21.6994 53.8448 26.3858 53.8448 31.0634C53.8448 33.0617 53.8448 35.0601 53.8448 37.0496C53.8448 38.9684 53.8801 40.8695 53.6326 42.7794C53.6768 42.4699 53.721 42.1516 53.7564 41.8421C53.438 44.1323 52.8014 46.3694 51.9083 48.5004C52.0233 48.2175 52.1471 47.9345 52.262 47.6515C51.1037 50.3927 49.5474 52.9392 47.7347 55.2913C47.9204 55.0526 48.1061 54.8138 48.2918 54.5751C45.8248 57.7583 42.9068 60.579 39.7236 63.046C39.9623 62.8603 40.2011 62.6746 40.4398 62.4889C36.8322 65.2743 32.8797 67.6352 28.6796 69.4125C27.928 69.7308 27.2648 70.7742 27.0526 71.5258C26.8227 72.3747 26.9288 73.4976 27.4063 74.2492C27.8926 75.0008 28.6177 75.6728 29.5196 75.8762C29.8291 75.9204 30.1474 75.9646 30.4569 76C31.12 75.9735 31.7213 75.8143 32.2695 75.496Z" fill="black"/>
<path d="M14.2489 37.1204C17.0961 39.9676 19.9434 42.8148 22.7994 45.6709C23.2062 46.0777 23.6129 46.4844 24.0197 46.8912C25.3725 48.244 27.6715 48.244 29.0244 46.8912C31.0316 44.8839 33.0477 42.8679 35.0549 40.8607C38.2381 37.6775 41.4125 34.5031 44.5957 31.3198C45.3296 30.5859 46.0635 29.852 46.7975 29.1181C48.1061 27.8094 48.2034 25.4043 46.7975 24.1134C45.3827 22.8135 43.1898 22.7163 41.7927 24.1134C39.7855 26.1206 37.7695 28.1366 35.7622 30.1438C32.579 33.327 29.4046 36.5014 26.2214 39.6847C25.4875 40.4186 24.7536 41.1525 24.0197 41.8864C25.6909 41.8864 27.3532 41.8864 29.0244 41.8864C26.1772 39.0392 23.33 36.192 20.4739 33.3359C20.0671 32.9291 19.6604 32.5224 19.2537 32.1156C17.945 30.807 15.5399 30.7097 14.2489 32.1156C12.9491 33.5304 12.843 35.7233 14.2489 37.1204Z" fill="black"/>
</svg>
                    </div>';
                    }else{
			$verified = sprintf('<span class="verified_userid"><i class="fas fa-check-circle" aria-hidden="true"></i> %s</span>', esc_html__('Verified','wprentals-core'));

                    }
                }
		return $verified;
	}
}

if ( ! function_exists( 'wpestate_userid_verified' ) ) {
	/**
	 * Checks if the users ID has been verified
	 *
	 * @param $userID
	 *
	 * @return bool
	 */
	function wpestate_userid_verified( $userID ) {
		$verified = FALSE;
		$verified_meta = get_user_meta( $userID, 'user_id_verified', TRUE);
		if ($verified_meta == '1') {
			$verified = TRUE;
		}
		return $verified;
	}
}
