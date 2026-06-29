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
                        <td style="background: #1a56db; padding: 24px; text-align: center;">
                            <h1 style="color: #fff; margin: 0; font-size: 20px;">MiabéStock</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="color: #1a1d2e; font-size: 18px; margin-top: 0;">Bienvenue {{ $user->name }} !</h2>
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                Votre boutique <strong>{{ $user->boutique->nom ?? '' }}</strong> a été créée avec succès sur MiabéStock.
                            </p>
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                Vous pouvez dès maintenant ajouter vos produits, vos clients, et commencer à gérer vos ventes.
                            </p>

                            <div style="text-align: center; margin-top: 24px;">
                                <a href="{{ route('login') }}" style="background: #1a56db; color: #fff; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                                    Accéder à mon espace
                                </a>
                            </div>
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