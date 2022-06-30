<?php
global $favorite_class;
global $favorite_text;
?>
<div class="fav_wrapper">
    <div id="add_favorites" class=" <?php print esc_attr($favorite_class);?>" data-postid="<?php the_ID();?>">
        <?php print esc_html($favorite_text);?>
    </div>                 
</div>              