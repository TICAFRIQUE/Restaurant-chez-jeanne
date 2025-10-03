@extends('backend.layouts.master')

@section('content')
    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
        @slot('li_1')
            Stock
        @endslot
        @slot('title')
            Transfert de stock
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h3 class="mb-3 text-center text-primary">Transfert de stock de {{ $produit_source->libelle }} vers {{ $produit_destination->libelle }}</h3>
            <div class="card">
                <div class="card-body">
                    <form id="formSend" action="{{ route('stock-transfert.store') }}" method="POST" class="needs-validation"
                        novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="from_produit_id" class="form-label">Produit source <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $produit_source->libelle }}" disabled>

                                <div class="invalid-feedback">Veuillez choisir le produit source.</div>
                            </div>

                            <div class="col-md-2">
                                <label for="from_produit_id" class="form-label">stock disponible <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $produit_source->stock }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="to_produit_id" class="form-label">Produit destination <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $produit_destination->libelle }}"
                                    disabled>
                                <div class="invalid-feedback">Veuillez choisir le produit destination.</div>
                            </div>
                             <div class="col-md-2">
                                <label for="from_produit_id" class="form-label">stock disponible <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $produit_destination->stock }}" disabled>
                            </div>

                            <!-- QUANTITE A TRANSFERER -->
                            <div class="col-md-4">
                                <label for="quantite_bouteille" class="form-label">Quantité (bouteille) à transferer</label>
                                <input type="number" min="0" step="1" name="quantite_bouteille"
                                    id="quantite_bouteille" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="quantite_verre" class="form-label">Quantité (verre) dans une bouteille</label>
                                <input type="number" min="0" step="1" name="quantite_verre"
                                    id="quantite_verre" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="quantite_total" class="form-label">Quantité totale (unité sortie)</label>
                                <input type="number" min="0" step="1" name="quantite_total"
                                    id="quantite_total" class="form-control" placeholder="0" disabled>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="date_transfert" class="form-label">Date du transfert <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_transfert" id="date_transfert" class="form-control"
                                    required>
                                <div class="invalid-feedback">Veuillez choisir la date du transfert.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="code" class="form-label">Code transfert</label>
                                <input type="text" name="code" id="code" class="form-control"
                                    placeholder="Code automatique ou manuel">
                            </div> --}}
                            <div class="col-md-12">
                                <label for="commentaire" class="form-label">Commentaire</label>
                                <textarea name="commentaire" id="commentaire" class="form-control" rows="2"
                                    placeholder="Commentaire sur le transfert"></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success w-lg">Valider le transfert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-product-create.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        // Validation Bootstrap
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Calcul de la quantite totale en fonction de la quantite en bouteille et en verre (jquery)
        $(document).ready(function() {
            $('#quantite_bouteille, #quantite_verre').on('input', function() {
                var quantite_bouteille = parseInt($('#quantite_bouteille').val()) || 0;
                var quantite_verre = parseInt($('#quantite_verre').val()) || 0;
                var quantite_total = quantite_bouteille * quantite_verre;
                $('#quantite_total').val(quantite_total);
            });
        });
    </script>
@endsection
