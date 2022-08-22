@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content">
        <div class="container-fluid p-3">
            {{-- <div class="row justify-content-center text-center ">
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-header btn-dark-green text-white">
                        <div class="float-start ">
                            <i class="fa-solid fa-satellite-dish text-danger fa-xl mt-4 pulsing p-1"></i><span>&nbsp;&nbsp;Live updating</span> 
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
        <br> --}}
            <div class="row">

                {{-- <div class="col-md-12">
            <div class="input-group p-2 float-end " style="width:30%" style="text-align:center!important">
              <span class="input-group-text realcognita ">FROM</span>
              <input id="from" type="date" class="form-control">
              <span class="input-group-text realcognita">TO</span>
              <input id="to" type="date" class="form-control">
            </div>
          </div> --}}
            </div>
            <div class="row">
                <div class="col-md-8 ">
                    <div class="row mb-3 ">
                        <div class="col-md-3 " style="padding-left:0px">
                            <div class="card info-card revenue-card shadow">

                                <div class="card-body">
                                    <h5 class="card-title">Unassigned <span class="text-muted"></span></h5>

                                    <div class="d-flex align-items-center">

                                        <button class="btn-circle bg-secondary m-2">
                                            &nbsp;<i class="fa-solid fa-user-pen fa-xl text-white"></i>
                                        </button>
                                        <div class="ps-3">
                                            <h3 id="unassigned_count" class="text-muted">({{ $unassigned_count }})</h3>
                                            {{-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="card info-card revenue-card shadow">

                                <div class="card-body">
                                    <h5 class="card-title">Ready To Submit</h5>

                                    <div class="d-flex align-items-center">
                                        <button class="btn-circle bg-warning m-2">
                                            &nbsp;<i class="fa-solid fa-r fa-xl text-dark"></i>
                                        </button>
                                        <div class="ps-3">
                                            <h3 id="ready_to_submit_count" class="text-muted">({{ $ready_to_submit_count }})
                                            </h3>
                                            {{-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="card info-card revenue-card shadow">

                                <div class="card-body">
                                    <h5 class="card-title">Submitted</h5>

                                    <div class="d-flex align-items-center">
                                        <button class="btn-circle bg-success m-2">
                                            <i class="fa-solid fa-paper-plane fa-xl text-white"></i>
                                        </button>
                                        <div class="ps-3">
                                            <h3 id="submitted_count" class="text-muted">({{ $submitted_count }})</h3>
                                            {{-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3 " style="padding-right:0px;">
                            <div class="card info-card revenue-card shadow">

                                <div class="card-body">
                                    <h5 class="card-title">Latest Job Added</h5>

                                    <div class="d-flex align-items-center ">
                                        <button class="btn-circle bg-primary m-2">
                                            <i class="fa-solid fa-briefcase fa-xl text-white"></i>
                                        </button>


                                        <div class="ps-3 " style=" overflow: hidden;">
                                            <h6><a href="{{ route('timesheets.drafting', $latest_job->id) }}"
                                                    id="latest_job"
                                                    class="small text-primary">{{ $latest_job->job_number }}</a></h6>
                                            <span id="latest_job_date"
                                                class="text-muted small pt-2 ps-1">{{ $latest_job->created_at }}</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="card shadow p-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item active" aria-current="true"><i
                                                    class="fas fa-toggle-on"></i>&nbsp;&nbsp;Active Users <span
                                                    id="active_users_count">({{ $active_users_count }})</span></li>
                                            <div id="active_users" style="height:400px; overflow:auto">
                                                @foreach ($active_users as $user)
                                                    <li
                                                        class='list-group-item d-flex justify-content-between align-items-center'>
                                                        {{ $user->full_name }}<small>{{ $user->job_type }}</small>
                                                        <a href="{{ route('timesheets.drafting', $user->drafting_masters_id) }}"
                                                            class='text-primary'><u>{{ $user->job_number }}</u></a>
                                                    </li>
                                                @endforeach
                                            </div>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-secondary text-white" aria-current="true"><i
                                                    class="fa-solid fa-toggle-off"></i>&nbsp;&nbsp;Inactive Users <span
                                                    id="inactive_users_count">({{ $inactive_users_count }})</span></li>
                                            <div id="inactive_users" style="height:400px; overflow:auto">
                                                @foreach ($inactive_users as $user)
                                                    <li
                                                        class='list-group-item d-flex justify-content-between align-items-center text-muted'>
                                                        {{ $user }}</li>
                                                @endforeach
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">

                    </div>

                </div>

                <div class="col-md-4">
                    {{-- <!-- Recent Activity -->
                   {{-- <div class="card shadow">
                    <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                        </li>

                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                    </div>

                    <div class="card-body">
                    <h5 class="card-title">Recent Activity <span>| Today</span></h5>

                    <div class="activity">

                        <div class="activity-item d-flex">
                        <div class="activite-label">32 min</div>
                        <i class='fa-solid fa-circle activity-badge text-success align-self-start'></i>
                        <div class="activity-content">
                            Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                        </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                        <div class="activite-label">56 min</div>
                        <i class='fa-solid fa-circle activity-badge text-danger align-self-start'></i>
                        <div class="activity-content">
                            Voluptatem blanditiis blanditiis eveniet
                        </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                        <div class="activite-label">2 hrs</div>
                        <i class='fa-solid fa-circle activity-badge text-primary align-self-start'></i>
                        <div class="activity-content">
                            Voluptates corrupti molestias voluptatem
                        </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                        <div class="activite-label">1 day</div>
                        <i class='fa-solid fa-circle activity-badge text-info align-self-start'></i>
                        <div class="activity-content">
                            Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                        </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                        <div class="activite-label">2 days</div>
                        <i class='fa-solid fa-circle activity-badge text-warning align-self-start'></i>
                        <div class="activity-content">
                            Est sit eum reiciendis exercitationem
                        </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                        <div class="activite-label">4 weeks</div>
                        <i class='fa-solid fa-circle activity-badge text-muted align-self-start'></i>
                        <div class="activity-content">
                            Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                        </div>
                        </div><!-- End activity item-->

                    </div>

                    </div>
                </div><!-- End Recent Activity --> --}}
                    {{-- <div class="card shadow p-3">
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
                    </div> --}}
                </div>
            </div>
        </div>
    @endsection
</div>
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script src="{{ asset('chart-js/chart-js.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".dashboard").addClass("sidebar_active");

        const toastLiveExample = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastLiveExample);
        const warningToast = document.getElementById('warningToast');
        const toastWarning = new bootstrap.Toast(warningToast);

        $("#latest_job_date").text(moment(moment($("#latest_job_date").text()).format()).fromNow());
    });
</script>
