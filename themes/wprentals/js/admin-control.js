/*global $, jQuery, document, window, tb_show, tb_remove ,admin_control_vars*/
jQuery(document).ready(function ($) {

    var icon_field;
    $('.input-group-addon').on('click',function(event){
          $('.iconpicker-items_wrapper').show();
          $('.icon_look_for_class').val('');
          $('.iconpicker-item').show();
          icon_field = $(this).parent().find('.icp-auto');
    });

    $('.iconpicker-items_wrapper_close').on('click',function(event){
        $('.iconpicker-items_wrapper').hide();
    });

    $('.iconpicker-item').on('click',function(event){
        event.preventDefault();
        var value = $(this).find('i').attr('class');
        icon_field.val(value);
        $('.iconpicker-items_wrapper').hide();
    });


    $('.icon_look_for_class').keydown(function(event){

        var look_for= $(this).val();
        var title, search_term,parent;
        parent = $(this).parent();

        if(look_for!==''){
            parent.find('.iconpicker-item').each(function() {
                title       = $(this).attr('title');
                search_term = $(this).attr('data-search-terms');

                if(typeof title==='undefined'){
                    title='';
                }
                if(typeof search_term==='undefined'){
                    search_term='';
                }


                if(title.indexOf(look_for) !== -1 || search_term.indexOf(look_for) !== -1){
                    $(this).show();
                }else{
                    $(this).hide();
                }

            });
        }else{
            parent.find('.iconpicker-item').show();
        }
    });


    $('.css_modal_close').on('click',function(){
        $('#css_modal').hide();
    });

    $('#copycsscode').on('click',function(){
        $('#css_modal').html();

    });


    $('#activate_pack_reservation_fee').on('click',function(){
        var book_id, invoice_id,ajaxurl,type;
        jQuery(this).text(admin_control_vars.processing);
        book_id     = $(this).attr('data-item');
        invoice_id  = $(this).attr('data-invoice');
        type        = $(this).attr('data-type');
        ajaxurl     =   admin_control_vars.ajaxurl;
        var nonce = jQuery('#wprentals_activate_pack').val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_admin_activate_reservation_fee',
            'book_id'       :   book_id,
            'invoice_id'    :   invoice_id,
            'security'      :   nonce,
        },
        success: function (data) {
            jQuery("#activate_pack_reservation_fee").remove();
            jQuery("#invnotpaid").remove();
        },
        error: function (errorThrown) {
        }
    });//end ajax

    });



     $('#activate_pack_listing').on('click',function(){
        var item_id, invoice_id,ajaxurl,type;

        item_id     = $(this).attr('data-item');
        invoice_id  = $(this).attr('data-invoice');
        type        = $(this).attr('data-type');
        ajaxurl     =   admin_control_vars.ajaxurl;
        var nonce = jQuery('#wprentals_activate_pack').val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_activate_purchase_listing',
            'item_id'       :   item_id,
            'invoice_id'    :   invoice_id,
            'type'          :   type,
            'security'      :   nonce,

        },
        success: function (data) {
            jQuery("#activate_pack_listing").remove();
            jQuery("#invnotpaid").remove();


        },
        error: function (errorThrown) {}
    });//end ajax

    });

     ///////////////////////////////////////////////////////////////////////////////
    /// activate purchase
    ///////////////////////////////////////////////////////////////////////////////

     $('#activate_pack').on('click',function(){
        var item_id, invoice_id,ajaxurl;

        item_id     =   $(this).attr('data-item');
        invoice_id  =   $(this).attr('data-invoice');
        ajaxurl     =   admin_control_vars.ajaxurl;
        var nonce = jQuery('#wprentals_activate_pack').val();


        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_activate_purchase',
            'item_id'       :   item_id,
            'invoice_id'    :   invoice_id,
            'security'         :   nonce,

        },
        success: function (data) {
            jQuery("#activate_pack").remove();
            jQuery("#invnotpaid").remove();

        },
        error: function (errorThrown) {}
    });//end ajax

    });















    var formfield, imgurl;
     $('#splash_video_mp4_button').on('click',function () {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#splash_video_mp4').val(mediaUrl);
            tb_remove();
        };
        return false;
    });


    $('#splash_video_webm_button').on('click',function () {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#splash_video_webm').val(mediaUrl);
            tb_remove();
        };
        return false;
    });


    $('#splash_video_ogv_button').on('click',function () {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#splash_video_ogv').val(mediaUrl);
            tb_remove();
        };
        return false;
    });



     $('#page_custom_video_button').on('click',function () {
        formfield = $('#page_custom_video').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");

            jQuery('#page_custom_video').val(mediaUrl);
            tb_remove();
        };
        return false;
    });

    $('#page_custom_video_webbm_button').on( 'click', function(event) {

       tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
       window.send_to_editor = function (html) {
           var href = html.match(/href='([^']*)/)[1];
           jQuery('#page_custom_video_webbm').val(href);
           tb_remove();
       };
       return false;
    });


    $('#page_custom_video_ogv_button').on( 'click', function(event) {

        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
             var href = html.match(/href='([^']*)/)[1];
            jQuery('#page_custom_video_ogv').val(href);
            tb_remove();
        };
        return false;
    });

    $('#page_custom_image_button').on('click',function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#page_custom_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    $('.category_featured_image_button').on('click',function () {
        var parent = $(this).parent();
        formfield  = parent.find('#category_featured_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            parent.find('#category_featured_image').val(imgurl);
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
    });


     $('.category_featured_icon_button').on('click',function () {
        var parent = $(this).parent();
        formfield  = parent.find('#category_featured_icon').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            parent.find('#category_featured_icon').val(imgurl);
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
    });




    $('.category_icon_image_button').on('click',function () {
        var parent = $(this).parent();
        formfield  = parent.find('#category_icon_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            parent.find('#category_icon_image').val(imgurl);
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
    });






    $('#page_custom_image_button').on('click',function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#page_custom_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    $('#page_custom_video_cover_image_button').on('click',function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#page_custom_video_cover_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    if (jQuery('.user-verifications').length === 1) {
        var verifications = jQuery('.user-verifications');

        verifications.on('change', 'input[type="checkbox"]', function () {
            var   userID = jQuery(this).data('userid');

            var   isVerified = 0;
            var   editUser = jQuery(this).closest('.verify-user', jQuery('.user-verifications'));


            if( $('input[name="verified-users[]"]:checked').length > 0 ){
                isVerified = 1;
            }


           var nonce = jQuery('#wprentals_user_verfication').val();

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action': 'wpestate_update_verification',
                    'userid': userID,
                    'verified': isVerified,
                    'security': nonce,
                },
                success: function (data) {
              
                    switch (true) {
                        case (isVerified === 0):

                            editUser.removeClass('verified');
                            break;
                        case (isVerified === 1):

                            editUser.addClass('verified');
                            break;
                    }
                },
                error: function (errorThrown) {

                }
            });
        });
    }


});
