<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'structures' => Structure::query()->where('actif', true)->orderBy('nom')->get(),
        ]);
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'structure_id' => ['required', 'exists:structures,id'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'structure_id' => $request->integer('structure_id'),
            'fonction' => $request->input('fonction'),
            'telephone' => $request->input('telephone'),
            'password' => Hash::make($request->password),
        ]);
        if (Role::query()->where('name', 'Utilisateur simple')->exists()) {
            $user->assignRole('Utilisateur simple');
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
