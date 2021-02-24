<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user"></i>
        {{auth()->user()->name}}
        <i class="fa fa-angle-down"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-divider"></div>
        <a href="" class="dropdown-item">
          <i class="fas fa-cog mr-2"></i>
          Account Settings
        </a>
        <div class="dropdown-divider"></div>
        <a
          href="" 
          class="dropdown-item" 
          onclick="
            event.preventDefault();
            logout()">
          <i class="fas fa-power-off mr-2"></i>
          Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
<form method="POST" action="{{ route('logout') }}" id="logout-form">
  @csrf
</form>

@push('script')
  <script type="text/javascript">
    function logout() {
      Swal.fire({
        title: 'Are you sure to logout',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        allowOutsideClick: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return new Promise(() => document.getElementById('logout-form').submit()) 
        }
      })
    }
  </script>
@endpush
