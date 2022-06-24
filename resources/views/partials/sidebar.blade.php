<div class="left-side-menu">
    <div class="h-100" data-simplebar>
        <!-- User box -->
        <div class="user-box text-center">
            <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt="user-img" title="Mat Helme"
                class="rounded-circle avatar-md">
            <div class="dropdown">
                <span class="text-dark h5 mt-2 mb-1 d-block">{{ Auth::user()->name }}</span>
            </div>
            <p class="text-muted">{{ Auth::user()->email }}</p>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li class="menu-title">{{ __('Menu Principal') }}</li>
                <li>
                    <a href="{{ url('panel/dashboard') }}">
                        <i data-feather="airplay"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('panel/products') }}">
                        <i data-feather="list"></i>
                        <span>{{ __('Produtos') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('panel/customers') }}">
                        <i data-feather="users"></i>
                        <span>{{ __('Clientes') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('panel/sales') }}">
                        <i data-feather="shopping-cart"></i>
                        <span>{{ __('Vendas') }}</span>
                    </a>
                </li>
				@if (Auth::user()->role == 'admin')
					<li>
						<a href="{{ url('panel/users') }}">
							<i data-feather="users"></i>
							<span>{{ __('Usuários') }}</span>
						</a>
					</li>
				@endif
                <li>
                    <a href="#sidebarReports" data-bs-toggle="collapse">
                        <i data-feather="pie-chart"></i>
                        <span> {{ __('Relatórios') }} </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarReports">
                        <ul class="nav-second-level">
							@if (Auth::user()->role == 'admin')
                            	<li><a href="{{ url('panel/reports/sellers') }}">{{ __('Vendedores') }}</a></li>
							@endif
                            <li><a href="{{ url('panel/reports/products') }}">{{ __('Produtos') }}</a></li>
                        </ul>
                    </div>
                </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
