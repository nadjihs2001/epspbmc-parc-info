<x-guest-layout>
    <h1 class="valex-auth-title">Connexion</h1>
    <p class="valex-auth-subtitle">Accedez a votre tableau de bord Valex.</p>

    @if (session('status'))
        <div class="valex-auth-status mt-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="valex-auth-form">
        @csrf

        <div class="valex-auth-group">
            <label for="email" class="valex-auth-label">Email</label>
            <input id="email" class="valex-auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="password" class="valex-auth-label">Mot de passe</label>
            <input id="password" class="valex-auth-input" type="password" name="password" required autocomplete="current-password" />
            @error('password') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="valex-auth-check" name="remember">
                <span>Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="valex-auth-link" href="{{ route('password.request') }}">
                    Mot de passe oublie ?
                </a>
            @endif
        </div>

        <div class="valex-auth-actions">
            @if (Route::has('register'))
                <a class="valex-auth-link" href="{{ route('register') }}">Creer un compte</a>
            @endif
            <button type="submit" class="valex-auth-btn">
                Se connecter
            </button>
        </div>
    </form>
</x-guest-layout>
