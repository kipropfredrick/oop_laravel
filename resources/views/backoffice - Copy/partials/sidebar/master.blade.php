<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('image/logo.png') }}"
         class="brand-image elevation-3"
         style="opacity: .8">
        <span class="brand-text font-weight-light" style="font-size: .9em;">
            {{ config('app.name') }}
        </span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            @include('backoffice.partials.sidebar.vendor')
            @include('backoffice.partials.sidebar.admin')
            @include('backoffice.partials.sidebar.customer')
        </nav>
        <!-- /.sidebar-menu -->
    </div>
</aside>