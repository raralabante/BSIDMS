<style>
    #hamburger {
        
        height: 30px;
        width: 30px;
        display: block;
        color: #ffffff
        /* Other styles here */
    }
</style>

<div class="wrapper">
  <!-- Sidebar  -->
  <nav id="sidebar">
      {{-- <div class="sidebar-header">
     
          <img src="{{ asset('images/realcognita-gif-logo.gif') }}" width="200px">
      </div> --}}
      <div class="user-details">
          <span><i class="fa-solid fa-user-astronaut"></i>  {{Auth::user()->first_name}} {{Auth::user()->last_name}}</span><br>
          <span><i class="fa-solid fa-earth-africa"></i>  {{Auth::user()->department}} ({{Auth::user()->team}})</span><br>
          
          <i class="fa-solid fa-briefcase"></i>
       
              @foreach (Auth::user()->permissions as $permission) 
                  &lt;{{
                    $role_name[] = \App\Models\Role::select('name')->where('id','=',$permission->role_id)->first()->name;
                  }}&gt;
              
              @endforeach
      </div>
      <ul class="list-unstyled components">

          <li class="active">
              <a role="button" id="draftingMenu" href="#draftingSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa-solid fa-pen-ruler"></i>&nbsp;&nbsp;Drafting</a>
              <ul class="collapse list-unstyled" id="draftingSubmenu">

               
                @if (!empty($role_name))
                @foreach ($role_name as $role) 
                        @if ($role == "Administrator" || $role == "Drafting Manager" || $role == "Drafting TL" )
                            <li>
                                <a class="drafting_master" href="{{route('drafting_master')}}"><i class="fa-solid fa-list-check"></i>&nbsp;&nbsp;Drafting Master</a>
                            </li>
                        @endif
                        @if ($role == "Administrator" || $role == "Drafting Manager" || $role == "Drafting TL" || $role == "Drafting Checker"|| $role == "Drafter" )
                            <li>
                                <a class="my_drafts" href="{{route('my_drafts')}}"><i class="fa-solid fa-compass-drafting"></i>&nbsp;&nbsp;My Drafts</a>
                            </li>
                        @endif
                        @if ($role == "Administrator" || $role == "Drafting Manager" || $role == "Drafting TL" || $role == "Drafting Checker")
                            <li>
                                <a class="my_drafts_check" href="{{route('my_drafts_check')}}"><i class="fa-solid fa-check-double"></i>&nbsp;&nbsp;My Drafts Check</a>
                            </li>
                        @endif
                        @if ($role == "Administrator" || $role == "Drafting Manager")
                            <li>
                                <a class="submitted"  href="{{route('drafting_master.submitted_jobs')}}"><i class="fa-solid fa-paper-plane"></i>&nbsp;&nbsp;Submitted</a>
                            </li>
                        @endif
                        @if ($role == "Administrator" || $role == "Drafting Manager")
                            <li>
                                <a class="cancelled"  href="{{route('drafting_master.cancelled_jobs')}}"><i class="fa-solid fa-ban"></i>&nbsp;&nbsp;Cancelled</a>
                            </li>
                        @endif
                        @if ($role == "Administrator" || $role == "Drafting Manager" || $role == "Six Stars")
                            <li>
                                <a class="six_stars" href="{{route('sixstars')}}"><i class="fa-solid fa-star"></i>&nbsp;&nbsp;Six Stars</a>
                            </li>
                        @endif
                @endforeach
                @endif
              </ul>
          </li>
          
            @if (!empty($role_name))
                @foreach ($role_name as $role) 
                @if ($role == "Administrator")
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
                
                @endif
                @endforeach
            @endif

            @if (!empty($role_name))
            @foreach ($role_name as $role) 
            @if ($role == "Administrator")
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
                </ul>
            </li>
            
            @endif
            @endforeach
        @endif

          <li>
            <a class=""><i class="fa-solid fa-circle-question"></i>&nbsp;&nbsp;FAQ </a>
           
        </li>
      </ul>
     
      
      <ul class="list-unstyled">
          <li>
              <a class="" href="{{ route('logout') }}"
              onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
               <i class="fa-solid fa-power-off"></i>{{ __('  Logout') }}
           </a>

           <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
               @csrf
           </form>
          </li>
      </ul>

  

  </nav>

  <!-- Page Content  -->
  <div id="content">

     
              {{-- <button type="button" id="sidebarCollapse" class="btn btn-info">
                <img id="hamburger" src="{{ asset('images/logo_white.png') }}" alt="">
                
              </button>
              <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  {{-- <i class="fas fa-align-justify"></i> --}}
                  <img id="hamburger" src="{{ asset('images/logo_white.png') }}" alt="">
              </button>

              
         
      <main class="py-4">
          @yield('content')
      </main>
  </div>
</div>
<script href="{{ asset('bootstrap-5/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>

<script type="text/javascript">
$( document ).ready(function() {
    var module = $(location).attr('pathname');
    module.indexOf(1);
    module = module.split("/")[1];

    //toggle submenu
    if(module == "draftingmaster")
    {
        $("#draftingMenu").click();

    }
    else if (module == "users" || module == "register"){
        $("#usersMenu").click();
    }
    else if (module == "brands" || module == "customers" || module == "jobtypes"|| module == "types" || module == "categories"){
        $("#filesMenu").click();
    }

    //remove duplicate submenu
    var seen = {};
    $('ul li').each(function() {
            var txt = $(this).text();
            if (seen[txt])
                $(this).remove();
            else
                seen[txt] = true;
        });

        $(document).ready(function()
			{
			$("#notificationLink").click(function()
			{
			$("#notificationContainer").fadeToggle(300);
			$("#notification_count").fadeOut("slow");
			return false;
			});

			//Document Click hiding the popup 
			$(document).click(function()
			{
			$("#notificationContainer").hide();
			});

			//Popup on click
			$("#notificationContainer").click(function()
			{
			return false;
			});

			});

});




</script>