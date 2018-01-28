<nav class="navbar navbar-default navbar-custom navbar-fixed-top" id="mainNav">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand page-scroll" href="#page-top">
                <img src="/img/logo.png" alt="logo sensorTool" class="" style="height: 2.5em">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <li class="text-center"><a href="{{ url('admin/login') }}">Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true" aria-haspopup="true">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" style="background: #222;border:0;">
                            <li class="text-center">
                                <a href="{{url('/admin')}}">
                                    Area riservata
                                </a>
                            </li>
                            <li class="text-center">
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();" >
                                    Logout
                                </a>
                            </li>

                            @impersonating
                                <li class="text-center">
                                    <a href="{{ route('impersonate.leave') }}"><i class="fa fa-eye-slash" aria-hidden="true"></i>&nbsp;Ritorna {{ App\User::find(session()->get('impersonated_by'))->name }}</a>
                                </li>
                            @endImpersonating

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>