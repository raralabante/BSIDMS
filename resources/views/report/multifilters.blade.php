@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
<style>
    .ui-widget, #drafters{
      z-index: 10000;
    }
  .ui-menu{
    z-index: 10000!important;
  }
  </style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content">
    <div class="container-fluid">
        <div class="row border border-2 rounded-3 btn-dark-green filters"  >
                <center>
                    <div class="input-group m-2" style="width:30%">
                        <span class="input-group-text btn-dark-green text-white">FROM</span>
                        <input id="from" type="date" class="form-control" required>
                        <span class="input-group-text btn-dark-green text-white">TO</span>
                        <input id="to" type="date" class="form-control" required>
                        <div class="invalid-feedback">
                            <i class="fa-solid fa-triangle-exclamation"></i> Date range cannot be empty.
                          </div>
                    </div>
                
                <div class="input-group p-2" style="width: 80%">
                    <span class="input-group-text">Department</span>
                    <select class="form-select form-select-sm input-group-sm" id="department" >
                      <option value=""  >Select Department</option>
                        <option value="Drafting" selected>Drafting</option>
                        <option value="Scheduling">Scheduling</option>
                    </select>
                    <span class="input-group-text">TEAM</span>
                    <select class="form-select form-select-sm input-group-sm" id="team" >
                        <option value=""  selected>Select Team</option>
                        @foreach($teams as $team)
                        <option value="{{$team->code_value}}">{{$team->code_value}}</option>
                        @endforeach
                    </select>
                    <span class="input-group-text">Status</span>
                    <select class="form-select form-select-sm " id="status" >
                        <option value=""  selected>Select Status</option>
                        <option value="Unassigned">Unassigned</option>
                        <option value="Assigned">Assigned</option>
                        <option value="Ready For Check">Ready For Check</option>
                        <option value="Ready For Submit">Ready To Submit</option>
                        <option value="Ready For Six Stars">Ready For Six Stars</option>
                        <option value="In Six Stars">In Six Stars</option>
                        <option value="On-going">On-going</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                    <span class="input-group-text">Customer</span>
                    <select class="form-select form-select-sm " id="customer" >
                        <option value=""  selected>Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{$customer->name}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
             
                
                    <div class="input-group m-2" style="width: 80%">
                        <span class="input-group-text">Type</span>
                        <select class="form-select form-select-sm " id="type" >
                            <option value=""  selected>Select Type</option>
                            @foreach($types as $type)
                            <option value="{{$type->name}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text">Job Type</span>
                        <select class="form-select form-select-sm " id="job_type" >
                            <option value=""  selected>Select Job Type</option>
                            @foreach($job_types as $job_type)
                            <option value="{{$job_type->name}}">{{$job_type->name}}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text">Brand</span>
                        <select class="form-select form-select-sm " id="brand" >
                            <option value=""  selected>Select Brand</option>
                            @foreach($brands as $brand)
                            <option value="{{$brand->name}}">{{$brand->name}}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text">Category</span>
                        <select class="form-select form-select-sm " id="category" >
                            <option value=""  selected>Select Categories</option>
                            @foreach($categories as $category)
                            <option value="{{$category->name}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                     </div>
                    <div class=" m-2" >
                       <button id="generate_btn" class="btn-dark-green  btn text-white"><i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;GENERATE</button>
                       <button id="clear_filter_btn" class="btn-dark-green  btn text-white"><i class="fa-solid fa-filter-circle-xmark"></i>&nbsp;&nbsp;CLEAR FILTERS</button>
                    </div>
                  </center>
        </div>
        <br>
        
          
          
        <div class="popover__wrapper" >
          <div class="popover__content_drafting">
            <div class=" p-2 toggle-column">
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="0" id="column0" checked>
                  <label class="form-check-label" for="column0" >ID</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="1" id="column1" checked>
                  <label class="form-check-label" for="column1" >Customer </label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="2" id="column2" checked>
                  <label class="form-check-label" for="column2">Client Job Number</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="3" id="column3" checked>
                  <label class="form-check-label" for="column3">Client Name</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="4" id="column4" checked>
                  <label class="form-check-label" for="column4" >Address</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="5" id="column5" checked>
                  <label class="form-check-label" for="column5" >Type</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="6" id="column6" checked>
                  <label class="form-check-label" for="column6" >ETA</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="7" id="column7" checked>
                  <label class="form-check-label" for="column7">Brand</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="8" id="column8" checked>
                  <label class="form-check-label" for="column8">Job Type</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="9" id="column9" checked>
                  <label class="form-check-label" for="column9">Category</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="10" id="column10" checked>
                  <label class="form-check-label" for="column10">Floor Area</label>
                </div>
              </div>
              <div class="form-check form-check-inline p-0">
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-vis" type="checkbox" role="switch" data-column="11" id="column11" checked>
                  <label class="form-check-label" for="column11">Prospect</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="border border-1 rounded-3 bg-light">
          <table id="multifilters_tbl" class="table table-bordered row-border order-column stripe hover" data-mode="columntoggle"width="100%">
          </table>
        </div>
    </div>

</div>
@endsection
</div>
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script src="{{ asset('chart-js/chart-js.js') }}"></script>
<script>
    $(document).ready(function(){
      
        $(".multifilters").addClass("sidebar_active");
        $("#reportsSubmenu").click();
        const toastLiveExample = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastLiveExample);
        const warningToast = document.getElementById('warningToast');
        const toastWarning = new bootstrap.Toast(warningToast);
        
        $("#from").val(moment().startOf('month').format('YYYY-MM-DD'));
        $("#to").val(moment().endOf('month').format('YYYY-MM-DD'));

        $("#generate_btn").click(function(){
 
            if($("#from").val() == "" || $("#to").val() == ""){
                $(".invalid-feedback").show();
                $("#warningToast .toast-body").html("<i class='fa-solid fa-ban'></i> Date range cannot be empty.");
                    toastWarning.show();
                    $( "#from" ).focus();
            }
            else{
                if($("#department").val() == "Drafting"){
                    if ($.fn.DataTable.isDataTable('#multifilters_tbl')) {
                        $('#multifilters_tbl').dataTable().fnClearTable();
                        $('#multifilters_tbl').dataTable().fnDestroy();
                    }
                    var multifilters_tbl = $('#multifilters_tbl').DataTable({
                        scrollX: true,
                        scrollY: true,
                        
                        ajax:{
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type:'GET',
                                url:  "{{route('report.multifiltersGenerate')}}",
                                data:{
                                from:$("#from").val()
                                ,to:$("#to").val()
                                ,department:$("#department").val()
                                ,team:$("#team").val()
                                ,status:$("#status").val()
                                ,customer:$("#customer").val()
                                ,type:$("#type").val()
                                ,job_type:$("#job_type").val()
                                ,brand:$("#brand").val()
                                ,category:$("#category").val()
                            },
                        },
                        dom: 'Bfrtip',
                        // colReorder: true,
                        stateSave: true,
                        //   processing: true,
                        // serverSide: true,
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5',
                            {
                                className:    'column-toggle',
                                text:     '<button class="btn btn-light popover__title_drafting"><i class=" fa-solid fa-ellipsis-vertical"></i></button>',
                            },
                        ],
                        columns: [
                            {data: 'id', title: 'ID', className: "dt-right" },
                            {data: 'customer_name', title: 'Customer'},
                            {data: 'job_number', title: 'Client Job Number', className: "dt-center"},
                            {data: 'client_name', title: 'Client Name'},
                            {data: 'address', title: 'Address'},
                            {data: 'type', title: 'Type'},
                            {data: 'ETA', title: 'ETA', className: "dt-right", 
                            render: function (data, type) {
                                    return moment(data).format('MMM DD, YYYY');
                                }},
                            {data: 'brand', title: 'Brand'},
                            {data: 'job_type', title: 'Job Type'},
                            {data: 'category', title: 'Category'},
                            {data: 'floor_area', title: 'Floor Area', className: "dt-right"},
                            {data: 'prospect', title: 'Prospect'},
                            {data: 'six_stars', title: 'Six Stars'},
                            {data: 'drafters', title: 'Drafters', className: "dt-center"},
                            {data: 'drafting_hours', title: 'Drafting Hours',className: "dt-center",
                            render: function (data, type) {
                                const duration = moment.duration(data, 'seconds').format("HH:mm:ss", { trim: false });
                                return duration;
                                },},
                            {data: 'checker', title: 'Checker', className: "dt-center"},
                            {data: 'checking_hours', title: 'Checking Hours',className: "dt-center",
                            render: function (data, type) {
                                const duration = moment.duration(data, 'seconds').format("HH:mm:ss", { trim: false });
                                return duration;
                                },},
                            {data: 'status', title: 'Status', className: "dt-center",
                            render: function (data, type) {
                            
                                return getStatusColor(data);
                                },},
                            {data: 'total_hours', title: 'Total Hours',className: "dt-center",
                            render: function (data, type) {
                                const duration = moment.duration(data, 'seconds').format("HH:mm:ss", { trim: false });
                                return duration;
                                },},
                            {data: 'created_at', title: 'Created At', className: "dt-right", 
                        render: function (data, type) {
                                return moment(data).format('MMM DD, YYYY');
                            },},
                           
                        ],
                        order: [[0, 'desc']],
                        
                    });
                }
                else if($("#department").val() == "Scheduling"){

                }

                $(document).on('click', 'input.toggle-vis' , function(e) {
                    
                    // Get the column API object
                    var column = multifilters_tbl.column($(this).attr('data-column'));
        
                    // Toggle the visibility
                    column.visible(!column.visible());
        
                    });
        
                    var columns = $('.toggle-vis').length;
                    for(var i = 0; i <= columns; i++){
        
                    if(multifilters_tbl.column( i ).visible() === true){
                        $('input[data-column="'+i+'"]').prop('checked',true);
                    }
                    else{
                        $('input[data-column="'+i+'"]').prop('checked',false);
                    }
                }
            }
        });

        $("#clear_filter_btn").click(function(){
            $.confirm({
                icon: 'fa-solid fa-filter-circle-xmark',
                draggable: false,
                closeIcon: true,
                backgroundDismiss: true,
                type: 'orange',
                title: 'Clear filters?',
                buttons: {
                    text: 'CLEAR',
                    btnClass: 'btn-warning',
                    confirm: function(){
                      $(".filters select, .filters input").val("");
                    },
                    cancel: function () {
                    },
                }
            });
        });

        
        
        $(document).on('click', '.popover__title_drafting' , function() {
                    $(".popover__content_drafting").css("visibility","visible");
                    $(".popover__content_drafting").css("z-index","10");
                    $(".popover__content_drafting").css("opacity","1");
                    $(".popover__content_drafting").css("transform","translate(0, -20px)");
                    $(".popover__content_drafting").css("transition","all 0.5s cubic-bezier(0.75, -0.02, 0.2, 0.97)");
                });

        $(document).on('mouseleave', '.popover__content_drafting' , function() {

            if($(".popover__content_drafting").is(":visible")){
              $(".popover__content_drafting").css("visibility","hidden");
            }
          }); 

        
          

         
        function getStatusColor(status){
              color_success = ['bg-secondary','bg-warning text-dark','bg-primary','bg-info','bg-light text-dark','bg-dark', 'bg-success'];
                if(status == "Unassigned"){
                  return '<span class="badge '+ color_success[0] +'">'+status+'</span>';
                }
                else if(status == "Assigned"){
                  return '<span class="badge '+ color_success[1] +'">'+status+'</span>';
                }
                else if(status == "Ready For Check"){
                  return '<span class="badge '+ color_success[2] +'">'+status+'</span>';
                }
                else if(status == "Ready To Submit"){
                  return '<span class="badge '+ color_success[3] +'">'+status+'</span>';
                }
                else if(status == "Ready For Six Stars"){
                  return '<span class="badge '+ color_success[4] +'">'+status+'</span>';
                }
                else if(status == "In Six Stars"){
                  return '<span class="badge '+ color_success[5] +'">'+status+'</span>';
                }
                else if(status == "Submitted"){
                  return '<span class="badge '+ color_success[6] +'">'+status+'</span>';
                }
                else if(status == "Cancelled"){
                  return '<span class="badge '+ color_success[0] +'">'+status+'</span>';
                }
            }
    });
</script>


