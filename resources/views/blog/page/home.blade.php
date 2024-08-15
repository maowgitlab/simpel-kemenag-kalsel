@extends('blog.app')
@section('title', 'Kantor Kementerian Agama')
@section('main')
  @if ($latestMedias->count() > 0 || $trendingMedias->count() > 0)
    <div class="row g-5">
      @foreach ($latestMedias->take(1) as $latestMedia)
        <div class="col-lg-4">
          <div class="post-entry-1 lg">
            <div style="position: relative; display: inline-block;">
              <img src="{{ asset('storage/' . $latestMedia->gambar) }}" alt="" class="img-fluid rounded">
              <small class="m-0"
                style="position: absolute; bottom: 40px; left: 10px; background-color: #2C65E1; color: white; padding: 1px 3px; border-radius: 5px;">
                {{ $latestMedia->category->nama_kategori }}
              </small>
            </div>
            <div class="post-meta">
              <span>{{ $latestMedia->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') . ' WITA' }}</span>
            </div>
            <h2><a href="{{ route('media.show', $latestMedia->slug) }}" class="title-hover">{{ $latestMedia->judul }}</a>
            </h2>
            <p class="mb-4 d-block">{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $latestMedia->konten)), 200) !!}</p>

            <div class="d-flex align-items-center author">
              <div class="photo"><img src="{{ $latestMedia->user->avatar == 'user.png' ? asset('img/user.png') : asset('storage/' . $latestMedia->user->avatar) }}" alt="" class="img-fluid"></div>
              <div class="name">
                <h3 class="m-0 p-0">{{ $latestMedia->user->nama ?? $latestMedia->user->username }}
                </h3>
              </div>
            </div>
          </div>
        </div>
      @endforeach

      <div class="col-lg-8">
        <div class="row g-5">
          <div class="col-lg-4 border-start custom-border">
            @foreach ($latestMedias->skip(1)->take(3) as $latestMedia)
              <div class="post-entry-1">
                <div style="position: relative; display: inline-block;">
                  <img src="{{ asset('storage/' . $latestMedia->gambar) }}" alt="" class="img-fluid rounded">
                  <small class="m-0"
                    style="position: absolute; bottom: 40px; left: 10px; background-color: #2C65E1; color: white; padding: 1px 3px; border-radius: 5px;">
                    {{ $latestMedia->category->nama_kategori }}
                  </small>
                </div>
                <div class="post-meta">
                  <span>{{ $latestMedia->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') . ' WITA' }}</span>
                </div>
                <h2><a href="{{ route('media.show', $latestMedia->slug) }}" class="title-hover">{{ $latestMedia->judul }}</a></h2>
              </div>
            @endforeach
          </div>
          <div class="col-lg-4 border-start custom-border">
            @foreach ($latestMedias->skip(4)->take(3) as $latestMedia)
              <div class="post-entry-1">
                <div style="position: relative; display: inline-block;">
                  <img src="{{ asset('storage/' . $latestMedia->gambar) }}" alt="" class="img-fluid rounded">
                  <small class="m-0"
                    style="position: absolute; bottom: 40px; left: 10px; background-color: #2C65E1; color: white; padding: 1px 3px; border-radius: 5px;">
                    {{ $latestMedia->category->nama_kategori }}
                  </small>
                </div>
                <div class="post-meta">
                  <span>{{ $latestMedia->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') . ' WITA' }}</span>
                </div>
                <h2><a href="{{ route('media.show', $latestMedia->slug) }}" class="title-hover">{{ $latestMedia->judul }}</a></h2>
              </div>
            @endforeach
          </div>
          <!-- Trending Section -->
          <div class="col-lg-4">
            <div class="trending">
              <h3>Trending</h3>
              <ul class="trending-post">
                @foreach ($trendingMedias->take(5) as $trendingMedia)
                  <li>
                    <a href="{{ route('media.show', $trendingMedia->slug) }}">
                      <span class="number">{{ $loop->iteration }}</span>
                      <h3><span class="title-hover">{{ $trendingMedia->judul }}</span>
                      </h3>
                      <span
                        class="author">{{ str()->limit($trendingMedia->user->nama ?? $trendingMedia->user->username, 25) }}</span>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div> <!-- End Trending Section -->
        </div>
      </div>

    </div> <!-- End .row -->
  @endif
@endsection
