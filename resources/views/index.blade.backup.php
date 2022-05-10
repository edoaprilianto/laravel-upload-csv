<!DOCTYPE html>
<html>
<head>
      <title>Upload</title>

      <!-- Meta -->
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta charset="utf-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">


      <style>
        .blink {
          animation: blink-animation 1s steps(5, start) infinite;
          -webkit-animation: blink-animation 1s steps(5, start) infinite;
        }
        @keyframes blink-animation {
          to {
            visibility: hidden;
          }
        }
        @-webkit-keyframes blink-animation {
          to {
            visibility: hidden;
          }
        }
      </style>


      <!-- CSS -->
     <link rel="stylesheet" type="text/css" href="{{asset('dropzone/dist/dropzone.css')}}">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!--   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
 


      <!-- JS -->
      <script src="{{asset('dropzone/dist/dropzone-min.js')}}" type="text/javascript"></script>
      <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->




</head>
<body>

         
<div class="container">
  <div class="row mt-2">
    <div class="col-md-12">
      <form action="{{route('uploadFile')}}" enctype="multipart/form-data" class="dropzone" id="image-upload">
      </form>
      <button class="btn btn-primary mt-2" id="uploadFile">Upload File</button>
    </div>
  </div>
  <table class="table table-bordered mt-2">
      <thead>
            <tr>
                <th>Time</th>
                <th>File Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
   </table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

      <script src='./js/app.js'></script>
       <script>


        var table = $('.table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        paging: false,
        bInfo:false,
        ajax: "{{ route('getdata') }}",
        columns: [
            {data: 'created_at', name: 'created_at'},
            {data: 'file_name', name: 'file_name'},
            {data: 'status', name: 'status'},
            ]
        });

         Dropzone.autoDiscover = false;
          var myDropzone = new Dropzone(".dropzone", { 
             autoProcessQueue: false,
             // maxFilesize: 1,
             acceptedFiles: ".csv",
             addRemoveLinks: true,
             sending: function(file, xhr, formData) {
                  formData.append("_token", "{{ csrf_token() }}");
             },
          });
          myDropzone.on("complete", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                  table.ajax.reload();
              }
              myDropzone.removeFile(file); 
          });

          // action button upload file
          $('#uploadFile').click(function(){
                  myDropzone.processQueue();
            });


          // Larevel Echo real-time data
          // Pusher.logToConsole = true;
          window.Echo.channel('UpTable')
            .listen('ProgressEvent', (e) => {
              console.log(e);
              // table.ajax.reload();
            })
       </script>

</body>
</html>