@if ( Gate::check('vendor') )
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview
            @if ( strpos( url()->current(), 'product' ) )
                menu-open 
            @endif">
            <a href="#" class="nav-link
                @if ( strpos( url()->current(), 'product' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-shopping-basket"></i>
                <p>
                    Products
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.product.approved') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'product/approved' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Approved Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.product.pending') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'product/pending' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pending Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.product.rejected') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'product/rejected' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Rejected Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.product.create') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'product/create' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Add Products</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('backoffice.vendor.order.index') }}" class="nav-link
                @if ( strpos( url()->current(), 'order' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-shopping-cart"></i>
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
                    <a href="{{ route('backoffice.vendor.booking.active') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/active' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Active Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.booking.complete') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/complete' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Complete Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.booking.pending') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/pending' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pending Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.booking.unserviced') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/unserviced' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Unserviced Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.vendor.booking.overdue') }}" class="nav-link 
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
            <a href="{{ route('backoffice.vendor.settings') }}" class="nav-link
                @if ( strpos( url()->current(), 'settings' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-cog"></i>
                <p>Account Settings</p>
            </a>
        </li>
    </ul>
@endif