<div class="header-middle">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-12 col-sm-4">
                <div class="logo">
                    @if ($setting != null)
                        <a href="{{ route('accueil') }}">
                            <img alt="" src="{{ URL::asset($setting->getFirstMediaUrl('logo_header')) }}"
                                alt="" width="50">
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-9 col-md-8 col-12 col-sm-8">
                <div class="header-middle-right f-right">
                    <div class="header-login px-3">
                        @auth
                            <!-- Si l'utilisateur est connecté, afficher un dropdown avec "Mon profil" et "Mes commandes" -->
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="header-icon-style">
                                        <i class="icon-user icons"></i>
                                    </div>
                                    <div class="login-text-content">
                                        <p>{{ Auth::user()->name }} <br> <span>Mon compte</span></p>
                                        <p> {{Auth::user()->first_name}} </p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.profile') }}">Mon profil</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.commande') }}">Mes commandes</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.logout') }}">
                                            Se déconnecter
                                        </a>

                                    </li>
                                </ul>
                            </div>
                        @else
                            <!-- Si l'utilisateur n'est pas connecté, afficher l'option de login/inscription -->
                            <a href="{{ route('user.login') }}">
                                <div class="header-icon-style">
                                    <i class="icon-user icons"></i>
                                </div>
                                <div class="login-text-content">
                                    <p>S'inscrire <br> ou <span>Se connecter</span></p>
                                </div>
                            </a>
                        @endauth
                    </div>

                    {{-- <div class="header-wishlist">
                        <a href="wishlist.html">
                            <div class="header-icon-style">
                                <i class="icon-heart icons"></i>
                            </div>
                            <div class="wishlist-text">
                                <p>Your <br> <span>Wishlist</span></p>
                            </div>
                        </a>
                    </div> --}}
                    <div class="header-cart">
                        <a href="{{ route('panier') }}">
                            <div class="header-icon-style">
                                <i class="icon-handbag icons"></i>

                                <span
                                    class="count-style totalQuantity">{{ session('cart') ? Session::get('totalQuantity') : '0' }}</span>
                            </div>
                            <div class="cart-text">
                                <span class="digit">Mon panier</span>
                                <span
                                    class="cart-digit-bold totalPrice">{{ session('cart') ? Session::get('totalPrice') : '0' }}
                                    FCFA</span>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
