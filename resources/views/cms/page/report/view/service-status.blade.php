@extends('cms.app')
@section('title', 'Laporan Status Pelayanan')
@section('cms')
  <div class="pagetitle">
    <h1>Laporan Status Pelayanan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Status Pelayanan</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            @if (!$services->isEmpty())
              <a href="{{ route('serviceStatusesOutput') }}" class="shadow-sm btn btn-sm btn-danger mt-3"><i
                  class="bi bi-filetype-pdf"></i> Cetak Laporan</a>
            @endif
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kode Layanan</th>
                    <th>Kategori Layanan</th>
                    <th>Layanan</th>
                    <th>Nama Pemohon</th>
                    <th>Email</th>
                    <th>Diproses Oleh</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($services as $service)
                    <tr>
                      <td class="align-middle">{{ $loop->iteration }}</td>
                      <td class="align-middle">{{ $service->kode_layanan }}</td>
                      <td class="align-middle">{{ $service->list->judul }}</td>
                      <td class="align-middle">{{ $service->service->judul }}</td>
                      <td class="align-middle">{{ $service->nama }}</td>
                      <td class="align-middle">{{ $service->email }}</td>
                      <td class="align-middle">{{ $service->diproses_oleh }}</td>
                      <td class="align-middle">{{ $service->status }}</td>
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
