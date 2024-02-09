<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center">
            <div class="info">
                <a href="javascript:void(0)" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if (Route::is('dashboard')) active @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('domain.index') }}"
                        class="nav-link @if (Route::is('domain.index')) active @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Domains
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('countries.index') }}"
                        class="nav-link @if (Route::is('countries.*')) active @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Countries
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cities.index') }}"
                        class="nav-link @if (Route::is('cities.*')) active @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Cities
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('keywords.index') }}"
                        class="nav-link @if (Route::is('keywords.*')) active @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Additional Keywords
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
