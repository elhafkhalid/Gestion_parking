@extends('layouts.app')

@section('content')
    <div class="h-screen w-full flex flex-col lg:flex-row-reverse overflow-hidden">

        {{-- IMAGE (DROITE) --}}
        <div class="relative w-full lg:w-[40%] h-80 lg:h-screen lg:sticky lg:top-0 bg-indigo-900 overflow-hidden">

            <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?q=80&w=2070&auto=format&fit=crop"
                alt="Visiteur" class="absolute inset-0 w-full h-full object-cover opacity-50">

            {{-- Gradient adapté pour image à droite --}}
            <div class="absolute inset-0 bg-gradient-to-l from-indigo-950 via-transparent to-transparent"></div>

            <div class="absolute inset-0 flex flex-col justify-end p-10 md:p-16 text-white">
                <span class="w-12 h-1.5 bg-indigo-500 mb-6 rounded-full"></span>

                <h2 class="text-5xl font-black tracking-tighter uppercase leading-[0.9] mb-4">
                    Mobilité <br><span class="text-indigo-400 italic">Connectée</span>
                </h2>

                <p class="text-slate-300 text-lg font-medium max-w-xs leading-relaxed">
                    Trouvez votre place en un clic et simplifiez vos déplacements urbains.
                </p>
            </div>
        </div>

        {{-- CONTENU (GAUCHE) --}}
        <div class="w-full lg:w-[60%] bg-white flex items-center justify-center p-8">

            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Connexion</h2>

                {{-- ERREURS --}}
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border-l-4 border-red-500">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- FORMULAIRE --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Adresse Email</label>
                        <input type="email" name="email" placeholder="admin@parksys.com"
                            class="w-full border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl transition">
                        Se connecter au Dashboard
                    </button>
                </form>

                <p class="mt-8 text-center text-gray-500 text-sm">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">
                        S'inscrire
                    </a>
                </p>
            </div>

        </div>

    </div>
@endsection
