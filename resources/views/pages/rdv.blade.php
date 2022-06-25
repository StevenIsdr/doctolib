@extends('layouts.layout')
@section('content')
    <div class="mt-24"></div>
    <title>Prendre RDV</title>
    <div x-data="{step : 0, timestamp : '0'}" class="bg-white rounded-lg shadow p-4 lg:max-w-4xl sm:max-w-2xl  mx-auto">
        <div x-show="step === 0">
            <h2 class="text-xl font-bold text-center font-maven">Choisissez votre horaire</h2>
            <livewire:horaires/>
        </div>
        <div x-show="step === 1"
             x-transition:enter="transition ease-in-out duration-1000 transform"
             x-transition:enter-start="-translate-x-5 opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in-out duration-1000"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <form action="/rdv/confirmer" method="post">
                @csrf
                <h2 class="text-xl font-bold text-center font-maven">Détailler votre demande</h2>
                <input type="hidden" name="timestamp" x-model="timestamp">
                <label for="raison">Raison du rendez vous</label><br>
                <select name="raison" class="w-full rounded-lg" id="raison">
                    <option value="Medecine généraliste ponctuelle">Medecine généraliste ponctuelle</option>
                    <option value="Demande de Vaccin">Demande de Vaccin</option>
                    <option value="Visite médicale pour embauche">Visite médicale pour embauche</option>
                    <option value="Vérification ou consultation ponctuelle">Vérification ou consultation ponctuelle</option>
                </select>
                <div class="mt-3"></div>
                <label for="rdv" class="font-bold font-maven ">Détails de votre rendez-vous</label><br>
                <textarea id="rdv" class="rounded-lg w-full" name="rdv"></textarea>
                <button class="flex justify-center w-auto hover:scale-105 transition bg-cyan-600 rounded-full mt-3 text-white font-montserrat cursor-pointer font-bold px-1 py-2 min-w-[10rem] mx-auto" type="submit">Soumettre la demande de rendez vous.</button>
            </form>
        </div>

    </div>

@endsection
