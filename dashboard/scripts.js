var paddingOffset = 60;
var plan = false;
var planCount = 1;

var plan_info = false;

jQuery(document).ready(function($) {

    if (plan) $("#new-plan").addClass("open");
    $("html").on("click", function(e) {
        if (plan && !$(e.target).parents("#new-plan .wrapper").length) {
            $(this).parents(".overlay-inner").children("input").val("");
            $(".overlay").removeClass("open");
            plan = false;
        }
    });

    $("#sidebar li").on("click", function() {
        $(".pane, #sidebar li").removeClass("active");
        $("#" + $(this).data("target")).addClass("active");
        $(this).addClass("active");
    });

    $("#sidebar .new-menu .new-menu-link").on("click", function() {
        if ($(this).hasClass("new-plan")) {
            $("#new-plan").addClass("open");
            setTimeout(function() {
                plan = true;
            }, 100);
        } else if ($(this).hasClass("new-idea")) {
            window.location.href = "../ideas/new";
        } else if ($(this).hasClass("new-location")) {
            window.location.href = "../locations/new";
        }
    })

    $("#overview-plans #plan-categories select").on("input", function() {
        $(".plan-table").removeClass("active");
        $(".plan-table." + $(this).val()).addClass("active");
    })

    $("#overview-plans td.edit").on("click", function() {
        // close all open plan-info divs first
        $("div.plan-info").removeClass("show");

        if (!plan_info) {
            // calculate left/top positions for target plan-info and grab target plan-id
            var left = $(this).position().left - $(this).width();
            var top = $(this).offset().top + $(this).height() + 15;
            var plan_id = $(this).parents(".plan").data("plan");

            var target_plan = $(".plan-info[data-plan=" + plan_id + "]");

            $(target_plan).css({"left": (left - $(target_plan).width() + 90) + "px", "top": top + "px"})
            $(target_plan).addClass("show");
            plan_info = true;
        } else plan_info = false;
    });

    $("#create-plan").on("click", function() {
        var title = $("#new-plan input").val();

        if (title.length == 0) {
            $("#new-plan input").addClass("empty");
        } else {
            $("#new-plan input").removeClass("empty");
            var location = $(this).data("location");
            var idea = $(this).data("idea");

            var data = {
                title: title,
            };

            if (location != undefined) data.location = location;
            if (idea != undefined) data.idea = idea;

            $.post("../helpers/plans/new.php", data, function(response) {
                if (response != "-1") {
                    window.location.reload();
                }
            })
        }
    });

    $("#cancel-create-plan").click(function() {
        $(this).parents(".overlay-inner").children("input").val("");
        $(".overlay").removeClass("open");
        plan = false;
    });
});

$(window).on("load", function() {
    $("#content").width($(window).width() - $("#sidebar").width() - paddingOffset);
});

$(window).on("resize", function() {
    $("#content").width($(window).width() - $("#sidebar").width() - paddingOffset);
});
