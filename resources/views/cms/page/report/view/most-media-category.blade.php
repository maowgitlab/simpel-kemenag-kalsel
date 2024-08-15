@extends('cms.app')
@section('title', 'Laporan Kategori Media Terbanyak')
@section('cms')
  <div class="pagetitle">
    <h1>Laporan Kategori Media Terbanyak</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Kategori Media Terbanyak</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            @if (!$categories->isEmpty())
              <a href="{{ route('mostMediaCategoryOutput') }}" class="shadow-sm btn btn-sm btn-danger mt-3"><i
                  class="bi bi-filetype-pdf"></i> Cetak Laporan</a>
            @endif
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Kategori Media</th>
                    <th class="text-center">Jumlah Dibaca</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($categories as $category)
                    <tr>
                      <td class="align-middle text-center">{{ $loop->iteration }}</td>
                      <td class="align-middle text-center">{{ $category->nama_kategori }}</td>
                      <td class="align-middle text-center"><i class="bi bi-eye"></i>
                        {{ $category->jumlah_dibaca . ' kali' }}</td>
                      <td class="align-middle text-center">{{ $category->medias->count() . ' Media' }}</td>
                    </tr>
                  @empty
                    <div class="alert alert-secondary">
                      <i class="bi bi-info-circle"></i> Kategori Media Terbanyak Belum Tersedia.
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
