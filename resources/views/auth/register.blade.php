<x-layout>
        <h2 class="lg:text-3xl md:text-2xl font-bold text-center mb-6 text-black">Buat Akun Baru</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label for="name" class="text-sm mb-1 text-black">Nama</label>
                <input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="username" class="text-sm mb-1 text-black">Username</label>
                <input id="username" class="form-input" type="text" name="username" :value="old('username')" required autocomplete="username" placeholder="Username"/>
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="text-sm mb-1 text-black">Email</label>
                <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="email"  placeholder="Email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="role" class="text-sm mb-1 text-black">Peran (Role)</label>
                <select name="role" id="role" class="form-input">
                    <option value="kasir" @selected(old('role') == 'kasir')>Kasir</option>
                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="text-sm mb-1 text-black">Password</label>
                <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" placeholder="Password" />
            </div>

            <div>
                <label for="password_confirmation" class="text-sm mb-1 text-black">Konfirmasi Password</label>
                <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" placeholder="Confirmation Password" />
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded-md hover:bg-slate-800 transition">Register</button>
            <div class="w-full flex items-center">
                <div class="w-full bg-black h-[0.5px]"></div>
                <span class="text-black mx-4">atau</span>
                <div class="w-full bg-black h-[0.5px]"></div>
            </div>
            <div class="text-black">
                Sudah Punya Akun? <a href="{{ route('login') }}" class="underline">Login</a>
            </div>
        </form>
</x-layout>
