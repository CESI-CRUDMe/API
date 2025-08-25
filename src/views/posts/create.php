<style>
    #map {
        height: 180px;
    }

    #modal-create-post>form {
        max-width: 100px;
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />




<form action="/api/posts" method="post">



    <!-- Bouton pour ouvrir la modale -->
    <button id="openModal"
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition hover:scale-105">
        Ouvrir la modale
    </button>


</form>

<!-- Overlay de la modale -->
<div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">

    <!-- Contenu de la modale -->
    <div id="modalContent"
        class="bg-white rounded-lg shadow-2xl w-11/12 max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0">

        <!-- En-tête -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Choisire la position</h2>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <p class="text-blue-800 text-sm">
                    💡 Cliquer sur la cart et celle-ci enregistrera la position.
                </p>
            </div>

            <div id="map" style="width: 100%; height: 400px;"></div>
        </div>

        <div class="flex justify-end space-x-3 p-6 border-t border-gray-200">
            <button id="cancelBtn" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                Annuler
            </button>
        </div>
    </div>
</div>


<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>

    const map = L.map('map').setView([51.505, -0.09], 13);

    const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    map.on('click', function(event) {
        let latlng = event.latlng
        let lat = latlng.lat
        let long =latlng.lng
        console.log(lat);
        console.log(long);
        
        
    })

    $(document).ready(function () {
        // Fonction pour ouvrir la modale
        function openModal() {
            $('#modalOverlay').removeClass('hidden');
            setTimeout(function () {
                $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 10);
        }

        // Fonction pour fermer la modale
        function closeModal() {
            $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(function () {
                $('#modalOverlay').addClass('hidden');
            }, 300);
        }

        // Événements pour ouvrir la modale
        $('#openModal').click(openModal);

        // Événements pour fermer la modale
        $('#closeModal, #cancelBtn').click(closeModal);

        // Fermer en cliquant sur l'overlay
        $('#modalOverlay').click(function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Fermer avec la touche Échap
        $(document).keydown(function (e) {
            if (e.keyCode === 27 && !$('#modalOverlay').hasClass('hidden')) {
                closeModal();
            }
        });

        // Action du bouton confirmer
        $('#confirmBtn').click(function () {
            alert('Action confirmée !');
            closeModal();
        });

        $('form').on('submit', function (event) {
            event.preventDefault()
            return false;
        })
    });
</script>