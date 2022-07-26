@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .ui-widget, #drafters{
    z-index: 10000;
  }
</style>

      <body>
        <div class="container-fluid p-5">
          <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="job_number" class="form-control" placeholder="JOB ORDER #" aria-label="Recipient's username" aria-describedby="add_six_stars" autocomplete="off" autofocus required>
                    <button type="button" id="add_six_stars" class="btn btn-success"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;ADD SIX STARS</button>
                  </div>
            </div>
            <div class="col-md-12">
              <br><br>
              <table id="sixstars_tbl" class="table table-bordered row-border order-column stripe hover" width="100%">
              </table>
            </div>
          </div>
          @if(session()->has('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check"></i>&nbsp;
            <div>
                {{ session()->get('success') }}
            </div>
          </div>
  
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle"></i>&nbsp;
            <div>
                {{ session()->get('error') }}
            </div>
          </div>
        @endif
  
        @if ($errors->any())
        <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div>
        @endif

        </div>

        
      </body>

<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready( function () {
      $("#draftingSubmenu .six_stars").addClass("sidebar_active");
      const successToast = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(successToast);
      const warningToast = document.getElementById('warningToast')
      const toastWarning = new bootstrap.Toast(warningToast);

    

      var sixstars_tbl = $('#sixstars_tbl').DataTable({
          ajax: "{{ route('sixstars.list') }}",
          dom: 'Bfrtip',
        //   processing: true,
        // serverSide: true,
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
          columns: [
            
              {data: 'id', title: 'ID', className: "dt-right" },
              {data: 'customer_name', title: 'Customer'},
              {data: 'job_number', title: 'Client Job Number'},
              {data: 'six_stars_submitted_at', title: 'Submitted At',className: "dt-right",
              render: function (data, type) {
                return moment(data).format('MMM DD, YYYY');
                },},
                {data: 'ammend', title: 'Ammend', className: "dt-center" },
                {data: 'submit', title: 'Submit', className: "dt-center" },
                
          ],
          order: [[0, 'desc']],
      });

      $.ajax({
              url:  location.pathname + '/getforsixstars',
              type:"GET",
              success:function(data) {
                $( "#job_number" ).autocomplete({
                    source: data,
                    })
                 }
          });

      $('#sixstars_tbl').on('click', '.submit', function (){
        var draft_id = $(this).data("id");
        var job_number = $(this).data("job_number");

        $.confirm({
          icon: 'fa-solid fa-paper-plane',
          draggable: false,
          closeIcon: true,
          backgroundDismiss: true,
          type: 'green',
          title: 'SUBMIT CLIENT JOB # ' + job_number + "?",
          buttons: {
              text: 'SUBMIT',
              btnClass: 'btn-green',
              confirm: function(){
                $.ajax({
                  
                  url:  "{{route('sixstars.submit_job','')}}"+"/"+draft_id,
                type:"GET",
                success:function(response){
                  sixstars_tbl.ajax.reload();
                  $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> Client Job Number# " + job_number + " has been submitted.");
                    toast.show();
                }
              });
              },
              cancel: function () {
              },
          }
      });
      });

        $("#add_six_stars").click(function(){
            var job_number = $("#job_number").val();
            $.ajax({
              

              url:  "{{route('sixstars.add_six_stars','')}}"+"/"+job_number,
                type:"GET",
                success:function(response){
                 if(response == 1){
                    sixstars_tbl.ajax.reload();
                    $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> Client Job Number# " + job_number + " has been added to Six Stars.");
                    toast.show();
                 }
                 else{
                    $("#warningToast .toast-body").html("<i class='fa-solid fa-ban'></i> Client Job Number# " + job_number + " does not exist.");
                    toastWarning.show();
                 }
                 $("#job_number").val("");
                }
              });
        });

        $('#sixstars_tbl').on('click', '.ammend', function (){
        var draft_id = $(this).data("id");
        var job_number = $(this).data("job_number");
        
        $.confirm({
          icon: 'fa-solid fa-repeat',
          draggable: false,
          closeIcon: true,
          backgroundDismiss: true,
          type: 'red',
          title: 'AMMEND CLIENT JOB# ' + job_number + "?",
          buttons: {
              text: 'SUBMIT',
              btnClass: 'btn-red',
              confirm: function(){
                $.ajax({
                  
                  url:  "{{route('sixstars.ammend_job','')}}"+"/"+draft_id,
                type:"GET",
                success:function(response){
                    sixstars_tbl.ajax.reload();
                  $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> Client Job Number# " + job_number + " has been ammended.");
                    toast.show();
                }
              });
              },
              cancel: function () {
              },
          }
      });
      });
      
      Pusher.logToConsole = true;

          var pusher = new Pusher('89eec464cd4d14a2238d', {
            cluster: 'ap1'
          });

          var channel = pusher.subscribe('my-channel');
          channel.bind('my-event', function(data) {
            sixstars_tbl.ajax.reload();

          });

    });
</script>
@endsection


