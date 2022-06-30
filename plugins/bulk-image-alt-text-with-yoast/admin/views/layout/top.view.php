<h2><span class="dashicons dashicons-media-text" style="margin-top: 6px; font-size: 24px;"></span>  Bulk Image Alt Attribute
    <?php echo  esc_html__( 'Settings', 'bialty' ); ?>
</h2>

<h2 class="nav-tab-wrapper">
    <a href="<?php echo esc_url( '?page=bialty&tab=bialty-settings' ); ?>" class="nav-tab <?php echo $active_tab == 'bialty-settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
    <a href="<?php echo esc_url( '?page=bialty&tab=bialty-faq' ); ?>" class="nav-tab <?php echo $active_tab == 'bialty-faq' ? 'nav-tab-active' : ''; ?>">FAQ</a>
    <a href="?page=bialty&tab=bialty-recs" class="nav-tab <?php echo $active_tab == 'bialty-recs' ? 'nav-tab-active' : ''; ?>">Recommendations</a>
</h2>