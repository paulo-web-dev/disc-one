<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Liberar relatório premium · DISC ONE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #eef2f7; color: #1e293b; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .wrap { width: 100%; max-width: 720px; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 18px 50px rgba(15,23,42,0.12); }
        .head { background: linear-gradient(135deg, #2E73B8, #28598F); color: #fff; padding: 26px 32px; }
        .head .brand { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; letter-spacing: .5px; }
        .head .brand b { font-weight: 400; opacity: .85; }
        .body { padding: 30px 32px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        @media (max-width: 640px) { .body { grid-template-columns: 1fr; } }
        h1 { font-family: 'Poppins', sans-serif; font-size: 22px; color: #0f172a; margin-bottom: 14px; }
        .incl { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .incl li { font-size: 14px; color: #334155; display: flex; gap: 9px; align-items: flex-start; }
        .incl li span { color: #18A878; font-weight: 700; }
        .price-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; text-align: center; margin-bottom: 18px; }
        .price-old { color: #94a3b8; text-decoration: line-through; font-size: 15px; }
        .price { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #2E73B8; line-height: 1.1; }
        .price-note { font-size: 12px; color: #64748b; margin-top: 4px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin: 12px 0 6px; }
        input { width: 100%; padding: 11px 13px; font-size: 15px; font-family: inherit; border: 1px solid #cbd5e1; border-radius: 9px; outline: none; }
        input:focus { border-color: #2E73B8; box-shadow: 0 0 0 3px rgba(46,115,184,0.15); }
        .err { color: #dc2626; font-size: 12px; margin-top: 5px; }
        .btn { width: 100%; margin-top: 18px; background: #2E73B8; color: #fff; border: none; border-radius: 10px; padding: 14px; font-size: 16px; font-weight: 700; font-family: 'Poppins', sans-serif; cursor: pointer; }
        .btn:hover { background: #28598F; }
        .hint { font-size: 11px; color: #94a3b8; margin-top: 10px; text-align: center; }
        .secure { font-size: 12px; color: #64748b; margin-top: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="head">
            <div class="brand">DISC<b>ONE</b></div>
        </div>
        <div class="body">
            <div>
                <h1>Relatório Premium</h1>
                <ul class="incl">
                    <li><span>✓</span> Análise completa do perfil dominante</li>
                    <li><span>✓</span> Pontos fortes e pontos de melhoria</li>
                    <li><span>✓</span> Estilo de liderança e de comunicação</li>
                    <li><span>✓</span> Ambiente ideal e motivadores</li>
                    <li><span>✓</span> Perfil comercial e de equipe</li>
                </ul>
            </div>
            <div>
                <div class="price-box">
                    <div class="price-old">R$ 97</div>
                    <div class="price">R$ {{ number_format($price, 2, ',', '.') }}</div>
                    <div class="price-note">pagamento único · PIX, boleto ou cartão</div>
                </div>

                <form method="POST" action="{{ route('disc.checkout.process', ['test' => $test->id]) }}">
                    @csrf
                    <label for="name">Nome completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $test->respondent_name) }}" required>
                    @error('name') <div class="err">{{ $message }}</div> @enderror

                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $test->respondent_email) }}" required>
                    @error('email') <div class="err">{{ $message }}</div> @enderror

                    <label for="phone">Celular</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $test->respondent_phone) }}">
                    @error('phone') <div class="err">{{ $message }}</div> @enderror

                    <label for="cpf_cnpj">CPF ou CNPJ</label>
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" placeholder="Somente números" required>
                    @error('cpf_cnpj') <div class="err">{{ $message }}</div> @enderror

                    <button type="submit" class="btn">Ir para o pagamento</button>
                    <p class="secure">🔒 Pagamento processado pelo Asaas.</p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
