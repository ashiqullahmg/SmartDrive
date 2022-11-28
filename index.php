<htmL>

<head>
   <!--View Starts Here-->
   <link rel="stylesheet" href="styles.css" />
   <title>Smart Drive</title>
   <!--Google API-->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
   <script src="https://maps.google.com/maps/api/js?key=AIzaSyBjku55AW8rs4Nr_Re4UUU4YAeEmnef-rA"></script>
   <!--Boostrap-->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <!--Upload JS-->
   <script src="upload.js"></script>
   <!--Main JS-->
   <script src="main.js"></script>
</head>
</head>

<body>
   <!--View Starts Here-->
   <br /><br />
   <div class="container">
      <h2 align="center">Welcome to Smart Drive</a></h2>
      <h5 align="center"> Course Project</h5>
      <h5 align="center"> Course: Internet of Things, Analytics and Security</h5>
      <h4 id="directory" align="center"></a></h4>
      <h5 name="errorMsg" style="color:red" id="errorMsg" class="errorMsg" align="center"></h5>
      <div align="center">
         <button type="button" name="create_folder" id="create_folder" class="btn btn-success">Add a location</button>
      </div>
      <br />
      <!--Drag and Drop Starts Here-->
      <div align="center">
         <div id="drop_file_zone" ondrop="upload_file(event)" ondragover="return false">
            <div id="drag_upload_file">
               <p>Drop file here</p>
               <p>or</p>
               <p><input id="selectBtn" type="button" value="Select File" onclick="file_explorer();" /></p>
               <input type="file" id="selectfile" />
            </div>
         </div>
         <div class="img-content"></div>
         <div class="uploadMsg"></div>
         
      </div>
      <!--Drag and Drop Ends Here-->
      <br />
      <!--Table Starts Here-->
      <div class="table-responsive" id="folder_table">
      </div>
   </div>
   <!--Table Ends Here-->
   <!--Create Folder Starts Here-->
   <div id="folderModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title"><span id="change_title">Add Location</span></h4>
            </div>
            <div class="modal-body">
               <p>Enter custom location.
                  <input type="text" name="folder_name" id="folder_name" class="form-control" />
               </p>
               <br />
               <input type="hidden" name="action" id="action" />
               <input type="hidden" name="old_name" id="old_name" />
               <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create" />
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   <!--Creat Folder Ends Here-->
   <!--Loader Starts Here-->
   <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel" data-backdrop="static"
      data-keyboard="false">
      <div class="modal-dialog modal-sm" role="document">
         <div class="modal-content">
            <div class="modal-body text-center">
               <div class="loader">
                  <div class="modal-body">
                  </div>
               </div>
               <div clas="loader-txt">
                  <p style="font-size: 20px">File is being uploaded.</p>
                  <p>Please wait. This might take a while...</p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--Loader Ends Here-->
   <!--File List Starts Here-->
   <div id="filelistModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">File List</h4>
            </div>
            <div class="modal-body" id="file_list">
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   <!--File List Ends Here-->
   <script>
      var error;
      $(document).ready(function () {

         load_folder_list();

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

         //  Creat Folder Starts Here
         $(document).on('click', '#create_folder', function () {
            $('#action').val("create");
            $('#folder_name').val('');
            $('#folder_button').val('Create');
            $('#folderModal').modal('show');
            $('#old_name').val('');
            $('#change_title').text("Create Folder");
         });

         $(document).on('click', '#folder_button', function () {
            var folder_name = $('#folder_name').val();
            var old_name = $('#old_name').val();
            var action = $('#action').val();
            if (folder_name != '') {
               $.ajax({
                  url: "action.php",
                  method: "POST",
                  data: { folder_name: folder_name, old_name: old_name, action: action },
                  success: function (data) {
                     $('#folderModal').modal('hide');
                     load_folder_list();
                     alert(data);
                  }
               });
            }
            else {
               alert("Enter Folder Name");
            }
         });

         //  Creat Folder Ends Here


         // Folder Delete Starts Here
         $(document).on("click", ".delete", function () {
            var folder_name = $(this).data("name");
            const two = folder_name.split("/");
            var action = "delete";
            if (confirm("Are you sure you want to remove " + two[1] + "?")) {
               $.ajax({
                  url: "action.php",
                  method: "POST",
                  data: { folder_name: folder_name, action: action },
                  success: function (data) {
                     load_folder_list();
                     alert(data);
                  }
               });
            }
         });
         // Folder Delete Ends Here
      });

      // Call On Loading Page Starts Here
      $(document).ready(function () {
         document.getElementById('create_folder').style.visibility = 'hidden';
         getLocation();
         setCookie("location", "", 0);
         errorMsg.innerHTML = "Address could not be found, please add a custom location!";
         errorMsg.style.visibility = 'hidden';

      });

      // Call On Loading Page Ends Here

      var x = document.getElementById("directory");
      var b = document.getElementById("create_folder");
      var errorMsg = document.getElementById("errorMsg");

   </script>
</body>

</htmL>