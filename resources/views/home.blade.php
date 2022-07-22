@extends('layouts.app')
@extends('layouts.sidebar')
@extends('layouts.navbar')
@section('content')

<div id="content" class="p-4 p-md-5 pt-5">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="btn-group">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                          Dropdown
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item" href="#">Menu item1</a></li>
                          <li><a class="dropdown-item" href="#">Menu itemasdasd</a></li>
                          <li><a class="dropdown-item" href="#">Menu item3</a></li>
                        </ul>
                      </div>
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</div>


