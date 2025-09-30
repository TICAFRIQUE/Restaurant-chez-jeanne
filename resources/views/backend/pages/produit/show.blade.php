@extends('backend.layouts.master')
@section('title')
   Produit
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Detail produit
        @endslot
        @slot('title')
            Produit
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body border border-primary border-dashed">
                            <div class="mb-4 d-flex justify-content-around">

                                <p>Sku : <span class="fw-bold" id="sku">{{ $data_produit['code'] }} </span></p>
                                <p>Nom : <span class="fw-bold" id="sku">{{ $data_produit['nom'] }}
                                       
                                        {{ $data_produit['variante']['libelle'] ?? '' }} </span></p>
                                <p>Stock actuel : <span class="fw-bold" id="stock">{{ $data_produit['stock'] }}</span>
                                </p>
                                <p>Stock alerte : <span class="fw-bold text-danger"
                                        id="stockAlerte">{{ $data_produit['stock_alerte'] }}</span>
                                </p>
                                <p>Categorie : <span class="fw-bold"
                                        id="categorie">{{ $data_produit['categorie']['name'] }}</span>
                                </p>
                                <p>Prix : <span class="fw-bold" id="prix">{{ $data_produit['prix'] ?? 0 }}</span>
                                </p>


                            </div>
                        </div>


                       @if (count($data_produit->variantes) > 0) 
                       <div class="card-body border border-primary border-dashed">
                        <h5>Variantes des prix</h5>
                       <div class="d-flex justify-content-around">
                        @foreach ($data_produit->variantes as $key => $value)
                        <p>{{ $value->pivot->quantite }} : <span class="fw-bold" id="stock">{{ $value->libelle }}</span>
                         => pour la bouteille<br>Prix Unitaire : <span class="fw-bold" id="prix">{{ $value->pivot->prix }}</span> FCFA
                        </p>
                       
                    @endforeach
                       </div>
                       
                    </div>
                       @endif
                    </div>
                    <!-- end card -->

                   

                </div>


                <div class="text-center col-lg-2">
                    <div class="text-center">
                        <div class="position-relative d-inline-block">
                            <div class="avatar-lg">
                                <div class="avatar-title bg-light rounded" id="product-img">
                                    <img src="{{ asset($data_produit->getFirstMediaUrl('ProduitImage')) }}" id="product-img"
                                        class="avatar-md h-auto" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card d-none">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Liste des achats</h5>
                        {{-- <a href="{{ route('achat.create') }}" type="button" class="btn btn-primary ">Faire
                        un achat</a> --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Statut</th>
                                        <th>Produit</th>
                                        <th>code</th>
                                        <th>Magasin</th>
                                        <th>N°Facture</th>
                                        <th>fournisseur</th>
                                        <th>Format</th>
                                        <th>Qté format</th>
                                        <th>Qté dans format</th>
                                        <th>PU format</th>
                                        <th>Total depensé</th>
                                        <th>Qté stockée</th>
                                        <th>PU achat</th>
                                        <th>PU vente</th>
                                        <th>Unite de vente</th>
                                        <th>Crée par</th>
                                        <th>Date achat</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_produit['achats'] as $key => $item)
                                        <tr id="row_{{ $item['id'] }}">
                                            <td> {{ ++$key }} </td>
                                            <td>{{ $item['statut'] }}</td>

                                            <td>
                                                {{-- <img class="rounded-circle"
                                                src="{{ $item->produit->getFirstMediaUrl('ProduitImage') }}" width="50px"
                                                alt=""> --}}

                                                {{ $item['produit']['nom'] }}
                                            </td>
                                            <td>{{ $item['code'] }}</td>
                                            <td>{{ $item['magasin']['libelle'] ?? 'N/A' }}</td>
                                            <td>{{ $item['numero_facture'] ?? 'N/A' }}</td>
                                            <td>{{ $item['fournisseur']['nom'] ?? 'N/A' }}</td>
                                            <td>{{ $item['format']['libelle'] ?? 'N/A' }}</td>
                                            <td> {{ $item['quantite_format'] }} </td>
                                            <td> {{ $item['quantite_in_format'] }} </td>
                                            <td> {{ $item['prix_unitaire_format'] }} </td>
                                            <td> {{ $item['prix_total_format'] }} </td>
                                            <td> {{ $item['quantite_stocke'] }} </td>
                                            <td> {{ $item['prix_achat_unitaire'] }} </td>
                                            <td> {{ $item['prix_vente_unitaire'] }} </td>
                                            <td> {{ $item['unite']['libelle'] ?? 'N/A' }} </td>
                                            <td> {{ $item['user']['first_name'] }} </td>
                                            <td> {{ $item['date_achat'] }} </td>

                                            <td>
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill align-middle"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="{{ route('ajustement.create', $item['id']) }}"
                                                                class="dropdown-item"><i
                                                                    class=" ri-exchange-fill align-bottom me-2 text-muted"></i>
                                                                Ajustement</a>
                                                        </li>
                                                        {{-- <li><a href="#!" class="dropdown-item"><i
                                                                class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                            View</a>
                                                    </li> --}}
                                                        {{-- <li><a href="{{ route('produit.edit', $item['id']) }}" type="button"
                                                            class="dropdown-item edit-item-btn"><i
                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Edit</a></li> --}}
                                                        <li>
                                                            <a href="#" class="dropdown-item remove-item-btn delete"
                                                                data-id={{ $item['id'] }}>
                                                                <i
                                                                    class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                                Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- @include('backend.pages.produit.edit') --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    {{-- @include('backend.pages.produit.create') --}}
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.delete').on("click", function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Etes-vous sûr(e) de vouloir supprimer ?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer!',
                    cancelButtonText: 'Annuler',
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                        cancelButton: 'btn btn-danger w-xs mt-2',
                    },
                    buttonsStyling: false,
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: "/achat/delete/" + Id,
                            dataType: "json",

                            success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                        title: 'Supprimé!',
                                        text: 'Votre fichier a été supprimé.',
                                        icon: 'success',
                                        customClass: {
                                            confirmButton: 'btn btn-primary w-xs mt-2',
                                        },
                                        buttonsStyling: false
                                    })

                                    $('#row_' + Id).remove();
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
