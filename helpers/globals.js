var submenu = false;
var add_to_plan = false;

jQuery(document).ready(function($) {
    // new-of-type button
    $(".new-of-type").on("click", function() {
        window.location.href = "new";
    })

    $("#user_nav .loggedin .click-space").click(function() {
        $("#user_nav .loggedin").toggleClass("down");
        submenu = !submenu;
    });

    $(window).scroll(function() {

        // close submenu on scroll
        if (submenu) {
            $("#user_nav .loggedin").removeClass("down");
            submenu = false;
        }
    })

    $("html").click(function(e) {
        // register clicks on body and close menu if not inside .loggedin
        if (!$(e.target).parents(".loggedin").length && submenu) {
            $("#user_nav .loggedin").removeClass("down");
            submenu = false;
        }

        if (!($(e.target).parents(".add-to-plan").length || $(e.target).parents(".plan-buttons").length) && add_to_plan) {
            $(".add-to-plan").removeClass("down");
            add_to_plan = false;
        }
    });

    // change sorting
    $("#toolbar #sort select").on("change", function() {
        window.location.href = "?sort=" + $(this).val();
    })
})
