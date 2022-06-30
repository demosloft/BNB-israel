jQuery(document).ready(function ($) {
    "use strict";
    
    var curent_m,curent_m_set, input , defaultBounds, options, componentForm, autocomplete, place, calendar_click, calendar_click_price;
    curent_m=2;
    curent_m_set=1;
    var max_month = parseInt(ajaxcalls_vars.max_month_no);

    $('#calendar-next').on('click',function () {
        if (curent_m < (max_month-2) ) {
            curent_m = curent_m + 1;
        } else {
            curent_m = max_month;
        }

        $('.booking-calendar-wrapper').hide();
        $('.booking-calendar-wrapper').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m + 1) {
                $(this).fadeIn();
            }
        });
    });

    $('#calendar-prev').on('click',function () {
        if (curent_m > 3) {
            curent_m = curent_m - 1;
        } else {
            curent_m = 2;
        }

        $('.booking-calendar-wrapper').hide();
        $('.booking-calendar-wrapper').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m - 1) {
                $(this).fadeIn();
            }
        });
    });
});