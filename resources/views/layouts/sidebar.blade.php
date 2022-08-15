
<div class="wrapper" style="display:none;">
  <!-- Sidebar  -->
  <nav id="sidebar">
      {{-- <div class="sidebar-header">
     
          <img src="{{ asset('images/realcognita-gif-logo.gif') }}" width="200px">
      </div> --}}

      <h3>
      
    </h3>

  
      <div class="user-details m-3">
          <span><i class="fa-solid fa-user-astronaut"></i>  {{Auth::user()->first_name}} {{Auth::user()->last_name}}</span><br>
          <span><i class="fa-solid fa-earth-africa"></i>  <span id="user_department">{{Auth::user()->department}}</span> ({{Auth::user()->team}})</span><br>
          <span><i class="fa-solid fa-envelope"></i>  {{Auth::user()->email}}</span><br>
          <i class="fa-solid fa-briefcase"></i>
       
              @foreach (Auth::user()->permissions as $permission) 
                  &lt;{{
                     $role_name[] = \App\Models\Role::select('name')->where('id','=',$permission->role_id)->first()->name;
                  }}&gt;
              
              @endforeach
      </div>
      <hr>
      <ul class="list-unstyled components">

        
        @if(Auth::user()->inRole(['Administrator','Drafting Manager','Scheduling Manager','Drafting Admin']))
        
            <li>
                <a class="dashboard" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-chart-pie"></i>&nbsp;&nbsp;Dashboard
                </a>
            </li>
        @endif

        @if(Auth::user()->inRole(['Administrator']))
            <li class="active">
                <a role="button" id="usersMenu" href="#userSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa-solid fa-user-gear"></i>&nbsp;&nbsp;User Maintenance</a>
                <ul class="collapse list-unstyled" id="userSubmenu">
                    <li>
                        <a class="register" href="{{route('register')}}"><i class="fa-solid fa-user-plus"></i>&nbsp;&nbsp;Register</a>
                    </li>
                    <li>
                        <a class="users" href="{{route('user')}}"><i class="fa-solid fa-users"></i>&nbsp;&nbsp;Users</a>
                    </li>
                </ul>
            </li>
            <li class="active">
                <a role="button" id="filesMenu" href="#filesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa-solid fa-file-circle-exclamation"></i>&nbsp;&nbsp;File Maintenance</a>
                <ul class="collapse list-unstyled" id="filesSubmenu">
                    <li>
                        <a class="brand" href="{{route('brand')}}"><i class="fa-solid fa-b"></i>&nbsp;&nbsp;Brands</a>
                    </li>
                    <li>
                        <a class="category" href="{{route('categories')}}"><i class="fa-solid fa-diagram-project"></i>&nbsp;&nbsp;Categories</a>
                    </li>
                    <li>
                        <a class="customer" href="{{route('customer')}}"><i class="fa-solid fa-user-tie"></i>&nbsp;&nbsp;Customers</a>
                    </li>
                    <li>
                        <a class="job_type" href="{{route('job_type')}}"><i class="fa-solid fa-briefcase"></i>&nbsp;&nbsp;Job Types</a>
                    </li>
                    <li>
                        <a class="type" href="{{route('type')}}"><i class="fa-solid fa-t"></i>&nbsp;&nbsp;Types</a>
                    </li>
                    <li>
                        <a class="prestart" href="{{route('prestart')}}"><i class="fa-solid fa-p"></i>&nbsp;&nbsp;Prestart</a>
                    </li>
                </ul>
            </li>
            
        @endif
              
        @if(Auth::user()->inRole(['Administrator','Drafting Manager','Drafting TL','Drafting Admin','Drafting Checker','Drafter']))
        <li class="active">
            <a role="button" id="draftingMenu" href="#draftingSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa-solid fa-pen-ruler"></i>&nbsp;&nbsp;Drafting</a>
          
            <ul class="collapse list-unstyled" id="draftingSubmenu">
              
                @if(Auth::user()->inRole(['Administrator','Drafting Manager','Drafting TL','Drafting Admin']))
                    <li>
                        <a class="drafting_master" href="{{route('drafting_master')}}"><i class="fa-solid fa-list-check"></i>&nbsp;&nbsp;Drafting Master</a>
                    </li>
                @endif
                
                @if(Auth::user()->inRole(['Administrator','Drafter']))
                    <li>
                        <a class="my_drafts" href="{{route('my_drafts')}}"><i class="fa-solid fa-compass-drafting"></i>&nbsp;&nbsp;My Drafts</a>
                    </li>
                @endif

                @if(Auth::user()->inRole(['Administrator','Drafting Checker']))
                    <li>
                        <a class="my_drafts_check" href="{{route('my_drafts_check')}}"><i class="fa-solid fa-check-double"></i>&nbsp;&nbsp;My Drafts Check</a>
                    </li>
                @endif

                @if(Auth::user()->inRole(['Administrator','Drafting Manager','Drafting Admin']))
                    <li>
                        <a class="submitted"  href="{{route('drafting_master.submitted_jobs')}}"><i class="fa-solid fa-paper-plane"></i>&nbsp;&nbsp;Submitted</a>
                    </li>
                    <li>
                        <a class="cancelled"  href="{{route('drafting_master.cancelled_jobs')}}"><i class="fa-solid fa-ban"></i>&nbsp;&nbsp;Cancelled</a>
                    </li>
                @endif

                @if(Auth::user()->inRole(['Administrator','Six Stars']))
                    <li>
                        <a class="six_stars" href="{{route('sixstars')}}"><i class="fa-solid fa-star"></i>&nbsp;&nbsp;Six Stars</a>
                    </li>
                @endif
             
            </ul>
        </li>
       @endif
       
       @if(Auth::user()->inRole(['Administrator','Scheduling Manager','Scheduling Admin','Senior Scheduler','Scheduling Checker','Scheduler']))
       
        <li class="active">
            <a role="button" id="schedulingMenu" href="#schedulingSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa-solid fa-calendar-days"></i>&nbsp;&nbsp;Scheduling</a>
            <ul class="collapse list-unstyled" id="schedulingSubmenu">
                @if(Auth::user()->inRole(['Administrator','Scheduling Manager','Scheduling Admin','Senior Scheduler']))
                    <li>
                        <a class="scheduling_master" href="{{route('scheduling_master')}}"><i class="fa-solid fa-list-check"></i>&nbsp;&nbsp;Scheduling Master</a>
                    </li>
                @endif

                @if(Auth::user()->inRole(['Administrator','Senior Scheduler','Scheduler']))
                    <li>
                        <a class="my_schedules" href="{{route('my_schedules')}}"><i class="fa-solid fa-calendar-days"></i>&nbsp;&nbsp;My Schedules</a>
                    </li>
                @endif
                @if(Auth::user()->inRole(['Administrator','Scheduling Checker']))
                    <li>
                        <a class="my_schedules_check" href="{{route('my_schedules_check')}}"><i class="fa-solid fa-check-double"></i>&nbsp;&nbsp;My Schedules Check</a>
                    </li>
                @endif
            </ul>
        </li>
        @endif

        @if(Auth::user()->inRole(['Administrator']))
       
            <li class="active">
                <a role="button" id="reportsSubmenu" href="#reportsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa-solid fa-calendar-days"></i>&nbsp;&nbsp;Reports</a>
                <ul class="collapse list-unstyled" id="reportsSubmenu">
                    <li>
                        <a class="multifilters" href="{{route('report.multifilters')}}"><i class="fa-solid fa-list-check"></i>&nbsp;&nbsp;Multi-Filters</a>
                    </li>
                    <li>
                        <a class="usertimesheets" href="{{route('report.usertimesheets','drafting')}}"><i class="fa-solid fa-clock"></i>&nbsp;&nbsp;User Timesheet</a>
                    </li>
                </ul>
            </li>
        @endif


      </ul>
      <p class="text-center">© 2022 BSI-DMS v1.0.0</p>
  </nav>

  <!-- Page Content  -->
  <div id="content">

      <main class="py-4">
          @yield('content')
      </main>
  </div>
</div>
<script href="{{ asset('bootstrap-5/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>

<script type="text/javascript">
$( document ).ready(function() {
    $(".wrapper,.navbar").show();
 
});
</script>