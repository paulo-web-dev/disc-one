<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu resultado DISC ONE</title>
</head>
<body style="margin:0; padding:0; background-color:#F4F7FB; font-family: Arial, Helvetica, sans-serif; color:#3D4654;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F4F7FB; padding:32px 16px;">
        <tr>
            <td align="center">

                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%; background-color:#ffffff; border:1px solid #E6EBF1; border-radius:16px;">
                    <tr>
                        <td style="padding:32px 40px 0 40px;" align="center">
                            <div style="font-family: Arial, Helvetica, sans-serif; font-weight:bold; font-size:20px; letter-spacing:1px; color:#2A323D;">
                                DISC<span style="color:#2E73B8;">.ONE</span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 40px 8px 40px;">
                            <h1 style="margin:0 0 16px 0; font-size:22px; line-height:1.3; color:#2A323D;">
                                Obrigado por concluir o teste, {{ $nome }}!
                            </h1>
                            <p style="margin:0 0 16px 0; font-size:15px; line-height:1.6; color:#56606E;">
                                Você deu um passo importante em busca de autoconhecimento. O seu relatório comportamental DISC já está pronto.
                            </p>
                            <p style="margin:0 0 24px 0; font-size:15px; line-height:1.6; color:#56606E;">
                                É só clicar no botão abaixo para ver o seu resultado.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 40px 32px 40px;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" bgcolor="#2E73B8" style="border-radius:12px;">
                                        <a href="{{ $url }}" target="_blank"
                                           style="display:inline-block; padding:15px 38px; font-family: Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#ffffff; text-decoration:none; border-radius:12px;">
                                            Ver resultado
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 40px 32px 40px;">
                            <p style="margin:0; font-size:12px; line-height:1.6; color:#8893A2;">
                                Se o botão não funcionar, copie e cole este endereço no navegador:<br>
                                <a href="{{ $url }}" style="color:#2E73B8; word-break:break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%;">
                    <tr>
                        <td align="center" style="padding:20px 40px;">
                            <p style="margin:0; font-size:12px; color:#8893A2;">
                                © {{ date('Y') }} DISC ONE · Avaliação comportamental
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>
