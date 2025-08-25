<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'purple-pastel': '#c9a9dd',
                    'blue-pastel': '#a8c8ec',
                    'pink-pastel': '#f4c2c2',
                    'lavender-pastel': '#e6d9f2',
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
        background: linear-gradient(45deg, #c9a9dd, #a8c8ec);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
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

<body class="min-h-screen bg-gradient-to-br from-purple-200 via-blue-200 to-pink-200 text-gray-700 font-sans">
    <!-- Floating Elements -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="floating-element absolute top-[10%] left-[10%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[20%] left-[80%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[60%] left-[15%] w-5 h-5 bg-pink-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[80%] left-[70%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[40%] left-[90%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full"></div>
    </div>

    <div class="container mx-auto px-4 py-5">
        <!-- Header -->
        <header class="text-center py-16 glass-effect rounded-3xl mb-10 shadow-2xl">
            <h1 class="text-5xl md:text-6xl font-bold gradient-text mb-4 drop-shadow">
                CRUD me !
            </h1>
            <p class="text-lg md:text-xl opacity-90 mb-4 text-gray-700 font-medium">
                Développement Web & Application Mobile
            </p>
            <p class="text-base md:text-lg opacity-80 text-gray-600">
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
                <h3 class="text-xl font-bold mb-4 text-gray-800">
                    Interface Web Admin
                </h3>
                <p class="opacity-80 leading-relaxed text-gray-700">
                    Interface administrateur dynamique et responsive pour gérer vos données avec les opérations CRUD
                    complètes.
                </p>
            </div>

            <div
                class="glass-effect rounded-2xl p-8 text-center transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl group">
                <div class="text-5xl mb-6 gradient-text group-hover:scale-110 transition-transform duration-300">
                    🔌
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-800">
                    API Sécurisée
                </h3>
                <p class="opacity-80 leading-relaxed mb-5 text-gray-700">
                    API REST avec authentification JWT, protection contre CSRF, injections SQL et failles XSS.
                </p>
                <div class="flex flex-wrap justify-center gap-2">
                    <span
                        class="bg-gradient-to-r from-emerald-300 to-teal-300 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                        🔒 JWT
                    </span>
                    <span
                        class="bg-gradient-to-r from-emerald-300 to-teal-300 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                        🛡️ CSRF
                    </span>
                    <span
                        class="bg-gradient-to-r from-emerald-300 to-teal-300 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                        💉 SQL Injection
                    </span>
                    <span
                        class="bg-gradient-to-r from-emerald-300 to-teal-300 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                        ⚡ XSS
                    </span>
                </div>
            </div>

            <div
                class="glass-effect rounded-2xl p-8 text-center transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl group md:col-span-2 lg:col-span-1">
                <div class="text-5xl mb-6 gradient-text group-hover:scale-110 transition-transform duration-300">
                    📱
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-800">
                    Application Mobile
                </h3>
                <p class="opacity-80 leading-relaxed text-gray-700">
                    App mobile avec pagination infinie, géolocalisation et interface utilisateur intuitive.
                </p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center py-16 glass-effect rounded-3xl my-10 shadow-2xl">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6">
                Découvrez le Projet
            </h2>
            <p class="text-lg md:text-xl opacity-80 mb-10 max-w-2xl mx-auto leading-relaxed text-gray-700">
                Explorez notre plateforme complète avec gestion des données,
                cartographie interactive et fonctionnalités avancées.
            </p>

            <a href="/posts"
                class="inline-block bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 text-gray-700 font-semibold py-4 px-9 rounded-full text-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl shadow">
                Accéder aux Posts
            </a>
        </div>

        <!-- Tech Stack -->
        <div class="flex flex-wrap justify-center gap-4 my-12">
            <div
                class="glass-effect px-6 py-3 rounded-full font-semibold text-gray-700 transition-transform duration-300 hover:scale-105">
                MVC Architecture
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-semibold text-gray-700 transition-transform duration-300 hover:scale-105">
                Base de Données
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-semibold text-gray-700 transition-transform duration-300 hover:scale-105">
                Leaflet Maps
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-semibold text-gray-700 transition-transform duration-300 hover:scale-105">
                Email Integration
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-semibold text-gray-700 transition-transform duration-300 hover:scale-105">
                PDF Export
            </div>
            <div
                class="glass-effect px-6 py-3 rounded-full font-semibold text-gray-700 transition-transform duration-300 hover:scale-105">
                Responsive Design
            </div>
        </div>
    </div>