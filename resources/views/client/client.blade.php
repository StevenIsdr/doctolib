@extends('client.layouts.client-layout')
@extends('layouts.layout')

@section('client-content')
    <title>Mon Espace Patient - Xenodoc</title>
    @foreach($rdvs as $rdv)
        <div
            class="cursor-pointer bg-light w-auto shadow-xl rounded-lg bg-white p-2 mb-2"
            x-data="{open : false}" x-on:click="open = !open">
            <div class="">
                <div>
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="flex flex-wrap items-center gap-2"><h1
                                class="font-bold font-montserrat tracking-wide text-xl"><span class="text-blue-600">[#{{$rdv['id']}}]</span>
                                Demande de rendez
                                vous</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600"
                                 viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div x-show="open"
                     x-transition:enter="transition duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition duration-300"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90">
                    <p class="font-montserrat font-bold tracking-wide text-sm">Status de la demande:</p>
                    @if($rdv->status == 1)
                        <form action="/cancel/{{$rdv->id}}" method="post">
                            @csrf
                            <p class="font-montserrat font-bold tracking-wide text-sm text-green-600">Accepté</p>
                            <p class="font-montserrat font-bold tracking-wide text-sm text-gray-600">{{ Carbon\Carbon::create($rdv->date)->locale('fr_FR')->isoFormat('LLLL')}}</p>
                            <input type="hidden" value="{{$rdv->id}}" name="cancel">
                            <button class="flex flex-row p-2 hover:scale-105 transition rounded-lg bg-red-600 text-white mx-auto font-bold">Annuler le rendez-vous</button>
                        </form>
                    @elseif($rdv->status == 2)
                        <p class="font-montserrat font-bold tracking-wide text-sm text-green-600">Terminé</p>
                        <p class="font-montserrat font-bold tracking-wide text-sm text-gray-600">{{ Carbon\Carbon::create($rdv->date)->locale('fr_FR')->isoFormat('LLLL')}}</p>
                    @elseif($rdv->status == -1)
                        <p class="font-montserrat font-bold tracking-wide text-sm text-red-600">Refusé</p>
                        <p class="font-montserrat font-bold tracking-wide text-sm text-gray-600">{{ Carbon\Carbon::create($rdv->date)->locale('fr_FR')->isoFormat('LLLL')}}</p>
                    @else
                        <form action="/cancel/{{$rdv->id}}" method="post">
                            @csrf
                            <p class="font-montserrat font-bold tracking-wide text-sm text-gray-600">En Attente
                                d'acceptation...</p>
                            <p class="font-montserrat font-bold tracking-wide text-sm text-gray-600">{{ Carbon\Carbon::create($rdv->date)->locale('fr_FR')->isoFormat('LLLL')}}</p>
                            <input type="hidden" value="{{$rdv->id}}" name="cancel">
                            <button class="flex flex-row p-2 hover:scale-105 transition rounded-lg bg-red-600 text-white mx-auto font-bold ">Annuler le rendez-vous</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endsection
