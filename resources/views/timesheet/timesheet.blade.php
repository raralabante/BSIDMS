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
          <h3>CLIENT JOB# {{$drafting_masters->id}}</h3>
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
          <table id="timesheets_tbl" class="table table-bordered row-border order-column stripe hover" width="100%">
          </table>
        </div>
      </body>

<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready( function () {
      const successToast = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(successToast);
      const warningToast = document.getElementById('warningToast')
      const toastWarning = new bootstrap.Toast(warningToast);
      var id = location.pathname.substring(location.pathname.lastIndexOf('/') + 1);
      var timesheets_tbl = $('#timesheets_tbl').DataTable({
          ajax: "{{route('timesheets.fetch','')}}"+"/"+id,
          dom: 'Bfrtip',
 
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
          columns: [
            
              {data: 'id', title: 'ID', className: "dt-right" },
              {data: 'user_id', title: 'Drafter', className: "dt-center"},
              {data: 'type', title: 'TYPE',  className: "dt-center"},
              {data: 'created_at', title: 'Date', className: "dt-right",
              render: function (data, type) {
                    return moment(data).format('MMM DD, YYYY');
                }},
              {data: 'morning_start', title: 'Morning Start', className: "dt-right"},
              {data: 'morning_stop', title: 'Morning Stop', className: "dt-right"},
              {data: 'afternoon_start', title: 'Afternoon Start', className: "dt-right"},
              {data: 'afternoon_stop', title: 'Afternoon Stop', className: "dt-right"},
              {data: 'hours', title: 'Hours', className: "dt-right",
              render: function (data, type) {
                const duration = moment.duration(data, 'seconds').format("HH:mm:ss", { trim: false });
                return duration;
                },},
             
                
          ],
          order: [[0, 'asc']],
          
      
          
      });

      


    });
</script>
@endsection


