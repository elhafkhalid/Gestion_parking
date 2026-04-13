<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkSys — Système de Gestion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Empêche le scroll global pour garder le layout fixe */
        body {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-slate-50 h-full flex flex-col">

    {{-- BARRE DE NAVIGATION --}}
    @auth
        <nav class="bg-white border-b border-gray-200 h-16 flex items-center shrink-0 z-50">
            <div class="container mx-auto px-6 flex justify-between items-center">

                {{-- Logo --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-black text-xs">P</span>
                    </div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800 italic">
                        Park<span class="text-blue-600">Sys</span>
                    </h1>
                </div>

                {{-- Droite : Profil & Logout --}}
                <div class="flex items-center gap-6">
                    <div class="hidden md:block text-right">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Utilisateur</p>
                        <p class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="flex items-center gap-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold transition-all border border-transparent hover:border-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Quitter
                        </button>
                    </form>
                </div>

            </div>
        </nav>
    @endauth

    {{-- ZONE DE CONTENU PRINCIPAL --}}
    {{-- On utilise flex-1 pour qu'il prenne tout l'espace sous la nav --}}
    <main class="flex-1 overflow-hidden relative">
        @yield('content')
    </main>

</body>

</html>
