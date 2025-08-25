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

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 640px) {
        .posts-grid {
            grid-auto-rows: 1fr;
        }
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

    <!-- Barre Recherche / Filtres -->
    <div class="container mx-auto px-4">
        <?php
            // Les posts sont déjà filtrés/triés par le contrôleur.
            $q = $q ?? ($_GET['q'] ?? '');
            $sort = $sort ?? ($_GET['sort'] ?? 'new');
        ?>
        <form method="get" class="glass-effect rounded-2xl p-4 md:p-5 mb-4 flex flex-col sm:flex-row gap-4 sm:items-end">
            <div class="flex-1">
                <label for="search" class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70">Recherche</label>
                <input id="search" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Titre, contenu, auteur..." class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" />
            </div>
            <div class="sm:w-48">
                <label for="sort" class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70">Tri</label>
                <select id="sort" name="sort" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm">
                    <option value="new" <?php if($sort==='new') echo 'selected'; ?>>Plus récents</option>
                    <option value="old" <?php if($sort==='old') echo 'selected'; ?>>Plus anciens</option>
                    <option value="title" <?php if($sort==='title') echo 'selected'; ?>>Titre A→Z</option>
                </select>
            </div>
            <div class="flex gap-2 sm:gap-3">
                <button type="submit" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 font-semibold text-gray-700 text-sm shadow-sm hover:shadow-md transition">Filtrer</button>
                <?php if($q !== '' || $sort !== 'new'): ?>
                    <a href="/posts" class="px-4 py-2.5 rounded-full bg-white/60 hover:bg-white text-gray-700 text-sm font-semibold border border-white/40 transition">Réinitialiser</a>
                <?php endif; ?>
            </div>
        </form>
        <div class="flex justify-end mb-8">
            <?php $exportUrl = '/posts/pdf/all' . ($q!=='' || $sort!=='new' ? ('?'. http_build_query(array_filter(['q'=>$q,'sort'=>$sort], fn($v)=>$v!==null && $v!==''))) : ''); ?>
            <a href="<?php echo $exportUrl; ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-emerald-300 to-teal-300 hover:from-emerald-400 hover:to-teal-400 font-semibold text-gray-700 text-sm shadow-sm hover:shadow-md transition" title="Exporter un récapitulatif PDF de tous les posts filtrés">
                <span>Exporter PDF (tous)</span>
            </a>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="container mx-auto px-3 sm:px-4 pb-16">
        <?php if(empty($posts)): ?>
            <div class="text-center glass-effect rounded-2xl p-10 max-w-xl mx-auto">
                <p class="text-lg font-semibold mb-2">Aucun post trouvé</p>
                <p class="text-sm opacity-70 mb-6">Essayez d'élargir vos critères de recherche.</p>
                <a href="/posts" class="inline-block px-6 py-2.5 rounded-full bg-gradient-to-r from-purple-300 to-blue-300 text-gray-700 font-semibold text-sm hover:from-purple-400 hover:to-blue-400 transition">Réinitialiser</a>
            </div>
        <?php else: ?>
        <div class="grid posts-grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 md:gap-6">
            <?php foreach ($posts as $item): ?>
                <a href="/posts/<?php echo $item['id']; ?>" class="group h-full">
                    <div class="h-full flex flex-col glass-effect rounded-2xl p-5 sm:p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl group-hover:bg-white group-hover:bg-opacity-25 animate-fade-in focus:outline-none focus:ring-2 focus:ring-purple-400" tabindex="0">
                        <!-- Post Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="bg-gradient-to-r from-purple-300 to-blue-300 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">
                                #<?php echo $item['id']; ?>
                            </div>
                            <time class="text-[11px] sm:text-xs opacity-70 text-gray-600" datetime="<?php echo htmlspecialchars($item['created_at']); ?>">
                                <?php echo date('d/m/Y', strtotime($item['created_at'])); ?>
                            </time>
                        </div>

                        <!-- Post Title -->
                        <h3 class="text-lg sm:text-xl font-bold mb-2 group-hover:text-purple-500 transition-colors duration-300 text-gray-800 line-clamp-2">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h3>

                        <!-- Post Content -->
                        <p class="opacity-80 leading-relaxed mb-4 text-xs sm:text-sm text-gray-600 line-clamp-3 flex-1">
                            <?php echo htmlspecialchars(strlen($item['content']) > 280 ? substr($item['content'], 0, 280) . '…' : $item['content']); ?>
                        </p>

                        <!-- Post Footer -->
                        <div class="mt-auto pt-2 flex justify-between items-center">
                            <div class="bg-gradient-to-r from-purple-300 to-blue-300 text-gray-700 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold group-hover:from-purple-400 group-hover:to-blue-400 transition-all duration-300">
                                Lire plus →
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php if(isset($pagination) && $pagination['pages']>1): ?>
            <?php
                $baseParams = array_filter(['q'=>$q,'sort'=>$sort], fn($v)=>$v!==null && $v!=='');
                $makeUrl = function($p) use ($baseParams){ return '/posts?' . http_build_query(array_merge($baseParams, ['page'=>$p])); };
                $current = $pagination['page'];
                $totalPages = $pagination['pages'];
                $window = 2; // pages around current
                $pages = [];
                for($i=1;$i<=$totalPages;$i++){
                    if($i==1 || $i==$totalPages || ($i>=$current-$window && $i<=$current+$window)){
                        $pages[] = $i;
                    }
                }
                // Insert ellipsis markers
                $display = [];
                $prev = 0;
                foreach($pages as $p){
                    if($prev && $p > $prev+1){ $display[] = '...'; }
                    $display[] = $p; $prev = $p;
                }
            ?>
            <nav class="mt-10 flex flex-wrap items-center justify-center gap-2 sm:gap-3" aria-label="Pagination">
                <?php if($pagination['has_prev']): ?>
                    <a href="<?= htmlspecialchars($makeUrl($pagination['prev_page'])) ?>" class="px-3 sm:px-4 py-2 rounded-full bg-white/60 hover:bg-white text-xs sm:text-sm font-semibold border border-white/40 transition" aria-label="Page précédente">«</a>
                <?php else: ?>
                    <span class="px-3 sm:px-4 py-2 rounded-full bg-white/30 text-xs sm:text-sm font-semibold border border-white/20 opacity-50 cursor-not-allowed">«</span>
                <?php endif; ?>

                <?php foreach($display as $p): ?>
                    <?php if($p==='...'): ?>
                        <span class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-500">…</span>
                    <?php elseif($p==$current): ?>
                        <span class="px-3 sm:px-4 py-2 rounded-full bg-gradient-to-r from-purple-400 to-blue-400 text-white text-xs sm:text-sm font-bold shadow"><?= $p ?></span>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($makeUrl($p)) ?>" class="px-3 sm:px-4 py-2 rounded-full bg-white/70 hover:bg-white text-xs sm:text-sm font-semibold border border-white/40 hover:border-purple-400 transition" aria-label="Aller à la page <?= $p ?>"><?= $p ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if($pagination['has_next']): ?>
                    <a href="<?= htmlspecialchars($makeUrl($pagination['next_page'])) ?>" class="px-3 sm:px-4 py-2 rounded-full bg-white/60 hover:bg-white text-xs sm:text-sm font-semibold border border-white/40 transition" aria-label="Page suivante">»</a>
                <?php else: ?>
                    <span class="px-3 sm:px-4 py-2 rounded-full bg-white/30 text-xs sm:text-sm font-semibold border border-white/20 opacity-50 cursor-not-allowed">»</span>
                <?php endif; ?>
            </nav>
            <p class="mt-4 text-center text-xs sm:text-sm opacity-70">Page <?= $pagination['page'] ?> sur <?= $pagination['pages'] ?> (<?= $pagination['total'] ?> résultats)</p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>