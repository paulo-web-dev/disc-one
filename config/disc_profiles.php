<?php

/*
 * Textos interpretativos dos perfis DISC.
 * Fonte: disc-data.jsx do layout (Claude Design).
 * Os campos resumidos (name, archetype, tagline, traits) alimentam a tela de
 * resultado gratuita; os demais ficam prontos para o relatório completo (PDF, Etapa 9).
 */

return [

    'D' => [
        'letter' => 'D',
        'name' => 'Dominante',
        'archetype' => 'O Executor',
        'hex' => '#2F66A8',
        'tagline' => 'Movido por resultados, decisão e desafio. Você assume o comando e faz as coisas acontecerem.',
        'traits' => [
            'Decidido e direto',
            'Focado em resultado',
            'Corajoso para arriscar',
            'Líder por natureza',
        ],
        'strengths' => [
            'Toma decisões rápidas sob pressão',
            'Não recua diante de desafios',
            'Foca no que realmente importa',
            'Assume responsabilidade naturalmente',
        ],
        'leadership' => 'Lidera pelo exemplo e pela exigência. Define a direção, delega o operacional e cobra resultados. Brilha em cenários de crise e virada, onde decisões rápidas valem mais que consenso.',
        'professional' => 'Prospera em funções com autonomia, metas claras e espaço para decidir: gestão, vendas de alto valor, empreendedorismo, operações e turnaround. Sente-se preso em ambientes excessivamente burocráticos.',
        'commercial' => 'Vendedor de fechamento. Conduz a negociação com firmeza, vai direto ao ponto e cria senso de urgência. Precisa cuidar para não atropelar a escuta do cliente.',
        'communication' => [
            'pros' => ['Seja direto e objetivo', 'Foque em resultados e prazos', 'Ofereça opções, não ordens'],
            'cons' => ['Evite rodeios e excesso de detalhe', 'Não desafie sem dados', 'Não imponha ritmo lento'],
        ],
        'improvements' => [
            'Desenvolver paciência e escuta ativa',
            'Considerar o impacto das decisões nas pessoas',
            'Delegar sem microgerenciar',
        ],
        'environment' => 'Dinâmico, com desafios, autonomia e reconhecimento por resultado. Pouca burocracia, metas ambiciosas e liberdade para decidir.',
        'motivators' => [
            'Desafios e metas ambiciosas',
            'Autonomia e poder de decisão',
            'Reconhecimento por resultados',
            'Competição saudável',
        ],
        'demotivators' => [
            'Microgerenciamento',
            'Rotina sem desafio',
            'Decisões lentas por consenso',
            'Falta de controle',
        ],
        'teamFit' => 'Complementa-se muito bem com perfis S (executam com constância o que o D inicia) e C (trazem o rigor que o D tende a pular). Pode atritar com outro D por disputa de comando.',
    ],

    'I' => [
        'letter' => 'I',
        'name' => 'Influente',
        'archetype' => 'O Comunicador',
        'hex' => '#18A878',
        'tagline' => 'Movido por pessoas, entusiasmo e reconhecimento. Você inspira, conecta e contagia com energia.',
        'traits' => [
            'Comunicativo e expressivo',
            'Persuasivo e convincente',
            'Sociável e caloroso',
            'Entusiasta e otimista',
        ],
        'strengths' => [
            'Engaja e motiva pessoas com facilidade',
            'Cria conexões e abre portas',
            'Traz energia e otimismo ao time',
            'Comunica ideias de forma envolvente',
        ],
        'leadership' => 'Lidera pelo entusiasmo e pela inspiração. Vende a visão, mobiliza pessoas e cria clima positivo. Precisa de estrutura por perto para transformar a empolgação em entrega consistente.',
        'professional' => 'Brilha em funções de relacionamento e exposição: vendas, marketing, atendimento, treinamento, gestão de pessoas, eventos e comunicação. Cansa em tarefas solitárias, repetitivas e cheias de detalhe.',
        'commercial' => 'Vendedor de relacionamento. Encanta, cria rapport e gera entusiasmo na proposta. Precisa cuidar do follow-up, dos detalhes do contrato e do fechamento objetivo.',
        'communication' => [
            'pros' => ['Seja amigável e entusiasta', 'Dê espaço para a pessoa falar', 'Reconheça publicamente as conquistas'],
            'cons' => ['Não seja frio ou só técnico', 'Evite excesso de detalhes e regras', 'Não ignore o lado pessoal'],
        ],
        'improvements' => [
            'Manter foco e concluir o que inicia',
            'Cuidar de detalhes e prazos',
            'Ouvir mais e falar um pouco menos',
        ],
        'environment' => 'Colaborativo, social e estimulante. Com reconhecimento, variedade, interação com pessoas e liberdade para expressar ideias.',
        'motivators' => [
            'Reconhecimento e visibilidade',
            'Interação social e novidade',
            'Liberdade de expressão',
            'Clima leve e positivo',
        ],
        'demotivators' => [
            'Isolamento e tarefas solitárias',
            'Rotina rígida e repetitiva',
            'Ambiente frio e impessoal',
            'Excesso de controle e detalhe',
        ],
        'teamFit' => 'Forma duplas poderosas com perfis C (que organizam o que o I idealiza) e D (que cobram o fechamento). Com outro I, o time tem energia, mas pode faltar quem cuide da execução.',
    ],

    'S' => [
        'letter' => 'S',
        'name' => 'Estável',
        'archetype' => 'O Apoiador',
        'hex' => '#6E9BD0',
        'tagline' => 'Movido por harmonia, cooperação e segurança. Você é o alicerce confiável que mantém tudo de pé.',
        'traits' => [
            'Paciente e constante',
            'Leal e confiável',
            'Ótimo ouvinte',
            'Colaborativo e gentil',
        ],
        'strengths' => [
            'Mantém a estabilidade e o ritmo do time',
            'Constrói relações de confiança duradouras',
            'Escuta com atenção genuína',
            'Executa com consistência e dedicação',
        ],
        'leadership' => 'Lidera pelo cuidado e pela constância. Cria segurança psicológica, apoia o time e mantém processos rodando. Precisa desenvolver firmeza para decisões difíceis e conversas duras.',
        'professional' => 'Brilha em funções que exigem confiança, continuidade e relacionamento de longo prazo: suporte, RH, operações, saúde, atendimento e gestão de equipes. Sofre com mudanças bruscas e conflito constante.',
        'commercial' => 'Vendedor consultivo e de pós-venda. Constrói confiança, fideliza e cuida do cliente no longo prazo. Precisa desenvolver senso de urgência e conforto para conduzir o fechamento.',
        'communication' => [
            'pros' => ['Seja paciente e acolhedor', 'Explique mudanças com antecedência', 'Mostre apreço e segurança'],
            'cons' => ['Não pressione por respostas rápidas', 'Evite mudanças repentinas', 'Não seja agressivo ou impessoal'],
        ],
        'improvements' => [
            'Lidar melhor com mudanças e incertezas',
            'Expor opiniões e dizer não quando preciso',
            'Buscar protagonismo sem medo',
        ],
        'environment' => 'Estável, cooperativo e previsível. Com relações de confiança, processos claros e baixo nível de conflito.',
        'motivators' => [
            'Segurança e estabilidade',
            'Trabalho em equipe e harmonia',
            'Reconhecimento sincero',
            'Previsibilidade e processos claros',
        ],
        'demotivators' => [
            'Mudanças bruscas e sem aviso',
            'Conflito e clima hostil',
            'Pressão e urgência constante',
            'Falta de clareza',
        ],
        'teamFit' => 'É a cola do time. Complementa o D (executando com constância) e o I (dando consistência ao entusiasmo). Com excesso de perfis S, o grupo é harmônico mas pode resistir a mudanças.',
    ],

    'C' => [
        'letter' => 'C',
        'name' => 'Conforme',
        'archetype' => 'O Analista',
        'hex' => '#4FA3C4',
        'tagline' => 'Movido por precisão, qualidade e lógica. Você garante que tudo seja feito do jeito certo.',
        'traits' => [
            'Analítico e preciso',
            'Organizado e metódico',
            'Cauteloso e prudente',
            'Focado em qualidade',
        ],
        'strengths' => [
            'Garante qualidade e exatidão',
            'Analisa riscos antes de decidir',
            'Estrutura processos e padrões',
            'Entrega com rigor e consistência',
        ],
        'leadership' => 'Lidera pelo conhecimento técnico e pelo padrão de qualidade. Define processos, controla riscos e eleva o nível de entrega. Precisa flexibilizar o perfeccionismo e acelerar decisões.',
        'professional' => 'Brilha em funções técnicas e analíticas: finanças, jurídico, engenharia, qualidade, dados, auditoria, conformidade e planejamento. Sente-se desconfortável com improviso e ambiguidade.',
        'commercial' => 'Vendedor técnico e consultivo. Domina o produto, responde com dados e gera confiança pela competência. Precisa cuidar para não travar a venda em excesso de análise.',
        'communication' => [
            'pros' => ['Seja preciso e baseado em dados', 'Dê tempo para análise', 'Respeite regras e processos'],
            'cons' => ['Evite pressão e improviso', 'Não seja vago ou emocional demais', 'Não force decisões rápidas'],
        ],
        'improvements' => [
            'Decidir mesmo sem 100% das informações',
            'Flexibilizar o perfeccionismo',
            'Expressar mais as emoções',
        ],
        'environment' => 'Organizado, técnico e previsível. Com regras claras, tempo para análise, padrões de qualidade e pouco improviso.',
        'motivators' => [
            'Qualidade e precisão',
            'Regras e processos claros',
            'Tempo para analisar',
            'Reconhecimento pela competência técnica',
        ],
        'demotivators' => [
            'Improviso e ambiguidade',
            'Pressão por rapidez sem dados',
            'Falta de padrões',
            'Crítica ao trabalho técnico',
        ],
        'teamFit' => 'Eleva o nível de qualquer time. Complementa o I (estruturando ideias) e o D (trazendo o rigor que falta). Com excesso de perfis C, o grupo é preciso, mas pode travar na análise.',
    ],

];
