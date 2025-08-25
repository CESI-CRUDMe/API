<div class="min-h-screen flex items-center justify-center px-4 pb-24">
  <div class="w-full max-w-md glass-effect rounded-2xl p-8 space-y-6">
    <h1 class="text-2xl font-bold text-center gradient-text">Connexion</h1>
    <p class="text-sm text-center text-gray-600">Identifiez-vous pour créer / modifier / supprimer des posts.</p>
    <div id="loginFlash" class="hidden text-sm px-4 py-2 rounded"></div>
    <form id="loginForm" class="space-y-4" autocomplete="on">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1" for="username">Utilisateur</label>
        <input required id="username" name="username" type="text" class="w-full px-4 py-2 rounded-xl bg-white/80 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" placeholder="admin" />
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1" for="password">Mot de passe</label>
        <input required id="password" name="password" type="password" class="w-full px-4 py-2 rounded-xl bg-white/80 focus:bg-white outline-none border border-white/40 focus:border-purple-400 transition text-sm" placeholder="••••••" />
      </div>
      <button type="submit" class="w-full px-6 py-3 rounded-full bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 font-semibold text-gray-700 text-sm shadow-sm hover:shadow-md transition">Se connecter</button>
    </form>
    <div class="text-center text-xs text-gray-500">Démo simple – ne pas utiliser en production sans sécurisation.</div>
  </div>
</div>
<script>
(function(){
  const form = document.getElementById('loginForm');
  const flash = document.getElementById('loginFlash');
  function show(msg, type='ok'){
    flash.textContent = msg; flash.className = 'text-sm px-4 py-2 rounded ' + (type==='ok'?'bg-green-100 text-green-800':'bg-red-100 text-red-700'); flash.classList.remove('hidden');
  }
  form.addEventListener('submit', async e=>{
    e.preventDefault();
    const data = { username: form.username.value.trim(), password: form.password.value };
    try{
      const res = await fetch('/api/login',{ method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(data) });
      const js = await res.json().catch(()=>({}));
      if(!res.ok){ show(js.message||'Erreur identifiants','err'); return; }
      show('Connexion réussie');
      setTimeout(()=> window.location.href='/posts', 600);
    }catch(err){ show(err.message||'Erreur réseau','err'); }
  });
})();
</script>