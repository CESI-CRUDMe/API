<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'purple-custom': '#9c27b0',
                    'blue-custom': '#3f51b5',
                },
                animation: {
                    'float': 'float 6s ease-in-out infinite',
                    'pulse-slow': 'pulse 3s ease-in-out infinite',
                },
                keyframes: {
                    float: {
                        '0%, 100%': { transform: 'translateY(0px)' },
                        '50%': { transform: 'translateY(-20px)' },
                    }
                }
            }
        }
    }
</script>
<style>
    .gradient-text {
        background: linear-gradient(45deg, #9c27b0, #3f51b5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .floating-element {
        animation: float 6s ease-in-out infinite;
    }

    .floating-element:nth-child(1) {
        animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
        animation-delay: 1s;
    }

    .floating-element:nth-child(3) {
        animation-delay: 2s;
    }

    .floating-element:nth-child(4) {
        animation-delay: 3s;
    }

    .floating-element:nth-child(5) {
        animation-delay: 4s;
    }
</style>
</head>

<body class="min-h-screen bg-gradient-to-br from-purple-500 via-blue-500 to-purple-700 text-white font-sans">
    <!-- Floating Elements -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="floating-element absolute top-[10%] left-[10%] w-5 h-5 bg-white bg-opacity-10 rounded-full"></div>
        <div class="floating-element absolute top-[20%] left-[80%] w-5 h-5 bg-white bg-opacity-10 rounded-full"></div>
        <div class="floating-element absolute top-[60%] left-[15%] w-5 h-5 bg-white bg-opacity-10 rounded-full"></div>
        <div class="floating-element absolute top-[80%] left-[70%] w-5 h-5 bg-white bg-opacity-10 rounded-full"></div>
        <div class="floating-element absolute top-[40%] left-[90%] w-5 h-5 bg-white bg-opacity-10 rounded-full"></div>
    </div>

    <div class="container mx-auto px-4 py-5">
        <!-- Header -->
        <header class="text-center py-16 glass-effect rounded-3xl mb-10 shadow-2xl">
            <h1 class="text-6xl md:text-7xl font-bold gradient-text mb-3 drop-shadow-lg">
                CRUD me !
            </h1>
            <p class="text-xl md:text-2xl opacity-90 mb-5">
                Développement Web & Application Mobile
            </p>
            <p class="text-lg opacity-80">
                Interface administrateur dynamique et responsive avec API sécurisée
            </p>
        </header>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 my-12">
            <div
                class="glass-effect rounded-2xl p-8 text-center transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl group">
                <div class="text-5xl mb-6 gradient-text group-hover:scale-110 transition-transform duration-300">
                    🌐
                </div>
                <h3 class="text-xl font-bold mb-4 text-purple-100">
                    Interface Web Admin
                </h3>
                <p class="opacity-90 leading-relaxed">
                    Interface administrateur dynamique et responsive pour gérer vos données avec les opérations CRUD
                    complètes.
                </p>
            </div>

            <div
                class="glass-effect rounded-2xl p-8 text-center transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl group">
                <div class="text-5xl mb-6 gradient-text group-hover:scale-110 transition-transform duration-300">
                    🔌
                </div>
                <h3 class="text-xl font-bold mb-4 text-purple-100">
                    API Sécurisée
                </h3>
                <p class="opacity-90 leading-relaxed mb-4">
                    API REST avec authentification JWT, protection contre CSRF, injections SQL et failles XSS.
                </p>
                <div class="flex flex-wrap justify-center gap-2">
                    <span
                        class="bg-green-500 bg-opacity-20 text-green-300 px-3 py-1 rounded-full text-sm font-bold border border-green-400 border-opacity-30">
                        🔒 JWT
                    </span>
                    <span
                        class="bg-green-500 bg-opacity-20 text-green-300 px-3 py-1 rounded-full text-sm font-bold border border-green-400 border-opacity-30">
                        🛡️ CSRF
                    </span>
                    <span
                        class="bg-green-500 bg-opacity-20 text-green-300 px-3 py-1 rounded-full text-sm font-bold border border-green-400 border-opacity-30">
                        💉 SQL Injection
                    </span>
                    <span
                        class="bg-green-500 bg-opacity-20 text-green-300 px-3 py-1 rounded-full text-sm font-bold border border-green-400 border-opacity-30">
                        ⚡ XSS
                    </span>
                </div>
            </div>

            <div
                class="glass-effect rounded-2xl p-8 text-center transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl group md:col-span-2 lg:col-span-1">
                <div class="text-5xl mb-6 gradient-text group-hover:scale-110 transition-transform duration-300">
                    📱
                </div>
                <h3 class="text-xl font-bold mb-4 text-purple-100">
                    Application Mobile
                </h3>
                <p class="opacity-90 leading-relaxed">
                    App mobile avec pagination infinie, géolocalisation et interface utilisateur intuitive.
                </p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center py-20 glass-effect rounded-3xl my-10 shadow-2xl">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6">
                Découvrez le Projet
            </h2>
            <p class="text-lg md:text-xl opacity-90 mb-10 max-w-2xl mx-auto leading-relaxed">
                Explorez notre plateforme complète avec gestion des données,
                cartographie interactive et fonctionnalités avancées.
            </p>

            <a href="/posts"
                class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-5 px-10 rounded-full text-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl shadow-lg">
                Accéder aux Posts
            </a>
        </div>

        <!-- Tech Stack -->
        <div class="flex flex-wrap justify-center gap-4 my-12">
            <div
                class="glass-effect px-6 py-3 rounded-full font-bold transition-transform duration-300 hover:scale-105">
                MVC Architecture
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-bold transition-transform duration-300 hover:scale-105">
                Base de Données
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-bold transition-transform duration-300 hover:scale-105">
                Leaflet Maps
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-bold transition-transform duration-300 hover:scale-105">
                Email Integration
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-bold transition-transform duration-300 hover:scale-105">
                PDF Export
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-bold transition-transform duration-300 hover:scale-105">
                Responsive Design
            </div>
        </div>
    </div>