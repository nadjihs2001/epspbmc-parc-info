<x-guest-layout>
    <h1 class="valex-auth-title">Creation de compte</h1>
    <p class="valex-auth-subtitle">Creez un acces a la plateforme de gestion.</p>

    <form method="POST" action="{{ route('register') }}" class="valex-auth-form">
        @csrf

        <div class="valex-auth-group">
            <label for="name" class="valex-auth-label">Nom complet</label>
            <input id="name" class="valex-auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            @error('name') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="email" class="valex-auth-label">Email</label>
            <input id="email" class="valex-auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="structure_id" class="valex-auth-label">Structure</label>
            <select id="structure_id" class="valex-auth-input" name="structure_id" required>
                <option value="">Selectionner une structure</option>
                @foreach ($structures as $structure)
                    <option value="{{ $structure->id }}" @selected(old('structure_id') == $structure->id)>
                        {{ $structure->nom }}
                    </option>
                @endforeach
            </select>
            @error('structure_id') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="fonction" class="valex-auth-label">Fonction</label>
            <input id="fonction" class="valex-auth-input" type="text" name="fonction" value="{{ old('fonction') }}" autocomplete="organization-title" />
            @error('fonction') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="telephone" class="valex-auth-label">Telephone</label>
            <input id="telephone" class="valex-auth-input" type="text" name="telephone" value="{{ old('telephone') }}" autocomplete="tel" />
            @error('telephone') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="password" class="valex-auth-label">Mot de passe</label>
            <input id="password" class="valex-auth-input" type="password" name="password" required autocomplete="new-password" />
            @error('password') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-group">
            <label for="password_confirmation" class="valex-auth-label">Confirmation du mot de passe</label>
            <input id="password_confirmation" class="valex-auth-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation') <p class="valex-auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="valex-auth-actions">
            <a class="valex-auth-link" href="{{ route('login') }}">Deja inscrit ? Se connecter</a>
            <button type="submit" class="valex-auth-btn">S'inscrire</button>
        </div>
    </form>
</x-guest-layout>
