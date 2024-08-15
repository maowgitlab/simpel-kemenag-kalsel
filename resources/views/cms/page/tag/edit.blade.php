@extends('cms.app')
@section('title', 'Edit Tag Lama')
@section('cms')
  <div class="pagetitle">
    <h1>Edit Tag Lama</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tag') }}">Data Tag</a></li>
        <li class="breadcrumb-item active">Edit Tag Lama</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form class="row g-3 mt-2" action="{{ route('updateTag', $tag->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="col-md-12">
                <label for="nama_tag" class="form-label fw-bold">Nama Tag</label>
                <input type="text"
                  class="form-control @error('nama_tag')
                                    is-invalid
                                @enderror"
                  id="nama_tag" name="nama_tag" value="{{ $tag->nama_tag }}">
                @error('nama_tag')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="text-start">
                <a href="{{ route('tag') }}" class="btn btn-sm btn-outline-secondary shadow-sm"><i
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
