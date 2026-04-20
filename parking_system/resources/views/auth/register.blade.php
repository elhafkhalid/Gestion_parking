@extends('layouts.app')

@section('content')
    <div class="h-screen w-full flex overflow-hidden">


        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">


            <img src="https://images.unsplash.com/photo-1573348722427-f1d6819fdf98?w=1200&q=80"
                class="absolute inset-0 w-full h-full object-cover opacity-40">

            <div class="relative z-10 p-16 flex flex-col justify-between text-white">
                <h1 class="text-3xl font-bold tracking-tight text-emerald-400">ParkSys</h1>

                <div>
                    <h2 class="text-5xl font-extrabold leading-tight mb-4">
                        Rejoignez l'aventure <br> ParkSys.
                    </h2>
                    <p class="text-slate-300 text-xl">Créez votre compte en quelques secondes et commencez à gérer vos
                        places.</p>
                </div>

                <p class="text-slate-500 text-sm">© {{ date('Y') }} Système de Gestion de Parking</p>
            </div>
        </div>


        <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8">

            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Créer un compte</h2>


                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border-l-4 border-red-500 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                
                <form method="POST" action="{{ route('register') }}" class="space-y-4">

                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nom complet</label>
                        <input type="text" name="name" placeholder="Jean Dupont"
                            class="w-full border border-gray-300 p-3 rounded-xl " required>
                    </div>


                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Adresse Email</label>
                        <input type="email" name="email" placeholder="jean@exemple.com"
                            class="w-full border border-gray-300 p-3 rounded-xl " required>
                    </div>


                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full border border-gray-300 p-3 rounded-xl " required>
                    </div>


                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full border border-gray-300 p-3 rounded-xl " required>
                    </div>


                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl mt-4">
                        Créer mon compte
                    </button>
                </form>

                <p class="mt-8 text-center text-gray-500 text-sm">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:underline">Se connecter</a>
                </p>
            </div>
        </div>

    </div>
@endsection
