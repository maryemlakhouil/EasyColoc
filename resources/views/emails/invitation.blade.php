<p>Vous avez reçu une invitation pour rejoindre la colocation :
    <strong>{{ $invitation->colocation->name }}</strong>
</p>

<p>
    Cliquez ici :
    <a href="{{ route('invitations.show', $invitation->token) }}">
        Voir l’invitation
    </a>
</p>

<p>Ce lien peut expirer.</p>