<?php /* Page d'édition d'un post (réutilise logique création + valeurs pré-remplies) */ ?>
<?php if(!$post): ?>
<div class="max-w-2xl mx-auto p-6">
    <p class="text-red-600 font-semibold">Post introuvable.</p>
    <a href="/posts" class="inline-block mt-4 text-sm text-blue-600 hover:underline">← Retour</a>
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
                    float: { '0%, 100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-20px)' } },
                    fadeIn: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                }
            }
        }
    }
</script>
<style>
    .gradient-text { background: linear-gradient(45deg, #c9a9dd, #a8c8ec); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .glass-effect { backdrop-filter: blur(10px); background: rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); }
    .floating-element { animation: float 6s ease-in-out infinite; }
    .floating-element:nth-child(1){animation-delay:0s;} .floating-element:nth-child(2){animation-delay:1s;} .floating-element:nth-child(3){animation-delay:2s;} .floating-element:nth-child(4){animation-delay:3s;} .floating-element:nth-child(5){animation-delay:4s;}
    #mapEdit { height: 320px; }
</style>
<script>
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
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold gradient-text mb-3">Modifier le Post #<?php echo htmlspecialchars($post['id']); ?></h1>
    <p class="text-base sm:text-lg opacity-90 mb-5 max-w-2xl mx-auto px-2">Mettez à jour les informations du post</p>
</header>

<div class="container mx-auto px-4 pb-16 max-w-3xl">
    <div class="glass-effect rounded-2xl p-6 sm:p-8 space-y-6 animate-fade-in">
        <div id="flashEdit" class="mb-4 hidden"></div>
        <form id="editPostForm" action="/api/posts/<?php echo htmlspecialchars($post['id']); ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>" />
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="title">Titre *</label>
                <input required name="title" id="title" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" value="<?php echo htmlspecialchars($post['title']); ?>">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="content">Contenu *</label>
                <textarea required name="content" id="content" rows="5" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm"><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="price">Prix (€) *</label>
                    <input required name="price" id="price" type="number" min="0" step="0.01" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" value="<?php echo htmlspecialchars($post['price']); ?>">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70">Latitude *</label>
                    <input required readonly name="latitude" id="latitude" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/50 text-gray-700 outline-none border border-white/40 text-sm" value="<?php echo htmlspecialchars($post['latitude']); ?>">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70">Longitude *</label>
                    <input required readonly name="longitude" id="longitude" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/50 text-gray-700 outline-none border border-white/40 text-sm" value="<?php echo htmlspecialchars($post['longitude']); ?>">
                </div>
            </div>
            <div>
                <button type="button" id="openMapEdit" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-blue-300 to-purple-300 hover:from-blue-400 hover:to-purple-400 font-semibold text-gray-700 text-sm shadow-sm hover:shadow-md transition">Ajuster sur la carte</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="contact_name">Nom du contact *</label>
                    <input required name="contact_name" id="contact_name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" value="<?php echo htmlspecialchars($post['contact_name']); ?>">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1 opacity-70" for="contact_phone">Téléphone du contact *</label>
                    <input required name="contact_phone" id="contact_phone" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/70 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" value="<?php echo htmlspecialchars($post['contact_phone']); ?>">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide mb-2 opacity-70" for="imageEdit">Image (optionnel)</label>
                <div id="imageDropzoneEdit" class="relative border-2 border-dashed border-purple-300/60 rounded-xl p-5 bg-white/40 hover:bg-white/60 transition cursor-pointer flex flex-col items-center justify-center text-center gap-3 <?php echo !empty($post['image_base64']) ? 'hidden' : ''; ?>">
                    <input id="imageEdit" name="image" type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    <div class="text-4xl">📷</div>
                    <p class="text-sm font-medium text-gray-700"><span class="hidden sm:inline">Glissez-déposez /</span> Cliquez pour choisir</p>
                    <p class="text-xs text-gray-500">PNG, JPG, WEBP, GIF &lt; 3MB</p>
                </div>
                <div id="imageDisplayEdit" class="mt-2 <?php echo !empty($post['image_base64']) ? '' : 'hidden'; ?>">
                    <img id="imagePreviewEdit" src="<?php echo !empty($post['image_base64']) ? htmlspecialchars($post['image_base64']) : ''; ?>" alt="Image sélectionnée" class="max-h-64 w-auto rounded-xl shadow-md object-contain mx-auto" />
                    <div class="flex justify-center gap-3 mt-4">
                        <button type="button" id="changeImageBtnEdit" class="px-4 py-2 rounded-full bg-gradient-to-r from-blue-300 to-purple-300 hover:from-blue-400 hover:to-purple-400 text-gray-800 text-xs font-semibold shadow-sm hover:shadow-md transition">Changer</button>
                        <button type="button" id="removeImageBtnEdit" class="px-4 py-2 rounded-full bg-gradient-to-r from-red-300 to-pink-300 hover:from-red-400 hover:to-pink-400 text-gray-800 text-xs font-semibold shadow-sm hover:shadow-md transition">Supprimer</button>
                    </div>
                </div>
                <input type="hidden" name="image_base64" id="imageBase64Edit" value="<?php echo !empty($post['image_base64']) ? htmlspecialchars($post['image_base64']) : ''; ?>" />
                <input type="hidden" name="remove_image" id="removeImageFlag" value="0" />
            </div>
            <div class="pt-2 flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-yellow-300 to-amber-300 hover:from-yellow-400 hover:to-amber-400 font-semibold text-gray-700 text-sm shadow-sm hover:shadow-md transition">Mettre à jour</button>
                <a href="/posts/<?php echo htmlspecialchars($post['id']); ?>" class="px-5 py-2.5 rounded-full bg-white/60 hover:bg-white text-gray-700 text-sm font-semibold border border-white/40 transition">Annuler</a>
            </div>
        </form>
    </div>
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
        const fileInput = document.getElementById('imageEdit');
        const hasFile = fileInput && fileInput.files.length > 0;
        let options;
        if(hasFile){
            const fd = new FormData(form);
            fd.delete('id'); // id dans l'URL
            options = { method: 'POST', body: fd }; // on utilisera override method ci-dessous
        } else {
            const fd = new FormData(form);
            const body = new URLSearchParams();
            fd.forEach((v,k)=>{ if(k!=='id') body.append(k,v); });
            options = { method: 'PUT', headers: { 'Content-Type':'application/x-www-form-urlencoded' }, body: body.toString() };
        }
        const id = <?php echo (int)$post['id']; ?>;
        const url = '/api/posts/' + id + (hasFile ? '' : '');
        try {
            const res = await authFetch(url, options);
            const data = await res.json().catch(()=>({}));
            if(!res.ok){ showFlash(data.message || 'Erreur lors de la mise à jour','error'); return; }
            showFlash('Post mis à jour');
            setTimeout(()=>{ window.location.href = '/posts/' + id; }, 1200);
        } catch(err){ showFlash(err.message || 'Erreur réseau','error'); }
    });

    // Modal carte édition
    const openBtn = document.getElementById('openMapEdit');
    const overlay = document.getElementById('modalOverlayEdit');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const confirmBtn = document.getElementById('confirmEditLocationBtn');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const preview = document.getElementById('coordPreviewEdit');
    // Added missing references
    const content = document.getElementById('modalContentEdit');
    const closeBtn = document.getElementById('closeModalEdit');
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

    const fileInputEdit = document.getElementById('imageEdit');
    const dzEdit = document.getElementById('imageDropzoneEdit');
    const imageDisplayEdit = document.getElementById('imageDisplayEdit');
    const previewImgEdit = document.getElementById('imagePreviewEdit');
    const changeImageBtnEdit = document.getElementById('changeImageBtnEdit');
    const removeImageBtnEdit = document.getElementById('removeImageBtnEdit');
    const base64InputEdit = document.getElementById('imageBase64Edit');
    const removeImageFlag = document.getElementById('removeImageFlag');

    const maxSize = 3 * 1024 * 1024;
    const allowedMime = ['image/jpeg','image/png','image/gif','image/webp'];

    function resetImageEdit(){
        fileInputEdit.value='';
        base64InputEdit.value='';
        removeImageFlag.value='1';
        previewImgEdit.src='';
        imageDisplayEdit.classList.add('hidden');
        dzEdit.classList.remove('hidden','ring-2','ring-purple-400');
    }
    function handleFileEdit(file){
        if(!file) return;
        if(!allowedMime.includes(file.type)){ showFlash('Type de fichier non supporté','error'); resetImageEdit(); return; }
        if(file.size > maxSize){ showFlash('Image trop grande (>3MB)','error'); resetImageEdit(); return; }
        const reader = new FileReader();
        reader.onload = e => {
            base64InputEdit.value = e.target.result;
            removeImageFlag.value='0';
            previewImgEdit.src = e.target.result;
            dzEdit.classList.add('hidden');
            imageDisplayEdit.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
    fileInputEdit?.addEventListener('change', e=> handleFileEdit(e.target.files[0]));
    changeImageBtnEdit?.addEventListener('click', ()=> fileInputEdit.click());
    removeImageBtnEdit?.addEventListener('click', ()=> resetImageEdit());
    ['dragenter','dragover'].forEach(evt=> dzEdit.addEventListener(evt, e=>{ e.preventDefault(); e.stopPropagation(); dzEdit.classList.add('bg-white/70'); }));
    ['dragleave','drop'].forEach(evt=> dzEdit.addEventListener(evt, e=>{ e.preventDefault(); e.stopPropagation(); dzEdit.classList.remove('bg-white/70'); }));
    dzEdit.addEventListener('drop', e=>{ const f = e.dataTransfer.files[0]; if(f){ fileInputEdit.files = e.dataTransfer.files; handleFileEdit(f);} });

    const formEdit = document.getElementById('editPostForm');
    formEdit.addEventListener('submit', async (e)=>{
        e.preventDefault();
        const hasFile = fileInputEdit && fileInputEdit.files.length > 0;
        let options;
        if(hasFile){
            const fd = new FormData(formEdit);
            fd.delete('id'); // éviter double id
            options = { method: 'POST', body: fd };
        } else {
            const fd = new FormData(formEdit);
            const body = new URLSearchParams();
            fd.forEach((v,k)=>{ if(k!=='id') body.append(k,v); });
            options = { method: 'PUT', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: body.toString() };
        }
        try {
            const res = await authFetch(formEdit.action, options);
            const data = await res.json().catch(()=>({}));
            if(!res.ok){ showFlash(data.message || 'Erreur lors de la mise à jour','error'); return; }
            showFlash('Post mis à jour');
            setTimeout(()=>{ window.location.href = '/posts/' + <?php echo (int)$post['id']; ?>; }, 1200);
        } catch(err){ showFlash(err.message || 'Erreur réseau','error'); }
    });
})();
</script>