<!-- ======= Post Grid Section ======= -->
<section id="{{ request()->routeIs('home') ? 'posts' : '' }}"
  class="{{ request()->routeIs('home') ? 'posts' : (request()->routeIs('media') ? 'single-post-content' : '') }}">
  <div class="container" {{ request()->routeIs('home') ? 'data-aos=fade-up' : '' }}>
    @yield('main')
  </div>
</section> <!-- End Post Grid Section -->
