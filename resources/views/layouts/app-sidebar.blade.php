<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Custom styles for sidebar layout */
            .main-content {
                margin-left: 250px;
                min-height: 100vh;
                transition: margin-left 0.3s;
            }
            
            @media (max-width: 768px) {
                .sidebar {
                    display: none;
                }
                .main-content {
                    margin-left: 0;
                }
            }

            .sidebar .nav-link:hover {
                background-color: rgba(255, 255, 255, 0.1);
                border-radius: 0.25rem;
            }

            .sidebar .dropdown-menu {
                position: static !important;
                transform: none !important;
                border: none;
                background-color: rgba(0, 0, 0, 0.2);
                margin-left: 1rem;
            }

            .sidebar .dropdown-toggle::after {
                float: right;
                margin-top: 8px;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-light">
        <!-- Include Sidebar Navigation -->
        @include('layouts.sidebar-navigation')

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="py-6 px-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>