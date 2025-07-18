<x-guest-layout>
    <h2 class="lg:text-3xl md:text-2xl font-bold text-center mb-6 text-black">Login</h2>
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label for="login" class="text-sm mb-1 text-black">Email atau Username</label>
            <input id="login" name="login" type="text" placeholder="ex: johndoe123" required class="form-input" />
        </div>

        <div>
            <label for="password" class="text-sm mb-1 text-black">Password</label>
            <input id="password" name="password" type="password" required placeholder="insert password" class="form-input" />
        </div>

        <div class="flex items-center justify-between text-black">
            <label class="text-sm"><input type="checkbox" name="remember" class="mr-2">Ingat Saya</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm underline">Lupa Password?</a>
            @endif
        </div>

        
        <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded-md hover:bg-slate-800 transition">Login</button>
    </form>
</x-guest-layout>