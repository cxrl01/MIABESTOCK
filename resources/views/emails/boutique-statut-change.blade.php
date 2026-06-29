<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background: #f5f6fa; padding: 40px 0; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0" style="background: #fff; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td style="background: {{ $action === 'reactivee' ? '#16a34a' : '#dc2626' }}; padding: 24px; text-align: center;">
                            <h1 style="color: #fff; margin: 0; font-size: 20px;">MiabéStock</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            @if($action === 'suspendue')
                                <h2 style="color: #1a1d2e; font-size: 18px; margin-top: 0;">Boutique suspendue</h2>
                                <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                    Votre boutique <strong>{{ $boutique->nom }}</strong> a été suspendue par l'administration de MiabéStock.
                                    Vos employés ne peuvent plus se connecter tant que la suspension est active.
                                </p>
                            @elseif($action === 'reactivee')
                                <h2 style="color: #1a1d2e; font-size: 18px; margin-top: 0;">Boutique réactivée</h2>
                                <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                    Bonne nouvelle ! Votre boutique <strong>{{ $boutique->nom }}</strong> a été réactivée.
                                    Vous et votre équipe pouvez à nouveau vous connecter normalement.
                                </p>
                            @else
                                <h2 style="color: #1a1d2e; font-size: 18px; margin-top: 0;">Boutique supprimée</h2>
                                <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                    Votre boutique <strong>{{ $boutique->nom }}</strong> a été supprimée définitivement de la plateforme MiabéStock.
                                </p>
                            @endif

                            @if($motif)
                                <div style="background: #f5f6fa; border-radius: 8px; padding: 16px; margin: 20px 0;">
                                    <p style="margin: 0 0 6px; font-size: 13px; color: #6b7280; font-weight: 700;">Motif indiqué :</p>
                                    <p style="margin: 0; font-size: 14px; color: #1a1d2e;">{{ $motif }}</p>
                                </div>
                            @endif

                            <p style="color: #6b7280; font-size: 13px; line-height: 1.6;">
                                Pour toute question, veuillez contacter le support MiabéStock.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px; text-align: center; background: #f5f6fa;">
                            <p style="font-size: 12px; color: #9ca3af; margin: 0;">© 2026 MiabéStock</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>