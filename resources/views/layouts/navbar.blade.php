
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
                <button type="button" id="" class="btn p-2">
                  <a class=""><i class="fa-solid fa-circle-question text-white fa-xl"></i></a>
                  </button>

                  <div class="p-2">
                    <ul class="nav navbar-nav" style="margin-right: 20px;">
                      <li class="dropdown">
                        <a id="notification_bell" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bell fa-xl text-white"></i>
                          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                              99+
                            </span></a>
                        <ul class="dropdown-menu notify-drop" style="right: 0; left: auto;">
                          <div class="notify-drop-title">
                            <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-6">Notifications</div>
                             
                            </div>
                          </div>
                          <!-- end notify title -->
                          <!-- notify content -->
                          <div class="drop-content">
                       
                            {{-- @foreach($activities_by_id as $activity_id)
      
                            <li>
                              @if($activity_id->status == 0)
                              <a href="" class="rIcon"><i class="fa-solid fa-circle text-primary"></i></a>
                              @else
                                <a href="" class="rIcon"><i class="fa-solid fa-circle text-secondary"></i></a>
                              @endif
                              <p> {{ $activity_id->description }}</p>
                              <span class="time">{{ $activity_id->created_at }}</span>
                            </li>
                            @endforeach --}}

                            @foreach($activities as $activity)
                            <li>
                              @if($activity->status == 0)
                              <a href="" class="rIcon"><i class="fa-solid fa-circle text-primary"></i></a>
                              @else
                                <a href="" class="rIcon"><i class="fa-solid fa-circle text-secondary"></i></a>
                              @endif
                              <p> {{ $activity->description }}</p>
                              <span class="time">{{ $activity->created_at }}</span>
                            </li>
                            @endforeach

                          </div>
                          <div class="notify-drop-footer text-center">
                            {{-- <a href=""><i class="fa fa-eye"></i> See More</a> --}}
                          </div>
                        </ul>
                      </li>
                    
                    </ul>
                    </div>
        </div>
      </div>
    </div>
  </nav>
  <script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
  <script src="{{ asset('moment-js/moment.js') }}"></script>
  <script type="text/javascript">
    

    $( ".time" ).each(function( index ) {
     $(this).text(moment($(this).text(), "YYYY-MM-DD hh:mm:ss").fromNow());
    });

    $("#notification_bell").click(function(){
      $.ajax({
        url:  '/submitjob/' + draft_id,
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
    });
  </script>