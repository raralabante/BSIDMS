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

      <body class="">
        <div class="container-fluid p-5">
          <h1>MY DRAFTS CHECK</h1>
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
          <table id="mydraftscheck_tbl" class="table table-bordered row-border order-column stripe hover" width="100%">
          </table>
        </div>
      </body>

<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready( function () {
      $("#draftingSubmenu .my_drafts_check").addClass("sidebar_active");
      const successToast = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(successToast);
      const warningToast = document.getElementById('warningToast')
      const toastWarning = new bootstrap.Toast(warningToast);

      var mydraftscheck_tbl = $('#mydraftscheck_tbl').DataTable({
          ajax: "{{ route('my_drafts_check.list') }}",
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
              {data: 'active', title: 'Active', className: "dt-center"},
              {data: 'customer_name', title: 'Customer'},
              {data: 'job_number', title: 'Client Job Number'},
              {data: 'client_name', title: 'Client Name'},
              {data: 'address', title: 'Address'},
              {data: 'type', title: 'Type'},
              {data: 'ETA', title: 'ETA',
              render: function (data, type) {
                    return moment(data).format('MMM DD, YYYY');
                }},
              {data: 'brand', title: 'Brand'},
              {data: 'job_type', title: 'Job Type'},
              {data: 'category', title: 'Category'},
              {data: 'floor_area', title: 'Floor Area', className: "dt-right"},
              {data: 'prospect', title: 'Prospect'},
              {data: 'six_stars', title: 'Six Stars'},
              {data: 'checking_hours', title: 'Total Hours',
              render: function (data, type) {
                const duration = moment.duration(data, 'seconds').format("HH:mm:ss", { trim: false });
                return duration;
                },},
              {data: 'created_at', title: 'Created At',
              render: function (data, type) {
                    return moment(data).format('MMM DD, YYYY');
                },},
                {data: 'reject', title: 'Reject', className: "dt-center" },
                {data: 'submit', title: 'Submit', className: "dt-center" },
                
          ],
          order: [[0, 'desc']],
          
      
          
      });

      $('#mydraftscheck_tbl').on('click', '.active', function (){
        var draft_id = $(this).data("id");
        var job_number = $(this).data("job_number");
        
          $.ajax({
                url:  location.pathname +  '/list/setstatus/' + draft_id,
                type:"GET",
                success:function(response){
                  if(response == 0){
                    $("#warningToast .toast-body").html("<i class='fa-solid fa-ban'></i> Client Job Number# " + job_number + " has been deactivated.");
                    toastWarning.show();
                  }
                  else if(response == 1){
                    $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> Client Job Number# " + job_number + " is now active.");
                    toast.show();
                  }
                  else if(response == 3){
                    $("#warningToast .toast-body").html("<i class='fa-solid fa-ban'></i> You have an active job on drafting.");
                    toastWarning.show();
                  }
                  
                  
                  mydraftscheck_tbl.ajax.reload();
                }
            });
      });

      $('#mydraftscheck_tbl').on('click', '.submit', function (){
        var draft_id = $(this).data("id");
        var job_number = $(this).data("job_number");
        var six_stars = $(this).data("six_stars");
   
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
                url:  location.pathname +  '/list/setjobstatus/' + draft_id + '/' + six_stars,
                type:"GET",
                success:function(response){
                  mydraftscheck_tbl.ajax.reload();
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

      $('#mydraftscheck_tbl').on('click', '.reject', function (){
        var draft_id = $(this).data("id");
        var job_number = $(this).data("job_number");
        
        $.confirm({
          icon: 'fa-solid fa-ban',
          draggable: false,
          closeIcon: true,
          backgroundDismiss: true,
          type: 'red',
          title: 'REJECT CLIENT JOB# ' + job_number + "?",
          buttons: {
              text: 'SUBMIT',
              btnClass: 'btn-red',
              confirm: function(){
                $.ajax({
                url:  location.pathname +  '/list/reject/' + draft_id,
                type:"GET",
                success:function(response){
                  mydraftscheck_tbl.ajax.reload();
                  $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> Client Job Number# " + job_number + " has been rejected.");
                    toast.show();
                }
              });
              },
              cancel: function () {
              },
          }
      });
      });


    });
</script>
@endsection


