@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')

<div class="content">
    <div class="container-fluid p-5 ">
        <div class="row justify-content-center text-center text-white">
            <div class="col-md-2 bg-secondary rounded-3 m-1 p-1">
                <h3>UNASSIGNED</h3>
                <div><h3>(3)</h3></div>
            </div>
            <div class="col-md-2 bg-warning rounded-3 m-1 p-1 text-dark">
                <h3>ASSIGNED</h3>
                <div><h3>(3)</h3></div>
            </div>
            <div class="col-md-2 bg-success rounded-3 m-1 p-1">
                <h3>SUBMITTED</h3>
                <div><h3>(3)</h3></div>
            </div>
            <div class="col-md-2 bg-dark rounded-3 m-1 p-1">
                <h3>CANCELLED</h3>
                <div><h3>(3)</h3></div>
            </div>
        </div>
        <br>
        <div class="row">
                <div class="col-md-7 border border-right-1 p-3 bg-light bg-gradient border-opacity-10 rounded-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item active" aria-current="true"><i class="fas fa-toggle-on"></i>&nbsp;&nbsp;Active Users</li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Rafael Labante
                                        <span class="badge bg-primary rounded-pill">14</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        A second list item
                                        <span class="badge bg-primary rounded-pill">2</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        A third list item
                                        <span class="badge bg-primary rounded-pill">1</span>
                                    </li>
                                </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-secondary text-white" aria-current="true"><i class="fa-solid fa-toggle-off"></i>&nbsp;&nbsp;Inactive Users</li>
                                <li class="list-group-item d-flex justify-content-between align-items-center text-muted">
                                    Rafael Labante
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center text-muted">
                                    A second list item
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center text-muted">
                                    A third list item
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5  border border-1 p-3 bg-light bg-gradient border-opacity-10 rounded-3">
                    <div class="col-md-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item active" aria-current="true"><i class="fa-solid fa-calculator"></i>&nbsp;&nbsp;Status</li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Unassigned
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Assigned
                                <span class="badge bg-primary rounded-pill">142</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Ready For Check
                                <span class="badge bg-primary rounded-pill">1423</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Ready To Submit
                                <span class="badge bg-primary rounded-pill">1423</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Ready For Six Stars
                                <span class="badge bg-primary rounded-pill">1423</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                In Six Stars
                                <span class="badge bg-primary rounded-pill">1423</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Submitted
                                <span class="badge bg-primary rounded-pill">1423</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Cancelled
                                <span class="badge bg-primary rounded-pill">1423</span>
                            </li>
                        </ul>
                    </div>
                    
                </div>
        </div>
        <div class="row">
            <div class="col-md-6 border border-1 p-3 bg-light bg-gradient border-opacity-10 rounded-3">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item active" aria-current="true"><i class="fa-solid fa-calculator"></i>&nbsp;&nbsp;Status</li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Unassigned
                        <span class="badge bg-primary rounded-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Assigned
                        <span class="badge bg-primary rounded-pill">142</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ready For Check
                        <span class="badge bg-primary rounded-pill">1423</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ready To Submit
                        <span class="badge bg-primary rounded-pill">1423</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ready For Six Stars
                        <span class="badge bg-primary rounded-pill">1423</span>
                    </li>
                    
                </ul>
            </div>
            <div class="col-md-6">
                GRAPH
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
    });
</script>


