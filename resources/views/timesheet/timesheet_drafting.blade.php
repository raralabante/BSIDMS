@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
<style>
  .job-details span{
    
    color:#ece7e7;
    font-size: 16px;
  }
  .bold{
    font-weight:bold;
  }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
      <body class="" >
        
        <div class="container-fluid ">
          <div class="row job-details border rounded-2 btn-dark-green">
            <div class="col-md-6 ">
              <div class="border-bottom m-3"><span class="bold">ID: </span><span id="id">{{$drafting_masters->id}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Job Number: </span><span>{{$drafting_masters->job_number}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Customer Name: </span><span>{{$drafting_masters->customer_name}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Client Name: </span><span>{{$drafting_masters->client_name}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Address: </span><span>{{$drafting_masters->address}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Type: </span><span>{{$drafting_masters->type}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Six Stars: </span><span id="six_stars">{{$drafting_masters->six_stars}}</span></div>
            </div>
            <div class="col-md-6">
              <div class="border-bottom m-3"><span class="bold">Brand: </span><span>{{$drafting_masters->brand}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Job Type: </span><span>{{$drafting_masters->type}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Category: </span><span>{{$drafting_masters->category}}</span></div>
              <div class="border-bottom m-3"><span class="bold">ETA: </span><span id="eta">{{$drafting_masters->ETA}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Floor Area: </span><span>{{$drafting_masters->floor_area}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Prospect: </span><span>{{$drafting_masters->prospect}}</span></div>
              <div class="border-bottom m-3"><span class="bold">Created At: </span><span id="created_at">{{$drafting_masters->created_at}}</span></div>
            </div>
          </div>
          <hr>
        
       
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
        <div class="border border-1 rounded-3 bg-light p-3">
          <table id="timesheets_tbl" class="table table-bordered row-border order-column stripe hover" width="100%">
          </table>
        </div>
        </div>
      </body>

<script>
    $(document).ready( function () {

  
      if($("#six_stars").text() == "1"){
        $("#six_stars").text("Yes");
      }
      else{
        $("#six_stars").text("No");
      }

      $("#eta").text(moment($("#eta").text()).format('MMMM DD, YYYY'));
      $("#created_at").text(moment($("#created_at").text()).format('MMMM DD, YYYY h:mm:ss a'));


      const successToast = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(successToast);
      const warningToast = document.getElementById('warningToast')
      const toastWarning = new bootstrap.Toast(warningToast);
      var id = $("#id").text();
      var timesheets_tbl = $('#timesheets_tbl').DataTable({
          ajax: "{{route('timesheets.fetchDrafting','')}}"+"/"+id,
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


