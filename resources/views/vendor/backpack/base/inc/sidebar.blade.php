@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            @include('backpack::inc.sidebar_user_panel')

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                {{-- <li class="header">{{ trans('backpack::base.administration') }}</li> --}}
                <!-- ================================================ -->
                <!-- ==== Recommended place for admin menu items ==== -->
                <!-- ================================================ -->
            @if(auth()->user()->isEmployee() || auth()->user()->isGuest())
            <li>
                <a href="{{ url('employee/dashboard') }}">
                    <i class="fa fa-dashboard"></i> 
                    <span>{{ trans('backpack::base.dashboard') }}</span>
                </a>
            </li>
            @endif
            
            @if(auth()->user()->isAdmin())
            <li style="display: none;">
                <a href="{{ backpack_url('elfinder') }}">
                    <i class="fa fa-files-o"></i> 
                    <span>File manager</span>
                </a>
            </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-group"></i> 
                        <span>Utenti, Ruoli, Permessi</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/user') }}">
                                <i class="fa fa-user"></i> 
                                <span>Utenti</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/role') }}">
                                <i class="fa fa-group"></i> 
                                <span>Ruoli</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/permission') }}">
                                <i class="fa fa-key"></i> 
                                <span>Permessi</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ backpack_url('enterprise') }}">
                        <i class="fa fa-industry"></i> 
                        <span>Imprese</span>
                    </a>
                </li>
            @endif
            @if(auth()->user()->isCompanyManager())
                <li>
                    <a href="{{  url('/companyManager/employee') }}">
                        <i class="fa fa-user"></i> 
                        <span>Impiegati</span>
                    </a>
                </li>
                <li>
                    <a href="{{  url('/companyManager/guest') }}">
                        <i class="fa fa-user"></i> 
                        <span>Utenti Guest</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-building"></i>
                        <span>Siti</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ url('/companyManager/site') }}">
                                <i class="fa fa-building"></i>
                                <span>Siti</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/companyManager/sitetype') }}">
                                <i class="fa fa-files-o"></i>
                                <span>Tipi di Siti</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-podcast"></i>
                    <span>Sensori</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->isAdmin())
                        <li>
                            <a href="{{ url('/admin/sensorcatalog') }}">
                                <i class="fa fa-files-o"></i>
                                <span>Cataloghi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/admin/sensortype') }}">
                                <i class="fa fa-thermometer-3"></i>
                                <span>Tipi di Sensori</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/admin/brand') }}">
                                <i class="fa fa-feed"></i>
                                <span>Brand sensori</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isCompanyManager())
                        <li>
                            <a href="{{ url('/companyManager/sensor') }}">
                                <i class="fa fa-podcast"></i>
                                <span>Sensori</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isEmployee() || auth()->user()->isGuest())
                        <li>
                            <a href="{{ url('/employee/message') }}">
                                <i class="fa fa-envelope-o"></i>
                                <span>Messaggi</span>
                            </a>
                        </li>
                    <li>
                        <a href="{{ url('/employee/detection') }}">
                            <i class="fa fa-hdd-o"></i>
                            <span>Dati</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li>
                @impersonating
                    <a href="{{ route('impersonate.leave') }}"><i class="fa fa-eye-slash" aria-hidden="true"></i>&nbsp;Ritorna {{ App\User::find(session()->get('impersonated_by'))->name }}</a>
                @endImpersonating
            </li>
          <!-- ======================================= -->
          {{-- <li class="header">Other menus</li> --}}
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
