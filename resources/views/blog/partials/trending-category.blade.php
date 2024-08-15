@if (request()->routeIs('home'))
  @foreach ($trendingCategories->take(1) as $trendingCategory)
    <!-- ======= Culture Category Section ======= -->
    <section class="category-section">
      <div class="container" data-aos="fade-up">

        <div class="section-header d-flex justify-content-between align-items-center mb-5">
          <h3>{{ $trendingCategory->nama_kategori }}</h3>
          <div><a href="{{ route('media.category', $trendingCategory->slug) }}" class="more">Lihat Semua
              {{ $trendingCategory->nama_kategori }}</a></div>
        </div>

        <div class="row">
          <div class="col-md-9">

            @foreach ($trendingCategory->medias->take(1) as $media)
              <div class="d-lg-flex post-entry-2">
                <div class="me-4 thumbnail mb-4 mb-lg-0 d-inline-block">
                  <img
                    src="{{ asset('storage/' . $media->gambar) ?? asset('storage/' . asset('storage/' . $media->gambar)) }}"
                    alt="" class="img-fluid rounded">
                </div>
                <div>
                  <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                      class="mx-1">&bullet;</span>
                    <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                  </div>
                  <h3><a href="{{ route('media.show', $media->slug) }}" class="title-hover">{{ $media->judul }}</a></h3>
                  <p>{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 200) !!}</p>
                  <div class="d-flex align-items-center author">
                    <div class="photo"><img
                        src="{{ $media->user->avatar == 'user.png' ? asset('img/user.png') : asset('storage/' . $media->user->avatar) }}"
                        alt="" class="img-fluid"></div>
                    <div class="name">
                      <h3 class="m-0 p-0">{{ $media->user->nama ?? $media->user->username }}</h3>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

            <div class="row">
              <div class="col-lg-4">
                @foreach ($trendingCategory->medias->skip(1)->take(1) as $media)
                  <div class="post-entry-1 border-bottom">
                    <img src="{{ asset('storage/' . $media->gambar) }}" alt="" class="img-fluid rounded">
                    <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                        class="mx-1">&bullet;</span>
                      <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                        class="title-hover">{{ $media->judul }}</a>
                    </h2>
                    <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                    <p class="mb-4 d-block">{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 200) !!}</p>
                  </div>
                @endforeach

                @foreach ($trendingCategory->medias->skip(2)->take(1) as $media)
                  <div class="post-entry-1">
                    <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                        class="mx-1">&bullet;</span>
                      <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                        class="title-hover">{{ $media->judul }}</a></h2>
                    <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                  </div>
                @endforeach
              </div>
              <div class="col-lg-8">
                @foreach ($trendingCategory->medias->skip(3)->take(1) as $media)
                  <div class="post-entry-1">
                    <img src="{{ asset('storage/' . $media->gambar) }}" alt="" class="img-fluid rounded">
                    <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                        class="mx-1">&bullet;</span>
                      <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                        class="title-hover">{{ $media->judul }}</a></h2>
                    <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                    <p class="mb-4 d-block">{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 200) !!}</p>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="col-md-3">
            @foreach ($trendingCategory->medias->skip(4)->take(6) as $media)
              <div class="post-entry-1 border-bottom">
                <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                    class="mx-1">&bullet;</span>
                  <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
                <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                    class="title-hover">{{ $media->judul }}</a></h2>
                <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section><!-- End Culture Category Section -->
  @endforeach

  @foreach ($trendingCategories->skip(1)->take(1) as $trendingCategory)
    <!-- ======= Business Category Section ======= -->
    <section class="category-section">
      <div class="container" data-aos="fade-up">

        <div class="section-header d-flex justify-content-between align-items-center mb-5">
          <h3>{{ $trendingCategory->nama_kategori }}</h3>
          <div><a href="{{ route('media.category', $trendingCategory->slug) }}" class="more">Lihat Semua
              {{ $trendingCategory->nama_kategori }}</a></div>
        </div>

        <div class="row">
          <div class="col-md-9 order-md-2">

            @foreach ($trendingCategory->medias->take(1) as $media)
              <div class="d-lg-flex post-entry-2">
                <div class="me-4 thumbnail d-inline-block mb-4 mb-lg-0">
                  <img src="{{ asset('storage/' . $media->gambar) }}" alt="" class="img-fluid rounded">
                </div>
                <div>
                  <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                      class="mx-1">&bullet;</span>
                    <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                  </div>
                  <h3><a href="{{ route('media.show', $media->slug) }}" class="title-hover">{{ $media->judul }}</a>
                  </h3>
                  <p>{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 200) !!}</p>
                  <div class="d-flex align-items-center author">
                    <div class="photo"><img
                        src="{{ $media->user->avatar == 'user.png' ? asset('img/user.png') : asset('storage/' . $media->user->avatar) }}"
                        alt="" class="img-fluid"></div>
                    <div class="name">
                      <h3 class="m-0 p-0">{{ $media->user->nama ?? $media->user->username }}</h3>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

            <div class="row">
              <div class="col-lg-4">
                @foreach ($trendingCategory->medias->skip(1)->take(1) as $media)
                  <div class="post-entry-1 border-bottom">
                    <img src="{{ asset('storage/' . $media->gambar) }}" alt="" class="img-fluid rounded">
                    <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                        class="mx-1">&bullet;</span>
                      <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                        class="title-hover">{{ $media->judul }}</a>
                    </h2>
                    <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                    <p class="mb-4 d-block">{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 200) !!}</p>
                  </div>
                @endforeach

                @foreach ($trendingCategory->medias->skip(2)->take(1) as $media)
                  <div class="post-entry-1">
                    <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                        class="mx-1">&bullet;</span>
                      <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                        class="title-hover">{{ $media->judul }}</a></h2>
                    <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                  </div>
                @endforeach
              </div>
              <div class="col-lg-8">
                @foreach ($trendingCategory->medias->skip(3)->take(1) as $media)
                  <div class="post-entry-1">
                    <img src="{{ asset('storage/' . $media->gambar) }}" alt="" class="img-fluid rounded">
                    <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                        class="mx-1">&bullet;</span>
                      <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                        class="title-hover">{{ $media->judul }}</a></h2>
                    <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                    <p class="mb-4 d-block">{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 200) !!}</p>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="col-md-3">
            @foreach ($trendingCategory->medias->skip(4)->take(6) as $media)
              <div class="post-entry-1 border-bottom">
                <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                    class="mx-1">&bullet;</span>
                  <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
                <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                    class="title-hover">{{ $media->judul }}</a></h2>
                <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section><!-- End Business Category Section -->
  @endforeach

  @foreach ($trendingCategories->skip(2)->take(1) as $trendingCategory)
    @if ($trendingCategories->skip(2)->first()->medias_count >= 15)
      <!-- ======= Lifestyle Category Section ======= -->
      <section class="category-section">
        <div class="container" data-aos="fade-up">

          <div class="section-header d-flex justify-content-between align-items-center mb-5">
            <h3>{{ $trendingCategory->nama_kategori }}</h3>
            <div><a href="{{ route('media.category', $trendingCategory->slug) }}" class="more">Lihat Semua
                {{ $trendingCategory->nama_kategori }}</a></div>
          </div>

          <div class="row g-5">
            <div class="col-lg-4">
              @foreach ($trendingCategory->medias->take(1) as $media)
                <div class="post-entry-1 lg">
                  <img src="{{ asset('storage/' . $media->gambar) }}" alt="" class="img-fluid rounded">
                  <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                      class="mx-1">&bullet;</span>
                    <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                  </div>
                  <h2><a href="{{ route('media.show', $media->slug) }}" class="title-hover">{{ $media->judul }}</a>
                  </h2>
                  <p class="mb-4 d-block">{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 200) !!}</p>

                  <div class="d-flex align-items-center author">
                    <div class="photo"><img
                        src="{{ $media->user->avatar == 'user.png' ? asset('img/user.png') : asset('storage/' . $media->user->avatar) }}"
                        alt="" class="img-fluid">
                    </div>
                    <div class="name">
                      <h3 class="m-0 p-0">{{ $media->user->nama ?? $media->user->username }}</h3>
                    </div>
                  </div>
                </div>
              @endforeach

              @foreach ($trendingCategory->medias->skip(1)->take(1) as $media)
                <div class="post-entry-1 border-bottom">
                  <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                      class="mx-1">&bullet;</span>
                    <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                  </div>
                  <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                      class="title-hover">{{ $media->judul }}</a></h2>
                  <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                </div>
              @endforeach

              @foreach ($trendingCategory->medias->skip(2)->take(1) as $media)
                <div class="post-entry-1">
                  <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                      class="mx-1">&bullet;</span>
                    <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                  </div>
                  <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                      class="title-hover">{{ $media->judul }}</a>
                  </h2>
                  <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                </div>
              @endforeach

            </div>

            <div class="col-lg-8">
              <div class="row g-5">
                <div class="col-lg-4 border-start custom-border">
                  @foreach ($trendingCategory->medias->skip(3)->take(3) as $media)
                    <div class="post-entry-1">
                      <img src="{{ asset('storage/' . $media->gambar) }}" alt="" class="img-fluid rounded">
                      <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                          class="mx-1">&bullet;</span>
                        <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                      </div>
                      <h2><a href="{{ route('media.show', $media->slug) }}"
                          class="title-hover">{{ $media->judul }}</a></h2>
                    </div>
                  @endforeach
                </div>
                <div class="col-lg-4 border-start custom-border">
                  @foreach ($trendingCategory->medias->skip(4)->take(3) as $media)
                    <div class="post-entry-1">
                      <img src="{{ asset('storage/' . $media->gambar) }}" alt=""class="img-fluid rounded">
                      <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                          class="mx-1">&bullet;</span>
                        <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                      </div>
                      <h2><a href="{{ route('media.show', $media->slug) }}"
                          class="title-hover">{{ $media->judul }}</a></h2>
                    </div>
                  @endforeach
                </div>
                <div class="col-lg-4">
                  @foreach ($trendingCategory->medias->skip(5)->take(6) as $media)
                    <div class="post-entry-1 border-bottom">
                      <div class="post-meta"><span class="date">{{ $media->category->nama_kategori }}</span> <span
                          class="mx-1">&bullet;</span>
                        <span>{{ $media->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                      </div>
                      <h2 class="mb-2"><a href="{{ route('media.show', $media->slug) }}"
                          class="title-hover">{{ $media->judul }}</a></h2>
                      <span class="author mb-3 d-block">{{ $media->user->nama ?? $media->user->username }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

          </div> <!-- End .row -->
        </div>
      </section><!-- End Lifestyle Category Section -->
    @endif
  @endforeach
@endif
