@extends('backend.layouts.master')
@section('title')
    Paramètres
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-12 mt-5">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i> Informations du site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                                <i class="fas fa-cog"></i> Application
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#backup" role="tab">
                                <i class="fas fa-database"></i> Sauvegardes
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- Tab Informations du site -->
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">

                                    <!-- ========== Start Section ========== -->
                                    <div class="row mb-3">
                                        <div class="col-lg-4">
                                            <label for="background-image">Image d'arrière-plan</label>
                                            <input type="file" id="background-image" name="cover" class="form-control"
                                                accept="image/*">
                                            <div class="mt-2">
                                                <img id="background-preview"
                                                    src="{{ $data_setting ? URL::asset($data_setting->getFirstMediaUrl('cover')) : URL::asset('build/images/profile-bg.jpg') }}"
                                                    class="rounded-circle avatar-xl img-thumbnail"
                                                    alt="Aperçu de l'arrière-plan">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="logo-header">Logo d'en-tête</label>
                                            <input type="file" id="logo-header" name="logo_header" class="form-control"
                                                accept="image/*">
                                            <div class="mt-2 text-center">
                                                <img id="header-preview"
                                                    src="{{ $data_setting ? URL::asset($data_setting->getFirstMediaUrl('logo_header')) : URL::asset('images/avatar-1.jpg') }}"
                                                    class="rounded-circle avatar-xl img-thumbnail"
                                                    alt="Aperçu du logo d'en-tête">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="logo-footer">Logo de pied de page</label>
                                            <input type="file" id="logo-footer" name="logo_footer" class="form-control"
                                                accept="image/*">
                                            <div class="mt-2 text-center">
                                                <img id="footer-preview"
                                                    src="{{ $data_setting ? URL::asset($data_setting->getFirstMediaUrl('logo_footer')) : URL::asset('images/avatar-1.jpg') }}"
                                                    class="rounded-circle avatar-xl img-thumbnail"
                                                    alt="Aperçu du logo de pied de page">
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        function previewImage(input, previewId) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function(e) {
                                                    document.getElementById(previewId).src = e.target.result;
                                                }

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }

                                        document.getElementById('background-image').addEventListener('change', function() {
                                            previewImage(this, 'background-preview');
                                        });

                                        document.getElementById('logo-header').addEventListener('change', function() {
                                            previewImage(this, 'header-preview');
                                        });

                                        document.getElementById('logo-footer').addEventListener('change', function() {
                                            previewImage(this, 'footer-preview');
                                        });
                                    </script>
                                    <!-- ========== End Section ========== -->
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Titre du projet</label>
                                            <input type="text" name="projet_title" class="form-control" id="emailInput"
                                                value="{{ $data_setting['projet_title'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Description du projet</label>
                                            <input type="text" name="projet_description" class="form-control"
                                                id="emailInput" value="{{ $data_setting['projet_description'] ?? '' }}">
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Telephone1</label>
                                            <input type="text" name="phone1" class="form-control" id="phonenumberInput"
                                                value="{{ $data_setting['phone1'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Telephone2</label>
                                            <input type="text" name="phone2" class="form-control"
                                                id="phonenumberInput" value="{{ $data_setting['phone2'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Telephone3</label>
                                            <input type="text" name="phone3" class="form-control"
                                                id="phonenumberInput" value="{{ $data_setting['phone3'] ?? '' }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Email 1</label>
                                            <input type="email" name="email1" class="form-control" id="emailInput"
                                                value="{{ $data_setting['email1'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Email 2</label>
                                            <input type="email" name="email2" class="form-control" id="emailInput"
                                                value="{{ $data_setting['email2'] ?? '' }}">
                                        </div>
                                    </div>
                                    <!--end col-->


                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="countryInput" class="form-label">Siège social</label>
                                            <input type="text" name="siege_social" class="form-control"
                                                id="countryInput" value="{{ $data_setting['siege_social'] ?? '' }}" />
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="countryInput" class="form-label">Localisation</label>
                                            <input type="text" name="localisation" class="form-control"
                                                id="countryInput" value="{{ $data_setting['localisation'] ?? '' }}" />
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="countryInput" class="form-label">Google maps</label>
                                            <input type="text" name="google_maps" class="form-control"
                                                id="countryInput" value="{{ $data_setting['google_maps'] ?? '' }}" />
                                        </div>
                                    </div>

                                    <!--end col-->




                                    <!-- ========== Start social network ========== -->
                                    <div class="row mt-4">
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-primary material-shadow">
                                                    <i class=" ri-facebook-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="facebook_link" class="form-control"
                                                id="websiteInput" value="{{ $data_setting['facebook_link'] ?? '' }}">
                                        </div>
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-primary material-shadow">
                                                    <i class=" ri-instagram-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="instagram_link" class="form-control"
                                                id="websiteInput" value="{{ $data_setting['instagram_link'] ?? '' }}">
                                        </div>

                                        <div class=" mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-danger material-shadow">
                                                    <i class=" ri-tiktok-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="tiktok_link" class="form-control"
                                                id="pinterestName" value="{{ $data_setting['tiktok_link'] ?? '' }}">
                                        </div>
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-danger material-shadow">
                                                    <i class=" ri-linkedin-line"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="linkedin_link" class="form-control"
                                                id="pinterestName" value="{{ $data_setting['linkedin_link'] ?? '' }}">
                                        </div>

                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-danger material-shadow">
                                                    <i class=" ri-twitter-x-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="twitter_link" class="form-control"
                                                id="pinterestName" value="{{ $data_setting['twitter_link'] ?? '' }}">
                                        </div>
                                    </div>
                                    <!-- ========== End social network ========== -->


                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line"></i> Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>

                            </form>
                        </div>

                        <!-- Tab Application -->
                        <div class="tab-pane" id="privacy" role="tabpanel">
                            <div class="row g-4">
                                <!-- Cache système -->
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm">
                                                        <div
                                                            class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Cache système</h6>
                                                    <p class="text-muted mb-0">Supprimer les fichiers temporaires en
                                                        mémoire
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <button type="button" class="btn btn-outline-primary btn-clear">
                                                        <i class="ri-refresh-line"></i> Vider le cache
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mode maintenance -->
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm">
                                                        <div
                                                            class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                                            <i class="ri-tools-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Mode maintenance</h6>
                                                    <p class="text-muted mb-0">Mettre l'application en mode maintenance</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    @if ($data_maintenance == null || $data_maintenance['type'] == 'up')
                                                        <button type="button"
                                                            class="btn btn-outline-warning btn-mode-down">
                                                            <i class="ri-lock-line"></i> Activer
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-outline-success btn-mode-up">
                                                            <i class="ri-lock-unlock-line"></i> Désactiver
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Sauvegardes -->
                        <div class="tab-pane" id="backup" role="tabpanel">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="ri-database-2-line me-2"></i>Gestion des sauvegardes
                                    </h5>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-success btn-sm" id="createBackup">
                                            <i class="ri-add-line"></i> Créer une sauvegarde
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" id="deleteAllBackups">
                                            <i class="ri-delete-bin-line"></i> Supprimer tout
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if (count($backup) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><i class="ri-file-line me-1"></i>Nom du fichier</th>
                                                        <th><i class="ri-calendar-line me-1"></i>Date</th>
                                                        <th><i class="ri-file-text-line me-1"></i>Taille</th>
                                                        <th class="text-center"><i
                                                                class="ri-settings-line me-1"></i>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($backup as $file)
                                                        @php
                                                            $fileName = basename($file);
                                                            $filePath = storage_path(
                                                                'app/' . config('app.name') . '/' . $fileName,
                                                            );
                                                            $fileSize = file_exists($filePath)
                                                                ? number_format(filesize($filePath) / 1024 / 1024, 2)
                                                                : 'N/A';
                                                            $fileDate = file_exists($filePath)
                                                                ? date('d/m/Y H:i:s', filemtime($filePath))
                                                                : 'N/A';
                                                        @endphp
                                                        <tr id="backup-row-{{ $loop->index }}">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-xs me-2">
                                                                        <div
                                                                            class="avatar-title bg-success-subtle text-success rounded">
                                                                            <i class="ri-file-zip-line"></i>
                                                                        </div>
                                                                    </div>
                                                                    <span class="fw-medium">{{ $fileName }}</span>
                                                                </div>
                                                            </td>
                                                            <td>{{ $fileDate }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-info-subtle text-info">{{ $fileSize }}
                                                                    MB</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="btn-group" role="group">
                                                                    <a href="{{ route('setting.download-backup', $fileName) }}"
                                                                        class="btn btn-outline-primary btn-sm"
                                                                        title="Télécharger">
                                                                        <i class="ri-download-line"></i>
                                                                    </a>
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger btn-sm delete-backup"
                                                                        data-file="{{ $fileName }}"
                                                                        data-index="{{ $loop->index }}"
                                                                        title="Supprimer">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-4">
                                                <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                                    <i class="ri-database-line"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">Aucune sauvegarde trouvée</h5>
                                            <p class="text-muted">Créez votre première sauvegarde pour commencer</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Cache clear
            $('.btn-clear').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Vider le cache?',
                    text: "Cette action supprimera tous les fichiers de cache.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, vider!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('setting.cache-clear') }}",
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire('Succès!',
                                        'Le cache a été vidé avec succès.',
                                        'success');
                                }
                            }
                        });
                    }
                });
            });

            // Maintenance mode
            $('.btn-mode-down, .btn-mode-up').click(function(e) {
                e.preventDefault();
                let isDown = $(this).hasClass('btn-mode-down');
                let url = isDown ? "{{ route('setting.maintenance-down') }}" :
                    "{{ route('setting.maintenance-up') }}";
                let message = isDown ? 'activer' : 'désactiver';

                Swal.fire({
                    title: `${message.charAt(0).toUpperCase() + message.slice(1)} le mode maintenance?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Oui, ${message}!`,
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: url,
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire('Succès!',
                                            `Mode maintenance ${isDown ? 'activé' : 'désactivé'}.`,
                                            'success')
                                        .then(() => location.reload());
                                }
                            }
                        });
                    }
                });
            });

            // Créer une sauvegarde
            $('#createBackup').click(function() {
                Swal.fire({
                    title: 'Créer une sauvegarde?',
                    text: "Cette action peut prendre quelques minutes.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, créer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('setting.create-backup') }}",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: "json",
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Création en cours...',
                                    html: 'Veuillez patienter pendant la création de la sauvegarde.',
                                    timer: 30000,
                                    timerProgressBar: true,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                });
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Succès!',
                                            'Sauvegarde créée avec succès.', 'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('Erreur!', response.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Erreur!', 'Une erreur est survenue.',
                                    'error');
                            }
                        });
                    }
                });
            });

            // Supprimer une sauvegarde
            $('.delete-backup').click(function() {
                let fileName = $(this).data('file');
                let index = $(this).data('index');

                Swal.fire({
                    title: 'Supprimer cette sauvegarde?',
                    text: `Voulez-vous vraiment supprimer "${fileName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('setting.delete-backup') }}",
                            data: {
                                _token: '{{ csrf_token() }}',
                                file: fileName
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    $('#backup-row-' + index).fadeOut(400, function() {
                                        $(this).remove();
                                    });
                                    Swal.fire('Supprimée!',
                                        'La sauvegarde a été supprimée.', 'success');
                                } else {
                                    Swal.fire('Erreur!', response.message, 'error');
                                }
                            }
                        });
                    }
                });
            });

            // Supprimer toutes les sauvegardes
            $('#deleteAllBackups').click(function() {
                Swal.fire({
                    title: 'Supprimer TOUTES les sauvegardes?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, tout supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('setting.delete-all-backups') }}",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Supprimées!',
                                            'Toutes les sauvegardes ont été supprimées.',
                                            'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('Erreur!', response.message, 'error');
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>

    @endsection
