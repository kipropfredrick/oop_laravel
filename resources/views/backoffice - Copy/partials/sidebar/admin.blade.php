@if ( Gate::check('admin') )
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview
            @if ( strpos( url()->current(), 'product' ) or strpos( url()->current(), 'category' ) )
                menu-open 
            @endif">
            <a href="#" class="nav-link
                @if ( strpos( url()->current(), 'product' ) or strpos( url()->current(), 'category' ) )
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
                    <a href="{{ route('backoffice.admin.category.index') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'category' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.product.pending') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'product/pending' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pending Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.product.approved') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'product/approved' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Approved Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.product.rejected') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'product/rejected' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Rejected Products</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview
            @if ( strpos( url()->current(), 'order' ) )
                menu-open 
            @endif">
            <a href="#" class="nav-link
                @if ( strpos( url()->current(), 'order' ) )
                    active 
                @endif">
                <i class="nav-icon fasfa-shopping-cart"></i>
                <p>
                    Orders
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.order.complete') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'order/complete' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Complete Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.order.pending') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'order/pending' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pending orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.order.shipping-paid') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'order/shipping-paid' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Shipping Paid orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.order.delivered') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'order/delivered' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Delivered orders</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview
            @if ( strpos( url()->current(), 'booking' ) )
                menu-open 
            @endif">
            <a href="#" class="nav-link
                @if ( strpos( url()->current(), 'booking' ) )
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
                    <a href="{{ route('backoffice.admin.booking.active') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/active' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Active Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.booking.complete') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/complete' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Complete Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.booking.pending') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/pending' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pending Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.booking.unserviced') }}" class="nav-link 
                        @if ( strpos( url()->current(), 'booking/unserviced' ) )
                            active 
                        @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Unserviced Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backoffice.admin.booking.overdue') }}" class="nav-link 
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
            <a href="{{ route('backoffice.admin.county.index') }}" class="nav-link
                @if ( strpos( url()->current(), 'county' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-globe"></i>
                <p>Counties</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('backoffice.admin.nairobi_zone.index') }}" class="nav-link
                @if ( strpos( url()->current(), 'nairobi-zone' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-map-signs"></i>
                <p>Nairobi Zones</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('backoffice.admin.customer.index') }}" class="nav-link
                @if ( strpos( url()->current(), 'customer' ) )
                    active 
                @endif">
                <i class="nav-icon fas fa-users"></i>
                <p>Customers</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('backoffice.admin.vendor.index') }}" class="nav-link
                @if ( strpos( url()->current(), 'customer' ) )
                    active 
                @endif">
                <i class="nav-icon fa fa-address-book-o"></i>
                <p>Vendors</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('backoffice.admin.payments.index') }}" class="nav-link
                @if ( strpos( url()->current(), 'payments' ) )
                    active 
                @endif">
                <i class="nav-icon fa fa-list"></i>
                <p>Payment Logs</p>
            </a>
        </li>

    </ul>
@endif