<div class="sidebar bg-dark text-white vh-100 position-fixed" style="width: 250px; z-index: 1000;">
    <div class="p-3">
        <!-- Logo/Brand -->
        <div class="mb-4 pb-3 border-bottom">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                <h4 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Installment System
                </h4>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="nav flex-column">
            <!-- Dashboard -->
            <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary rounded' : '' }}" 
               href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>

            <!-- Products -->
            <div class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                   href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-box me-2"></i> Products
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="{{ route('products.index') }}">
                        <i class="fas fa-list me-1"></i> All Products
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('products.create') }}">
                        <i class="fas fa-plus me-1"></i> Add Product
                    </a></li>
                </ul>
            </div>

            <!-- Customers -->
            <div class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('customers.*') ? 'active' : '' }}" 
                   href="#" id="customersDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-users me-2"></i> Customers
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="{{ route('customers.index') }}">
                        <i class="fas fa-list me-1"></i> All Customers
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('customers.create') }}">
                        <i class="fas fa-user-plus me-1"></i> Add Customer
                    </a></li>
                </ul>
            </div>

            <!-- Installment Plans -->
            <div class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('installment-plans.*') ? 'active' : '' }}" 
                   href="#" id="installmentDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-file-contract me-2"></i> Installments
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="{{ route('installment-plans.index') }}">
                        <i class="fas fa-list me-1"></i> All Plans
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('installment-plans.create') }}">
                        <i class="fas fa-plus me-1"></i> Create Plan
                    </a></li>
                </ul>
            </div>

            <!-- Payments -->
            <div class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('payments.*') ? 'active' : '' }}" 
                   href="#" id="paymentsDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-money-bill-wave me-2"></i> Payments
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="{{ route('payments.index') }}">
                        <i class="fas fa-list me-1"></i> All Payments
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('payments.create') }}">
                        <i class="fas fa-plus me-1"></i> Record Payment
                    </a></li>
                </ul>
            </div>

            <!-- Reports -->
            <a class="nav-link text-white {{ request()->routeIs('reports.*') ? 'active bg-primary rounded' : '' }}" 
               href="{{ route('reports.index') }}">
                <i class="fas fa-chart-bar me-2"></i> Reports
            </a>

            <!-- Divider -->
            <hr class="my-3 border-secondary">

            <!-- User Section -->
            @auth
            <div class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle" 
                   href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-cog me-1"></i> Profile Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </nav>
    </div>
</div>

<!-- Mobile Toggle Button -->
<button class="btn btn-dark d-md-none position-fixed" 
        style="top: 10px; left: 10px; z-index: 1001;"
        type="button" 
        data-bs-toggle="offcanvas" 
        data-bs-target="#mobileSidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Mobile Sidebar (Offcanvas) -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">
            <i class="fas fa-chart-line me-2"></i>
            Installment System
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Same navigation content as desktop sidebar -->
        <nav class="nav flex-column">
            <!-- Dashboard -->
            <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary rounded' : '' }}" 
               href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>

            <!-- Products -->
            <div class="mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileProducts">
                    <i class="fas fa-box me-2"></i> Products
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->routeIs('products.*') ? 'show' : '' }}" id="mobileProducts">
                    <div class="ps-3">
                        <a class="nav-link text-white-50" href="{{ route('products.index') }}">
                            <i class="fas fa-list me-1"></i> All Products
                        </a>
                        <a class="nav-link text-white-50" href="{{ route('products.create') }}">
                            <i class="fas fa-plus me-1"></i> Add Product
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customers -->
            <div class="mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileCustomers">
                    <i class="fas fa-users me-2"></i> Customers
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->routeIs('customers.*') ? 'show' : '' }}" id="mobileCustomers">
                    <div class="ps-3">
                        <a class="nav-link text-white-50" href="{{ route('customers.index') }}">
                            <i class="fas fa-list me-1"></i> All Customers
                        </a>
                        <a class="nav-link text-white-50" href="{{ route('customers.create') }}">
                            <i class="fas fa-user-plus me-1"></i> Add Customer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Installment Plans -->
            <div class="mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileInstallments">
                    <i class="fas fa-file-contract me-2"></i> Installments
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->routeIs('installment-plans.*') ? 'show' : '' }}" id="mobileInstallments">
                    <div class="ps-3">
                        <a class="nav-link text-white-50" href="{{ route('installment-plans.index') }}">
                            <i class="fas fa-list me-1"></i> All Plans
                        </a>
                        <a class="nav-link text-white-50" href="{{ route('installment-plans.create') }}">
                            <i class="fas fa-plus me-1"></i> Create Plan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobilePayments">
                    <i class="fas fa-money-bill-wave me-2"></i> Payments
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->routeIs('payments.*') ? 'show' : '' }}" id="mobilePayments">
                    <div class="ps-3">
                        <a class="nav-link text-white-50" href="{{ route('payments.index') }}">
                            <i class="fas fa-list me-1"></i> All Payments
                        </a>
                        <a class="nav-link text-white-50" href="{{ route('payments.create') }}">
                            <i class="fas fa-plus me-1"></i> Record Payment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reports -->
            <a class="nav-link text-white {{ request()->routeIs('reports.*') ? 'active bg-primary rounded' : '' }}" 
               href="{{ route('reports.index') }}">
                <i class="fas fa-chart-bar me-2"></i> Reports
            </a>

            <!-- Divider -->
            <hr class="my-3 border-secondary">

            <!-- User Section -->
            @auth
            <div class="mb-2">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileUser">
                    <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name }}
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="mobileUser">
                    <div class="ps-3">
                        <a class="nav-link text-white-50" href="{{ route('profile.edit') }}">
                            <i class="fas fa-cog me-1"></i> Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
        </nav>
    </div>
</div>