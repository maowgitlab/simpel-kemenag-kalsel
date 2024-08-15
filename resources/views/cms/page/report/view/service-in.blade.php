@extends('cms.app')
@section('title', 'Laporan Pelayanan Masuk')
@section('cms')
  <div class="pagetitle">
    <h1>Laporan Pelayanan Masuk</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Pelayanan Masuk</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            @if (!$inboxes->isEmpty())
              <a href="{{ route('serviceInOutput') }}" class="shadow-sm btn btn-sm btn-danger mt-3"><i
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
                  </tr>
                </thead>
                <tbody>
                  @foreach ($inboxes as $inbox)
                    <tr>
                      <td class="align-middle">{{ $loop->iteration }}</td>
                      <td class="align-middle">{{ $inbox->kode_layanan }}</td>
                      <td class="align-middle">{{ $inbox->list->judul }}</td>
                      <td class="align-middle">{{ $inbox->service->judul }}</td>
                      <td class="align-middle">{{ $inbox->nama }}</td>
                      <td class="align-middle">{{ $inbox->email }}</td>
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
