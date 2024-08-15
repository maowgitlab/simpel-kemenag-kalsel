@extends('auth.app')
@section('title', 'Verfikasi Akun')
@section('auth')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="card mb-3">
    <div class="card-body">

      <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4">Verifikasi Akun</h5>
        <p class="small">Silahkan generate token dibawah ini untuk aktivasi akun anda yang akan dikirim ke email:
          <u>{{ substr($user->email, 0, 1) . str_repeat('*', strpos($user->email, '@') - 2) . substr($user->email, strpos($user->email, '@') - 1) }}</u>
        </p>
      </div>

      <form class="row g-3" method="POST" action="{{ route('activate') }}">
        @csrf
        <div class="col-12">
          <label for="token" class="form-label fw-bold">Token</label>
          <div class="input-group">
            <input type="text" name="token" class="form-control @error('token') is-invalid @enderror" id="token"
              autofocus autocomplete="off">
            <button type="button" id="generateButton" class="btn btn-light input-group-text"
              data-email="{{ $user->email }}">Generate</button>
            @error('token')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <small id="countdown"><i></i></small>
        </div>

        <div class="col-12">
          <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-key"></i> Aktivasi
          </button>
        </div>
        <div class="col-12">
          <p class="small mb-0 text-center"><a href="{{ route('login') }}"><i class="bi bi-arrow-left"></i> Kembali</a>
          </p>
        </div>
      </form>

      <script>
        const generateButton = document.getElementById('generateButton');
        const countdownElement = document.getElementById('countdown');
        const cooldownTime = 60; // Waktu delay dalam detik

        function startCountdown(seconds) {
          const now = new Date().getTime();
          const endTime = now + seconds * 1000;

          const interval = setInterval(function() {
            const currentTime = new Date().getTime();
            const remainingTime = endTime - currentTime;

            if (remainingTime <= 0) {
              clearInterval(interval);
              countdownElement.textContent = '';
              generateButton.disabled = false;
              generateButton.innerHTML = 'Generate';
              localStorage.removeItem('generateCooldown');
            } else {
              const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
              const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
              countdownElement.textContent = `Silahkan tunggu ${minutes}m ${seconds}detik untuk generate token lagi.`;
            }
          }, 1000);
        }

        generateButton.addEventListener('click', function(event) {
          event.preventDefault(); // Mencegah tindakan default dari button

          if (navigator.onLine) {
            const email = generateButton.getAttribute('data-email');
            const route = "{{ route('verifyEmail', ['email' => ':email']) }}".replace(':email', encodeURIComponent(
            email));

            // Set cooldown timestamp
            const now = new Date().getTime();
            const cooldownTimestamp = now + cooldownTime * 1000;
            localStorage.setItem('generateCooldown', cooldownTimestamp);

            // Mulai hitungan mundur
            startCountdown(cooldownTime);

            generateButton.disabled = true;
            generateButton.innerHTML =
              '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Tunggu...';

            // Redirect ke halaman yang sesuai
            window.location.href = route;
          } else {
            // Menampilkan SweetAlert ketika offline
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Anda sedang offline. Silakan periksa koneksi internet Anda dan coba lagi.'
            });
          }
        });

        // Saat halaman dimuat, periksa apakah ada waktu cooldown yang tersisa
        document.addEventListener('DOMContentLoaded', function() {
          const generateCooldown = localStorage.getItem('generateCooldown');
          if (generateCooldown) {
            const now = new Date().getTime();
            const remainingTime = generateCooldown - now;
            if (remainingTime > 0) {
              generateButton.disabled = true;
              startCountdown(Math.floor(remainingTime / 1000));
            }
          }
        });

        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.addEventListener('click', function(event) {
          if (!navigator.onLine) {
            event.preventDefault(); // Mencegah pengiriman form ketika offline
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Anda sedang offline. Silakan periksa koneksi internet Anda dan coba lagi.'
            });
          }
        });
      </script>
    </div>
  </div>
@endsection
