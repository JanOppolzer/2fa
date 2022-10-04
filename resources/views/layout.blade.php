<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="bg-gray antialiased text-gray-700">

    <main class="md:p-8 max-w-screen-xl p-4 mx-auto">
        <x-flash-message />
        @yield('content')
    </main>

    @livewireScripts

</body>

</html>
