<?php

namespace App\Mail;

use App\Models\Boutique;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoutiqueStatutChange extends Mailable
{
    use Queueable, SerializesModels;

    public Boutique $boutique;
    public string $action; // 'suspendue', 'reactivee', 'supprimee'
    public ?string $motif;

    public function __construct(Boutique $boutique, string $action, ?string $motif = null)
    {
        $this->boutique = $boutique;
        $this->action = $action;
        $this->motif = $motif;
    }

    public function build()
    {
        $sujets = [
            'suspendue' => 'Votre boutique a été suspendue',
            'reactivee' => 'Votre boutique a été réactivée',
            'supprimee' => 'Votre boutique a été supprimée',
        ];

        return $this->subject($sujets[$this->action] ?? 'Mise à jour de votre boutique')
            ->view('emails.boutique-statut-change');
    }
}