@extends('layouts.app')

@section('content')
    
    <div class="h-screen w-full flex overflow-hidden">
        
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">
            

            <img src="https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=1200&q=80" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40">

           
            <div class="relative z-10 p-16 flex flex-col justify-between text-white">
                <h1 class="text-3xl font-bold ">Parking Systeme</h1>
                
                <div>
                    <h2 class="text-5xl font-extrabold leading-tight mb-4">
                        Simplifiez la gestion <br> de votre parking.
                    </h2>
                    <p class="text-slate-300 text-xl">Une interface claire pour un suivi en temps réel.</p>
                </div>

                <p class="text-slate-500 text-sm">© 2024 Système de Gestion de Parking</p>
            </div>
        </div>

  
        <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8">
            
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Connexion</h2>

                
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border-l-4 border-red-500">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Adresse Email</label>
                        <input type="email" name="email" placeholder="admin@parksys.com"
                            class="w-full border border-gray-300 p-3 rounded-xl" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full border border-gray-300 p-3 rounded-xl" required>
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl ">
                        Se connecter au Dashboard
                    </button>
                </form>

                <p class="mt-8 text-center text-gray-500 text-sm">
                    Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">S'inscrire</a>
                </p>
            </div>
        </div>

    </div>
@endsection