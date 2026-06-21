<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
 

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <!-- <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div> -->
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item {{ request()->routeIs('admin/dashboard') ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                
                <!-- ================= USER MANAGEMENT MAIN MENU ================= -->
                @canany(['permission_access','role_access','user_access'])
                <li class="nav-item 
                    {{ request()->routeIs('admin.permissions.*') 
                    || request()->routeIs('admin.roles.*') 
                    || request()->routeIs('admin.users.*') ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link 
                    {{ request()->routeIs('admin.permissions.*') 
                    || request()->routeIs('admin.roles.*') 
                    || request()->routeIs('admin.users.*') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            User Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">


                        @canany(['permission_access', 'role_access'])
                        <li class="nav-item {{ request()->routeIs('admin.permissions.*') || request()->routeIs('admin.roles.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.permissions.*') || request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Permissions & Role
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">

                                @can('permission_access')
                                <li class="nav-item {{ request()->routeIs('admin.permissions.*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-table"></i>
                                        <p>
                                            Permissions
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('permission_list')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.index','admin.permissions.edit') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Manage Permissions</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('permission_add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.permissions.create') }}" class="nav-link {{ request()->routeIs('admin.permissions.create') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Create Permission</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan

                                @can('role_access')
                                <li class="nav-item {{ request()->routeIs('admin.roles.*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-table"></i>
                                        <p>
                                            Access Roles
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('role_list')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.index','admin.roles.edit') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p> Manage Roles</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('role_add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.roles.create') }}" class="nav-link {{ request()->routeIs('admin.roles.create') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Create Role</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan

                            </ul>
                        </li>
                        @endcanany


                        @can('user_access')
                        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    User management
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                @can('user_access')
                                <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-table"></i>
                                        <p>
                                            Sub Admins
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('user_list')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index','admin.users.edit') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Manage Sub Admin</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('user_add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Create Sub Admin</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                    

                    </ul>
                </li>
                @endcanany
                @can('company_access')
                <li class="nav-item {{request()->routeIs('admin.companies.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{request()->routeIs('admin.companies.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>
                            Company Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @can('company_list')
                        <li class="nav-item">
                            <a href="{{ route('admin.companies.index') }}" class="nav-link {{request()->routeIs('admin.companies.index','admin.companies.edit') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Companies</p>
                            </a>
                        </li>
                        @endcan
                        @can('company_add')
                        <li class="nav-item">
                            <a href="{{ route('admin.companies.create') }}" class="nav-link {{request()->routeIs('admin.companies.create') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create Company</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('short_url_access')
                <li class="nav-item {{request()->routeIs('admin.shortUrls.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{request()->routeIs('admin.shortUrls.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>
                            Short Url Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @can('short_url_list')
                        <li class="nav-item">
                            <a href="{{ route('admin.shortUrls.index') }}" class="nav-link {{request()->routeIs('admin.shortUrls.index','admin.shortUrls.edit') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Short Url</p>
                            </a>
                        </li>
                        @endcan
                        @can('short_url_add')
                        <li class="nav-item">
                            <a href="{{ route('admin.shortUrls.create') }}" class="nav-link {{request()->routeIs('admin.shortUrls.create') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create Short Url</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>