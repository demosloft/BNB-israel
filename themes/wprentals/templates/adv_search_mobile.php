<div id="adv-search-header-mobile">
    <?php esc_html_e('Advanced Search','wprentals');?>
</div>

<div class="adv-search-mobile"  id="adv-search-mobile">
    <?php
    global $search_object;
    print  trim($search_object->wpstate_display_search_form('mobile'));
    include(locate_template('libs/internal_autocomplete_wpestate.php'));

    ?>
</div>
<?php include(locate_template('libs/internal_autocomplete_wpestate.php')); ?>
