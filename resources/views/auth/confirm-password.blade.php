<x-guest-layout>
    <h1 class="valex-auth-title">Confirmation de securite</h1>
    <p class="valex-auth-subtitle">Saisissez votre mot de passe pour continuer.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="valex-auth-form">
        @csrf

        <div class="valex-auth-group">
            <label for="password" class="valex-auth-label">Mot de passe</label>
            <input id="password" class="valex-auth-input" type="password" name="password" required autocomplete="current-password" />
            @error('password') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-actions">
            <a class="valex-auth-link" href="{{ route('dashboard') }}">Annuler</a>
            <button type="submit" class="valex-auth-btn">Confirmer</button>
        </div>
    </form>
</x-guest-layout>
