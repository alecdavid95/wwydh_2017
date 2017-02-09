jQuery(document).ready(function($) {

    // scroll to how it works on click
    $("#see-how").click(function() {
        $("html, body").animate({scrollTop: $("#how").offset().top}, 650);
    })

    // handle homepage tab switching
    $("li.tablink").click(function() {
        if (!$(this).hasClass("active")) {
            // handle nav change
            $("li.tablink").removeClass("active");
            $(this).addClass("active");

            // handle content change
            $(".tabcontent").removeClass("active");
            $(".tabcontent[data-tab=" + $(this).data("target") + "]").addClass("active");
        }
    });
});

// open and close functions for home sidemenu
function openNav() {
    $(".sidenav").addClass("open");
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

// Google's MAP function DON'T TOUCH!!
function initMap() {
    // Create a map object and specify the DOM element for display.
    var map = new google.maps.Map(document.getElementById('map'), {
        animation: google.maps.Animation.DROP,
        center: {lat: parseFloat(locations[0].latitude), lng: parseFloat(locations[0].longitude)},
        scrollwheel: false,
        zoom: 14
    });

    $(locations).each(function() {
        var marker = new google.maps.Marker({
            map: map,
            position: {lat: parseFloat(this.latitude), lng: parseFloat(this.longitude)},
            address: this.mailing_address
        });

        marker.addListener("click", function() {
            alert(this.address); // FRONTEND:10 change the map marker click listener to trigger location popup
        })
    })
}
