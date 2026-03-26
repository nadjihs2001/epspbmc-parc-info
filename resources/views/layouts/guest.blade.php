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
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-slate-100 relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_10%_10%,#c7d2fe,transparent_32%),radial-gradient(circle_at_90%_20%,#ddd6fe,transparent_38%)]"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="w-full max-w-5xl grid lg:grid-cols-2 bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
                    <div class="hidden lg:flex flex-col justify-between p-10 text-white bg-[linear-gradient(160deg,#2f1f6b_0%,#4b3aa7_65%,#6259ca_100%)]">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-xl bg-white/15 grid place-items-center">
                                <x-application-logo class="w-8 h-8 fill-current text-white" />
                            </div>
                            <div>
                                <p class="font-semibold">EPSP BMC</p>
                                <p class="text-sm text-white/70">Parc Informatique</p>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-3xl font-semibold leading-tight">Gestion centralisée du parc informatique.</h1>
                            <p class="mt-3 text-white/80">Suivi des équipements, tickets, licences et réseau dans une seule interface.</p>
                        </div>
                        <div class="text-sm text-white/70">Interface inspirée du style Valex.</div>
                    </div>

                    <div class="p-6 sm:p-10">
                        <div class="mb-6 lg:hidden flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-700 grid place-items-center">
                                <x-application-logo class="w-6 h-6 fill-current" />
                            </div>
                            <div>
                                <p class="font-semibold">EPSP BMC</p>
                                <p class="text-xs text-slate-500">Parc Informatique</p>
                            </div>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
