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
                    'fade-in': 'fadeIn 0.5s ease-in-out',
                },
                keyframes: {
                    float: {
                        '0%, 100%': { transform: 'translateY(0px)' },
                        '50%': { transform: 'translateY(-20px)' },
                    },
                    fadeIn: {
                        '0%': { opacity: '0', transform: 'translateY(20px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' },
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
        <div class="floating-element absolute top-[10%] left-[10%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full">
        </div>
        <div class="floating-element absolute top-[20%] left-[80%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full">
        </div>
        <div class="floating-element absolute top-[60%] left-[15%] w-5 h-5 bg-pink-300 bg-opacity-20 rounded-full">
        </div>
        <div class="floating-element absolute top-[80%] left-[70%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full">
        </div>
        <div class="floating-element absolute top-[40%] left-[90%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full">
        </div>
    </div>

    <!-- Header Section -->
    <header class="container mx-auto px-4 pt-6 pb-4 text-center">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold gradient-text mb-3">
            Tous les Posts
        </h2>
        <p class="text-base sm:text-lg opacity-90 mb-5 max-w-2xl mx-auto px-2">
            Découvrez notre collection complète de contenus
        </p>
    </header>

    <!-- Posts Grid -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-center">
            <?php foreach ($posts as $item): ?>
                <a href="/posts/<?php echo $item['id']; ?>" class="block group">
                    <div
                        class="glass-effect rounded-2xl p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl group-hover:bg-white group-hover:bg-opacity-25 animate-fade-in">
                        <!-- Post Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="bg-gradient-to-r from-purple-300 to-blue-300 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">
                                #<?php echo $item['id']; ?>
                            </div>
                            <div class="text-sm opacity-80 text-gray-600">
                                <?php echo date('d/m/Y', strtotime($item['created_at'])); ?>
                            </div>
                        </div>

                        <!-- Post Title -->
                        <h3
                            class="text-xl font-bold mb-3 group-hover:text-purple-500 transition-colors duration-300 text-gray-800">
                            <?php echo $item['title']; ?>
                        </h3>

                        <!-- Post Content -->
                        <p class="opacity-90 leading-relaxed mb-4 text-sm text-gray-600">
                            <?php echo strlen($item['content']) > 120 ? substr($item['content'], 0, 120) . '...' : $item['content']; ?>
                        </p>

                        <!-- Post Footer -->
                        <div class="flex justify-between items-center">
                            <div class="text-sm opacity-80 text-gray-600">
                                Par <?php echo $item['author']; ?>
                            </div>
                            <div
                                class="bg-gradient-to-r from-purple-300 to-blue-300 text-gray-700 px-4 py-2 rounded-full text-sm font-bold group-hover:from-purple-400 group-hover:to-blue-400 transition-all duration-300">
                                Lire plus →
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>