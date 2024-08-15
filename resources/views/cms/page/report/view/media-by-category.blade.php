@extends('cms.app')
@section('title', 'Laporan Berita Berdasarkan Kategori')
@section('cms')
  <div class="pagetitle">
    <h1>Laporan Berita Berdasarkan Kategori</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Berita Berdasarkan Kategori</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('mediaByCategory') }}" method="get" class="mt-3">
              @csrf
              <div class="row mb-3 g-1">
                <div class="col-12 col-md-4">
                  <select name="categoryID" id="categoryID" class="form-select form-select-sm">
                    <option selected disabled>-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}"
                        {{ old('categoryID', $inputCategory) == $category->id ? 'selected' : '' }}>
                        {{ $category->nama_kategori }}
                        ({{ $category->medias->count() }})
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-lg-4 ms-0 text-center text-lg-start">
                  <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-filter"></i>
                    Filter</button>
                  @if (request()->has('categoryID'))
                    @if (!$medias->isEmpty())
                      <a href="{{ route('mediaByCategoryOutput', ['categoryID' => $inputCategory]) }}"
                        class="btn btn-sm btn-danger"><i class="bi bi-filetype-pdf"></i>
                        Cetak Laporan</a>
                    @endif
                  @endif
                  <a href="{{ route('mediaByCategory') }}" class="btn btn-sm btn-secondary"><i class="bi bi-repeat"></i>
                    Reset</a>
                </div>
              </div>
            </form>
            @if (request()->has('categoryID'))
              <div class="table-responsive-lg my-3">
                <table class="table table-hover" id="datatables">
                  <thead>
                    <tr>
                      <th class="text-center">No.</th>
                      <th class="text-center">Gambar</th>
                      <th class="text-center">Judul</th>
                      <th class="text-center">Kategori</th>
                      <th class="text-center">Penting</th>
                      <th class="text-center">Dibaca</th>
                      <th class="text-center">Diupload Oleh</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($medias as $media)
                      <tr>
                        <td class="align-middle text-center">{{ $loop->iteration }}</td>
                        <td class="align-middle text-center"><img src="{{ asset('storage/' . $media->gambar) }}"
                            alt="" width="80px" height="60px" class="rounded">
                          @foreach ($media->tags as $tag)
                            <div class="text-primary fw-bold" style="font-size: 12px">
                              #{{ $tag->nama_tag }}</div>
                          @endforeach
                        </td>
                        <td class="align-middle">{{ $media->judul }}</td>
                        <td class="align-middle text-center"><small
                            class="fw-bold text-primary">{{ $media->category->nama_kategori }}</small>
                        </td>
                        <td class="align-middle text-center"><small
                            class="fw-bold">{{ $media->penting == 1 ? 'Ya' : 'Tidak' }}</small></td>
                        <td class="align-middle text-center"><small class="fw-bold text-muted"><i class="bi bi-eye"></i>
                            {{ $media->jumlah_dibaca }}</small></td>
                        <td class="align-middle text-center">
                          <small class="fw-bold">{{ $media->user->username }}</small>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="alert alert-secondary">
                <i class="bi bi-info-circle"></i> Silahkan pilih kategori terlebih dahulu.
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
