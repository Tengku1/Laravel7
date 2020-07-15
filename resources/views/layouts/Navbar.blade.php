<header>
    <div class="col-md-10 float-left text-center pt-3">
        <h4><a href="/">Crux</a></h4>
    </div>
    <div class="col-md-1 float-left text-right pr-4 userheader pt-2">
        @guest
        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        @if (Route::has('register'))
        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
        @endif
        @else
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        @endguest
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-light">
    <button class="navbar-toggler my-3" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        @if (Auth::user()->roles[0] == "Master")
        <div class="navbar-nav">
            @php
            $url = request()->url();
            @endphp
            <li class="mx-1{{ Request::path() == '/' ? ' active' : '' }}">
                <a href="/" class="menuitem nav-item nav-link">
                    Dashboard <i class="fa fa-dashboard"></i>
                </a>
            </li>
            <li class="mx-1{{ request()->is(strstr($url,"stock")) ? ' active' : '' }}">
                <a href="/stock" class="menuitem nav-item nav-link">
                    Branch <i class="fa fa-dropbox"></i>
                </a>
            </li>
            <li class="mx-1{{ request()->is(strstr($url,"history")) ? ' active' : '' }}">
                <a href="/history" class="menuitem nav-item nav-link">
                    History Sells <i class="fa fa-shopping-cart"></i>
                </a>
            </li>
        </div>
        @elseif(Auth::user()->roles[0] == "Admin")
        <div class="navbar-nav">
            @php
            $url = request()->url();
            @endphp
            <li class="mx-1{{ Request::path() == '/' ? ' active' : '' }}">
                <a href="/" class="menuitem nav-item nav-link">
                    Dashboard <i class="fa fa-dashboard"></i>
                </a>
            </li>
            <li class="mx-1{{ request()->is(strstr($url,"stock")) ? ' active' : '' }}">
                <a href="/stock" class="menuitem nav-item nav-link">
                    Stock <i class="fa fa-dropbox"></i>
                </a>
            </li>
            <li class="mx-1{{ request()->is(strstr($url,"history")) ? ' active' : '' }}">
                <a href="/history" class="menuitem nav-item nav-link">
                    History Sells <i class="fa fa-shopping-cart"></i>
                </a>
            </li>
        </div>
        @else
        {{abort(404)}}
        @endif
    </div>
</nav>