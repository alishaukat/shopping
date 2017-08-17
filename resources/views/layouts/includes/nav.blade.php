<nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand @if(Request::route()->getName() == 'home') active @endif" href="{{ route('home') }}">Home</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class=""><a href="{{ route('products') }}" @if(Request::route()->getName() == 'products') active @endif>Products</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    $wishlistCount  = Cart::instance('wishlist')->count();
                    $cartCount      = Cart::instance('cart')->count();
                ?>
                <li><a href="{{ route('wishlist') }}">Wishlist (<span id="wishlist_count">{{ $wishlistCount }}</span>) </a></li>
                <li><a href="{{ route('cart') }}"><span class="glyphicon glyphicon-shopping-cart"></span> Cart (<span id="cart_count">{{ $cartCount }}</span>) </a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>