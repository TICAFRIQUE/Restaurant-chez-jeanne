@extends('backend.layouts.master')
@section('title')
    Tableau de bord
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Bonjour,
                                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                                    !</h4>
                                <p class="text-muted mb-0">Voici ce qui se passe avec votre restaurant aujourd'hui.</p>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <form action="javascript:void(0);">
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-auto">
                                            <div class="input-group input-group-lg">
                                                <input type="text"
                                                    class="form-control border-0 minimal-border shadow fs-5" id="horloge"
                                                    readonly>
                                                <input type="text"
                                                    class="form-control border-0 minimal-border shadow fs-5" id="date"
                                                    readonly>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-time-line me-2"></i>
                                                    <i class="ri-calendar-line"></i>
                                                </div>
                                            </div>
                                            <script>
                                                function mettreAJourHorloge() {
                                                    var maintenant = new Date();
                                                    var heures = maintenant.getHours().toString().padStart(2, '0');
                                                    var minutes = maintenant.getMinutes().toString().padStart(2, '0');
                                                    var secondes = maintenant.getSeconds().toString().padStart(2, '0');
                                                    document.getElementById('horloge').value = heures + ':' + minutes + ':' + secondes;

                                                    var options = {
                                                        weekday: 'long',
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: 'numeric'
                                                    };
                                                    var dateEnFrancais = maintenant.toLocaleDateString('fr-FR', options);
                                                    document.getElementById('date').value = dateEnFrancais;
                                                }

                                                setInterval(mettreAJourHorloge, 1000);
                                                mettreAJourHorloge(); // Appel initial pour afficher l'heure et la date immédiatement
                                            </script>
                                        </div>
                                        <!--end col-->

                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <a href=" {{ route('rapport.vente') }} ">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Ventes
                                                totales mois</p>
                                        </div>

                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class=""
                                                   >{{ number_format($montantTotalVentesMois , 0 , ',' , ' ') }}</span>
                                                FCFA </h4>

                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                                <i class="bx bx-money text-success"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </a>
                        <!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <a href=" {{ route('commande.index') }} ">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Commandes</p>
                                        </div>
                                        <div class="flex-shrink-0">

                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{number_format($nombreCommandes , 0 , ',' , ' ') }}">{{ $nombreCommandes }}</span></h4>
                                            {{-- <a href="" class="text-decoration-underline">Voir toutes les commandes</a> --}}
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                                <i class="bx bx-shopping-bag text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </a>

                        <!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <a href=" {{ route('etat-stock.index', ['statut' => 'alerte']) }} ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Produits en
                                                alerte</p>
                                        </div>
                                        <div class="flex-shrink-0">

                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $produitsEnAlerte }}">{{ $produitsEnAlerte }}</span>
                                            </h4>

                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                <i class="bx bx-bell text-danger"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>



                            <!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <a href=" {{ route('depense.index') }} ">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Depenses
                                                totales mois
                                            </p>
                                        </div>

                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class=""
                                                   >{{ number_format($montantTotalDepensesMois, 0, ',', ' ') }}</span>
                                                FCFA </h4>

                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                <i class="bx bx-wallet text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </a>

                        <!-- end card -->
                    </div><!-- end col -->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Revenus</h4>

                            </div><!-- end card header -->

                            {{-- <div class="card-header p-0 border-0 bg-light-subtle">
                                <div class="row g-0 text-center">
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Commandes</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1">$<span class="counter-value" data-target="22.89">0</span>k
                                            </h5>
                                            <p class="text-muted mb-0">Gains</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1"><span class="counter-value" data-target="367">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Remboursements</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                                            <h5 class="mb-1 text-success"><span class="counter-value"
                                                    data-target="18.92">0</span>%</h5>
                                            <p class="text-muted mb-0">Taux de conversion</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                            </div><!-- end card header --> --}}

                            <div class="card-body p-0 pb-2">
                                <div class="w-100">
                                    {{-- <div id="customer_impression_charts"
                                        data-colors='["--vz-primary", "--vz-success", "--vz-danger"]'
                                        data-colors-minimal='["--vz-light", "--vz-primary", "--vz-info"]'
                                        data-colors-saas='["--vz-success", "--vz-info", "--vz-danger"]'
                                        data-colors-modern='["--vz-warning", "--vz-primary", "--vz-success"]'
                                        data-colors-interactive='["--vz-info", "--vz-primary", "--vz-danger"]'
                                        data-colors-creative='["--vz-warning", "--vz-primary", "--vz-danger"]'
                                        data-colors-corporate='["--vz-light", "--vz-primary", "--vz-secondary"]'
                                        data-colors-galaxy='["--vz-secondary", "--vz-primary", "--vz-primary-rgb, 0.50"]'
                                        data-colors-classic='["--vz-light", "--vz-primary", "--vz-secondary"]'
                                        data-colors-vintage='["--vz-success", "--vz-primary", "--vz-secondary"]'
                                        class="apex-charts" dir="ltr">
                                    </div> --}}
                                    <div id="revenuChart"></div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->


                    <!-- end col -->
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Produits les plus vendus</h4>

                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                {{-- <th scope="col">Code</th> --}}
                                                <th scope="col">Produit</th>
                                                <th scope="col">Categorie</th>
                                                <th scope="col">Nombre de ventes</th>
                                                <th scope="col">Total vente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produitsLesPlusVendus as $item)
                                                <tr>
                                                    {{-- <td>
                                                        <a href="#"
                                                            class="fw-medium link-primary">#{{ $item->code }} </a>
                                                    </td> --}}
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                {{ $item->nom }}
                                                                {{-- <img src="{{ URL::asset($item->getFirstMediaUrl('ProduitImage')) }}"
                                                                    alt=" {{ $item->nom }} "
                                                                    class="avatar-xs rounded-circle material-shadow" /> --}}
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="text-success">{{ $item->categorie->famille == 'bar' ? 'BAR' : 'Restaurant' }}
                                                        </span>
                                                    </td>
                                                    <td> {{ $item->ventes_count }} </td>
                                                    <td>
                                                        <span> {{ $item->total_ventes }} </span>
                                                    </td>

                                                </tr><!-- end tr -->
                                            @endforeach

                                        </tbody><!-- end tbody -->
                                    </table><!-- end table -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end row-->

                {{-- <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Commandes récentes</h4>

                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col">Order ID</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Vendor</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Rating</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="apps-ecommerce-order-details"
                                                        class="fw-medium link-primary">#VZ2112</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}"
                                                                alt=""
                                                                class="avatar-xs rounded-circle material-shadow" />
                                                        </div>
                                                        <div class="flex-grow-1">Alex Smith</div>
                                                    </div>
                                                </td>
                                                <td>Clothes</td>
                                                <td>
                                                    <span class="text-success">$109.00</span>
                                                </td>
                                                <td>Zoetic Fashion</td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">Paid</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 fw-medium mb-0">5.0<span
                                                            class="text-muted fs-11 ms-1">(61 votes)</span></h5>
                                                </td>
                                            </tr><!-- end tr -->

                                        </tbody><!-- end tbody -->
                                    </table><!-- end table -->
                                </div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div>  --}}
                
                <!-- end row-->

            </div> <!-- end .h-100-->

        </div> <!-- end col -->


    </div>
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>




    {{-- <script>
        var options = {
            series: [{
                name: "Revenu",
                data: @json($data)
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            xaxis: {
                categories: @json($labels)
            }
        };

        var chart = new ApexCharts(document.querySelector("#revenuChart"), options);
        chart.render();
    </script> --}}


    <script>
        var options = {
            series: [{
                name: "Revenu",
                data: @json($data)
            }],
            chart: {
                type: 'bar', // Changer 'line' en 'bar'
                height: 350
            },
            // plotOptions: {
            //     bar: {
            //         borderRadius: 4,
            //         borderRadiusApplication: 'end',
            //         horizontal: true,
            //     }
            // },
            xaxis: {
                categories: @json($labels), // Affichage des mois en texte
                title: {
                    text: "Mois"
                }
            },
            yaxis: {
                title: {
                    text: "Revenu"
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#revenuChart"), options);
        chart.render();
    </script>
@endsection
