/*global $, jQuery, ajaxcalls_vars, document, control_vars, window, map, setTimeout, Modernizr, Blazy, location, google, options, wprentals_show_pins, wpestate_show_login_form, adv_search_click, wpestate_restart_js_after_ajax,start_filtering_ajax,  start_filtering_ajax_map, wpestate_toggleStreetView, wpestate_show_capture, wpestate_new_open_close_map*/
var has_transparent = 0;
var componentForm;
var price_array;
var my_custom_curr_symbol = wprentals_getCookie('my_custom_curr_symbol');
var my_custom_curr_coef = parseFloat(wprentals_getCookie('my_custom_curr_coef'), 10);
var my_custom_curr_pos = parseFloat(wprentals_getCookie('my_custom_curr_pos'), 10);
var my_custom_curr_cur_post = wprentals_getCookie('my_custom_curr_cur_post');
var my_custom_curr_label = wprentals_getCookie('my_custom_curr');
var my_custom_curr_symbol2 = decodeURIComponent(wprentals_getCookie('my_custom_curr_symbol2'));
var login_modal_type = 1;
var scroll_trigger = 0;
const longmonths = Array.from({length: 12}, (_, m) =>
    new Date(2019, m, 1).toLocaleString(control_vars.datepick_lang, {month: 'long'})
);
const dayNamesShort_long = Array.from({length: 7}, (_, d) =>
    new Date(2019, 6, d).toLocaleString(control_vars.datepick_lang, {weekday: 'short'})
);
const dayNamesShort = new Array();


dayNamesShort_long.forEach(wpestate_trim_daynames);
function wpestate_trim_daynames(value) {
    dayNamesShort.push(value.substring(0, 2));
}

jQuery(window).bind("load", function () {
    wpestate_lazy_load_carousel_property_unit();
});


function wprentals_getCookie(cname) {
    "use strict";
    var name, ca, i, c;
    name = cname + "=";
    ca = document.cookie.split(';');
    for (i = 0; i < ca.length; i = i + 1) {
        c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1);
        if (c.indexOf(name) === 0)
            return c.substring(name.length, c.length);
    }
    return "";
}

function wprentals_splash_slider() {
    "use strict";
    if (jQuery("#splash_slider_wrapper").length > 0) {

    }
}

(function ($) {
    "use strict";
    $.fn.nodoubletapzoom = function () {
        $(this).bind('touchstart', function preventZoom(e) {
            var t2 = e.timeStamp
                    , t1 = $(this).data('lastTouch') || t2
                    , dt = t2 - t1
                    , fingers = e.originalEvent.touches.length;
            $(this).data('lastTouch', t2);
            if (!dt || dt > 500 || fingers > 1)
                return; // not double-tap

            e.preventDefault(); // double tap - prevent the zoom
            // also synthesize click events we just swallowed up
            $(this).trigger('click').trigger('click');
        });
    };
})(jQuery);



var widgetId1, widgetId2, widgetId3, widgetId4;

var wpestate_onloadCallback = function () {
    "use strict";
    // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
    // The id of the reCAPTCHA widget is assigned to 'widgetId1'.

    if (document.getElementById('capthca_register')) {

        widgetId1 = grecaptcha.render('capthca_register', {
            'sitekey': control_vars.captchakey,
            'theme': 'light'
        });
        grecaptcha.reset();
    }

    if (document.getElementById('mobile_register_menu')) {
        widgetId2 = grecaptcha.render('mobile_register_menu', {
            'sitekey': control_vars.captchakey,
            'theme': 'light'
        });
    }


    if (document.getElementById('widget_register_menu')) {

        widgetId3 = grecaptcha.render('widget_register_menu', {
            'sitekey': control_vars.captchakey,
            'theme': 'light'
        });

    }

    if (document.getElementById('capthca_register_sh')) {
        widgetId4 = grecaptcha.render('capthca_register_sh', {
            'sitekey': control_vars.captchakey,
            'theme': 'light'
        });

    }


};


jQuery(window).scroll(function ($) {
    "use strict";
    var scroll = jQuery(window).scrollTop();

    if (control_vars.stiky_search === 'yes') {
        wpestate_adv_search_sticky(scroll);
    } else {
        wpestate_header_sticky(scroll);
    }
});


function wpestate_header_sticky(scroll) {
    "use strict";
    if (scroll >= 10) {
        if (!Modernizr.mq('only all and (max-width: 1025px)')) {
            jQuery('.logo').addClass('miclogo');

            if (!jQuery(".header_wrapper").hasClass('is_half_map')) {
                jQuery(".header_wrapper").addClass("navbar-fixed-top");
                jQuery(".master_header").addClass("navbar-fixed-top-master");
                jQuery(".header_wrapper").addClass("customnav");

                if (jQuery(".header_wrapper").hasClass('transparent_header')) {
                    has_transparent = 1;
                    jQuery(".header_wrapper").removeClass('transparent_header');
                    if (control_vars.transparent_logo !== '') {
                        if (control_vars.normal_logo !== '') {
                            jQuery(".logo img").attr('src', control_vars.normal_logo);
                        } else {
                            jQuery(".logo img").attr('src', control_vars.path + "/img/logo.png");
                        }
                    }
                }
            }



            jQuery('.barlogo').show();
            jQuery('#user_menu_open').hide();
            jQuery('#wpestate_header_shoping_cart').hide();
        }
        jQuery('.backtop').addClass('islive');
    } else {
        jQuery(".header_wrapper").removeClass("navbar-fixed-top");
        jQuery(".master_header").removeClass("navbar-fixed-top-master");
        jQuery(".header_wrapper").removeClass("customnav");

        if (has_transparent === 1) {
            jQuery(".header_wrapper").addClass('transparent_header');
            if (control_vars.transparent_logo !== '') {
                jQuery(".logo img").attr('src', control_vars.transparent_logo);
            }
        }

        jQuery('.backtop').removeClass('islive');
        jQuery('.contactformwrapper').addClass('hidden');
        jQuery('.barlogo').hide();
        jQuery('#user_menu_open').hide();
        jQuery('#wpestate_header_shoping_cart').hide();
        jQuery('.logo').removeClass('miclogo');
    }
}



function wpestate_adv_search_sticky(scroll) {
    "use strict";


    if (scroll > 20) {
        if (jQuery('.has_header_type4').length <= 0) {
            if (jQuery('.estate_property-template-default').length === 0) {
                jQuery(".master_header").hide();
            }

        } else {
            jQuery('.top_bar_wrapper').hide();
        }
    } else {
        jQuery(".master_header,.top_bar_wrapper").show();
    }

    if (wpestate_isScrolledIntoView(scroll) && scroll_trigger === 0) {
    
        // jQuery('#search_wrapper').addClass('sticky_adv');
    }

    if (scroll_trigger !== 0) {
        if (scroll < scroll_trigger) {
            jQuery('#search_wrapper').removeClass('sticky_adv').removeClass('sticky_adv_anime');
        } else if (scroll > scroll_trigger - 40 && scroll < scroll_trigger + 100) {
            jQuery('#search_wrapper').addClass('sticky_adv_anime');
        } else if (scroll > scroll_trigger) {
            jQuery('#search_wrapper').removeClass('sticky_adv_anime');
            jQuery('#search_wrapper').addClass('sticky_adv');
        }


    }
}


function wpestate_isScrolledIntoView(scroll) {
    "use strict";
    if (jQuery('#search_wrapper').length > 0) {
        var elemTop = parseInt(jQuery('#search_wrapper').offset().top);
        var elemHeight = parseInt(jQuery('#search_wrapper').height());
        elemHeight = 0;

        if ((elemTop + elemHeight + 3) < scroll) {

            return true;
        } else {
            if (scroll_trigger === 0) {
                scroll_trigger = elemTop + elemHeight + 30;
            }
            return false;
        }
    }


}


jQuery('#google_map_prop_list_sidebar').scroll(function () {

    "use strict";
    var scroll = jQuery('#google_map_prop_list_sidebar').scrollTop();
    if (scroll >= 110) {
        jQuery('#advanced_search_map_list_hidden').show();
        jQuery('#advanced_search_map_list').removeClass('move_to_fixed');
    } else {
        jQuery('#advanced_search_map_list_hidden').hide();
    }
});

jQuery(window).resize(function () {
    "use strict";
    jQuery('#mobile_menu').hide('10');
});

Number.prototype.format = function (n, x) {
    "use strict";
    var re;
    re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};



function  wpestate_show_capture_vertical() {
    "use strict";


    var position, slideno, slidedif, tomove, curentleft, position;
    jQuery('#googleMapSlider').hide();
    position = parseFloat(jQuery('#carousel-listing .carousel-inner .active').index(), 10);
    jQuery('#carousel-indicators-vertical  li').removeClass('active');
    jQuery('#carousel-listing  .caption-wrapper span').removeClass('active');
    jQuery("#carousel-listing  .caption-wrapper span[data-slide-to='" + position + "'] ").addClass('active');
    jQuery("#carousel-listing  .caption-wrapper span[data-slide-to='" + position + "'] ").addClass('active');

    jQuery("#carousel-indicators-vertical  li[data-slide-to='" + position + "'] ").addClass('active');

    slideno = position + 1;

    slidedif = slideno * 92;


    if (slidedif > 338) {
        tomove = 338 - slidedif;
        tomove = tomove;
        jQuery('#carousel-indicators-vertical').css('top', tomove + "px");
    } else {
        position = jQuery('#carousel-indicators-vertical').css('top', tomove + "px").position();
        curentleft = position.top;

        if (curentleft < 0) {
            tomove = 0;
            jQuery('#carousel-indicators-vertical').css('top', tomove + "px");
        }

    }
}

function wpestate_owner_insert_book(button) {
    "use strict";

    var extra_options, fromdate, todate, listing_edit, nonce, ajaxurl, comment, booking_guest_no, action_function, to_be_paid, price;
    ajaxurl = control_vars.admin_url + 'admin-ajax.php';


    if (jQuery('#start_hour_wrapper_list').length > 0) { // we have per hour
        var fromdate_base = jQuery("#start_date").val();
        ;
        fromdate = fromdate_base + " " + jQuery('#start_hour_no_wrapper').attr('data-value');
        todate = fromdate_base + " " + jQuery('#end_hour_no_wrapper').attr('data-value');

    } else {
        fromdate = jQuery("#start_date").val();
        todate = jQuery("#end_date").val();
    }




    listing_edit = jQuery('#listing_edit').val();
    comment = jQuery("#book_notes").val();
    booking_guest_no = jQuery('#booking_guest_no_wrapper').attr('data-value');

    var booking_adults  =   booking_guest_no;
    var booking_childs  =   0;
    var booking_infants =   0;
    
    if(jQuery('.guest_no_hidden').length>0){
        booking_guest_no    =   parseInt( jQuery('.guest_no_hidden').val(),10);
        booking_adults      =   parseInt( jQuery('.steper_value_adults').html(), 10); 
        booking_childs      =   parseInt( jQuery('.steper_value_childs').html() , 10); 
        booking_infants     =   parseInt( jQuery('.steper_value_infants').html(), 10 ); 
    }



    extra_options = '';
    jQuery('.cost_row_extra input').each(function () {
        if ((jQuery(this).is(":checked"))) {
            if (!isNaN(jQuery(this).attr('data-key')) && typeof (jQuery(this).attr('data-key')) != undefined) {
                extra_options = extra_options + jQuery(this).attr('data-key') + ",";
            }
        }
    });

    action_function = 'wpestate_ajax_add_booking';

    if (document.getElementById('submit_booking_front_instant')) {
        action_function = 'wpestate_ajax_add_booking_instant';
    }
    var nonce = jQuery('#wprentals_add_booking').val();


    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': action_function,
            'fromdate': fromdate,
            'todate': todate,
            'listing_edit': listing_edit,
            'comment': comment,
            'booking_guest_no': booking_guest_no,
            'extra_options': extra_options,
            'booking_adults':booking_adults,
            'booking_childs':booking_childs,
            'booking_infants':booking_infants,
            'security': nonce
        },
        success: function (data) {

            jQuery('.has_future').each(function () {
                jQuery('#start_date, #end_date').val('');
                jQuery('#booking_guest_no_wrapper').html(control_vars.guest_any + '<span class="caret caret_filter"></span>');
            });

            if (action_function == 'wpestate_ajax_add_booking_instant') {
                if (document.getElementById('submit_booking_front_instant')) {
                    if (property_vars.logged_in === "no") {
                        window.location.href = data;
                    } else {
                        jQuery('#instant_booking_modal .modal-body').html(data);
                        jQuery('#instant_booking_modal').modal({
                            backdrop: 'static',
                            keyboard: false});
                        wpestate_create_payment_action();
                    }
                }
            } else {
                jQuery('#booking_form_request_mess').empty().removeClass('book_not_available').text(control_vars.bookconfirmed);
            }

            wpestate_redo_listing_sidebar();

            if (button.attr('id') === 'submit_booking_front_instant') {
                button.val(property_vars.instant_booking);
            } else {
                button.val(property_vars.book_now);
            }
            button.prop('disabled', false);



        },
        error: function (errorThrown) {
        }
    });
}


function wpestate_redo_listing_sidebar() { // 638

    if (jQuery('#primary').hasClass('listing_type_1')) {
        return;
    }

    var newmargin = 0;
    var current_height = jQuery('#booking_form_request').outerHeight();
    if (current_height > 525) {
        newmargin = current_height - 525 + 180;
        // 525  default booking_form_request
        // listing sidebar margin-top

        jQuery('.single-estate_property #primary').css('margin-top', newmargin + 'px');
    }

}





function wprentals_check_booking_valability(button) {
    "use strict";

    var book_from, book_to, listing_edit, ajaxurl, internal;
    internal = 0;
    book_from = jQuery('#start_date').val();
    book_to = jQuery('#end_date').val();
    listing_edit = jQuery('#listing_edit').val();
    ajaxurl = control_vars.admin_url + 'admin-ajax.php';


    if (jQuery('#start_hour_wrapper_list').length > 0) { // we have per hour
        var fromdate_base = book_from;
        book_from = fromdate_base + " " + jQuery('#start_hour_no_wrapper').attr('data-value');
        //  book_from            =   wpestate_convert_selected_days(book_from);
        book_to = fromdate_base + " " + jQuery('#end_hour_no_wrapper').attr('data-value');
        //book_to              =   wpestate_convert_selected_days(book_to);

    } else {
        book_from = jQuery('#start_date').val();
        book_to = jQuery('#end_date').val();
    }


    var nonce = jQuery('#wprentals_add_booking').val();
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'wpestate_ajax_check_booking_valability',
            'book_from': book_from,
            'book_to': book_to,
            'listing_id': listing_edit,
            'internal': internal,
            'security': nonce
        },
        success: function (data) {

            if (data.trim() === 'run') {
                wpestate_owner_insert_book(button);
            } else if (data === 'stopcheckinout') {
                jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.stopcheckinout);
            } else if (data === 'stopcheckin') {
                jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.stopcheckin);
            } else if (data === 'stopdays') {
                jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.mindays);
            } else {
                jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.bookdenied);
            }

            if (data.trim() !== 'run') {
                if (button.attr('id') === 'submit_booking_front_instant') {
                    button.val(property_vars.instant_booking);
                } else {
                    button.val(property_vars.book_now);
                }
                button.prop('disabled', false);
            }

        },
        error: function (errorThrown) {
        }
    });
}

function wpestate_show_instant_book_modal() {

}


function wpestate_owner_insert_book_internal() {
    "use strict";

    var fromdate, todate, listing_edit, nonce, ajaxurl, comment, booking_guest_no, hour_from, hour_to;
    ajaxurl = control_vars.admin_url + 'admin-ajax.php';
    fromdate = jQuery("#start_date_owner_book").val();
    todate = jQuery("#end_date_owner_book").val();

    listing_edit = jQuery('#listing_edit').val();
    comment = jQuery("#book_notes").val();
    booking_guest_no = jQuery('#booking_guest_no_wrapper').attr('data-value');
    
    if(jQuery('.guest_no_hidden').length>0){
        booking_guest_no            =   parseInt( jQuery('.guest_no_hidden').val(),10);
    }


    var nonce = jQuery('#wprentals_add_booking').val();

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'wpestate_ajax_add_booking',
            'fromdate': fromdate,
            'todate': todate,
            'listing_edit': listing_edit,
            'comment': comment,
            'booking_guest_no': booking_guest_no,
            'confirmed': 1,
            'security': nonce
        },
        success: function (data) {

            jQuery('.has_future').each(function () {
                jQuery(this).removeClass('calendar-reserved-stop');
                jQuery(this).removeClass('calendar-reserved-start');
                jQuery(this).removeClass('calendar-reserved-stop-visual');
                jQuery(this).removeClass('calendar-reserved-start-visual');
                jQuery('#owner_reservation_modal').modal('hide');


                jQuery('.booking-calendar-wrapper-in .calendar-selected').removeClass('calendar-selected');
                jQuery('#book_dates').empty().text(ajaxcalls_vars.reserve);
                jQuery('#book_notes').val('');
            });
            jQuery('#booking_form_request_mess').empty().text(control_vars.bookconfirmed);
        },
        error: function (errorThrown) {

        }
    });
}

function wpestate_check_booking_valability_internal() {
    "use strict";

    var book_from, book_to, listing_edit, ajaxurl, internal, hour_from, hour_to;
    jQuery('#book_dates').empty().text(ajaxcalls_vars.saving);
    book_from = jQuery('#start_date_owner_book').val();
    book_to = jQuery('#end_date_owner_book').val();
    var nonce = jQuery('#wprentals_add_booking').val();

    listing_edit = jQuery('#listing_edit').val();
    ajaxurl = control_vars.admin_url + 'admin-ajax.php';
    internal = 1;
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'wpestate_ajax_check_booking_valability_internal',
            'book_from': book_from,
            'book_to': book_to,
            'listing_id': listing_edit,
            'internal': internal,
            'security': nonce,
        },
        success: function (data) {

            if (data.trim() === 'run') {
                wpestate_owner_insert_book_internal();
            } else {
                jQuery('#book_dates').empty().text(ajaxcalls_vars.reserve);
            }
        },
        error: function (errorThrown) {

        }
    });
}

componentForm = {
    establishment: 'long_name',
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    administrative_area_level_2: 'long_name',
    country: 'long_name',
    postal_code: 'short_name',
    postal_code_prefix: 'short_name',
    neighborhood: 'long_name',
    natural_feature: 'long_name'
};



function wpestate_convert_selected_days_reverse(date) {
    // // from yy-mm-dd to - to whatever
    var from, return_date;



    if (control_vars.date_format === 'yy-mm-dd') {
        //default
        return date;
    } else if (control_vars.date_format === 'yy-dd-mm') {
        from = date.split("-");
        return_date = from[0] + '-' + from[2] + '-' + from[1];
        return return_date;

    } else if (control_vars.date_format === 'dd-mm-yy') {
        from = date.split("-");
        return_date = from[2] + '-' + from[1] + '-' + from[0];
        return return_date;
    } else if (control_vars.date_format === 'mm-dd-yy') {
        from = date.split("-");
        return_date = from[1] + '-' + from[2] + '-' + from[0];
        return return_date;
    } else if (control_vars.date_format === 'dd-yy-mm') {
        from = date.split("-");
        return_date = from[2] + '-' + from[0] + '-' + from[1];
        return return_date;
    } else if (control_vars.date_format === 'mm-yy-dd') {
        from = date.split("-");
        return_date = from[1] + '-' + from[0] + '-' + from[2];
        return return_date;
    }

}


function wpestate_convert_selected_days(date) {

    var from, return_date, date_format;


    if (property_vars.book_type === '2') {
        date_format = control_vars.date_format.toUpperCase() + " HH:mm";
    } else {
        date_format = control_vars.date_format.toUpperCase();
    }

    var new_date_format = date_format.replace("YY", "YYYY");



    if (property_vars.book_type === '2') {
        var return_date = moment(date, new_date_format).format('YYYY-MM-DD HH:mm');
    } else {
        var return_date = moment(date, new_date_format).format('YYYY-MM-DD');
    }

    return return_date;

}



function wpestate_convert_selected_days_simple_add_days(date, days) {

    var date_format = control_vars.date_format.toUpperCase();
    var new_date_format = date_format.replace("YY", "YYYY");
    var return_date = moment(date, new_date_format).add(days, 'days');

    return_date = moment(return_date).format(new_date_format);
    return return_date;
}





function wpestate_convert_selected_days_simple(date) {

    var from, return_date;

    var date_format = control_vars.date_format.toUpperCase();
    var new_date_format = date_format.replace("YY", "YYYY");
    var return_date = moment(date, new_date_format).format('DD-MM-YYYY');

    return return_date;
}



function wpestate_UTC_addDays(date, days) {
    var result = new Date(date);
    var now_utc = new Date(result.getUTCFullYear(), result.getUTCMonth(), result.getUTCDate(), result.getUTCHours(), result.getUTCMinutes(), result.getUTCSeconds());
    var new_day = parseFloat(result.getUTCDate()) + 1 + parseFloat(days);
    now_utc.setDate(new_day);
    return now_utc;
}



function wpestaste_check_in_out_enable(in_date, out_date) {
    "use strict";
    wpestaste_check_in_out_enable_new(in_date, out_date);
    return;
}


function wpestaste_check_in_out_enable_new(in_date, out_date) {

    var today, prev_date, read_in_date;
    today = new Date();
    var date_format = control_vars.date_format.toUpperCase();
    today = moment(today).format("MM/DD/YYYY");
    jQuery("#" + in_date).attr('readonly', 'readonly');

    var options = {
        singleDatePicker: false,
        autoApply: true,
        alwaysShowCalendars: true,
        autoUpdateInput: false,
        minDate: today,
        locale: {
            daysOfWeek: dayNamesShort,
            monthNames: longmonths
        },

        isCustomDate: 'isCustomDate_wpstate'

    };

    if (in_date === 'booking_from_date' && jQuery('#contact_for_reservation').length === 0) {
        options.parentEl = "#contact_owner_modal";
    }



    var date_format = control_vars.date_format.toUpperCase();


    date_format = date_format.replace("YY", "YYYY");

    var in_date_front = jQuery('#' + in_date);
    var out_date_front = jQuery('#' + out_date);

    jQuery("#" + in_date).daterangepicker(
            options,
            function (start, end, label) {
                jQuery("#" + out_date).removeAttr('disabled');
                start_date = start.format(date_format);
                end_date = end.format(date_format);

                in_date_front.val(start_date);
                out_date_front.val(end_date);

                if (jQuery("#google_map_prop_list_sidebar").length > 0) {
                    wpestate_start_filtering_ajax_map(1);
                }



            }
    );


    jQuery("#" + in_date).on('click', function () {
        jQuery('.daterangepicker').css('margin-top', '7px');
    });

    jQuery("#" + out_date).on('click', function () {
        jQuery("#" + in_date).trigger('click');
        var parent = jQuery(this).parent().parent().parent();
        var parent2 = jQuery(this).parent().parent().parent().parent();

        if (parent.hasClass('adv-search-2') || parent2.hasClass('adv-search-5')) {
            jQuery('.daterangepicker').css('margin-top', '59px');
        }
        if (parent2.hasClass('adv-search-5')) {
            jQuery('.daterangepicker').css('margin-top', '69px');
        }
    });


}








function isCustomDate_wpstate(date) {
    return 'maca';
}


function wpestate_booking_calendat_get_price(unixtime1_key, display_price) {


    var return_price;
    return_price = '';


    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) { // if we have custom curency
        if (my_custom_curr_cur_post === 'before') {


            if (price_array[unixtime1_key] === undefined) {
                if (display_price === '') { // we DONT have weekend price
                    display_price = control_vars_property.default_price;
                }
                if (parseFloat(price_per_guest_from_one, 10) === 1) {
                    display_price = parseFloat(extra_price_per_guest, 10);
                }
                display_price = wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)) + String(Math.round(display_price * my_custom_curr_coef));

            } else {
                if (display_price === '') { // we DONT have weekend price
                    display_price = price_array[unixtime1_key];
                }
                if (parseFloat(price_per_guest_from_one, 10) === 1) {
                    display_price = mega_details[unixtime1_key]['period_extra_price_per_guest'];
                }
                display_price = wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)) + String(Math.round(display_price * my_custom_curr_coef));
            }
        } else {

            if (price_array[unixtime1_key] === undefined) {
                if (display_price === '') { // we DONT have weekend price
                    display_price = control_vars_property.default_price;
                }
                if (parseFloat(price_per_guest_from_one, 10) === 1) {
                    display_price = parseFloat(extra_price_per_guest, 10);
                }

                display_price = String(Math.round(display_price * my_custom_curr_coef)) + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label));
            } else {
                if (display_price === '') { // we DONT have weekend price
                    display_price = price_array[unixtime1_key];
                }
                if (parseFloat(price_per_guest_from_one, 10) === 1) {
                    display_price = mega_details[unixtime1_key]['period_extra_price_per_guest'];
                }
                display_price = String(Math.round(display_price * my_custom_curr_coef)) + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label));
            }
        }
    } else { // we don't have custom curency

        if (control_vars.where_curency === 'before') {
            if (price_array[unixtime1_key] === undefined) {
                if (display_price === '') { // we DONT have weekend price
                    display_price = control_vars_property.default_price;
                }
                display_price = wpestate_replace_plus(decodeURIComponent(control_vars.curency)) + display_price;
                //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) + display_price ];
            } else {
                if (display_price === '') { // we DONT have weekend price
                    display_price = price_array[unixtime1_key];
                }
                display_price = wpestate_replace_plus(decodeURIComponent(control_vars.curency)) + String(display_price);
                //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) +  String(display_price) ];
            }
        } else {
            if (price_array[unixtime1_key] === undefined) {
                if (display_price === '') { // we DONT have weekend price
                    display_price = control_vars_property.default_price;
                }
                display_price = display_price + wpestate_replace_plus(decodeURIComponent(control_vars.curency));
                //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key,display_price +  wpestate_replace_plus ( decodeURIComponent ( control_vars.curency ) ) ];
            } else {
                if (display_price === '') { // we DONT have weekend price
                    display_price = price_array[unixtime1_key];
                }
                display_price = String(display_price) + wpestate_replace_plus(decodeURIComponent(control_vars.curency));
                // return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, String(display_price)+ wpestate_replace_plus( decodeURIComponent ( control_vars.curency ) ) ];
            }
        }
    }


    if (parseFloat(price_per_guest_from_one, 10) === 1) {
        if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
            var to_show = parseFloat(extra_price_per_guest, 10) * my_custom_curr_coef;
            if (my_custom_curr_cur_post === 'before') {
                display_price = control_vars.from + " " + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)) + ' ' + to_show.toFixed(0);
            } else {
                display_price = control_vars.from + " " + to_show.toFixed(0) + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label));
            }
        } else {
            if (control_vars.where_curency === 'before') {
                display_price = control_vars.from + " " + wpestate_replace_plus(decodeURIComponent(control_vars.curency)) + parseFloat(extra_price_per_guest, 10);
            } else {
                display_price = control_vars.from + " " + parseFloat(extra_price_per_guest, 10) + wpestate_replace_plus(decodeURIComponent(control_vars.curency));
            }

        }

    }





    return display_price;
}


























function wpestate_enable_slider_radius(slider_name, low_val, max_val, now_val) {

    if (jQuery("#" + slider_name).length > 0) {
        jQuery("#" + slider_name).slider({
            range: true,
            min: parseFloat(low_val),
            max: parseFloat(max_val),
            value: parseFloat(now_val),
            range: "max",
            slide: function (event, ui) {

                jQuery("#geolocation_radius").val(ui.value);
                jQuery('.radius_value').text(ui.value + " " + control_vars.geo_radius_measure);


            },
            stop: function (event, ui) {
                if (placeCircle != '') {
                    if (control_vars.geo_radius_measure === 'miles') {
                        placeCircle.setRadius(ui.value * 1609.34);
                    } else {
                        placeCircle.setRadius(ui.value * 1000);
                    }
                    map.fitBounds(placeCircle.getBounds());
                    wpestate_start_filtering_ajax_map(1);

                }
            }
        });
    }

    jQuery("#geolocation_search").on('change', function () {
        if (jQuery(this).val() === '') {
            jQuery('#geolocation_lat').val('');
            jQuery('#geolocation_long').val('');
            if (placeCircle != '') {
                placeCircle.setMap(null);
                placeCircle = '';


            }
        }
    });


    if (jQuery("#geolocation_search").length > 0) {

        if (typeof google === 'object' && typeof google.maps === 'object' && parseInt(mapbase_vars.wprentals_places_type) == 1) {
            var input, defaultBounds, autocomplete_normal;
            input = (document.getElementById('geolocation_search'));
            defaultBounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(-90, -180),
                    new google.maps.LatLng(90, 180)
                    );
            var options = {
                bounds: defaultBounds,
                types: ['geocode'],
                // types: ['(regions)'],
            };

            autocomplete_normal = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete_normal, 'place_changed', function () {
                initial_geolocation_circle_flag = 0;
                var place = autocomplete_normal.getPlace();
                var place_lat = place.geometry.location.lat();
                var place_lng = place.geometry.location.lng();

                jQuery('#geolocation_lat').val(place_lat);
                jQuery('#geolocation_long').val(place_lng);

                wpestate_start_filtering_ajax_map(1);

            });
        }

    }


}




function wpestate_enable_slider(slider_name, price_low, price_max, amount, my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef, my_custom_curr_label) {
    "use strict";
    var price_low_val, price_max_val, temp_min, temp_max;
    price_low_val = parseFloat(jQuery('#' + price_low).val(), 10);
    price_max_val = parseFloat(jQuery('#' + price_max).val(), 10);

    var slider_min = control_vars.slider_min;
    var slider_max = control_vars.slider_max;
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
        slider_min = parseFloat(slider_min * my_custom_curr_coef, 10);
        slider_max = parseFloat(slider_max * my_custom_curr_coef, 10);
    }




    jQuery("#" + slider_name).slider({
        range: true,
        min: parseFloat(slider_min),
        max: parseFloat(slider_max),
        values: [price_low_val, price_max_val],
        slide: function (event, ui) {


            jQuery("#" + price_low).val(ui.values[0]);
            jQuery("#" + price_max).val(ui.values[1]);

            if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {

                temp_min = ui.values[0];
                temp_max = ui.values[1];

                if (my_custom_curr_cur_post === 'before') {
                    jQuery("#" + amount).text(wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)) + " " + temp_min.format() + " " + control_vars.to + " " + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)) + " " + temp_max.format());
                } else {
                    jQuery("#" + amount).text(temp_min.format() + " " + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)) + " " + control_vars.to + " " + temp_max.format() + " " + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)));
                }
            } else {
                if (control_vars.where_curency === 'before') {
                    jQuery("#" + amount).text(wpestate_replace_plus(decodeURIComponent(control_vars.curency)) + " " + ui.values[0].format() + " " + control_vars.to + " " + wpestate_replace_plus(decodeURIComponent(control_vars.curency)) + " " + ui.values[1].format());
                } else {
                    jQuery("#" + amount).text(ui.values[0].format() + " " + wpestate_replace_plus(decodeURIComponent(control_vars.curency)) + " " + control_vars.to + " " + ui.values[1].format() + " " + wpestate_replace_plus(decodeURIComponent(control_vars.curency)));
                }
            }
        }
    });
}


function wpestate_replace_plus(string) {
    return string.replace("+", " ");
}

function wpestate_prevent_enter_submit(main_search, search_location, check_in, check_out, guest_no) {
    jQuery('#' + main_search).on("keyup keypress", function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            if (jQuery('#' + search_location).val() !== '') {
                if (jQuery('#' + check_in).val() !== '') {
                    if (jQuery('#' + check_out).val() !== '') {
                        jQuery('#' + check_out).focusout();
                        jQuery('#' + check_out).datepicker("hide");
                        jQuery('#' + guest_no).focus().dropdown('toggle');
                        jQuery('#' + check_out).datepicker("hide");
                    } else {
                        jQuery('#' + check_in).datepicker("hide");
                        jQuery('#' + check_out).datepicker("show");
                    }
                } else {
                    jQuery('#' + check_in).datepicker("show");
                }
            }

            return false;
        }
    });
}

function wpestate_lazy_load_carousel_property_unit() {

    jQuery('.property_unit_carousel img').each(function (event) {
        var new_source = '';

        new_source = jQuery(this).attr('data-lazy-load-src');
        if (typeof (new_source) !== 'undefined' && new_source !== '') {
            jQuery(this).attr('src', new_source);
        }
    });
}


function wpestate_open_property_card_setup() {

    jQuery('.agent_unit, .blog_unit,.blog_unit_back,.places_wrapper,.places_wrapper_design_2_wrapper,.featured_agent,.places_slider_wrapper_type_1').on('click', function () {
        var link;
        link = jQuery(this).attr('data-link');
        window.open(link, '_self');
    });



//.property_listing .carousel a
    jQuery('.featured_listing_title').on('click', function (event) {
        event.preventDefault();
    });




    jQuery('.property_listing').on('click', function (event) {
        // event.stopPropagation();
        var link, classevent;
        var event_parent = jQuery(event.target).parent();
        classevent = jQuery(event.target);

        if (classevent.hasClass('carousel-control') || classevent.hasClass('icon-left-open-big') || classevent.hasClass('icon-right-open-big')) {
            return;
        }

        if (event_parent.hasClass('category_tagline')) {
            return;
        }


        link = jQuery(this).attr('data-link');
        if (typeof (link) !== "undefined") {
            window.open(link, control_vars.new_tab);
        }
    });




    ////////////////////////////////////////////////////////////////////////////
    // listing map actions
    ////////////////////////////////////////////////////////////////////////////

    jQuery("#google_map_prop_list_sidebar .listing_wrapper").on("mouseenter", function (event) {
        event.stopPropagation();
        var listing_id = jQuery(this).attr('data-listid');
        if (typeof wpestate_hover_action_pin == 'function') {
            wpestate_hover_action_pin(listing_id);
        }
    });

    jQuery("#google_map_prop_list_sidebar .listing_wrapper").on("mouseleave", function (event) {
        event.stopPropagation();
        var listing_id = jQuery(this).attr('data-listid');
        if (typeof wpestate_return_hover_action_pin == 'function') {
            wpestate_return_hover_action_pin(listing_id);
        }
    });

}










jQuery(document).ready(function ($) {
    "use strict";
    var bLazy, search_label, curent, price_regular, price_featured, total, percent, parent, price_low_val, price_max_val, autoscroll_slider, all_browsers_stuff, wrap_h, map_h, mediaQuery;

    $.datepicker.setDefaults($.datepicker.regional[control_vars.datepick_lang]);
    $.datepicker.setDefaults({
        dateFormat: control_vars.date_format,
    });


    wpestate_open_property_card_setup();
    wpestate_slider_box();

    wpestate_mobile_menu_slideout();


    if (control_vars.wp_estate_slider_cycle != '' && control_vars.wp_estate_slider_cycle != '0') {

        $('#estate-carousel_slick').slick({
            'arrows': true,
            'dots': true,

            'cssEase': 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
            'autoplay': true,
            'autoplaySpeed': control_vars.wp_estate_slider_cycle
        });
    } else {
        $('#estate-carousel_slick').slick({
            'arrows': true,
            'dots': true,

            'cssEase': 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
        });

    }



    jQuery('.show_hide_password').on('click', function(){
        var pass_field = jQuery(this).parent().find(':input');
        
      
        
        if (pass_field.attr('type') === "password") {
            pass_field.attr('type','text');
             jQuery(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            pass_field.attr('type','password');
          
            
             jQuery(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });


    $('#listing_main_image_photo_slider').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        'arrows': true,
        responsive: [
            {
                breakpoint: 1025,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    if (control_vars.is_rtl === '1') {
        $('#listing_main_image_photo_slider').slick('slickSetOption', 'rtl', true, true);
    }

    if (Modernizr.mq('only all and (min-width: 1024px)')) {
        $('#estate-carousel_slick').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            $('.item-version').hide(200);
            $('.price-slider-wrapper').hide(300);
            $('.theme-slider-view').hide(400);
        });


        $('#estate-carousel_slick').on('afterChange', function (event, slick, currentSlide, nextSlide) {
            $('.item-version').slideDown('200', function () {
                $('.price-slider-wrapper').slideDown(300);
                $('.theme-slider-view').slideDown(600);

            });
        });
    }




    if ($(".full_screen_yes").length) {
        var new_height;
        if (jQuery('.transparent_header').length > 0) {
            new_height = jQuery(window).height();
        } else {
            new_height = jQuery(window).height() - jQuery('.master_header').height();
        }

        if ($('.with_search_on_start').length > 0) {
            new_height = new_height - jQuery('.search_wrapper.with_search_on_start ').height();
        }

        jQuery('.wpestate_header_image,.wpestate_header_video,.theme_slider_wrapper,.theme_slider_classic,.theme_slider_wrapper .item_type2 ').css('height', new_height);
    }





    $('.search_location_autointernal_list li').on('click', function (event) {

        var meta_tax = $(this).attr('data-tax');
        var parent = $(this).parent().parent().parent();
        parent.find('.stype').val(meta_tax);
    });





    var handler_top;
    $('.adv_handler').on('click', function (event) {
        event.preventDefault();

        var check_row = $(this).parent().find('.adv_search_hidden_fields');
        var parent = $(this).parent().parent();

        if ($('#search_wrapper').hasClass('with_search_form_float')) {
            if (!$('#search_wrapper').hasClass('openmore')) {
                check_row.css('display', 'block');
                var height = check_row.height();
                handler_top = parseInt($('#search_wrapper').css('top'));
                var top = parseInt($('#search_wrapper').css('top')) - height;

                check_row.css('display', 'none');

                parent.find('.adv_search_hidden_fields').slideDown({duration: 200, queue: false});
                $('#search_wrapper').addClass('openmore');
            } else {

                parent.find('.adv_search_hidden_fields').slideUp({duration: 200, queue: false});
                $('#search_wrapper').removeClass('openmore');
            }
        } else {

            parent.find('.adv_search_hidden_fields').slideToggle();
        }



    });









    ////////////////////////////
    // taxonomy slick slider
    ////////////////////////////

    $('.estate_places_slider').each(function () {
        var items = $(this).attr('data-items-per-row');
        var auto = parseInt($(this).attr('data-auto'));
        var slick = $(this).slick({
            infinite: true,
            slidesToShow: items,
            slidesToScroll: 1,
            dots: false,

            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        if (control_vars.is_rtl === '1') {
            $(this).slick('slickSetOption', 'rtl', true, true);
            $(this).slick('slidesToScroll', '-1');
        }
    });


    wpestate_enable_stripe_booking_prop();




    //////direct pay//////////////////////////////////////////////////////////////////////

    jQuery('#direct_pay').on('click', function () {
        var direct_pay_modal, selected_pack, selected_prop, include_feat, attr, price_pack;

        selected_pack = $('#pack_select').val();
        var price_pack = $('#pack_select option:selected').attr('data-price');

        if (control_vars.where_curency === 'after') {
            price_pack = price_pack + ' ' + control_vars.submission_curency;
        } else {
            price_pack = control_vars.submission_curency + ' ' + price_pack;
        }

        price_pack = control_vars.direct_price + ': ' + price_pack;

        if (selected_pack !== '') {
            window.scrollTo(0, 0);
            direct_pay_modal = '<div class="modal fade" id="direct_pay_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h2 class="modal-title_big">' + control_vars.direct_title + '</h2></div><div class="modal-body listing-submit"><span class="to_be_paid">' + price_pack + '</span><span>' + control_vars.direct_pay + '</span><div id="send_direct_bill" data-pack="' + selected_pack + '">' + control_vars.send_invoice + '</div></div></div></div></div></div>';
            jQuery('body').append(direct_pay_modal);
            jQuery('#direct_pay_modal').modal();
            wpestate_enable_direct_pay();
        }

        $('#direct_pay_modal').on('hidden.bs.modal', function (e) {
            $('#direct_pay_modal').remove();
        });

    });


    $('.perpack').on('click', function () {
        var direct_pay_modal, selected_pack, selected_prop, include_feat, attr;
        selected_prop = $(this).attr('data-listing');

        var price_pack = $(this).parent().parent().find('.submit-price-total').text();

        attr = $(this).attr('data-isupgrade');
        if (typeof attr !== typeof undefined && attr !== false) {
            price_pack = $(this).parent().parent().find('.submit-price-featured').text();
        }

        if (control_vars.where_curency === 'after') {
            price_pack = price_pack + ' ' + control_vars.submission_curency;
        } else {
            price_pack = control_vars.submission_curency + ' ' + price_pack;
        }

        price_pack = control_vars.direct_price + ': ' + price_pack;


        include_feat = ' data-include-feat="0" ';
        $('#send_direct_bill').attr('data-include-feat', 0);
        $('#send_direct_bill').attr('data-listing', selected_prop);

        if ($(this).parent().find('.extra_featured').attr('checked')) {
            include_feat = ' data-include-feat="1" ';
            $('#send_direct_bill').attr('data-include-feat', 1);
        }


        if (typeof attr !== typeof undefined && attr !== false) {
            include_feat = ' data-include-feat="1" ';
            $('#send_direct_bill').attr('data-include-feat', 1);
        }


        window.scrollTo(0, 0);
        direct_pay_modal = '<div class="modal fade" id="direct_pay_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h2 class="modal-title_big">' + control_vars.direct_title + '</h2></div><div class="modal-body listing-submit"><span class="to_be_paid">' + price_pack + '</span><span>' + control_vars.direct_pay + '</span><div id="send_direct_bill" ' + include_feat + ' data-listing="' + selected_prop + '">' + control_vars.send_invoice + '</div></div></div></div></div></div>';
        jQuery('body').append(direct_pay_modal);
        jQuery('#direct_pay_modal').modal();
        wpestate_enable_direct_pay_perlisting();

        $('#direct_pay_modal').on('hidden.bs.modal', function (e) {
            $('#direct_pay_modal').remove();
        });

    });



    function wpestate_enable_direct_pay() {
        jQuery('#send_direct_bill').on('click', function () {
            jQuery('#send_direct_bill').unbind('click');
            var selected_pack, ajaxurl;
            selected_pack = jQuery(this).attr('data-pack');
            ajaxurl = ajaxcalls_vars.admin_url + 'admin-ajax.php';
            var nonce = jQuery('#wprentals_payments_actions').val();

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action': 'wpestate_direct_pay_pack',
                    'selected_pack': selected_pack,
                    'security': nonce,
                },
                success: function (data) {
                    jQuery('#send_direct_bill').hide();
                    jQuery('#direct_pay_modal .listing-submit span:nth-child(2)').empty().html(control_vars.direct_thx);

                },
                error: function (errorThrown) {}
            });//end ajax




        });

    }



    function  wpestate_enable_direct_pay_perlisting() {
        jQuery('#send_direct_bill').unbind('click');
        jQuery('#send_direct_bill').on('click', function () {
            jQuery('#send_direct_bill').unbind('click');
            var selected_pack, ajaxurl, include_feat;

            selected_pack = jQuery(this).attr('data-listing');
            include_feat = jQuery(this).attr('data-include-feat');
            ajaxurl = ajaxcalls_vars.admin_url + 'admin-ajax.php';

            var nonce = jQuery('#wprentals_payments_actions').val();
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action': 'wpestate_direct_pay_pack_per_listing',
                    'selected_pack': selected_pack,
                    'include_feat': include_feat,
                    'security': nonce
                },
                success: function (data) {
                    jQuery('#send_direct_bill').hide();
                    jQuery('#direct_pay_modal .listing-submit span:nth-child(2)').empty().html(control_vars.direct_thx);
                },
                error: function (errorThrown) {}
            });//end ajax

        });

    }










    /////////////////////////////////////////////////////////////////////////////
    if (document.getElementById('imagelist')) {
        $("#imagelist").sortable({
            revert: true,
            update: function (event, ui) {
                var all_id, new_id;
                all_id = "";
                $("#imagelist .uploaded_images").each(function () {

                    new_id = $(this).attr('data-imageid');
                    if (typeof new_id != 'undefined') {
                        all_id = all_id + "," + new_id;

                    }

                });

                $('#attachid').val(all_id);
            },
        });
    }





    $('.retina_ready').dense();


    $('#user_tab_menu_trigger').on('click', function () {
        $('#user_tab_menu_container').toggle('200');
    });

    $('#carousel-listing').on('slid.bs.carousel', function () {
        wpestate_show_capture_vertical();
        $('#carousel-listing div').removeClass('slideron');
        $('#slider_enable_slider').addClass('slideron');
    });

    ////////////////////////////////////////////////////////////////////////////
    ///carousel show controls
    ////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////
    ///prevent form submit on enter
    ////////////////////////////////////////////////////////////////////////////
    wpestate_prevent_enter_submit('main_search', 'search_location', 'check_in', 'check_out', 'guest_no');
    wpestate_prevent_enter_submit('widget_search', 'search_locationsidebar', 'checkinwidget', 'checkoutwidget', 'guest_no_wid');
    wpestate_prevent_enter_submit('form-search-mobile', 'search_location_mobile', 'check_in_mobile', 'check_out_mobile', 'guest_no_mobile');

    ////////////////////////////////////////////////////////////////////////////
    ///unit clicks
    ////////////////////////////////////////////////////////////////////////////
    jQuery('.blog_unit_back ').on('click', function () {
        window.open($(this).find('.blog-title-link').attr('href'), '_self', false);
    });



    jQuery(".calendar_pad").on("mouseenter", function (event) {
        var timeunix = $(this).attr('data-curent-date');
        jQuery(".calendar_pad[data-curent-date=" + timeunix + "]").addClass('calendar-pad-hover');
        jQuery(".calendar_pad_title[data-curent-date=" + timeunix + "]").addClass('calendar-pad-hover');


        if (jQuery(this).hasClass('calendar-reserved')) {
            var reservation_data = $(this).find('.allinone_reservation');
            reservation_data.show();
            var internal_booking_id = parseFloat($(this).find('.allinone_reservation').attr('data-internal-reservation'), 10);
            if (!isNaN(internal_booking_id) && internal_booking_id != 0) {
                var ajaxurl = ajaxcalls_vars.admin_url + 'admin-ajax.php';

                var nonce = jQuery('#wprentals_allinone').val();
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,

                    data: {
                        'action': 'wpestate_get_booking_data',
                        'internal_booking_id': internal_booking_id,
                        'security': nonce
                    },
                    success: function (data) {
                        reservation_data.empty().append(data);

                    },
                    error: function (errorThrown) {}
                });//end ajax
            }
        }

    });


    jQuery(".calendar_pad").on("mouseleave", function (event) {
        $(this).find('.allinone_reservation').hide();
        var timeunix = $(this).attr('data-curent-date');
        $(".calendar_pad[data-curent-date=" + timeunix + "]").removeClass('calendar-pad-hover');
        $(".calendar_pad_title[data-curent-date=" + timeunix + "]").removeClass('calendar-pad-hover');
    });



    ////////////////////////////////////////////////////////////////////////////
    // mobile menu
    ////////////////////////////////////////////////////////////////////////////

    $('.all-elements').animate({
        minHeight: 100 + '%'
    });
    $('.header-tip').addClass('hide-header-tip');

/*
    $('.mobile-trigger').on('click', function () {
        if ($('#all_wrapper').hasClass('moved_mobile')) {
            $('.mobilewrapper-user').show();
            $('#all_wrapper').removeAttr('style');
            $('#all_wrapper').removeClass('moved_mobile');
            $('.mobilewrapper').removeAttr('style');
        } else {
            $('.mobilewrapper-user').hide();
            $('.mobilewrapper').show();
            $('#all_wrapper').css('-webkit-transform', 'translate(265px, 0px)');
            $('#all_wrapper').css('-moz-transform', 'translate(265px, 0px)');
            $('#all_wrapper').css('-ms-transform', 'translate(265px, 0px)');
            $('#all_wrapper').css('-o-transform', 'translate(265px, 0px)');

            $('#all_wrapper').addClass('moved_mobile');

            $('.mobilewrapper').css('-webkit-transform', 'translate(0px, 0px)');
            $('.mobilewrapper').css('-moz-transform', 'translate(0px, 0px)');
            $('.mobilewrapper').css('-ms-transform', 'translate(0px, 0px)');
            $('.mobilewrapper').css(' -o-transform', 'translate(0px, 0px)');
        }
    });

    $('.mobile-trigger-user').on('click', function () {
        if ($('#all_wrapper').hasClass('moved_mobile_user')) {
            $('#all_wrapper').removeClass('moved_mobile_user');

            $('#all_wrapper').removeAttr('style');
            $('.mobilewrapper-user').hide();
            $('.mobilewrapper').show();
            $('.mobilewrapper-user').removeAttr('style');

        } else {
            $('#all_wrapper').css('-webkit-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-moz-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-ms-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-o-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').addClass('moved_mobile_user');

            $('.mobilewrapper-user').show();
            $('.mobilewrapper').hide();
            $('.mobilewrapper-user').css('-webkit-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css('-moz-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css('-ms-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css(' -o-transform', 'translate(0px, 0px)');
            jQuery('#login_user_wd_mobile').focus();
        }
    });

*/

    $('.user_tab_menu_close').on('click', function () {
        $('#all_wrapper').removeAttr('style');
        $('#all_wrapper').removeClass('moved_mobile_user');
        $('#user_tab_menu_container').removeAttr('style');
    });

/*
    $('.mobilemenu-close-user').on('click', function () {
        $('#all_wrapper').removeAttr('style');
        $('#all_wrapper').removeClass('moved_mobile_user');
        $('.mobilewrapper-user').removeAttr('style');
    });

    $('.mobilemenu-close').on('click', function () {
        $('.mobilewrapper-user').show();
        $('#all_wrapper').removeAttr('style');
        $('#all_wrapper').removeClass('moved_mobile');
        $('.mobilewrapper').removeAttr('style');
    });
*/

    $('.mobilex-menu li').on('click', function (event) {
        event.stopPropagation();
        var selected;
        selected = $(this).find('.sub-menu:first');
        selected.slideToggle();
    });


    $('#user_menu_u').on('click', function (event) {
        jQuery('#wpestate_header_shoping_cart').fadeOut(400);
        if ($('#user_menu_open').is(":visible")) {
            $('#user_menu_open').removeClass('iosfixed').fadeOut(400);
        } else {
            $('#user_menu_open').fadeIn(400);
        }
        event.stopPropagation();
    });

    $('#shopping-cart').on('click', function (event) {
        $('#user_menu_open').removeClass('iosfixed').fadeOut(400);
        if ($('#wpestate_header_shoping_cart').is(":visible")) {
            jQuery('#wpestate_header_shoping_cart').fadeOut(400);
        } else {
            jQuery('#wpestate_header_shoping_cart').fadeIn(400);
        }
        event.stopPropagation();
    });




    $(document).on('click', function (event) {
        var clicka;
        clicka = event.target.id;
        jQuery('#wpestate_header_shoping_cart').fadeOut(400);
        if (!$('#' + clicka).parents('.topmenux').length) {
            $('#user_menu_open').removeClass('iosfixed').hide(400);
        }
    });

    ////////////////////////////////////////////////////////////////////////////
    // multiple cur set cookige
    ////////////////////////////////////////////////////////////////////////////

    $('.list_sidebar_currency li').on('click', function () {
        var ajaxurl, data, pos, symbol, coef, curpos, symbol2;
        data = $(this).attr('data-value');
        pos = $(this).attr('data-pos');
        symbol = $(this).attr('data-symbol');
        coef = $(this).attr('data-coef');
        curpos = $(this).attr('data-curpos');
        symbol2 = $(this).attr('data-symbol2');
        var nonce = jQuery('#wprentals_change_currency').val();
        ajaxurl = ajaxcalls_vars.admin_url + 'admin-ajax.php';
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'wpestate_set_cookie_multiple_curr',
                'curr': data,
                'pos': pos,
                'symbol': symbol,
                'symbol2': symbol2,
                'coef': coef,
                'curpos': curpos,
                'security': nonce,
            },
            success: function (data) {
                location.reload();
            },
            error: function (errorThrown) {}
        });//end ajax
    });




    ////////////////////////////////////////////////////////////////////////////
    ///prop list header
    ////////////////////////////////////////////////////////////////////////////
    $('.adv_extended_options_text').on('click', function () {
        var parent = $(this).parent();
        parent.find('#extended_search_check_filter').slideDown();
        parent.find('.extended_search_check_wrapper').slideDown();
        parent.find('.adv_extended_close_button').show();
        $(this).hide();
    });

    $('.adv_extended_close_button').on('click', function () {
        $(this).hide();
        var parent = $(this).parent().parent();
        parent.find('#extended_search_check_filter').slideUp();
        parent.find('.extended_search_check_wrapper').slideUp();
        parent.find('.adv_extended_options_text').show();
    });

    $('#adv_extended_options_show_filters').on('click', function () {
        $('#advanced_search_map_list').addClass('move_to_fixed');
        $('#extended_search_check_filter').slideDown();
        $('#adv_extended_close_adv').show();
        $('#adv_extended_options_text_adv').hide();
    });




    wpestaste_check_in_out_enable('check_in', 'check_out'); //advanced search
    wpestaste_check_in_out_enable('check_in_list', 'check_out_list');// half map search
    wpestaste_check_in_out_enable('booking_from_date', 'booking_to_date'); // owner contact
    wpestaste_check_in_out_enable('check_in_mobile', 'check_out_mobile'); //advanced search mobile
    wpestaste_check_in_out_enable('check_in_widget', 'check_out_widget'); //search form widget
    wpestaste_check_in_out_enable('check_in_shortcode', 'check_out_shortcode'); //search form shortcode search




    // today = new Date();
    // jQuery("#testx").datepicker({ dateFormat: "yy-m-d" });
    $('#ui-datepicker-div').css('clip', 'auto');



    ////////////////////////////////////////////////////////////////////////////
    /// stripe
    ////////////////////////////////////////////////////////////////////////////
    $('#pack_select').change(function () {

        if ($(this).val() !== '') {
            $('.pay_disabled').removeClass('pay_disabled');
        } else {
            $('.payments_buttons_wrapper').addClass('pay_disabled');
        }

        var stripe_pack_id, stripe_ammount, the_pick, labelstripe;
        $("#pack_select option:selected").each(function () {
            stripe_pack_id = $(this).val();
            stripe_ammount = parseFloat($(this).attr('data-price'));
            the_pick = $(this).attr('data-pick');
            labelstripe = $(this).text();
        });

        $('#pack_id').val(stripe_pack_id);
        $('#pay_ammout').val(stripe_ammount);

        $('.wpestate_stripe_pay_desc').html(control_vars.stripe_pay_for + " " + labelstripe);
        $('#wpestate_stripe_form_button_sumit').html(control_vars.stripe_pay + " " + stripe_ammount + " " + control_vars.submission_curency);


    });

    $('#pack_recuring').on('click', function () {
        if ($(this).attr('checked')) {
            $('#stripe_form').append('<input type="hidden" name="stripe_recuring" id="stripe_recuring" value="1">');
        } else {
            $('#stripe_recuring').remove();
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////
    // listing menu
    /////////////////////////////////////////////////////////////////////////////////////////
    $('.check_avalability, .property_menu_wrapper_hidden a').on('click', function () {
        var target;
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            target = $(this.hash);
            if (target.selector === '#carousel-control-theme-next' || target.selector === '#carousel-control-theme-prev' || target.selector === '#carousel-listing' || target.selector === '#carousel-example-generic' || target.selector === '#post_carusel_right') {
                return;
            }
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 140
                }, 1000);
                return false;
            }
        }
    });






    componentForm = {
        establishment: 'long_name',
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        administrative_area_level_2: 'long_name',
        administrative_area_level_3: 'long_name',
        administrative_area_level_4: 'long_name',
        country: 'long_name',
        postal_code: 'short_name',
        postal_code_prefix: 'short_name',
        neighborhood: 'long_name',
        sublocality_level_1: 'long_name',
        natural_feature: 'long_name'
    };




    wpestate_autocomplete_google('search_location');
    wpestate_autocomplete_google('search_locationshortcode');
    wpestate_autocomplete_google('search_locationmobile');
    wpestate_autocomplete_google('search_locationsidebar');
    wpestate_autocomplete_google('search_locationhalf');



    function wpestate_autocomplete_google(input_id) {

        if (typeof google === 'object' && typeof google.maps === 'object' && parseInt(mapbase_vars.wprentals_places_type) == 1) {
            var input, defaultBounds, autocomplete_normal, extension;
            input = (document.getElementById(input_id));
            if (input instanceof HTMLInputElement) {
                extension = '';
                if (input_id == 'search_locationshortcode') {
                    extension = 'shortcode';
                } else if (input_id == 'search_locationmobile') {
                    extension = 'mobile';
                } else if (input_id == 'search_locationsidebar') {
                    extension = 'sidebar';
                } else if (input_id == 'search_locationhalf') {
                    extension = 'half';
                }

                defaultBounds = new google.maps.LatLngBounds(
                        new google.maps.LatLng(-90, -180),
                        new google.maps.LatLng(90, 180)
                        );
                var options = {
                    bounds: defaultBounds,
                    types: ['geocode'],
                };

                autocomplete_normal = new google.maps.places.Autocomplete(input, options);
                google.maps.event.addListener(autocomplete_normal, 'place_changed', function () {
                    var place = autocomplete_normal.getPlace();
                    wprentals_fillInAddress_filter_google(place, extension);
                });
            }
        }
    }


    function wprentals_fillInAddress_filter_google(place, extension) {

        var i, addressType, val, is_google_map, have_city, admin_area;
        have_city = 0;
        admin_area = '';


        $('#advanced_area' + extension).val('');
        $('#advanced_city' + extension).val('');
        $('#advanced_country' + extension).val('');
        $('#search_location_area' + extension).val('');
        $('#search_location_city' + extension).val('');
        $('#search_location_country' + extension).val('');
        $('#property_admin_area' + extension).val('');



        for (i = 0; i < place.address_components.length; i++) {
            addressType = place.address_components[i].types[0];

            val = place.address_components[i][componentForm[addressType]];

            if (typeof (val) !== 'undefined') {
                val = val.toLowerCase();
                val = val.split(' ').join('-');
            }

            if (addressType === 'neighborhood' || addressType === 'sublocality_level_1') {
                $('#advanced_area' + extension).attr('data-value', val);
                $('#advanced_area' + extension).val(val);
                $('#search_location_area' + extension).val(val);
            } else if (addressType === 'administrative_area_level_4') {
                admin_area = wpestate_build_admin_area(admin_area, val);
            } else if (addressType === 'administrative_area_level_3') {
                admin_area = wpestate_build_admin_area(admin_area, val);
            } else if (addressType === 'administrative_area_level_2') {
                admin_area = wpestate_build_admin_area(admin_area, val);
            } else if (addressType === 'administrative_area_level_1') {
                admin_area = wpestate_build_admin_area(admin_area, val);
            } else if (addressType === 'locality') {

                $('#advanced_city' + extension).attr('data-value', val);
                $('#advanced_city' + extension).val(val);
                $('#search_location_city' + extension).val(val);
                if (val !== '') {
                    have_city = 1;
                }
            } else if (addressType === 'country' || addressType === 'natural_feature') {

                $('#advanced_country' + extension).attr('data-value', val);
                $('#advanced_country' + extension).val(val);
                $('#search_location_country' + extension).val(val);

            }


        }

        if (have_city === 0) {
            wpestate_second_measure_city('advanced_city' + extension, place.adr_address);
            wpestate_second_measure_city('search_location_city' + extension, place.adr_address);
        }


        if (jQuery('#advanced_search_map_list').length > 0) {
            wpestate_start_filtering_ajax_map(1);
        }


        is_google_map = parseFloat(jQuery('#isgooglemap').attr('data-isgooglemap'), 10);
        if (is_google_map === 1) {
            var guest_val = $(this).attr('data-value');
            //start_filtering_ajax_on_main_map(guest_val);


        }
    }

    function wpestate_build_admin_area(admin_area, val) {
        if (admin_area === '') {
            admin_area = admin_area + val;
        } else {
            admin_area = admin_area + ", " + val;
        }

        $('#property_admin_area,#property_admin_areasidebar,#property_admin_areashortcode,#property_admin_areamobile').val(admin_area);

        return admin_area;
    }


    ////////
    function  wpestate_second_measure_city(stringplace, adr_address) {
        var new_city;
        new_city = $(adr_address).filter('span.locality').html();

        $('#' + stringplace).val(new_city);
    }






    function wpestate_fillInAddress() {
        var place, adr, country;
        place = autocomplete.getPlace();
        adr = place['address_components'];
        country = adr[adr.length - 1]['long_name'];
        document.getElementById('property_city').value = place['name'];
        document.getElementById('property_country').value = country;
    }

    $('#check_out').change(function () {
        if ($('#check_in').val() !== '') {
            var guest_val = $(this).attr('data-value');
            // start_filtering_ajax_on_main_map(guest_val);
        }
    });

    $('#guest_no_main_list li').on('click', function () {
        var guest_val = $(this).attr('data-value');
        //  start_filtering_ajax_on_main_map(guest_val);
    });



    ////////////////////////////////////////////////////////////////////////////
    // top bar login
    ////////////////////////////////////////////////////////////////////////////
    $('#topbarlogin').on('click', function (event) {
        wpestate_show_login_form(1, 0, 0);
    });

    $('#topbarregister').on('click', function (event) {
        wpestate_show_login_form(2, 0, 0);
    });

    ////////////////////////////////////////////////////////////////////////////
    /// slider price
    ////////////////////////////////////////////////////////////////////////////

    price_low_val = parseFloat($('#price_low').val(), 10);
    price_max_val = parseFloat($('#price_max').val(), 10);




    if (jQuery('#slider_price').length > 0) {
        wpestate_enable_slider('slider_price', 'price_low', 'price_max', 'amount', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef, my_custom_curr_label);
    }

    if (jQuery('#slider_price_widget').length > 0) {
        wpestate_enable_slider('slider_price_widget', 'price_low_widget', 'price_max_widget', 'amount_wd', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef, my_custom_curr_label);
    }
    if (jQuery('#slider_price_sh').length > 0) {
        wpestate_enable_slider('slider_price_sh', 'price_low_sh', 'price_max_sh', 'amount_sh', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef, my_custom_curr_label);
    }
    if (jQuery('#slider_price_mobile').length > 0) {
        wpestate_enable_slider('slider_price_mobile', 'price_low_mobile', 'price_max_mobile', 'amount_mobile', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef, my_custom_curr_label);
    }

    if (jQuery('#wpestate_slider_radius').length > 0) {
        wpestate_enable_slider_radius('wpestate_slider_radius', control_vars.min_geo_radius, control_vars.max_geo_radius, control_vars.initial_radius);
    }

    function wpestate_slider_control_left_function(element) {
        var step_size, margin_left, new_value, last_element, base_value, parent;
        parent = element.parent();
        step_size = parent.find('.shortcode_slider_list').width();
        margin_left = parseFloat(parent.find('.shortcode_slider_list').css('margin-left'), 10);
        new_value = margin_left - 389;
        base_value = 3;
        parent.find('.shortcode_slider_list').css('margin-left', new_value + 'px');
        last_element = parent.find('.shortcode_slider_list li:last-child');
        parent.find('.shortcode_slider_list li:last-child').remove();
        parent.find('.shortcode_slider_list').prepend(last_element);
        wpestate_restart_js_after_ajax();
        parent.find('.shortcode_slider_list').animate({
            'margin-left': base_value
        }, 800, function () {
        });

    }

    function wpestate_slider_control_right_function(elemenet) {
        var step_size, margin_left, new_value, first_element, parent;
        parent = elemenet.parent();
        step_size = parent.find('.shortcode_slider_list').width();
        margin_left = parseFloat(parent.find('.shortcode_slider_list').css('margin-left'), 10);
        new_value = margin_left - 389;
        parent.find('.shortcode_slider_list').animate({
            'margin-left': new_value
        }, 800, function () {
            first_element = parent.find('.shortcode_slider_list li:nth-child(1)');
            parent.find('.shortcode_slider_list li:nth-child(1)').remove();
            parent.find('.shortcode_slider_list').append(first_element);
            parent.find('.shortcode_slider_list').css('margin-left', 3 + 'px');

            wpestate_restart_js_after_ajax();
        });
    }

    $('.slider_control_left').on('click', function () {
        wpestate_slider_control_left_function($(this));
        //bLazy.revalidate();
    });

    $('.slider_control_right').on('click', function () {
        wpestate_slider_control_right_function($(this));
        //bLazy.revalidate();
    });

    $('.slider_container ').each(function () {
        var element, wrapper;
        element = $(this).find(".slider_control_right");
        wrapper = $(this).find(".shortcode_slider_wrapper").attr('data-auto');

        autoscroll_slider = parseFloat(wrapper, 10);
        if (autoscroll_slider !== 0) {

            setInterval(function () {
                wpestate_slider_control_right_function(element);
            }, autoscroll_slider);
        }

    });


    $('#login_user_topbar,#login_pwd_topbar').on('focus', function (e) {
        $('#user_menu_open').addClass('iosfixed');
    });

    $('#estate-carousel .slider-content h3 a,#estate-carousel .slider-content .read_more ').on('click', function () {
        var new_link;
        new_link = $(this).attr('href');
        window.open(new_link, '_self', false);
    });

    ////////////////////////////////////////////////////////////////////////////////////////////
    ///city-area-selection
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('#filter_city li').on('click', function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city = String($(this).attr('data-value2')).toLowerCase();

        $('#filter_area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value = String($(this).attr('data-value')).toLowerCase();

            if (is_city === value_city || value_city === 'all' || is_city === 'undefined') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#sidebar_filter_city li').on('click', function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city = String($(this).attr('data-value2')).toLowerCase();
        $('#sidebar_filter_area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#adv-search-city li').on('click', function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city = String($(this).attr('data-value2')).toLowerCase();

        $('#adv-search-area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#property_city_submit').change(function () {
        var city_value, area_value;
        city_value = $(this).val();
        all_browsers_stuff = $('#property_area_submit_hidden').html();
        $('#property_area_submit').empty().append(all_browsers_stuff);
        $('#property_area_submit option').each(function () {
            area_value = $(this).attr('data-parentcity');
            if (city_value === area_value || area_value === 'all') {
                //  $(this).show();
            } else {
                //$(this).hide();
                $(this).remove();
            }
        });
    });

    $('#adv_short_select_city li').on('click', function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city = String($(this).attr('data-value2')).toLowerCase();
        $('#adv_short_select_area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#mobile-adv-city li').on('click', function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city = String($(this).attr('data-value2')).toLowerCase();
        $('#mobile-adv-area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    ////////////////////////////////////////////////////////////////////////////////////////////
    ///mobile
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('#adv-search-header-mobile').on('click', function () {
        $('#adv-search-mobile').toggle('300');
    });

    ////////////////////////////////////////////////////////////////////////////////////////////
    ///navigational links
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('.nav-prev,.nav-next ').on('click', function (event) {
        event.preventDefault();
        var link = $(this).find('a').attr('href');
        window.open(link, '_self', false);
    });

    $('.featured_agent_details_wrapper, .agent-listing-img-wrapper').on('click', function () {
        var newl = $(this).attr('data-link');
        window.open(newl, '_self', false);
    });

    $('.see_my_list_featured').on('click', function (event) {
        event.stopPropagation();
    });

    $('.featured_cover').on('click', function () {
        var newl = $(this).attr('data-link');
        window.open(newl, '_self', false);
    });



    jQuery(".agent_face").on("mouseenter", function (event) {
        $(this).find('.agent_face_details').fadeIn('500');
    });

    jQuery(".agent_face").on("mouseleave", function (event) {
        $(this).find('.agent_face_details').fadeOut('500');
    });



    jQuery('#imagelist i').on('click', function () {
        var curent = '';
        jQuery(this).parent().remove();
        jQuery('#imagelist .uploaded_images').each(function () {
            curent = curent + ',' + jQuery(this).attr('data-imageid');
        });
        jQuery('#attachid').val(curent);
    });

    jQuery('#imagelist img').dblclick(function () {
        jQuery('#imagelist .uploaded_images .thumber').each(function () {
            jQuery(this).remove();
        });
        jQuery('.fa-star').remove();
        jQuery(this).parent().append('<i class="fas thumber fa-star"></i>');
        jQuery('#attachthumb').val(jQuery(this).parent().attr('data-imageid'));
    });

    $('.advanced_search_sidebar li').on('click', function (event) {
        event.preventDefault();
        var pick, value, parent;
        pick = $(this).text();
        value = $(this).attr('data-value');
        parent = $(this).parent().parent();
        parent.find('.filter_menu_trigger').text(pick).append('<span class="caret caret_sidebar"></span>').attr('data-value', value);
        parent.find('input').val(value);
    });

    $('.adv-search-mobile li').on('click', function (event) {
        event.preventDefault();
        var pick, value, parent;
        pick = $(this).text();
        value = $(this).attr('data-value');
        parent = $(this).parent().parent();
        parent.find('.filter_menu_trigger').text(pick).append('<span class="caret caret_filter"></span>').attr('data-value', value);
        parent.find('input').val(value);
    });

    $('#switch').on('click', function () {
        $('.main_wrapper').toggleClass('wide');
    });

    $('#accordion_prop_addr, #accordion_prop_details, #accordion_prop_features, #yelp_details').on('shown.bs.collapse', function () {
        $(this).find('h4').removeClass('carusel_closed');
    });

    $('#accordion_prop_addr, #accordion_prop_details, #accordion_prop_features, #yelp_details').on('hidden.bs.collapse', function () {
        $(this).find('h4').addClass('carusel_closed');
    });

    $('.search_wr_elementor li,.advanced_search_form_wrapper li,#adv-search-5 li,#adv-search-4 li,#adv-search-3 li,#adv-search-1 li,#advanced_search_shortcode li,#advanced_search_map_list li').on('click', function () {

        var pick, value, parent;
        pick = $(this).text();
        value = $(this).attr('data-value');
        parent = $(this).parent().parent();
        parent.find('.filter_menu_trigger').text(pick).append('<span class="caret caret_filter"></span>').attr('data-value', value);
        parent.find('input').val(value);
    });

    $('.advanced_search_map_list_container li').on('click', function () {
        wpestate_start_filtering_ajax_map(1);
    });

    $('.advanced_search_map_list_container_trigger input[type="text"]').change(function () {
        if ($(this).attr('id') !== 'search_location'
                && $(this).attr('id') !== 'check_in'
                && $(this).attr('id') !== 'search_locationhalf'
                && $(this).attr('id') !== 'search_locationsidebar'
                && $(this).attr('id') !== 'search_location'
                && $(this).attr('id') !== 'search_locationmobile') {
              
            wpestate_start_filtering_ajax_map(1);
        }
    });






    $('#check_out_list').change(function () {
        var start_date = $('#check_in_list').val();
        if (start_date !== '') {
            wpestate_start_filtering_ajax_map(1);
        }
    });

    $('#check_out_list').change(function () {
        var start_date = $('#check_in_list').val();
        if (start_date !== '') {
            wpestate_start_filtering_ajax_map(1);
        }
    });

    $('.extended_search_check_wrapper input[type="checkbox"]').on('click', function () {
        wpestate_start_filtering_ajax_map(1);
    });

    $("#google_map_prop_list_sidebar #slider_price").slider({
        stop: function (event, ui) {
            wprentals_show_pins(); 
            wpestate_start_filtering_ajax_map(1);
        }
    });

    $('#showinpage,#showinpage_mobile').on('click', function (event) {
        event.preventDefault();
        if ($('#gmap-full').hasClass('spanselected')) {
            $('#gmap-full').trigger('click');
        }
        wpestate_start_filtering(1);

    });

    $('#openmap').on('click', function () {
        if ($(this).find('i').hasClass('fa-angle-down')) {
            $(this).empty().append('<i class="fas fa-angle-up"></i>' + control_vars.close_map);
            if (control_vars.show_adv_search_map_close === 'no') {
                $('.search_wrapper').addClass('adv1_close');
                adv_search_click();
            }
        } else {
            $(this).empty().append('<i class="fas fa-angle-down"></i>' + control_vars.open_map);
        }
        wpestate_new_open_close_map(2);
    });

    $('#gmap-full').on('click', function () {

        if ($('#gmap_wrapper').hasClass('fullmap')) {
            $('#gmap_wrapper').removeClass('fullmap').css('height', wrap_h + 'px');
            $('#googleMap').removeClass('fullmap').css('height', map_h + 'px');
            $('#search_wrapper').removeClass('fullscreen_search');
            $('#search_wrapper').removeClass('fullscreen_search_open');
            $('.master_header').removeClass('hidden');
            $('#gmap-controls-wrapper ').removeClass('fullscreenon');
            $('.content_wrapper,#colophon,#openmap').show();
            $('#gmap-controls-wrapper ').removeClass('fullscreenon');

            $('body,html').animate({
                scrollTop: 0
            }, "slow");
            $('#openmap').show();
            $(this).removeClass('spanselected');

        } else {
            wrap_h = $('#gmap_wrapper').outerHeight();
            map_h = $('#googleMap').outerHeight();
            $('#gmap_wrapper,#googleMap').css('height', '100%').addClass('fullmap');
            $('#search_wrapper').addClass('fullscreen_search');
            $('.master_header ').addClass('hidden');
            $('.content_wrapper,#colophon,#openmap').hide();
            $('#gmap-controls-wrapper ').addClass('fullscreenon');
            $(this).addClass('spanselected');
        }

        if ($('#google_map_prop_list_wrapper').hasClass('halfmapfull')) {
            $('#google_map_prop_list_wrapper').removeClass('halfmapfull');
            $('#google_map_prop_list_wrapper').removeClass('halfmapfullx');
            $('.master_header').removeClass('hidden');
            $('#gmap-controls-wrapper ').removeClass('fullscreenon');
            $(this).removeClass('spanselected');
        } else {
            $('#google_map_prop_list_wrapper').addClass('halfmapfull');
            $('#google_map_prop_list_wrapper').addClass('halfmapfullx');

        }


        wprentals_map_resize();



    });

    $('#street-view').on('click', function () {
        wpestate_toggleStreetView();
    });


    $('.videoitem iframe').on('click', function () {
        $('.estate_video_control').remove();
    });





    jQuery(".icon-fav, .share_list,  .compare-action, .dashboad-tooltip, .pack-name, .normal_list_no, .mess_tooltip").on("mouseenter", function (event) {
        $(this).tooltip('show');
    })
    jQuery(".icon-fav, .share_list,  .compare-action, .dashboad-tooltip, .pack-name, .normal_list_no, .mess_tooltip").on("mouseleave", function (event) {
        $(this).tooltip('hide');
    })



    $('.share_list').on('click', function (event) {
        event.stopPropagation();
        var sharediv = $(this).parent().find('.share_unit');
        sharediv.toggle();
        $(this).toggleClass('share_on');
    });

    $('.backtop').on('click', function (event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0
        }, "slow");
    });



    jQuery("#geolocation-button").on("mouseenter", function (event) {
        $('#tooltip-geolocation').fadeIn();
        $('.tooltip').fadeOut("fast");
    });

    jQuery("#geolocation-button").on("mouseleave", function (event) {
        $('#tooltip-geolocation').fadeOut();
    });



    ////////////////////////////////////////////////////////////////////////////////////////////
    /// adding total for featured listings
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('.extra_featured').change(function () {
        var parent = $(this).parent();
        var price_regular = parseFloat(parent.find('.submit-price-no').text(), 10);
        var price_featured = parseFloat(parent.find('.submit-price-featured').text(), 10);
        var total = price_regular + price_featured;

        if ($(this).is(':checked')) {
            parent.find('.submit-price-total').text(total);
            parent.find('#stripe_form_featured').show();
            parent.find('#stripe_form_simple').hide();
        } else {
            //substract from total
            parent.find('.submit-price-total').text(price_regular);
            parent.find('#stripe_form_featured').hide();
            parent.find('#stripe_form_simple').show();
        }
        wpestate_enable_stripe_booking_prop();
    });


    $('.compare_wrapper').each(function () {
        var cols = $(this).find('.compare_item_head').length;
        $(this).addClass('compar-' + cols);
    });



    $('#list_view').on('click', function () {
        $(this).toggleClass('icon_selected');
        $('#listing_ajax_container').addClass('ajax12');
        $('#grid_view').toggleClass('icon_selected');
        $('.listing_wrapper').hide().removeClass('col-md-4').removeClass('col-md-3').addClass('col-md-12').fadeIn(400);
        $('.the_grid_view').fadeOut(10, function () {
            $('.the_list_view').fadeIn(300);
        });
    });

    $('#grid_view').on('click', function () {
        var class_type;
        class_type = $('.listing_wrapper:first-of-type').attr('data-org');
        $(this).toggleClass('icon_selected');
        $('#listing_ajax_container').removeClass('ajax12');
        $('#list_view').toggleClass('icon_selected');
        $('.listing_wrapper').hide().removeClass('col-md-12').addClass('col-md-' + class_type).fadeIn(400);
        $('.the_list_view').fadeOut(10, function () {
            $('.the_grid_view').fadeIn(300);
        });
    });

    $('#add-new-image').on('click', function () {
        $('<p><label for="file">New Image:</label><input type="file" name="upload_attachment[]" id="file_featured"></p> ').appendTo('#files_area');
    });

    $('.delete_image').on('click', function () {
        var image_id = $(this).attr('data-imageid');
        curent = $('#images_todelete').val();
        if (curent === '') {
            curent = image_id;
        } else {
            curent = curent + ',' + image_id;
        }

        $('#images_todelete').val(curent);
        $(this).parent().remove();
    });

    $('#googleMap').bind('mousemove', function (e) {
        $('.tooltip').css({'top': e.pageY, 'left': e.pageX, 'z-index': '1'});
    });

    setTimeout(function () {
        $('.tooltip').fadeOut("fast");
    }, 10000);
});


/*
*
* 
*
*/


function wpestate_show_capture() {
    "use strict";
    var position, slideno, slidedif, tomove, curentleft;
    jQuery('#googleMapSlider').hide();
    position = parseFloat(jQuery('#carousel-listing .carousel-inner .active').index(), 10);
    jQuery('#carousel-listing  .caption-wrapper span').removeClass('active');
    jQuery("#carousel-listing  .caption-wrapper span[data-slide-to='" + position + "'] ").addClass('active');
    slideno = position + 1;

    slidedif = slideno * 146;
    if (slidedif > 810) {
        tomove = 810 - slidedif;
        jQuery('.post-carusel .carousel-indicators').css('left', tomove + "px");
    } else {
        position = jQuery('.post-carusel .carousel-indicators').css('left', tomove + "px").position();
        curentleft = position.left;

        if (curentleft < 0) {
            tomove = 0;
            jQuery('.post-carusel .carousel-indicators').css('left', tomove + "px");
        }
    }
}

/*
*
* 
*
*/


function wpestate_shortcode_google_map_load(containermap, lat, long, mapid) {
    "use strict";
    var myCenter, mapOptions, map, marker;
    myCenter = new google.maps.LatLng(lat, long);
    mapOptions = {
        flat: false,
        noClear: false,
        zoom: 15,
        scrollwheel: false,
        draggable: true,
        center: myCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        },
        disableDefaultUI: true,
        gestureHandling: 'cooperative'
    };

    map = new google.maps.Map(document.getElementById(mapid), mapOptions);
    google.maps.visualRefresh = true;
    marker = new google.maps.Marker({
        position: myCenter,
        map: map
    });

    marker.setMap(map);

}

/*
*
* 
*
*/


function wpestate_enable_stripe_booking_prop() {

    jQuery('.wpestate_stripe_booking_prop').unbind();
    jQuery('.wpestate_stripe_booking_prop').on('click', function () {
        var parent = jQuery(this).parent();
        var modalid = jQuery(this).attr('data-modalid');

        jQuery('#' + modalid).show();
        jQuery('#' + modalid + ' .wpestate_stripe_form_1').show();
        wpestate_start_stripe(0, modalid);
    });

    jQuery('.close_stripe_form').on('click', function () {
        jQuery('.wpestate_stripe_form_wrapper').hide();
        jQuery('.wpestate_stripe_form_1').hide();
    });
}

/*
*
* 
*
*/


function wprentals_elementor_submit_form() {


    jQuery('.wprentals_elementor_form').on('submit', function (event) {
        event.preventDefault();
        var form_submit = jQuery('.wprentals_elementor_form').find('.agent_submit_class_elementor');
        wprentals_elementor_contact_process_form(form_submit);
    });


}

/*
*
* 
*
*/


function wprentals_elementor_contact_process_form(form_submit) {
    var parent, button, ajaxurl, contact_u_email, contact_u_name, subject, booking_from_date, booking_to_date, booking_guest_no, message, nonce, agent_property_id, is_elementor;


    parent = form_submit.parent();
    button = jQuery('.wprentals_elementor_form').find('.agent_submit_class_elementor');
    button.val(ajaxcalls_vars.processing);
    button.text(ajaxcalls_vars.processing);
    button.prop('disabled', true);
    ajaxurl = control_vars.admin_url + 'admin-ajax.php';
    booking_from_date = jQuery("#rentals_contact_builder_booking_from_date").val();
    booking_to_date = jQuery("#rentals_contact_builder_booking_to_date").val();
    booking_guest_no = jQuery("#rentals_contact_builder_guests").val();
    message = jQuery("#form-field-message").val();
    contact_u_email = jQuery("#rentals_contact_builder_email").val();
    contact_u_name = jQuery("#rentals_contact_builder_name").val();
    nonce = jQuery('#agent_property_ajax_nonce').val();



    is_elementor = parent.find('#contact_form_elementor').val();
    var elementor_email_send_to = jQuery('#elementor_email_send_to').val();
    var elementor_email_subject = jQuery('#elementor_email_subject').val();
    var elementor_email_sendc = jQuery('#elementor_email_sendc').val();
    var elementor_email_sendbcc = jQuery('#elementor_email_sendbcc').val();

    var temp_details;
    temp_details = '';
    var elementor_form = form_submit.parents('.wprentals_elementor_form');

    var form_items = elementor_form.find('.elementor-field');

    form_items.each(function () {
        temp_details = temp_details + jQuery(this).attr('name') + ": " + jQuery(this).val() + "/n";
    });

    message = temp_details;


    if (jQuery('#wpestate_agree_gdpr').length > 0 && !jQuery('#wpestate_agree_gdpr').is(':checked')) {
        jQuery("#booking_form_request_mess_modal").show().empty().append(control_vars.gdpr_terms);
        button.val(control_vars.send_mess);
        button.text(control_vars.send_mess);
        var aTag = jQuery(".wprentals_elementor_form");
        jQuery('html,body').animate({scrollTop: aTag.offset().top - 120}, 'slow');
        button.prop('disabled', false);
        return;
    }



    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        data: {
            'action': 'wpestate_ajax_agent_contact_page',
            'name': contact_u_name,
            'email': contact_u_email,
            'comment': message,
            'booking_guest_no': booking_guest_no,
            'booking_from_date': booking_from_date,
            'booking_to_date': booking_to_date,
            //'elementor_email_send_to':elementor_email_send_to,
            'elementor_email_subject': elementor_email_subject,
            //'elementor_email_sendc':elementor_email_sendc,
            //'elementor_email_sendbcc':elementor_email_sendbcc,
            'is_elementor': 1,

            'nonce': nonce
        },
        success: function (data) {
            var aTag = jQuery("#booking_form_request_mess_modal");
            jQuery('html,body').animate({scrollTop: aTag.offset().top - 120}, 'slow');
            jQuery("#booking_form_request_mess_modal").show().empty().append(data.response);
            // reset contact form
            button.val(control_vars.send_mess);
            button.text(control_vars.send_mess);
            button.prop('disabled', false);
            jQuery(".wprentals_elementor_form .elementor-field").val('');



        },
        error: function (errorThrown) {

        }

    });

}




/*
*
* 
*
*/



function wpestate_control_guest_no() {

    jQuery('.wpestate_guest_no_control_info ').off('click').on('click', function (event) {
        jQuery(this).parent().find('.wpestate_guest_no_buttons').toggle();
        event.stopPropagation();
    });


    jQuery('.close_guest_control').off('click').on('click', function (event) {
        event.preventDefault();
        jQuery(this).parent().hide();
        
        if (jQuery("#google_map_prop_list_sidebar").length > 0) {
            wpestate_start_filtering_ajax_map(1);
        }
    });



    // when a plus or minus button is pushed
    jQuery('.wpestate_guest_no_button_control').off('click').on('click', function (event) {    
        event.stopPropagation();
        event.preventDefault();
        var with_count_children = 1;
        var item_parent = jQuery(this).parent();
        var section_wrapper = jQuery(this).parents().eq(2);
        var overload_guest = parseInt(section_wrapper.attr('data-overload-guest'), 10);
        var max_extra_guest_no = parseInt(section_wrapper.attr('data-max-extra-guest-no'), 10);
        var max_guest = parseInt(section_wrapper.attr('data-max-guest'), 10);

        var check_max_guest = max_guest;
        if (overload_guest === 1) {
            check_max_guest = max_guest + max_extra_guest_no;
        }

        var trigger_label_creation = 1;

        // contro item incrementioa
        if (!wpestate_guest_control_add_item(jQuery(this))) {
            return;
        }


        if (jQuery(this).hasClass('on_booking_control1')) {
            with_count_children = 0;
        }


        var guest_parent = jQuery(this).closest('.wpestate_guest_no_control_wraper');

        if (!item_parent.hasClass('steper_infants')) {

            var guest_value_text = guest_parent.find('.guest_no_hidden').val();
            var guest_value_int = parseInt(guest_value_text, 10);


            if (jQuery(this).hasClass('wpestate_guest_no_button_plus')) {

                if (jQuery(this).hasClass('on_booking_control1') || jQuery(this).hasClass('on_booking_control0')) {

                    if (jQuery(this).parent().hasClass('steper_childs')) {
                        if (jQuery(this).hasClass('on_booking_control0')) {
                            guest_value_int = guest_value_int + 1;
                        }
                    } else {
                        guest_value_int = guest_value_int + 1;
                    }

                } else {
                    guest_value_int = guest_value_int + 1;
                }

            } else {

                if (jQuery(this).hasClass('on_booking_control1') || jQuery(this).hasClass('on_booking_control0')) {
                    if (jQuery(this).parent().hasClass('steper_childs')) {
                        if (jQuery(this).hasClass('on_booking_control0')) {
                            guest_value_int = guest_value_int - 1;
                        }
                    } else {
                        guest_value_int = guest_value_int - 1;
                    }

                } else {
                    guest_value_int = guest_value_int - 1;
                }


                if (guest_value_int < 0) {
                    guest_value_int = 0;
                }
                    }// end if plus or minus 

                      
                    // if we have booking control
                    if (jQuery(this).hasClass('on_booking_control1') || jQuery(this).hasClass('on_booking_control0')) {

                        if(guest_value_int === check_max_guest){
                            section_wrapper.find('.max_guest_notice').show().text( check_max_guest+' '+control_vars.guests_max);
                            trigger_label_creation=1;
                            guest_parent.find('.guest_no_hidden').val(guest_value_int);
                            wpestate_block_unblock_plus_buttons(section_wrapper,'block');

                        }else if(guest_value_int > check_max_guest){
                            trigger_label_creation=1;

                        }else{
                            section_wrapper.find('.max_guest_notice').hide();
                            guest_parent.find('.guest_no_hidden').val(guest_value_int);
                            trigger_label_creation=1;
                            wpestate_block_unblock_plus_buttons(section_wrapper,'unblock');
                        }
                    }else{
                        // if not -it means search control
                          guest_parent.find('.guest_no_hidden').val(guest_value_int);
                    }



                } // end if is adult or guest conrols



                // generate label for control
                if(trigger_label_creation==1){
                    label_to_add = wpestate_guest_no_label_to_add(guest_parent,with_count_children);
                    guest_parent.find('.wpestate_guest_no_control_info').text(label_to_add);
                    if (jQuery(this).parents().eq(5).hasClass('booking_form_request')) {
                        show_booking_costs();
                    }
                }


       
    });


    jQuery(document).on('click', function (event) {
        jQuery(".wpestate_guest_no_buttons").each(function () {
            var container = jQuery(this);    
            if (!container.is(event.target) && container.has(event.target).length === 0) {
                if (container.css('display') == 'block') {
                    container.hide();
                    if (jQuery("#google_map_prop_list_sidebar").length > 0) {
                        wpestate_start_filtering_ajax_map(1);
                    }
                }
            }
        });
    });
    
}

/*
*
* 
*
*/


function wpestate_guest_no_label_to_add(wrapper,with_count_children) {
    var adult_guest_text = wrapper.find('.steper_value_adults').text();
    var children_guest_text = wrapper.find('.steper_value_childs').text();
    var infants_guest_text = wrapper.find('.steper_value_infants').text();

    var adult_guest_int = parseInt(adult_guest_text, 10);
    var children_guest_int = parseInt(children_guest_text, 10);
    var infant_guest_int = parseInt(infants_guest_text, 10);

    var label_to_return = '';
    var total_guests_to_bill = adult_guest_int + children_guest_int;
    
    if( with_count_children==0 ){
        total_guests_to_bill = adult_guest_int;      
    }
        
    if (total_guests_to_bill > 0) {
        var guest_label = control_vars.multiple_guest;
        if (total_guests_to_bill == 1) {
            guest_label = control_vars.single_guest;
        }
        label_to_return = label_to_return + total_guests_to_bill + ' ' + guest_label;        
    }
    
    if(with_count_children==0 && children_guest_int>0){
        var children_label = control_vars.multiple_child;
        if (children_guest_int == 1) {
            children_label = control_vars.single_child;
        }

        if (total_guests_to_bill > 0) {
            label_to_return =  label_to_return + ', ';
        }
        label_to_return = label_to_return + children_guest_int + ' ' + children_label;
    }

    if (infant_guest_int > 0) {
        var infants_label = control_vars.multiple_infants;
        if (infant_guest_int == 1) {
            infants_label = control_vars.single_infant;
        }
      
        if (total_guests_to_bill > 0 || ( with_count_children==0  && children_guest_int>0 ) ) {
            label_to_return =  label_to_return + ', ';
        }
        label_to_return = label_to_return + infant_guest_int + ' ' + infants_label;
    }

    if ( ( total_guests_to_bill == 0 && infant_guest_int == 0) || ( with_count_children==0  && total_guests_to_bill == 0 && infant_guest_int == 0 )  ) {
        label_to_return = 'Guests';
    }
    
    
    if(typeof(wpestate_setCookie)!=='undefined'){
      wpestate_setCookie('adult_guest_int',  adult_guest_int , 1);
      wpestate_setCookie('children_guest_int',  children_guest_int , 1);
      wpestate_setCookie('infant_guest_int',  infant_guest_int , 1);
      wpestate_setCookie('label_to_return',  label_to_return , 1);
      wpestate_setCookie('guest_no_hidden',  total_guests_to_bill , 1);
    }
    return label_to_return;
}



/*
*
* 
*
*/


function wpestate_check_if_children_as_guests(){
    if( typeof(property_vars)!=='undefined'){
        if(property_vars.children_as_guests==='0'){
             return true;
        }
         
    }
    return false;
}

/*
*
* 
*
*/


function  wpestate_block_unblock_plus_buttons(section_wrapper,action){
    if(action==='block'){
        section_wrapper.find('.adults_control_plus').prop('disabled', true);;
        section_wrapper.find('.childs_control_plus.on_booking_control0').prop('disabled', true);;
    }else{
         section_wrapper.find('.wpestate_guest_no_button_plus').prop('disabled', false);
    }
}


/*
*
* Guest control add item
*
*/


function wpestate_guest_control_add_item(item){
        var item_parent         =   item.parent();
        var item_value_text     =   item_parent.find('.wpestate_guest_no_button_value').text();
        var item_value_int      =   parseInt(item_value_text, 10);
        var abort_flag          =   0;
           
        if (item.hasClass('wpestate_guest_no_button_plus')) {
            item_value_int = item_value_int + 1;
        } else {
            item_value_int = item_value_int - 1;
            if (item_value_int < 0) {
                item_value_int = 0;
                abort_flag=1;
            }
        }
        
     
        
        item.parent().find('.placeholeder_search_val').val(item_value_int);
        item_parent.find('.wpestate_guest_no_button_value').text(item_value_int);
        
        if(abort_flag===1){
            return false;
        }
        return true;
        
}

/*
*
* Slider Box
*
*/

function wpestate_slider_box(){
   
    jQuery( ".wpestate_sliding_box" ).mouseover(function() {
        var selected_item   = jQuery(this);
        var item_parent     = selected_item.parent();
        
        item_parent.find('.wpestate_sliding_box').removeClass('active-element')

        selected_item.addClass('active-element');
    });
}


/*
*
* Testimonial Slider
*
*/

function wpestate_testimonial_slider(slider_id){
 
     jQuery('#'+slider_id).slick({
        infinite:true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:true,
       // rtl:true,
       
    });
    
}

/*
*
* Mobile Menu slide out
*
*/

function wpestate_mobile_menu_slideout(){
    
  

    var vc_size;
    var var_parents=new Array();
    var var_parents_back=new Array();

  


    if(jQuery('.mobile-trigger-user').length>0){
        var slideout_user_menu = new Slideout({
            'panel': document.getElementById('all_wrapper'),
            'menu': document.getElementById('mobilewrapperuser'),
            'padding': -256,
            'tolerance': 70
        });

        slideout_user_menu.disableTouch();

        // Toggle button
        document.querySelector('.mobile-trigger-user').addEventListener('click', function() {
            slideout_user_menu.toggle();
            jQuery('#mobilewrapperuser').show();
        
        });
       
        jQuery('.mobilemenu-close-user').on( 'click', function(event) {
            slideout_user_menu.toggle();
            setTimeout(function() { 
                jQuery('#mobilewrapperuser').hide(); 
            }, 200);
    
        });

    }



    if(jQuery('.mobile-trigger').length>0){
        var slideout_link_menu = new Slideout({
            'panel': document.getElementById('all_wrapper'),
            'menu': document.getElementById('mobilewrapper_links'),
            'padding': 256,
            'tolerance': 70
        });
        slideout_link_menu.disableTouch();
        document.querySelector('.mobile-trigger').addEventListener('click', function() {
            slideout_link_menu.toggle();
            jQuery('#mobilewrapper_links').show();
        });

        jQuery('.mobilemenu-close').on( 'click', function(event) {
            slideout_link_menu.toggle();
            setTimeout(function() { 
                jQuery('#mobilewrapper_links').hide();
            }, 200);
        });
    }
    
 

 

}