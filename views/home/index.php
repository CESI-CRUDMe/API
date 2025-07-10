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
</head>

<body>
    <div class="container mx-auto">
        <?php foreach ($posts as $item): ?>
            <a href="/<?php echo $item['id']; ?>">
                <div class="card">
                    <h1><?php echo $item['title']; ?></h1>
                    <p><?php echo strlen($item['content']) > 100 ? substr($item['content'], 0, 100) . '...' : $item['content']; ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <style type="text/tailwindcss">
        .card {
            @apply bg-white p-4 rounded-md shadow-md;
        }
        .btn {
            @apply bg-blue-500 text-white p-2 rounded-md;
        }
        .container {
            @apply flex grid grid-cols-3 gap-4 justify-center;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('a[name="toggle-content"]').click(function () {
                $(this).parent().find('p').toggle();
                $(this).text($(this).text() === 'Voir plus' ? 'Voir moins' : 'Voir plus');
            });
        });

        $('a[name="toggle-content"]').click(function () {
            $(this).parent().find('p').();
            $(this).text($(this).text() === 'Voir plus' ? 'Voir moins' : 'Voir plus');
        });
    </script>

</html>