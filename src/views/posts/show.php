<?php if (!$post): ?>
    <div class="container mx-auto px-4 py-16 text-center">
        <h2 class="text-3xl font-bold mb-4 text-red-600">Post introuvable</h2>
        <a href="/posts" class="inline-block mt-6 bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300">← Retour à la liste</a>
    </div>
    <?php return; endif; ?>

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
    .gradient-text { background: linear-gradient(45deg, #c9a9dd, #a8c8ec); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .glass-effect { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); }
    .floating-element { animation: float 6s ease-in-out infinite; }
    .floating-element:nth-child(1){animation-delay:0s;} .floating-element:nth-child(2){animation-delay:1s;} .floating-element:nth-child(3){animation-delay:2s;} .floating-element:nth-child(4){animation-delay:3s;} .floating-element:nth-child(5){animation-delay:4s;}
    /* Ajout bulle description (overlay retiré) */
    .bubble { background: rgba(255,255,255,0.55); backdrop-filter: blur(8px); border:1px solid rgba(255,255,255,0.4); border-radius:1.5rem; padding:1.5rem 1.75rem; position:relative; overflow:visible; }
    .bubble:after { content:""; position:absolute; bottom:-12px; left:3rem; width:26px; height:26px; background:inherit; border:inherit; border-top:none; border-left:none; transform:rotate(45deg); border-radius:0 0 0.75rem 0; }
    .nav-mobile-enter {max-height:0; opacity:0; transition:max-height .3s ease, opacity .25s ease;}
    .nav-mobile-open {max-height:200px; opacity:1;}
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
</head>
<body class="min-h-screen bg-gradient-to-br from-purple-200 via-blue-200 to-pink-200 text-gray-700 font-sans">
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="floating-element absolute top-[10%] left-[10%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[20%] left-[80%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[60%] left-[15%] w-5 h-5 bg-pink-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[80%] left-[70%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full"></div>
        <div class="floating-element absolute top-[40%] left-[90%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full"></div>
    </div>

    <header class="container mx-auto px-4 py-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Post #<?php echo htmlspecialchars($post['id']); ?></h2>
        <p class="text-lg opacity-90 mb-8">Créé le <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?><?php if(!empty($post['updated_at'])): ?> • MAJ le <?php echo date('d/m/Y H:i', strtotime($post['updated_at'])); ?><?php endif; ?></p>
    </header>

    <div class="container mx-auto px-4 pb-16 max-w-4xl">
        <div class="glass-effect rounded-2xl p-8 md:p-10 space-y-8 animate-fade-in">
            <section>
                <h3 class="text-2xl font-bold mb-4 gradient-text">Titre</h3>
                <div class="bubble">
                    <div id="content-wrapper" class="relative overflow-hidden max-h-64 transition-all duration-500">
                        <div class="text-gray-700 whitespace-pre-line leading-relaxed"><?php echo nl2br(htmlspecialchars($post['title'])); ?></div>
                    </div>
                    <button id="toggle-content-btn" type="button" class="mt-4 inline-flex items-center gap-2 cursor-pointer select-none text-sm font-semibold text-gray-800 px-5 py-2 rounded-full bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 shadow-sm hover:shadow-md transition focus:outline-none focus:ring-2 focus:ring-purple-300" aria-expanded="false">Voir plus</button>
                </div>
            </section>
            <section id="post-content">
                <h3 class="text-2xl font-bold mb-4 gradient-text">Description</h3>
                <div class="bubble">
                    <div id="content-wrapper" class="relative overflow-hidden max-h-64 transition-all duration-500">
                        <div class="text-gray-700 whitespace-pre-line leading-relaxed"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                    </div>
                    <button id="toggle-content-btn" type="button" class="mt-4 inline-flex items-center gap-2 cursor-pointer select-none text-sm font-semibold text-gray-800 px-5 py-2 rounded-full bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 shadow-sm hover:shadow-md transition focus:outline-none focus:ring-2 focus:ring-purple-300" aria-expanded="false">Voir plus</button>
                </div>
            </section>
            <section class="grid sm:grid-cols-2 gap-6">
                <div class="glass-effect rounded-xl p-5 sm:col-span-2">
                    <h4 class="text-sm uppercase tracking-wide font-semibold opacity-70 mb-2">Prix</h4>
                    <p class="text-lg font-bold text-gray-800"><?php echo isset($post['price']) ? number_format($post['price'], 2, ',', ' ') . ' €' : '—'; ?></p>
                </div>
                <div class="glass-effect rounded-xl p-5 sm:col-span-2">
                    <h4 class="text-sm uppercase tracking-wide font-semibold opacity-70 mb-2">Localisation</h4>
                    <?php if(!empty($post['latitude']) && !empty($post['longitude'])): ?>
                        <div class="text-gray-800 text-sm mb-3">Lat: <?php echo $post['latitude']; ?> / Lng: <?php echo $post['longitude']; ?></div>
                        <div id="post-map" class="h-56 w-full rounded-lg overflow-hidden"></div>
                    <?php else: ?>
                        <p class="text-gray-800">—</p>
                    <?php endif; ?>
                </div>
                <div class="glass-effect rounded-xl p-5">
                    <h4 class="text-sm uppercase tracking-wide font-semibold opacity-70 mb-2">Contact Nom</h4>
                    <p class="text-gray-800"><?php echo htmlspecialchars($post['contact_name'] ?? '—'); ?></p>
                </div>
                <div class="glass-effect rounded-xl p-5">
                    <h4 class="text-sm uppercase tracking-wide font-semibold opacity-70 mb-2">Contact Téléphone</h4>
                    <p class="text-gray-800"><?php echo htmlspecialchars($post['contact_phone'] ?? '—'); ?></p>
                </div>
            </section>

            <div class="pt-6 flex justify-between items-center">
                <a href="/posts" class="bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300">← Retour</a>
                <div class="flex gap-3">
                    <a href="/posts/<?php echo htmlspecialchars($post['id']); ?>/pdf" class="bg-gradient-to-r from-emerald-300 to-teal-300 hover:from-emerald-400 hover:to-teal-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300" title="Exporter en PDF">Exporter PDF</a>
                    <button disabled class="opacity-50 cursor-not-allowed bg-gradient-to-r from-yellow-300 to-amber-300 text-gray-700 font-bold py-2 px-6 rounded-full">Edit (à venir)</button>
                    <button disabled class="opacity-50 cursor-not-allowed bg-gradient-to-r from-red-300 to-pink-300 text-gray-700 font-bold py-2 px-6 rounded-full">Supprimer (à venir)</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function(){
    const lat = <?php echo isset($post['latitude']) && $post['latitude'] !== '' ? (float)$post['latitude'] : 'null'; ?>;
    const lng = <?php echo isset($post['longitude']) && $post['longitude'] !== '' ? (float)$post['longitude'] : 'null'; ?>;
    if(lat !== null && lng !== null && document.getElementById('post-map')) {
        const map = L.map('post-map').setView([lat, lng], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map);
        // Corrige l'affichage si la carte est dans un conteneur flex/hidden initial
        setTimeout(()=>{ map.invalidateSize(); }, 200);
    }

    // Gestion état description + masquage bouton si contenu court
    const wrapper = document.getElementById('content-wrapper');
    const btn = document.getElementById('toggle-content-btn');
    if(!wrapper || !btn) return;
    const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize) || 16;
    const MAX_REM = 16; // correspond à max-h-64
    const thresholdPx = MAX_REM * rootFontSize;
    let expanded = false;

    // Si contenu pas plus grand que la limite => on affiche tout et on retire le bouton
    if(wrapper.scrollHeight <= thresholdPx + 2) {
        expanded = true;
        wrapper.classList.remove('max-h-64');
        btn.remove(); // pas besoin de bouton
    } else {
        function applyState(){
            if(expanded){
                wrapper.classList.remove('max-h-64');
                btn.textContent = 'Voir moins';
                btn.setAttribute('aria-expanded','true');
            } else {
                wrapper.classList.add('max-h-64');
                btn.textContent = 'Voir plus';
                btn.setAttribute('aria-expanded','false');
            }
        }
        btn.addEventListener('click', () => {
            expanded = !expanded;
            applyState();
            if(!expanded){
                wrapper.scrollIntoView({behavior:'smooth', block:'start'});
            }
        });
        applyState();
    }

    // Navigation toggle supprimé (centralisé dans header.tpl.php)
})();
</script>
</html>
