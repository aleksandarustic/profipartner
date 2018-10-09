<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="{{ asset('img/logo.png') }}" alt="c Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{config('app.name')}}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img v-cloak :src="'/storage/profile_images/'+user.photo" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{Auth::user()->name}}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
       
       @role('superadministrator|administrator')
        <li class="nav-item">

          <router-link to="/dashboard/loaylty" class="nav-link">
            <i class="nav-icon fa fa-tachometer-alt"></i>
            <p>
              Loyalty Program
            </p>
          </router-link>

        </li>
        @endrole

        @role('user|administrator|superadministrator')

        <li class="nav-item has-treeview" :class="{'menu-open': subIsActive('/dashboard/manage')}">
          <a href="#" class="nav-link">
            <i class="nav-icon fa fa-cog"></i>
            <p>
              Manage
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @role('superadministrator')
            <li class="nav-item">
              <router-link to="/dashboard/manage/users" class="nav-link">
                <i class="fa fas fa-users nav-icon"></i>
                <p>Users</p>
              </router-link>
            </li>
            @endrole
            <li class="nav-item">
              <router-link to="/dashboard/manage/passport" class="nav-link">
                <i class="fas fa-toolbox nav-icon"></i>
                <p>Developer options</p>
              </router-link>
            </li>
          </ul>
        </li>
        
        @endrole

        <li class="nav-item">
          <router-link to="/dashboard/profile" class="nav-link">
            <i class="nav-icon fa fa-user"></i>
            <p>
              Profile
            </p>
          </router-link>
        </li>

        <li class="nav-item">

          <a href="#" class="nav-link" @click.prevent="logout">
            <i class="nav-icon fa fa-power-off"></i>
            <p>
              Logout
            </p>
          </a>

        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

