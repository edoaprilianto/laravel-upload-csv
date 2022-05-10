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
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


      <!-- JS -->
      <script src="{{asset('dropzone/dist/dropzone-min.js')}}" type="text/javascript"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>




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
          <th scope="col">Time</th>
          <th scope="col">File Name</th>
          <th scope="col">Status</th>
          </tr>
      </thead>     
      <tbody>
        @foreach($row as $rows)
        @php
        $end_time = date("H:i:s", time());
        $time1 = new DateTime($rows->created_at);
        $time2 = new DateTime($end_time);
        $interval = $time1->diff($time2);
        $min = $interval->format('%i min');


        @endphp
         <tr>
            <td>{{$rows->created_at}}  <br> ({{$min}} minutes ago)</td>
            <td>{{$rows->file_name}}</td>
            <td> 
            @if($rows->status == 'pending')
              <span class="badge badge-secondary"> Pending </span>
            @elseif($rows->status == 'completed')
              <span class="badge badge-success"> Completed </span>
            @elseif($rows->status == 'failed') 
              <span class="badge badge-danger"> Failed </span>
            @else
              <span class="badge badge-primary blink"> Processing </span>
            @endif
            </td>
         </tr>
        @endforeach
      </tbody>
   </table>

       <!-- Script -->
        <script src='./js/app.js'></script>
       <script>
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
                    location.reload();
              }
              myDropzone.removeFile(file); 
          });

          // action button upload file
          $('#uploadFile').click(function(){
                  myDropzone.processQueue();
            });


          // Larevel Echo real-time data
          Pusher.logToConsole = true;
          window.Echo.channel('UpTable')
            .listen('ProgressEvent', (e) => {
              location.reload();
              console.log(e);
            })
       </script>

</body>
</html>