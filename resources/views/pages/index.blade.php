@extends('layouts.layout')
@section('content')
    <title>BIEDOC</title>
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <div class="md:overflow-hidden mt-12">
        <div class="px-4 py-20 md:py-4">
            <div class="w-11/12 mx-auto pt-8 lg:w-4/5 xl:w-3/5 mb-12">
                <div class="md:flex md:flex-wrap mb-12">
                    <div class="md:w-1/2 text-center md:text-left md:pt-16">
                        <h1 class="sm:text-6xl text-4xl md:text-5xl leading-tight mb-4 font-maven font-extrabold text-gray-800">Trouvez
                            un <br>docteur <br><span
                                id="test" class="text-cyan-500"></span><br><span id="2" class="hidden">Qualifié, Rapidement, Simplement</span>
                        </h1>
                        <p class="text-cyan-900 md:text-xl md:pr-20 text-justify">
                            Zenodoc permet à nos utilisateurs de pouvoir prendre rendez-vous dans les plus bref délais pour toutes vos demandes de médecine à domicile.
                        </p>

                        <a href="/rdv"
                           class="mt-6 hover:scale-105 transition mb-12 md:mb-0 md:mt-10 font-maven font-bold tracking-wide inline-block py-3 px-8 text-white bg-cyan-500 rounded-lg shadow">Prendre un Rendez Vous</a>
                    </div>

                    <div class="md:w-1/2 flex items-center justify-center">
                        <img class="lg:max-h-full max-h-96" src="/images/index.png">
                    </div>

                    <script>
                        if ($("#test").length == 1) {
                            var typed_strings = $("#2").text();
                            var typed = new Typed("#test", {
                                strings: typed_strings.split(", "),
                                typeSpeed: 50,
                                loop: true,
                                backDelay: 2000,
                                backSpeed: 30,
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="w-11/12 mx-auto pt-8 lg:w-3/5 mb-12">
        <h2 class="font-bold font-maven flex mx-auto text-gray-800 text-center text-3xl sm:text-5xl w-2/3">Trouvez un docteur rapidement</h2>
        <p class="font-maven text-gray-800gray-xl flex mx-auto text-gray-800 text-center text-base sm:text-lg w-2/3 mt-3">Zenodoc permet aux patients de trouver facilement un médecin et de prendre rendez-vous en ligne 24h/24 et 7j/7. Zenodoc permet également aux médecins de gérer leurs rendez-vous et de trouver de nouveaux patients.</p>
        <div class="grid sm:grid-cols-2 grid-cols-1 gap-6 mt-12">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 text-cyan-500 mb-8" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="font-maven font-bold text-2xl text-gray-800 mb-4">Docteurs qualifiés</h3>
                <p class="text-justify text-gray-800">Zenodoc est une plateforme française qui permet aux médecins et autres professionnels de santé de publier des annonces pour leurs consultations. Les médecins sont tenus de respecter les règles éthiques de la profession, mais il n'y a pas de vérification formelle de leur qualification.
                </p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 text-cyan-500 mb-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <h3 class="font-maven font-bold text-2xl text-gray-800 mb-4">Plateforme de qualité</h3>
                <p class="text-justify text-gray-800">Zenodoc est une application web qui permet aux médecins et aux patients de gérer leurs rendez-vous médicaux en ligne. Elle permet également aux médecins de trouver de nouveaux patients et de mieux gérer leur pratique. Zenodoc est une application très pratique pour les médecins et leur personnel, car elle leur permet de gagner du temps et de mieux gérer leurs rendez-vous.</p>
            </div>
        </div>
    </div>
@endsection
