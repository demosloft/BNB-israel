<div class="property_dashboard_location_wrapper">
  <h4 class="listing_title">
      <a href="<?php print esc_url($link); ?>">
      <?php

      $title=get_the_title();
      echo ( html_entity_decode( $title ));

      ?>
      </a>
  </h4>


  <div class="user_dashboard_listed">
       <?php esc_html_e('Listed in','wprentals');?>
       <?php print trim($property_action_category); ?>
       <?php if( $property_action_category!='') {
               print' '.esc_html__( 'and','wprentals').' ';
             }
             print trim($property_category);
       ?>
  </div>

  <div class="user_dashboard_listed">
       <?php print esc_html__( 'City','wprentals').': ';?>
       <?php print get_the_term_list($post_id, 'property_city', '', ', ', '');?>
       <?php print ', '.esc_html__( 'Area','wprentals').': '?>
       <?php print get_the_term_list($post_id, 'property_area', '', ', ', '');?>
  </div>
</div>
