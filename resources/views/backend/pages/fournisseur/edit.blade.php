 <!-- Default Modals -->
 <div id="myModalEdit{{ $item['id'] }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="myModalLabel">Modification </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                 </button>
             </div>
             <div class="modal-body">

                 <form class="row g-3 needs-validation" method="post"
                     action="{{ route('fournisseur.update', $item['id']) }}" novalidate enctype="multipart/form-data">
                     @csrf
                     <div class="col-md-12">
                         <label for="validationCustom01" class="form-label">Nom du fournisseur</label>
                         <input type="text" value="{{ $item['nom'] }}" name="nom" class="form-control"
                             id="validationCustom01" required>
                         <div class="valid-feedback">
                             Looks good!
                         </div>
                     </div>

                     <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Telephone du fournisseur</label>
                        <input type="number" value="{{ $item['telephone'] }}" name="telephone" class="form-control"
                            id="validationCustom01" >
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>

                     <div class="col-md-12">
                         <label for="validationCustom01" class="form-label">Adresse du fournisseur</label>
                         <input type="text" value="{{ $item['adresse'] }}" name="adresse" class="form-control"
                             id="validationCustom01" >
                         <div class="valid-feedback">
                             Looks good!
                         </div>
                     </div>


                     <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Email du fournisseur</label>
                        <input type="email" value="{{ $item['email'] }}" name="email" class="form-control"
                            id="validationCustom01" >
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>

                     <div class="row">
                         <div class="col-md-2">
                             <img class="rounded-circle" src="{{ $item->getFirstMediaUrl('fournisseurImage') }}"
                                 width="50px" alt="">
                         </div>
                         <div class="col-md-10">
                             <label for="validationCustom01" class="form-label">Image du fournisseur</label>
                             <input type="file" name="image" class="form-control" id="validationCustom01">
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
             </div>
         </div><!-- /.modal-dialog -->
     </div><!-- /.modal -->
 </div><!-- end col -->

 {{-- @section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    
@endsection --}}
