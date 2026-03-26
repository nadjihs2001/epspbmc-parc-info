<x-guest-layout>
    <h1 class="valex-auth-title">Recuperation du mot de passe</h1>
    <p class="valex-auth-subtitle">Entrez votre email pour recevoir un lien de reinitialisation.</p>

    @if (session('status'))
        <div class="valex-auth-status mt-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="valex-auth-form">
        @csrf

        <div class="valex-auth-group">
            <label for="email" class="valex-auth-label">Email</label>
            <input id="email" class="valex-auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-actions">
            <a class="valex-auth-link" href="{{ route('login') }}">Retour connexion</a>
            <button type="submit" class="valex-auth-btn">Envoyer le lien</button>
        </div>
    </form>
</x-guest-layout>
