<?php /* Page de création de post avec sélection de coordonnées via modal Leaflet (Tailwind modal) */ ?>
<style>
    #map { height: 400px; }
    /* Suppression des styles custom de modal: utilisation Tailwind uniquement */
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Créer un post</h1>
    <div id="flashMsg" class="mb-4 hidden"></div>
    <form id="createPostForm" action="/api/posts" method="post" class="space-y-6">
        <div>
            <label class="block text-sm font-medium mb-1" for="title">Titre *</label>
            <input required name="title" id="title" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Titre du post">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="content">Contenu *</label>
            <textarea required name="content" id="content" rows="5" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Décrivez votre post..."></textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium mb-1" for="price">Prix (€) *</label>
                <input required name="price" id="price" type="number" min="0" step="0.01" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="0.00">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Latitude *</label>
                <input required readonly name="latitude" id="latitude" type="text" class="w-full border rounded px-3 py-2 bg-gray-100" placeholder="Cliquez sur la carte">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Longitude *</label>
                <input required readonly name="longitude" id="longitude" type="text" class="w-full border rounded px-3 py-2 bg-gray-100" placeholder="Cliquez sur la carte">
            </div>
        </div>
        <div>
            <button type="button" id="openModal" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded transition">Choisir la position sur la carte</button>
            <p class="text-xs text-gray-500 mt-1">Cliquez ci-dessus pour sélectionner les coordonnées.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="contact_name">Nom du contact *</label>
                <input required name="contact_name" id="contact_name" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Nom">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="contact_phone">Téléphone du contact *</label>
                <input required name="contact_phone" id="contact_phone" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Téléphone">
            </div>
        </div>
        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded shadow">Enregistrer</button>
            <a href="/posts" class="text-gray-600 hover:text-gray-800 text-sm">Annuler</a>
        </div>
    </form>
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