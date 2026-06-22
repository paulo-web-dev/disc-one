<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório DISC</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #e0e0e0;
            color: #222;
            margin: 0;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        /* PÁGINA A4 */
        .page {
            width: 210mm;
            height: 297mm;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
        }

        /* FUNDOS TIMBRADOS (mantidos) */
        .capa     { background-image: url('https://unyflex.com.br/storage/fav/1.png'); }
        .conteudo {
            background-image: url('https://unyflex.com.br/storage/fav/2.png');
            padding: 40mm 20mm 30mm 20mm;
        }
        .final {
            background-image: url('https://unyflex.com.br/storage/fav/4.png');
            page-break-after: auto;
        }

        h1, h2, h3 { text-align: center; margin-top: 0; }
        p { font-size: 14px; line-height: 1.6; text-align: justify; }

        .grafico { margin-top: 30px; height: 300px; width: 100%; position: relative; }

        .valores { display: flex; justify-content: space-between; margin-top: 15px; font-weight: bold; }
        .valores div { width: 23%; text-align: center; }

        .cta-wrap { text-align: center; margin-top: 28px; }

        /* BOTÃO NEON (desbloquear premium) */
        .btn-neon {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 18px 45px;
            background: #120d2b;
            color: #ffffff;
            text-decoration: none;
            font-family: 'Poppins', Arial, sans-serif;
            font-weight: 800;
            font-size: 16px;
            letter-spacing: 1px;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            border: 2px solid transparent;
            background-image: linear-gradient(#120d2b, #120d2b),
                              linear-gradient(90deg, #00d2ff, #ff00e6);
            background-origin: border-box;
            background-clip: padding-box, border-box;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.4),
                        0 0 15px rgba(255, 0, 230, 0.4);
        }
        .btn-neon:hover {
            transform: scale(1.03);
            box-shadow: 0 0 30px rgba(0, 210, 255, 0.7),
                        0 0 30px rgba(255, 0, 230, 0.7);
            color: #fff;
        }
        .btn-neon i { margin-right: 12px; font-size: 20px; color: #00d2ff; }

        /* IMPRESSÃO / SALVAR COMO PDF */
        @media print {
            body { background: none; padding: 0; margin: 0; display: block; }
            .page {
                box-shadow: none;
                margin: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .cta-wrap { display: none; } /* não imprime o botão de desbloqueio */
            @page { size: A4; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="page capa">
        <div style="margin-top: 455px; margin-left: 120px; color: #ffffff; font-family: 'Poppins', sans-serif; font-weight: bold;">
            <p style="margin: 0;">{{ $nome }}</p>
        </div>
    </div>

    <div class="page conteudo">

        <h1>Relatório DISC</h1>

        <p>Olá, <strong>{{ $nome }}</strong>, seja muito bem-vindo(a)!</p>

        <p>
            Gostaríamos de te parabenizar pelo grande passo dado em busca de autoconhecimento. Este relatório é frequentemente utilizado por profissionais especialistas comportamentais, como Coaches, Terapeutas e Profissionais de RH, com o objetivo de maximizar a performance. Entretanto, a devolutiva DISC deve ser realizada por um profissional capacitado, garantindo resultados mais assertivos.
        </p>

        @if($perfilDominante === 'D')
            <h3>Perfil Dominante – Padrão D</h3>
            <p>Pessoas que apresentam o padrão D têm como características marcantes a competitividade, agressividade, velocidade na tomada de decisão e foco em resultado. São determinadas e inovadoras. Por serem muito enérgicas, seus movimentos são rápidos, expansivos e firmes. Gostam de correr riscos e de fazer muitas coisas ao mesmo tempo. Sua palavra de ordem é <strong>DOMINAR</strong>. Quando essas características não são controladas, podem gerar a percepção de uma pessoa nervosa, brusca ou agressiva.</p>

        @elseif($perfilDominante === 'I')
            <h3>Perfil Influente – Padrão I</h3>
            <p>Pessoas que apresentam o padrão I têm como características marcantes a receptividade, facilidade em se comunicar e se expressar, otimismo e extroversão. São espontâneas, entusiastas e tendem a ser populares por onde passam, exercendo forte influência sobre os outros. Por serem muito falantes e emocionais, podem deixar de lado a escuta ativa e prometer mais do que conseguem cumprir, movidas pelo otimismo e pela busca de aprovação social.</p>

        @elseif($perfilDominante === 'S')
            <h3>Perfil Estável – Padrão S</h3>
            <p>Indivíduos com padrão S são calmos, prestativos, pacientes, modestos e descontraídos. Sua palavra de ordem é <strong>ALTRUÍSMO</strong>. São leais, confiáveis e excelentes membros de equipe. Possuem grande habilidade de ouvir e manter a serenidade, mesmo em situações desafiadoras. Evitam mudanças bruscas e decisões impulsivas. Quando não equilibram suas características, podem ser percebidos como lentos, indecisos ou resistentes a mudanças.</p>

        @elseif($perfilDominante === 'C')
            <h3>Perfil Cauteloso – Padrão C</h3>
            <p>Pessoas com padrão C são precisas, lógicas, analíticas e cuidadosas. Gostam de dados, informações e análises detalhadas. São reservadas, focadas em qualidade e prezam por ambientes organizados e previsíveis. Sua palavra de ordem é <strong>CALCULISTA</strong>. Quando essas características não são bem administradas, podem ser vistas como excessivamente perfeccionistas, pessimistas ou críticas.</p>
        @endif

        <div class="grafico">
            <canvas id="discChart"></canvas>
        </div>

        <div class="valores">
            <div>D<br>{{ $percentages['D'] }}%</div>
            <div>I<br>{{ $percentages['I'] }}%</div>
            <div>S<br>{{ $percentages['S'] }}%</div>
            <div>C<br>{{ $percentages['C'] }}%</div>
        </div>

        <div class="cta-wrap">
            <a href="{{ route('disc.checkout', ['test' => $funcionario->id]) }}" class="btn-neon">
                <i class="fas fa-lock-open"></i> DESBLOQUEAR ANÁLISE COMPLETA
            </a>
        </div>
    </div>

    <div class="page final"></div>

    <script>
        const ctx = document.getElementById('discChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Dominância (D)', 'Influência (I)', 'Estabilidade (S)', 'Conformidade (C)'],
                datasets: [{
                    data: [
                        {{ $percentages['D'] ?? 0 }},
                        {{ $percentages['I'] ?? 0 }},
                        {{ $percentages['S'] ?? 0 }},
                        {{ $percentages['C'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, max: 100 } },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>
