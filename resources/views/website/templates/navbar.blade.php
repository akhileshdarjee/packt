<section class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-12 col-sm-4">
                <div class="contact-number">
                    <a href="tel:0121 265 6484">
                        <i class="tf-ion-ios-telephone"></i>
                        <span>0121 265 6484</span>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-12 col-sm-4">
                <div class="logo text-center">
                    <a href="{{ route('index') }}">
                        <img src="{{ asset('logo.png') }}" width="135" height="44">
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-12 col-sm-4">
                <ul class="top-menu text-right list-inline">
                    <li class="commonSelect">
                        <select class="form-control currency-selector" data-action="{{ route('default.currency') }}">
                            <option value="USD"{{ (session("defaultCurrency") == "USD") ? " selected" : "" }}>USD</option>
                            <option value="GBP"{{ (session("defaultCurrency") == "GBP") ? " selected" : "" }}>GBP</option>
                            <option value="EUR"{{ (session("defaultCurrency") == "EUR") ? " selected" : "" }}>EUR</option>
                            <option value="INR"{{ (session("defaultCurrency") == "INR") ? " selected" : "" }}>INR</option>
                            <option value="AUD"{{ (session("defaultCurrency") == "AUD") ? " selected" : "" }}>AUD</option>
                        </select>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="menu">
    <nav class="navbar navigation">
        <div class="container">
            <div class="navbar-header">
                <h2 class="menu-title">Main Menu</h2>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse text-center">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="{{ route('index') }}">Home</a>
                    </li>
                    <li class="dropdown">
                        <a href="{{ route('about') }}">About</a>
                    </li>
                    <li class="dropdown">
                        <a href="{{ route('products') }}">Products</a>
                    </li>
                    <li class="dropdown">
                        <a href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</section>
