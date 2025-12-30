<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MS² Cash Flow - Gestão Financeira Inteligente</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="min-height: 100vh; background-color: #F9FAFB; font-family: 'Inter', sans-serif; margin: 0; padding: 0;">
    <!-- Header -->
    <header style="background-color: white; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; height: 4rem;">
                <!-- Logo -->
                <div style="display: flex; align-items: center;">
                    <img src="{{ asset('images/MS2 Cash_Flow_Logo.png') }}" alt="MS² Cash Flow"
                        style="height: 3.5rem; width: auto;">
                </div>

                <!-- Navigation -->
                @if (Route::has('login'))
                    <nav style="display: flex; align-items: center; gap: 1rem;">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                style="background: linear-gradient(135deg, #FFC312 0%, #FF8F00 100%); color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: transform 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                style="color: #4B5563; padding: 0.5rem 1rem; font-weight: 600; text-decoration: none;">
                                Entrar
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    style="background: linear-gradient(135deg, #FFC312 0%, #FF8F00 100%); color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: transform 0.3s ease;"
                                    onmouseover="this.style.transform='scale(1.05)'"
                                    onmouseout="this.style.transform='scale(1)'">
                                    Cadastrar
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #FFC312 0%, #FF8F00 100%); padding: 5rem 0;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem; text-align: center;">
            <img src="{{ asset('images/Logo2_MS2_Cash_Flow_BCO.png') }}" alt="MS² Cash Flow"
                style="height: 18rem; width: auto; display: block; margin: 0 auto 2rem auto;">
            <p
                style="font-size: 1.25rem; color: white; margin-bottom: 2rem; max-width: 48rem; margin-left: auto; margin-right: auto; opacity: 0.95; line-height: 1.6;">
                Transforme sua gestão financeira com inteligência e simplicidade.
                Controle completo do seu fluxo de caixa em uma plataforma moderna.
            </p>
            <div
                style="display: flex; flex-direction: column; gap: 1rem; justify-content: center; align-items: center;">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        style="background-color: white; color: #111827; padding: 1rem 2rem; border-radius: 0.5rem; font-weight: 600; font-size: 1.125rem; text-decoration: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        Acessar Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        style="background-color: white; color: #111827; padding: 1rem 2rem; border-radius: 0.5rem; font-weight: 600; font-size: 1.125rem; text-decoration: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease; margin-bottom: 0.5rem;"
                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        Começar Agora
                    </a>
                    <a href="{{ route('login') }}"
                        style="border: 2px solid white; color: white; padding: 1rem 2rem; border-radius: 0.5rem; font-weight: 600; font-size: 1.125rem; text-decoration: none; background: transparent; transition: all 0.3s ease;"
                        onmouseover="this.style.backgroundColor='white'; this.style.color='#111827'"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='white'">
                        Já tenho conta
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 5rem 0; background-color: white;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2 style="font-size: 2.25rem; font-weight: 700; color: #111827; margin-bottom: 1rem;">
                    Funcionalidades Poderosas
                </h2>
                <p style="font-size: 1.25rem; color: #4B5563; max-width: 42rem; margin: 0 auto;">
                    Tudo que você precisa para ter controle total das suas finanças
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <!-- Feature 1 -->
                <div style="text-align: center; padding: 2rem 1rem; border-radius: 1rem; background-color: #F9FAFB; transition: all 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)'"
                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                    <div
                        style="width: 4rem; height: 4rem; background: linear-gradient(135deg, #FFC312 0%, #FF8F00 100%); border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Controle de
                        Fluxo</h3>
                    <p style="color: #4B5563;">Monitore entradas e saídas em tempo real com relatórios detalhados e
                        intuitivos.</p>
                </div>

                <!-- Feature 2 -->
                <div style="text-align: center; padding: 2rem 1rem; border-radius: 1rem; background-color: #F9FAFB; transition: all 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)'"
                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                    <div
                        style="width: 4rem; height: 4rem; background: linear-gradient(135deg, #FFC312 0%, #FF8F00 100%); border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Relatórios
                        Inteligentes</h3>
                    <p style="color: #4B5563;">Análises automáticas que ajudam você a tomar decisões financeiras mais
                        assertivas.</p>
                </div>

                <!-- Feature 3 -->
                <div style="text-align: center; padding: 2rem 1rem; border-radius: 1rem; background-color: #F9FAFB; transition: all 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)'"
                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                    <div
                        style="width: 4rem; height: 4rem; background: linear-gradient(135deg, #FFC312 0%, #FF8F00 100%); border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Performance
                        Otimizada</h3>
                    <p style="color: #4B5563;">Interface rápida e responsiva, desenvolvida com as melhores tecnologias
                        do mercado.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section style="background-color: #111827; padding: 5rem 0;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem; text-align: center;">
            <h2 style="font-size: 2.25rem; font-weight: 700; color: white; margin-bottom: 1.5rem;">
                Pronto para revolucionar suas finanças?
            </h2>
            <p
                style="font-size: 1.25rem; color: #D1D5DB; margin-bottom: 2rem; max-width: 42rem; margin-left: auto; margin-right: auto;">
                Junte-se a milhares de usuários que já transformaram sua gestão financeira com o MS² Cash Flow.
            </p>
            @guest
                <a href="{{ route('register') }}"
                    style="background: linear-gradient(135deg, #FFC312 0%, #FF8F00 100%); color: white; padding: 1rem 2rem; border-radius: 0.5rem; font-weight: 600; font-size: 1.125rem; text-decoration: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease; display: inline-block;"
                    onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    Começar Gratuitamente
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: white; border-top: 1px solid #E5E7EB; padding: 3rem 0;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem;">
            <div style="text-align: center;">
                <img src="{{ asset('images/MS2_Cash_Flow_Logo.png') }}" alt="MS² Cash Flow"
                    style="height: 2rem; width: auto; margin: 0 auto 1rem auto; display: block;">
                <p style="color: #4B5563; margin: 0;">
                    © {{ date('Y') }} MS² Cash Flow. Gestão financeira inteligente.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
