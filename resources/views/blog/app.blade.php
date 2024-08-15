<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('img/logo-kemenag.png') }}" rel="icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Inter:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('vendor/_blog/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/_blog/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/_blog/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/_blog/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/_blog/vendor/aos/aos.css') }}" rel="stylesheet">

  <!-- Template Main CSS Files -->
  <link href="{{ asset('vendor/_blog/css/variables.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/_blog/css/main.css') }}" rel="stylesheet">

  {{-- Sweet Alert --}}
  <script src="{{ asset('vendor/_blog/js/sweetalert2.all.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/_blog/css/sweetalert2.all.min.css') }}">

  {{-- Star Rating --}}
  <script src="https://cdn.jsdelivr.net/npm/lemonadejs/dist/lemonade.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@lemonadejs/rating/dist/index.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  {{-- Feedback --}}
  <script src="https://cdn.jsdelivr.net/npm/@betahuhn/feedback-js/dist/feedback-js.min.js" data-feedback-opts='{ 
        "id": "feedback", 
        "endpoint": "{{ route('feedback') }}", 
        "emailField": true,
        "inputPlaceholder": "Ketikan feedback disini...",
        "emailPlaceholder": "Alamat Email",
        "title": "Feedback Aplikasi",
        "failedTitle": "Oops, terjadi kesalahan",
        "failedMessage": "Jika masalah masih berlanjut, silahkan coba lagi.",
        "submitText": "Kirim",
        "backText": "Kembali",
        "success": "Mengirim...",
        "contactText": "Hubungi Saya",
        "contactLink": "mailto:robianoor@gmail.com",
        "typeMessage": "Feedback apa yang ingin disampaikan?",
        "position": "left",
        "primary": "green",
        "background": "white",
        "color": "black",
        "events": true,
        "types": {
          "umum": {
            "text": "Feedback Umum",
            "icon": "😁"
          },
          "saran": {
            "text": "Saya punya saran",
            "icon": "💡"
          },
          "bug": {
            "text": "Saya menemukan bug",
            "icon": "🐞"
          }
        }
      }'></script>

  <script>
    window.addEventListener('feedback-submit', (event) => {
      const email = event.detail.email;
      const message = event.detail.message;
      const feedbackType = event.detail.feedbackType;
      const id = event.detail.id;
      const url = event.detail.url;
      const emailRegex = /^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com)$/;

      // Validasi email dan pesan
      if (email === "" || message === "") {
        alert("Email dan pesan wajib diisi.");
        event.preventDefault(); // Mencegah pengiriman feedback otomatis
        return; // Tidak melanjutkan proses pengiriman
      }

      // Validasi format email
      if (!emailRegex.test(email)) {
        alert("Hanya email dengan domain resmi (@gmail.com, @yahoo.com, @outlook.com) yang diperbolehkan.");
        event.preventDefault(); // Mencegah pengiriman feedback otomatis
        return; // Tidak melanjutkan proses pengiriman
      }

      // Kirim data feedback secara manual
      const formData = new FormData();
      formData.append('id', id);
      formData.append('feedbackType', feedbackType);
      formData.append('message', message);
      formData.append('email', email);
      formData.append('url', url);

      fetch("{{ route('feedback') }}", {
          method: "POST",
          body: formData,
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
          }
        })
        .then(response => {
          if (response.ok) {
            Swal.fire({
              icon: 'success',
              title: 'Terima kasih!',
              text: 'Terima kasih atas feedback yang Anda berikan. ini sangat membantu dalam pengembangan website :).',
            })
          } else {
            alert("Gagal mengirim feedback. Silakan coba lagi.");
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("Terjadi kesalahan. Silakan coba lagi.");
        });
    });
  </script>

  @stack('styles')

  <!-- =======================================================
  * Template Name: ZenBlog
  * Template URL: https://bootstrapmade.com/zenblog-bootstrap-blog-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https:///bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  @include('blog.partials.header')

  <main id="main">

    {{-- Hero --}}
    @include('blog.partials.hero')

    {{-- Main --}}
    @include('blog.partials.main')

    {{-- Category --}}
    @include('blog.partials.trending-category')

    {{-- Footer --}}
  </main><!-- End #main -->

  @include('blog.partials.footer')

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('vendor/_blog/js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('vendor/_blog/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/_blog/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/_blog/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('vendor/_blog/vendor/aos/aos.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('vendor/_blog/js/main.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const spinButton = document.getElementById('spin');
      if (!spinButton) {
        return;
      }
      const form = spinButton.closest('form');

      form.addEventListener('submit', function() {
        spinButton.disabled = true;
        spinButton.innerHTML =
          '<span class="spinner-border text-white spinner-border-sm" role="status"></span> Tunggu...';
      });
    });
  </script>
  @stack('scripts')

</body>

</html>
