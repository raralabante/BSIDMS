@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content">
    <div class="container-fluid p-5 ">
        <div class="row justify-content-center text-center ">
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-header btn-dark-green text-white">
                        <div class="float-start ">
                            {{-- <i class="fa-solid fa-satellite-dish text-danger fa-xl mt-4 pulsing p-1"></i><span>&nbsp;&nbsp;Live updating</span>  --}}
                        </div>
                        
                        <center>
                            <div class="input-group p-2" style="width:30%" style="text-align:center!important">
                                <span class="input-group-text">FROM</span>
                                <input id="from" type="date" class="form-control">
                                <span class="input-group-text">TO</span>
                                <input id="to" type="date" class="form-control">
                              </div>
                        </center>
                       
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div id="average_drafting" class="col-md-6">
                                <h5 class="card-title">AVERAGE DRAFTING HOURS</h5>
                                <h3 class="card-text"></h3>
                            </div>
                            <div id="average_checking" class="col-md-6">
                                <h5 class="card-title">AVERAGE CHECKING HOURS</h5>
                      <h3 class="card-text"></h3>
                            </div>
                        </div>
                      
                    </div>
                    <div class="card-footer text-muted">
                      Formula: (Total of Hours / Days)
                    </div>
                </div>
            </div>

        </div>
        <br>
        <div class="row">
                <div class="col-md-7 p-3 bg-body rounded-2 shadow-sm">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item active" aria-current="true"><i class="fas fa-toggle-on"></i>&nbsp;&nbsp;Active Users <span id="active_users_count"></span></li>
                                    <div id="active_users">
                                        
                                    </div>
                                    
                                </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-secondary text-white" aria-current="true"><i class="fa-solid fa-toggle-off"></i>&nbsp;&nbsp;Inactive Users <span id="inactive_users_count"></span></li>
                                <div id="inactive_users">
                                        
                                </div>
                              
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5  border border-1 p-3 bg-light bg-gradient border-opacity-10 rounded-3">
                    
                    <ul class="list-group">
                        <li class="list-group-item border-0" aria-current="true"><h3>Feeds</h3></li>
                        <div id="feeds">
                            
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 p-3">
                                <button class="btn-circle bg-secondary">
                                    &nbsp;<i class="fa-solid fa-user-pen fa-xl text-white" ></i>
                                </button>
                                <div class="ms-2 me-auto ">
                                  <p class="m-1 fw-bold">Unassiged Jobs</p>
                                </div>
                                <span id="unassigned_count" class="text-muted">()</span>
                              </li>
                              
                              <li class="list-group-item d-flex justify-content-between align-items-center border-0 p-3">
                                <button class="btn-circle bg-warning">
                                    &nbsp;<i class="fa-solid fa-r fa-xl text-dark"></i>
                                </button>
                                <div class="ms-2 me-auto ">
                                  <p class="m-1 fw-bold">Ready To Submit</p>
                                </div>
                                <span id="ready_to_submit_count" class="text-muted">()</span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center border-0 p-3">
                                <button class="btn-circle bg-success">
                                    <i class="fa-solid fa-paper-plane fa-xl text-white"></i>
                                </button>
                                <div class="ms-2 me-auto ">
                                  <p class="m-1 fw-bold">Submitted</p>
                                </div>
                                <span id="submitted_count" class="text-muted">()</span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center border-0 p-3">
                                <button class="btn-circle bg-primary">
                                    <i class="fa-solid fa-briefcase fa-xl text-white"></i>
                                </button>
                                <div class="ms-2 me-auto ">
                                    <div class="fw-bold m-1">Latest Job Added</div>
                                  <span id="latest_job" class="m-1" >(Customer Name)</span>
                                </div>
                                <span id="latest_job_date" class="text-muted">(Date)</span>
                              </li>
                        </div>
                       
       
                        
                    </ul>
                </div>
        </div>

</div>
@endsection
</div>
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script src="{{ asset('chart-js/chart-js.js') }}"></script>
<script>
    $(document).ready(function(){
        $(".dashboard").addClass("sidebar_active");
    
        const toastLiveExample = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastLiveExample);
        const warningToast = document.getElementById('warningToast');
        const toastWarning = new bootstrap.Toast(warningToast);

      

        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
               type:'POST',
               url:  "{{route('dashboard.getActiveUsers')}}",
               success:function(data) {
                $("#active_users_count").text("(" + data.length + ")");
                $("#active_users").empty();
                $.each(data, function(i, item) {
                    if($("#user_department").text() == "DFT"){
                        var url = '{{ route("timesheets.drafting", ":id") }}';
                        url = url.replace(':id', data[i].drafting_masters_id);
                        $("#active_users").append("<li class='list-group-item d-flex justify-content-between align-items-center'>"+data[i].full_name+" <span>"+data[i].type+"</span><a href='"+url+"' class='text-primary'><u>Job No. "+data[i].job_number+"</u></a></li>");
                    }
                    else{
                        var url = '{{ route("timesheets.drafting", ":id") }}';
                        url = url.replace(':id', data[i].drafting_masters_id);
                        $("#active_users").append("<li class='list-group-item d-flex justify-content-between align-items-center'>"+data[i].full_name+" <a href='"+url+"' class='text-primary'><u>VIEW JOB ID No. "+data[i].drafting_masters_id+"</u></a></li>");
                    }
                    
                });
                 }
            });

            $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
               type:'POST',
               url:  "{{route('dashboard.getInactiveUsers')}}",
               success:function(data) {
                $("#inactive_users_count").text("(" + data.length + ")");
                $("#inactive_users").empty();
                
                $.each(data, function(i, item) {
                    
                    $("#inactive_users").append("<li class='list-group-item d-flex justify-content-between align-items-center text-muted'>"+data[i]+"</li>");
                });
                 }
            });

            $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
               type:'POST',
               url:  "{{route('dashboard.getFeeds')}}",
               success:function(data) {
                // $("#unassigned_count").text(data[0]);
                // $("#ready_to_submit_count").text(data[1]);
                // $("#submitted_count").text(data[2]);
                // $("#latest_job").text(data[3]);
                // $("#latest_job_date").text(moment(data[4]).format('MMM DD, YYYY hh:mm A'));

                 }
            });


            $("#from,#to").change(function(){
                $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:  "{{route('dashboard.getAverageDraftingHours')}}",
                    data:{from:$("#from").val(),to:$("#to").val(),test:"test"},
                    success:function(data) {
                        //  alert(data);

                        }
                });

            });

           
   
      
    });
</script>


