@extends('cms.app')
@section('title', 'Laporan berita Popular')
@section('cms')
  <div class="pagetitle">
    <h1>Laporan berita Popular</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan berita Popular</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            @if (!$medias->isEmpty())
              <a href="{{ route('popularPostOutput') }}" class="shadow-sm btn btn-sm btn-danger mt-3"><i
                  class="bi bi-filetype-pdf"></i> Cetak Laporan</a>
            @endif
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
                  @forelse ($medias as $media)
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
                  @empty
                    <div class="alert alert-secondary">
                      <i class="bi bi-info-circle"></i> Media popular belum tersedia.
                    </div>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
