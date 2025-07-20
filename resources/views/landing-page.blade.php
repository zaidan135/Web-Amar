<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3; url={{ route('login') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #000; /* hitam */
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo {
            width: 120px;
            margin-bottom: 30px;
            animation: fadeIn 1.5s ease-in-out;
        }

        .spinner {
            width: 64px;
            height: 64px;
            border: 6px solid rgba(255,255,255,0.2);
            border-top: 6px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .film-effect {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(0deg, rgba(255,255,255,0.05) 0px, rgba(255,255,255,0.05) 1px, transparent 1px, transparent 3px),
                radial-gradient(circle at center, rgba(255,255,255,0.05), transparent 60%);
            pointer-events: none;
            animation: flicker 1s infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes flicker {
            0%, 100% { opacity: 0.04; }
            50% { opacity: 0.08; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    {{-- Efek Film Flicker --}}
    <div class="film-effect"></div>

    {{-- Logo --}}
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">

    {{-- Spinner --}}
    <div class="spinner"></div>

    {{-- Fallback JS Redirect --}}
    <script>
        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 3000);
    </script>
</body>
</html>
