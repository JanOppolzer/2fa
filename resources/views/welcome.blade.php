<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>

<body class="sm:p-16 bg-gray-50 dark:bg-gray-900 dark:text-gray-400 p-4 antialiased text-gray-700">

    <div class="max-w-screen-md mx-auto">

    @if (App::environment(['local', 'testing']))
        <hr class="hidden">
        <div class="mt-4 text-blue-900 bg-blue-100 rounded shadow">
            <h2 class="sm:text-xl p-4 font-semibold bg-blue-400 rounded-t">Login without authentication</h2>
            <form action="/fakelogin" method="POST">
                @csrf
                <div class="sm:flex-row sm:justify-between flex flex-col p-4 space-y-5">
                    <div class="flex items-center space-x-5">
                        <label class="font-semibold" for="user_id">User ID:</label>
                        <input class="w-20" type="number" name="id" id="user_id" value="1" min="1"
                        required>
                    </div>
                    <button class="hover:bg-blue-400 hover:shadow-lg px-6 py-3 bg-blue-300 rounded shadow"
                    type="submit">Fake Login</button>
                </div>
            </form>
        </div>
        @endif

    </div>
    
</body>

</html>
