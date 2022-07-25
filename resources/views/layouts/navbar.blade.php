
<nav class="navbar navbar-expand-lg bg-dark navbar-default" style="display:none;">
    <div class="container-fluid m-1">
      
      <a class="navbar-brand" href="#"><img src="{{ asset('images/realcognita-gif-logo.gif') }}" width="180px"></a>
      <button type="button" id="sidebarCollapse" class="btn btn-info p-3">
      
        <i class="fa-solid fa-bars"></i>
      </button>
      <button class="btn btn-dark d-inline-block d-lg-none ml-auto p-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
        
      </button>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>


      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            

        </ul>
        <div class="d-flex ">
            {{-- <button type="button" id="sidebarCollapse" class="btn p-3 m-10">
                <i class="fa-solid fa-bell fa-xl"></i>
               
              </button> --}}
            
              <button type="button" id="" class="btn p-3" data-toggle="modal" data-target="#exampleModal">
                <i class="fa-solid fa-flag fa-xl text-white"></i>
              </button>

                  <button type="button" id="" class="btn p-3">
                    <a class=""><i class="fa-solid fa-circle-question text-white fa-xl"></i></a>
                  </button>
         
                  <div class="m-1">
                    <ul class="nav navbar-nav">
                      <li class="dropdown">
                       

                        
                          @if($notification_count == 0)
                          <button type="button" id="notification_bell" href="#" class="dropdown-toggle btn p-3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bell fa-xl text-white "></i>
                          <span id="notification_count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger pulsing hidden">{{$notification_count}}</span></button>
                          @else
                          <button type="button" id="notification_bell" href="#" class="dropdown-toggle btn p-3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bell fa-xl text-danger "></i>
                          <span id="notification_count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger pulsing ">{{$notification_count}}</span></button>
                          @endif
                            
                        <ul class="dropdown-menu notify-drop" style="right: 0; left: auto;">
                          <div class="notify-drop-title">
                            <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-6">Notifications</div>
                            </div>
                          </div>
                          <div id="notification_list" class="drop-content">
                            
                          </div>
                          <div class="notify-drop-footer text-center">
                            {{-- <a href=""><i class="fa fa-eye"></i> See More</a> --}}
                          </div>
                        </ul>
                      </li>
                    
                    </ul>
                    </div>

                    <div id="user_profile" class="m-1">
                      <div class="dropdown ">
                        <button class="btn dropdown-toggle p-3 " type="button" data-toggle="dropdown" aria-expanded="false">
                          <i class="fa-solid fa-user-tie text-white fa-xl"></i>
                        </button>

                      <ul class="dropdown-menu" style="right: 0; left: auto; ">
                        <li>
                          <a class="text-white dropdown-item" href="{{ route('logout') }}"
                          onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                           <i class="fa-solid fa-power-off"></i>{{ __('  Logout') }}
                       </a>
            
                       <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                           @csrf
                       </form>
                      </li>
                       
                      </ul>
                    </div>
                    </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header real-cognita-teal">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-flag"></i>&nbsp;&nbsp;REPORT A PROBLEM?</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="border p-4 text-center">
                  <h3 class="text-center">HOW CAN WE HELP?</h3>
                  <p class="text-center">Tell us your problem so we can get you the right help and support.</p><br>
                  <span class="badge rounded-pill bg-primary p-2"><strong>DEVELOPERS</strong></span><br><br>
                  <span><strong>Edrick John Gabagat</strong> </span><br>
                  <span><i class="fa-solid fa-envelope text-primary"></i>&nbsp;&nbsp;<a class="text-primary" href = "mailto: gabaejs@realcognita.com"><u>gabaejs@realcognita.com</u> </a></span><br>
                  <span><strong>Rafael Labante</strong>  </span><br>
                  <span><i class="fa-solid fa-envelope text-primary"></i>&nbsp;&nbsp;<a class="text-primary" href = "mailto: rafaell@realcognita.com"><u>rafaell@realcognita.com</u> </a></span><br>
                </div>
                
              </div>
              {{-- <div class="col-md-6">
                <img src="{{ asset('images/problem.jpg') }}" alt="" height="auto" width="375px">
              </div> --}}
            </div>
          </div>
        </div>
      </div>
    </div>

  </nav>
  <script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
  <script src="{{ asset('moment-js/moment.js') }}"></script>
  <script type="text/javascript">
    
$(document).ready(function(){
  $("#page_title").text("{{ config('app.name', 'Laravel') }} " +  "(" + $("#notification_count").text() + ")");
    $("#notification_bell").click(function(){
      $.ajax({
            url:  '{{route("user.getActivities")}}',
            type:"GET",
            async: true,
            success:function(response){
              $("#notification_list").empty();
              $.each(response, function(index, item) {
                
                  if(item.status == 0){
                    
                    $("#notification_list").append("<li><a class='rIcon'><i class='fa-solid fa-circle text-primary'></i></a>"
                      + "<p>" + item.description + "</p><span class='time'>"+moment(moment(item.created_at).format()).fromNow()+"</span></li>");
                  }
                  else{
                    $("#notification_list").append("<li><a  class='rIcon'><i class='fa-solid fa-circle text-secondary'></i></a>"
                    + "<p>" + item.description + "</p><span class='time'>"+moment(moment(item.created_at).format()).fromNow()+"</span></li>");
                  }
                 
              });
              
            }
          });
          $.ajax({
            url:  '{{route("user.readActivities")}}',
                type:"GET",
                success:function(response){
                  $.ajax({
                    url:  '{{route("user.countActivities")}}',
                    type:"GET",
                    success:function(response){
                      if(response == 0 ){
                          $("#notification_bell i").addClass("text-white").removeClass("text-danger");
                
                          $("#notification_count").text(response).addClass("hidden");
                         
                        }
                        else{
                          $("#notification_bell i").removeClass("text-white").addClass("text-danger");
                         
                          $("#notification_count").text(response).removeClass("hidden");
                        }
                        $("#page_title").text("{{ config('app.name', 'Laravel') }} " +  "(" + response + ")");
                    }
                  });
                }
              });
    });

    Pusher.logToConsole = true;

    var pusher = new Pusher('89eec464cd4d14a2238d', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      $.ajax({
              url:  '/users/countactivities',
              type:"GET",
              success:function(response){
                if(response == 0 ){
                  $("#notification_bell i").addClass("text-white").removeClass("text-danger");
                  $("#notification_count").text(response).addClass("hidden");
                }
                else{
                  $("#notification_bell i").removeClass("text-white").addClass("text-danger");
                  $("#notification_count").text(response).removeClass("hidden");
                }
                $("#page_title").text("{{ config('app.name', 'Laravel') }} " +  "(" + response + ")");
              }
            });

});

});
    
  </script>