@extends('cms.app')
@section('title', 'Profil')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  @if ($errors->any())
    <script>
      Swal.fire({
        title: "Gagal!",
        text: "Silahkan periksa pesan kesalahan pada Form Edit Profil atau Form ganti password.",
        icon: "error",
        showConfirmButton: true
      });
    </script>
  @endif
  <div class="pagetitle">
    <h1>Profil</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profil</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <img
              src="{{ auth()->user()->avatar == 'user.png' ? asset('img/user.png') : asset('storage/' . auth()->user()->avatar) }}"
              alt="Profile" class="rounded-circle" width="100px" height="100px">
            <div class="my-3 text-center">
              <h2>{{ auth()->user()->nama ?? auth()->user()->username }}</h2>
              <h3>{{ auth()->user()->role }}</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">

              <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview"
                  aria-selected="true" role="tab">Overview</button>
              </li>

              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit" aria-selected="false"
                  role="tab" tabindex="-1">Edit Profile</button>
              </li>

              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"
                  aria-selected="false" tabindex="-1" role="tab">Ganti Password</button>
              </li>

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade profile-overview active show" id="profile-overview" role="tabpanel">
                {{-- <form action="{{ route('verifyEmail') }}" method="post">
                  @csrf
                  <input type="hidden" name="email" value="{{ auth()->user()->email }}" id="">
                  <button type="submit">Kirim kode</button>
                </form> --}}
                <h5 class="card-title border-bottom border-1">Tentang Saya</h5>
                <p class="small fst-italic">{{ auth()->user()->bio ?? '-' }}</p>

                <h5 class="card-title border-bottom border-1">Detail Profil</h5>

                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 label ">Nama Lengkap</div>
                  <div class="col-lg-9 col-md-8">{{ auth()->user()->nama ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 label">Email</div>
                  <div class="col-lg-9 col-md-8">{{ auth()->user()->email ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 label">Total Login</div>
                  <div class="col-lg-9 col-md-8">{{ auth()->user()->total_login ?? '-' }} Kali </div>
                </div>

                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 label">Terakhir Login</div>
                  <div class="col-lg-9 col-md-8">
                    {{ auth()->user()->terakhir_login == 0 ? '-' : date('Y-m-d H:i', auth()->user()->terakhir_login) }}
                  </div>
                </div>

                <form id="deleteAccountForm" action="{{ route('deleteAccount', auth()->user()->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete()"><i
                      class="bi bi-dash-circle"></i> Hapus akun saya!</button>
                </form>

                @push('scripts')
                  <script>
                    function confirmDelete() {
                      Swal.fire({
                        title: 'Apakah Anda yakin ingin menghapus akun ini?',
                        text: 'Akun ini akan dihapus secara permanen dan tidak dapat dikembalikan, lalu konten yang terkait dengan akun ini akan dihapus secara permanen. Apakah Anda yakin ingin melanjutkan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                      }).then((result) => {
                        if (result.isConfirmed) {
                          Swal.fire({
                            title: 'Masukkan kata sandi Anda',
                            text: 'Ini adalah opsi terakhir, silahkan pikirkan kembali tentang akun Anda.',
                            input: 'password',
                            inputPlaceholder: 'Masukkan kata sandi Anda',
                            inputAttributes: {
                              autocapitalize: 'off',
                              autocorrect: 'off'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Konfirmasi',
                            cancelButtonText: 'Batal',
                            showLoaderOnConfirm: true,
                            preConfirm: (password) => {
                              return new Promise((resolve, reject) => {
                                // AJAX request to validate the password
                                $.ajax({
                                  url: '{{ route('validatePassword') }}',
                                  type: 'POST',
                                  data: {
                                    _token: '{{ csrf_token() }}',
                                    password: password
                                  },
                                  success: function(response) {
                                    if (response.valid) {
                                      resolve();
                                    } else {
                                      reject('Password salah!');
                                    }
                                  },
                                  error: function() {
                                    reject('Terjadi kesalahan pada server. Coba lagi nanti.');
                                  }
                                });
                              });
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                          }).then((result) => {
                            if (result.isConfirmed) {
                              // If password is valid, submit the form to delete the account
                              document.getElementById('deleteAccountForm').submit();
                            }
                          }).catch(error => {
                            // If the password validation fails, show an error alert
                            Swal.fire({
                              icon: 'error',
                              title: 'Gagal',
                              text: error
                            });
                          });
                        }
                      });
                    }
                  </script>
                @endpush

              </div>

              <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">

                <!-- Profile Edit Form -->
                <form method="POST" action="{{ route('profileUpdate', $user->id) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Gambar
                      Profil</label>
                    <div class="col-md-8 col-lg-9">
                      <img
                        src="{{ auth()->user()->avatar == 'user.png' ? asset('img/user.png') : asset('storage/' . auth()->user()->avatar) }}"
                        alt="Profile" id="profileImage" class="rounded" width="100px" height="100px">
                      <div class="pt-2">
                        <label for="fileInput" class="btn btn-primary btn-sm" title="Upload new profile image">
                          <i class="bi bi-upload"></i>
                        </label>
                        <input type="file" id="fileInput" name="profil"
                          class="@error('profil')
                                            is-invalid
                                        @enderror"
                          style="display: none;">
                        @if (auth()->user()->avatar != 'user.png')
                          <a href="{{ route('removeProfile', $user->id) }}" class="btn btn-danger btn-sm"><i
                              class="bi bi-trash"></i></a>
                        @endif
                        @error('profil')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <script>
                      document.getElementById('fileInput').addEventListener('change', function() {
                        const file = this.files[0];
                        if (file) {
                          const reader = new FileReader();
                          reader.onload = function(e) {
                            document.getElementById('profileImage').src = e.target.result;
                          }
                          reader.readAsDataURL(file);
                        }
                      });
                    </script>
                  </div>


                  <div class="row mb-3">
                    <label for="nama_lengkap" class="col-md-4 col-lg-3 col-form-label">Nama
                      lengkap</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="nama_lengkap" type="text"
                        class="form-control @error('nama_lengkap')
                                        is-invalid
                                    @enderror"
                        id="nama_lengkap" value="{{ old('nama_lengkap') ?? auth()->user()->nama }}">
                      @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="tentang" class="col-md-4 col-lg-3 col-form-label">Tentang</label>
                    <div class="col-md-8 col-lg-9">
                      <textarea name="tentang"
                        class="form-control @error('tentang')
                                        is-invalid
                                    @enderror"
                        id="tentang" style="height: 100px">{{ old('tentang') ?? auth()->user()->bio }}</textarea>
                      @error('tentang')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="email"
                        class="form-control @error('email')
                                        is-invalid
                                    @enderror"
                        id="email" value="{{ old('email') ?? auth()->user()->email }}">
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary" id="spin"><i class="bu bi-save"></i>
                      Simpan Perubahan</button>
                  </div>
                </form><!-- End Profile Edit Form -->
              </div>

              <div class="tab-pane fade pt-3" id="profile-change-password" role="tabpanel">
                <!-- Change Password Form -->
                <form method="POST" action="{{ route('changePassword', $user->id) }}">
                  @csrf
                  <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password
                      Sekarang</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="currentpassword" type="password"
                        class="form-control @error('currentpassword')
                                        is-invalid
                                    @enderror"
                        id="currentPassword">
                      @error('currentpassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="password" class="col-md-4 col-lg-3 col-form-label">Password
                      Baru</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="password" type="password"
                        class="form-control @error('password')
                                        is-invalid
                                    @enderror"
                        id="password">
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Ulangi
                      password baru</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="password_confirmation" type="password"
                        class="form-control @error('password_confirmation')
                                        is-invalid
                                    @enderror"
                        id="password_confirmation">
                      @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary" id="spin1"><i
                        class="bi bi-check-circle"></i> Ganti
                      Password</button>
                  </div>
                </form><!-- End Change Password Form -->

              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>
      </div>
    </div>
  </section>
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const spinButton = document.getElementById('spin1');
        if (!spinButton) {
          return;
        }
        const form = spinButton.closest('form');

        form.addEventListener('submit', function() {
          spinButton.disabled = true;
          spinButton.innerHTML =
            '<span class="spinner-border text-white spinner-border-sm" role="status"></span> Menyimpan...';
        });
      });
    </script>
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
            '<span class="spinner-border text-white spinner-border-sm" role="status"></span> Menyimpan...';
        });
      });
    </script>
  @endpush
@endsection
