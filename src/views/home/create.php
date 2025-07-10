<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUDMe - Create</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <div class="container mx-auto">
        <form action="/api/posts" method="post">
            <input type="text" name="title" placeholder="Titre">
            <input type="text" name="content" placeholder="Contenu">
            <button type="submit">Créer</button>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $('form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/api/posts',
                    type: 'POST',
                    data: $(this).serialize(),
                }).done(function (data) {
                    console.log(data);
                }).fail(function (error) {
                    console.log(error);
                });
            });
        });
    </script>

</html>