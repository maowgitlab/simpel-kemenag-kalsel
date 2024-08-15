<div class="col-md-3">
  <!-- ======= Sidebar ======= -->
  <div class="aside-block">

    <ul class="nav nav-pills custom-tab-nav mb-4" id="pills-tab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-trending-tab" data-bs-toggle="pill" data-bs-target="#pills-trending"
          type="button" role="tab" aria-controls="pills-trending" aria-selected="true">Trending</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-latest-tab" data-bs-toggle="pill" data-bs-target="#pills-latest"
          type="button" role="tab" aria-controls="pills-latest" aria-selected="false"
          tabindex="-1">Terbaru</button>
      </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
      <!-- Trending -->
      <div class="tab-pane fade show active" id="pills-trending" role="tabpanel" aria-labelledby="pills-trending-tab">
        @foreach ($trendingMedias->take(5) as $trendingMedia)
          <div class="post-entry-1 border-bottom">
            <div class="post-meta"><span class="date">{{ $trendingMedia->category->nama_kategori }}</span> <span
                class="mx-1">•</span>
              <span>{{ $trendingMedia->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</span></div>
            <h2 class="mb-2"><a href="{{ route('media.show', $trendingMedia->slug) }}"
                class="title-hover">{{ $trendingMedia->judul }}</a></h2>
            <span
              class="author mb-3 d-block">{{ str()->limit($trendingMedia->user->nama ?? $trendingMedia->user->username, 25) }}</span>
          </div>
        @endforeach
      </div> <!-- End Trending -->

      <!-- Latest -->
      <div class="tab-pane fade" id="pills-latest" role="tabpanel" aria-labelledby="pills-latest-tab">
        @foreach ($latestMedias->take(5) as $latestMedia)
          <div class="post-entry-1 border-bottom">
            <div class="post-meta"><span class="date">{{ $latestMedia->category->nama_kategori }}</span> <span
                class="mx-1">•</span>
              <span>{{ $latestMedia->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</span></div>
            <h2 class="mb-2"><a href="{{ route('media.show', $latestMedia->slug) }}"
                class="title-hover">{{ $latestMedia->judul }}</a></h2>
            <span
              class="author mb-3 d-block">{{ str()->limit($latestMedia->user->userDetail->nama_lurus ?? $latestMedia->user->username, 25) }}</span>
          </div>
        @endforeach
      </div> <!-- End Latest -->

    </div>
  </div>

  {{-- <div class="aside-block">
    <h3 class="aside-title">Video</h3>
    <div class="video-post">
      <a href="https://www.youtube.com/watch?v=AiFfDjmd0jU" class="glightbox link-video">
        <span class="bi-play-fill"></span>
        <img src="assets/img/post-landscape-5.jpg" alt="" class="img-fluid">
      </a>
    </div>
  </div><!-- End Video --> --}}

  <div class="aside-block">
    <h3 class="aside-title">Media</h3>
    <ul class="aside-links list-unstyled">
      @foreach ($categories as $category)
        <li><a href="{{ route('media.category', $category->slug) }}"><i class="bi bi-chevron-right"></i>
            {{ $category->nama_kategori }}</a></li>
      @endforeach
    </ul>
  </div><!-- End Categories -->

</div>
