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
            @if(auth()->user()->isEmployee())
            <li>
                <a href="{{ backpack_url('dashboard') }}">
                    <i class="fa fa-dashboard"></i> 
                    <span>{{ trans('backpack::base.dashboard') }}</span>
                </a>
            </li>
            @endif
            
            @if(auth()->user()->isAdmin())
            <li>
                <a href="{{ backpack_url('elfinder') }}">
                    <i class="fa fa-files-o"></i> 
                    <span>File manager</span>
                </a>
            </li>
                <li>
                    <a href="{{ backpack_url('enterprise') }}">
                        <i class="fa fa-industry"></i> 
                        <span>Imprese</span>
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
            @endif
            @if(auth()->user()->isCompanyManager())
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-building"></i>
                        <span>Siti</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/site') }}">
                                <i class="fa fa-building"></i>
                                <span>Siti</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/sitetype') }}">
                                <i class="fa fa-files-o"></i>
                                <span>Tipi di Siti</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-podcast"></i>
                        <span>Sensori</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/sensor') }}">
                                <i class="fa fa-podcast"></i>
                                <span>Sensori</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/sensorcatalog') }}">
                                <i class="fa fa-files-o"></i>
                                <span>Cataloghi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/sensortype') }}">
                                <i class="fa fa-thermometer-3"></i>
                                <span>Tipi di Sensori</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/brand') }}">
                                <i class="fa fa-feed"></i>
                                <span>Brand sensori</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/message') }}">
                                <i class="fa fa-envelope-o"></i>
                                <span>Messaggi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/detection') }}">
                                <i class="fa fa-hdd-o"></i>
                                <span>Rilevazioni</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif


          <!-- ======================================= -->
          {{-- <li class="header">Other menus</li> --}}
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
