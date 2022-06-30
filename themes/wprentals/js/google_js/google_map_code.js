/*global google, Modernizr, InfoBox, wprentals_map_general_start_map,wprentals_map_general_set_markers,googlecode_regular_vars, document, window, setOms, OverlappingMarkerSpiderfier, mapfunctions_vars, jQuery, googlecode_regular_vars2, wprentals_google_map_cluster, wprentals_google_setMarkers*/
var gmarkers = [];
var leafletMarkers;
var current_place = 0;
var actions = [];
var categories = [];
var vertical_pan = -190;
var map_open = 0;
var vertical_off = 150;
var pins = '';
var markers = '';
var infoBox = null;
var category = null;
var width_browser = null;
var infobox_width = null;
var wraper_height = null;
var info_image = null;
var map;
var found_id;
var selected_id = '';
var javamap;
var oms;
var external_action_ondemand=0;
var is_fit_bounds_zoom=0;
var map_geo_first_load=0;
var markers_cluster;
var is_drag_end=0;
var is_zoom_end=0;
var map_is_moved=0;
var map_is_pan=0;




function wprentals_initialize_map() {
    "use strict";    
    if( !document.getElementById('googleMap') && !document.getElementById('google_map_prop_list') && !document.getElementById('google_map_on_list') ) {
        return;
    } 

    wprentals_map_general_start_map();

    // reading markers from original google_regular_vars
    if(googlecode_regular_vars.generated_pins === '0') {
        pins = googlecode_regular_vars.markers;
        markers = jQuery.parseJSON(pins);
    }else {     
        if (typeof (googlecode_regular_vars2.markers2) !== 'undefined' && googlecode_regular_vars2.markers2.length > 0) {
            pins = googlecode_regular_vars2.markers2;
            markers = jQuery.parseJSON(pins);
        }
    }

    // if we have markers start adding
    if (markers.length > 0) {
        wprentals_map_general_set_markers(map, markers);
    }

    //set map cluster
     wprentals_map_general_cluster();
 
 
    //fit bounds
    wprentals_map_general_fit_to_bounds();
    
    //spider
    wprentals_map_general_spiderfy();
    
    //pan movie
    wprentals_map_general_map_pan_move();


}

function wpestate_ondenamd_map_moved_leaflet(){
    "use strict";
    if(  map_geo_first_load===1 && is_fit_bounds_zoom===0 && external_action_ondemand==0){
        if(lealet_map_move_on_hover==0){
            map_is_moved=1;
            map_is_pan=1;
            wpestate_reload_pins_onmap(1);
        }
    }
    map_geo_first_load=1;
    external_action_ondemand=0; 
    lealet_map_move_on_hover=0;
  
    return;
}



function wpestate_ondenamd_map_moved(){
    "use strict";

    if(  map_geo_first_load===1 && is_fit_bounds_zoom===0 && external_action_ondemand==0){
        map_is_moved=1;
        map_is_pan=1;
        wpestate_reload_pins_onmap(1);
    }
    map_geo_first_load=1;
    external_action_ondemand=0; 

    return;
}



///////////////////////////////// end initialize
/////////////////////////////////////////////////////////////////////////////////// 


if (typeof google === 'object' && typeof google.maps === 'object') {
    google.maps.event.addDomListener(window, 'load', wprentals_initialize_map);
}else{
    wprentals_initialize_map();
}




function wpestate_get_coordinates(container,newpage,NE,SW){
    "use strict";
    var return_array=[];
    var container_id = "#"+container;
    
    if( newpage == 1 || newpage == '1' ){
       
    
        if(page_tracker===0){
         
            return_array['ne_lat']  =   NE.lat();
            return_array['ne_long'] =   NE.lng();
            return_array['sw_lat'] =   SW.lat();
            return_array['sv_long'] =   SW.lng();
            jQuery(container_id).attr('data-ne_lat',return_array['ne_lat']);
            jQuery(container_id).attr('data-ne_long',return_array['ne_long']);
            jQuery(container_id).attr('data-sw_lat',  return_array['sw_lat'] );
            jQuery(container_id).attr('data-sv_long', return_array['sv_long']);
        }else{
         
            return_array['ne_lat']  =   jQuery(container_id).attr('data-ne_lat');
            return_array['ne_long'] =   jQuery(container_id).attr('data-ne_long');
            return_array['sw_lat']  =   jQuery(container_id).attr('data-sw_lat' );
            return_array['sv_long'] =   jQuery(container_id).attr('data-sv_long');
            
            
            page_tracker=0;
            
            
            
            
        }
    }else{
       
        return_array['ne_lat']  =   jQuery(container_id).attr('data-ne_lat');
        return_array['ne_long'] =   jQuery(container_id).attr('data-ne_long');
        return_array['sw_lat']  =   jQuery(container_id).attr('data-sw_lat' );
        return_array['sv_long'] =   jQuery(container_id).attr('data-sv_long');
        page_tracker=1;
     

        if(    return_array['ne_lat']  === undefined ||    return_array['ne_lat']  === null){
            return_array['ne_lat']  =   NE.lat();
            return_array['ne_long'] =   NE.lng();
            return_array['sw_lat'] =   SW.lat();
            return_array['sv_long'] =   SW.lng();
          
        }

            
        jQuery(container_id).attr('data_page_tracker',page_tracker);
    }
    
    return return_array;

}

function wpestate_reload_pins_onmap(newpage){
    "use strict";
   
    if(wprentals_map_type===1){
        var curentbounds = map.getBounds();

        var NE = curentbounds.getNorthEast();
        var SW = curentbounds.getSouthWest();

        if (document.getElementById('google_map_prop_list')) {
            var coordinates_array=wpestate_get_coordinates('google_map_prop_list',newpage,NE,SW);
            wpestate_start_filtering_ajax_map(newpage,coordinates_array['ne_lat'], coordinates_array['ne_long'],  coordinates_array['sw_lat'],coordinates_array['sv_long'],1);
        }else if(document.getElementById('googleMap')){
            var coordinates_array=wpestate_get_coordinates('googleMap',newpage,NE,SW);
            wpestate_start_filtering_ajax_map(newpage,coordinates_array['ne_lat'], coordinates_array['ne_long'],  coordinates_array['sw_lat'],coordinates_array['sv_long'],1);
        }
        
    } else if(wprentals_map_type===2){

        if (document.getElementById('google_map_prop_list')) {
            var coordinates_array=wpestate_get_coordinates_leaflet('google_map_prop_list',newpage);
            wpestate_start_filtering_ajax_map(newpage,coordinates_array['ne_lat'], coordinates_array['ne_long'],  coordinates_array['sw_lat'],coordinates_array['sv_long'],1);
        }else if(document.getElementById('googleMap')){
            var coordinates_array=wpestate_get_coordinates('googleMap',newpage);
            wpestate_start_filtering_ajax_map(newpage,coordinates_array['ne_lat'], coordinates_array['ne_long'],  coordinates_array['sw_lat'],coordinates_array['sv_long'],1);
        }
        
    }
}


function wpestate_get_coordinates_leaflet(container,newpage){
    "use strict";
    var return_array=[];
    var container_id = "#"+container;
    
    if( newpage == 1 || newpage == '1' ){
        var curentbounds = map.getBounds();
      
        if(page_tracker===0){
         
            return_array['ne_lat']  =   curentbounds.getNorth();
            return_array['ne_long'] =   curentbounds.getEast();
            return_array['sw_lat']  =   curentbounds.getSouth();
            return_array['sv_long'] =   curentbounds.getWest();
            jQuery(container_id).attr('data-ne_lat',return_array['ne_lat']);
            jQuery(container_id).attr('data-ne_long',return_array['ne_long']);
            jQuery(container_id).attr('data-sw_lat',  return_array['sw_lat'] );
            jQuery(container_id).attr('data-sv_long', return_array['sv_long']);
        }else{
         
            return_array['ne_lat']  =   jQuery(container_id).attr('data-ne_lat');
            return_array['ne_long'] =   jQuery(container_id).attr('data-ne_long');
            return_array['sw_lat']  =   jQuery(container_id).attr('data-sw_lat' );
            return_array['sv_long'] =   jQuery(container_id).attr('data-sv_long');
            
            
            page_tracker=0;
            
            
            
            
        }
    }else{
       
        return_array['ne_lat']  =   jQuery(container_id).attr('data-ne_lat');
        return_array['ne_long'] =   jQuery(container_id).attr('data-ne_long');
        return_array['sw_lat']  =   jQuery(container_id).attr('data-sw_lat' );
        return_array['sv_long'] =   jQuery(container_id).attr('data-sv_long');
        page_tracker=1;
     

        if( return_array['ne_lat']  === undefined ||    return_array['ne_lat']  === null){
            return_array['ne_lat']  =   curentbounds.getNorth();
            return_array['ne_long'] =   curentbounds.getEast();
            return_array['sw_lat']  =   curentbounds.getSouth();
            return_array['sv_long'] =   curentbounds.getWest();
        }

            
        jQuery(container_id).attr('data_page_tracker',page_tracker);
    }
    
    return return_array;

}
