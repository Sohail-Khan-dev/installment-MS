<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <!-- Products Dropdown -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('products.*') ? 'text-gray-900' : '' }}">
                                    <i class="fas fa-box me-1"></i> {{ __('Products') }}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('products.index')">
                                    <i class="fas fa-list me-1"></i> {{ __('All Products') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('products.create')">
                                    <i class="fas fa-plus me-1"></i> {{ __('Add Product') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    <!-- Customers Dropdown -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('customers.*') ? 'text-gray-900' : '' }}">
                                    <i class="fas fa-users me-1"></i> {{ __('Customers') }}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('customers.index')">
                                    <i class="fas fa-list me-1"></i> {{ __('All Customers') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('customers.create')">
                                    <i class="fas fa-user-plus me-1"></i> {{ __('Add Customer') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    <!-- Installment Plans Dropdown -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('installment-plans.*') ? 'text-gray-900' : '' }}">
                                    <i class="fas fa-file-contract me-1"></i> {{ __('Installments') }}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('installment-plans.index')">
                                    <i class="fas fa-list me-1"></i> {{ __('All Plans') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('installment-plans.create')">
                                    <i class="fas fa-plus me-1"></i> {{ __('Create Plan') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    <!-- Payments Dropdown -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('payments.*') ? 'text-gray-900' : '' }}">
                                    <i class="fas fa-money-bill-wave me-1"></i> {{ __('Payments') }}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('payments.index')">
                                    <i class="fas fa-list me-1"></i> {{ __('All Payments') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('payments.create')">
                                    <i class="fas fa-plus me-1"></i> {{ __('Record Payment') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    <!-- Reports -->
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        <i class="fas fa-chart-bar me-1"></i> {{ __('Reports') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <!-- Products Section -->
            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    {{ __('Products') }}
                </div>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                    <i class="fas fa-list me-1"></i> {{ __('All Products') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.create')" :active="request()->routeIs('products.create')">
                    <i class="fas fa-plus me-1"></i> {{ __('Add Product') }}
                </x-responsive-nav-link>
            </div>
            
            <!-- Customers Section -->
            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    {{ __('Customers') }}
                </div>
                <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')">
                    <i class="fas fa-list me-1"></i> {{ __('All Customers') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customers.create')" :active="request()->routeIs('customers.create')">
                    <i class="fas fa-user-plus me-1"></i> {{ __('Add Customer') }}
                </x-responsive-nav-link>
            </div>
            
            <!-- Installment Plans Section -->
            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    {{ __('Installment Plans') }}
                </div>
                <x-responsive-nav-link :href="route('installment-plans.index')" :active="request()->routeIs('installment-plans.index')">
                    <i class="fas fa-list me-1"></i> {{ __('All Plans') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('installment-plans.create')" :active="request()->routeIs('installment-plans.create')">
                    <i class="fas fa-plus me-1"></i> {{ __('Create Plan') }}
                </x-responsive-nav-link>
            </div>
            
            <!-- Payments Section -->
            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    {{ __('Payments') }}
                </div>
                <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                    <i class="fas fa-list me-1"></i> {{ __('All Payments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payments.create')" :active="request()->routeIs('payments.create')">
                    <i class="fas fa-plus me-1"></i> {{ __('Record Payment') }}
                </x-responsive-nav-link>
            </div>
            
            <!-- Reports Section -->
            <div class="border-t border-gray-200 pt-2">
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    <i class="fas fa-chart-bar me-1"></i> {{ __('Reports') }}
                </x-responsive-nav-link>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
