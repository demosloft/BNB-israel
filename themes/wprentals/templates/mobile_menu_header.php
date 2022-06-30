<?php
$logo= wprentals_get_option('wp_estate_logo_image', 'url');

?>
<div class="mobile_header <?php print esc_attr($wpestate_is_top_bar_class);?>  mobile_header_sticky_<?php   echo esc_attr( wprentals_get_option('wp_estate_mobile_sticky_header') ) ?>">
    <div class="mobile-trigger"><i class="fas fa-bars"></i></div>
    <div class="mobile-logo">
        <a href="<?php echo esc_url( home_url('','login'));?>">
        <?php
            $mobilelogo              =   esc_html( wprentals_get_option('wp_estate_mobile_logo_image','url') );
            if ( $mobilelogo!='' ){
               print '<img src="'.esc_url($mobilelogo).'" class="img-responsive retina_ready" alt="'.esc_html__('logo','wprentals').'"/>';
            } else {
               print '<img class="img-responsive retina_ready" src="'. get_template_directory_uri().'/img/logo.png" alt="'.esc_html__('logo','wprentals').'"/>';
            }
        ?>
        </a>
    </div>
    <?php
    if (esc_html(wprentals_get_option('wp_estate_show_top_bar_user_login', '')) == "yes") {
    ?>
        <div class="mobile-trigger-user"><i class="fas fa-user-circle"></i></div>
    <?php } ?>
</div>
