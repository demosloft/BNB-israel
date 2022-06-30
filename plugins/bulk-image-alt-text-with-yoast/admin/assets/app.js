jQuery(document).ready(function () {
    jQuery('.bialty-alert').on('click', '.closebtn', function () {
        jQuery(this).closest('.bialty-alert').fadeOut(); //.css('display', 'none');
    });

    jQuery('.bialty-boost-label input').on('click', function() { 
        jQuery('.bialty-boost').slideToggle();
    });

    jQuery('.bialty-mobi-label input').on('click', function() { 
        jQuery('.bialty-mobi').slideToggle();
    });
    jQuery('.bialty-bigta-label input').on('click', function() { 
        jQuery('.bialty-bigta').slideToggle();
    });
    jQuery('.bialty-vidseo-label input').on('click', function() { 
        jQuery('.bialty-vidseo').slideToggle();
    });

    jQuery(function() {
        jQuery(".meter > span").each(function() {
            jQuery(this)
                .data("origWidth", jQuery(this).width())
                .width(0)
                .animate({
                    width: jQuery(this).data("origWidth")
                }, 2500);
        });
    });

    jQuery("#fs_connect button[type=submit]").on("click", function(e) {
        console.log("open verify window")
        window.open('https://better-robots.com/subscribe.php?plugin=bialty','bialty','resizable,height=400,width=700');
    });

});