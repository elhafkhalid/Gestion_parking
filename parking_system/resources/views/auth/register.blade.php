@extends('layouts.app')

@section('content')
    <div class="h-screen w-full flex flex-col lg:flex-row-reverse overflow-hidden">

      
        <div class="relative w-full lg:w-[40%] h-80 lg:h-screen lg:sticky lg:top-0 bg-indigo-900 overflow-hidden">

            <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?q=80&w=2070&auto=format&fit=crop"
                alt="Visiteur" class="absolute inset-0 w-full h-full object-cover opacity-50">

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

        
        <div class="w-full lg:w-[60%] bg-white flex items-center justify-center p-8">

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
                        <input type="text" name="name" placeholder="khalid"
                            class="w-full border border-gray-300 p-3 rounded-xl" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Adresse Email</label>
                        <input type="email" name="email" placeholder="khalid@exemple.com"
                            class="w-full border border-gray-300 p-3 rounded-xl" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full border border-gray-300 p-3 rounded-xl" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full border border-gray-300 p-3 rounded-xl" required>
                    </div>

                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl mt-4">
                        Créer mon compte
                    </button>
                </form>

                <p class="mt-8 text-center text-gray-500 text-sm">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:underline">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>

    </div>
@endsection
