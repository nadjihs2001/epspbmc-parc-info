<x-guest-layout>
    <h1 class="valex-auth-title">Verification de l'email</h1>
    <p class="valex-auth-subtitle">Confirmez votre adresse email pour activer votre compte.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="valex-auth-status mt-4">
            Un nouveau lien de verification a ete envoye a votre adresse email.
        </div>
    @endif

    <div class="valex-auth-form">
        <p class="text-sm text-slate-600">
            Consultez votre boite mail puis cliquez sur le lien de verification. Si vous ne l'avez pas recu, renvoyez-le.
        </p>

        <div class="valex-auth-actions">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="valex-auth-link">Se deconnecter</button>
            </form>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="valex-auth-btn">Renvoyer le lien</button>
            </form>
        </div>
    </div>
</x-guest-layout>
