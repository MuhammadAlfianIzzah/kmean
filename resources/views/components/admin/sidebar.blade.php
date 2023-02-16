  <ul class="navbar-nav bg-white sidebar sidebar-light accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
          <div class="sidebar-brand-text">Dashboard</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
          <a class="nav-link" href="{{ route('dashboard') }}">
              <i class="fas fa-fw fa-tachometer-alt"></i>
              <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">
      <!-- Heading -->
      <div class="sidebar-heading">
          Proses Kmean
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data" aria-expanded="true"
              aria-controls="data">
              <i class="fa-solid fa-database"></i>
              <span>Data</span>
          </a>
          <div id="data" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <a class="collapse-item" href="{{ route('data.transaksi') }}">Data Transaksi</a>
              </div>

          </div>

      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#kmean" aria-expanded="true"
              aria-controls="kmean">
              <i class="fa-solid fa-spinner"></i>
              <span>Proses Kmean</span>
          </a>
          <div id="kmean" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <a class="collapse-item" href="{{ route('hitung.kmean') }}">Hitung Kmean</a>
                  <a class="collapse-item" href="{{ route('riwayat.kmean') }}">Riwayat Perhitungan</a>
                  <a class="collapse-item" href="{{ route('manual.hitung.kmean') }}">Hitung Kmean munual</a>
              </div>

          </div>

      </li>


      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

  </ul>
