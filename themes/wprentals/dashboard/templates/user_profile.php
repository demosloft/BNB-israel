<?php
$current_user           =   wp_get_current_user();
$userID                 =   $current_user->ID;
$user_login             =   $current_user->user_login;
$first_name             =   get_the_author_meta( 'first_name' , $userID );
$last_name              =   get_the_author_meta( 'last_name' , $userID );
$user_email             =   get_the_author_meta( 'user_email' , $userID );
$user_mobile            =   get_the_author_meta( 'mobile' , $userID );
$user_phone             =   get_the_author_meta( 'phone' , $userID );
$description            =   get_the_author_meta( 'description' , $userID );
$facebook               =   get_the_author_meta( 'facebook' , $userID );
$twitter                =   get_the_author_meta( 'twitter' , $userID );
$linkedin               =   get_the_author_meta( 'linkedin' , $userID );
$pinterest              =   get_the_author_meta( 'pinterest' , $userID );
$user_skype             =   get_the_author_meta( 'skype' , $userID );
$instagram              =   get_the_author_meta( 'instagram' , $userID );
$youtube                =   get_the_author_meta( 'youtube' , $userID );
$user_website           =   get_the_author_meta( 'website' , $userID );


$user_title          =   get_the_author_meta( 'title' , $userID );
$user_custom_picture =   get_the_author_meta( 'custom_picture' , $userID );
$user_small_picture  =   get_the_author_meta( 'small_custom_picture' , $userID );
$image_id            =   get_the_author_meta( 'small_custom_picture',$userID);
$user_id_picture     =   get_the_author_meta( 'user_id_image', $userID);
$id_image_id         =   get_the_author_meta( 'user_id_image_id', $userID);
$about_me            =   get_the_author_meta( 'description' , $userID );
$live_in             =   get_the_author_meta( 'live_in' , $userID );
$i_speak             =   get_the_author_meta( 'i_speak' , $userID );
$paypal_payments_to  =   get_the_author_meta( 'paypal_payments_to' , $userID );
$payment_info        =   get_the_author_meta( 'payment_info' , $userID );

if($user_custom_picture==''){
    $user_custom_picture=get_stylesheet_directory_uri().'/img/default_user.png';
}

if($user_id_picture == '' ){
    $user_id_picture =get_stylesheet_directory_uri().'/img/default_user.png';
}

?>


<div class="user_profile_div wprentals_dashboard_container">
    <div class=" row">
        <div class="col-md-12">
          <?php
          include(locate_template('dashboard/templates/sms_validation.php') );
          ?>
        </div>

        <div class="col-md-9">
          <div class="user_dashboard_panel ">
              <h4 class="user_dashboard_panel_title"><?php esc_html_e('Your details','wprentals');?></h4>
              <div class="col-md-12" id="profile_message"></div>
              <div class="col-md-6">
                <p>
                    <label for="firstname"><?php esc_html_e('First Name','wprentals');?></label>
                    <input type="text" id="firstname" class="form-control" value="<?php print esc_html($first_name);?>"  name="firstname">
                </p>

                <p>
                    <label for="secondname"><?php esc_html_e('Last Name','wprentals');?></label>
                    <input type="text" id="secondname" class="form-control" value="<?php print esc_html($last_name);?>"  name="firstname">
                </p>

                <p>
                    <label for="useremail"><?php esc_html_e('Email','wprentals');?></label>
                    <input type="text" id="useremail"  class="form-control" value="<?php print esc_html($user_email);?>"  name="useremail">
                </p>

                <p>
                    <label for="about_me"><?php esc_html_e('About Me','wprentals');?></label>
                    <textarea id="about_me" class="form-control about_me_profile" name="about_me"><?php print esc_textarea($about_me);?></textarea>
                </p>

                <p>
                <label for="live_in"><?php esc_html_e('I live in','wprentals');?></label>
                   <input type="text" id="live_in"  class="form-control" value="<?php print esc_html($live_in);?>"  name="live_in">
                </p>

                <p>
                    <label for="i_speak"><?php esc_html_e('I speak','wprentals');?></label>
                    <input type="text" id="i_speak"  class="form-control" value="<?php print esc_html($i_speak);?>"  name="i_speak">
                </p>
                 <p>
                    <label for="payment_info"><?php esc_html_e('Payment Info/Hidden Field','wprentals');?></label>
                    <textarea id="payment_info" class="form-control" name="payment_info" cols="70" rows="3"><?php print esc_html($payment_info);?></textarea>
                </p>

                <p>
                    <label for="paypal_payments_to"><?php esc_html_e('Email for receiving Paypal Payments (booking fees, refund fees, etc)','wprentals');?></label>
                    <input type="text" id="paypal_payments_to"  class="form-control" value="<?php print esc_html($paypal_payments_to);?>"  name="paypal_payments_to">
                </p>
            </div>

              <div class="col-md-6">
                <p>
                    <label for="userphone"><?php esc_html_e('Phone','wprentals');?></label>
                    <input type="text" id="userphone" class="form-control" value="<?php print esc_html($user_phone);?>"  name="userphone">
                </p>
                <p>
                    <label for="usermobile"><?php esc_html_e('Mobile (*Add the country code format Ex :+1 232 3232)','wprentals');?></label>
                    <input type="text" id="usermobile" class="form-control" value="<?php print esc_html($user_mobile);?>"  name="usermobile">
                </p>
                <p>
                    <label for="userwebsite"><?php esc_html_e('Website','wprentals');?></label>
                    <input type="text" id="userwebsite" class="form-control" value="<?php print esc_html($user_website);?>"  name="usermobile">
                </p>
                <p>
                    <label for="userskype"><?php esc_html_e('Skype','wprentals');?></label>
                    <input type="text" id="userskype" class="form-control" value="<?php print esc_html($user_skype);?>"  name="userskype">
                </p>

                <p>
                    <label for="userfacebook"><?php esc_html_e('Facebook Url','wprentals');?></label>
                    <input type="text" id="userfacebook" class="form-control" value="<?php print esc_html($facebook);?>"  name="userfacebook">
                </p>

                 <p>
                    <label for="usertwitter"><?php esc_html_e('Twitter Url','wprentals');?></label>
                    <input type="text" id="usertwitter" class="form-control" value="<?php print esc_html($twitter);?>"  name="usertwitter">
                </p>

                 <p>
                    <label for="userlinkedin"><?php esc_html_e('Linkedin Url','wprentals');?></label>
                    <input type="text" id="userlinkedin" class="form-control"  value="<?php print esc_html($linkedin);?>"  name="userlinkedin">
                </p>

                 <p>
                    <label for="userpinterest"><?php esc_html_e('Pinterest Url','wprentals');?></label>
                    <input type="text" id="userpinterest" class="form-control"  height="100" value="<?php print esc_html($pinterest);?>"  name="userpinterest">
                </p>
                <p>
                    <label for="userinstagram"><?php esc_html_e('Instagram Url','wprentals');?></label>
                    <input type="text" id="userinstagram" class="form-control"  value="<?php print esc_html($instagram);?>"  name="userinstagram">
                </p>

                <p>
                    <label for="useryoutube"><?php esc_html_e('YouTube Url','wprentals');?></label>
                    <input type="text" id="useryoutube" class="form-control"  height="100" value="<?php print esc_html($youtube);?>"  name="useryoutube">
                </p>
            </div>
            <?php   wp_nonce_field( 'profile_ajax_nonce', 'security-profile' );   ?>

            <p class="fullp-button">
                <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="update_profile"><?php esc_html_e('Update profile','wprentals');?></button>

                <?php
                $ajax_nonce = wp_create_nonce( "wprentals_update_profile_nonce" );
                print'<input type="hidden" id="wprentals_update_profile_nonce" value="'.esc_html($ajax_nonce).'" />    ';

                ?>

                <?php
                  $agent_id   =   get_user_meta($userID, 'user_agent_id', true);
                    if ( $agent_id!=0 && get_post_status($agent_id)=='publish'  ){
                        print'<a href='.esc_url ( get_permalink($agent_id) ).' class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="view_profile">'.esc_html__('View public profile', 'wprentals').'</a>';
                    }
                ?>
                <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="delete_profile"><?php esc_html_e('Delete account','wprentals');?></button>
            </p>
          </div>
        </div>


        <div class="col-md-3 profile_upload_image_wrapper" >
                  <div class="profile_upload_image user_dashboard_panel" >
                        <div class="">
                           <div  id="profile-div" class="feature-media-upload">
                                 <?php print '<img id="profile-image" src="'.esc_url($user_custom_picture).'" alt="'.esc_html__('thumb','wprentals').'" data-profileurl="'.esc_attr($user_custom_picture).'" data-smallprofileurl="'.esc_attr($image_id).'" >';?>

                                  <div id="upload-container">
                                      <div id="aaiu-upload-container">

                                          <button id="aaiu-uploader" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button"><?php esc_html_e('Upload Image','wprentals');?></button>
                                          <div id="profile-div-upload-imagelist">
                                              <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                                          </div>
                                      </div>
                                  </div>
                                  <span class="upload_explain"><?php esc_html_e('* recommended size: minimum 550px ','wprentals');?></span>
                          </div>
                        </div>

                        <div class="">
                          <?php
                         $user_verified = get_user_meta( $userID, 'user_id_verified', TRUE );
                         
                          $user_id_class = ( $user_verified == 1 ) ? 'verified' : 'feature-media-upload';
                          ?>

                          <div id="user-id" class="<?php print esc_attr($user_id_class);?>">
                            <?php print '<img id="user-id-image" src="'.esc_url($user_id_picture).'" alt="'.esc_html__('thumb','wprentals').'" data-useridurl="'.esc_attr($user_id_picture).'" data-useridimageid="'.esc_attr($id_image_id).'" >';?>
                            <?php if ( ! $user_verified ) { ?>
                            <div id="user-id-upload-container-wrap">
                                <div id="user-id-upload-container">

                                    <button id="user-id-uploader" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button image_id_uppload "><?php esc_html_e('Upload an Id Scan','wprentals');?></button>
                                    <div id="user-id-upload-imagelist">
                                        <ul id="user-id-ul-list" class="aaiu-upload-list"></ul>
                                    </div>
                                </div>
                            </div>
                            <span class="upload_explain"><?php esc_html_e('* recommended size: minimum 550px ','wprentals');?></span>
                            <?php } else { ?>
                                <div class="verified-id"><?php esc_html_e('You have been verified','wprentals');?></div>
                            <?php } ?>

                        </div>
                      </div>
                  </div>
                </div>



        <div class="col-md-9">
          <?php
            include(locate_template('dashboard/templates/change_password.php') );
          ?>
        </div>

 </div>
