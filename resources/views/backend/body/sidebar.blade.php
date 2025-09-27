<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{route('dashboard')}}" class="brand-link">
    <img src="{{asset('backend/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{asset('backend/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item {{ request()->routeIs('personas.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                    Personas
                    <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                    <a href="{{ route('personas.listar') }}" class="nav-link {{ request()->routeIs('personas.listar') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <i class="nav-icon fas fa-user"></i>
                        <p>Lista personas</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('usuarios.listar') }}" class="nav-link {{ request()->routeIs('usuarios.listar') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Lista usuarios</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('voluntarios.listar') }}" class="nav-link {{ request()->routeIs('voluntarios.listar') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <i class="nav-icon fas fa-hands-helping"></i>
                        <p>Voluntarios</p>
                    </a>
                    </li>
                </ul>
        </li>
        <li class="nav-item">
            <a href="" class="nav-link {{ request()->routeIs('lugares.listar') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marked-alt"></i>
                <p>Lugares</p>
            </a>
        </li>
         <li class="nav-item">
            <a href="{{ route('plantas.listar')}}"class="nav-link {{ request()->routeIs('plantas.listar') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tree"></i>
                <p>Plantas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('inventario.listar') }}" class="nav-link">
                <i class="nav-icon fas fa-leaf"></i>
                <p>Mi Inventario</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="" class="nav-link {{ request()->routeIs('compras.listar') ? 'active' : '' }}">
                <i class="nav-icon fas fa-receipt"></i>
                <p>Compras</p>
            </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
