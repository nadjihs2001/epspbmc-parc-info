<x-guest-layout>
    <h1 class="valex-auth-title">Reinitialiser le mot de passe</h1>
    <p class="valex-auth-subtitle">Definissez un nouveau mot de passe securise.</p>

    <form method="POST" action="{{ route('password.store') }}" class="valex-auth-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="valex-auth-group">
            <label for="email" class="valex-auth-label">Email</label>
            <input id="email" class="valex-auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            @error('email') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="password" class="valex-auth-label">Nouveau mot de passe</label>
            <input id="password" class="valex-auth-input" type="password" name="password" required autocomplete="new-password" />
            @error('password') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="password_confirmation" class="valex-auth-label">Confirmation du mot de passe</label>
            <input id="password_confirmation" class="valex-auth-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-actions">
            <a class="valex-auth-link" href="{{ route('login') }}">Retour connexion</a>
            <button type="submit" class="valex-auth-btn">Reinitialiser</button>
        </div>
    </form>
</x-guest-layout>
