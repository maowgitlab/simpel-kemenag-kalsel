@extends('cms.app')
@section('title', 'Data Kategori Pelayanan')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Data Kategori Pelayanan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Kategori Pelayanan</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <a href="{{ route('createListService') }}" class="shadow-sm btn btn-sm btn-primary"><i
                  class="bi bi-plus-circle"></i> Input Kategori Pelayanan Baru</a>
            </h5>
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Judul Kategori Pelayanan</th>
                    <th>Jumlah Data Pelayanan</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($listServices as $listService)
                    <tr>
                      <td class="align-middle">{{ $loop->iteration }}</td>
                      <td class="align-middle">{{ $listService->judul }}</td>
                      <td class="align-middle">{{ $listService->services->count() }} Data</td>
                      <td class="align-middle text-center">
                        <div class="btn-group" role="group">
                          <a href="{{ route('editListService', $listService->id) }}" class="btn btn-sm btn-primary"><i
                              class="bi bi-pencil"></i></a>
                          @if ($listService->services->count() == 0)
                            <form action="{{ route('deleteListService', $listService->id) }}" method="post"
                              id="delete-listService-form-{{ $listService->id }}">
                              @csrf
                              @method('DELETE')
                              <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $listService->id }})"><i class="bi bi-trash"></i></button>
                            </form>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(listServiceId) {
                  Swal.fire({
                    title: 'Hapus Kategori Pelayanan ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-listService-form-' + listServiceId).submit();
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
