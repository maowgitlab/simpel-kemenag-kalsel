<!-- ======= Header ======= -->
<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

    <a href="{{ route('home') }}" class="logo d-flex align-items-center">
      <!-- Uncomment the line below if you also wish to use an image logo -->
      <img src="{{ asset('img/logo-kemenag.png') }}" alt="kemenag_logo">
      <h1 class="mt-2"><small class="d-block" style="font-size: 16px"><i class="bi bi-feather"></i>
          SIMPel</small>Kemenag Kalsel</h1>
    </a>

    <nav id="navbar" class="navbar">
      <ul>
        <li><a href="{{ route('home') }}"><span class="link-hover">Beranda</span></a></li>
        <li class="dropdown"><a href="#"><span class="link-hover">Media</span> <i
              class="bi bi-chevron-down dropdown-indicator"></i></a>
          <ul>
            @foreach ($categories as $category)
              <li><a href="{{ route('media.category', $category->slug) }}"><span
                    class="link-hover">{{ $category->nama_kategori }}</span></a></li>
            @endforeach
          </ul>
        </li>
        <li><a href="{{ route('service.show') }}"><span class="link-hover">Pelayanan Publik</span></a></li>
      </ul>
    </nav><!-- .navbar -->

    <div class="position-relative">
      <a href="#" class="mx-2 d-none d-lg-inline"><span class="bi-facebook"></span></a>
      <a href="#" class="mx-2 d-none d-lg-inline"><span class="bi-twitter"></span></a>
      <a href="#" class="mx-2 d-none d-lg-inline"><span class="bi-instagram"></span></a>

      <a href="#" class="mx-2 js-search-open"><span class="bi-search"></span></a>
      <i class="bi bi-list mobile-nav-toggle"></i>

      <!-- ======= Search Form ======= -->
      <div class="search-form-wrap js-search-form-wrap">
        <form onsubmit="event.preventDefault(); searchKeyword();" class="search-form">
          <span class="icon bi-search"></span>
          <input type="text" placeholder="Cari..." name="cari" class="form-control" id="search-input">
          <button class="btn js-search-close" type="button"><span class="bi-x"></span></button>
        </form>
      </div><!-- End Search Form -->

    </div>

  </div>

</header><!-- End Header -->
@push('scripts')
  <script>
    function searchKeyword() {
      var keyword = document.getElementById('search-input').value;
      if (keyword) {
        window.location.href = "{{ route('media.search', ':keyword') }}".replace(':keyword', encodeURIComponent(keyword));
      }
    }
  </script>
@endpush
