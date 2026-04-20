<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkSys — Système de Gestion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] h-full flex flex-col text-slate-900">
    @auth
        <nav class="bg-slate-900 border-b border-slate-800 h-20 flex items-center shrink-0 z-50 sticky top-0 shadow-xl">
            <div class="container mx-auto px-6 flex justify-between items-center">

                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/50">
                        <span class="text-white font-black text-sm">P</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-xl font-black tracking-tighter text-white leading-none italic">
                            Park<span class="text-blue-500">Sys</span>
                        </h1>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">Management</span>
                    </div>
                </div>

                <div class="flex items-center gap-8">

                    <div class="hidden sm:flex items-center gap-3 pr-6 border-r border-slate-800">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">
                                Session active</p>
                            <p class="text-sm font-extrabold text-white tracking-tight">{{ auth()->user()->name }}</p>
                        </div>
                        <div
                            class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center border-2 border-slate-700 shadow-sm">
                            <span class="text-slate-300 font-black text-xs">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="group flex items-center gap-2 bg-white text-slate-900 hover:bg-red-600 hover:text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md active:scale-95">
                                <span>Quitter</span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5 group-hover:translate-x-1 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </nav>
    @endauth

    <main class="flex-1 relative">
        <div class="absolute inset-0 overflow-y-auto">
            @yield('content')
        </div>
    </main>

</body>

</html>
