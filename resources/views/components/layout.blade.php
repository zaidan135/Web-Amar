<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://kit.fontawesome.com/d9a94bae06.js" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Marcisa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white" style="scrollbar-width: thin; scrollbar-color: #4B5563 transparent;">
    <div class="flex">
        <div>
            <x-sidebar />
        </div>
        <div class="w-full">
            <x-navbar />
            <main class="w-[100%] h-[calc(100vh-74px)] relative bg-white overflow-y-auto overflow-x-hidden">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>