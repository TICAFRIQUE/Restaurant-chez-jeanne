
@extends('backend.layouts.master')
@section('title')
    @lang('translation.datatables')
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
          Liste des ventes
        @endslot
        @slot('title')
        Gestion des ventes
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Liste des ventes </strong></h5>
                    <a href="{{ route('vente.create') }}" type="button" class="btn btn-primary ">Effectuer
                        une nouvelle vente</a>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Caisse actuelle</h5>
                                <p class="card-text h3 text-primary">
                                    {{ auth()->user()->caisse->libelle ?? 'Non définie' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total des ventes du jour</h5>
                                <p class="card-text h3 text-success">
                                    {{ number_format($data_vente->sum('montant_total'), 0, ',', ' ') }} FCFA
                                </p>
                                <a href="{{route('vente.cloture-caisse')}}" class="btn btn-danger mt-3">Clôturer la caisse</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>N° de vente</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Vendu par</th>
                                    <th>Caisse</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data_vente as $key => $item)
                                    <tr id="row_{{ $item['id'] }}">
                                        <td> {{ $loop->iteration }} </td>
                                        <td> <a class="fw-bold" href="{{route('vente.show' , $item->id)}}">#{{ $item['code'] }}</a> </td>
                                        <td> {{ $item['created_at'] }} </td>
                                        <td> {{ number_format($item['montant_total'], 0, ',', ' ') }} FCFA </td>
                                        <td> {{ $item['user']['first_name'] }} {{ $item['user']['last_name'] }} </td>
                                        <td> {{ $item['caisse']['libelle'] ?? '' }} </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune vente trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

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
@endsection