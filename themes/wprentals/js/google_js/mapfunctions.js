/*global google, $, Modernizr, InfoBox, window, alert, setTimeout, mapbase_vars,wpestate_pan_to_last_pin, alert, wprentals_showMyPosition, errorCallback, wpestate_new_show_advanced_search, MarkerClusterer, navigator, wpestate_new_hide_advanced_search, adv_search_click, wpestate_createMarker, infoBox, map, gmarkers, bounds_list, wpestate_new_open_close_map, custompin,googlecode_property_vars, document, wpestate_placeSavedMarker, wpestate_placeMarker, removeMarkers, googlecode_home_vars, google_map_submit_vars, jQuery, control_vars, setOms, wprentals_google_map_cluster, oms, OverlappingMarkerSpiderfier, wprentals_google_setMarkers, googlecode_regular_vars2, wprentals_google_setMarkers_contact, mapfunctions_vars, wpestate_close_adv_search, show_advanced_search*/
var page_tracker=   0;
var pin_images  =   mapfunctions_vars.pin_images;
var images      =   jQuery.parseJSON(pin_images);
var ipad_time   =   0;
var infobox_id  =   0;
var shape = {
    coord: [1, 1, 1, 38, 38, 59, 59, 1],
    type: 'poly'
};

var mcOptions;
var markers_cluster;
var clusterStyles;
var infoBox;
var infobox_width;
var poi_marker_array=[];
var poi_type='';
var placeCircle='';
var circleLayer='';
var initial_geolocation_circle_flag=0;
var bounds_list;
var curent_gview_lat = jQuery('#gmap_wrapper').attr('data-cur_lat');
var curent_gview_long = jQuery('#gmap_wrapper').attr('data-cur_long');

function wpestate_show_pins_filters_from_file() {
    "use strict";

    if(jQuery("#a_filter_action").length == 0) {
        var action      =   jQuery('#second_filter_action').attr('data-value');
        var category    =   jQuery('#second_filter_categ').attr('data-value');
        var city        =   jQuery('#second_filter_cities').attr('data-value');
        var area        =   jQuery('#second_filter_areas').attr('data-value');
        var county      =   jQuery('#second_filter_county').attr('data-value');

    }else{
        var action      =   jQuery('#a_filter_action').attr('data-value');
        var category    =   jQuery('#a_filter_categ').attr('data-value');
        var city        =   jQuery('#a_filter_cities').attr('data-value');
        var area        =   jQuery('#a_filter_areas').attr('data-value');
        var county      =   jQuery('#a_filter_county').attr('data-value');
    }


    if( typeof(action)!=='undefined'){
        action      = action.toLowerCase().trim().replace(" ", "-");
    }

    if( typeof(action)!=='undefined'){
        category    = category.toLowerCase().trim().replace(" ", "-");
    }

    if( typeof(action)!=='undefined'){
        city        = city.toLowerCase().trim().replace(" ", "-");
    }

    if( typeof(action)!=='undefined'){
        area        = area.toLowerCase().trim().replace(" ", "-");
    }

    if(  typeof infoBox!=='undefined' && infoBox!== null ){
        infoBox.close();
    }


    var bounds = new google.maps.LatLngBounds();

    if(!isNaN(markers_cluster) ){
        markers_cluster.setIgnoreHidden(true);
    }

    if(  typeof gmarkers!=='undefined'){

        for (var i=0; i<gmarkers.length; i++) {

                if( !wpestate_classic_form_tax_visible (gmarkers[i].action, action) && action!='all' && action!='all' && action!='all-sizes' ){
                    gmarkers[i].setVisible(false);

                }else if (!wpestate_classic_form_tax_visible (gmarkers[i].category, category) && category!='all' && category!='all-types') {
                    gmarkers[i].setVisible(false);

                }else if( !wpestate_classic_form_tax_visible (gmarkers[i].area, area) && area!='all'  && area!='all-areas'){
                    gmarkers[i].setVisible(false);

                }else if( !wpestate_classic_form_tax_visible (gmarkers[i].city, city)  && city!='all' && city!='all-cities' ){
                    gmarkers[i].setVisible(false);

                }else{
                    gmarkers[i].setVisible(true);
                    bounds.extend( gmarkers[i].getPosition() );
                }
        }//end for
        if(!isNaN(markers_cluster) ){
            markers_cluster.repaint();
        }
    }//end if

        if( !bounds.isEmpty() ){
            wpestate_fit_bounds(bounds);
        }

}


function wpestate_fit_bounds_nolsit_leaflet(){
    map.setZoom(3);
    is_fit_bounds_zoom=0;
}



function wpestate_fit_bounds_nolsit(){
    map.setZoom(3);
    is_fit_bounds_zoom=0;
}


function wpestate_fit_bounds(bounds){

    is_fit_bounds_zoom=1;

    if(placeCircle!=''){
        map.fitBounds(placeCircle.getBounds());
    }else{
        if(gmarkers.length===1){
            var center = gmarkers[0].getPosition();
            map.setCenter(center);
            map.setZoom(10);

            google.maps.event.addListenerOnce(map, 'idle', function() {
                is_fit_bounds_zoom=0;
            });
        }else{

            map.fitBounds(bounds);
            google.maps.event.addListenerOnce(map, 'idle', function() {
                is_fit_bounds_zoom=0;
            });
        }
    }


}

function wpestate_fit_bounds_leaflet(bounds){

    is_fit_bounds_zoom=1;

    if(placeCircle!=''){
        map.fitBounds(placeCircle.getBounds());// placecircle is google geolocation
    }else{
        if(gmarkers.length===1){
            var center = gmarkers[0].getLatLng();
            map.panTo(center);
            map.setZoom(10);
            is_fit_bounds_zoom=0;

        }else{
            map.fitBounds(bounds);
            is_fit_bounds_zoom=0;
        }
    }


}

 function wpestate_classic_form_tax_visible($onpin,$onreq){
    $onpin = $onpin.toLowerCase();
    $onpin = decodeURI($onpin);
    $onreq = $onreq.toLowerCase();
    $onreq = decodeURI($onreq);
    $onpin = $onpin.split(' ').join('-');
    $onreq = $onreq.split(' ').join('-');
    $onreq = $onreq.split("'").join('');
    $onpin = $onpin.latinise();
    $onreq = $onreq.latinise();


    if($onpin.indexOf($onreq)> -1 ){
        return true;
    } else{
        return false;
    }
 }


/////////////////////////////////////////////////////////////////////////////////////////////////
//  set markers... loading pins over map
/////////////////////////////////////////////////////////////////////////////////////////////////

function wprentals_google_setMarkers(map, locations) {
    "use strict";
    var  custom_info,pin_price,beach, id, lat, lng, title, pin, counter, image, price, single_first_type, single_first_action, link, city, area, cleanprice, rooms, baths, size, single_first_type_name, single_first_action_name, map_open, myLatLng, selected_id, open_height, boxText, closed_height, width_browser, infobox_width, vertical_pan, myOptions,status, i, slug1, val1, how1, slug2, val2, how2, slug3, val3, how3, slug4, val4, how4, slug5, val5, how5, slug6, val6, how6, slug7, val7, how7, slug8, val8, how8;
    selected_id = parseInt(jQuery('#gmap_wrapper').attr('data-post_id'), 10);
    if (isNaN(selected_id)) {
        selected_id = parseInt(jQuery('#google_map_on_list').attr('data-post_id'), 10);
    }

    open_height     = parseInt(mapfunctions_vars.open_height, 10);
    closed_height   = parseInt(mapfunctions_vars.closed_height, 10);
    boxText         = document.createElement("div");
    width_browser   = jQuery(window).width();

    infobox_width = 700;
    vertical_pan = -215;
    if (width_browser < 900) {
        infobox_width = 500;
    }
    if (width_browser < 600) {
        infobox_width = 400;
    }
    if (width_browser < 400) {
        infobox_width = 200;
    }


    myOptions = {
        content: boxText,
        disableAutoPan: true,
        maxWidth: infobox_width,
        boxClass: "mybox",
        zIndex: null,
        closeBoxMargin: "-13px 0px 0px 0px",
        closeBoxURL: "",
        infoBoxClearance: new google.maps.Size(1, 1),
        isHidden: false,
        pane: "floatPane",
        enableEventPropagation: false
    };
    infoBox = new InfoBox(myOptions);

    for (i = 0; i < locations.length; i++) {

        beach                       = locations[i];
        id                          = beach[10];
        lat                         = beach[1];
        lng                         = beach[2];
        title                       = decodeURIComponent(beach[0]);
        pin                         = beach[8];
        counter                     = beach[3];
        image                       = decodeURIComponent(beach[4]);
        price                       = decodeURIComponent(beach[5]);
        single_first_type           = decodeURIComponent(beach[6]);
        single_first_action         = decodeURIComponent(beach[7]);
        link                        = decodeURIComponent(beach[9]);
        city                        = decodeURIComponent(beach[11]);
        area                        = decodeURIComponent(beach[12]);
        cleanprice                  = beach[13];
        rooms                       = beach[14];
        baths                       = beach[15];
        size                        = beach[16];
        single_first_type_name      = decodeURIComponent(beach[17]);
        single_first_action_name    = decodeURIComponent(beach[18]);
        status                      = decodeURIComponent(beach[19]);
        pin_price                   =   decodeURIComponent ( beach[20] );
        custom_info                 =   decodeURIComponent ( beach[21] );


        if (mapfunctions_vars.custom_search === 'yes') {
            i=1;
            slug1 = beach[19+i];
            val1 = beach[20+i];
            how1 = beach[21+i];
            slug2 = beach[22+i];
            val2 = beach[23+i];
            how2 = beach[24+i];
            slug3 = beach[25+i];
            val3 = beach[26+i];
            how3 = beach[27+i];
            slug4 = beach[28+i];
            val4 = beach[29+i];
            how4 = beach[30+i];
            slug5 = beach[31+i];
            val5 = beach[32+i];
            how5 = beach[33+i];
            slug6 = beach[34+i];
            val6 = beach[35+i];
            how6 = beach[36+i];
            slug7 = beach[37+i];
            val7 = beach[38+i];
            how7 = beach[39+i];
            slug8 = beach[40+i];
            val8 = beach[41+i];
            how8 = beach[42+i];
        }

        wpestate_createMarker(pin_price,infobox_width ,size, i, id, lat, lng, pin, title, counter, image, price, single_first_type, single_first_action, link, city, area, rooms, baths, cleanprice, slug1, val1, how1, slug2, val2, how2, slug3, val3, how3, slug4, val4, how4, slug5, val5, how5, slug6, val6, how6, slug7, val7, how7, slug8, val8, how8, single_first_type_name, single_first_action_name,status,custom_info);
        // found the property

        if (selected_id === id) {
            found_id = i;
        }
    }//end for

    // pan to the latest pin for taxonmy and adv search

    if (mapfunctions_vars.generated_pins !== '0') {
        myLatLng = new google.maps.LatLng(lat, lng);

        if(map_is_pan===0){

            wpestate_pan_to_last_pin(myLatLng);
            oms = new OverlappingMarkerSpiderfier(map);
            setOms(gmarkers);
            oms.addListener('spiderfy', function (markers) {
            });
            oms.addListener('unspiderfy ', function (markers) {
            });
        }
        map_is_pan=0;
    }

    if(mapfunctions_vars.is_prop_list==='1' || mapfunctions_vars.is_tax==='1' ){
        wpestate_show_pins_filters_from_file();
    }

}// end wprentals_google_setMarkers


/////////////////////////////////////////////////////////////////////////////////////////////////
//  create marker
/////////////////////////////////////////////////////////////////////////////////////////////////

function wpestate_createMarker(pin_price,infobox_width, size, i, id, lat, lng, pin, title, counter, image, price, single_first_type, single_first_action, link, city, area, rooms, baths, cleanprice, slug1, val1, how1, slug2, val2, how2, slug3, val3, how3, slug4, val4, how4, slug5, val5, how5, slug6, val6, how6, slug7, val7, how7, slug8, val8, how8, single_first_type_name, single_first_action_name,status,custom_info) {
    "use strict";
    var marker, myLatLng;
    var Titlex = jQuery('<textarea />').html(title).text();
    var infobox_class="";
    var poss=0;
    myLatLng = new google.maps.LatLng(lat, lng);
    if(mapfunctions_vars.useprice === 'yes'){
        infobox_class=" pin_price_info ";
        var myLatlng = new google.maps.LatLng(lat,lng);
        marker= new WpstateMarker(
            area,
            city,
            pin_price,
            poss,
            myLatlng,
            map,
            Titlex,
            counter,
            image,
            id,
            price,
            single_first_type,
            single_first_action,
            link,
            i,
            rooms,
            baths,
            cleanprice,
            size,
            single_first_type_name,
            single_first_action_name,
            pin,
            custom_info

        );

    }else{
        infobox_class=" classic_info ";
        if (mapfunctions_vars.custom_search === 'yes') {
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: custompin(pin),
                custompin: pin,
                shape: shape,
                title: decodeURIComponent(title.replace(/\+/g, ' ')),
                zIndex: counter,
                image: image,
                idul: id,
                price: price,
                category: single_first_type,
                action: single_first_action,
                link: link,
                city: city,
                area: area,
                infoWindowIndex: i,
                rooms: rooms,
                guest_no: baths,
                size: size,
                cleanprice: cleanprice,
                category_name: single_first_type_name,
                action_name: single_first_action_name,
                slug1: slug1,
                val1: val1,
                howto1: how1,
                slug2: slug2,
                val2: val2,
                howto2: how2,
                slug3: slug3,
                val3: val3,
                howto3: how3,
                slug4: slug4,
                val4: val4,
                howto4: how4,
                slug5: slug5,
                val5: val5,
                howto5: how5,
                slug6: slug6,
                val6: val6,
                howto6: how7,
                slug7: slug7,
                val7: val7,
                howto7: how7,
                slug8: slug8,
                val8: val8,
                howto8: how8

            });

        }else {
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: custompin(pin),
                custompin: pin,
                shape: shape,
                title: title,
                zIndex: counter,
                image: image,
                idul: id,
                price: price,
                category: single_first_type,
                action: single_first_action,
                link: link,
                city: city,
                area: area,
                infoWindowIndex: i,
                rooms: rooms,
                guest_no: baths,
                cleanprice: cleanprice,
                size: size,
                category_name: single_first_type_name,
                action_name: single_first_action_name,
                status:status,
                custom_info:custom_info
            });
        }
    }

    gmarkers.push(marker);

    if (typeof (bounds_list) !== "undefined") {
        bounds_list.extend(marker.getPosition());
    }


    google.maps.event.addListener(marker, 'click', function (event) {
        var title, info_image, category, action, category_name, action_name, in_type, infoguest, inforooms,  vertical_off, status_html,status;
        wpestate_new_open_close_map(1);
        external_action_ondemand=1;

        if (this.image === '') {
            info_image =  mapfunctions_vars.path + '/idxdefault.jpg';
        } else {
            info_image = this.image;
        }




        if ( typeof(this.status)!='undefined'){
            if( this.status.indexOf('%')!==-1 ){
                status = this.status;
                //(this.status.replace(/-/g, ' '));
            }else{
                status = decodeURIComponent(this.status.replace(/-/g, ' '));
            }
        }else{
            status='';
        }



        category        = decodeURIComponent(this.category.replace(/-/g, ' '));
        action          = decodeURIComponent(this.action.replace(/-/g, ' '));
        category_name   = decodeURIComponent(this.category_name.replace(/-/g, ' '));
        action_name     = decodeURIComponent(this.action_name.replace(/-/g, ' '));

        status_html=wpestate_display_status(status);


        in_type = mapfunctions_vars.in_text;
        if (category === '' || action === '') {
            in_type = " ";
        }
        in_type = " / ";

        if (this.guest_no !== '' &&  this.guest_no !== '0' && this.guest_no !== 0  )  {
            infoguest = '<span id="infoguest">' + this.guest_no + '</span>';
        } else {
            infoguest = '';
        }

        if (this.rooms !== '') {
            inforooms = '<span id="inforoom">' + this.rooms + '</span>';
        } else {
            inforooms = '';
        }

        title = this.title.toString();




        if( this.custom_info!=='undefined'){
            var custom_array=this.custom_info.split(',');
            inforooms = '<span id="inforoom" class="custom_infobox_icon"><i class="' + custom_array[0] + '"></i>' + custom_array[1] + '</span>';
            infoguest = '<span id="infoguest" class="custom_infobox_icon"><i class="' + custom_array[2] + '"></i>' + custom_array[3] + '</span>';

        }



        infoBox.setContent('<div class="info_details '+infobox_class+' "><span id="infocloser" onClick=\'javascript:infoBox.close();\' ></span>'+status_html+'<a href="' + this.link + '"><div class="infogradient"></div><div class="infoimage" style="background-image:url(' + info_image + ')"  ></div></a><a href="' + this.link + '" id="infobox_title"> ' + title + '</a><div class="prop_detailsx">' + category_name + " " + in_type + " " + action_name + '</div><div class="infodetails">' + infoguest + inforooms + '</div><div class="prop_pricex">' + this.price + '</div></div>');

        infoBox.open(map, this);

        map.setCenter(this.position);

        switch (infobox_width) {
            case 700:
                if (!document.getElementById('google_map_on_list')) {
                    if (mapfunctions_vars.listing_map === 'top') {
                        if( document.getElementById('google_map_prop_list') ){
                            map.panBy(0, -100);

                        }else{
                            map.panBy(100, -100);
                        }
                    } else {
                        map.panBy(10, -110);
                    }
                } else {

                    map.panBy(0, -160);
                }
                vertical_off = 0;
                break;
            case 500:
                if( document.getElementById('google_map_prop_list') ){
                    map.panBy(50, -120);
                }else{
                    map.panBy(50, -150);
                }
                break;
            case 400:

                if( document.getElementById('google_map_prop_list') ){
                     map.panBy(100, -220);
                }else{
                    map.panBy(0, -150);
                }
                break;
            case 200:
                map.panBy(20, -170);
                break;
        }

        if (control_vars.show_adv_search_map_close === 'no') {
            $('.search_wrapper').addClass('adv1_close');
            adv_search_click();
        }
        wpestate_close_adv_search();
    });/////////////////////////////////// end event listener

    if (mapfunctions_vars.generated_pins !== '0') {
        if(map_is_pan===0){
            wpestate_pan_to_last_pin(myLatLng);
        }
        map_is_pan=1;
    }
}





function wpestate_pan_to_last_pin(myLatLng) {
    "use strict";
    if(wprentals_map_type===1){
        map.setCenter(myLatLng);
    }else  if(wprentals_map_type===2){
        map.panTo(myLatLng);
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////
//  map set search
/////////////////////////////////////////////////////////////////////////////////////////////////
function setOms(gmarkers) {
    "use strict";
    var i;
    for (i = 0; i < gmarkers.length; i++) {
        if (typeof oms !== 'undefined') {
            oms.addMarker(gmarkers[i]);
        }
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//  open close map
/////////////////////////////////////////////////////////////////////////////////////////////////
function wpestate_new_open_close_map(type) {
    "use strict";
    var current_height, closed_height, open_height, googleMap_h, gmapWrapper_h, vertical_off;
    if (jQuery('#gmap_wrapper').hasClass('fullmap')) {
        return;
    }

    if (mapfunctions_vars.open_close_status !== '1') { // we can resize map

        current_height = jQuery('#googleMap').outerHeight();
        closed_height = parseInt(mapfunctions_vars.closed_height, 10);
        open_height = parseInt(mapfunctions_vars.open_height, 10);
        googleMap_h = open_height;
        gmapWrapper_h = open_height;

        if (infoBox !== null) {
            infoBox.close();
        }

        if (current_height === closed_height) {
            googleMap_h = open_height;
            if (Modernizr.mq('only all and (max-width: 940px)')) {
                gmapWrapper_h = open_height;
            } else {
                jQuery('#gmap-menu').show();
                gmapWrapper_h = open_height;
            }

            wpestate_new_show_advanced_search();
            vertical_off = 0;
            jQuery('#openmap').empty().append('<i class="fas fa-angle-up"></i>' + mapfunctions_vars.close_map);

        } else if (type === 2) {
            jQuery('#gmap-menu').hide();
            jQuery('#advanced_search_map_form').hide();
            googleMap_h = gmapWrapper_h = closed_height;
            // hide_advanced_search();
            wpestate_new_hide_advanced_search();
            vertical_off = 150;
        }

        jQuery('#googleMap').animate({'height': googleMap_h + 'px'});
        jQuery('#gmap_wrapper').animate({'height': gmapWrapper_h + 'px'}, 500, function () {
            wprentals_map_resize();
            if(wprentals_map_type===1){
                map.setOptions({'scrollwheel': true});
            }
            jQuery('#googleMap').addClass('scrollon');
            jQuery('.tooltip').fadeOut("fast");
        });

    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//  build map cluter
/////////////////////////////////////////////////////////////////////////////////////////////////
function wprentals_google_map_cluster() {
    "use strict";
    if (mapfunctions_vars.user_cluster === 'yes') {
        clusterStyles = [
            {
                textColor: '#ffffff',
                opt_textColor: '#ffffff',
                url: images['cloud_pin'],
                height: 72,
                width: 72,
                textSize: 15
            }
        ];
        mcOptions = {gridSize: 50,
            ignoreHidden: true,
            maxZoom: parseInt( mapfunctions_vars.zoom_cluster,10),
            styles: clusterStyles
        };
        markers_cluster = new MarkerClusterer(map, gmarkers, mcOptions);
        markers_cluster.setIgnoreHidden(true);
    }

}


/////////////////////////////////////////////////////////////////////////////////////////////////
/// zoom
/////////////////////////////////////////////////////////////////////////////////////////////////


if (document.getElementById('gmapzoomplus')) {

    jQuery('#gmapzoomplus').on('click',function(){
        "use strict";
        var current = parseInt(map.getZoom(), 10);
        current = current + 1;
        if (current > 20) {
            current = 20;
        }
        map.setZoom(current);
    });

}


if (document.getElementById('gmapzoomminus')) {
      jQuery('#gmapzoomminus').on('click',function(){
        "use strict";
        var current = parseInt(map.getZoom(), 10);
        current = current - 1;
        if (current < 3) {
            current = 3;
        }
        map.setZoom(current);
    });


}





jQuery('#gmapstreet').on('click',function () {
    "use strict";
    wpestate_toggleStreetView();
});

/////////////////////////////////////////////////////////////////////////////////////////////////
/// geolocation
/////////////////////////////////////////////////////////////////////////////////////////////////

if (document.getElementById('geolocation-button')) {
    jQuery('#geolocation-button').on('click',function(){
        "use strict";
        wprentals_myposition(map);
        wpestate_close_adv_search();
    });

}


if (document.getElementById('mobile-geolocation-button')) {
    google.maps.event.addDomListener(document.getElementById('mobile-geolocation-button'), 'click', function () {
        "use strict";
        wprentals_myposition(map);
        wpestate_close_adv_search();
    });
}


jQuery('#mobile-geolocation-button,#geolocation-button').on('click',function () {
    "use strict";
    wprentals_myposition(map);
});



function wprentals_myposition(map){

    if(navigator.geolocation){
        var latLong,protocol;

        protocol = "http:";
        if (location.protocol != 'http:'){
            protocol="https:";
        }

        if (location.protocol === 'https:') {
            navigator.geolocation.getCurrentPosition(wpestate_showMyPosition_original,errorCallback,{timeout:10000});
        }else{
            jQuery.getJSON(protocol+"//ipinfo.io", function(ipinfo){
                latLong = ipinfo.loc.split(",");
                wprentals_showMyPosition (latLong);
            });
        }

    }else{
        alert(mapfunctions_vars.geo_no_brow);
    }
}


function wpestate_showMyPosition_original(pos){
    "use strict";
    if(wprentals_map_type===1){
        var shape = {
           coord: [1, 1, 1, 38, 38, 59, 59 , 1],
           type: 'poly'
        };

        var MyPoint=  new google.maps.LatLng( pos.coords.latitude, pos.coords.longitude);
        map.setCenter(MyPoint);

        var marker = new google.maps.Marker({
                 position: MyPoint,
                 map: map,
                 icon: wpestate_custompinchild(),
                 shape: shape,
                 title: '',
                 zIndex: 999999999,
                 image:'',
                 price:'',
                 category:'',
                 action:'',
                 link:'' ,
                 infoWindowIndex : 99 ,
                 radius: parseInt(mapfunctions_vars.geolocation_radius,10)+' '+mapfunctions_vars.geo_message
                });

        var populationOptions = {
            strokeColor: '#67cfd8',
            strokeOpacity: 0.6,
            strokeWeight: 1,
            fillColor: '#67cfd8',
            fillOpacity: 0.2,
            map: map,
            center: MyPoint,
            radius: parseInt(mapfunctions_vars.geolocation_radius,10)
        };

        var cityCircle = new google.maps.Circle(populationOptions);

        var label = new Label({
            map: map
        });
        label.bindTo('position', marker);
        label.bindTo('text', marker, 'radius');
        label.bindTo('visible', marker);
        label.bindTo('clickable', marker);
        label.bindTo('zIndex', marker);
    }else{

        var new_pos=[pos.coords.latitude, pos.coords.longitude];
        wprentals_draw_leaflet_circle(new_pos);
    }

}





function errorCallback() {
    "use strict";
    alert(mapfunctions_vars.geo_no_pos);
}

function wprentals_showMyPosition(pos) {
    "use strict";

    if(wprentals_map_type===1){
        wprentals_draw_google_circle(pos);
    }else if(wprentals_map_type===2){
        wprentals_draw_leaflet_circle(pos);
    }

}


function wprentals_draw_leaflet_circle(pos){

    L.circle( [pos[0],pos[1]], parseInt(mapfunctions_vars.geolocation_radius, 10)).addTo(map);

    var markerImage = {
            iconUrl: wprentals_custompinchild_leaflet(),
            iconSize: [44, 50],
            iconAnchor: [20, 50],
            popupAnchor: [1, -50]
        };
    var  markerOptions = {
        riseOnHover: true
    };

    var markerCenter    =   L.latLng( pos[0], pos[1] );
    markerOptions.icon = L.icon( markerImage );
    L.marker( markerCenter, markerOptions ).addTo( map );


    var wprentals_leaflet_label = '<div class="wprentals_leaflet_label">';
    wprentals_leaflet_label+=parseInt(mapfunctions_vars.geolocation_radius, 10) + ' ' + mapfunctions_vars.geo_message;
    wprentals_leaflet_label += '</div>';

    var myIcon = L.divIcon({
            className:'someclass',
            iconSize: new L.Point(0, 0),
            html: wprentals_leaflet_label
        });

    L.marker( markerCenter, {icon: myIcon} ).addTo( map );


    map.panTo(markerCenter);
    map.setZoom(10);
}




function wprentals_draw_google_circle(pos){
    var shape, MyPoint, marker, populationOptions, cityCircle, label;
    shape = {
        coord: [1, 1, 1, 38, 38, 59, 59, 1],
        type: 'poly'
    };

    MyPoint=  new google.maps.LatLng( pos[0], pos[1]);
    map.setCenter(MyPoint);
    map.setZoom(13);
    marker = new google.maps.Marker({
        position: MyPoint,
        map: map,
        icon: wpestate_custompinchild(),
        shape: shape,
        title: '',
        zIndex: 999999999,
        image: '',
        price: '',
        category: '',
        action: '',
        link: '',
        infoWindowIndex: 99,
        radius: parseInt(mapfunctions_vars.geolocation_radius, 10) + ' ' + mapfunctions_vars.geo_message
    });

    populationOptions = {
        strokeColor: '#67cfd8',
        strokeOpacity: 0.6,
        strokeWeight: 1,
        fillColor: '#67cfd8',
        fillOpacity: 0.2,
        map: map,
        center: MyPoint,
        radius: parseInt(mapfunctions_vars.geolocation_radius, 10)
    };
    cityCircle = new google.maps.Circle(populationOptions);

    label = new Label({
        map: map
    });
    label.bindTo('position', marker);
    label.bindTo('text', marker, 'radius');
    label.bindTo('visible', marker);
    label.bindTo('clickable', marker);
    label.bindTo('zIndex', marker);
}


function wpestate_custompinchild() {
    "use strict";
    var custom_img, image;
    var extension='';
    var ratio = jQuery(window).dense('devicePixelRatio');

    if(ratio>1){
        extension='_2x';
    }

    if (images['userpin'] === '') {
        custom_img = mapfunctions_vars.path + '/' + 'userpin' +extension+ '.png';
    } else {
        custom_img = images['userpin'];
        if(ratio>1){
            custom_img=wpestate_get_custom_retina_pin(custom_img);
        }
    }

    image = {
        url: custom_img,
        size: new google.maps.Size(59, 59),
        origin: new google.maps.Point(0, 0),
       // anchor: new google.maps.Point(16, 59)
    };


    if(ratio>1){

        var   image = {
            url: custom_img,
            size :  new google.maps.Size(44, 50),
            scaledSize   :  new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
            optimized:false

        };

    }else{
       var   image = {
            url: custom_img,
            size: new google.maps.Size(59, 59),
            origin: new google.maps.Point(0,0),

        };
    }


    return image;
}


// same thing as above but with ipad double click workaroud solutin
jQuery('#googleMap,#google_map_prop_list_wrapper').on('click',function (event) {
    "use strict";
    var time_diff;
    time_diff = event.timeStamp - ipad_time;

    if(wprentals_map_type==1){

        if (time_diff > 300) {
            ipad_time = event.timeStamp;
            if (map.scrollwheel === false) {
                if(wprentals_map_type===1){
                    map.setOptions({'scrollwheel': true});
                }
                jQuery('#googleMap').addClass('scrollon');
            } else {
                if(wprentals_map_type===1){
                    map.setOptions({'scrollwheel': false});
                }
                jQuery('#googleMap').removeClass('scrollon');
            }
            jQuery('.tooltip').fadeOut("fast");


            if (Modernizr.mq('only all and (max-width: 1025px)')) {
                if (map.draggable === false) {
                    map.setOptions({'draggable': true});
                } else {
                    map.setOptions({'draggable': false});
                }
            }

        }
    }
});


jQuery('#google_map_on_list').on('click',function (event) {
    if (Modernizr.mq('only all and (max-width: 1025px)')) {
        if (map.draggable === false) {
            map.setOptions({'draggable': true});
        } else {
            map.setOptions({'draggable': false});
        }
    }
});

/////////////////////////////////////////////////////////////////////////////////////////////////
///
/////////////////////////////////////////////////////////////////////////////////////////////////

if (document.getElementById('gmap')) {
    google.maps.event.addDomListener(document.getElementById('gmap'), 'mouseout', function () {
        "use strict";
        if(wprentals_map_type===1){
            map.setOptions({'scrollwheel': true});
        }
        wprentals_map_resize();
    });
}


if (document.getElementById('search_map_button')) {
    google.maps.event.addDomListener(document.getElementById('search_map_button'), 'click', function () {
        "use strict";
        infoBox.close();
    });
}


if (document.getElementById('advanced_search_map_button')) {
    google.maps.event.addDomListener(document.getElementById('advanced_search_map_button'), 'click', function () {
        "use strict";
        infoBox.close();
    });
}

////////////////////////////////////////////////////////////////////////////////////////////////
/// navigate troguh pins
///////////////////////////////////////////////////////////////////////////////////////////////

jQuery('#gmap-next').on('click',function () {
    "use strict";
    current_place++;
    external_action_ondemand=1;
    lealet_map_move_on_hover=1;
    if (current_place > gmarkers.length) {
        current_place = 1;
    }
    while (gmarkers[current_place-1].visible === false) {
        current_place++;
        if (current_place > gmarkers.length) {
            current_place = 1;
        }
    }

    if (map.getZoom() < 15) {
        map.setZoom(15);
    }
    if(wprentals_map_type===1){
        google.maps.event.trigger(gmarkers[current_place - 1], 'click');
    }else if(wprentals_map_type===2){

        map.setView(gmarkers[current_place - 1].getLatLng());
        if (! gmarkers[current_place - 1]._icon) {
            gmarkers[current_place - 1].__parent.spiderfy();
        }
            lealet_map_move_on_hover=1;

            map.setZoom(20);

            if( (current_place - 1)==0 || (current_place - 1)==gmarkers.length ){
                setTimeout(function(){  gmarkers[current_place - 1].fire('click');  }, 1000);
            }else{
                gmarkers[current_place - 1].fire('click');
            }

        //lealet_map_move_on_hover=0;
         lealet_map_move_on_hover=1;
    }


});


jQuery('#gmap-prev').on('click',function () {

    lealet_map_move_on_hover=1;
    external_action_ondemand=1;
    if (current_place < 1) {
        current_place = gmarkers.length;
    }
    while (gmarkers[current_place - 1].visible === false) {
        current_place--;
        if (current_place > gmarkers.length) {
            current_place = 1;
        }
    }
    if (map.getZoom() < 15) {
        map.setZoom(15);
    }

    if(wprentals_map_type===1){
        google.maps.event.trigger(gmarkers[current_place - 1], 'click');
    }else if(wprentals_map_type===2){
        map.setView(gmarkers[current_place - 1].getLatLng());
        if (! gmarkers[current_place - 1]._icon)  gmarkers[current_place - 1].__parent.spiderfy();
            lealet_map_move_on_hover=1;

            map.setZoom(20);

            if( (current_place - 1)==0 || (current_place )==gmarkers.length ){
                setTimeout(function(){  gmarkers[current_place - 1].fire('click');  }, 1000);
            }else{
                gmarkers[current_place - 1].fire('click');
            }

        lealet_map_move_on_hover=1;

    }
    current_place--;

});


///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// filter pins
//////////////////////////////////////////////////////////////////////////////////////////////////////////

jQuery('.advanced_action_div li').on('click',function () {
    "use strict";
    var action = jQuery(this).val();
});


if (document.getElementById('gmap-menu')) {
    google.maps.event.addDomListener(document.getElementById('gmap-menu'), 'click', function (event) {
        "use strict";
        var category;
        infoBox.close();

        if (event.target.nodeName === 'INPUT') {
            category = event.target.className;

            if (event.target.name === "filter_action[]") {
                if (actions.indexOf(category) !== -1) {
                    actions.splice(actions.indexOf(category), 1);
                } else {
                    actions.push(category);
                }
            }

            if (event.target.name === "filter_type[]") {
                if (categories.indexOf(category) !== -1) {
                    categories.splice(categories.indexOf(category), 1);
                } else {
                    categories.push(category);
                }
            }

            show(actions, categories);
        }

    });
}
//!visible_or_not(mapfunctions_vars.hows[0], gmarkers[i].val1, val1, mapfunctions_vars.slugs[0])

function wpestate_visible_or_not(how, slug, value, read) {
    "use strict";
     var slider_min, slider_max, parsevalue;
    if (value !== '' && typeof (value) !== 'undefined') {
        // value = value.replace('%','');
    }

    //////////////////////////////////////////////
    // in case of slider -
    if (read === 'property_price' && mapfunctions_vars.slider_price === 'yes') {
        slider_min = parseInt(jQuery('#price_low').val(), 10);
        slider_max = parseInt(jQuery('#price_max').val(), 10);
        if (slug >= slider_min && slug <= slider_max) {
            return true;
        } else {
            return false;
        }
    }
    //////////////////////////////////////////////
    // END in case of slider -

    if (read === 'none') {
        return true;
    }

    if (value !== '' && value !== ' ' && value !== 'all') {
        parsevalue = parseInt(value, 10);
        if (how === 'greater') {
            if (slug >= parsevalue) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'smaller') {
            slug = parseInt(slug, 10);
            if (slug <= parsevalue) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'equal') {
            if (slug === value || value === 'all') {
                return true;
            } else {
                return false;
            }
        } else if (how === 'like') {
            slug = slug.toLowerCase();
            value = value.toLowerCase();
            if (slug.indexOf(value) > -1) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'date bigger') {
            slug = new Date(slug);
            value = new Date(value);
            if (slug >= value) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'date smaller') {
            slug = new Date(slug);
            value = new Date(value);
            if (slug <= value) {
                return true;
            } else {
                return false;
            }
        }
        //return false;
    } else {
        return true;
    }
}


function wpestate_get_custom_value(slug) {
    "use strict";
    var value;

    if (slug === 'adv_categ' || slug === 'adv_actions' || slug === 'advanced_city' || slug === 'advanced_area') {
        value = jQuery('#' + slug).attr('data-value');
    } else if (slug === 'property_price' && mapfunctions_vars.slider_price === 'yes') {
        value = jQuery('#price_low').val();
    } else {
        value = jQuery('#' + slug).val();
    }

    return value;
}


function wprentals_show_pins() {
    "use strict";
    var is_google_map, results_no, city, area, guests, bounds, i;
    is_google_map = parseInt(jQuery('#isgooglemap').attr('data-isgooglemap'), 10);
    if (is_google_map !== 1) {
        return;
    }

    results_no = 0;
    city = jQuery('#advanced_city').attr('data-value');
    area = jQuery('#advanced_area').attr('data-value');
    guests = parseInt(jQuery('#guest_no').val(), 10);

    if (isNaN(guests)) {
        guests = 0;
    }

    if (typeof infoBox !== 'undefined' && infoBox !== null) {
        infoBox.close();
    }

    bounds = new google.maps.LatLngBounds();
    markers_cluster.setIgnoreHidden(true);
    if (typeof gmarkers !== 'undefined') {
        for (i = 0; i < gmarkers.length; i++) {
            if (gmarkers[i].area !== area && area !== 'all' && area !== '') {
                gmarkers[i].setVisible(false);
            } else if (gmarkers[i].city !== city && city !== 'all') {
                gmarkers[i].setVisible(false);
            } else if (parseInt(gmarkers[i].guests, 10) !== guests && guests !== 0) {
                gmarkers[i].setVisible(false);
            } else {
                gmarkers[i].setVisible(true);
                results_no = results_no + 1;
                bounds.extend(gmarkers[i].getPosition());
            }
        }//end for
        markers_cluster.repaint();
    }//end if

    if (mapfunctions_vars.generated_pins === '0') {
        if (results_no === 0) {
            jQuery('#gmap-noresult').show();
            jQuery('#results').hide();
        } else {
            jQuery('#gmap-noresult').hide();
            if (!bounds.isEmpty()) {
                wpestate_fit_bounds(bounds);
            }
            jQuery("#results, #showinpage,#showinpage_mobile").show();

            jQuery("#results_no").show().empty().append(results_no);
        }
    } else {
        wpestate_get_filtering_ajax_result();
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////
/// get pin image
/////////////////////////////////////////////////////////////////////////////////////////////////
function wpestate_convertToSlug(Text) {
    "use strict";
    return Text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
}


function custompin(image) {
    "use strict";

    if( !mapfunctions_vars.hidden_map ){
        image ={
            path: google.maps.SymbolPath.CIRCLE,
            scale: 60,
            fillColor: "#b881fc",
            fillOpacity: 0.4,
            strokeWeight: 0.4
        }
        return image;
    }


    var custom_img;

    var extension='';
    var ratio = jQuery(window).dense('devicePixelRatio');

    if(ratio>1){
        extension='_2x';
    }


    if(mapfunctions_vars.use_single_image_pin==='no'){
        if (image !== '') {
            if (images[image] === '') {
                custom_img = mapfunctions_vars.path + '/' + image + extension + '.png';
            } else {
                custom_img = images[image];
                if(ratio>1){
                    custom_img=custom_img.replace(".png","_2x.png");
                }
            }
        } else {
            custom_img = mapfunctions_vars.path + '/none.png';
        }

        if (typeof (custom_img) === 'undefined') {
            custom_img = mapfunctions_vars.path + '/none.png';
        }
    }else{
        if(ratio>1){
            custom_img= wpestate_get_custom_retina_pin(  images['single_pin'] );
        }else{
            custom_img= images['single_pin'];
        }
    }

    if(ratio>1){

        image = {
            url: custom_img,
            size :  new google.maps.Size(44, 50),
            scaledSize   :  new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
            optimized:false
        };

    }else{
            image = {
            url: custom_img,
            size: new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),

        };
    }



    return image;
}


function wprentals_custompinhover() {
    "use strict";
    var custom_img, image;

    var extension='';
    var ratio = jQuery(window).dense('devicePixelRatio');

    if(ratio>1){
        extension='_2x';
    }

    custom_img = mapfunctions_vars.path + '/hover'+extension+'.png';


    if(wprentals_map_type===2){

        var markerImage    = L.icon({
            iconUrl: custom_img,
            iconSize: [44, 50],
            iconAnchor: [20, 50],
            popupAnchor: [1, -50]
        });
        return markerImage;

    }


    image = {
        url: custom_img,
        size: new google.maps.Size(44, 50),
        origin: new google.maps.Point(0, 0),
       // anchor: new google.maps.Point(26, 25)
    };

    if(ratio>1){

        image = {
            url: custom_img,
            size :  new google.maps.Size(44, 50),
            scaledSize   :  new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
            optimized:false

          };

    }else{
        image = {
            url: custom_img,
            size: new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
        };
    }

    return image;
}



function wpestate_get_custom_retina_pin(custom_img){
    "use strict";
    var custom_img_retina;
    custom_img_retina=custom_img.replace(".png","_2x.png");

    jQuery.get(custom_img_retina)
        .done(function() {
            custom_img = custom_img_retina;
        }).fail(function() {
        });
    return custom_img;
}




/////////////////////////////////////////////////////////////////////////////////////////////////
//// Circle label
/////////////////////////////////////////////////////////////////////////////////////////////////

function Label(opt_options) {
    "use strict";
    // Initialization
    this.setValues(opt_options);
    // Label specific
    var span = this.span_ = document.createElement('span');
    span.style.cssText = 'position: relative; left: -50%; top: 8px; ' +
            'white-space: nowrap;  ' +
            'padding: 2px; background-color: white;opacity:0.7';


    var div = this.div_ = document.createElement('div');
    div.appendChild(span);
    div.style.cssText = 'position: absolute; display: none';
}


if (typeof google === 'object' && typeof google.maps === 'object') {
    Label.prototype = new google.maps.OverlayView;
    // Implement onAdd
    Label.prototype.onAdd = function () {
        var pane = this.getPanes().overlayImage;
        pane.appendChild(this.div_);

        // Ensures the label is redrawn if the text or position is changed.
        var me = this;
        this.listeners_ = [
            google.maps.event.addListener(this, 'position_changed', function () {
                me.draw();
            }),
            google.maps.event.addListener(this, 'visible_changed', function () {
                me.draw();
            }),
            google.maps.event.addListener(this, 'clickable_changed', function () {
                me.draw();
            }),
            google.maps.event.addListener(this, 'text_changed', function () {
                me.draw();
            }),
            google.maps.event.addListener(this, 'zindex_changed', function () {
                me.draw();
            }),
            google.maps.event.addDomListener(this.div_, 'click', function () {
                if (me.get('clickable')) {
                    google.maps.event.trigger(me, 'click');
                }
            })
        ];
    };

    // Implement onRemove
    Label.prototype.onRemove = function () {
        this.div_.parentNode.removeChild(this.div_);
        // Label is removed from the map, stop updating its position/text.
        for (var i = 0, I = this.listeners_.length; i < I; ++i) {
            google.maps.event.removeListener(this.listeners_[i]);
        }
    };


    // Implement draw
    Label.prototype.draw = function () {
        var projection = this.getProjection();
        var position = projection.fromLatLngToDivPixel(this.get('position'));
        var div = this.div_;
        div.style.left = position.x + 'px';
        div.style.top = position.y + 'px';


        var visible = this.get('visible');
        div.style.display = visible ? 'block' : 'none';


        var clickable = this.get('clickable');
        this.span_.style.cursor = clickable ? 'pointer' : '';


        var zIndex = this.get('zIndex');
        div.style.zIndex = zIndex;


        this.span_.innerHTML = this.get('text').toString();
    };
}


/////////////////////////////////////////////////////////////////////////////////////////////////
/// close advanced search
/////////////////////////////////////////////////////////////////////////////////////////////////
function wpestate_close_adv_search() {

}


function wpestate_new_show_advanced_search() {
    "use strict";
    jQuery("#search_wrapper").removeClass("hidden");
}

function wpestate_new_hide_advanced_search() {
    "use strict";
    if (mapfunctions_vars.show_adv_search === 'no') {
        jQuery("#search_wrapper").addClass("hidden");
    }
}


function wpestate_set_filter_pins(map, new_markers) {
    "use strict";
    if(wprentals_map_type===1){
        for (var i = 0; i < gmarkers.length; i++) {
            gmarkers[i].setVisible(false);
            gmarkers[i].setMap(null);
        }
        gmarkers = [];


        if( typeof (markers_cluster)!=='undefined'){
            markers_cluster.clearMarkers();
        }

        if (new_markers.length > 0) {
            bounds_list = new google.maps.LatLngBounds();
            wprentals_map_general_set_markers(map, new_markers);
            wprentals_google_map_cluster();
              wprentals_google_fit_to_bounds();
        }

    } else if(wprentals_map_type===2){

        if (mapfunctions_vars.user_cluster === 'yes') {
            map.removeLayer(markers_cluster);
        }else{
            for (var i = 0; i < gmarkers.length; i++) {
                map.removeLayer(gmarkers[i]);
            }
        }

        gmarkers = [];
        markers_cluster=L.markerClusterGroup({
            showCoverageOnHover: false,
            iconCreateFunction: function(cluster) {
                    return L.divIcon({ html: '<div class="leaflet_cluster" style="background-image: url('+images['cloud_pin']+')">' + cluster.getChildCount() + '</div>' });
            },
        });

        if (new_markers.length > 0) {

            bounds_list =undefined;
            wprentals_map_general_set_markers(map, new_markers);
            //set map cluster
            wprentals_map_general_cluster();
            //fit bounds
            wprentals_map_general_fit_to_bounds();
        }
        wprentals_map_general_cluster();

    }

}


function wpestate_set_filter_pins_ondemand(map, new_markers) {
    "use strict";
    if(wprentals_map_type===1){

        for (var i = 0; i < gmarkers.length; i++) {
            gmarkers[i].setVisible(false);
            gmarkers[i].setMap(null);
        }
        gmarkers = [];

        if (new_markers.length > 0) {
            if( typeof (markers_cluster)!=='undefined'){
               markers_cluster.clearMarkers();
            }
            wprentals_google_setMarkers(map, new_markers);
            wprentals_google_map_cluster();

        }

        oms = new OverlappingMarkerSpiderfier(map);
        setOms(gmarkers);
        oms.addListener('spiderfy', function (markers) {
        });
        oms.addListener('unspiderfy ', function (markers) {
        });


    } else if(wprentals_map_type===2){

        if (mapfunctions_vars.user_cluster === 'yes') {
            map.removeLayer(markers_cluster);
        }else{
            for (var i = 0; i < gmarkers.length; i++) {
                map.removeLayer(gmarkers[i]);
            }
        }

        gmarkers = [];
        markers_cluster=L.markerClusterGroup({
            showCoverageOnHover: false,
            iconCreateFunction: function(cluster) {
                    return L.divIcon({ html: '<div class="leaflet_cluster" style="background-image: url('+images['cloud_pin']+')">' + cluster.getChildCount() + '</div>' });
            },
        });

        if (new_markers.length > 0) {
            bounds_list = map.getBounds();
            wprentals_map_general_set_markers(map, new_markers);
        }
        wprentals_map_general_cluster();



    }

}






var marker_zindex;

function wpestate_hover_action_pin(listing_id) {
    "use strict";

    for (var i = 0; i < gmarkers.length; i++) {

        if (parseInt(gmarkers[i].idul, 10) === parseInt(listing_id, 10)) {

            if(wprentals_map_type===1){
                google.maps.event.trigger(gmarkers[i], 'click');

                if(mapfunctions_vars.useprice !== 'yes'){
                    gmarkers[i].setIcon( wprentals_custompinhover() );
                }else{
                    gmarkers[i].addHoverClass();
                }

                marker_zindex=gmarkers[i].getZIndex();
                gmarkers[i].setZIndex(9999999);
            }else if(wprentals_map_type===2){

                lealet_map_move_on_hover=1;
                if (! gmarkers[i]._icon)  gmarkers[i].__parent.spiderfy();
                map.panTo(  gmarkers[i].getLatLng());
                gmarkers[i].togglePopup();


                if(mapfunctions_vars.useprice !== 'yes'){
                    gmarkers[i].setIcon(wprentals_custompinhover());
                }else{
                    L.DomUtil.addClass(gmarkers[i]._icon, 'openstreet_price_marker_on_click_parent');
                }


            }
        }
    }




}

function wpestate_return_hover_action_pin(listing_id) {
    "use strict";
    for (var i = 0; i < gmarkers.length; i++) {
        if (parseInt(gmarkers[i].idul, 10) === parseInt(listing_id, 10)) {

            if(wprentals_map_type===1){
                if (parseInt(gmarkers[i].idul, 10) === parseInt(listing_id, 10)) {
                    infoBox.close();
                    if(mapfunctions_vars.useprice !== 'yes'){
                        gmarkers[i].setIcon(custompin(gmarkers[i].custompin));
                    }else{
                          gmarkers[i].removeHoverClass();
                    }
                    gmarkers[i].setZIndex(marker_zindex);
                }
            }  else if(wprentals_map_type===2){

                gmarkers[i].togglePopup();
                lealet_map_move_on_hover=0;
                if(mapfunctions_vars.useprice !== 'yes'){
                 var   markerImage      = L.icon({
                        iconUrl: wprentals_custompin_leaflet(gmarkers[i].pin),
                        iconSize: [44, 50],
                        iconAnchor: [20, 50],
                        popupAnchor: [1, -50]
                    });
                    gmarkers[i].setIcon(markerImage);
                }else{
                    L.DomUtil.removeClass(gmarkers[i]._icon, 'openstreet_price_marker_on_click_parent');
                }
            }


        }

    }

}

String.prototype.capitalize = function () {
    return this.replace(/(?:^|\s)\S/g, function (a) {
        return a.toUpperCase();
    });
};

////////////////////////////////////////////////////////////////
//map places
///////////////////////////////////////////////////////////////

var wpestate_initialize_poi = function ( map_for_poi,what){

    if( parseInt(mapbase_vars.wprentals_map_type)!==1){
        return;
    }

    var poi_service         =   new google.maps.places.PlacesService( map_for_poi );
    var already_serviced    =   '';
    var show_poi            =   '';
    var map_bounds          =   map_for_poi.getBounds();
    var selector            =   '.google_poi';
    if(what==2){
        selector = '.google_poish';
    }



    jQuery(selector).on('click',function(event){
        event.stopPropagation();
        poi_type        =   jQuery(this).attr('id');
        var position    =   map_for_poi.getCenter();
        var show_poi    =   wpestate_return_poi_values(poi_type);


        if( jQuery(this).hasClass('poi_active')){
            wpestate_show_hide_poi(poi_type,'hide');
        }else{

            already_serviced = wpestate_show_hide_poi(poi_type,'show');
            if(already_serviced===1){

                var request = {
                    location:   position,
                    types:      show_poi,
                    bounds:     map_bounds,
                    radius:     2500,
                };
                poi_service.nearbySearch( request,function (results, status){
                    wpestate_googlemap_display_poi(results, status,map_for_poi);
                });
            }
        }
        jQuery(this).toggleClass('poi_active');
    });





    // return google poi types for selected poi
    var wpestate_return_poi_values = function (poi_type){
        var  show_poi;
        switch(poi_type) {
                case 'transport':
                    show_poi = ['bus_station', 'airport', 'train_station', 'subway_station'];
                    break;
                case 'supermarkets':
                    show_poi = ['grocery_or_supermarket', 'shopping_mall'];
                    break;
                case 'schools':
                    show_poi = ['school', 'university'];
                    break;
                case 'restaurant':
                    show_poi=['restaurant'];
                    break;
                case 'pharma':
                    show_poi = ['pharmacy'];
                    break;
                case 'hospitals':
                    show_poi = ['hospital'];
                    break;
            }
        return show_poi;
    };


    // add poi markers on the map
    var wpestate_googlemap_display_poi = function (results, status,map_for_poi) {
        var place, poi_marker;
        if ( google.maps.places.PlacesServiceStatus.OK == status  ) {
            for (var i = 0; i < results.length; i++) {
                poi_marker  =   wpestate_create_poi_marker(results[i],map_for_poi);
                poi_marker_array.push(poi_marker);
            }
        }
    };

    // create the poi markers
    var wpestate_create_poi_marker = function (place,map_for_poi){
        marker = new google.maps.Marker({
            map: map_for_poi,
            position: place.geometry.location,
            show_poi:poi_type,
            icon: mapfunctions_vars.path+'/poi/'+poi_type+'.png'
        });


        var boxText         =   document.createElement("div");
        var infobox_poi     =   new InfoBox({
                    content: boxText,
                    boxClass:"estate_poi_box",
                    pixelOffset: new google.maps.Size(-30, -70),
                    zIndex: null,
                    maxWidth: 275,
                    closeBoxMargin: "-13px 0px 0px 0px",
                    closeBoxURL: "",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    pane: "floatPane",
                    enableEventPropagation: false
                });

        google.maps.event.addListener(marker, 'mouseover', function(event) {
            infobox_poi.setContent(place.name);
            infobox_poi.open(map, this);
        });

        google.maps.event.addListener(marker, 'mouseout', function(event) {
            if( infobox_poi!== null){
                infobox_poi.close();
            }
        });
        return marker;
    };



    // hide-show poi
    var wpestate_show_hide_poi = function (poi_type,showhide){
        var is_hiding=1;

        for (var i = 0; i < poi_marker_array.length; i++) {
            if (poi_marker_array[i].show_poi === poi_type){
                if(showhide==='hide'){
                    poi_marker_array[i].setVisible(false);
                }else{
                    poi_marker_array[i].setVisible(true);
                    is_hiding=0;
                }
            }
        }

        return is_hiding;
    };
};


function wpestate_makeSafeForCSS(name) {
    return name.replace(/[^a-z0-9]/g, function(s) {
        var c = s.charCodeAt(0);
        if (c == 32) return '-';
        if (c >= 65 && c <= 90) return  s.toLowerCase();
        return  ('000' + c.toString(16)).slice(-4);
    });
}
