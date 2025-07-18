<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $users = User::latest()->paginate(10);

        return view('pages-admin.account.index', compact('users'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['admin', 'kasir'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));

        return back()->with('success', 'Akun baru berhasil dibuat.');
    }

    public function update(Request $r, User $user)
    {
        $data = $r->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required','string','lowercase','max:255',
                           Rule::unique('users')->ignore($user->id)],
            'email'    => ['required','email','max:255',
                           Rule::unique('users')->ignore($user->id)],
            'role'     => ['required', Rule::in(['admin','kasir'])],
            'password' => 'nullable|confirmed|min:6',
        ]);

        if ($data['password'])
            $data['password'] = Hash::make($data['password']);
        else
            unset($data['password']);   // tidak diganti

        $user->update($data);
        return back()->with('success','Akun diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success','Akun dihapus.');
    }
}
