@extends('layouts.app')

@section('title', 'Contacto - CooperativaLiberté')

@section('content')

    <div class="bg-dark text-white py-16 px-[5%] text-center border-b-[5px] border-accent-500">
        <h1 class="text-[2.5rem] font-bold mb-2">Contacto</h1>
        <p class="text-lg opacity-80">Ponete en contacto con la cooperativa</p>
    </div>

    <section class="py-16 px-[5%]">
        <div class="max-w-[1000px] mx-auto flex flex-col lg:flex-row gap-12">
            {{-- Info --}}
            <div class="flex-1">
                <h2 class="text-primary-500 text-2xl font-bold mb-6">Datos de contacto</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-primary-500 text-sm font-semibold uppercase tracking-wider">Dirección</h3>
                        <p class="mt-1 text-gray-600">Unidad Penal N.&deg; 15 - Batán<br>Mar del Plata, Buenos Aires<br>Argentina</p>
                    </div>
                    <div>
                        <h3 class="text-primary-500 text-sm font-semibold uppercase tracking-wider">Redes sociales</h3>
                        <div class="mt-2 space-y-1">
                            <p class="text-gray-600">Instagram: @cooperativaliberte</p>
                            <p class="text-gray-600">Facebook: Cooperativa Liberté</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-primary-500 text-sm font-semibold uppercase tracking-wider">Sitios hermanos</h3>
                        <div class="mt-2 space-y-1">
                            <p><a href="#" class="text-accent-500 font-medium hover:underline">Universidad Liberté</a></p>
                            <p><a href="#" class="text-accent-500 font-medium hover:underline">Taller Solidario Liberté</a></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="flex-1 bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-primary-500 text-2xl font-bold mb-6">Envianos un mensaje</h2>
                <form class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-accent-500 focus:border-accent-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-accent-500 focus:border-accent-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                        <textarea rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-accent-500 focus:border-accent-500 outline-none transition"></textarea>
                    </div>
                    <button type="submit" class="bg-accent-500 text-white font-bold px-8 py-3 rounded-md shadow-lg hover:bg-accent-600 hover:-translate-y-0.5 transition-all">
                        Enviar mensaje
                    </button>
                </form>
            </div>
        </div>
    </section>

@endsection
