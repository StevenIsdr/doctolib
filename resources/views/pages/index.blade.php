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
                            votre <br>meilleur article <br><span
                                id="test" class="text-cyan-500"></span><br><span id="2" class="hidden">pour votre blog, SEO friendly, non dupliqué</span>
                        </h1>
                        <p class="text-cyan-900 md:text-xl md:pr-20 text-justify">
                            Grace a ses rédacteurs certifié et vérifié, trouvez les articles dont votre blog a besoin,
                            SEO friendly, avec du contenu unique et informatif et de qualité !
                        </p>

                        <a href="/articles"
                           class="mt-6 hover:scale-105 transition mb-12 md:mb-0 md:mt-10 font-maven font-bold tracking-wide inline-block py-3 px-8 text-white bg-cyan-500 rounded-lg shadow">Voir les articles</a>
                    </div>

                    <div class="md:w-1/2 flex items-center justify-center">
                        <img class="lg:max-h-full max-h-96" src="/images/marketplace_article_blog.png">
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

    <div class="px-4 py-12 bg-gray-800" x-data="{ modelOpen: false }">
        <h2 class="text-2xl sm:text-4xl font-maven text-white font-bold text-center">Les derniers articles</h2>
        <div class="md:max-w-6xl md:mx-auto ">
            <div class="relative m-3 flex flex-wrap justify-center lg:justify-between">
            </div>
        </div>

        <div x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                <div x-cloak @click="modelOpen = false" x-show="modelOpen"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-40" aria-hidden="true"
                ></div>

                <div x-cloak x-show="modelOpen"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl"
                >
                    <div class="flex items-center justify-between space-x-4">
                        <h1 class="text-xl font-bold font-maven text-gray-800">Connectez vous</h1>

                        <button @click="modelOpen = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>
                    </div>

                    <p class="mt-2 text-sm text-gray-800 ">
                        Afin de commandez un article, vous devez tout d'abords vous authentifier
                    </p>

                    <div class="flex flex-wrap gap-6 justify-center">
                        <a href="/login"
                           class="mt-6 mb-12 md:mb-0 md:mt-10 font-maven font-bold tracking-wide inline-block py-3 px-8 text-white bg-cyan-500 rounded-lg shadow hover:scale-105 transition">Connexion</a>
                        <a href="/register"
                           class="mt-6 mb-12 md:mb-0 md:mt-10 font-maven font-bold tracking-wide inline-block py-3 px-8 text-white bg-cyan-500 rounded-lg shadow hover:scale-105 transition">Inscription</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-11/12 mx-auto pt-8 lg:w-3/5 mb-12">
        <h2 class="font-bold font-maven flex mx-auto text-gray-800 text-center text-3xl sm:text-5xl w-2/3">Trouvez le texte le plus
            adapté à votre blog</h2>
        <p class="font-maven text-gray-800gray-xl flex mx-auto text-gray-800 text-center text-base sm:text-lg w-2/3 mt-3">Les textes sont
            soumis à une validation manuelle afin de vérifier si les articles sont bien optimisés pour le SEO, non
            dupliqués et non générés par une IA !</p>
        <div class="grid sm:grid-cols-2 grid-cols-1 gap-6 mt-12">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 text-cyan-500 mb-8" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="font-maven font-bold text-2xl text-gray-800 mb-4">Articles de qualité</h3>
                <p class="text-justify text-gray-800">Nous nous engageons à vous fournir un espace permettant d'acquérir des textes de qualité, rédigés par
                    des rédacteurs certifiés par notre équipe.</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 text-cyan-500 mb-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <h3 class="font-maven font-bold text-2xl text-gray-800 mb-4">Articles non dupliqué</h3>
                <p class="text-justify text-gray-800">Notre équipe fait aussi en sorte de vérifier si les articles ne possèdent pas de contenu dupliqué afin que vous puissiez poster du contenu unique sur votre site internet.</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 text-cyan-500 mb-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <h3 class="font-maven font-bold text-2xl text-gray-800 mb-4">Pour les rédacteurs</h3>
                <p class="text-justify text-gray-800">Nous permettons à nos rédacteurs de mieux rémunérer leur contenu avec notre système de marketplace complet.
                </p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 text-cyan-500 mb-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <h3 class="font-maven font-bold text-2xl text-gray-800 mb-4">Pour les Webmasters</h3>
                <p class="text-justify text-gray-800">Notre marketplace vous permet de trouver des articles adaptés à votre site internet afin d’augmenter naturellement votre trafic.</p>
            </div>
        </div>
    </div>

@endsection
