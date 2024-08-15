@extends('cms.app')
@section('title', 'Edit Media Lama')
@section('cms')
  <div class="pagetitle">
    <h1>Edit Media Lama</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('media') }}">Data Media</a></li>
        <li class="breadcrumb-item active">Edit Media Lama</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <form action="{{ route('reuploadMedia', $media->id) }}" enctype="multipart/form-data" method="POST">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-pencil-square"></i> Input Data Media</h5>
              <div class="mb-3">
                <label for="judul" class="form-label fw-bold">Judul</label>
                <input type="text"
                  class="form-control @error('judul')
                                    is-invalid
                                @enderror"
                  id="judul" name="judul" value="{{ $media->judul }}">
                @error('judul')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="konten" class="form-label fw-bold">Konten</label>
                <textarea name="konten" id="konten"
                  class="form-control @error('konten')
                                    is-invalid
                                @enderror">{{ $media->konten }}</textarea>
                @error('konten')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-pencil-square"></i> Input Data Media</h5>
              <div class="mb-3">
                <label for="file" class="form-label">Gambar</label>
                <img src="{{ $media->gambar == null ? asset('img/thumbnail.png') : asset('storage/' . $media->gambar) }}"
                  alt="" id="showImage" class="img-fluid rounded mb-3">
                <input
                  class="form-control @error('gambar')
                                      is-invalid
                                  @enderror"
                  type="file" id="gambar" name="gambar">
                @error('gambar')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <script>
                document.getElementById('gambar').addEventListener('change', function() {
                  const file = this.files[0];
                  if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                      document.getElementById('showImage').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                  }
                });
              </script>
              <div class="mb-3">
                <label for="kategori" class="form-label fw-bold">Kategori</label>
                <select name="kategori"
                  class="form-control @error('kategori')
                                    is-invalid
                                @enderror">
                  <option disabled>Pilih Kategori</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $media->category->id == $category->id ? 'selected' : '' }}>
                      {{ $category->nama_kategori }}</option>
                  @endforeach
                </select>
                @error('kategori')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="Tag" class="form-label fw-bold">Tag</label>
                <select
                  class="form-select @error('tags')
                                    is-invalid
                                @enderror"
                  name="tags[]" id="tags" multiple="multiple" aria-label="multiple select example">
                  @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}"
                      {{ in_array($tag->id, $media->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                      #{{ $tag->nama_tag }}</option>
                  @endforeach
                </select>
                @error('tags')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Jadikan Media Pilihan</label>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="pilihan" id="pilihan"
                    {{ $media->pilihan == 1 ? 'checked' : '' }}>
                  <label class="form-check-label" for="pilihan">
                    Ya
                  </label>
                  @error('pilihan')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <a href="{{ route('media') }}" class="btn btn-sm btn-secondary shadow"><i class="bi bi-arrow-left"></i> Kembali</a>
      <button type="submit" class="btn btn-sm btn-primary shadow" id="spin"><i class="bi bi-upload"></i>
        Upload</button>
    </form>
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
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
        });
      });
    </script>
  @endpush
@endsection
