@extends('layouts.app')

@section('title', 'Descubra seu perfil comportamental')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/marketing.css') }}">
@endpush

@section('content')

{{-- ====================== NAV ====================== --}}
<header class="mkt-nav">
    <div class="container inner">
        <a href="{{ url('/') }}" class="brand-lockup" aria-label="DISC ONE — início">
            <span class="brand-mark"><img src="{{ asset('assets/logo.png') }}" alt=""></span>
            <span class="brand-word">DISC<b>.ONE</b></span>
        </a>

        <nav class="mkt-nav-links">
            <a href="#dimensoes" class="nav-text hide-sm">As 4 dimensões</a>
            <a href="#como-funciona" class="nav-text hide-sm">Como funciona</a>
            @auth
                <a href="{{ route('dashboard') }}" class="nav-text">Meu painel</a>
                <a href="{{ route('test.intro') }}" class="btn btn-primary btn-sm">Fazer o teste</a>
            @else
                <a href="{{ route('login') }}" class="nav-text">Entrar</a>
                <a href="{{ route('test.intro') }}" class="btn btn-primary btn-sm">Fazer o teste</a>
            @endauth
        </nav>
    </div>
</header>

{{-- ====================== HERO ====================== --}}
<section class="mkt-hero">
    {{-- pétalas decorativas --}}
    <span class="petal-float" style="width:160px;height:160px;background:var(--disc-i);top:8%;left:-40px;"></span>
    <span class="petal-float" style="width:120px;height:120px;background:var(--disc-d);bottom:-30px;right:6%;transform:rotate(180deg);"></span>

    <div class="container hero-grid">
        <div class="hero-copy animate-fadeUp">
            <span class="eyebrow">Avaliação comportamental DISC</span>
            <h1>Entenda como você <span class="grad">age, decide e se relaciona</span></h1>
            <p class="hero-sub">
                O DISC ONE mapeia o seu estilo em quatro dimensões — D, I, S e C — e entrega
                um retrato claro dos seus pontos fortes, da sua comunicação e do que te move.
            </p>

            <div class="hero-cta">
                @auth
                    <a href="{{ route('test.intro') }}" class="btn btn-primary btn-lg">Fazer o teste agora</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-lg">Ir para o painel</a>
                @else
                    <a href="{{ route('test.intro') }}" class="btn btn-primary btn-lg">Fazer o teste agora</a>
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-lg">Entrar</a>
                @endauth
            </div>

            <div class="hero-meta">
                <span class="item"><span class="disc-dot dot-i"></span> <b>24</b>&nbsp;questões</span>
                <span class="item"><span class="disc-dot dot-s"></span> cerca de <b>7</b>&nbsp;minutos</span>
                <span class="item"><span class="disc-dot dot-d"></span> resultado <b>na hora</b></span>
            </div>
        </div>

        <div class="hero-visual animate-scaleIn">
            <div class="hero-card">
                <img src="{{ asset('assets/disc-one-stacked.png') }}" alt="DISC ONE">
            </div>
        </div>
    </div>
</section>

{{-- ====================== 4 DIMENSÕES ====================== --}}
<section class="mkt-section" id="dimensoes">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">O modelo</span>
            <h2>Quatro dimensões, um perfil só seu</h2>
            <p>Todo comportamento é uma combinação destas quatro forças. O teste mede a intensidade de cada uma em você.</p>
        </div>

        <div class="dim-grid">
            @foreach (config('disc_profiles') as $key => $p)
                <article class="dim-card {{ strtolower($key) }}">
                    <div class="dim-letter">{{ $p['letter'] }}</div>
                    <h3>{{ $p['name'] }}</h3>
                    <div class="role">{{ $p['archetype'] }}</div>
                    <p>{{ $p['tagline'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ====================== COMO FUNCIONA ====================== --}}
<section class="mkt-section alt" id="como-funciona">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Simples assim</span>
            <h2>Como funciona</h2>
            <p>Do cadastro ao resultado em três passos — sem complicação.</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="num">1</div>
                <h3>Informe nome e e-mail</h3>
                <p>Sem cadastro nem senha. É só se identificar para começar o teste na hora.</p>
            </div>
            <div class="step">
                <div class="num">2</div>
                <h3>Responda 24 questões</h3>
                <p>Em cada questão, ordene quatro frases — da que <b>mais</b> combina com você até a que <b>menos</b> combina.</p>
            </div>
            <div class="step">
                <div class="num">3</div>
                <h3>Veja seu perfil</h3>
                <p>Resultado na hora, com o gráfico das quatro dimensões. Quer o relatório completo? Baixe em PDF.</p>
            </div>
        </div>
    </div>
</section>

{{-- ====================== CTA ====================== --}}
<section class="cta-band">
    <div class="container">
        <div class="cta-card">
            <h2>Pronto para se conhecer melhor?</h2>
            <p>Leva poucos minutos e o resultado é seu para sempre. Comece agora mesmo.</p>
            @auth
                <a href="{{ route('test.intro') }}" class="btn btn-secondary btn-lg">Fazer o teste</a>
            @else
                <a href="{{ route('test.intro') }}" class="btn btn-secondary btn-lg">Fazer o teste</a>
            @endauth
        </div>
    </div>
</section>

{{-- ====================== FOOTER ====================== --}}
<footer class="mkt-footer">
    <div class="container inner">
        <a href="{{ url('/') }}" class="brand-lockup is-sm">
            <span class="brand-mark"><img src="{{ asset('assets/logo.png') }}" alt=""></span>
            <span class="brand-word">DISC<b>.ONE</b></span>
        </a>
        <span class="muted">© {{ date('Y') }} DISC ONE · Avaliação comportamental</span>
        @guest
            <a href="{{ route('login') }}" class="nav-text">Entrar</a>
        @endguest
    </div>
</footer>

@endsection
