@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
<style>

</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="content" class="p-4 p-md-5 pt-5">
    <div class="container-fluid">
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

      <table id="users_tbl" class="table table-bordered row-border order-column stripe hover" width="100%" >
        
      </table>

    
</div>
<!-- Modal -->
<div class="modal fade" id="edit_role_modal" tabindex="-1" aria-labelledby="edit_role_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header real-cognita-teal">
          <h5 class="modal-title" id="edit_role_modal"><i class="fa-solid fa-pen"></i>&nbsp;&nbsp;EDIT ROLES</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="{{ route('user.updateRoles') }}" onsubmit="role_insert.disabled = true; return true;">
          @csrf
        <div class="modal-body">
          <i class="fa-solid fa-hashtag"></i>&nbsp;<span id="user_id">USER ID</span><br>
            <i class="fa-solid fa-user-tie"></i>&nbsp;&nbsp;<label id="user_fullname">USER FULLNAME</label><br>
            <i class="fa-solid fa-earth-africa "></i>&nbsp;&nbsp;<label id="user_department" >DEPARTMENT</label>

            <div class="mb-3">
              <div class="form-floating">
                <input type="hidden" name="edit_draft_id" id="edit_draft_id">
                <input type="hidden" name="edit_job_number" id="edit_job_number">
                  
              </div>
            </div>
            <hr>
            <div class="row m-3">
              <div class="col-md-6">
                <div class="mb-3">
                  <Strong>Teams</Strong>
                  @foreach($teams as $team)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $team->code_value }}" id="team{{ $team->id }}" data-department="{{$team->desc1}}"name="teams[]">
                    <label class="form-check-label" for="team{{ $team->id }}">
                      {{ $team->code_value }}
                    </label>
                  </div>
                  @endforeach
                </div>
              </div>
              <div class="col-md-6">
                <div id="roles_div mb-3">
                  <Strong>Roles</Strong>
                  <input type="hidden" id="user_id_val" name="user_id">
                  @foreach($user_roles as $roles)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $roles->id }}" id="role_{{ $roles->id }}" name="rolenames[]" data-role="{{$roles->name}}" data-department={{$roles->department}}>
                    <label class="form-check-label" for="role_{{ $roles->id }}">
                      {{ $roles->name }}
                    </label>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
           

           
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="role_insert">Save changes</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  
<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>

<script>
  
  
    $(document).ready( function () {

      $(".users").addClass('sidebar_active');
      $("#usersMenu").click();
      const toastLiveExample = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(toastLiveExample);
      
     
     

      // $("input[data-department='"+$(this).val()+"']").parent().show();

      var users_table = $('#users_tbl').DataTable({
        initComplete: function(settings, json) {
            var scrollThead = $('#users_tbl').find('thead');
        
            $('tr', scrollThead).clone(false).appendTo(scrollThead);
            console.log($(scrollThead).html());
            $('tr:eq(1) th', scrollThead).each(function() {
              $(this).removeClass('sorting sorting_asc sorting_desc');
              $(this).html('<input type="text" class="form-control search_filter" placeholder="Search" />');
            });

            var state = users_table.state.loaded();
              if (state) {
                $.each( users_table.columns().visible(),function ( colIdx,value ) {
                var colSearch = state.columns[colIdx].search;
                if ( colSearch.search ) {
                 $(".search_filter:eq("+colIdx+")").val(colSearch.search);
                }
              });
              // users_table.draw();
            }
          },
          dom: 'Bfrtip',
          buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
        ],
        colReorder: true,
        stateSave: true,
          ajax: "{{ route('user.list') }}",
          columns: [
            {data: 'id', title: 'USER ID', className:'dt-right'},
              {data: 'full_name', title: 'Full Name'},
              {data: 'email', title: 'Email'},
              {data: 'the_roles', title: 'Roles'},
              {data: 'department', title: 'Department'},
              // {data: 'team', title: 'OLD TEAM'},
              {data: 'the_teams', title: 'Team'},
              {data: 'edit_user', title: 'Action',className:'dt-center'},
              {data: 'delete_user', title: 'Action',className:'dt-center'},
              
          ],
          order: [[0, 'desc']],
      });
      
      $( users_table.table().container() ).on( 'keyup', 'thead input', function () {
        users_table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    });

      

      $('#users_tbl').on('click', '.edit-role-btn', function (){
        var user_id = $(this).data("id");
        var user_fullname = $(this).data("first_name") + " " + $(this).data("last_name");
        var user_department = $(this).data("department");

        var user_teams = $(this).data("teams");
        var user_roles = $(this).data("roles");

        var user_teams_arr = user_teams.split(',');
        var user_roles_arr = user_roles.split(',');
    
        
       $(".form-check-input[name='teams[]']").parent().hide();
       $(".form-check-input[name='rolenames[]']").parent().hide();
       $(".form-check-input[data-department='"+user_department+"']").parent().show();

       
       $(".form-check-input[data-role='"+user_department+"']").parent().show();

        $("#edit_role_modal #user_id").text(user_id);
        $("#edit_role_modal #user_id_val").val(user_id);
        
        $("#edit_role_modal #user_fullname").text(user_fullname);
        $("#edit_role_modal #user_department").text(user_department);

        $(".form-check-input[name='teams[]']").prop("checked",false);
        $(".form-check-input[name='rolenames[]']").prop("checked",false);

        $.each(user_teams_arr, function (i, item) { 
          var team = item.replace(/^\s+|\s+$/gm,'');
          $(".form-check-input[value='"+team+"']").prop("checked",true);
           
        });

        $.each(user_roles_arr, function (i, item) { 
          var role_name = item.replace(/^\s+|\s+$/gm,'');
          $(".form-check-input[data-role='"+role_name+"']").prop("checked",true);
           
        });
      
      //  {{-- $.ajax({
      //       headers: {
      //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //        },
      //          type:'POST',
      //          url:  "{{route('user.loadRoles')}}",
      //          data:{id:user_id},
      //          success:function(data) {
      //           $(".form-check-input").prop("checked",false);
      //           $.each(data, function(i, item) {
      //             $("#role_" + data[i].id).prop("checked",true);
      //           });
      //            }
      //       }); --}}

            
          });

      $('#users_tbl').on('click', '.delete-user-btn', function (){
        var user_id = $(this).data("id");
        var user_fullname = $(this).data("first_name") + " " + $(this).data("last_name")
        var token = $("meta[name='csrf-token']").attr("content");
        $.confirm({
          icon: 'fa fa-warning',
          draggable: false,
          closeIcon: true,
          backgroundDismiss: true,
          type: 'red',
          title: 'DELETE USER # ' + user_id + "?",
          buttons: {
              text: 'DELETE',
              btnClass: 'btn-red',
              confirm: function(){
                $.ajax({
                  
                  url:  "{{route('user.deleteUser','')}}"+"/"+user_id,
                  type:"GET",
                  success:function(response){
                    users_table.ajax.reload();
                    $("#liveToast .toast-body").html("<i class='fa-solid fa-check'></i> " + user_fullname + " has been deleted.");
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


