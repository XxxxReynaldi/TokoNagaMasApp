<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>

        <!-- Fonts and Css Default -->
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}
        {{-- <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> --}}
        <!-- End Fonts and Css Default -->

        <!-- Fonts and icons   -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
        <!-- Nucleo Icons -->
        <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        <!-- Material Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
        <!-- CSS Files -->
        <link id="pagestyle" href="{{ asset('css/material-dashboard.css?v=3.0.5') }}" rel="stylesheet" />
        <!-- Nepcha Analytics (nepcha.com) -->
        <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
        <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
        
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

        {{-- Datatable --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="{{ asset('css/picture.css') }}">

        {{-- Select2 --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <style>
            .sidenav {
                z-index: 1;
            }
        </style>

        <!-- Styles -->
        {{-- @livewireStyles --}}

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <!--   Core JS Files   -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <script src="{{ asset('js/core/popper.min.js') }}"></script>
        <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
        {{-- <script src="{{ asset('js/core/bootstrap.bundle.min.js') }}"></script> --}}
        
        <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        
    </head>
    <body class="g-sidenav-show bg-gray-200">
        {{-- <x-jet-banner /> --}}

            {{-- @livewire('navigation-menu') --}}

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            @include('includes.navbar')
            <!-- Page Content -->
            <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
                {{-- {{ $slot }} --}}
                @yield('content')
            </main>
        

        @stack('scripts')
        @stack('modals')

        {{-- @livewireScripts --}}
        <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
        <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
            damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
        </script>
        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ asset('js/material-dashboard.min.js?v=3.0.5') }}"></script>
    </body>
</html>
