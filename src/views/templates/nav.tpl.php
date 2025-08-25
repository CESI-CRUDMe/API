<?php /* Barre de navigation globale */ ?>
<?php if(session_status()===PHP_SESSION_NONE){ session_start(); } $logged = isset($_SESSION['auth']); ?>
<style>
/* Styles navigation responsive centralisés */
.nav-mobile-enter{max-height:0;opacity:0;transition:max-height .3s ease,opacity .25s ease}
.nav-mobile-open{max-height:200px;opacity:1}
.nav-mobile-open-margin{margin-top:1rem}
</style>
<nav class="container mx-auto px-4 py-6">
    <div class="glass-effect rounded-2xl px-6 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold gradient-text">CRUD me !</h1>
            <button id="nav-toggle" class="md:hidden p-2 rounded-lg bg-gradient-to-r from-purple-300 to-blue-300 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400" aria-expanded="false" aria-controls="nav-links">
                <svg id="nav-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="nav-icon-close" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div id="nav-links" class="hidden md:flex gap-4 items-center">
                <a href="/" class="bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">Accueil</a>
                <a href="/posts" class="bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">Posts</a>
                <?php if($logged): ?>
                <a href="/posts/create" class="bg-gradient-to-r from-green-300 to-emerald-300 hover:from-green-400 hover:to-emerald-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">+ Nouveau Post</a>
                <button id="logoutBtn" class="bg-gradient-to-r from-red-300 to-pink-300 hover:from-red-400 hover:to-pink-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">Déconnexion</button>
                <?php else: ?>
                <a href="/login" class="bg-gradient-to-r from-yellow-300 to-amber-300 hover:from-yellow-400 hover:to-amber-400 text-gray-700 font-bold py-2 px-6 rounded-full transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
        <div id="nav-links-mobile" class="md:hidden nav-mobile-enter overflow-hidden flex flex-col gap-3 mt-0">
            <a href="/" class="block text-center bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 text-gray-700 font-bold py-2 px-4 rounded-full transition">Accueil</a>
            <a href="/posts" class="block text-center bg-gradient-to-r from-purple-300 to-blue-300 hover:from-purple-400 hover:to-blue-400 text-gray-700 font-bold py-2 px-4 rounded-full transition">Posts</a>
            <?php if($logged): ?>
            <a href="/posts/create" class="block text-center bg-gradient-to-r from-green-300 to-emerald-300 hover:from-green-400 hover:to-emerald-400 text-gray-700 font-bold py-2 px-4 rounded-full transition">+ Nouveau Post</a>
            <button id="logoutBtnMobile" class="block text-center bg-gradient-to-r from-red-300 to-pink-300 hover:from-red-400 hover:to-pink-400 text-gray-700 font-bold py-2 px-4 rounded-full transition">Déconnexion</button>
            <?php else: ?>
            <a href="/login" class="block text-center bg-gradient-to-r from-yellow-300 to-amber-300 hover:from-yellow-400 hover:to-amber-400 text-gray-700 font-bold py-2 px-4 rounded-full transition">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<script>
(function(){
  function bindLogout(id){ const el=document.getElementById(id); if(!el) return; el.addEventListener('click', async ()=>{ try{ await fetch('/api/logout',{method:'POST'}); window.location.href='/'; }catch(e){ console.warn('logout fail',e);} }); }
  bindLogout('logoutBtn');
  bindLogout('logoutBtnMobile');
})();
</script>
