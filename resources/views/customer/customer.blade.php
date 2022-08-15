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
        <div class="col-md-6">
          <form method="POST" action="{{ route('customer.insert') }}" onsubmit="add_customer_btn.disabled = true; return true;">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Company Name" name="customer_name" required autocomplete="off">
                  <select class="form-select" name="team" id="team" required>
                    @foreach($teams as $team)
                    <option value="{{$team->code_value}}">{{$team->code_value}}</option>
                    @endforeach
                  </select>
                <button class="btn btn-success" type="submit" id="add_customer_btn"><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;ADD CUSTOMER</button>
            </div>
          </form>
        </div>
      </div>
      
        <table id="customers_tbl" class="table table-bordered row-border order-column stripe hover" width="100%"></table>
        
    {{-- </div>
      Toggle column: <a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1">Position</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
    </div> --}}
</div>




<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready( function () {
      $("#filesSubmenu .customer").addClass("sidebar_active");
      $("#filesMenu").click();
      const toastLiveExample = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(toastLiveExample);

      var customers_tbl = $('#customers_tbl').DataTable({
          ajax: "{{ route('customer.list') }}",
          columns: [
              {data: 'id', title: 'ID', className: "dt-right" },
              {data: 'name', title: 'Name'},
              {data: 'team', title: 'Team'},
              {data: 'delete_customer', title: 'Action'},
          ],
          order: [[0, 'desc']],
      });


      $('#customers_tbl').on('click', '.delete-customer-btn', function (){
        var customer_id = $(this).data("id");
        var customer_name = $(this).data("customer_name");
        var token = $("meta[name='csrf-token']").attr("content");
        $.confirm({
          icon: 'fa fa-warning',
          draggable: false,
          closeIcon: true,
          backgroundDismiss: true,
          type: 'red',
          title: 'DELETE ' + customer_name + "?",
          buttons: {
              text: 'DELETE',
              btnClass: 'btn-red',
              confirm: function(){
                $.ajax({
                  url:  "{{route('customer.deleteCustomer','')}}"+"/"+customer_id,
                  
                  type:"GET",
                  success:function(response){
                    customers_tbl.ajax.reload();
                    $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> " + customer_name + " has been deleted.");
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


