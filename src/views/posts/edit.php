<?php /* Page d'édition d'un post (réutilise logique création + valeurs pré-remplies) */ ?>
<?php if(!$post): ?>
<div class="max-w-2xl mx-auto p-6">
    <p class="text-red-600 font-semibold">Post introuvable.</p>
    <a href="/posts" class="inline-block mt-4 text-sm text-blue-600 hover:underline">← Retour</a>
</div>
<?php return; endif; ?>
<style>
    #mapEdit { height: 320px; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Modifier le post #<?php echo htmlspecialchars($post['id']); ?></h1>
    <div id="flashEdit" class="mb-4 hidden"></div>
    <form id="editPostForm" action="/api/posts/<?php echo htmlspecialchars($post['id']); ?>" method="post" class="space-y-6">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>" />
        <div>
            <label class="block text-sm font-medium mb-1" for="title">Titre *</label>
            <input required name="title" id="title" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" value="<?php echo htmlspecialchars($post['title']); ?>">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="content">Contenu *</label>
            <textarea required name="content" id="content" rows="5" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring"><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium mb-1" for="price">Prix (€) *</label>
                <input required name="price" id="price" type="number" min="0" step="0.01" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" value="<?php echo htmlspecialchars($post['price']); ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Latitude *</label>
                <input required readonly name="latitude" id="latitude" type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="<?php echo htmlspecialchars($post['latitude']); ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Longitude *</label>
                <input required readonly name="longitude" id="longitude" type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="<?php echo htmlspecialchars($post['longitude']); ?>">
            </div>
        </div>
        <div>
            <button type="button" id="openMapEdit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded transition">Ajuster sur la carte</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="contact_name">Nom du contact *</label>
                <input required name="contact_name" id="contact_name" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" value="<?php echo htmlspecialchars($post['contact_name']); ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="contact_phone">Téléphone du contact *</label>
                <!-- pattern corrigé -->
                <input required name="contact_phone" id="contact_phone" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Téléphone" value="<?php echo htmlspecialchars($post['contact_phone']); ?>">
            </div>
        </div>
        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-5 py-2.5 rounded shadow">Mettre à jour</button>
            <a href="/posts/<?php echo htmlspecialchars($post['id']); ?>" class="text-gray-600 hover:text-gray-800 text-sm">Annuler</a>
        </div>
    </form>
</div>
<!-- Modal Map Edit -->
<div id="modalOverlayEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" aria-hidden="true" role="dialog" aria-modal="true">
    <div id="modalContentEdit" class="bg-white rounded-xl shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh] transform scale-95 opacity-0 transition duration-200 overflow-hidden">
        <div class="flex justify-between items-center px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Ajuster la position</h2>
            <button id="closeModalEdit" class="text-gray-500 hover:text-gray-700" aria-label="Fermer">✕</button>
        </div>
        <div class="px-5 py-4 space-y-3 overflow-y-auto">
            <p class="text-sm text-gray-600">Cliquez ou déplacez le marqueur pour changer les coordonnées.</p>
            <div id="mapEdit" class="rounded-md overflow-hidden ring-1 ring-gray-200"></div>
            <div class="text-xs text-gray-500" id="coordPreviewEdit">Lat: <?php echo htmlspecialchars($post['latitude']); ?>, Lng: <?php echo htmlspecialchars($post['longitude']); ?></div>
        </div>
        <div class="flex justify-end gap-3 px-5 py-4 border-t border-gray-200 bg-gray-50">
            <button id="cancelEditBtn" type="button" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">Annuler</button>
            <button id="confirmEditLocationBtn" type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">Valider</button>
        </div>
    </div>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function(){
    const form = document.getElementById('editPostForm');
    const flash = document.getElementById('flashEdit');
    function showFlash(msg, type='success'){
        flash.textContent = msg;
        flash.className = 'mb-4 px-4 py-2 rounded text-sm ' + (type==='success'?'bg-green-100 text-green-800':'bg-red-100 text-red-800');
        flash.classList.remove('hidden');
        setTimeout(()=>{ flash.classList.add('hidden'); }, 6000);
    }

    form.addEventListener('submit', async (e)=>{
        e.preventDefault();
        const fd = new FormData(form);
        const id = fd.get('id');
        const body = new URLSearchParams();
        fd.forEach((v,k)=> body.append(k,v));
        try {
            const res = await authFetch(form.action, { method: 'PUT', headers: { 'Content-Type':'application/x-www-form-urlencoded' }, body: body.toString() });
            const data = await res.json().catch(()=>({}));
            if(!res.ok){ showFlash(data.message || 'Erreur lors de la mise à jour','error'); return; }
            showFlash('Post mis à jour');
            setTimeout(()=>{ window.location.href = '/posts/' + id; }, 1200);
        } catch(err){ showFlash(err.message || 'Erreur réseau','error'); }
    });

    // Modal carte édition
    const openBtn = document.getElementById('openMapEdit');
    const overlay = document.getElementById('modalOverlayEdit');
    const content = document.getElementById('modalContentEdit');
    const closeBtn = document.getElementById('closeModalEdit');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const confirmBtn = document.getElementById('confirmEditLocationBtn');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const preview = document.getElementById('coordPreviewEdit');
    let map, marker, pending = null, initialized = false;

    function openModal(){ overlay.classList.remove('hidden'); overlay.classList.add('flex'); requestAnimationFrame(()=>{ content.classList.remove('scale-95','opacity-0'); content.classList.add('scale-100','opacity-100'); }); document.body.style.overflow='hidden'; if(!initialized){ initMap(); } setTimeout(()=> map.invalidateSize(), 250); }
    function closeModal(){ content.classList.add('scale-95','opacity-0'); content.classList.remove('scale-100','opacity-100'); setTimeout(()=>{ overlay.classList.add('hidden'); overlay.classList.remove('flex'); document.body.style.overflow=''; },180); }

    function initMap(){
        const startLat = parseFloat(latInput.value)||46.7;
        const startLng = parseFloat(lngInput.value)||2.5;
        map = L.map('mapEdit').setView([startLat,startLng], 8);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{ attribution: '&copy; OpenStreetMap' }).addTo(map);
        marker = L.marker([startLat,startLng],{draggable:true}).addTo(map);
        marker.on('dragend', ()=>{ pending = marker.getLatLng(); updatePreview(); });
        map.on('click', e=>{ pending = e.latlng; marker.setLatLng(pending); updatePreview(); });
        initialized = true;
    }
    function updatePreview(){ if(pending){ preview.textContent = 'Lat: '+pending.lat.toFixed(6)+', Lng: '+pending.lng.toFixed(6); } }

    openBtn.addEventListener('click', e=>{ e.preventDefault(); openModal(); });
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', e=>{ if(e.target===overlay) closeModal(); });
    confirmBtn.addEventListener('click', ()=>{ if(pending){ latInput.value = pending.lat.toFixed(6); lngInput.value = pending.lng.toFixed(6); showFlash('Coordonnées mises à jour'); closeModal(); }});
})();
</script>