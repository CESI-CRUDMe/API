<?php /* Page de création de post avec sélection de coordonnées via modal Leaflet (Tailwind modal) */ ?>
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
                    float: { '0%, 100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-20px)' } },
                    fadeIn: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                }
            }
        }
    }
</script>
<style>
    /* Styles alignés avec index/show */
    .gradient-text { background: linear-gradient(45deg, #c9a9dd, #a8c8ec); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .glass-effect { backdrop-filter: blur(10px); background: rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); }
    .floating-element { animation: float 6s ease-in-out infinite; }
    .floating-element:nth-child(1){animation-delay:0s;} .floating-element:nth-child(2){animation-delay:1s;} .floating-element:nth-child(3){animation-delay:2s;} .floating-element:nth-child(4){animation-delay:3s;} .floating-element:nth-child(5){animation-delay:4s;}
    #map { height: 400px; }
</style>
<script>
    // Applique classes body (header.tpl.php a déjà ouvert <body>)
    document.addEventListener('DOMContentLoaded', ()=>{
        document.body.classList.add('min-h-screen','bg-gradient-to-br','from-purple-200','via-blue-200','to-pink-200','text-gray-700','font-sans');
    });
</script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Floating Elements -->
<div class="fixed inset-0 pointer-events-none -z-10">
    <div class="floating-element absolute top-[10%] left-[10%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full"></div>
    <div class="floating-element absolute top-[20%] left-[80%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full"></div>
    <div class="floating-element absolute top-[60%] left-[15%] w-5 h-5 bg-pink-300 bg-opacity-20 rounded-full"></div>
    <div class="floating-element absolute top-[80%] left-[70%] w-5 h-5 bg-purple-300 bg-opacity-20 rounded-full"></div>
    <div class="floating-element absolute top-[40%] left-[90%] w-5 h-5 bg-blue-300 bg-opacity-20 rounded-full"></div>
</div>

<header class="container mx-auto px-4 pt-6 pb-4 text-center">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold gradient-text mb-3">Créer un Post</h2>
    <p class="text-base sm:text-lg opacity-90 mb-5 max-w-2xl mx-auto px-2">Ajoutez un nouveau contenu à la collection</p>
</header>

<div class="container mx-auto px-4 pb-16 max-w-3xl">
    <div class="glass-effect rounded-2xl p-6 sm:p-8 space-y-6 animate-fade-in">
        <div id="flashMsg" class="hidden"></div>
        <form id="createPostForm" action="/api/posts" method="post" class="space-y-6">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="title">Titre *</label>
                <input required name="title" id="title" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" placeholder="Titre du post">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="content">Contenu *</label>
                <textarea required name="content" id="content" rows="5" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" placeholder="Décrivez votre post..."></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="price">Prix (€) *</label>
                    <input required name="price" id="price" type="number" min="0" step="0.01" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70">Latitude *</label>
                    <input required readonly name="latitude" id="latitude" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/50 text-gray-700 outline-none border border-white/40 text-sm" placeholder="Cliquez sur la carte">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70">Longitude *</label>
                    <input required readonly name="longitude" id="longitude" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/50 text-gray-700 outline-none border border-white/40 text-sm" placeholder="Cliquez sur la carte">
                </div>
            </div>
            <div>
                <button type="button" id="openModal" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-blue-300 to-purple-300 hover:from-blue-400 hover:to-purple-400 font-semibold text-gray-700 text-sm shadow-sm hover:shadow-md transition">Choisir la position sur la carte</button>
                <p class="text-xs text-gray-600 mt-1">Cliquez ci-dessus pour sélectionner les coordonnées.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="contact_name">Nom du contact *</label>
                    <input required name="contact_name" id="contact_name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" placeholder="Nom">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="contact_phone">Téléphone du contact *</label>
                    <input required name="contact_phone" id="contact_phone" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" placeholder="Téléphone">
                </div>
            </div>
            <div class="pt-2 flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-emerald-300 to-teal-300 hover:from-emerald-400 hover:to-teal-400 font-semibold text-gray-700 text-sm shadow-sm hover:shadow-md transition">Enregistrer</button>
                <a href="/posts" class="px-5 py-2.5 rounded-full bg-white/60 hover:bg-white text-gray-700 text-sm font-semibold border border-white/40 transition">Annuler</a>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div id="modalOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" aria-hidden="true" role="dialog" aria-modal="true">
    <div id="modalContent" class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh] transform scale-95 opacity-0 transition duration-200 overflow-hidden">
        <div class="flex justify-between items-center px-5 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Choisir la position</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" aria-label="Fermer">
                <span class="inline-block text-xl leading-none">✕</span>
            </button>
        </div>
        <div class="px-5 py-4 space-y-3 overflow-y-auto">
            <p class="text-sm text-gray-600 dark:text-gray-300">Cliquez sur la carte pour placer/mettre à jour le marqueur. Vous pouvez ensuite le déplacer en le faisant glisser.</p>
            <div id="map" class="rounded-md overflow-hidden ring-1 ring-gray-200 dark:ring-neutral-700"></div>
            <div class="text-xs text-gray-500 dark:text-gray-400" id="coordPreview">Aucune position sélectionnée.</div>
        </div>
        <div class="flex justify-end gap-3 px-5 py-4 border-t border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800/60">
            <button id="cancelBtn" type="button" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 text-sm font-medium">Annuler</button>
            <button id="confirmLocationBtn" type="button" disabled class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-medium rounded-md shadow-sm">Valider</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function(){
    const form = document.getElementById('createPostForm');
    const flash = document.getElementById('flashMsg');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const openModalBtn = document.getElementById('openModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalContent = document.getElementById('modalContent');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmLocationBtn = document.getElementById('confirmLocationBtn');
    const coordPreview = document.getElementById('coordPreview');

    let map, marker, pendingLatLng = null, mapInitialized = false;

    function showFlash(msg, type='success'){
        flash.textContent = msg;
        flash.className = 'mb-4 px-4 py-2 rounded text-sm ' + (type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
        flash.classList.remove('hidden');
        setTimeout(()=>{ flash.classList.add('hidden'); }, 6000);
    }

    function openModal(){
        modalOverlay.classList.remove('hidden');
        modalOverlay.classList.add('flex');
        requestAnimationFrame(()=> { modalContent.classList.remove('scale-95','opacity-0'); modalContent.classList.add('scale-100','opacity-100'); });
        document.body.style.overflow='hidden';
        if(!mapInitialized){ initMap(); }
        setTimeout(()=> { map.invalidateSize(); }, 250);
    }
    function closeModal(){
        modalContent.classList.add('scale-95','opacity-0');
        modalContent.classList.remove('scale-100','opacity-100');
        setTimeout(()=> { modalOverlay.classList.add('hidden'); modalOverlay.classList.remove('flex'); document.body.style.overflow=''; }, 180);
    }

    openModalBtn.addEventListener('click', (e)=>{ e.preventDefault(); openModal(); });
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', (e)=> { if(e.target === modalOverlay) closeModal(); });
    document.addEventListener('keydown', (e)=> { if(e.key === 'Escape' && !modalOverlay.classList.contains('modal-hidden')) closeModal(); });

    function initMap(){
        map = L.map('map').setView([46.7, 2.5], 6); // centre France
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        map.on('click', (e)=> {
            pendingLatLng = e.latlng;
            if(!marker){
                marker = L.marker(pendingLatLng,{draggable:true}).addTo(map);
                marker.on('dragend', ()=> { pendingLatLng = marker.getLatLng(); updatePreview(); });
            } else {
                marker.setLatLng(pendingLatLng);
            }
            updatePreview();
        });
        mapInitialized = true;
    }

    function updatePreview(){
        if(pendingLatLng){
            coordPreview.textContent = 'Sélection: ' + pendingLatLng.lat.toFixed(6) + ', ' + pendingLatLng.lng.toFixed(6);
            confirmLocationBtn.disabled = false;
        } else {
            coordPreview.textContent = 'Aucune position sélectionnée.';
            confirmLocationBtn.disabled = true;
        }
    }

    confirmLocationBtn.addEventListener('click', ()=>{
        if(!pendingLatLng) return;
        latitudeInput.value = pendingLatLng.lat.toFixed(6);
        longitudeInput.value = pendingLatLng.lng.toFixed(6);
        showFlash('Coordonnées mises à jour');
        closeModal();
    });

    form.addEventListener('submit', async (e)=>{
        e.preventDefault();
        if(!latitudeInput.value || !longitudeInput.value){
            showFlash('Veuillez sélectionner des coordonnées.', 'error');
            return;
        }
        const fd = new FormData(form);
        try {
            const res = await authFetch(form.action, { method: 'POST', body: fd });
            const data = await res.json().catch(()=>({}));
            if(!res.ok){
                showFlash(data.message || 'Erreur lors de la création', 'error');
                return;
            }
            showFlash('Post créé avec succès');
            form.reset();
            latitudeInput.value = '';
            longitudeInput.value = '';
            setTimeout(()=> { window.location.href = '/posts'; }, 1500);
        } catch(err){
            showFlash(err.message || 'Erreur réseau', 'error');
        }
    });
})();
</script>