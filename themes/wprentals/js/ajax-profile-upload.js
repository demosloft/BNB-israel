/*global $, jQuery, document, window, plupload, ajax_vars */
var current_no_up;

jQuery(document).ready(function ($) {
    "use strict";
    var array_cut,
        should_warn=0,
        current_no_up=  parseInt( jQuery('.uploaded_images ').length,10),
        array_cut=0;

    if (typeof (plupload) !== 'undefined') {
        var uploaders = new Array();

        var initUploaders = function (uploaders) {
            var uploadButtons = jQuery('.feature-media-upload').find('button');

            if (uploadButtons.length > 0) {
                jQuery('.feature-media-upload').each(function () {
                    var uploadTo = jQuery(this),
                        browseBtn = uploadTo.find('button'),
                        uploadList = uploadTo.attr('id') + '-upload-imagelist',
                        thumbHolder = uploadTo.find('img');

                    ajax_vars.plupload.browse_button = browseBtn.attr('id');
             
                    var uploader = new plupload.Uploader(ajax_vars.plupload);
                    uploader.settings.multipart_params["button_id"] = ajax_vars.plupload.browse_button;
                    uploader.init();
                 

                    uploader.bind('FilesAdded', function (up, files) {

                        if(ajax_vars.max_images>0) { // if is not unlimited
                            if (current_no_up === 0) {
                                array_cut = ajax_vars.max_images;
                                if (files.length > ajax_vars.max_images) {
                                    current_no_up = array_cut;
                                } else {
                                    current_no_up = files.length;
                                }
                            } else {
                                if (current_no_up >= ajax_vars.max_images) {
                                    array_cut = -1;
                                } else {
                                    array_cut = ajax_vars.max_images - current_no_up;
                                    if (files.length > array_cut) {
                                        current_no_up = current_no_up + array_cut;
                                    } else {
                                        current_no_up = current_no_up + files.length;
                                    }

                                }
                            }


                            if (array_cut > 0) {
                                up.files.slice(0, array_cut);
                                files.slice(0, array_cut);
                                var i = array_cut;
                                while (files.length > array_cut) {
                                    up.files.pop();
                                    files.pop();
                                    should_warn = 1;
                                }
                            }

                            if (should_warn === 1) {
                                jQuery('.image_max_warn').remove();
                                uploadList.before('<div class="image_max_warn" style="width:100%;float:left;">' + ajax_vars.warning_max + '</div>');
                            }

                            if (array_cut == -1) {
                                jQuery('.image_max_warn').remove();
                                jQuery('#' + uploadList ).before('<div class="image_max_warn" style="width:100%;float:left;">' + ajax_vars.warning_max + '</div>');
                                files = [];
                                up = [];
                                return;
                            }
                        }

                        up.refresh(); // Reposition Flash/Silverlight
                        uploader.start();
                    });

                    uploader.bind('UploadProgress', function (up, file) {
                        jQuery('#' + uploadList ).html(file.percent + "%");
                    });

                    uploader.bind('Error', function (up, err) {
                        jQuery('#' + uploadList ).append("<div>Error: " + err.code +
                            ", Message: " + err.message +
                            (err.file ? ", File: " + err.file.name : "") +
                            "</div>"
                        );
                        up.refresh(); // Reposition Flash/Silverlight
                    });

                    uploader.bind('FileUploaded', function (up, file, response) {
                      
                        
                       var result = jQuery.parseJSON(response.response),
                           targetUpload = thumbHolder.attr('id'),
                           imageURL = '',
                           imageID = '';

                        if (result.success) {

                            switch (true) {
                                case (targetUpload === 'profile-image'):
                                    imageURL = 'data-profileurl';
                                    imageID = 'data-smallprofileurl';
                                    break;
                                case (targetUpload === 'user-id-image'):
                                    imageURL = 'data-useridurl';
                                    imageID = 'data-useridimageid';
                                    break;
                            }

                            thumbHolder.attr({
                                'src': result.html,
                                [imageURL] : result.html,
                                [imageID] : result.attach
                        });
                            jQuery('#' + uploadList).remove();
                        }
                    });

                    uploaders.push(uploader);

                });
            }
        };

        initUploaders(uploaders);
    }
});