<?php

//  Formating Folder Size Starts here
function format_folder_size($size)
{
    if ($size >= 1073741824) {
        $size = number_format($size / 1073741824, 2) . " GB";
    } elseif ($size >= 1048576) {
        $size = number_format($size / 1048576, 2) . " MB";
    } elseif ($size >= 1024) {
        $size = number_format($size / 1024, 2) . " KB";
    } elseif ($size > 1) {
        $size = $size . " bytes";
    } elseif ($size == 1) {
        $size = $size . " byte";
    } else {
        $size = "0 bytes";
    }
    return $size;
}
//  Formating Folder Size Ends here

// Get Folder Starts Here
function get_folder_size($folder_name)
{
    $total_size = 0;
    $file_data = scandir($folder_name);
    foreach ($file_data as $file) {
        if ($file === "." or $file === "..") {
            continue;
        } else {
            $path = $folder_name . "/" . $file;
            $total_size = $total_size + filesize($path);
        }
    }
    return format_folder_size($total_size);
}
// Get Folder Ends Here

if (isset($_POST["action"])) {
    if ($_POST["action"] == "fetch") {
        $folder = array_filter(glob("Locations/*"), "is_dir");

        $output = '
  <table class="table table-bordered table-striped">
   <tr>
    <th>Location</th>
    <th>Total File(s)</th>
    <th>Size</th>
    <th>Action(s)</th>
    <th>View Uploaded File(s)</th>
   </tr>
   ';
        if (count($folder) > 0) {
            foreach ($folder as $name) {
                $two = explode("/", $name);
                $fname = $two[1];
                $output .=
                    '
     <tr>
      <td>' .
                    $fname .
                    '</td>
      <td>' .
                    (count(scandir($name)) - 2) .
                    '</td>
      <td>' .
                    get_folder_size($name) .
                    '</td>
      <td><button type="button" name="delete" data-name="' .
                    $name .
                    '" class="delete btn btn-danger btn-xs">Delete</button></td>
      <td><button type="button" name="view_files" data-name="' .
                    $name .
                    '" class="view_files btn btn-default btn-xs">View Files</button></td>
     </tr>';
            }
        } else {
            $output .= '
    <tr>
     <td colspan="6">No folder found</td>
    </tr>
   ';
        }
        $output .= "</table>";
        echo $output;
    }

    if ($_POST["action"] == "create") {
        if (!file_exists("../Locations/" . $_POST["folder_name"])) {
            setcookie(
                "location",
                $_POST["folder_name"],
                time() + 86400 * 30,
                "/"
            );
            echo "Files will be uploaded to " . $_POST["folder_name"];
        } else {
            setcookie(
                "location",
                $_POST["folder_name"],
                time() + 86400 * 30,
                "/"
            );
            echo "Folder Already Created. Files will be uploaded to " .
                $_POST["folder_name"];
        }
    }

    if ($_POST["action"] == "delete") {
        $files = scandir($_POST["folder_name"]);
        foreach ($files as $file) {
            if ($file === "." or $file === "..") {
                continue;
            } else {
                unlink($_POST["folder_name"] . "/" . $file);
            }
        }
        if (rmdir($_POST["folder_name"])) {
            echo "Folder Deleted";
        }
    }

    if ($_POST["action"] == "fetch_files") {
        $file_data = scandir($_POST["folder_name"]);
        $output = '
  <table class="table table-bordered table-striped">
   <tr>
    <th>File</th>
    <th>File Name</th>
    <th>Action(s)</th>
   </tr>
  ';

        foreach ($file_data as $file) {
            if ($file === "." or $file === "..") {
                continue;
            } else {
                $path = $_POST["folder_name"] . "/" . $file;
                $output .=
                    '
    <tr>
     <td><img src="' .
                    $path .
                    '" class="img-thumbnail" height="50" width="50" /></td>
     <td contenteditable="true" data-folder_name="' .
                    $_POST["folder_name"] .
                    '"  data-file_name = "' .
                    $file .
                    '" class="change_file_name">' .
                    $file .
                    '</td>
     <td><button name="remove_file" class="remove_file btn btn-danger btn-xs" id="' .
                    $path .
                    '">Remove</button></td>
    </tr>
    ';
            }
        }
        $output .= "</table>";
        echo $output;
    }

    if ($_POST["action"] == "remove_file") {
        if (file_exists($_POST["path"])) {
            unlink($_POST["path"]);
            echo "File has been deleted";
        }
    }
}
?>