@extends('auth.app')
@section('title', 'Login')
@section('auth')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="card mb-3">
    <div class="card-body">

      <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4">Selamat Datang!</h5>
        <p class="text-center small">Silahkan gunakan username & password anda untuk login.</p>
      </div>

      <form class="row g-3" method="POST" action="{{ route('authenticate') }}">
        @csrf
        <div class="col-12">
          <label for="username" class="form-label fw-bold">Username</label>
          <div class="input-group has-validation">
            <input type="text" name="username"
              class="form-control @error('username') is-invalid
                        @enderror" id="username"
              autofocus>
            @error('username')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="col-12">
          <label for="password" class="form-label fw-bold">Password</label>
          <input type="password" name="password"
            class="form-control @error('password') is-invalid
                    @enderror" id="password">
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <button class="btn btn-primary w-100" type="submit" id="buttonLogin"><i class="bi bi-box-arrow-in-right"></i>
            Login</button>
        </div>
        <div class="col-12">
          <p class="small mb-0 text-center"><a href="{{ route('home') }}"><i class="bi bi-arrow-left"></i>
              Kembali</a></p>
        </div>
      </form>
      <script>
        const buttonLogin = document.getElementById('buttonLogin');
        const form = buttonLogin.closest('form'); // Ambil form terdekat dari button yang ditekan

        form.addEventListener('submit', function(event) {
          buttonLogin.disabled = true;
          buttonLogin.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Login...';
        });
      </script>
    </div>
  </div>
@endsection
