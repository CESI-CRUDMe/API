<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUDMe - Accueil</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btn = document.getElementById('nav-toggle');
            if(!btn) return; // pas de nav sur cette vue
            const mobile = document.getElementById('nav-links-mobile');
            const iconOpen = document.getElementById('nav-icon-open');
            const iconClose = document.getElementById('nav-icon-close');
            let open = false;
            function sync(){
                if(open){
                    mobile && mobile.classList.add('nav-mobile-open','nav-mobile-open-margin');
                    iconOpen && iconOpen.classList.add('hidden');
                    iconClose && iconClose.classList.remove('hidden');
                    btn.setAttribute('aria-expanded','true');
                } else {
                    mobile && mobile.classList.remove('nav-mobile-open','nav-mobile-open-margin');
                    iconOpen && iconOpen.classList.remove('hidden');
                    iconClose && iconClose.classList.add('hidden');
                    btn.setAttribute('aria-expanded','false');
                }
            }
            btn.addEventListener('click', ()=>{ open = !open; sync(); });
            window.addEventListener('resize', ()=>{ if(window.innerWidth >= 768){ open = false; sync(); } });
            sync();
        });
    </script>
</head>

<body>
<?php require __DIR__ . '/nav.tpl.php'; ?>