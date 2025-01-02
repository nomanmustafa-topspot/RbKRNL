  <!-- ======= Sidebar ======= -->

  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="{{URL::to('/dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->


      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bx bxs-user-plus"></i><span>Clients</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ URL::to('/client/list') }}">
              <i class="bi bi-circle"></i><span>Clients list</span>
            </a>
          </li>
          <li>
            <a href="{{ URL::to('/add-client') }}">
              <i class="bi bi-circle"></i><span>Add New</span>
            </a>
          </li>

        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/pdf/list') }}">
          <i class="bi bi-menu-button-wide"></i><span>Pdf</span></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/category/list') }}">
          <i class="bi bi-menu-button-wide"></i><span>Question Category List</span></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/factor/list') }}">
          <i class="bi bi-menu-button-wide"></i><span>Questions List</span></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="{{ URL::to('/make-report') }}">
          <i class="bi bi-menu-button-wide"></i><span>Make Report</span></i>
        </a>
      </li>

      {{-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav-task" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Tasks</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav-task" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ URL::to('/create/task') }}">
              <i class="bi bi-circle"></i><span>Add Task</span>
            </a>
          </li>

          <li>
            <a href="{{ URL::to('/task/list') }}">
              <i class="bi bi-circle"></i><span>Tasks list</span>
            </a>
          </li>
        </ul>
      </li> --}}

    </ul>

  </aside><!-- End Sidebar-->
