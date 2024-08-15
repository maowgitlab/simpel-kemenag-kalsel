@extends('cms.app')
@section('title', 'Laporan Pelayanan Berdasarkan Kategori')
@section('cms')
  <div class="pagetitle">
    <h1>Laporan Pelayanan Berdasarkan Kategori</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Pelayanan Berdasarkan Kategori</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            @if (!$listServices->isEmpty())
              <a href="{{ route('serviceByCategoryOutput') }}" class="shadow-sm btn btn-sm btn-danger mt-3"><i
                  class="bi bi-filetype-pdf"></i> Cetak Laporan</a>
            @endif
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kategori Layanan</th>
                    <th>Total Layanan</th>
                    <th>Total Pemohon</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($listServices as $listService)
                    <tr>
                      <td class="align-middle">{{ $loop->iteration }}</td>
                      <td class="align-middle">{{ $listService->judul }}</td>
                      <td class="align-middle">{{ $listService->services->count() }} Layanan Aktif</td>
                      <td class="align-middle">{{ $listService->applicants->count() }} Permohonan</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
