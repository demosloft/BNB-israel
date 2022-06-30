<?php
global $wpestate_curent_fav;
global $wpestate_currency;
global $wpestate_where_currency;
global $show_compare;
global $wpestate_show_compare_only;
global $show_remove_fav;
global $wpestate_options;
global $isdashabord;
global $align;
global $align_class;
global $is_shortcode;
global $is_widget;
global $wpestate_row_number_col;
global $wpestate_full_page;
global $wpestate_listing_type;
global $wpestate_property_unit_slider;
global $wpestate_book_from;
global $wpestate_book_to;
global $wpestate_guest_no;
global $post;
$booking_type       =   wprentals_return_booking_type($post->ID);
$rental_type        =   wprentals_get_option('wp_estate_item_rental_type');
$wpestate_listing_type    =   wprentals_get_option('wp_estate_listing_unit_type','');
$pinterest          =   '';
$previe             =   '';
$compare            =   '';
$extra              =   '';
$property_size      =   '';
$property_bathrooms =   '';
$property_rooms     =   '';
$measure_sys        =   '';

$col_class  =   'col-md-6';
$col_org    =   4;
$title=get_the_title($post->ID);

if(isset($is_shortcode) && $is_shortcode==1 ){
    $col_class='col-md-'.esc_attr($wpestate_row_number_col).' shortcode-col';
}

if(isset($is_widget) && $is_widget==1 ){
    $col_class='col-md-12';
    $col_org    =   12;
}

if(isset($wpestate_full_page) && $wpestate_full_page==1 ){
    $col_class='col-md-4 ';
    $col_org    =   3;
}

$link           =   esc_url ( get_permalink());
$preview        =   array();
$preview[0]     =   '';
$favorite_class =   'icon-fav-off';
$fav_mes        =   esc_html__( 'add to favorites','wprentals');
if($wpestate_curent_fav){
    if ( in_array ($post->ID,$wpestate_curent_fav) ){
    $favorite_class =   'icon-fav-on';
    $fav_mes        =   esc_html__( 'remove from favorites','wprentals');
    }
}

$listing_type_class='property_unit_v2';
if($wpestate_listing_type==1){
    $listing_type_class='';
}else if($wpestate_listing_type==3){
    $listing_type_class=' property_unit_type_3 ';
}


$link                       =  esc_url ( get_permalink());
$wprentals_is_per_hour      =   wprentals_return_booking_type($post->ID);
$link                       =   wprentals_card_link_autocomplete($post->ID,$link,$wprentals_is_per_hour);


global $schema_flag;
if( $schema_flag==1) {
   $schema_data='itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ';
}else{
   $schema_data=' itemscope itemtype="http://schema.org/Product" ';
}
?>

<div  <?php print trim($schema_data);?>  class="listing_wrapper col-md-12 wide_property property_flex <?php print esc_attr($listing_type_class);?>" data-org="<?php print esc_attr($col_org);?>" data-listid="<?php print esc_attr($post->ID);?>" >
    <?php if( $schema_flag==1) {?>
        <meta itemprop="position" content="<?php print esc_html($prop_selection->current_post);?>" />
    <?php } ?>

    <div class="property_listing " >
        <?php
            $featured                 =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
            $price                    =   intval( get_post_meta($post->ID, 'property_price', true) );
            $property_city            =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area            =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
            $property_action          =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');
            $property_categ           =   get_the_term_list($post->ID, 'property_category', '', ', ', '');

            ?>


            <?php wpestate_print_property_unit_slider($post->ID,$wpestate_property_unit_slider,$wpestate_listing_type,$wpestate_currency,$wpestate_where_currency,$link,''); ?>

            <?php
            if($featured==1){
                print '<div class="featured_div">'.esc_html__( 'featured','wprentals').'</div>';
            }

            echo wpestate_return_property_status($post->ID);

            ?>


            <?php

            if($wpestate_listing_type!=3){ ?>
                <div class="title-container">

                    <?php echo wprentals_card_owner_image($post->ID); ?>

                    <?php
                    if(wpestate_has_some_review($post->ID)!==0){
                        print wpestate_display_property_rating( $post->ID );
                    }else{
                        print '  <div class="rating_placeholder"> </div>';
                    }
                    ?>

                    <div class="category_name">
                        <?php   include(locate_template('templates/property_card_templates/property_card_title.php'));   ?>

                        <div class="listing_content">
                           <?php print wpestate_strip_words( get_the_excerpt(),15).' ...'; ?>
                        </div>

                    </div>
                </div>


                <div class="category_tagline_wrapper">
                <div class="category_tagline map_icon">
                    <?php
                    if ($property_area != '') {
                        print trim($property_area).', ';
                    }
                    print trim($property_city); //escaped abvove
                    ?>
                </div>

                <div class="category_tagline actions_icon">
                    <?php print wp_kses_post($property_categ.' / '.$property_action);//escaped above ?>
                </div>


                <div class="property_unit_action">
                    <span class="icon-fav <?php print esc_attr($favorite_class); ?>" data-original-title="<?php print esc_attr($fav_mes); ?>" data-postid="<?php print intval($post->ID); ?>"><i class="fas fa-heart"></i></span>
                </div>
            </div>
            <?php }else{ ?>

                <div class="title-container">

                <?php
                if(wpestate_has_some_review($post->ID)!==0){
                    print wpestate_display_property_rating( $post->ID );
                }else{
                    print '<div class=rating_placeholder></div>';
                }
                ?>

                <div class="category_name">
                  <?php   include(locate_template('templates/property_card_templates/property_card_title.php'));   ?>



                    <div class="category_tagline actions_icon">
                        <?php print trim($property_categ.' / '.$property_action);?>
                    </div>

                    <div class="category_tagline">
                       <?php
                        $options_array=array(
                            0   =>  esc_html__('Single Fee','wprentals'),
                            1   =>  wpestate_show_labels('per_night',$rental_type,$booking_type),
                            2   =>  esc_html__('Per Guest','wprentals'),
                            3   =>  wpestate_show_labels('per_night_per_guest',$rental_type,$booking_type)
                        );


                       $custom_listing_fields = wprentals_get_option( 'wp_estate_custom_listing_fields');

                       foreach ($custom_listing_fields as $field){
                            if($field[2]!='none'){

                                if( $field[2]=='property_category' || $field[2]=='property_action_category' ||  $field[2]=='property_city' ||  $field[2]=='property_area' ){
                                    $value=   get_the_term_list($post->ID, $field[2], '', ', ', '');
                                }else{

                                    $slug       =   wpestate_limit45(sanitize_title( $field[2] ));
                                    $slug       =   sanitize_key($slug);
                                    $value      =   esc_html(get_post_meta($post->ID, $slug, true));

                                }

                                if($value!=''){
                                    print '<div class="custom_listing_data">';
                                    if($field[0]!=''){
                                        print '<span class="custom_listing_data_label">'.stripslashes(esc_html($field[0])).':</span>';
                                    }else{
                                        if($field[1]!=''){
                                            print '<i class="'.esc_attr($field[1]).'"></i>';
                                        }
                                    }


                                    $price_items =array('property_price','city_fee','cleaning_fee','price_per_weekeend','property_price_per_week','property_price_per_month','extra_price_per_guest','security_deposit');

                                    if( $value!=0 && in_array($field[2], $price_items) ){
                                        if( $field[2]=='property_price'){
                                            print get_post_meta($post->ID, 'property_price_before_label', true).' ';
                                        }
                                        print wpestate_show_price_booking($value,$wpestate_currency,$wpestate_where_currency,1);
                                        if( $field[2]=='cleaning_fee' ){
                                            $cleaning_fee_per_day           =   floatval  ( get_post_meta($post->ID,  'cleaning_fee_per_day', true) );
                                            print ' '. trim($options_array[ intval($cleaning_fee_per_day) ]);
                                        }

                                        if(   $field[2]=='city_fee' ){
                                            $city_fee_per_day      =   floatval  ( get_post_meta($post->ID,  'city_fee_per_day', true) );
                                            print ' '.trim($options_array[ intval($city_fee_per_day) ]);
                                        }

                                        if( $field[2]=='property_price'){
                                            print ' '.get_post_meta($post->ID, 'property_price_after_label', true);
                                        }
                                    }else if( $field[2]=='property_size'){

                                        $measure_sys    =   esc_html (wprentals_get_option('wp_estate_measure_sys',''));
                                        if(is_numeric($value)){
                                            print number_format(floatval($value)) . ' '.esc_html($measure_sys).'<sup>2</sup>';
                                        }
                                    }else if( $field[2]=='property_taxes'){
                                        print '%';
                                    }else{
                                        print trim($value);
                                    }

                                    print '</div>';
                                }
                            }
                       }

                    ?>
                    </div>
                </div>


            </div>
            <?php } ?>
        <?php

        if ( isset($show_remove_fav) && $show_remove_fav==1 ) {
            print '<span class="icon-fav icon-fav-on-remove" data-postid="'.intval($post->ID).'"> '.esc_html($fav_mes).'</span>';
        }
        ?>

        </div>
    </div>
