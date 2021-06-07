jQuery(function ($) {

    $(".sidebar-dropdown > a").on('click', function() {
        $(".sidebar-submenu").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this).next(".sidebar-submenu").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $("#show-sidebar").on('click', function() {
        $(".page-wrapper").toggleClass("toggled");
        $("#show-sidebar-icon").toggleClass("fa-times fa-bars");
    });
});