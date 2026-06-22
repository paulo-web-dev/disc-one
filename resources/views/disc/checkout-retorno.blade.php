<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $paid ? 'Obrigado!' : 'Conclua seu pagamento' }} · DISC ONE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #eef2f7; color: #1e293b; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { width: 100%; max-width: 540px; background: #fff; border-radius: 18px; padding: 42px 36px; text-align: center; box-shadow: 0 18px 50px rgba(15,23,42,0.12); }
        .brand { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; letter-spacing: .5px; color: #2E73B8; margin-bottom: 22px; }
        .brand b { font-weight: 400; opacity: .7; }
        .icon { width: 76px; height: 76px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 38px; }
        .icon.ok { background: rgba(24,168,120,0.12); color: #18A878; }
        .icon.wait { background: rgba(46,115,184,0.10); color: #2E73B8; }
        h1 { font-family: 'Poppins', sans-serif; font-size: 24px; color: #0f172a; margin-bottom: 12px; }
        p { font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 8px; }
        .btn { display: inline-block; margin-top: 22px; background: #2E73B8; color: #fff; text-decoration: none; border-radius: 10px; padding: 15px 34px; font-size: 16px; font-weight: 700; font-family: 'Poppins', sans-serif; }
        .btn:hover { background: #28598F; }
        .btn-ghost { display: inline-block; margin-top: 14px; color: #2E73B8; text-decoration: none; font-size: 14px; font-weight: 600; }
        .muted { font-size: 12px; color: #94a3b8; margin-top: 20px; }
        .waiting { display: inline-flex; align-items: center; gap: 10px; margin-top: 22px; color: #64748b; font-size: 14px; }
        .spin { width: 18px; height: 18px; border: 3px solid #cbd5e1; border-top-color: #2E73B8; border-radius: 50%; animation: spin 0.9s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">DISC<b>ONE</b></div>

        @if ($paid)
            <div class="icon ok">✓</div>
            <h1>Obrigado, {{ $nome }}! 🎉</h1>
            <p>Seu pagamento foi confirmado e o relatório premium está liberado.</p>
            <p>Também enviamos o link para o seu e-mail. 📧</p>
            <a href="{{ route('disc.resultDocumentoPremium', ['id' => $test->id]) }}" class="btn">Ver relatório premium</a>
        @else
            <div class="icon wait">💳</div>
            <h1>Falta só o pagamento</h1>
            <p>Clique no botão abaixo para pagar (PIX, boleto ou cartão). Pode deixar esta aba aberta — assim que o pagamento confirmar, o relatório libera aqui automaticamente.</p>
            @if ($invoiceUrl)
                <a href="{{ $invoiceUrl }}" target="_blank" rel="noopener" class="btn">Pagar agora</a>
            @endif
            <div class="waiting"><span class="spin"></span> Aguardando confirmação do pagamento…</div>
            <p class="muted">Pagou via PIX ou cartão? Costuma confirmar em segundos. Se preferir, recarregue a página.</p>
        @endif
    </div>

    @unless ($paid)
    <script>
        const statusUrl = "{{ route('disc.checkout.status', ['test' => $test->id]) }}";
        setInterval(async () => {
            try {
                const r = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
                const j = await r.json();
                if (j.paid) {
                    window.location.reload();
                }
            } catch (e) { /* tenta de novo no próximo ciclo */ }
        }, 4000);
    </script>
    @endunless
</body>
</html>
