<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0 font-size-18">
                <a href="#" class="btn btn-primary" id="goBack">
                    <i class="ri ri-arrow-left-fill"></i> Retour
                </a>
                {{ $title }}
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $li_1 }}</a></li>
                    @if (isset($title))
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Script intégré pour retour + rechargement -->
<script>
    document.getElementById('goBack').addEventListener('click', function(e) {
        e.preventDefault();

        const ref = document.referrer;

        if (ref && ref !== window.location.href) {
            window.location.href = ref; // Recharge la page précédente
        } else {
            window.history.back(); // Fallback si pas de referrer
        }
    });

    // Forcer le rechargement quand on revient avec le bouton navigateur
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>
<!-- end page title -->
