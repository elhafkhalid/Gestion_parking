<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen overflow-hidden bg-slate-100">

    <div class="flex h-full">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col">

            <div class="p-6 border-b border-slate-700">
                <h2 class="text-xl font-bold">ADMIN PANEL</h2>
            </div>

            <nav class="flex-1 p-4 space-y-2 text-sm">

                <a href="{{ route('admin.dashboard', ['section' => 'statistics']) }}"
                    class="block px-4 py-2 rounded
               {{ request('section', 'statistics') == 'statistics' ? 'bg-slate-800' : 'hover:bg-slate-800' }}">
                    📊 Statistiques
                </a>

                <a href="{{ route('admin.dashboard', ['section' => 'users']) }}"
                    class="block px-4 py-2 rounded
               {{ request('section') == 'users' ? 'bg-slate-800' : 'hover:bg-slate-800' }}">
                    👥 Utilisateurs
                </a>

                <a href="{{ route('admin.dashboard', ['section' => 'parkings']) }}"
                    class="block px-4 py-2 rounded
               {{ request('section') == 'parkings' ? 'bg-slate-800' : 'hover:bg-slate-800' }}">
                    🅿 Parkings
                </a>

            </nav>

            <div class="p-4 border-t border-slate-700 text-xs text-slate-400">
                © {{ date('Y') }}
            </div>

        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col">

            <div class="h-16 bg-white border-b flex items-center justify-between px-8">
                <div class="font-semibold text-lg">
                    Dashboard
                </div>

                <div class="flex items-center gap-4">
                    <span>{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-500 hover:underline">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>

            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </main>

        </div>

    </div>

</body>

</html>
