<div class="header-top bg-danger d-none d-lg-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                <div class="welcome-area">
                    <p class="d-flex justify-content-around">
                     <span>   <i class="ion-ios-restaurant"> </i>  {{ $setting->projet_description ?? 'Bienvenue chez restaurant Jeanne!' }}</span>
                      <span>  <i class="ion-ios-telephone"> </i>  {{ $setting->phone1 ?? '' }}</span>
                       <span> <i class="ion-ios-email"> </i>  {{ $setting->email1 ?? '' }}</span>
                        <span> <i class="ion-ios-location"> </i> {{ $setting->localisation ?? '' }}</span>
                    </p>
                </div>
            </div>
            {{-- <div class="col-lg-8 col-md-8 col-12 col-sm-8">
                <div class="account-curr-lang-wrap f-right">
                    <ul>
                        <li class="top-hover"><a href="#">  <i class=" icon-user"></i> Mon compte <i class="ion-chevron-down"></i></a>
                            <ul>
                                <li><a href="wishlist.html">Mes commandes </a></li>
                                <li><a href="login-register.html">Modifier mes infos</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</div>