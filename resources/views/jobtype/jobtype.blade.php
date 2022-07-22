@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="content" class="p-4 p-md-5 pt-5">
    <div class="container">
    <div class="row justify-content-center">
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
      
      <div class="row">
        <div class="col-md-4">
          <form method="POST" action="{{ route('job_type.insert') }}" onsubmit="add_job_type_btn.disabled = true; return true;">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Job Type" aria-label="Job Type" aria-describedby="add_job_type_btn" name="job_type_name" required autocomplete="off">
                <button class="btn btn-success" type="submit" id="add_job_type_btn"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;ADD JOB TYPE</button>
            </div>
          </form>
        </div>
      </div>
      
        <table id="job_types_tbl" class="table table-bordered row-border order-column stripe hover" width="100%">
           
        </table>
    {{-- </div>
      Toggle column: <a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1">Position</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
    </div> --}}
</div>




<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready( function () {
      $("#filesSubmenu .job_type").addClass("sidebar_active");
      const toastLiveExample = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(toastLiveExample);

      var job_types_tbl = $('#job_types_tbl').DataTable({
          ajax: "{{ route('job_type.list') }}",
          columns: [
              {data: 'id', title: 'ID', className: "dt-right" },
              {data: 'name', title: 'Name'},
              {data: 'delete_job_type', title: 'Action'},
          ],
          order: [[0, 'desc']],
      });

      $('#job_types_tbl').on('click', '.delete-job_type-btn', function (){
        var job_type_id = $(this).data("id");
        var job_type_name = $(this).data("job_type_name");
        var token = $("meta[name='csrf-token']").attr("content");
        $.confirm({
          icon: 'fa fa-warning',
          draggable: false,
          closeIcon: true,
          backgroundDismiss: true,
          type: 'red',
          title: 'DELETE ' + job_type_name + "?",
          buttons: {
              text: 'DELETE',
              btnClass: 'btn-red',
              confirm: function(){
                $.ajax({
                  url:  location.pathname +  '/list/deletejobtype/' + job_type_id,
                  type:"GET",
                  success:function(response){
                    job_types_tbl.ajax.reload();
                    $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> " + job_type_name + " has been deleted.");
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
        job_types_tbl.ajax.reload();

      });

    });
</script>
@endsection


