<?php use Pagup\Bialty\Core\Option; ?>

<div class="bialty-row">

    <div class="bialty-column col-4">
        <span class="bialty-label"><?php 
    echo  __( 'Looking for FREE unlimited content for SEO?', 'bialty' ) ;
    ?></span>
    </div>
    
    <div class="bialty-column col-8">
        
    <label class="bialty-switch bialty-vidseo-label">
        <input type="checkbox" id="promo_vidseo" name="promo_vidseo" value="promo" 
        <?php if ( 
            Option::check('promo_vidseo') ) { echo  'checked'; } 
        ?> />
        <span class="bialty-slider bialty-round"></span>
    </label>

        &nbsp; <span><?php 
    echo  __( 'Get access to billions of non-indexed content with Video transcripts (Youtube)', 'bialty' ) ;
    ?></span>
        
        <div class="bialty-vidseo" <?php if ( Option::check('promo_vidseo') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>

            <div class="bialty-alert bialty-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to learn more about <a href="%2s" target="_blank">VidSEO</a> Wordpress plugin & how to skyrocket your SEO', 'bialty' ), array( 
                    'a' => array( 
                        'href' => array(), 
                        'target' => array(), 
                    ), 
                    'a' => array( 
                        'href' => array(), 
                        'target' => array(), 
                    ),
                ) ), esc_url( "https://wordpress.org/plugins/vidseo/" ), esc_url( "https://wordpress.org/plugins/vidseo/" ) ); ?>
            </div>
        </div>
    </div>
    
</div>