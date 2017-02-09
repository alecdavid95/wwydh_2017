// alright boys, get ready...

// Google's MAP function DON'T TOUCH!!
function initMap() {
    // call my getLocation method
    console.log("calling get location");
    getCurrentLocation(function(location) {
        if (location == 1) {
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
                    address: this.mailing_address,
                    distance: this.distance
                });

                marker.addListener("click", function() {
                    alert("Distance (as the crow flies): " + this.distance); // FRONTEND:10 change the map marker click listener to trigger location popup
                })
            });
        } else if (location != -1) {
            // getCurrentLocation returned an array, which means
            // we need to reload page and set GET variables to run search with php
            var url = window.location.href;
            if (url.indexOf("?") < 0) url += "?";
            else url += "&";

            url += "lat=" + location.lat;
            url += "&lng=" + location.lng;

            console.log("forwarding...");

            window.location.href = url;
        }
    })
}

function getCurrentLocation(callback) {
    if (!loadPage) {
        // not already using current location, attempt to grab it
        if (navigator.geolocation) {
            $("body").prepend('<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>');
            // on a secure connection (https) and can grab location
            var lat, long;
            navigator.geolocation.getCurrentPosition(function(position) {
                console.log("we have the location");
                callback({lat: position.coords.latitude, lng: position.coords.longitude});
            }, function(error) {
                // location was declined WOOP WOOP
                console.log("location declined");

                var url = window.location.href;
                if (url.indexOf("?") < 0) url += "?";
                else url += "&";

                url += "deniedLocation";

                console.log("forwarding...");

                window.location.href = url;
            })
        } else {
            // not on secure connection and cant grab current location
            callback(-1);
        }
    } else {
        // lat and lng were specified in URL and PHP already pulled location information based on current location (25 mile radius)
        callback(1);
    }
}
