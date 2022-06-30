/*global $, jQuery, ajaxcalls_vars, ajaxurl,admin_vars,document, control_vars, window, tb_show, tb_remove*/

wpestate_set_theme_tab_visible2();
wpestate_set_theme_tab_visible();

jQuery(document).ready(function ($) {
    "use strict";

    wpestate_theme_options_sidebar();
    
       /*admin tabs*/
    $("#property_uploaded_thumb_wrapepr" ).sortable({
        revert: true,
        update: function( event, ui ) {
            var all_id,new_id;
            all_id="";
            $( "#property_uploaded_thumb_wrapepr .uploaded_thumb" ).each(function(){
                new_id = $(this).attr('data-imageid'); 
                if (typeof new_id != 'undefined') {
                    all_id=all_id+","+new_id; 
                }
            });
            $('#image_to_attach').val(all_id);
        },
    });

                    
                    
                    
    $('.property_tab_item').on( 'click', function() {
        var tab=$(this).attr('data-content');
        $('.property_tab_item').removeClass('active_tab');
        $('.property_tab_item_content ').removeClass('active_tab');
        $(this).addClass('active_tab');
        $('#'+tab).addClass('active_tab');       
    });
    
               
        $('#button_new_image').on( 'click', function(event) {

            event.stopPropagation();
            event.preventDefault();
            var  metaBox = $('#new_tabbed_interface');
            var imgContainer = metaBox.find( '.property_uploaded_thumb_wrapepr');

            var imgIdInput = metaBox.find( '#image_to_attach' ).val();
            var post_id=$(this).attr('data-postid');


            // Accepts an optional object hash to override default values.
            var frame = new wp.media.view.MediaFrame.Select({
                    // Modal title
                    title: 'Select Images',

                    // Enable/disable multiple select
                    multiple: true,

                    // Library WordPress query arguments.
                    library: {
                            order: 'DESC',
                            orderby: 'id',
                            type: 'image',
                    },

                    button: {
                            text: 'Set Image'
                    }
            });

            // Fires after the frame markup has been built, but not appended to the DOM.
            // @see wp.media.view.Modal.attach()
            frame.on( 'ready', function() { } );

            // Fires when the frame's $el is appended to its DOM container.
            // @see media.view.Modal.attach()
            frame.on( 'attach', function() {} );

            // Fires when the modal opens (becomes visible).
            // @see media.view.Modal.open()
            frame.on( 'open', function() {} );

            // Fires when the modal closes via the escape key.
            // @see media.view.Modal.close()
            frame.on( 'escape', function() {} );

            // Fires when the modal closes.
            // @see media.view.Modal.close()
            frame.on( 'close', function() {} );

            // Fires when a user has selected attachment(s) and clicked the select button.
            // @see media.view.MediaFrame.Post.mainInsertToolbar()
            frame.on( 'select', function() {
                    var attachment = frame.state().get('selection').toJSON();
                    var arrayLength = attachment.length;
                    for (var i = 0; i < arrayLength; i++) {
                        imgIdInput = metaBox.find( '#image_to_attach' ).val();
                        $( '#image_to_attach' ).val(imgIdInput+attachment[i].id+",")
                        imgContainer.append( '<div class="uploaded_thumb" data-imageid="'+attachment[i].id+'">\n\
                            <img src="'+attachment[i].sizes.thumbnail.url+'"  style="max-width:100%;"/>\n\
                            <a target="_blank" href="'+admin_vars.admin_url+'post.php?post='+attachment[i].id+'&action=edit" class="attach_edit"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a><a class="attach_delete"><i class="fas fa-trash" aria-hidden="true"></i></span></div>' );

                    }
            } );

            // Fires when a state activates.
            frame.on( 'activate', function() {} );

            // Fires when a mode is deactivated on a region.
            frame.on( '{region}:deactivate', function() {} );
            // and a more specific event including the mode.
            frame.on( '{region}:deactivate:{mode}', function() {} );

            // Fires when a region is ready for its view to be created.
            frame.on( '{region}:create', function() {} );
            // and a more specific event including the mode.
            frame.on( '{region}:create:{mode}', function() {} );

            // Fires when a region is ready for its view to be rendered.
            frame.on( '{region}:render', function() {} );
            // and a more specific event including the mode.
            frame.on( '{region}:render:{mode}', function() {} );

            // Fires when a new mode is activated (after it has been rendered) on a region.
            frame.on( '{region}:activate', function() {} );
            // and a more specific event including the mode.
            frame.on( '{region}:activate:{mode}', function() {} );

            // Get an object representing the current state.
            frame.state();


                // Get an object representing the previous state.
            frame.lastState();

                // Open the modal.
            frame.open();  
        });
        
        
        $('.attach_delete').on( 'click', function() {

            var curent,remove;
            var img_remove= jQuery(this).parent().attr('data-imageid');
            jQuery(this).parent().remove();

            jQuery('#property_uploaded_thumb_wrapepr .uploaded_thumb').each(function(){
                remove  =   jQuery(this).attr('data-imageid');
                curent  =   curent+','+remove; 

            });
            
            var nonce = jQuery('#wpestate_image_upload').val();
            jQuery('#image_to_attach').val(curent); 
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_delete_file',
                    'attach_id'         :   img_remove,
                    'security'          :   nonce,
                    'isadmin'           :  1
                },
                success: function (data) {     
                },
                error: function (errorThrown) {  
                }
            });//end ajax   

        });
         
         
         
         
   jQuery('.wpestate_notices .notice-dismiss').on('click',function(){
       
        var ajaxurl     = admin_vars.ajaxurl;
        var notice_type = $(this).parent().attr('data-notice-type');
        var nonce       = $('#wpestate_notice_nonce').val();
        
       
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'                    :   'wpestate_update_cache_notice',
                'notice_type'               :   notice_type,
                'security'                     :   nonce
            },
            success: function (data) {     

          
            },
            error: function (errorThrown) { 
              
            }
        });
    });
    
    jQuery('#add_feature2').on('click',function (event) {
        event.preventDefault();
        var new_feature =jQuery('#new_feature').val();
        if (new_feature !== '') {
            //feature_list
           var  current_features    =  jQuery('#new_feature_all').val();
            if (current_features === '') {
                current_features    =   new_feature;
            } else {
                current_features    =   current_features + ',\n' + new_feature;
            }
           jQuery('#new_feature_all').val(current_features);
           jQuery('#new_feature').val('');
        }
    });
    
    jQuery('#add_status').on('click',function (event) {
        
        event.preventDefault();
        var new_status = jQuery('#new_status').val();
        if (new_status !== '') {
            //status_list
            var current_status    =   jQuery('#status_list').val();

            if (current_status === '') {
                current_status    =   new_status;
            } else {
                current_status    =   current_status + ',\n' + new_status;
            }
            jQuery('#status_list').val(current_status);
            jQuery('#new_status').val('');
        }
    });
    
    jQuery('#add_field2').on('click',function (event) {

        event.preventDefault();
        var newfield, field_name, field_label, field_order,drodown_values,field_type;
        newfield = '';
        field_name      =   jQuery('#field_name').val();
        field_label     =   jQuery('#field_label').val();
        field_type      =   jQuery('#field_type').val();
        field_order     =   parseInt(jQuery('#field_order').val(), 10);
        drodown_values  =   jQuery('#drodown_values').val();

        newfield =  '<div    class=field_row>';
        newfield += '<div    class=field_item><strong>Field Name</strong></br><input  type="text"   name="wprentals_admin[wpestate_custom_fields_list][add_field_name][]"   value="' + field_name + '"  ></div>';
        newfield += '<div    class=field_item><strong>Field Label</strong></br><input  type="text"  name="wprentals_admin[wpestate_custom_fields_list][add_field_label][]"  value="' + field_label + '"  ></div>';
        newfield += '<div    class=field_item><strong>Field Type</strong></br><input  type="text"   name="wprentals_admin[wpestate_custom_fields_list][add_field_type][]"   value="' + field_type + '"   ></div>';
        newfield += '<div    class=field_item><strong>Field Order</strong></br><input  type="text"  name="wprentals_admin[wpestate_custom_fields_list][add_field_order][]"  value="' + field_order + '"></div>';
        newfield += '<div    class=field_item><strong>Dropdwn Values</strong></br><textarea         name="wprentals_admin[wpestate_custom_fields_list][add_dropdown_order][]">' + drodown_values + '</textarea></div>';
        newfield += '<a class="deletefieldlink" href="#">delete</a>';
        newfield += '</div>';

        jQuery('#custom_fields_wrapper').append(newfield);
        jQuery('#field_name').val('');
        jQuery('#field_label').val('');
        jQuery('#field_order').val('');
        jQuery('#drodown_values').val('');
    });
    
      
    jQuery('.deletefieldlink').on('click',function (event) {
        event.preventDefault();
       var parent_div = jQuery(this).parent();
        parent_div.remove();
    });
    
    
    $('#start_wprentals_setup').on('click',function(){
       $('#wpestate_start_wrapper').slideToggle();
    });
      
    $('.wpestate_start_wrapper_close').on('click',function(){
       $('#wpestate_start_wrapper').slideUp();
    });
    
     
    $( '.wpestate-megamenu-background-image' ).css( 'display', 'block' );
    $( ".wpestate-megamenu-background-image[src='']" ).css( 'display', 'none' );
    
    
    $('.edit-menu-item-wpestate-megamenu-check').on('click',function(){
        var parent_li_item = $( this ).parents( '.menu-item:eq( 0 )' );

        if( $( this ).is( ':checked' ) ) {
                parent_li_item.addClass( 'wpestate-megamenu' );
        } else 	{
                parent_li_item.removeClass( 'wpestate-megamenu' );
        }
        wpestate_update_megamenu_fields();
    });
    
     
    $('.load_back_menu').on('click',function(e){
        e.preventDefault();
        var parent = $(this).parent().parent();
        var item_id = this.id.replace('wpestate-media-upload-', '');
        
       // formfield  = parent.find('#category_featured_image').attr('name');
        
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        
        window.send_to_editor = function (html) {
            var    imgurl = $('img', html).attr('src');
            parent.find('#edit-menu-item-megamenu-background-'+item_id).val(imgurl);
            parent.find( '#wpestate-media-img-'+item_id ).attr( 'src', imgurl ).css( 'display', 'block' );
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
        
        
                              
    });
    
     $('.remove-megamenu-background').on('click',function(e){
        e.preventDefault();
        var  item_id = this.id.replace( 'wpestate-media-remove-', '' );
        $( '#edit-menu-item-megamenu-background-'+item_id ).val( '' );
        $( '#wpestate-media-img-'+item_id ).attr( 'src', '' ).css( 'display', 'none' );
    });
    
    
      function wpestate_update_megamenu_fields() {
        var menu_li_items = $( '.menu-item');

        menu_li_items.each( function( i ) 	{

                var megamenu_status = $( '.edit-menu-item-wpestate-megamenu-check', this );

                if( ! $( this ).is( '.menu-item-depth-0' ) ) {
                        var check_against = menu_li_items.filter( ':eq(' + (i-1) + ')' );


                        if( check_against.is( '.wpestate-megamenu' ) ) {

                                megamenu_status.attr( 'checked', 'checked' );
                                $( this ).addClass( 'wpestate-megamenu' );
                        } else {
                                megamenu_status.attr( 'checked', '' );
                                $( this ).removeClass( 'wpestate-megamenu' );
                        }
                } else {
                        if( megamenu_status.attr( 'checked' ) ) {
                                $( this ).addClass( 'wpestate-megamenu' );
                        }
                }
        });
    }
    
    
    
    
    $('#check_ajax_license').on('click',function(){
        var ajaxurl= admin_vars.ajaxurl;
        var wpestate_license_key    = jQuery('#wpestate_license_key').val();
        var license_ajax_nonce      = jQuery('#license_ajax_nonce').val();


        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'                    :   'wpestate_check_license_function',
                'wpestate_license_key'      :   wpestate_license_key,
                'security'                  :   license_ajax_nonce

            },
            success: function (data) {     
            
                if(data==='ok'){
                    $('.license_check_wrapper').empty().text('Your copy of the theme is activated. Refresh the page to see Theme Options panel.');
                }else{
                    $('.notice_here').empty().text('The activation code is not correct!');
                }

            },
            error: function (errorThrown) { 
              
            }
        });//end ajax   

    });
            
            
    
    $('.admin_top_bar_button').on('click',function(event){
        event.preventDefault();
        var selected = $(this).attr('data-menu');
        var autoselect='';
        
        $('.admin_top_bar_button').removeClass('tobpbar_selected_option');
        $(this).addClass('tobpbar_selected_option');
        
        $('.theme_options_sidebar, .theme_options_wrapper_tab,.theme_options_tab').hide();
        $('#'+selected).show();
        $('#'+selected+'_tab').show();
        $('#'+selected+'_tab .theme_options_tab:eq(0)').show();
      
      
        localStorage.setItem('hidden_tab',selected);
        
        
        $('#'+selected+' li:eq(0)').addClass('selected_option');
        autoselect =  $('#'+selected+' li:eq(0)').attr('data-optiontab');
     
        localStorage.setItem('hidden_sidebar',autoselect);
        wpestate_theme_options_sidebar();
    });
     
     
   
    
     
    $('#wpestate_sidebar_menu li').on('click',function(event){
        event.preventDefault();
        $('#wpestate_sidebar_menu li').removeClass('selected_option');
        $(this).addClass('selected_option');
        
        var selected = $(this).attr('data-optiontab');
      
        $('.theme_options_tab').hide();
        $('#'+selected).show();
        $('#hidden_sidebar').val(selected);
        
       
        localStorage.setItem('hidden_sidebar',selected);
        wpestate_theme_options_sidebar();
                
    });
     
    
    var my_colors, k;
    ///////////////////////////////////////////////////////////////////////////////
    /// add new membership
    ///////////////////////////////////////////////////////////////////////////////
    $('#new_membership').on('click',function () {
        var new_row;
        new_row = $('#sample_member_row').html();
        new_row = '<div class="memebership_row">' + new_row + '</div>';
        $('#new_membership').before(new_row);
    });

    $('.remove_pack').on('click',function () {
        $(this).parent().remove();
    });

    ///////////////////////////////////////////////////////////////////////////////
    /// pin upload
    ///////////////////////////////////////////////////////////////////////////////
    $('.pin-upload').on('click',function () {
        var formfield, imgurl;
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        formfield = $(this).prev();
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            formfield.val(imgurl);
            tb_remove();
        };
        return false;
    });

    ///////////////////////////////////////////////////////////////////////////////
    /// upload header
    ///////////////////////////////////////////////////////////////////////////////
    $('#global_header_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#global_header').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#global_header').val(imgurl);
            tb_remove();
        };
        return false;
    });

    /////////////////////////////////////////////////////////////////////////////////
    /// upload footer
    ///////////////////////////////////////////////////////////////////////////////
    $('#footer_background_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#footer_background').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#footer_background').val(imgurl);
            tb_remove();
        };
        return false;
    });

    ///////////////////////////////////////////////////////////////////////////////
    /// upload logo
    ///////////////////////////////////////////////////////////////////////////////
    $('#logo_image_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#logo_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#logo_image').val(imgurl);
            tb_remove();
        };
        return false;
    });
    
    ///////////////////////////////////////////////////////////////////////////////
    /// upload logo
    ///////////////////////////////////////////////////////////////////////////////
    $('#transparent_logo_image_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#logo_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#transparent_logo_image').val(imgurl);
            tb_remove();
        };
        return false;
    });
    
     ///////////////////////////////////////////////////////////////////////////////
    /// upload logo
    ///////////////////////////////////////////////////////////////////////////////
    $('#mobile_logo_image_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#logo_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#mobile_logo_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    ///////////////////////////////////////////////////////////////////////////////
    /// upload fotoer logo
    ///////////////////////////////////////////////////////////////////////////////
    $('#footer_logo_image_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#footer_logo_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#footer_logo_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    $('#favicon_image_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#favicon_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#favicon_image').val(imgurl);
            tb_remove();
        };
        return false;
    });


    $('#logo_image_retina_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#logo_image_retina').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#logo_image_retina').val(imgurl);
            tb_remove();
        };
        return false;
    });
    $('#transparent_logo_image_retina_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#transparent_logo_image_retina').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#transparent_logo_image_retina').val(imgurl);
            tb_remove();
        };
        return false;
    });
    
    $('#mobile_logo_image_retina_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#mobile_logo_image_retina').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#mobile_logo_image_retina').val(imgurl);
            tb_remove();
        };
        return false;
    });


  
    $('#background_image_button').on('click',function () {
        var formfield, imgurl;
        formfield = $('#background_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#background_image').val(imgurl);
            tb_remove();
        };
        return false;
    });


    function getUrlVars() {
        var vars = [], hash, hashes, i;
        hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    if ($(".admin_menu_list")[0]) {//if is our custom admin page
        // admin tab controls.
        var fullUrl, tab, pick;
        fullUrl = getUrlVars()["page"];
        tab = (fullUrl.split("#"));
        pick = tab[1];

        if (typeof tab[1] === 'undefined') {
            pick = "tab1";
        }

        $(".tabadmin").each(function () {
            if ($(this).attr("data-tab") === pick) {
                $(this).addClass("active");
            } else {
                $(this).removeClass("active");
            }
        });

        $(".admin_menu_list li").each(function () {
            if ($(this).attr("rel") === pick) {
                $(this).addClass("active");
            } else {
                $(this).removeClass("active");
            }
        });

    }
    
    my_colors = ['page_header_overlay_color_video',
        'page_header_overlay_color',
        ];
 

        for(let i=0, size=my_colors.length; i<size; i++){

            jQuery('#' + my_colors[i] ).ColorPicker({ 
                onChange: function (hsb, hex, rgb) {
                    $('#' + my_colors[i] + ' .sqcolor').css('background-color', '#' + hex);
                    $('[name=' + my_colors[i] + ']' ).val( hex );
                }
            });	
        }
    
    


    
    function clearimg() {
	 $('#tabpat img').each(
            function () {
                $(this).css('border','none');
            });
    }



    $('input[id^="item-custom"]').on('click',function () {
	var formfieldx, imgurl;
        formfieldx = "edit-menu-"+$(this).attr("id");
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery("#"+formfieldx).val(imgurl);
            tb_remove();
        };
        return false;
	});
        
//        page header options 
        $('#page_header_type').change(function(){
                var value=$(this).val();
               
                wpestate_show_heder_options(value);
               
        });
            

        function wpestate_show_heder_options(value){
            $('.header_admin_options').hide();

            if (value=='2'){
                 $('.header_admin_options.image_header').show();
            }
            else if (value=='3'){
                $('.header_admin_options.theme_slider').show();
            }
            else if (value=='4'){
                $('.header_admin_options.revolution_slider').show();
            }
            else if (value=='5'){
                 $('.header_admin_options.google_map').show();
            } 
            else if (value=='6'){
                 $('.header_admin_options.video_header').show();
            }
        }
        
        $('#page_header_type').trigger('change');
        //end page header options
        
////////////////////////////////////////////////////////////////////////////
//start setup
////////////////////////////////////////////////////////////////////////////
 

    $('#wpestate_close_notice').on( 'click', function() {

        var  ajaxurl         = admin_vars.ajaxurl;
        var nonce = jQuery('#wprentals_activate_license_nonce').val();
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            
            data: {
                'action'        :  'wpestate_disable_licence_notifications',
                'security'      :   nonce
            },
            success: function (data) { 
                jQuery('#license_check_wrapper').hide();
            },
            error: function (errorThrown) {
            }
        });
    });


    $('#button_start_notice').on('click',function () {
        $('#wpestate_start').slideUp();
        $('#wpestate_start_map').slideDown();
    });
    
    
    $('#button_map_set').on('click',function () {
        var ajaxurl, ssl_map_set, api_key,map_type_option,places_type_option,mapbox_api_key,algolia_app_id,algolia_app_key;
        ssl_map_set     = jQuery('#ssl_map_set').val();
        api_key         = jQuery('#api_key').val();
        map_type_option = jQuery('#map_type_option').val();
        places_type_option = jQuery('#places_type_option').val();
        mapbox_api_key     = jQuery('#mapbox_api_key').val();
        algolia_app_id     = jQuery('#algolia_app_id').val();
        algolia_app_key    = jQuery('#algolia_app_key').val();
        ajaxurl         = admin_vars.ajaxurl;
         var nonce = jQuery('#wprentals_map_set_nonce').val();
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            data: {
                'action'              :  'wpestate_ajax_start_map',
                'ssl_map_set'         :  ssl_map_set, 
                'api_key'             :  api_key,
                'map_type_option'     :  map_type_option,
                'places_type_option'  :  places_type_option,
                'mapbox_api_key'      :  mapbox_api_key,
                'algolia_app_id'      :  algolia_app_id,
                'algolia_app_key'     :  algolia_app_key,
                'security'            :  nonce,
            },
            success: function (data) { 
                $('#wpestate_start_map').hide();
                $('#wpestate_general_settings').show();

            },
            error: function (errorThrown) {
            }
        });


    });
    
    
    
    $('#button_general_set').on('click',function () {
        var ajaxurl, prices_th_separator_set, currency_label_main,where_currency_symbol,date_lang;
        prices_th_separator_set     = jQuery('#prices_th_separator_set').val();
        currency_label_main     = jQuery('#currency_label_main').val();
        where_currency_symbol   = jQuery('#where_currency_symbol').val();
        date_lang               = jQuery('#date_lang').val();
        ajaxurl                 = admin_vars.ajaxurl;
         var nonce = jQuery('#wprentals_general_set_nonce').val();
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            data: {
                'action'                 :  'wpestate_ajax_general_set',
                'prices_th_separator_set':  prices_th_separator_set, 
                'currency_label_main'    :  currency_label_main,
                'where_currency_symbol'  :  where_currency_symbol,
                'date_lang'              :  date_lang,
                'security'               :  nonce
            },
            success: function (data) {
         
                $('#wpestate_general_settings').hide();
                $('#wpestate_booking_settings').show();
               
            },
            error: function (errorThrown) {
            }
        });


    });
    
    $('#button_booking_set').on('click',function () {
        var ajaxurl, date_format_set, setup_weekend;
        date_format_set      = jQuery('#date_format_set').val();
        setup_weekend    = jQuery('#setup_weekend').val();
        ajaxurl          = admin_vars.ajaxurl;
        var nonce = jQuery('#wprentals_date_format_nonce').val();
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            data: {
                'action'            :  'wpestate_booking_settings',
                'date_format_set'   :  date_format_set, 
                'setup_weekend'     :  setup_weekend,
                'security'          :   nonce
                
            },
            success: function (data) {
                $('#wpestate_booking_settings').hide();
                $('#wpestate_booking_payment').show();
               
            },
            error: function (errorThrown) {
            }
        });
        
        
        $('#button_continue_notice').on('click',function () {
            $('##wpestate_start_wrapper').slideUp();
     
        });
    


    });
    
    
    
    
    $('#button_booking_payment').on('click',function () {
        var ajaxurl,include_expenses, book_down, book_down_fixed_fee, service_fee, service_fee_fixed_fee;
        include_expenses     = jQuery('#include_expenses').val();
        book_down            = jQuery('#book_down').val();
        book_down_fixed_fee  = jQuery('#book_down_fixed_fee').val();
        service_fee          = jQuery('#service_fee').val();
        service_fee_fixed_fee= jQuery('#service_fee_fixed_fee').val();
        ajaxurl              = admin_vars.ajaxurl;
        var nonce = jQuery('#wprentals_booking_payment_nonce').val();
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            data: {
                'action'                 :  'wpestate_booking_payment',
                'include_expenses'      :  include_expenses, 
                'book_down'              :  book_down,
                'book_down_fixed_fee'    :  book_down_fixed_fee,
                'service_fee'            :  service_fee,
                'service_fee_fixed_fee'  :  service_fee_fixed_fee,
                'security'              :   nonce
            },
            success: function (data) { 
                $('#wpestate_booking_payment').hide();
                $('#wpestate_end').show();
            },
            error: function (errorThrown) {
            }
        });


    });
    
    $('#button_map_prev').on('click',function () {
        $('#wpestate_start').show();
        $('#wpestate_start_map').hide();
    });
    
    $('#button_general_prev').on('click',function () {
        $('#wpestate_start_map').show();
        $('#wpestate_general_settings').hide();
    });
    
    $('#button_booking_prev').on('click',function () {
        $('#wpestate_general_settings').show();
        $('#wpestate_booking_settings').hide();
    });
    
    
    $('#button_booking_payment_prev').on('click',function () {
        $('#wpestate_booking_settings').show();
        $('#wpestate_booking_payment').hide();
    });
    
    
    
});
function  wpestate_set_theme_tab_visible2(){
    var current_url=window.location.href;
    var page_par=findGetParameter('subpage');
    
    if(page_par==='logos_favicon_tab'){
        localStorage.setItem('hidden_tab','general_settings_sidebar');
        localStorage.setItem('hidden_sidebar','logos_favicon_tab');
    } 
  
    if(page_par==='custom_colors_tab'){
        localStorage.setItem('hidden_tab','design_settings_sidebar');
        localStorage.setItem('hidden_sidebar','custom_colors_tab');
    }
    
    if(page_par==='pay_settings'){
        localStorage.setItem('hidden_tab','membership_settings_sidebar');
        localStorage.setItem('hidden_sidebar','membership_settings_tab');
    }
    
     
}


function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
          tmp = item.split("=");
          if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}


function wpestate_set_theme_tab_visible(){
   
    var show_tab        =   localStorage.getItem('hidden_tab');
    var show_sidebar    =   localStorage.getItem('hidden_sidebar');
    if(show_tab===null || show_tab===''){
        show_tab = 'general_settings_sidebar';
    }
    
    if(show_sidebar=== null || show_sidebar==''){
        show_sidebar = 'global_settings_tab';
    }
    

  
    if(show_tab!=='none'){
     
        jQuery('.theme_options_sidebar, .theme_options_wrapper_tab').hide();
        jQuery('#'+show_tab).show();
        jQuery('#'+show_tab+'_tab').show();
        jQuery('.wrap-topbar div').removeClass('tobpbar_selected_option');
        jQuery('.wrap-topbar div[data-menu="'+show_tab+'"]').addClass('tobpbar_selected_option');
    }


    if(show_sidebar!=='none'){
       
        jQuery('.theme_options_tab').hide();
        jQuery('#'+show_sidebar).show();
        jQuery('#wpestate_sidebar_menu li').removeClass('selected_option');
        jQuery('#wpestate_sidebar_menu li[data-optiontab="'+show_sidebar+'"]').addClass('selected_option');
        
    }
 
}



function wpestate_theme_options_sidebar(){
    var new_height;
    new_height = jQuery ('#wpestate_wrapper_admin_menu').height();
    jQuery ('#wpestate_sidebar_menu').height(new_height);
    
}


