<div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Change Password','wprentals');?></h4>

    <div class="col-md-12" id="profile_pass">
         <?php esc_html_e('*After you change the password you will have to login again.','wprentals'); ?>
    </div>

    <p  class="col-md-4">
        <label for="oldpass"><?php esc_html_e('Old Password','wprentals');?></label>
        <input  id="oldpass" value=""  class="form-control" name="oldpass" type="password">
    </p>

    <p  class="col-md-4">
        <label for="newpass"><?php esc_html_e('New Password ','wprentals');?></label>
        <input  id="newpass" value="" class="form-control" name="newpass" type="password">
    </p>
    <p  class="col-md-4">
        <label for="renewpass"><?php esc_html_e('Confirm New Password','wprentals');?></label>
        <input id="renewpass" value=""  class="form-control" name="renewpass"type="password">
    </p>

    <?php   wp_nonce_field( 'pass_ajax_nonce', 'security-pass' );   ?>
    <p class="fullp-button">
        <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="change_pass"><?php esc_html_e('Reset Password','wprentals');?></button>
    </p>
</div>
