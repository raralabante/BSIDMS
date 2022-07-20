@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
      <body class="">
        <div class="container-fluid p-5">
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
            <div class="col-md-12 col-sm-12">
                <div class="d-flex justify-content-center align-items-center ">
                    <div class="card mb-3 col-md-4 col-sm-12">
                        <div class="card-header h3 bg-dark real-cognita-teal"><i class="fa-solid fa-clock"></i>&nbsp;&nbsp;SHIFTING SCHEDULE</div>
                        <form method="POST" action="{{ route('shifting_schedule.update') }}" onsubmit="update.disabled = true; return true;">
                            @csrf
                        <div class="card-body">
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" id="floatingInput" value="{{$shifting_schedule->morning_start}}" name="morning_start" required>
                                <label for="floatingInput">Morning Start </label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" id="floatingInput" value="{{$shifting_schedule->morning_end}}" name="morning_end" required>
                                <label for="floatingInput">Morning End </label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" id="floatingInput" value="{{$shifting_schedule->afternoon_start}}" name="afternoon_start" required>
                                <label for="floatingInput">Afternoon Start </label>
                            </div>
                            <div class="form-floating">
                                <input type="time" class="form-control" id="floatingInput" value="{{$shifting_schedule->afternoon_end}}" name="afternoon_end" required>
                                <label for="floatingInput">Afternoon End </label>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-wrench" name="update"></i>&nbsp;&nbsp;UPDATE</button>
                            </div>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
        
        
        </div>
      </body>

<script src="{{ asset('jquery/jquery-3.6.0.js') }}"></script>
<script>
    $(document).ready( function () {
      const successToast = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(successToast);
      const warningToast = document.getElementById('warningToast')
      const toastWarning = new bootstrap.Toast(warningToast);
    });
</script>
@endsection


