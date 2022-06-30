/*global google,  Modernizr, InfoBox,wpestate_initialize_poi,mapbase_vars, window,curent_gview_long,wprentals_map_general_spiderfy,curent_gview_lat,wprentals_map_general_cluster,wprentals_map_general_set_markers,wprentals_map_general_start_map, googlecode_property_vars, document, jQuery, control_vars, setOms, wprentals_google_map_cluster, oms, OverlappingMarkerSpiderfier, wprentals_google_setMarkers, googlecode_regular_vars2, wprentals_google_setMarkers_contact, mapfunctions_vars, wpestate_close_adv_search, show_advanced_search*/
var gmarkers = [];
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
var selected_id = jQuery('#gmap_wrapper').attr('data-post_id');
var heading = 0;
var panorama;
var oms;
var map_intern = 0;
var external_action_ondemand=0;
var is_fit_bounds_zoom=0;
var map_geo_first_load=0;
var markers_cluster;
var is_drag_end=0;
var is_zoom_end=0;

function wprentals_initialize_map() {
    "use strict";
    var viewPlace, mapOptions, mapOptions_intern, styles;
   
    wprentals_map_general_start_map('prop');
  

   
    if (map_intern === 0) {
        ///////////////////////////////////////////////////////////////// header map
        if (googlecode_property_vars.generated_pins === '0') {
            pins = googlecode_property_vars.markers;
            markers = jQuery.parseJSON(pins);
        } else {
            if (typeof (googlecode_regular_vars2) !== 'undefined' && googlecode_regular_vars2.markers2.length > 2) {
                pins = googlecode_regular_vars2.markers2;
                markers = jQuery.parseJSON(pins);
            }
        }


        if (markers.length > 0) {
            wprentals_map_general_set_markers(map, markers);
        }
 
       
         wprentals_map_general_cluster();
        ///////////////////////////////////////////////////////////// end header map       
    } else {
        /////////////////////////////////////////////////////////////////  listing map

        pins = googlecode_property_vars.single_marker;
        markers = jQuery.parseJSON(pins);



        if (markers.length > 0) {
            wprentals_map_general_set_markers(map, markers);
        }
        curent_gview_lat = jQuery('#google_map_on_list').attr('data-cur_lat');
        curent_gview_long = jQuery('#google_map_on_list').attr('data-cur_long');
        
        if(parseInt(mapbase_vars.wprentals_map_type)===1){
            google.maps.event.trigger(gmarkers[0], 'click');
        }else{
            gmarkers[0].addTo( map );
            map.panTo(gmarkers[0].getLatLng());
            if( mapfunctions_vars.hidden_map ){
                gmarkers[0].fire('click').openPopup();
            }
          
        }


    }



    function scrollwhel(event) {
        if (map.scrollwheel === true) {
            event.stopPropagation();
        }
    }

    wprentals_map_general_spiderfy();
    
    wpestate_initialize_poi(map, 1);

}
///////////////////////////////// end initialize
/////////////////////////////////////////////////////////////////////////////////// 


if (typeof google === 'object' && typeof google.maps === 'object') {
      google.maps.event.addDomListener(window, 'load', wprentals_initialize_map);
}else{
    wprentals_initialize_map();
}

function wpestate_toggleStreetView() {
    "use strict";
    var curent_gview_lat = jQuery('#google_map_on_list').attr('data-cur_lat');
    var curent_gview_long = jQuery('#google_map_on_list').attr('data-cur_long');
    var  viewPlace = new google.maps.LatLng(curent_gview_lat, curent_gview_long);
    panorama = map.getStreetView();
    panorama.setPosition(viewPlace);
    heading = parseInt(googlecode_property_vars.camera_angle, 10);

    panorama.setPov(({
        heading: heading,
        pitch: 0
    }));
    
    panorama.addListener('visible_changed', function() {
        if (!panorama.visible) {
            jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus,#gmapstreet').show();
            jQuery('#street-view').removeClass('mapcontrolon');
            jQuery('#street-view').html('<i class="fa fa-location-arrow"></i> ' + control_vars.street_view_on);
        }
    });


    if (panorama.visible) {
        panorama.setVisible(false);
        jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus,#gmapstreet').show();
        jQuery('#street-view').removeClass('mapcontrolon');
        jQuery('#street-view').html('<i class="fa fa-location-arrow"></i> ' + control_vars.street_view_on);
    } else {
        panorama.setVisible(true);
        jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus,#gmapstreet').hide();
        jQuery('#street-view').addClass('mapcontrolon');
        jQuery('#street-view').html('<i class="fa fa-location-arrow"></i> ' + control_vars.street_view_off);
    }
}