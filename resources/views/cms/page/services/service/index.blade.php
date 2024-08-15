@extends('cms.app')
@section('title', 'Data Pelayanan')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Data Pelayanan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Pelayanan</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <a href="{{ route('createService') }}" class="shadow-sm btn btn-sm btn-primary"><i
                  class="bi bi-plus-circle"></i> Input Pelayanan Baru</a>
            </h5>
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kategori Terkait</th>
                    <th>Judul Pelayanan</th>
                    <th>File SOP</th>
                    <th>File Permohonan</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($services as $service)
                    <tr>
                      <td class="align-middle">{{ $loop->iteration }}</td>
                      <td class="align-middle">{{ $service->list->judul }}</td>
                      <td class="align-middle">{{ $service->judul }}</td>
                      <td class="align-middle">
                        @if ($service->file_sop != null)
                          <a href="{{ asset('storage/' . $service->file_sop) }}" target="_blank"
                            class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        @else
                          -
                        @endif
                      </td>
                      <td class="align-middle">
                        @if ($service->file_permohonan != null)
                          <a href="{{ asset('storage/' . $service->file_permohonan) }}" target="_blank"
                            class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        @else
                          -
                        @endif
                      </td>
                      <td class="align-middle text-center">
                        <div class="btn-group" role="group">
                          <a href="{{ route('editService', $service->id) }}" class="btn btn-sm btn-primary"><i
                              class="bi bi-pencil"></i></a>
                          <form action="{{ route('deleteService', $service->id) }}" method="post"
                            id="delete-service-form-{{ $service->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger"
                              onclick="confirmDelete({{ $service->id }})"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(serviceId) {
                  Swal.fire({
                    title: 'Hapus Data Pelayanan ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-service-form-' + serviceId).submit();
                    }
                  })
                }
              </script>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
