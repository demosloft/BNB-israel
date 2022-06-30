<div class="misc-pub-section misc-pub-section-last"><span id="timestamp">
    
    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="bialty_text"><?php echo  esc_html__( 'Use Custom Alt Text for all images?*', "bulk-image-alt-text-with-yoast" ); ?></label></p>

    <div class="bialty-switch-radio">

    <input type="radio" id="use_bialty_alt_btn1" name="use_bialty_alt" value="use_bialty_alt_yes" <?php if ( isset( $use_bialty_alt ) ) echo 'checked="checked"'; ?> />
        <label for="use_bialty_alt_btn1"><?php echo esc_html__( 'Yes', "bulk-image-alt-text-with-yoast" ); ?></label>

        <input type="radio" id="use_bialty_alt_btn2" name="use_bialty_alt" value="use_bialty_alt_no" <?php if ( empty( $use_bialty_alt ) ) echo 'checked="checked"'; ?> />
        <label for="use_bialty_alt_btn2"><?php echo esc_html__( 'No', "bulk-image-alt-text-with-yoast" ); ?></label> 

    </div>

    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="bialty_text"><?php echo  esc_html__( 'Insert your custom Alt text (other than Yoast Focus Keyword + Page title)', "bulk-image-alt-text-with-yoast" ) ;?></label></p>

    <input type="text" name="bialty_cs_alt" value="<?php if ( !empty($bialty_cs_alt) ) echo $bialty_cs_alt; ?>">

    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="bialty_text"><?php echo  esc_html__( 'Disable Bialty?', "bulk-image-alt-text-with-yoast" ); ?></label></p>

    <div class="bialty-switch-radio">

        <input type="radio" id="disable_bialty_btn1" name="disable_bialty" value="disable_bialty_yes" <?php if ( isset( $disable_bialty ) ) echo 'checked="checked"'; ?> />
        <label for="disable_bialty_btn1"><?php echo esc_html__( 'Yes', "bulk-image-alt-text-with-yoast" ); ?></label>

        <input type="radio" id="disable_bialty_btn2" name="disable_bialty" value="disable_bialty_no" <?php if ( empty( $disable_bialty ) ) echo 'checked="checked"'; ?> />
        <label for="disable_bialty_btn2"><?php echo esc_html__( 'No', "bulk-image-alt-text-with-yoast" ); ?></label>  

    </div>

    <p style="margin-top: 20px;"><?php echo  esc_html__( '*If NO, default Bialty settings will be applied', "bulk-image-alt-text-with-yoast" ); ?></p>

</div>