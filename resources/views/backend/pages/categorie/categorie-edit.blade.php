@extends('backend.layouts.master')
@section('title')
   Categorie
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            categorie
        @endslot
        @slot('title')
            Modifier un categorie
        @endslot
    @endcomponent

    <div class="row">
      

        <div class="col-lg-10 m-auto">
            <div class="card">

                <div class="card-body">
                    <form class="row g-3 needs-validation" method="post"
                        action="{{ route('categorie.update', $data_categorie_edit['id']) }}" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">Modifier une categorie </label>
                            <input type="text" name="name" value="{{ $data_categorie_edit['name'] }}"
                                class="form-control" id="validationCustom01" placeholder="categorie1" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="validationCustom01" class="form-label">Position </label>
                            <select name="position" class="form-control">
                                @for ($i = 1; $i <= $data_count; $i++)
                                    <option value="{{ $i }}"
                                        {{ $data_categorie_edit['position'] == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>


                        {{-- <div class="col-md-8">
                            <label for="validationCustom01" class="form-label">Url</label>
                            <input type="text" name="url" class="form-control" id="validationCustom01"
                                value="{{ $data_categorie_edit['url'] }}">
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">Position</label>
                            <input type="text" name="position" class="form-control" id="validationCustom01"
                                placeholder="">
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div> --}}

                        <div class="col-md-2">
                            <label for="validationCustom01" class="form-label">Statut</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ $data_categorie_edit['status'] == 'active' ? 'selected' : '' }}>
                                    Activé
                                </option>
                                <option value="desactive"
                                    {{ $data_categorie_edit['status'] == 'desactive' ? 'selected' : '' }}>
                                    Desactivé
                                </option>
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-2 pt-4">
                            <button type="submit" class="btn btn-primary w-100 ">Modifier</button>
                        </div>

                </div>
               
                </form>
            </div>
        </div><!-- end row -->

          <!-- ========== Start categorie list ========== -->
        @include('backend.pages.categorie.categorie-list')
        <!-- ========== End categorie list ========== -->

    </div><!-- end col -->

    <!--end row-->

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
@endsection
@endsection
