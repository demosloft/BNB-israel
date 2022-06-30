<div class="wrap bialty-containter">

    <?php 
include 'layout/top.view.php';
?>
    <!-- start main settings column -->
    <div class="bialty-row">

        <div class="bialty-column col-9">

            <div class="bialty-main">

                <form method="post">

                    <?php 
if ( function_exists( 'wp_nonce_field' ) ) {
    wp_nonce_field( 'bialty_settings' );
}
?>
                    
                    <?php 
echo  $progress_bar ;
?>

                    <div class="bialty-note">
                        <h3><?php 
echo  esc_html__( 'BIALTY, how does it work?', "bulk-image-alt-text-with-yoast" ) ;
?></h3>
                        <p><?php 
echo  esc_html__( '1. Select what to do with missing alt tags', "bulk-image-alt-text-with-yoast" ) ;
?><br>
                        <?php 
echo  esc_html__( '2. Select what to do with existing alt tags', "bulk-image-alt-text-with-yoast" ) ;
?><br>
                        <?php 
echo  esc_html__( '3. Click on "save changes" and your settings will be applied everywhere.', "bulk-image-alt-text-with-yoast" ) ;
?><br>
                        <?php 
echo  esc_html__( "4. Bialty will be active for all future publications which means that you won't have to worry anymore about Alt texts.", "bulk-image-alt-text-with-yoast" ) ;
?></p>
                    </div>

                    <h2><?php 
echo  esc_html__( 'About Page and Post Alt texts', "bulk-image-alt-text-with-yoast" ) ;
?></h2>

                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Replace Missing Alt Tags with', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                        <div class="bialty-column col-8">

                            <select name="alt_empty" class="bialty-select">
                                <option>
                                    <?php 
echo  __( "Disabled", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                                
                                <option value="alt_empty_fkw" <?php 
if ( $options::valid( 'alt_empty', 'alt_empty_fkw' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Yoast / Rank Math Focus Keyword", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                                <option value="alt_empty_title" <?php 
if ( $options::valid( 'alt_empty', 'alt_empty_title' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Post Title", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                                <option value="alt_empty_imagename" <?php 
if ( $options::valid( 'alt_empty', 'alt_empty_imagename' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Image Name", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                                <option value="alt_empty_both" <?php 
if ( $options::valid( 'alt_empty', 'alt_empty_both' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Yoast / Rank Math Focus Keyword & Post Title", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                            </select>
                            
                        </div>

                    </div>

                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Replace Defined Alt Tags with', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                        <div class="bialty-column col-8">

                            <select name="alt_not_empty" class="bialty-select">
                                <option>
                                    <?php 
echo  __( "Disabled", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                                
                                <option value="alt_not_empty_fkw" <?php 
if ( $options::valid( 'alt_not_empty', 'alt_not_empty_fkw' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Yoast / Rank Math Focus Keyword", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                                <option value="alt_not_empty_title" <?php 
if ( $options::valid( 'alt_not_empty', 'alt_not_empty_title' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Post Title", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                                <option value="alt_not_empty_imagename" <?php 
if ( $options::valid( 'alt_not_empty', 'alt_not_empty_imagename' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Image Name", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>

                                <option value="alt_not_empty_both" <?php 
if ( $options::valid( 'alt_not_empty', 'alt_not_empty_both' ) ) {
    echo  'selected' ;
}
?> >
                                    <?php 
echo  __( "Yoast / Rank Math Focus Keyword & Post Title", "bulk-image-alt-text-with-yoast" ) ;
?>
                                </option>
                            </select>
                            
                        </div>

                    </div>

                    <h2><?php 
echo  esc_html__( 'About Product Alt texts (for Woocommerce)', "bulk-image-alt-text-with-yoast" ) ;
?></h2>
                    
                    <?php 
?>
                    
                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Replace Missing Alt Tags with', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                        <div class="bialty-column col-8">

                            <select name="product_images" class="bialty-select" disabled>
                                <option>Yoast / Rank Math Focus Keyword</option>
                            </select>

                        </div>

                    </div>

                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Replace Defined Alt Tags with', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                        <div class="bialty-column col-8">

                            <select name="product_images" class="bialty-select" disabled>
                                <option>Yoast / Rank Math Focus Keyword</option>
                            </select>

                            <div class="bialty-alert bialty-info" style="display: block;">
                                <span class="closebtn">&times;</span>
                                <?php 
echo  $get_pro . " " . esc_html__( 'Bulk Image Alt Text for Woocommerce Products', "bulk-image-alt-text-with-yoast" ) ;
?>
                            </div>

                        </div>

                    </div>
                    
                    <?php 
//end free
?>

                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Disable for Product Gallery', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                            <div class="bialty-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bialty-tooltiptext"><?php 
echo  __( 'Using this option will disable Bialty for Product Gallery and Featured Image. Turn it ON if Product images are not displaying or gallery is broken.', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                            </div>
                        </div>

                        <div class="bialty-column col-8">
                            <label class="bialty-switch">
                                <input type="checkbox" id="woo_disable_gallery" name="woo_disable_gallery" value="woo_disable_gallery"
                                <?php 
if ( $options::check( 'woo_disable_gallery' ) ) {
    echo  'checked' ;
}
?> />
                                <span class="bialty-slider bialty-round"></span>
                            </label>
                            &nbsp;
                            <span><?php 
echo  esc_html__( 'Advanced Users: Only turn it ON if you have issues with product gallery images on all products.', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                    </div>

                    <hr class="bialty-hr" />

                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Add Site Title', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                            <div class="bialty-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bialty-tooltiptext"><?php 
echo  __( 'Using this option will add your site title to all your image title attributes.', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                            </div>
                        </div>

                        <div class="bialty-column col-8">
                            <label class="bialty-switch">
                                <input type="checkbox" id="add_site_title" name="add_site_title" value="add_site_title"
                                <?php 
if ( $options::check( 'add_site_title' ) ) {
    echo  'checked' ;
}
?> />
                                <span class="bialty-slider bialty-round"></span>
                            </label>
                            &nbsp;
                            <span><?php 
echo  esc_html__( 'Add website title defined in Settings &raquo; General to bialty text as well', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                    </div>

                    <div class="bialty-note" style="margin: 10px 0;">
                        <h3><?php 
echo  esc_html__( 'Warning:', 'bulk-image-alt-text-with-yoast' ) ;
?></h3>
                        <p>●&nbsp; <?php 
echo  __( 'Alt texts added/created by BIALTY plugin ARE NOT added to MEDIA LIBRARY (which is useless as not visible to search engines). All image Alt text are added to HTML code, on frontend. Please follow instructions below, at "How to check Alt Text now?", to see your settings applied.', 'bulk-image-alt-text-with-yoast' ) ;
?></p>

                        <p>●&nbsp; <?php 
echo  sprintf( wp_kses( __( 'Alt texts (Alt attributes, Alt tags) are NOT Title attributes (title tags). Please use <a href="%s" target="_blank">BIGTA plugin</a> (by Pagup) if you need to add title tags to your images.', 'bulk-image-alt-text-with-yoast' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( 'https://wordpress.org/plugins/bulk-image-title-attribute/' ) ) ;
?></p>
                    </div>

                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Debug Mode', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                        <div class="bialty-column col-8">
                            <label class="bialty-switch">
                                <input type="checkbox" id="debug_mode" name="debug_mode" value="debug_mode"
                                <?php 
if ( $options::check( 'debug_mode' ) ) {
    echo  'checked' ;
}
?> />
                                <span class="bialty-slider bialty-round"></span>
                            </label>
                            &nbsp;
                            <span><?php 
echo  esc_html__( 'Advanced Users: Only turn it ON if you have styling issues on front-end, otherwise leave it disabled', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                    </div>

                    <div class="bialty-row">

                        <div class="bialty-column col-4">
                            <span class="bialty-label"><?php 
echo  esc_html__( 'Delete Settings', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                        <div class="bialty-column col-8">
                            <label class="bialty-switch">
                                <input type="checkbox" id="remove_settings" name="remove_settings" value="remove_settings"
                                <?php 
if ( $options::check( 'remove_settings' ) ) {
    echo  'checked' ;
}
?> />
                                <span class="bialty-slider bialty-round"></span>
                            </label>
                            &nbsp;
                            <span><?php 
echo  esc_html__( 'Checking this box will remove all settings when you deactivate plugin.', "bulk-image-alt-text-with-yoast" ) ;
?></span>
                        </div>

                    </div>

                    <div class="bialty-note" style="margin: 10px 0;">
                        <h3><?php 
echo  esc_html__( 'Custom ALT Text?', 'bulk-image-alt-text-with-yoast' ) ;
?></h3>
                        <p><?php 
echo  sprintf( wp_kses( __( 'Please use <a href="%s" target="_blank">BIALTY META BOX</a> to either disable plugin locally or add Alt text other than Post titles or Yoast Keyword.', 'bulk-image-alt-text-with-yoast' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( plugin_dir_url( __FILE__ ) . '../assets/imgs/meta-box.png' ) ) ;
?></p>
                    </div>

                    <div class="bialty-alert bialty-info" style="margin-top: 10px;">
                        <?php 
echo  sprintf( wp_kses( __( 'We strongly recommend to combine BIALTY plugin with <a href="%s" target="_blank">BIGTA plugin</a> to auto-optimize your Image title attributes (Image title tags)', 'bulk-image-alt-text-with-yoast' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( "https://wordpress.org/plugins/bulk-image-title-attribute/" ) ) ;
?>
                    </div>
                        
                    <?php 
include 'promo/robot.php';
include 'promo/mobilook.php';
include 'promo/vidseo.php';
?>

                    <p class="submit"><input type="submit" name="update" class="button-primary" value="<?php 
echo  esc_html__( 'Save Changes', "bulk-image-alt-text-with-yoast" ) ;
?>" /></p>
                </form>
                        
            <div class="bialty-note">
                <h3><?php 
echo  esc_html__( 'How to check your Alt texts now?', 'bulk-image-alt-text-with-yoast' ) ;
?></h3>
                <p><?php 
echo  esc_html__( 'Go to your website, click right on a webpage and select "Show Page Source." (Firefox, Safari, Chrome, Internet Explorer,...). Scroll down to the appropriate section (displaying your content), after header area and before footer area. You will be able to identify your modified Alt Texts with your post title (if selected), your Yoast\'s Focus Keyword (if used) and your site name (if selected), separated with a comma. Please note that BIALTY modifies image Alt texts on Frontend (in your HTML code), not on backend (Media LIbrary, etc.), which would be useless for search engines. Want more details about this? Check our video :', 'bulk-image-alt-text-with-yoast' ) ;
?> <a href="https://vimeo.com/306421381">https://vimeo.com/306421381</a></p>
            </div>
                        
            <div class="bialty-alert bialty-info" style="display: block;">
                <?php 
echo  esc_html__( 'IMPORTANT: BIALTY plugin modifies image alt texts on front-end. Any empty or existing alt text will be replaced according to settings above. About Yoast SEO, please note that it "checks" content in real time inside text editor in Wordpress back-end, so even if Yoast does not display a green bullet for the "image alt attributes" line, BIALTY is still doing the job. For your information, Google Bot and other search engine bots see only image alt attributes on Front-end (not as Yoast reading content inside text editor)', 'bulk-image-alt-text-with-yoast' ) ;
?>
            </div>

            <div class="bialty-note">
                <p><?php 
echo  esc_html__( 'Note 1: BIALTY is fully compatible with most popular page builders (TinyMCE, SiteOrigin, Elementor, Gutenberg)', 'bulk-image-alt-text-with-yoast' ) ;
?><br />
                <?php 
echo  esc_html__( 'Note 2: If you\'ve installed YOAST SEO but did not optimize yet, select "Both Focus Keyword & Post Title"', 'bulk-image-alt-text-with-yoast' ) ;
?><br />
                <?php 
echo  esc_html__( 'Note 3: If you did not install YOAST SEO plugin, please keep default settings. BIALTY will add your post titles to Alt tags.', 'bulk-image-alt-text-with-yoast' ) ;
?></p>
            </div>

            <?php 
include 'partials/seo-recommendations.php';
?>
            

        </div>
        <!-- end bialty-main -->
    </div>
        <!-- end main settings bialty-column col-9 -->

    <?php 
include 'layout/sidebar.view.php';
?>

    </div>

</div>