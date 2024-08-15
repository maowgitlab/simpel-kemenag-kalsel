<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
  <div class="footer-content">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-4">
          <h3 class="footer-heading">Tentang Website ini</h3>
          <p>Selamat datang di website Penelitian Saya! Ini adalah website yang saya buat untuk penelitian
            skripsi saya. Website ini dirancang untuk mendukung berbagai kegiatan dan kebutuhan
            informasi di lingkungan Kementerian Agama Kalimantan Selatan. Melalui website ini, pengguna
            dapat mengakses Media Elektronik terupdate, serta beragam konten terkait lainnya dengan mudah dan cepat.
            Terima kasih telah mengunjungi website ini, semoga bermanfaat!</p>
        </div>
        <div class="col-6 col-lg-2">
          <h3 class="footer-heading">Navigation</h3>
          <ul class="footer-links list-unstyled">
            <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right"></i> Beranda</a></li>
            <li><a href="{{ route('service.show') }}"><i class="bi bi-chevron-right"></i> Pelayanan Publik</a></li>
          </ul>
        </div>
        <div class="col-6 col-lg-2">
          <h3 class="footer-heading">Media</h3>
          <ul class="footer-links list-unstyled">
            @foreach ($categories as $category)
              <li><a href="{{ route('media.category', $category->slug) }}"><i class="bi bi-chevron-right"></i>
                  {{ $category->nama_kategori }}</a></li>
            @endforeach
          </ul>
        </div>

        <div class="col-lg-4">
          <h3 class="footer-heading">Media Terupdate</h3>

          <ul class="footer-links footer-blog-entry list-unstyled">
            @foreach ($latestMedias->take(4) as $latestMedia)
              <li>
                <a href="{{ route('media.show', $latestMedia->slug) }}" class="d-flex align-items-center title-hover">
                  <img src="{{ asset('storage/' . $latestMedia->gambar) }}" alt="" class="img-fluid me-3">
                  <div>
                    <div class="post-meta d-block"><span
                        class="date">{{ $latestMedia->category->nama_kategori }}</span> <span
                        class="mx-1">&bullet;</span>
                      <span>{{ $latestMedia->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <span>{{ $latestMedia->judul }}</span>
                  </div>
                </a>
              </li>
            @endforeach
          </ul>

        </div>
      </div>
      <div class="row">
        <div class="col">
          <h3 class="footer-heading">Alamat</h3>
          <div class="mb-3">
            <ul class="footer-links list-unstyled">
              <li><i class="bi bi-geo-alt"></i> Jl. D. I. Panjaitan No.19, Antasan Besar, Kec. Banjarmasin Tengah,
                Kota Banjarmasin, Kalimantan Selatan 70123</li>
              <li><i class="bi bi-envelope"></i> <a href="mailto:UqyvO@example.com"
                  class="footer-link-more">kanwilkalsel@kemenag.go.id</a></li>
            </ul>
          </div>
          <iframe class="rounded-4" style="width: 100%; height: 350px;"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d694.0309630218421!2d114.59032847374154!3d-3.31609165038758!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de423c0e9adc917%3A0x17814b82b6a467f5!2sKanwil%20Kementerian%20Agama%20Provinsi%20Kalimantan%20Selatan!5e0!3m2!1sen!2sid!4v1678236992116!5m2!1sen!2sid"
            allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-legal">
    <div class="container">

      <div class="row justify-content-between">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          <div class="copyright">
            © Copyright <strong><span>ZenBlog</span></strong>. All Rights Reserved
          </div>

          <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/herobiz-bootstrap-business-template/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
          </div>

        </div>

        <div class="col-md-6">
          <div class="social-links mb-3 mb-lg-0 text-center text-md-end">
            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="google-plus"><i class="bi bi-skype"></i></a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>

        </div>

      </div>

    </div>
  </div>

</footer>
