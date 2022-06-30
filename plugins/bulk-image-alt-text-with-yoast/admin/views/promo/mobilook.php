<?php use Pagup\Bialty\Core\Option; ?>

<div class="bialty-row">
    <div class="bialty-column col-4">
        <span class="bialty-label"><?php 
    echo  __( 'Mobile-Friendly & responsive design', 'bialty' ) ;
    ?></span>
    </div>
    
    <div class="bialty-column col-8">
        
    <label class="bialty-switch bialty-mobi-label">
        <input type="checkbox" id="promo_mobilook" name="promo_mobilook" value="promo" 
        <?php 
        if ( Option::check('promo_mobilook') ) { echo  'checked' ;  }
        ?> />
        <span class="bialty-slider bialty-round"></span>
    </label>

        &nbsp; <span><?php 
    echo  __( 'Get dynamic mobile previews of your pages/posts/products + Facebook debugger', 'bialty' ) ;
    ?></span>
        
        <div class="bialty-mobi" <?php if ( Option::check('promo_mobilook') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>

            <div class="bialty-alert bialty-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">Mobilook</a> and test your website on Dualscreen format (Galaxy fold)', 'bialty' ), array( 
                    'a' => array( 
                        'href' => array(), 
                        'target' => array(), 
                    ), 
                    'a' => array( 
                        'href' => array(), 
                        'target' => array(), 
                    ),
                ) ), esc_url( "https://wordpress.org/plugins/mobilook/" ), esc_url( "https://wordpress.org/plugins/mobilook/" ) ); ?>
            </div>
        </div>
    </div>
    
</div>