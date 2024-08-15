@extends('cms.app')
@section('title', 'Edit Pelayanan Lama')
@section('cms')
  <div class="pagetitle">
    <h1>Edit Pelayanan Lama</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('service') }}">Pelayanan</a></li>
        <li class="breadcrumb-item active">Edit Pelayanan Lama</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form class="row g-3 mt-2" action="{{ route('updateService', $service->id) }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="list_id" class="form-label fw-bold">Kategori Pelayanan</label>
                <select name="list_id" id="categoryID"
                  class="form-select @error('list_id')
                  is-invalid
                @enderror">
                  <option disabled>Pilih Kategori Pelayanan</option>
                  @foreach ($listServices as $listService)
                    <option value="{{ $listService->id }}" {{ $listService->id == $service->list_id ? 'selected' : '' }}>
                      {{ $listService->judul }}</option>
                  @endforeach
                </select>
                @error('list_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="judul" class="form-label fw-bold">Judul Pelayanan</label>
                <input type="text"
                  class="form-control @error('judul')
                                    is-invalid
                                @enderror"
                  id="judul" name="judul" value="{{ $service->judul }}">
                @error('judul')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="file_sop" class="form-label fw-bold">File SOP <sup class="text-danger"
                    style="font-size: 10px">PDF | Maks. 1 MB</sup></label>
                <input type="file"
                  class="form-control @error('file_sop')
                                      is-invalid
                                  @enderror"
                  id="file_sop" name="file_sop">
                @error('file_sop')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="file_permohonan" class="form-label fw-bold">File Permohonan <sup class="text-danger"
                    style="font-size: 10px">PDF | Maks. 1 MB</sup></label>
                <input type="file"
                  class="form-control @error('file_permohonan')
                                      is-invalid
                                  @enderror"
                  id="file_permohonan" name="file_permohonan">
                @error('file_permohonan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="text-start">
                <a href="{{ route('service') }}" class="btn btn-sm btn-outline-secondary shadow-sm"><i
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
