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
                            <h2 style="color: #1a1d2e; font-size: 18px; margin-top: 0;">Bonjour {{ $user->name }},</h2>
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                Un compte vous a été créé sur <strong>{{ $user->boutique->nom ?? 'MiabéStock' }}</strong>
                                avec le rôle de <strong>{{ ucfirst($user->role) }}</strong>.
                            </p>

                            <div style="background: #f5f6fa; border-radius: 8px; padding: 16px; margin: 20px 0;">
                                <p style="margin: 4px 0; font-size: 13px; color: #6b7280;">Email de connexion</p>
                                <p style="margin: 4px 0; font-size: 15px; font-weight: 700; color: #1a1d2e;">{{ $user->email }}</p>
                                <p style="margin: 12px 0 4px; font-size: 13px; color: #6b7280;">Mot de passe temporaire</p>
                                <p style="margin: 4px 0; font-size: 15px; font-weight: 700; color: #1a56db;">{{ $motDePasseTemporaire }}</p>
                            </div>

                            <p style="color: #dc2626; font-size: 13px; line-height: 1.6;">
                                ⚠️ Pour des raisons de sécurité, vous devrez changer ce mot de passe dès votre première connexion.
                            </p>

                            <div style="text-align: center; margin-top: 24px;">
                                <a href="{{ route('login') }}" style="background: #1a56db; color: #fff; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                                    Se connecter
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