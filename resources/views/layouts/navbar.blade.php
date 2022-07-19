<nav class="navbar navbar-expand-lg bg-dark">
    <div class="container-fluid">
       

        
      <a class="navbar-brand" href="#"><img src="{{ asset('images/realcognita-gif-logo.gif') }}" width="180px"></a>
      <button type="button" id="sidebarCollapse" class="btn btn-info p-3">
        {{-- <img id="hamburger" src="{{ asset('images/logo_white.png') }}" alt=""> --}}
        <i class="fa-solid fa-bars"></i>
      </button>
      <button class="btn btn-dark d-inline-block d-lg-none ml-auto p-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
          {{-- <img id="hamburger" src="{{ asset('images/logo_white.png') }}" alt=""> --}}
      </button>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            

        </ul>
        <div class="d-flex" role="">

            <button type="button" id="sidebarCollapse" class="btn p-3 position-relative">
                {{-- <img id="hamburger" src="{{ asset('images/logo_white.png') }}" alt=""> --}}
                
                <i class="fa-solid fa-bell fa-xl"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    99+
                  </span>
              </button>
              
             
              {{-- <button type="button" id="sidebarCollapse" class="btn p-3 dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            
                <i class="fa-solid fa-user-gear fa-xl text-white"></i>
                
              </button>

              <div class="dropdown">
               
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <li><button class="dropdown-item" type="button">Action</button></li>
                  <li><button class="dropdown-item" type="button">Another action</button></li>
                  <li><button class="dropdown-item" type="button">Something else here</button></li>
                </ul>
              </div> --}}
        </div>
      </div>
    </div>
  </nav>