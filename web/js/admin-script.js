$(function() {

    $(document).tooltip();

    $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );

    $('#start-date, #end-date').datepicker({dateFormat: 'yy-mm-dd'});

    $("#main-menu").children("li").has("ul").children("a").on("click", function(e) {
        e.preventDefault();
    });

    // icon-menu

    var menu = $("#icon-menu").children("ul");

    var firstLevelLinks = menu.children("li").children("a");

    var hasSubLinks = menu.children("li").has("ul").children("a");

    hasSubLinks.on("click", showSubLinks);

    function showSubLinks(e) {
        e.preventDefault();

        $(this).next("ul").show();

        firstLevelLinks.hide(); 
    };

    $(".menu-back").on("click", hideSubLinks);

    function hideSubLinks(e) {
        e.preventDefault();

        $(this).closest("ul").hide();
        firstLevelLinks.show();
    };

    // actions

    var actions = $("#actions").children("li").children("a");

    var actionForms = $(".action-form");

    actions.on("click", showAction);

    function showAction(e) {
        e.preventDefault();

        if( !$(this).hasClass("current-action") ) {
            actionForms.slideUp("fast");
            actions.removeClass("current-action");
            $(this).addClass("current-action").next(".action-form").slideDown("fast");
        } else {
            actions.removeClass("current-action");
            actionForms.slideUp("fast");
        };
    };


    $(window).on("scroll", function() {
        console.log( $(window).scrollTop() );
        if( $(window).scrollTop() > 0) {
            $("#header").addClass("shadow");
        } else {
            $("#header").removeClass("shadow");
        }
        
    });

});