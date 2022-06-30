<?php
if(isset($userid_agent) && intval($userid_agent)!=0) {
    print '<a href="'.esc_url ( get_permalink($userid_agent) ).'" target="_blank" > '. esc_html($author).' </a>';
}else{
    print esc_html($author);
}
?>
