@if (request()->routeIs('home'))
  <!-- ======= Hero Slider Section ======= -->
  <section id="hero-slider" class="hero-slider">
    <div class="container-md" data-aos="fade-in">
      <div class="row">
        <div class="col-12">
          <div class="swiper sliderFeaturedPosts rounded-4">
            <div class="swiper-wrapper">
              @foreach ($choicesMedias as $choiceMedia)
                <div class="swiper-slide">
                  <div class="img-bg d-flex align-items-end"
                    style="background-image: url('{{ asset('storage/' . $choiceMedia->gambar) }}');">
                    <div class="img-bg-inner">
                      <h2><a href="{{ route('media.show', $choiceMedia->slug) }}" style="color: #fff;"
                          class="hero-title">{{ $choiceMedia->judul }}</a></h2>
                      <div class="post-meta" style="color: #fff;"><span
                          class="date">{{ $choiceMedia->category->nama_kategori }}</span> <span
                          class="mx-1">&bullet;</span>
                        <span>{{ $choiceMedia->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                      </div>
                      <p>{!! str()->limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $choiceMedia->konten)), 200) !!}</p>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            <div class="custom-swiper-button-next">
              <span class="bi-chevron-right"></span>
            </div>
            <div class="custom-swiper-button-prev">
              <span class="bi-chevron-left"></span>
            </div>

            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  </section><!-- End Hero Slider Section -->
@endif
