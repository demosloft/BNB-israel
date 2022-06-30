
        <div class="search_dashborad_header">
            <form method="post" action="<?php echo wpestate_get_template_link('user_dashboard.php');?>">
            <?php wp_nonce_field( 'wpestate_dash_search', 'wpestate_dash_search_nonce' ); ?>
            <div class="col-md-4">
                <input type="text" id="title" class="form-control" value="" size="20" name="wpestate_prop_title" placeholder="<?php esc_html_e('Search by listing name.','wprentals');?>">
            </div>
            <div class="col-md-6">
                <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" value="<?php esc_html_e('Search','wprentals');?>">
            </div>
            </form>
        </div>
