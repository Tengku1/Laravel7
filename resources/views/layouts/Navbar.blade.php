<header>
    <div class="col-12 text-center pt-3 float-left">
        <h4 class="text-center"><a href="/">Crux</a></h4>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-light">
    <button class="navbar-toggler my-3" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            @php
            $url = request()->url();
            @endphp
            <li class="mx-1{{ Request::path() == '/' ? ' active' : '' }}">
                <a href="/" class="menuitem nav-item nav-link">
                    Dashboard <i class="fa fa-dashboard"></i>
                </a>
            </li>

            @if (Auth::user()->roles[0] == "Master")
            <li class="mx-1{{ strstr($url, "user") ? ' active' : '' }}">
                <a href="/user" class="menuitem nav-item nav-link">
                    User <i class="fa fa-user"></i>
                </a>
            </li>
            <li class="mx-1{{ strstr($url, "branch") ? ' active' : '' }}">
                <a href="/branch" class="menuitem nav-item nav-link">
                    Branch <i class="fa fa-dropbox"></i>
                </a>
            </li>
            <li class="mx-1{{ (strstr($url, "/product") && !(strstr($url, "/report/product"))) ? ' active' : '' }}">
                <a href="/product" class="menuitem nav-item nav-link">
                    Product <i class="fa fa-product-hunt"></i>
                </a>
            </li>
            @else
            <li class="mx-1{{ (strstr($url, "/product") && !(strstr($url, "/report/product"))) ? ' active' : '' }}">
                <a href="/product" class="menuitem nav-item nav-link">
                    Product <i class="fa fa-product-hunt"></i>
                </a>
            </li>
            <li class="mx-1{{ strstr($url, "stock") ? ' active' : '' }}">
                <a href="/stock" class="menuitem nav-item nav-link">
                    Stock <i class="fa fa-dropbox"></i>
                </a>
            </li>
            @endif
            <li class="mx-1{{ (strstr($url, "/sell") && !(strstr($url, "/report/sell"))) ? ' active' : '' }}">
                <a href="/market/sell" class="menuitem nav-item nav-link">
                    Sell Product <i class="fa fa-shopping-cart"></i>
                </a>
            </li>
            <li class="mx-1{{ (strstr($url, "/buy") && !(strstr($url, "/report/buy"))) ? ' active' : '' }}">
                <a href="/market/buy" class="menuitem nav-item nav-link">
                    Buy Product <i class="fa fa-shopping-bag"></i>
                </a>
            </li>
            <li class="nav-item dropdown{{ strstr($url, "/report") ? ' active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Reports <i class="fa fa-paperclip"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/report/products">Products</a>
                    <a class="dropdown-item" href="/report/buy">History Buy</a>
                    <a class="dropdown-item" href="/report/sell">History Sell</a>
                </div>
            </li>
            <li class="nav-item dropdown float-right">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    {{-- <a href="" class="dropdown-item">Dark Mode <i class="fa fa-moon-o"></i></a> --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </div>
    </div>
</nav>