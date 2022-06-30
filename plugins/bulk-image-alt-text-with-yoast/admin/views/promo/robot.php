<?php use Pagup\Bialty\Core\Option; ?>

<div class="bialty-row">

    <div class="bialty-column col-4">
        <span class="bialty-label"><?php 
    echo  __( 'Boost your ranking on Search engines', 'bialty' ) ;
    ?></span>
    </div>
    
    <div class="bialty-column col-8">
        
        <label class="bialty-switch bialty-boost-label">
            <input type="checkbox" id="promo_robot" name="promo_robot" value="promo" 
            <?php 
                if ( Option::check('promo_robot') ) { echo  'checked' ;  }
            ?> />
            <span class="bialty-slider bialty-round"></span>
        </label>

        &nbsp; <span><?php 
        echo  __( 'Optimize site\'s crawlability with an optimized robots.txt', 'bialty' ) ;
        ?></span>
            
            <div class="bialty-boost" <?php if ( Option::check('promo_robot') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>
                <div class="bialty-alert bialty-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">Better Robots.txt plugin</a> to boost your robots.txt', 'bialty' ), array( 
                    'a' => array( 
                        'href' => array(), 
                        'target' => array(), 
                    ),
                    'a' => array( 
                        'href' => array(), 
                        'target' => array(), 
                    ),
                    ) ), esc_url( "https://wordpress.org/plugins/bialty/" ), esc_url( "https://wordpress.org/plugins/bialty/" ) ); ?>
                </div>
            </div>
        
    </div>
    
</div>