<!-- Liens Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="modal fade" id="dateSessionVenteModal" tabindex="-1" aria-labelledby="dateSessionVenteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateSessionVenteModalLabel">Sélectionnez la date de la session de vente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" action="{{ route('vente.session-date') }}" method="POST" novalidate>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="dateSessionVente" class="col-form-label">Date:</label>
                       
                        <input type="date" value="{{ $sessionDate ?? '' }}" name="session_date" class="form-control"
                            id="dateSessionVente" required>
                        <!-- type="text" pour Flatpickr -->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#dateSessionVente", {
            dateFormat: "Y-m-d",
            defaultDate: "today",
            minDate: "today",
            locale: "French "
            // locale: Flatpickr.l10ns.fr // Utilise l'objet de localisation français
        });
    });
</script> --}}


{{-- 
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#dateSessionVente", {
            dateFormat: "Y-m-d", // Format de date souhaité
            defaultDate: "today", // Optionnel : définit la date actuelle comme valeur par défaut
            minDate: "today", // Optionnel : empêche la sélection de dates passées
            locale: "fr" // Définit la langue en français
        });
    });
</script>  --}}
