<?php
$sms_verification =esc_html( wprentals_get_option('wp_estate_sms_verification',''));
if($sms_verification==='yes'){
  $check_phone = get_the_author_meta( 'check_phone_valid' , $userID);
  if($check_phone!='yes'){ ?>

  <div class="sms_wrapper user_dashboard_panel">
      <h4 class="user_dashboard_panel_title"><?php esc_html_e(' Validate your Mobile Phone Number to receive SMS Notifications','wprentals');?></h4>
      <div class="col-md-12" id="sms_profile_message"></div>
      <div class="col-md-9">
          <?php //echo get_user_meta( $userID, 'validation_pin',true). '</br>';
              esc_html_e('1. Add your Mobile no in Your Details section. Make sure you add it with country code.','wprentals');echo '</br>';
              esc_html_e('2. Click on the button "Send me validation code".','wprentals');echo '</br>';
              esc_html_e('3. You will get a 4 digit code number via sms at','wprentals');echo ' '.esc_html($user_mobile).'.</br> ';
              esc_html_e('4. Add the 4 digit code in the form below and click "Validate Mobile Phone Number"','wprentals');

          ?>
          <input type="text" style="max-width:250px;" id="validate_phoneno" class="form-control" value=""  name="validate_phoneno">
          <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="send_sms_pin"><?php esc_html_e('Send me validation code','wprentals');?></button>
          <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="validate_phone"><?php esc_html_e('Validate Mobile Phone Number','wprentals');?></button>
          <?php  echo '</br>'; esc_html_e('*** If you don\'t receive the SMS, please check that your mobile phone number has the proper format (use the country code ex: +1 3232 232)','wprentals');echo '</br>';?>

          <?php
          $ajax_nonce = wp_create_nonce( "wprentals_send_sms_nonce" );
          print'<input type="hidden" id="wprentals_send_sms_nonce" value="'.esc_html($ajax_nonce).'" />    ';
          ?>

      </div>
      <div class="col-md-6"></div>
  </div>
  <?php
  }
}
?>
