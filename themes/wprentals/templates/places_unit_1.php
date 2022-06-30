<?php

$place_id                     = intval($place_id);
$category_attach_id           = '';
$category_tax                 = '';
$category_featured_image      = '';
$category_name                = '';
$category_featured_image_url  = '';
$term_meta                    = get_option( "taxonomy_$place_id");
$category_tagline             = '';

if(isset($term_meta['category_featured_image'])){
    $category_featured_image=$term_meta['category_featured_image'];
}

if(isset($term_meta['category_attach_id'])){
    $category_attach_id=$term_meta['category_attach_id'];
    $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'wpestate_property_featured');
    $category_featured_image_url=$category_featured_image[0];
}

if(isset($term_meta['category_tax'])){
    $category_tax=$term_meta['category_tax'];
    $term= get_term( $place_id, $category_tax);
    if( isset($term->name) ){
        $category_name=$term->name;
        $category_count = $term->count;
    }

}

 if(isset($term_meta['category_tagline'])){
    $category_tagline=$term_meta['category_tagline'];
}

$term_link =  get_term_link( $place_id, $category_tax );
if ( is_wp_error( $term_link ) ) {
    $term_link='';
}


if($category_featured_image_url==''){
    $category_featured_image_url=get_template_directory_uri().'/img/defaultimage.jpg';
}

    $places_style="";

print '<div class="places_wrapper  '.$type_class.' "   data-link="'.$term_link.'"><div class="listing-hover-gradient"></div><div class="listing-hover"></div>';
print '<div class="places1 featuredplace" style="background-image:url('.$category_featured_image_url.');'.$places_style.'"></div>';

print '<div class="category_name">';
if ( $place_type==1 ){
    print'<div class="featured_place_count">'.$category_count .' '.__('Listings','wprentals-core' ).'</div>';
}
print'<a class="featured_listing_title" href="'.$term_link.'">'.stripslashes($category_name).'</a>';

print '<div class="category_tagline">'.stripslashes($category_tagline).'</div>';

    if ( $place_type==3 ){
        print'<div class="featured_place_count">'.$category_count .' '.__('Listings','wprentals-core' ).'</div>';
        print'<div class="featured_more"><a href="' . $term_link . '">' . __('Discover', 'wprentals-core') . '</a> <i class="fas fa-chevron-right"></i></div>';
    }

print '</div>';



print '';
print'</div>';
