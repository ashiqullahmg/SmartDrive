var fileobj;


function upload_file(e) {
    e.preventDefault();
    fileobj = e.dataTransfer.files[0];
    var directoryName = getCookie('location');
    let last_dot = fileobj.name.lastIndexOf('.')
    let ext = fileobj.name.slice(last_dot + 1)
    console.log(ext);
    const fileType = ['gif', 'GIF', 'jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'mp4', 'MP4', 'pdf', 'PDF', 'mov', 'MOV'];
    if (fileType.indexOf(ext) !== -1) {
        if (directoryName == 'Unknown') {
            const response = confirm("Are you sure you want to upload file to " + getCookie('location') + "?");
            if (response) {
                ajax_file_upload(fileobj);
                showLoader();
            } else {
                alert("Please add a custom location.");
            }
        } else {
            ajax_file_upload(fileobj);
            showLoader();
        }
    } else {
        alert("Please upload pictues, pdf and videos files only");
    }
}

function file_explorer() {
    document.getElementById('selectfile').click();
    document.getElementById('selectfile').onchange = function () {
        fileobj = document.getElementById('selectfile').files[0];
        let last_dot = fileobj.name.lastIndexOf('.')
        let ext = fileobj.name.slice(last_dot + 1)
        console.log(ext);
        const fileType = ['gif', 'GIF', 'jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'mp4', 'MP4', 'pdf', 'PDF', 'mov', 'MOV'];
        if (fileType.indexOf(ext) !== -1) {
            var directoryName = getCookie('location');
            if (directoryName == 'Unknown') {
                const response = confirm("Are you sure you want to upload file to " + getCookie('location') + "?");
                if (response) {
                    ajax_file_upload(fileobj);
                    showLoader();
                } else {
                    alert("Please add a custom location.");
                }
            } else {
                ajax_file_upload(fileobj);
                showLoader();
            }

        } else {
            alert("Please Upload Pictues, PDF and Videos Files only");
        }
    };
}

function ajax_file_upload(file_obj) {

    if (file_obj != undefined) {

        var form_data = new FormData();
        form_data.append('file', file_obj);
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "upload.php", true);
        xhttp.onload = function (event) {
            oOutput = document.querySelector('.img-content');
            uploadMsg = document.querySelector('.uploadMsg');

            pdfView = document.querySelector('.img-content');

            var filePath = this.responseText;
            let last_dot = filePath.lastIndexOf('.')
            let ext = filePath.slice(last_dot + 1)


            if (xhttp.status == 200) {
                if (ext == 'pdf') {
                    pdfView.innerHTML = "<embed src='" + this.responseText + "' type='application/pdf' scrolling='no'>";
                } else if (ext == 'mp4') {
                    oOutput.innerHTML = "<video width='30%' height='auto' controls> <source src='" + this.responseText + "' type='video/mp4'> Your browser does not support HTML5 video. </video>";
                } else if (ext == 'mov') {
                    oOutput.innerHTML = "<video width='30%' height='auto' controls> <source src='" + this.responseText + "' type='video/mov'> Your browser does not support HTML5 video. </video>";
                } else {
                    oOutput.innerHTML = "<img  src='" + this.responseText + "' alt='The Image' />";
                }

                uploadMsg.innerHTML = "<h5>File has been uploaded to " + getCookie('location') + ".</h5>";
                load_folder_list();
            } else {
                oOutput.innerHTML = "Error " + xhttp.status + " occurred when trying to upload your file.";
            }
            $("#loadMe").modal('hide');
        }

        xhttp.send(form_data);
    }
    $("#loadMe").modal('hide');

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

function load_folder_list() {
    var action = "fetch";
    $.ajax({
        url: "action.php",
        method: "POST",
        data: { action: action },
        success: function (data) {
            $('#folder_table').html(data);
        }
    });
}

function showLoader() {
    $("#loadMe").modal('show');
}