<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouveauCompteEquipe extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $motDePasseTemporaire;

    public function __construct(User $user, string $motDePasseTemporaire)
    {
        $this->user = $user;
        $this->motDePasseTemporaire = $motDePasseTemporaire;
    }

    public function build()
    {
        return $this->subject('Votre compte MiabéStock a été créé')
            ->view('emails.nouveau-compte-equipe');
    }
}