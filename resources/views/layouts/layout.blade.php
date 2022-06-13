<!doctype html>
<html lang="fr">
<head>
    @include('includes.head')
     @livewireStyles
</head>
<body class="flex flex-col min-h-screen">
 @livewireScripts
<header class="row">
    @include('includes.header')
</header>


<main class="flex-grow">
    @yield('content')
</main>

@if(!str_starts_with(request()->path(),"espace"))
    @include('includes.footer')
@endif
</body>

</html>
