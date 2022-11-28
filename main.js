// View files Starts Here
$(document).on('click', '.view_files', function () {
    var folder_name = $(this).data("name");
    var action = "fetch_files";
    $.ajax({
        url: "action.php",
        method: "POST",
        data: {
            action: action,
            folder_name: folder_name
        },
        success: function (data) {
            $('#file_list').html(data);
            $('#filelistModal').modal('show');
        }
    });
});
// View files Ends Here


// Delete Files Starts Here
$(document).on('click', '.remove_file', function () {
    var path = $(this).attr("id");
    var action = "remove_file";
    if (confirm("Are you sure you want to remove this file?")) {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                path: path,
                action: action
            },
            success: function (data) {
                alert(data);
                $('#filelistModal').modal('hide');
                load_folder_list();
            }
        });
    }
});
// Delete Files Ends Here

var erroris = [];

function getLocation() {
    if (navigator.geolocation) {
        erroris.push("Issue");
        navigator.geolocation.getCurrentPosition(showPosition, showError)
        erroris.push("Perfect issue");
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
        erroris.push("1");
        setCookie("location", "Unknown", 2);
    }
}

function showPosition(position) {
    lat = position.coords.latitude;
    lon = position.coords.longitude;
    displayLocation(lat, lon);

}

function showError(error) {
    setCookie("location", "Unknown", 2);
    errorMsg.style.visibility = 'visible';
    document.getElementById('create_folder').style.visibility = 'visible';
    switch (error.code) {
        case error.PERMISSION_DENIED:
            erroris.push("2");
            // x.innerHTML="You have denied the request for Geolocation. Try again or add custom location";
            x.innerHTML = "Current Location: " + getCookie("location");
            break;
        case error.POSITION_UNAVAILABLE:
            // x.innerHTML="Location information is unavailable. Try again or add custom location"
            erroris.push("3");
            break;
        case error.TIMEOUT:
            // x.innerHTML="The request to get user location timed out. Try again or add custom location"
            erroris.push("4");
            break;
        case error.UNKNOWN_ERROR:
            // x.innerHTML="An unknown error occurred. Try again or add custom location"
            erroris.push("5");
            break;
    }
    errorMsg.style.visibility = 'visible';
}

function displayLocation(latitude, longitude) {
    var location;
    var geocoder;
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(latitude, longitude);
    geocoder.geocode({
        'latLng': latlng
    },
        function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var add = results[0].formatted_address;
                    var value = add.split(",");
                    count = value.length;
                    country = value[count - 1];
                    state = value[count - 2];
                    city = value[count - 3];
                    location = city;
                    // x.innerHTML = "Current Directory: " + location;
                    var duration = "1";
                    var cookieName = "location";
                    var expires;
                    setCookie(cookieName, city, duration);
                    x.innerHTML = "Current Directory: " + getCookie(cookieName);
                } else {
                    x.innerHTML = "address not found";
                    erroris.push("6");
                    setCookie("location", "Unknown", 2);
                }
            } else {
                x.innerHTML = "Geocoder failed due to: " + status;
                erroris.push("7");
                setCookie("location", "Unknown", 2);
            }
        }
    );

}



function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setLocName() {
    x.innerHTML = "Current Location: " + getCookie("location");
}

function load_folder_list() {
    var action = "fetch";
    $.ajax({
        url: "action.php",
        method: "POST",
        data: {
            action: action
        },
        success: function (data) {
            $('#folder_table').html(data);
        }
    });
}