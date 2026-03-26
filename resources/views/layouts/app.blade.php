<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased valex-body">
        <div class="valex-shell">
            <aside class="valex-sidebar" id="valexSidebar">
                <div class="valex-brand">
                    <div class="valex-brand-logo">
                        <x-application-logo class="h-7 w-7 fill-current text-white" />
                    </div>
                    <div>
                        <div class="valex-brand-title">EPSP BMC</div>
                        <div class="valex-brand-subtitle">Parc Informatique</div>
                    </div>
                </div>

                @include('layouts.navigation')
            </aside>

            <div class="valex-main">
                <header class="valex-topbar">
                    <button class="valex-menu-btn" id="valexMenuBtn" type="button" aria-label="Ouvrir le menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 3a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="valex-topbar-right">
                        <a class="valex-top-link" href="{{ route('profile.edit') }}">Profil</a>
                        <div class="valex-user-chip">
                            <span class="valex-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="valex-logout-btn">Déconnexion</button>
                        </form>
                    </div>
                </header>

                @isset($header)
                    <div class="valex-page-head">
                        {{ $header }}
                    </div>
                @endisset

                <main class="valex-content">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <div class="valex-overlay" id="valexOverlay"></div>
        @livewireScripts
        <script>
            (function () {
                const sidebar = document.getElementById('valexSidebar');
                const overlay = document.getElementById('valexOverlay');
                const menuBtn = document.getElementById('valexMenuBtn');

                if (!sidebar || !overlay || !menuBtn) {
                    return;
                }

                const closeMenu = () => {
                    sidebar.classList.remove('is-open');
                    overlay.classList.remove('is-open');
                };

                menuBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('is-open');
                    overlay.classList.toggle('is-open');
                });

                overlay.addEventListener('click', closeMenu);
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        closeMenu();
                    }
                });
            })();
        </script>
    </body>
</html>
