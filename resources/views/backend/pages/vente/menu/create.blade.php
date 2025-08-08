{{-- @extends('backend.layouts.master')
@section('title')
    Vente Menu
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


    @component('backend.components.breadcrumb')
        @slot('li_1')
            Vente
        @endslot
        @slot('title')
            Point de Vente
        @endslot
    @endcomponent --}}
<style>
    .menu-image {
        max-width: 100%;
        /* Adapte l'image à la largeur de la colonne */
        height: auto;
        /* Garde les proportions */
        border-radius: 8px;
        /* Ajoute des coins arrondis (optionnel) */


    }

    .product-quantity {
        width: 50px;
    }
</style>

<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

<div class="shop-page-area pt-10 pb-100">
    <div class="container-fluid">
        @if (!$menu)
            <h3 class="text-center text-danger ">Le Menu du jour n'est pas encore disponible.</h3>
            @can('voir-menu')
                <div class="text-center mt-4">
                    <a href="{{ route('menu.create') }}" class="btn btn-success">Créer un nouveau menu</a>
                </div>
            @endcan
        @else
            <h1 class="text-center my-4">Menu du <span>{{ \Carbon\Carbon::parse($menu->date)->format('d/m/Y') }}</span>
            </h1>

            <?php $cartMenu = Session::get('cartMenu', []); ?>
            <div class="d-flex mt-4 ol-sm-12 col-md-12 col-lg-12 col-xl-12  m-auto">
                <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                    @foreach ($categories as $categorie => $plats)
                        <div class="card shadow col-12">
                            <div class="card-header bg-danger text-white">
                                <h5 class="m-0 text-white">{{ $categorie }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($plats as $platKey => $plat)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <div class="form-check">
                                                            <input type="checkbox" data-price="{{ $plat->prix }}"
                                                                id="plat_{{ $plat->id }}"
                                                                class="form-check-input plat-checkbox" name="plats[]"
                                                                value="{{ $plat->id }}">
                                                            <label for="plat_{{ $plat->id }}"
                                                                class="form-check-label fw-bold text-capitalize fs-6">
                                                                {{ $plat->nom }}
                                                            </label>
                                                            @if ($plat->complements->isNotEmpty() || $plat->garnitures->isNotEmpty())
                                                                <i class="fa fa-info-circle text-warning fs-6"
                                                                    data-bs-toggle="popover" data-bs-placement="top"
                                                                    data-bs-trigger="hover"
                                                                    data-bs-content="Choisir les garnitures et complements en fonction de la quantité du plat ."></i>
                                                            @endif
                                                            <div class="product-quantity mb-0"
                                                                data-product-id="{{ $plat->id }}">
                                                                <div class="cart-plus-minus">
                                                                    <div class="dec qtybutton"
                                                                        onclick="decreaseValue(this)">-</div>
                                                                    <input id="quantity"
                                                                        class="cart-plus-minus-box quantityPlat text-danger"
                                                                        type="text" name="quantity" value="1"
                                                                        min="1" readonly>
                                                                    <div class="inc qtybutton"
                                                                        onclick="increaseValue(this)">+</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <strong data-price="{{ $plat->prix }}"
                                                            class="price text-danger plat-price-display">
                                                            {{ number_format($plat->prix, 0, ',', ' ') }} FCFA

                                                            <input type="hidden" name="prix_{{ $plat->id }}"
                                                                class="hidden-plat-price" value="{{ $plat->prix }}">
                                                        </strong>

                                                        <div class="form-check mt-2">
                                                            <input type="checkbox"
                                                                class="form-check-input offert-checkbox"
                                                                data-plat-id="{{ $plat->id }}"
                                                                id="offert_{{ $plat->id }}">
                                                            <label class="form-check-label"
                                                                for="offert_{{ $plat->id }}">
                                                                Offert
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div
                                                            class="{{ $plat->garnitures->isNotEmpty() ? 'col-6' : 'col-12' }}">
                                                            @if ($plat->complements->isNotEmpty())
                                                                <p class="card-text fw-bold mt-3">Choisir des
                                                                    compléments :</p>
                                                                <form class="complement-form">
                                                                    @foreach ($plat->complements as $complementKey => $complement)
                                                                        <div class="form-check">
                                                                            <input type="checkbox"
                                                                                id="complement_{{ $platKey }}_{{ $complementKey }}"
                                                                                name="complements_{{ $platKey }}[]"
                                                                                class="form-check-input complement-checkbox"
                                                                                data-plat-id="{{ $plat->id }}"
                                                                                value="{{ $complement->id }}">
                                                                            <label
                                                                                for="complement_{{ $platKey }}_{{ $complementKey }}"
                                                                                class="form-check-label">
                                                                                {{ $complement->nom }}
                                                                            </label>

                                                                            <div class="product-quantity mb-0"
                                                                                data-product-id="{{ $complement->id }}">
                                                                                <div class="cart-plus-minus">
                                                                                    <div class="dec qtybutton"
                                                                                        onclick="decreaseValue(this)">-
                                                                                    </div>
                                                                                    <input id="quantity"
                                                                                        class="cart-plus-minus-box quantityComplement text-danger"
                                                                                        type="text" name="quantity"
                                                                                        value="1" min="1"
                                                                                        readonly>
                                                                                    <div class="inc qtybutton"
                                                                                        onclick="increaseValue(this)">+
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </form>
                                                            @endif
                                                        </div>
                                                        <div
                                                            class="{{ $plat->complements->isNotEmpty() ? 'col-6' : 'col-12' }}">
                                                            @if ($plat->garnitures->isNotEmpty())
                                                                <p class="card-text fw-bold mt-3">Choisir des
                                                                    garnitures :</p>
                                                                <form class="garniture-form">
                                                                    @foreach ($plat->garnitures as $garnitureKey => $garniture)
                                                                        <div class="form-check">
                                                                            <input type="checkbox"
                                                                                id="garniture_{{ $platKey }}_{{ $garnitureKey }}"
                                                                                name="garnitures_{{ $platKey }}[]"
                                                                                class="form-check-input garniture-checkbox"
                                                                                data-plat-id="{{ $plat->id }}"
                                                                                value="1">
                                                                            <label
                                                                                for="garniture_{{ $platKey }}_{{ $garnitureKey }}"
                                                                                class="form-check-label">
                                                                                {{ $garniture->nom }}
                                                                            </label>

                                                                            <div class="product-quantity mb-0"
                                                                                data-product-id="{{ $garniture->id }}">
                                                                                <div class="cart-plus-minus">
                                                                                    <div class="dec qtybutton"
                                                                                        onclick="decreaseValue(this)">-
                                                                                    </div>
                                                                                    <input id="quantity"
                                                                                        class="cart-plus-minus-box quantityGarniture text-danger"
                                                                                        type="text" name="quantity"
                                                                                        value="1" min="1"
                                                                                        readonly>
                                                                                    <div class="inc qtybutton"
                                                                                        onclick="increaseValue(this)">+
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endif
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const offertCheckboxes = document.querySelectorAll('.offert-checkbox');

        offertCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const platId = this.getAttribute('data-plat-id');
                const priceDisplay = document.querySelector(`#plat_${platId}`).closest(
                    '.card-body').querySelector('.plat-price-display');
                const hiddenPriceInput = document.querySelector(`#plat_${platId}`).closest(
                    '.card-body').querySelector('.hidden-plat-price');
                const originalPrice = parseFloat(document.querySelector(`#plat_${platId}`)
                    .dataset.price);

                if (this.checked) {
                    priceDisplay.textContent = '0 FCFA';
                    hiddenPriceInput.value = 0;
                } else {
                    priceDisplay.textContent = `${originalPrice.toLocaleString('fr-FR')} FCFA`;
                    hiddenPriceInput.value = originalPrice;
                }
            });
        });
    });
</script>
