<?php
$location = "Locations/" . $_COOKIE["location"];
if (!file_exists($location)) {
    mkdir($location, 0777, true);
    chmod("$location", 0777);
}

$filename = $_FILES["file"]["name"];
if (!file_exists($location . "/" . $filename)) {
    $filename = $_FILES["file"]["name"];
} else {
    $filename = time() . "_" . $filename;
}
if (
    move_uploaded_file($_FILES["file"]["tmp_name"], $location . "/" . $filename)
) {
    echo $location . "/" . $filename;
} else {
    echo "Did not work";
}

die();