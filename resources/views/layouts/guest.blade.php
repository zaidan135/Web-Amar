<!-- guest.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #fff;
            color: #ffffff;
            overflow: hidden;
        }
        .auth-container {
            perspective: 1000px;
        }
        @keyframes flipIn {
            from {
                transform: rotateY(90deg);
                opacity: 0;
            }
            to {
                transform: rotateY(0);
                opacity: 1;
            }
        }
        .form-input {
            background: #fafafa;
            border: 1px solid #444;
            color: black;
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }
        .auth-link {
            text-align: center;
            margin-top: 1rem;
            color: #ccc;
        }
        .auth-link a {
            color: #fff;
            text-decoration: underline;
            transition: color 0.3s ease;
        }
        .auth-link a:hover {
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="w-full min-h-screen grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1">
        <div id="slot" class="flex items-center justify-center">
            <div class="bg-transparent border-black border-1 border-b-[5px] border-r-[5px] p-4 px-11 pb-10 rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <div id="gambar" class="w-full h-screen flex justify-center items-center bg-black">
            <div class="w-[100vw] h-screen flex flex-col justify-center items-center pb-[10%]">
                <div class="w-[300px] h-[150px] -mb-5">
                    <svg viewBox="0 0 200 100" xmlns="http://www.w3.org/2000/svg">
                        <path id="curve" fill="transparent" d="M 10,90 A 130,100 0 0,1 190,90" />
                        <text width="500" font-family="sans-serif" font-size="30" font-weight="bold" fill="white">
                            <textPath xlink:href="#curve" startOffset="50%" text-anchor="middle">
                                Kedai Amar
                            </textPath>
                        </text>
                    </svg>
                </div>
                <img class="w-[50%] rounded-full bg-white p-6" src="{{ asset('images/img-login.svg') }}" alt="img-login">
            </div>
        </div>
    </div>
</body>
</html>