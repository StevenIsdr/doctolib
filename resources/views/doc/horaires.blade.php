@extends('doc.layouts.doc-layout')
@extends('layouts.layout')

@section('doc-content')
    <head>
        <title>Mes Horaires - XENODOC</title>
    </head>



    </script>
    <div class="mb-6">
        <p class="text-2xl font-montserrat font-bold tracking-wide text-center">Selectionnez votre plage horaire</p>
        <p class="text-base font-montserrat font-bold text-gray text-center">Utilisez cette grille pour définir vos
            heure d'activité</p>
    </div>
    @if(!Auth::user()->GetGoogleClient())
        <a href="/sync">
            <button class=" flex font-montserrat font-bold tracking-wide py-2 px-3  border-2 border-red rounded-full mx-auto">
                <div class="flex flex-wrap items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 px-2" viewBox="186 38 76 76">
                        <path fill="#fff" d="M244 56h-40v40h40V56z"/>
                        <path fill="#EA4335" d="M244 114l18-18h-18v18z"/>
                        <path fill="#FBBC04" d="M262 56h-18v40h18V56z"/>
                        <path fill="#34A853" d="M244 96h-40v18h40V96z"/>
                        <path fill="#188038" d="M186 96v12c0 3.315 2.685 6 6 6h12V96h-18z"/>
                        <path fill="#1967D2" d="M262 56V44c0-3.315-2.685-6-6-6h-12v18h18z"/>
                        <path fill="#4285F4" d="M244 38h-52c-3.315 0 -6 2.685-6 6v52h18V56h40V38z"/>
                        <path fill="#4285F4"
                              d="M212.205 87.03c-1.495-1.01-2.53-2.485-3.095-4.435l3.47-1.43c.315 1.2.865 2.13 1.65 2.79.78.66 1.73.985 2.84.985 1.135 0 2.11-.345 2.925-1.035s1.225-1.57 1.225-2.635c0-1.09-.43-1.98-1.29-2.67-.86-.69-1.94-1.035-3.23-1.035h-2.005V74.13h1.8c1.11 0 2.045-.3 2.805-.9.76-.6 1.14-1.42 1.14-2.465 0 -.93-.34-1.67-1.02-2.225-.68-.555-1.54-.835-2.585-.835-1.02 0 -1.83.27-2.43.815a4.784 4.784 0 0 0 -1.31 2.005l-3.435-1.43c.455-1.29 1.29-2.43 2.515-3.415 1.225-.985 2.79-1.48 4.69-1.48 1.405 0 2.67.27 3.79.815 1.12.545 2 1.3 2.635 2.26.635.965.95 2.045.95 3.245 0 1.225-.295 2.26-.885 3.11-.59.85-1.315 1.5-2.175 1.955v.205a6.605 6.605 0 0 1 2.79 2.175c.725.975 1.09 2.14 1.09 3.5 0 1.36-.345 2.575-1.035 3.64s-1.645 1.905-2.855 2.515c-1.215.61-2.58.92-4.095.92-1.755.005-3.375-.5-4.87-1.51zM233.52 69.81l-3.81 2.755-1.905-2.89 6.835-4.93h2.62V88h-3.74V69.81z"/>
                    </svg>
                    <p>Synchroniser avec <span class="text-red">Google Calendar</span></p>
                </div>
            </button>
        </a>
    @else
        <a href="/desync">
            <button class=" flex font-montserrat font-bold tracking-wide py-2 px-3  border-2 border-green rounded-full mx-auto">
                <div class="flex flex-wrap items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 px-2" viewBox="186 38 76 76">
                        <path fill="#fff" d="M244 56h-40v40h40V56z"/>
                        <path fill="#EA4335" d="M244 114l18-18h-18v18z"/>
                        <path fill="#FBBC04" d="M262 56h-18v40h18V56z"/>
                        <path fill="#34A853" d="M244 96h-40v18h40V96z"/>
                        <path fill="#188038" d="M186 96v12c0 3.315 2.685 6 6 6h12V96h-18z"/>
                        <path fill="#1967D2" d="M262 56V44c0-3.315-2.685-6-6-6h-12v18h18z"/>
                        <path fill="#4285F4" d="M244 38h-52c-3.315 0 -6 2.685-6 6v52h18V56h40V38z"/>
                        <path fill="#4285F4"
                              d="M212.205 87.03c-1.495-1.01-2.53-2.485-3.095-4.435l3.47-1.43c.315 1.2.865 2.13 1.65 2.79.78.66 1.73.985 2.84.985 1.135 0 2.11-.345 2.925-1.035s1.225-1.57 1.225-2.635c0-1.09-.43-1.98-1.29-2.67-.86-.69-1.94-1.035-3.23-1.035h-2.005V74.13h1.8c1.11 0 2.045-.3 2.805-.9.76-.6 1.14-1.42 1.14-2.465 0 -.93-.34-1.67-1.02-2.225-.68-.555-1.54-.835-2.585-.835-1.02 0 -1.83.27-2.43.815a4.784 4.784 0 0 0 -1.31 2.005l-3.435-1.43c.455-1.29 1.29-2.43 2.515-3.415 1.225-.985 2.79-1.48 4.69-1.48 1.405 0 2.67.27 3.79.815 1.12.545 2 1.3 2.635 2.26.635.965.95 2.045.95 3.245 0 1.225-.295 2.26-.885 3.11-.59.85-1.315 1.5-2.175 1.955v.205a6.605 6.605 0 0 1 2.79 2.175c.725.975 1.09 2.14 1.09 3.5 0 1.36-.345 2.575-1.035 3.64s-1.645 1.905-2.855 2.515c-1.215.61-2.58.92-4.095.92-1.755.005-3.375-.5-4.87-1.51zM233.52 69.81l-3.81 2.755-1.905-2.89 6.835-4.93h2.62V88h-3.74V69.81z"/>
                    </svg>
                    <p>Désyncroniser <span class="text-green">Google Calendar</span></p>
                </div>
            </button>
        </a>
    @endif


    <livewire:entreprise-horaire/>
@endsection
