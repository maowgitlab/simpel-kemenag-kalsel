@extends('cms.app')
@section('title', 'Edit User Lama')
@section('cms')
  <div class="pagetitle">
    <h1>Edit User Lama</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user') }}">Data User</a></li>
        <li class="breadcrumb-item active">Edit User Lama</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form class="row g-3 mt-2" action="{{ route('updateUser', $user->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="col-md-12">
                <label for="username" class="form-label fw-bold">Username</label>
                <input type="text"
                  class="form-control @error('username')
                                    is-invalid
                                @enderror"
                  id="username" name="username" value="{{ $user->username }}">
                @error('username')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="role" class="form-label fw-bold">Role</label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                  <option disabled>Pilih Role</option>
                  <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                  <option value="penulis" {{ $user->role == 'penulis' ? 'selected' : '' }}>Penulis</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="status" class="form-label fw-bold">Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                  <option disabled>Pilih status</option>
                  <option value="aktif" {{ $user->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                  <option value="non-aktif" {{ $user->status == 'non-aktif' ? 'selected' : '' }}>Non Aktif</option>
                </select>
              </div>
              <div class="col-md-12">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="text"
                  class="form-control @error('email')
                                  is-invalid
                              @enderror"
                  id="email" name="email" value="{{ $user->email }}">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="text-start">
                <a href="{{ route('user') }}" class="btn btn-sm btn-outline-secondary shadow-sm"><i
                    class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-sm btn-outline-primary shadow-sm" id="spin"><i
                    class="bi bi-arrow-repeat"></i>
                  Perbarui</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  @push('scripts')
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
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memperbarui...';
        });
      });
    </script>
  @endpush
@endsection
