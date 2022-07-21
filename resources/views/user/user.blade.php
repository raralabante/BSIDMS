@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
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
      <table id="users_tbl" class="table table-bordered row-border order-column stripe hover" width="100%">
      </table>

    
</div>
<!-- Modal -->
<div class="modal fade" id="edit_role_modal" tabindex="-1" aria-labelledby="edit_role_modal" aria-hidden="true">
    <div class="modal-dialog">
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
            <i class="fa-solid fa-earth-africa"></i>&nbsp;&nbsp;<label id="user_department">DEPARTMENT</label>
            <hr>

            <div class="mb-3">
              <div class="form-floating">
                <input type="hidden" name="edit_draft_id" id="edit_draft_id">
                <input type="hidden" name="edit_job_number" id="edit_job_number">
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Team</span>
                  <select class="form-select col-md-6 @error('team') is-invalid @enderror" name="team" id="team">
                    <option value="" disabled selected>Select Team</option>
                </select>
                </div>
              </div>
            </div>
            
            <div id="roles_div mb-3">
              <input type="hidden" id="user_id_val" name="user_id">
              @foreach($user_roles as $roles)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="{{ $roles->id }}" id="role_{{ $roles->id }}" name="rolename[]">
                <label class="form-check-label" for="role_{{ $roles->id }}">
                  {{ $roles->name }}
                </label>
              </div>
              @endforeach
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
      const toastLiveExample = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(toastLiveExample);
      
      $('#users_tbl').on('click', '.edit-role-btn', function (){
        var user_id = $(this).data("id");
        var user_fullname = $(this).data("first_name") + " " + $(this).data("last_name");
        var user_department = $(this).data("department");
        var user_team = $(this).data("team");

        $("#edit_role_modal #user_id").text(user_id);
        $("#edit_role_modal #user_id_val").val(user_id);
        
        $("#edit_role_modal #user_fullname").text(user_fullname);
        $("#edit_role_modal #user_department").text(user_department);

        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
               type:'POST',
               url:  location.pathname +  '/list/loadroles',
               data:{id:user_id},
               success:function(data) {
                $.each(data, function(i, item) {
                  $("#role_" + data[i].id).prop("checked",true);
                });
                 }
            });


            $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
               type:'POST',
               url:  '/register/loadteam',
               async: true,
               data:{department:user_department},
               success:function(data) {
                var team = $("#team");
                    team.empty();
                    $.each(data, function(i, item) {
                        team.append($("<option />").val(data[i].code_value).text(data[i].code_value));
                    });
                    $("#team").val(user_team).change();
                 }
            });
           
            
          });

          

      var users_table = $('#users_tbl').DataTable({
        colReorder: true,
        stateSave: true,
          ajax: "{{ route('user.list') }}",
          columns: [
            {data: 'id', title: 'USER ID', className:'dt-right'},
              {data: 'full_name', title: 'Full Name'},
              {data: 'email', title: 'Email'},
              {data: 'the_role', title: 'Roles'},
              {data: 'department', title: 'Department'},
              {data: 'team', title: 'Team'},
              {data: 'edit_role', title: 'Action'},
          ],
          order: [[0, 'desc']],
      });
        
      $('a.toggle-vis').on('click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = users_table.column($(this).attr('data-column'));
 
        // Toggle the visibility
        column.visible(!column.visible());
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
                  url:  location.pathname +  '/list/deleteuser/' + user_id,
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


