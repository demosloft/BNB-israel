<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <?php   wp_nonce_field( 'wpestate_search_form', 'wpestate_search_form_nonce' ); ?>
    <input type="text" class="form-control" name="s" id="s" placeholder="<?php esc_html_e( 'Search', 'wprentals' ); ?>" />
    <button class="search_form_but"> <i class="fas fa-search"></i> </button>
    <?php
    if (function_exists('icl_translate') ){
        print do_action( 'wpml_add_language_form_field' );
    }
    ?>
</form>
