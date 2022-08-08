@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content">
    <div class="container-fluid p-5 ">
        <div class="row border border-2 rounded-3 bg-light">
            <div class="col-md-2">
                <div class="form-floating m-3">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                      <option value="Drafting">Drafting</option>
                      <option value="Scheduling">Scheduling</option>
                    </select>
                    <label for="floatingSelect">Module</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating m-3">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                        @foreach($customers as $customer)
                        <option value="{{$customer->name}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                    <label for="floatingSelect">Customer</label>
                </div>
            </div>
        </div>
        
        <table id="multifilters_tbl" class="table table-bordered row-border order-column stripe hover" data-mode="columntoggle"width="100%">
        </table>
    </div>

</div>
@endsection
</div>
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script src="{{ asset('chart-js/chart-js.js') }}"></script>
<script>
    $(document).ready(function(){
        $(".multifilters").addClass("sidebar_active");
    
        const toastLiveExample = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastLiveExample);
        const warningToast = document.getElementById('warningToast');
        const toastWarning = new bootstrap.Toast(warningToast);

 
    });
</script>


