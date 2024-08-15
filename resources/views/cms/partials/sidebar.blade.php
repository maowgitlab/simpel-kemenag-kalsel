  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-speedometer"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-heading">Master</li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeis('media') || request()->routeIs('createMedia') || request()->routeIs('editMedia') || request()->routeIs('category') || request()->routeIs('createCategory') || request()->routeIs('editCategory') || request()->routeIs('tag') || request()->routeIs('createTag') || request()->routeIs('editTag') || request()->routeIs('comment') ? '' : 'collapsed' }}"
          data-bs-target="#components-nav1" data-bs-toggle="collapse" href="#">
          <i class="bi bi-collection"></i><span>Media</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav1"
          class="nav-content {{ request()->routeis('media') || request()->routeIs('createMedia') || request()->routeIs('editMedia') || request()->routeIs('category') || request()->routeIs('createCategory') || request()->routeIs('editCategory') || request()->routeIs('tag') || request()->routeIs('createTag') || request()->routeIs('editTag') || request()->routeIs('comment') ? '' : 'collapse' }}"
          data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('media') }}"
              class="{{ request()->routeis('media') || request()->routeIs('editMedia') || request()->routeIs('createMedia') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Data Media</span>
            </a>
          </li>
          @if (auth()->user()->role == 'admin')
            <li>
              <a href="{{ route('category') }}"
                class="{{ request()->routeis('category') || request()->routeIs('editCategory') || request()->routeIs('createCategory') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Kategori</span>
              </a>
            </li>
            <li>
              <a href="{{ route('comment') }}" class="{{ request()->routeis('comment') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Komentar Spam</span>
              </a>
            </li>
          @endif
          <li>
            <a href="{{ route('tag') }}"
              class="{{ request()->routeis('tag') || request()->routeIs('editTag') || request()->routeIs('createTag') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Tagar</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->

      @if (auth()->user()->role == 'admin')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeis('user') || request()->routeIs('createUser') || request()->routeIs('editUser') ? '' : 'collapsed' }}"
            data-bs-target="#components-nav4" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span>User</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav4"
            class="nav-content {{ request()->routeis('user') || request()->routeIs('createUser') || request()->routeIs('editUser') ? '' : 'collapse' }}"
            data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('user') }}"
                class="{{ request()->routeis('user') || request()->routeIs('editUser') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Data User</span>
              </a>
            </li>
            <li>
              <a href="{{ route('createUser') }}" class="{{ request()->routeIs('createUser') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Input User Baru</span>
              </a>
            </li>
          </ul>
        </li><!-- End Components Nav -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeis('listService') || request()->routeIs('createListService') || request()->routeIs('editListService') || request()->routeIs('service') || request()->routeIs('createService') || request()->routeIs('editService') || request()->routeIs('inbox') ? '' : 'collapsed' }}"
            data-bs-target="#components-nav5" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-bookmark"></i><span>Pelayanan</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav5"
            class="nav-content {{ request()->routeis('listService') || request()->routeIs('createListService') || request()->routeIs('editListService') || request()->routeIs('service') || request()->routeIs('createService') || request()->routeIs('editService') || request()->routeIs('inbox') ? '' : 'collapse' }}"
            data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('inbox') }}" class="{{ request()->routeIs('inbox') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Kotak Masuk</span>
              </a>
              <a href="{{ route('listService') }}"
                class="{{ request()->routeis('listService') || request()->routeIs('editListService') || request()->routeIs('createListService') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Kategori Pelayanan</span>
              </a>
              <a href="{{ route('service') }}"
                class="{{ request()->routeIs('service') || request()->routeIs('editService') || request()->routeIs('createService') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Data Pelayanan</span>
              </a>
            </li>
          </ul>
        </li><!-- End Components Nav -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('feedback.show') ? '' : 'collapsed' }}" href="{{ route('feedback.show') }}">
            <i class="bi bi-envelope"></i>
            <span>Feedback Pengguna</span>
          </a>
        </li>
      @endif

      <li class="nav-heading">Lainnya</li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('profile') ? '' : 'collapsed' }}" href="{{ route('profile') }}">
          <i class="bi bi-gear"></i>
          <span>Pengaturan Akun</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('logout') ? '' : 'collapsed' }}" href="{{ route('logout') }}">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </a>
      </li>

      @if (auth()->user()->role == 'admin')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeis('allMedia') || request()->routeIs('mediaByCategory') || request()->routeIs('mediaByTime') || request()->routeIs('popularMedia') || request()->routeIs('mostMediaCategory') || request()->routeIs('serviceIn') || request()->routeIs('serviceByCategory') || request()->routeIs('serviceStatuses') ? '' : 'collapsed' }}"
            data-bs-target="#components-nav6" data-bs-toggle="collapse" href="#">
            <i class="bi bi-printer"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav6"
            class="nav-content {{ request()->routeis('allMedia') || request()->routeIs('mediaByCategory') || request()->routeIs('mediaByTime') || request()->routeIs('popularMedia') || request()->routeIs('mostMediaCategory') || request()->routeIs('serviceIn') || request()->routeIs('serviceByCategory') || request()->routeIs('serviceStatuses') ? '' : 'collapse' }}"
            data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('allMedia') }}" class="{{ request()->routeIs('allMedia') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Semua Media</span>
              </a>
            </li>
            <li>
              <a href="{{ route('mediaByCategory') }}"
                class="{{ request()->routeIs('mediaByCategory') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Media Berdasarkan Kategori</span>
              </a>
            </li>
            <li>
              <a href="{{ route('mediaByTime') }}" class="{{ request()->routeIs('mediaByTime') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Media Rentang Waktu</span>
              </a>
            </li>
            <li>
              <a href="{{ route('popularMedia') }}" class="{{ request()->routeIs('popularMedia') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Media Paling Populer</span>
              </a>
            </li>
            <li>
              <a href="{{ route('mostMediaCategory') }}"
                class="{{ request()->routeIs('mostMediaCategory') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Kategori Media Terbanyak</span>
              </a>
            </li>
            {{-- <li>
          <a href="#" class="">
            <i class="bi bi-circle"></i><span>Feedback Pengguna Aplikasi</span>
          </a>
        </li> --}}
            <li>
              <a href="{{ route('serviceIn') }}" class="{{ request()->routeIs('serviceIn') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Pelayanan Masuk</span>
              </a>
            </li>
            <li>
              <a href="{{ route('serviceByCategory') }}"
                class="{{ request()->routeIs('serviceByCategory') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Pelayanan Berdasarkan Kategori</span>
              </a>
            </li>
            <li>
              <a href="{{ route('serviceStatuses') }}"
                class="{{ request()->routeIs('serviceStatuses') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Status Pelayanan</span>
              </a>
            </li>
          </ul>
        </li><!-- End Components Nav -->
      @endif
    </ul>

  </aside>
  <!-- End Sidebar-->
