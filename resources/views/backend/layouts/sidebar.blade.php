  <!-- ======= Sidebar ======= -->

  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="{{URL::to('/dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @role('admin')

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/client/list') }}">
          <i class="bi bi-menu-button-wide"></i><span>Clients list</span></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/pdf/list') }}">
          <i class="bi bi-menu-button-wide"></i><span>Pdf List</span></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/category/list') }}">
          <i class="bi bi-menu-button-wide"></i><span>Category List</span></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/factor/list') }}">
          <i class="bi bi-menu-button-wide"></i><span>Questions List</span></i>
        </a>
      </li>
      @endrole

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/make-report') }}">
          <i class="bi bi-menu-button-wide"></i><span>Generate Pdf</span></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/reports') }}">
          <i class="bi bi-menu-button-wide"></i><span>Reports</span></i>
        </a>
      </li>

    </ul>

  </aside><!-- End Sidebar-->
