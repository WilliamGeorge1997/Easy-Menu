<!DOCTYPE html>
@php
    $locale = config('app.locale');
@endphp
<html lang="{{ $locale }}" class="light-style layout-menu-fixed" dir="{{ $locale == 'ar' ? 'rtl' : 'ltr' }}"
    data-theme="theme-default" data-assets-path="{{ asset('dashboard/assets/') }}"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description"
        content="Admin Dashboard for {{ config('app.name') }}. Manage menus, users, and view analytics with ease in your restaurant or cafe." />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('dashboard\assets\img\favicon\favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <title>{{ __('app.name') }} - @yield('title')</title>
    @include('common::includes.css')
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">

                {{-- Navbar --}}
                @include('common::includes.navbar')
                {{-- Aside --}}
                @include('common::includes.sidebar')
                {{-- Content --}}
                @yield('content')

                {{-- Footer --}}
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl">
                        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                &#169; <script>document.write(new Date().getFullYear())</script>,
                                made with ❤️ by <a href="#" target="_blank" class="footer-link">Coudex Solutions</a>
                            </div>
                        </div>
                    </div>
                </footer>

            </div>
        </div>
    </div>

    @include('common::includes.js')
    @stack('scripts')
</body>

</html>
