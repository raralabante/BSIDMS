@extends('layouts.app')
@extends('layouts.sidebar')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .ui-widget, #drafters{
    z-index: 10000;
  }
.ui-menu{
  z-index: 10000!important;
}

</style>

<body>
  <div class="content">
    <div class="container-fluid p-5 ">
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

     
      <div class="row">
        <div class="col-md-6 input-group mb-3">
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_job_modal"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;ADD JOB</button>
        </div>
      </div>
      
      <div class="popover__wrapper">
        <div class="popover__content">
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
                <label class="form-check-label" for="column1" >Customer</label>
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
      
      
 
      <table id="drafting_master_tbl" class="table table-bordered row-border order-column stripe hover" data-mode="columntoggle"width="100%">
      </table>
      
        <div class="modal fade" id="add_job_modal" tabindex="-1" aria-labelledby="add_job_modal" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header real-cognita-teal">
                <i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;<h5 class="modal-title col-md-10" id="add_job_modal">ADD JOB</h5>

                

                <button type="button" class="btn-close " data-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('drafting_master.insert') }}" onsubmit="draft_insert.disabled = true; return true;">
                @csrf
              <div class="modal-body">

                <div class="input-group mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="six_stars" name="six_stars" value="1">
                    <label class="form-check-label" for="six_stars">Six Stars</label>
                  </div>

                </div>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Customer Name</span>
                  <input id="customer_names" type="text" class="form-control" placeholder="Customer Name" aria-label="Customer Name" aria-describedby="basic-addon1" name="customer_name" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Client Job Number</span>
                  <input id="job_number" type="text" class="form-control" placeholder="Job Number" aria-label="Job Number" aria-describedby="basic-addon1" name="job_number" autocomplete="off" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Client Name</span>
                  <input id="client_name" type="text" class="form-control" placeholder="Client Name" aria-label="Client Name" aria-describedby="basic-addon1" name="client_name" autocomplete="off" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Address</span>
                  <input id="address" type="text" class="form-control" placeholder="Address" aria-label="Address" aria-describedby="basic-addon1" name="address" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Type</span>
                  <input id="types" type="text" class="form-control" placeholder="Type" aria-label="Type" aria-describedby="basic-addon1" name="type" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">ETA</span>
                  <input id="eta" type="date" class="form-control" placeholder="Type" aria-label="Type" aria-describedby="basic-addon1" name="eta" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Brands</span>
                  <input id="brands" type="text" class="form-control" placeholder="Brand" aria-label="Brand" aria-describedby="basic-addon1" name="brand">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Job Type</span>
                  <input id="job_types" type="text" class="form-control" placeholder="Job Type" aria-label="Job Type" aria-describedby="basic-addon1" name="job_type">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Category</span>
                  <input id="categories" type="text" class="form-control" placeholder="Category" aria-label="Category" aria-describedby="basic-addon1" name="category">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Floor Area</span>
                  <input id="floor_area" type="text" class="form-control" placeholder="Floor Area" aria-label="Floor Area" aria-describedby="basic-addon1" name="floor_area" onkeyup="value=value.replace(/[^0-9^\.]+/g,'').replace('.','$#$').replace(/\./g,'').replace('$#$','.').replace(/^0+/, '')">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Prospect/Sample</span>
                  <input id="prospect" type="text" class="form-control" placeholder="Prospect/Sample" aria-label="Prospect/Sample" aria-describedby="basic-addon1" name="prospect">
                </div>

               
                  <div class="form-floating">
                    <span class="input-group-text" id="basic-addon1">Drafters</span>
                  <input id="drafters" type="text" class="form-control amsify" placeholder="Add drafters here" aria-label="Drafter" style="height: 100px" aria-describedby="basic-addon1" name="drafters">
                  </div>
               
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="draft_insert">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="edit_job_modal" tabindex="-1" aria-labelledby="edit_job_modal" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header real-cognita-teal">
                <h5 class="modal-title col-md-10" >EDIT JOB</h5>
               
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('drafting_master.edit') }}" onsubmit="draft_edit.disabled = true; return true;">
                @csrf
              <div class="modal-body">

                <input id="edit_draft_id" type="hidden" name="edit_draft_id">
                <div class="input-group mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="edit_six_stars" name="edit_six_stars" value="1">
                    <label class="form-check-label" for="edit_six_stars">Six Stars</label>
                  </div>

                </div>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Customer Name</span>
                  <input id="edit_customer_names" type="text" class="form-control" placeholder="Customer Name" aria-label="Customer Name" aria-describedby="basic-addon1" name="edit_customer_name" required>
                </div>

                <div class="input-group mb-3 hidden">
                  <span class="input-group-text" id="basic-addon1">Client Job Number</span>
                  <input id="edit_job_number" type="text" class="form-control" placeholder="Job Number" aria-label="Job Number" aria-describedby="basic-addon1" name="edit_job_number" autocomplete="off" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Client Name</span>
                  <input id="edit_client_name" type="text" class="form-control" placeholder="Client Name" aria-label="Client Name" aria-describedby="basic-addon1" name="edit_client_name" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Address</span>
                  <input id="edit_address" type="text" class="form-control" placeholder="Address" aria-label="Address" aria-describedby="basic-addon1" name="edit_address" autocomplete="off" required>
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Type</span>
                  <input id="edit_types" type="text" class="form-control" placeholder="Type" aria-label="Type" aria-describedby="basic-addon1" name="edit_type" required>
                </div>

                {{-- <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">ETA</span>
                  <input id="edit_eta" type="date" class="form-control" placeholder="Type" aria-label="Type" aria-describedby="basic-addon1" name="edit_eta" required>
                </div> --}}

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Brands</span>
                  <input id="edit_brands" type="text" class="form-control" placeholder="Brand" aria-label="Brand" aria-describedby="basic-addon1" name="edit_brand">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Job Type</span>
                  <input id="edit_job_types" type="text" class="form-control" placeholder="Job Type" aria-label="Job Type" aria-describedby="basic-addon1" name="edit_job_type">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Category</span>
                  <input id="edit_categories" type="text" class="form-control" placeholder="Category" aria-label="Category" aria-describedby="basic-addon1" name="edit_category">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Floor Area</span>
                  <input id="edit_floor_area" type="text" class="form-control" placeholder="Floor Area" aria-label="Floor Area" aria-describedby="basic-addon1" name="edit_floor_area" onkeyup="value=value.replace(/[^0-9^\.]+/g,'').replace('.','$#$').replace(/\./g,'').replace('$#$','.').replace(/^0+/, '')">
                </div>

                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Prospect/Sample</span>
                  <input id="edit_prospect" type="text" class="form-control" placeholder="Prospect/Sample" aria-label="Prospect/Sample" aria-describedby="basic-addon1" name="edit_prospect">
                </div>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="draft_edit">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Assign Drafter Modal -->
        <div class="modal fade" id="assign_drafter_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header real-cognita-teal">
                <i class="fa-solid fa-folder h5  "></i>&nbsp;&nbsp;<h5 id="job_number_title" class="col-md-8">CLIENT JOB# </h5>
               
                <button type="button" class="btn-close " data-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('drafting_master.assign_drafters') }}" onsubmit="save.disabled = true; return true;">
                @csrf
              <div class="modal-body">
                <div class="form-floating">
                  <input type="hidden" name="draft_id" id="draft_id">
                  <input type="hidden" name="job_number" id="job_number">
                  <input class="form-control amsify" placeholder="Add drafters here" id="assign_drafters" style="height: 100px" name="drafters" required/>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="save">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Assign Checker Modal -->
        <div class="modal fade" id="assign_checker_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header real-cognita-teal">
                <i class="fa-solid fa-folder h5"></i>&nbsp;&nbsp;<h5 id="job_number_title" class="col-md-8">CLIENT JOB# </h5>
                
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('drafting_master.assign_checker') }}" onsubmit="save.disabled = true; return true;">
                @csrf
              <div class="modal-body">
                <div class="form-floating">
                  <input type="hidden" name="draft_id" id="draft_id">
                  <input type="hidden" name="job_number" id="job_number">
                  <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Checker</span>
                   
                      <select class="form-select" id="assign_checker" name="checker" required>
                      </select>
                      
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="save">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Edit Drafter Modal -->
        <div class="modal fade" id="edit_drafter_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header real-cognita-teal">
                <i class="fa-solid fa-folder h5"></i>&nbsp;&nbsp;<h5 id="edit_job_number_title" class=" col-md-8">CLIENT JOB# </h5>
               
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('drafting_master.edit_drafters') }}" onsubmit="save.disabled = true; return true;">
                @csrf
              <div class="modal-body">
                <div class="form-floating">
                  <input type="hidden" name="edit_draft_id" id="edit_draft_id">
                  <input type="hidden" name="edit_job_number" id="edit_job_number">
                    <input class="form-control amsify" placeholder="Add drafters here" id="edit_drafters" style="height: 100px" name="edit_drafters" required/>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="save">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Edit Checker Modal -->
        <div class="modal fade" id="edit_checker_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header real-cognita-teal">
                <i class="fa-solid fa-folder h5"></i>&nbsp;&nbsp;<h5 id="edit_job_number_title " class="col-md-8">CLIENT JOB# </h5>
                
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('drafting_master.edit_checker') }}" onsubmit="save.disabled = true; return true;">
                @csrf
              <div class="modal-body">
                <div class="form-floating">
                  <input type="hidden" name="edit_draft_id" id="edit_draft_id">
                  <input type="hidden" name="edit_job_number" id="edit_job_number">
                  <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Checker</span>
                      <select class="form-select" id="edit_checker" name="checker" required>
                      </select>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="save">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        
        @endsection
      </div>
</body>



<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready( function () {
      $("#draftingSubmenu .drafting_master").addClass("sidebar_active");

      document.getElementById("eta").min = moment().format('YYYY-MM-DD');


      const toastLiveExample = document.getElementById('liveToast');
      const toast = new bootstrap.Toast(toastLiveExample);
      const warningToast = document.getElementById('warningToast');
      const toastWarning = new bootstrap.Toast(warningToast);

      $.ajax({
            url:  '/users/getuser',
            type:"GET",
            success:function(data) {
              $('.amsify').amsifySuggestags({
                suggestions: data,
                whiteList: true,
                showAllSuggestions: false,
              });
            }
        });

      var company_list = [];
        $.ajax({
              url:  '/customers/getcustomers',
              type:"GET",
              success:function(data) {
                $.each(data, function(i, item) {
                  company_list.push(data[i].name);
                });
                 }
          });

      var jobtype_list = [];
        $.ajax({
              url:  '/jobtypes/getjobtypes',
              type:"GET",
              success:function(data) {
                $.each(data, function(i, item) {
                  jobtype_list.push(data[i].name);
                });
                 }
          });

      var type_list = [];
        $.ajax({
              url:  '/types/gettypes',
              type:"GET",
              success:function(data) {
                $.each(data, function(i, item) {
                  type_list.push(data[i].name);
                });
                 }
          });

      var categories_list = [];
        $.ajax({
              url:  '/categories/getcategories',
              type:"GET",
              success:function(data) {
                $.each(data, function(i, item) {
                  categories_list.push(data[i].name);
                });
                 }
          });
        
      var brands_list = [];
        $.ajax({
              url:  '/brands/getbrands',
              type:"GET",
              success:function(data) {
                $.each(data, function(i, item) {
                  brands_list.push(data[i].name);
                });
                 }
          });

        $.ajax({
              url:  '/users/getcheckers',
              type:"GET",
              success:function(data) {
                $('#assign_checker, #edit_checker').empty();
                $('#assign_checker, #edit_checker').append('<option value="" selected disabled>Select Checker</option>');
                $.each(data, function (i, item) {
                    $('#assign_checker, #edit_checker').append($('<option>', { 
                        value: item.value,
                        text : item.label 
                    }));
                });
                 }
          });

        $( "#customer_names,#edit_customer_names" ).autocomplete({
          source: company_list
        });
        $( "#job_types,#edit_job_types" ).autocomplete({
          source: jobtype_list
        });
        $( "#types,#edit_types" ).autocomplete({
          source: type_list
        });
        $( "#categories,#edit_categories" ).autocomplete({
          source: categories_list
        });
        $( "#brands,#edit_brands" ).autocomplete({
          source: brands_list
        });
    
      var drafting_master_tbl = $('#drafting_master_tbl').DataTable({
          ajax: "{{ route('drafting_master.list') }}",
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
                text:     '<button class="btn btn-light popover__title"><i class=" fa-solid fa-ellipsis-vertical"></i></button>',
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
              {data: 'status', title: 'Status', className: "dt-center" },
              {data: 'total_hours', title: 'Total Hours',className: "dt-center",
              render: function (data, type) {
                const duration = moment.duration(data, 'seconds').format("HH:mm:ss", { trim: false });
                return duration;
                },},
              {data: 'created_at', title: 'Created At', className: "dt-right", 
              render: function (data, type) {
                    return moment(data).format('MMM DD, YYYY');
                },},
                {data: 'cancel_job', title: 'Cancel', className: "dt-center"},
              {data: 'edit_job', title: 'Edit', className: "dt-center"},
              {data: 'submit_job', title: 'Submit', className: "dt-center"},
          ],
          order: [[0, 'desc']],
          
      });

      $('#drafting_master_tbl').on('click', '.edit_job', function (){
        var draft_id = $(this).data("id");
        var job_number = $(this).data("job_number");
        $("#edit_job_modal h5").html("<i class='fa-solid fa-pen'></i> &nbsp;EDIT CLIENT JOB# " + job_number);
          $.ajax({
              url:  location.pathname +  '/fetch/' + draft_id,
              type:"GET",
              success:function(response){
            
                $("#edit_draft_id").val(response.id);
                $("#edit_customer_names").val(response.customer_name);
                $("#edit_job_number").val(response.job_number);
                $("#edit_client_name").val(response.client_name);
                $("#edit_address").val(response.address);
                $("#edit_types").val(response.type);
                $("#edit_eta").val(response.ETA);
                $("#edit_brands").val(response.brand);
                $("#edit_job_types").val(response.job_type);
                $("#edit_categories").val(response.category);
                $("#edit_floor_area").val(response.floor_area);
                $("#edit_prospect").val(response.prospect);
                if(response.six_stars == 1){
                  $( "#edit_six_stars" ).prop( "checked", true );
                }
                else{
                  $( "#edit_six_stars" ).prop( "checked", false );
                }
              }
          });
      });

      $('#drafting_master_tbl').on('click', '.assign_drafter', function (){
        var draft_id = $(this).data('id');
        var job_number = $(this).data('job_number');
    
        $("#draft_id").val(draft_id);
        $("#job_number").val(job_number);
        
        $("#job_number_title").text("CLIENT JOB# " + job_number);
      });

      $('#drafting_master_tbl').on('click', '.assign_checker', function (){
        var draft_id = $(this).data('id');
        var job_number = $(this).data('job_number');
    
        $("#assign_checker_modal #draft_id").val(draft_id);
        $("#assign_checker_modal #job_number").val(job_number);
        
        $("#assign_checker_modal #job_number_title").text("CLIENT JOB# " + job_number);
      });

      $('#drafting_master_tbl').on('click', '.edit_drafter', function (){
        var draft_id = $(this).data('id');
        var job_number = $(this).data('job_number');
        $("#edit_drafter_modal #edit_draft_id").val(draft_id);
        $("#edit_drafter_modal #edit_job_number").val(job_number);
        $("#edit_job_number_title").text("CLIENT JOB# " + job_number);
        $.ajax({
              url:  location.pathname +  '/fetch/drafters/' + draft_id,
              type:"GET",
              success:function(response){
                $('#edit_drafter_modal #edit_drafters').val(response.users_id);
                
                $.ajax({
                      url:'/users/getuser',
                      type:"GET",
                      success:function(data) {
                        $('.amsify').amsifySuggestags({
                          suggestions: data,
                          whiteList: true,
                          showAllSuggestions: false,
                        });
                      }
                  });
                    
              }
          });

      });

      $('#drafting_master_tbl').on('click', '.edit_checker', function (){
        var draft_id = $(this).data('id');
        var job_number = $(this).data('job_number');
        $("#edit_checker_modal #edit_draft_id").val(draft_id);
        $("#edit_checker_modal #edit_job_number").val(job_number);
        $("#edit_job_number_title").text("CLIENT JOB# " + job_number);
        $.ajax({
              url:  location.pathname +  '/fetch/checker/' + draft_id,
              type:"GET",
              success:function(response){
                $("#edit_checker").val(response.users_id);
              }
          });

      });

      $('#drafting_master_tbl').on('click', '.submit_job', function (){
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
                url:  location.pathname +  '/submitjob/' + draft_id,
                type:"GET",
                success:function(response){
                  if(response == 0){
                    $("#warningToast .toast-body").html("<i class='fa-solid fa-ban'></i> Client Job Number# " + job_number + " is not yet ready for submission.");
                    toastWarning.show();
                  }
                  else{
                    $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> Client Job Number# " + job_number + " has been submitted.");
                    toast.show();
                  }
                  
                  drafting_master_tbl.ajax.reload();
                }
              });
              },
              cancel: function () {
              },
          }
      });
      });

      $('#drafting_master_tbl').on('click', '.cancel_job', function (){
        var draft_id = $(this).data("id");
        var job_number = $(this).data("job_number");
        
        $.confirm({
          icon: 'fa-solid fa-ban',
          draggable: false,
          closeIcon: true,
          backgroundDismiss: true,
          type: 'red',
          title: 'CANCEL CLIENT JOB # ' + job_number + "?",
          buttons: {
              text: 'CANCEL',
              btnClass: 'btn-red',
              confirm: function(){
                $.ajax({
                url:  location.pathname +  '/canceljob/' + draft_id,
                type:"GET",
                success:function(response){
                    $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> Client Job Number# " + job_number + " has been submitted.");
                    toast.show();
                  drafting_master_tbl.ajax.reload();
                }
              });
              },
              cancel: function () {
              },
          }
      });
      });

      $('input.toggle-vis').on('click', function (e) {
        // Get the column API object
        var column = drafting_master_tbl.column($(this).attr('data-column'));
 
        // Toggle the visibility
        column.visible(!column.visible());
      });

      var columns = $('.toggle-vis').length;
        for(var i = 0; i <= columns; i++){
            
            if(drafting_master_tbl.column( i ).visible() === true){
              $('input[data-column="'+i+'"]').prop('checked',true);
            }
            else{
              $('input[data-column="'+i+'"]').prop('checked',false);
            }

        }

        $('.modal').on('shown.bs.modal', function () {
          $('.amsify-suggestags-list').css('width','769px');
         
        })

        $( ".popover__title" )
          .click(function() {
            $(".popover__content").css("visibility","visible");
            $(".popover__content").css("z-index","10");
            $(".popover__content").css("opacity","1");
            $(".popover__content").css("transform","translate(0, -20px)");
            $(".popover__content").css("transition","all 0.5s cubic-bezier(0.75, -0.02, 0.2, 0.97)");
          })

          $(".popover__content").mouseleave(function(){
            if($(".popover__content").is(":visible")){
              $(".popover__content").css("visibility","hidden");
            }
          }); 

        
    });

</script>



