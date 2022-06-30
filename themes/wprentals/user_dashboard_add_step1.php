<?php
// Template Name: User Dashboard Submit - Step 1
// Wp Estate Pack

$current_user                   =   wp_get_current_user();
$userID                         =   $current_user->ID;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$status_values                  =   esc_html( wprentals_get_option('wp_estate_status_list','') );
$status_values_array            =   explode(",",$status_values);
$allowed_html                   =   array();
$submission_page_fields         =   ( wprentals_get_option('wp_estate_submission_page_fields','') );
$mandatory_fields               =   ( wprentals_get_option('wp_estate_mandatory_page_fields','') );
global $show_err;

///////////////////////////////////////////////////////////////////////////////////////////
/////// Submit Code
///////////////////////////////////////////////////////////////////////////////////////////
if( isset($_POST) && !empty($_POST) ) {

    $mandatory_fields           =   wprentals_get_option('wp_estate_mandatory_page_fields','');
    $submission_page_fields     =   wprentals_get_option('wp_estate_submission_page_fields','');
    if ( !sh_verify_onetime_nonce( $_POST['estatenonce'], 'thisestate') ){
       exit('');
    }

    if ( !isset($_POST['new_estate']) || !wp_verify_nonce($_POST['new_estate'],'submit_new_estate') ){
        exit('');
    }


    $paid_submission_status    = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
    if ( $paid_submission_status!='membership' || ( $paid_submission_status== 'membership' || wpestate_get_current_user_listings($userID) > 0)  ){ // if user can submit
        if ( !isset($_POST['new_estate']) || !wp_verify_nonce($_POST['new_estate'],'submit_new_estate') ){
           exit('Sorry, your not submiting from site');
        }

        if( !isset($_POST['prop_category']) ) {
            $prop_category  = 0;
        }else{
            $prop_category  =  $prop_category_selected= intval($_POST['prop_category']);
        }

        if( !isset($_POST['prop_action_category']) ) {
            $prop_action_category   =   0;
        }else{
            $prop_action_category  = $prop_action_category_selected=  wp_kses($_POST['prop_action_category'],$allowed_html);
        }

        if( !isset($_POST['property_city']) || $_POST['property_city']=='') {
            if( !isset($_POST['property_city_front'])) {
                $property_city  =   '';
            }else{
                $property_city  =   wp_kses($_POST['property_city_front'],$allowed_html);
            }
        }else{
            $property_city  =   wp_kses($_POST['property_city'],$allowed_html);
        }

        if( !isset($_POST['property_area_front']) ) {
            $property_area  =   '';
        }else{
            $property_area  =   wp_kses($_POST['property_area_front'],$allowed_html);
        }


        if( !isset($_POST['property_country']) ) {
            $property_country   =   '';
        }else{
            $property_country  =   wp_kses($_POST['property_country'],$allowed_html);
        }

        $allowed_html_desc=array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br'        =>  array(),
            'em'        =>  array(),
            'strong'    =>  array(),
            'ul'        =>  array('li'),
            'li'        =>  array(),
            'code'      =>  array(),
            'ol'        =>  array('li'),
            'del'       =>  array(
                            'datetime'=>array()
                            ),
            'blockquote'=> array(),
            'ins'       =>  array(),


        );

        if( !isset($_POST['property_description']) ) {
            $property_description   =   '';
        }else{
            $property_description  =   wp_kses($_POST['property_description'],$allowed_html_desc);
        }

        $property_admin_area='';
        if(isset($_POST['property_admin_area'])){
            $property_admin_area=   wp_kses($_POST['property_admin_area'],$allowed_html);
        }

        $show_err                       =   '';
        $post_id                        =   '';
        $submit_title                   =   wp_kses( $_POST['wpestate_title'],$allowed_html );
        $wpestate_guest_no              =   0;
        if(isset($_POST['guest_no'])){
            $wpestate_guest_no          =   intval( $_POST['guest_no']);
        }

        if( !isset($_POST['property_affiliate']) ) {
            $property_affiliate   =   '';
        }else{
            $property_affiliate  =   esc_url($_POST['property_affiliate']);
        }


        $has_errors                     =   false;
        $errors                         =   array();


        if($submit_title==''){
            $has_errors=true;
            $errors[]=esc_html__( 'Please submit a title for your listing','wprentals');
        }


        if( is_array($mandatory_fields) && in_array('prop_category_submit', $mandatory_fields)) {
            if($prop_category=='' || $prop_category=='-1'){
                $has_errors=true;
                $errors[]=esc_html__( 'Please submit a category','wprentals');
            }
        }

        if( is_array($mandatory_fields) && in_array('prop_action_category_submit', $mandatory_fields)) {
            if($prop_action_category=='' || $prop_action_category=='-1'){
                $has_errors=true;
                $errors[]=esc_html__( 'Please submit the second category','wprentals');
            }
        }

        if( is_array($mandatory_fields) && in_array('property_city_front', $mandatory_fields)) {
            if($property_city==''){
                $has_errors=true;
                $errors[]=esc_html__( 'Please chose a city.','wprentals');
            }
        }

        if( is_array($mandatory_fields) && in_array('property_area_front', $mandatory_fields)) {
            if($property_area==''){
                $has_errors=true;
                $errors[]=esc_html__( 'Please chose an area.','wprentals');
            }
        }

        if( is_array($mandatory_fields) && in_array('property_description', $mandatory_fields)) {
            if($property_description==''){
                $has_errors=true;
                $errors[]=esc_html__( 'Please add the description.','wprentals');
            }
        }

        if( is_array($mandatory_fields) && in_array('property_affiliate', $mandatory_fields)) {
            if($property_affiliate==''){
                $has_errors=true;
                $errors[]=esc_html__( 'Please add an affiliate link.','wprentals');
            }
        }



        if($has_errors){
            foreach($errors as $key=>$value){
                $show_err.='<div class="submit_error">'.esc_html($value).'</div>';
            }
        }else{
            $paid_submission_status = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
            $new_status             = 'pending';

            $admin_submission_status= esc_html ( wprentals_get_option('wp_estate_admin_submission','') );
            if($admin_submission_status=='no' && $paid_submission_status!='per listing'){
               $new_status='publish';
            }
            
            if($paid_submission_status=='per listing'){
                 $new_status             = 'pending';
            }

            if($current_user->ID==''){
                $new_user_id=0;
            }else{
                $new_user_id=$current_user->ID;
            }

            $post = array(
                'post_title'	=> $submit_title,
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property' ,
                'post_author'   => $new_user_id ,
                'post_content'  => $property_description
            );
            
           
            
            
            $post_id =  wp_insert_post($post );

            if( $paid_submission_status == 'membership'){ // update pack status
                wpestate_update_listing_no($current_user->ID);
            }

        }

        if($post_id) {
            $prop_category                  =   get_term( $prop_category, 'property_category');
            if(isset($prop_category->term_id)){
                $prop_category_selected         =   $prop_category->term_id;
            }

            $prop_action_category           =   get_term( $prop_action_category, 'property_action_category');
            if(isset($prop_action_category->term_id)){
                $prop_action_category_selected  =   $prop_action_category->term_id;
            }

            $prop_category_name         =   '';
            $prop_action_category_name  =   '';



            if( isset($prop_category->name) ){
                $prop_category_name=$prop_category->name;
                wp_set_object_terms($post_id,$prop_category->name,'property_category');
            }
            if ( isset ($prop_action_category->name) ){
                $prop_action_category_name=$prop_action_category->name;
                wp_set_object_terms($post_id,$prop_action_category->name,'property_action_category');
            }
            if( isset($property_city) && $property_city!='none' ){
                wp_set_object_terms($post_id,$property_city,'property_city');
            }

            if( isset($property_area) && $property_area!='none' ){
               $property_area= wpestate_double_tax_cover($property_area,$property_city,$post_id);
            }


            if( isset($property_area) && $property_area!='none' && $property_area!=''){
                $property_area_obj=   get_term_by('name', $property_area, 'property_area');

                    $t_id = $property_area_obj->term_id ;
                    $term_meta = get_option( "taxonomy_$t_id");

                    $allowed_html   =   array();
                    $term_meta['cityparent'] =  wp_kses( $property_city,$allowed_html);
                    //save the option array
                     update_option( "taxonomy_$t_id", $term_meta );

            }

            update_post_meta($post_id, 'prop_featured', 0);
            $rental_type =  wprentals_get_option('wp_estate_item_rental_type');
            if($rental_type==1){
                $wpestate_guest_no=1;
            }

            $property_country = wprentals_agolia_dirty_hack($property_country);

            update_post_meta($post_id, 'guest_no', $wpestate_guest_no);
            update_post_meta($post_id, 'property_affiliate',$property_affiliate);
            update_post_meta($post_id, 'property_country', $property_country);
            if(isset($_POST['instant_booking'])){
                update_post_meta($post_id,'instant_booking',intval($_POST['instant_booking']));
            }
             if(isset($_POST['children_as_guests'])){
                update_post_meta($post_id,'children_as_guests',intval($_POST['children_as_guests']));
            }
            
            update_post_meta($post_id, 'property_admin_area', $property_admin_area);

            update_post_meta($post_id, 'pay_status', 'not paid');
            update_post_meta($post_id, 'page_custom_zoom', 16);
            $sidebar =  wprentals_get_option( 'wp_estate_blog_sidebar');
            update_post_meta($post_id, 'sidebar_option', $sidebar);
            $sidebar_name   = wprentals_get_option( 'wp_estate_blog_sidebar_name');
            update_post_meta($post_id, 'sidebar_select', $sidebar_name);

            wpestate_global_check_mandatory($post_id);

            // get user dashboard link
            $edit_link                       =   wpestate_get_template_link('user_dashboard_edit_listing.php');
            $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'listing_edit', $post_id, $edit_link) ) ;
            $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'action', 'description', $edit_link_desc) ) ;
            $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'isnew', 1, $edit_link_desc) ) ;

           $arguments=array(
                'new_listing_url'   => esc_url (get_permalink($post_id)),
                'new_listing_title' => $submit_title
            );
            wpestate_select_email_type(get_option('admin_email'),'new_listing_submission',$arguments);

            wp_reset_query();

            if ( intval($_POST['pointblank']!=1)){
                wp_redirect( $edit_link_desc);
                exit;
            }

        }
        }//end if user can submit

} // end post


get_header();
$wpestate_options=wpestate_page_details($post->ID);




function sh_verify_onetime_nonce( $_nonce, $action = -1) {

    //Extract timestamp and nonce part of $_nonce aebe3659e7-1447771723
    $parts = explode( '-', $_nonce );
    $nonce = $parts[0]; // Original nonce generated by WordPress.
    $generated = $parts[1]; //Time when generated

    $nonce_life = 60*60; //We want these nonces to have a short lifespan
    $expires = (int) $generated + $nonce_life;
    $time = time(); //Current time

    //Verify the nonce part and check that it has not expired

    if( ! wp_verify_nonce( $nonce, $generated.$action ) || $time > $expires ){
        return false;
    }

    //Get used nonces
    $used_nonces = get_option('_sh_used_nonces');

    //Nonce already used.
    // print '- la used nonces - ';
    if( isset( $used_nonces[$nonce] ) ) {
        //   print ' - 259 - ';
        return false;
    }


    if($used_nonces!=''){
        //print '-la foreach - ';
        foreach ($used_nonces as $nonce=> $timestamp){
            if( $timestamp > $time ){
                break;
            }
            //This nonce has expired, so we don't need to keep it any longer
            unset( $used_nonces[$nonce] );
        }
    }


    //Add nonce to used nonces and sort
    $used_nonces[$nonce] = $expires;
    asort( $used_nonces );
    update_option( '_sh_used_nonces',$used_nonces );
    return true;

}

///////////////////////////////////////////////////////////////////////////////////////////
/////// Html Form Code below
///////////////////////////////////////////////////////////////////////////////////////////
?>

<div id="cover"></div>
<div class="row
    <?php
    if( is_user_logged_in() ){
        echo 'is_dashboard';
        if ( !wpestate_check_user_level()){
            wp_redirect(  esc_html( home_url('/') ) );exit();
        }
    }else{
        echo 'no_log_submit';
    }
    ?> ">

    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {
            include(locate_template('templates/user_menu.php') );
        }
    }
    ?>

    <div class="dashboard-margin
    <?php if ( !is_user_logged_in() ) {
        echo 'dashboard-margin-nolog';
    }
    ?>
    ">

    <?php       wprentals_dashboard_header_display(); ?>

    <?php
    $remaining_listings =   wpestate_get_remain_listing_user($userID,$user_pack);

    if($remaining_listings  === -1){
       $remaining_listings=11;
    }
    $paid_submission_status= esc_html ( wprentals_get_option('wp_estate_paid_submission','') );


    if( is_user_logged_in() && !isset( $_GET['listing_edit'] ) && $paid_submission_status == 'membership' && $remaining_listings != -1 && $remaining_listings < 1 ) {
        print '<h4 class="nosubmit">'.esc_html__( 'Your current package doesn\'t let you publish more properties! You need to upgrade your subscription.','wprentals' ).'</h4>';
    }else{
    ?>

    <div class="user_dashboard_panel">
        <?php include(locate_template('templates/submission_guide.php') );?>

        <div class="row">
            <?php print trim($show_err); //escaped above?>
            <?php include(locate_template('templates/submit_templates/property_description_first.php') ); ?>
        </div>
    </div>    
    <?php
    }
    ?>

    </div>
</div>





<?php
if( isset($_POST) && !empty($_POST)  ) {
    if (intval($_POST['pointblank']==1)){
        print   '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                    //jQuery("#form_submit_1").remove();
                    var random;
                    random=Math.random().toString(36).substring(7);
                    jQuery("#new_estate").val(random);
                    jQuery("#title,#prop_category_submit,#prop_action_category_submit,#guest_no,#property_city_front,#property_country,#property_city,#property_area_front,#property_description").val("");
                    jQuery("#new_post").remove();

                    wpestate_show_login_form(1,0,'.intval($post_id).');

                });
                //]]>
                </script>';
    }
}

get_footer();
?>
