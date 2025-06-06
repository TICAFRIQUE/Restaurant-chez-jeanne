<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <!-- Default Modals -->
            <div id="myModalAddLibelle" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
                style="display: none;">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Créer un nouveau libellé </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">

                            <form id="formSave" class="row g-3 needs-validation" method="post"
                                novalidate>
                                @csrf

                                {{-- <div class="col-md-12">
                                    <label for="validationCustom01" class="form-label">Categorie</label>
                                    <select name="categorie_depense_id" class="form-control" required>
                                        <option disabled selected value="">Selectionner</option>
                                        @foreach ($categorie_depense as $item)
                                            <option value="{{ $item['id'] }}"> {{ $item['libelle'] }} </option>
                                        @endforeach
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div> --}}
                                <input type="text" name="categorie_depense_id"  class="form-control categorieId" hidden>

                                <div class="col-md-12">
                                    <label for="validationCustom01" class="form-label">Libelle</label>
                                    <input type="text" name="libelle" class="form-control libelleDepense" id="validationCustom01"
                                        required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="validationCustom01" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="" cols="30" rows="10"></textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary ">Valider</button>
                        </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- end col -->
    </div><!-- end row -->
</div><!-- end col -->
</div>
<!--end row-->

{{-- @section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection --}}
