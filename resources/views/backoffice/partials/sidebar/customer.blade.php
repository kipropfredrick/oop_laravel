@if ( Gate::check('customer') )
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ route('backoffice.customer.home') }}" class="nav-link
                @if ( url()->current() == route('backoffice.customer.home') )
                    active 
                @endif">
                <i class="nav-icon fas fa-home"></i>
                <p>Home</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('backoffice.customer.order.index') }}" class="nav-link
                @if ( strpos( url()->current(), 'order' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-shopping-basket"></i>
                <p>Orders</p>
            </a>
        </li>
        
        <li class="nav-item has-treeview
            @if ( strpos( url()->current(), 'booking' ) or strpos( url()->current(), 'category' ) )
                menu-open 
            @endif">
            <a href="#" class="nav-link
                @if ( strpos( url()->current(), 'booking' ) or strpos( url()->current(), 'category' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Bookings
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('backoffice.customer.booking.active') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/active' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Active Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.customer.booking.complete') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/complete' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Complete Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.customer.booking.pending') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/pending' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pending Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.customer.booking.unserviced') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/unserviced' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Unserviced Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.customer.booking.overdue') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/overdue' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Overdue Bookings</p>
                    </a>
                </li>
                
            </ul>
        </li>

        <li class="nav-item">
            <a href="{{ route('backoffice.customer.settings') }}" class="nav-link
                @if ( strpos( url()->current(), 'settings' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-cog"></i>
                <p>Account Settings</p>
            </a>
        </li>
    </ul>
@endif