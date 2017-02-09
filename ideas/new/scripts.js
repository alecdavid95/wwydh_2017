jQuery(document).ready(function($) {

    $(".advance").click(function() {
        var elem = $(this);
        var target = $(this).data("target");

        if (target == -1) {
            submitIdea(elem);
        } else {
            $(this).parents(".pane").addClass("done").removeClass("active");
            $(".pane[data-index=" + target + "]").addClass("active");
        }
    });

    $(".login input[type=submit]").click(function() {
        var elem = $(this);
        doLogin(elem);
    });

    $(".retreat").click(function() {
        var target = $(this).data("target");

        $(this).parents(".pane").removeClass("active");
        $(".pane[data-index=" + target + "]").removeClass("done").addClass("active");
    });

    $(".pane[data-index=1] .button").click(function() {
        $(".pane[data-index=1] .button").removeClass("active");

        if ($(this).data("leader") === 0) {
            $(".pane[data-index=1] .button[data-leader=0]").addClass("active");
            $(".pane[data-index=1] .login-warning").removeClass("active");
        } else {
            $(".pane[data-index=1] .button[data-leader=1]").addClass("active");
            $(".pane[data-index=1] .login-warning").addClass("active");
        }
    });

    $(".add-checklist-item").click(function() {
        addItem($(this));
    });

    /*

        Garbage code for automatically traversing the checklist panes. Fix or delete.

    $(".checklist").on("input", ".checklist-item input", function() {
        var check = $(this).val().match(/ x [0-9]+/gi);

        if  (check != null && check.length == 1) $(this).parent().addClass("valid");
        else $(this).parent().removeClass("valid");
    })

    $(".checklist").on("blur", ".checklist-item.valid input", function(e) {
        var elem = $(this);
        if ($(".checklist-item").not(".valid").length == 0) {
            addItem($(this));
            traverse($(this));
        } else {
            traverse($(this));
        }
    })
    */

    function doLogin(elem) {
        var login = {
            username: $(".login input[name=user]").val(),
            password: $(".login input[name=pass]").val()
        };

        $.post("../../helpers/standalone-login.php", login, function(data) {

            if (data == 1) {
                submitIdea(elem);
            } else {
                // bad login information
                alert("Login information incorrect! Please try again!");
            }
        }, "text");
    }

    function submitIdea(elem) {
        // handle form submission
        var location_requirements = "";
        var contributions = "";

        $(".location-checklist .checklist-item").each(function(index, value) {
            if ($("input", value).val().length > 0) {
                if (index == 0) location_requirements += $("input", value).val();
                else  location_requirements += "[-]" + $("input", value).val();
            }
        });

        $(".checklist .checklist-item").each(function(index, value) {
            if ($("input", value).val().length > 0) {
                if (index == 0) contributions += $("input", value).val();
                else  contributions += "[-]" + $("input", value).val();
            }
        });

        var form = {
            leader: ($(".pane .button.active").data("leader") === 1) ? true : false,
            title: $("input[name=title]").val(),
            description: $("textarea").val(),
            category: $("[name=category]").val(),
            location_requirements: location_requirements,
            contributions: contributions,
            submit: "true"
        };

        console.log(form);

        $.post("../../helpers/ideas/new.php", form, function(data) {

            if (data == -1) {
                // login required
                $(elem).parents(".pane").addClass("done").removeClass("active");
                $(".pane[data-index=-1]").addClass("active");
            } else {
                // successfully inserted idea
                $(elem).parents(".pane").addClass("done").removeClass("active");
                $(".pane[data-index=-2]").addClass("active");
            }
        }, "text");
    }

    function addItem(elem) {
        $(elem).parent().append('<div class="checklist-item"><input type="text" placeholder="Enter another requirement here." /></div>');
    }

    function traverse(element) {
        $(element).parent().next().children("input").focus();
    }
});
