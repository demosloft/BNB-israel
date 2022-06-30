/*global $, jQuery, document, window, plupload, ajax_vars ,ajaxurl*/
   var current_no_up;
function thumb_setter() {
    "use strict";
    jQuery('#imagelist img').dblclick(function () {
        jQuery('#imagelist .uploaded_images .thumber').each(function () {
            jQuery(this).remove();
        });
        jQuery('.fa-star').remove();
        jQuery(this).parent().append('<i class="fas fa-star"></i>');
        jQuery('#attachthumb').val(jQuery(this).parent().attr('data-imageid'));
    });
}



function delete_binder() {
    "use strict";
    jQuery('#imagelist i').unbind('click');
    jQuery('#imagelist i.fa-trash-alt').on('click',function () {
        var curent = '';
        var remove='';
        var img_remove= jQuery(this).parent().attr('data-imageid');
         var nonce = jQuery('#wpestate_image_upload').val();
        current_no_up=current_no_up-1;
 
        jQuery(this).parent().remove();

        jQuery('#imagelist .uploaded_images').each(function () {
            curent = curent + ',' + jQuery(this).attr('data-imageid');
        });
        jQuery('#attachid').val(curent);
        var ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'            :   'wpestate_delete_file',
                'attach_id'         :   img_remove,
                'security'          :   nonce
                
            },
            success: function (data) {     
              

            },
            error: function (errorThrown) {}
        });//end ajax     

    });

}

jQuery(document).ready(function ($) {
    "use strict";
    var all_id, uploader, result;
    
    delete_binder();
    var array_cut;
    var should_warn=0;
    current_no_up=  parseInt( $('.uploaded_images ').length,10);
    array_cut=0;
   
    
        if (typeof (plupload) !== 'undefined') {
            uploader = new plupload.Uploader(ajax_vars.plupload);
            uploader.init();
            uploader.bind('FilesAdded', function (up, files) {

            if(ajax_vars.max_images>0){ // if is not unlimited
                if(current_no_up===0){
                    array_cut=ajax_vars.max_images;
                    if(files.length>ajax_vars.max_images){
                        current_no_up=array_cut;
                    }else{
                        current_no_up=files.length;
                    }
                }else{
                    if (current_no_up>=ajax_vars.max_images){
                        array_cut=-1;
                    }else{
                        array_cut=ajax_vars.max_images-current_no_up;
                        if(files.length>array_cut){
                            current_no_up=current_no_up+array_cut;
                        }else{
                            current_no_up=current_no_up+files.length;
                        }

                    }
                }


                if(array_cut>0 ){
                    up.files.slice(0,array_cut);
                    files.slice(0,array_cut);   
                    var i = array_cut;
                    while (files.length>array_cut){
                        up.files.pop();
                        files.pop();  
                        should_warn=1;
                    }
                }

                if(should_warn===1){
                    $('.image_max_warn').remove();
                    $('#imagelist').before('<div class="image_max_warn" style="width:100%;float:left;">'+ajax_vars.warning_max+'</div>');
                }

                if( array_cut==-1 ){
                    $('.image_max_warn').remove();
                    $('#imagelist').before('<div class="image_max_warn" style="width:100%;float:left;">'+ajax_vars.warning_max+'</div>');
                    files=[];
                    up=[];
                    return;
                }

            }

            $.each(files, function (i, file) {
                $('#aaiu-upload-imagelist').append('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>');
            });

            up.refresh(); // Reposition Flash/Silverlight
            uploader.start();
        });

        uploader.bind('UploadProgress', function (up, file) {
            $('#' + file.id + " b").html(file.percent + "%");
        });

        // On erro occur
        uploader.bind('Error', function (up, err) {
         
            $('#aaiu-upload-imagelist').append("<div>Error: " + err.code +
                    ", Message: " + err.message +
                    (err.file ? ", File: " + err.file.name : "") +
                    "</div>"
                    );
            up.refresh(); // Reposition Flash/Silverlight
        });





        uploader.bind('FileUploaded', function (up, file, response) {
            var current_no_up2=  parseInt( $('.uploaded_images ').length,10);
           
            if(ajax_vars.max_images>0 && current_no_up2> ajax_vars.max_images){ 
            return;
            }            

            result = $.parseJSON(response.response);
            $('#image_warn').remove();
            $('#' + file.id).remove();
            if (result.success) {

         

                $('#profile-image').attr('src', result.html);
                $('#profile-image').attr('data-profileurl', result.html);
                $('#profile-image').attr('data-smallprofileurl', result.attach);
                
           

                all_id = $('#attachid').val();
                all_id = all_id + "," + result.attach;
                $('#attachid').val(all_id);
              
                if (result.html !== '') {
                    $('#imagelist').append('<div class="uploaded_images" data-imageid="' + result.attach + '"><img src="' + result.html + '"  /><i class="far fa-trash-alt"></i> </div>');
                } else {
                    $('#imagelist').append('<div class="uploaded_images" data-imageid="' + result.attach + '"><img src="' + ajax_vars.path + '/img/pdf.png"  /><i class="far fa-trash-alt"></i> </div>');
                }

                $( "#imagelist" ).sortable({
                    revert: true,
                    update: function( event, ui ) {
                        var all_id,new_id;
                        all_id="";
                        $( "#imagelist .uploaded_images" ).each(function(){

                            new_id = $(this).attr('data-imageid'); 
                            if (typeof new_id != 'undefined') {
                                all_id=all_id+","+new_id; 

                            }

                        });

                        $('#attachid').val(all_id);
                    },
                });

                delete_binder();
                thumb_setter();
            }else{
                    
                if (result.image){ 
                    $('#imagelist').before('<div id="image_warn" style="width:100%;float:left;">'+ajax_vars.warning+'</div>');
                }
            }
        });


        $('#aaiu-uploader').on('click',function (e) {
            uploader.start();
            e.preventDefault();
        });

        $('#aaiu-uploader2').on('click',function (e) {
            uploader.start();
            e.preventDefault();
        });

        $('#aaiu-uploader-floor').on('click',function (e) {
            e.preventDefault();
            $('#aaiu-uploader').trigger('click');
        });
    }
});