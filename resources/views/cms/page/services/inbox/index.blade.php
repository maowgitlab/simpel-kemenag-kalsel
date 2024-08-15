@extends('cms.app')
@section('title', 'Kota Masuk Permohonan')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Kota Masuk Permohonan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kota Masuk Permohonan</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
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
                    <th>Status</th>
                    <th>Rating Layanan</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($inboxes as $inbox)
                    <tr>
                      <td class="align-middle">{{ $loop->iteration }}</td>
                      <td class="align-middle"><a href="#" class="hover-title" id="detail_permohonan"
                          data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                          data-kode="{{ $inbox->kode_layanan }}">{{ $inbox->kode_layanan }}</a></td>
                      <td class="align-middle">{{ $inbox->list->judul }}</td>
                      <td class="align-middle">{{ $inbox->service->judul }}</td>
                      <td class="align-middle">{{ $inbox->nama }}</td>
                      <td class="align-middle">{{ $inbox->email }}</td>
                      <td class="align-middle">{{ $inbox->status }}</td>
                      <td class="align-middle">
                        @if ($inbox->rating == 0)
                          -
                        @else
                          @for ($i = 0; $i < $inbox->rating; $i++)
                            <i class="bi bi-star-fill text-warning"></i>
                          @endfor
                        @endif
                      </td>
                      <td class="align-middle text-center">
                        <div class="btn-group" role="group">
                          <form action="{{ route('deleteInbox', $inbox->id) }}" method="post"
                            id="delete-inbox-form-{{ $inbox->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger"
                              onclick="confirmDelete({{ $inbox->id }})"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(inboxId) {
                  Swal.fire({
                    title: 'Hapus Kotak Masuk ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-inbox-form-' + inboxId).submit();
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
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Detail Layanan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateForm">
            <table class="table">
              <tr>
                <th>Kode Layanan</th>
                <td id="modalKodeLayanan"></td>
              </tr>
              <tr>
                <th>Kategori Layanan</th>
                <td id="modalListId"></td>
              </tr>
              <tr>
                <th>Layanan</th>
                <td id="modalServiceId"></td>
              </tr>
              <tr>
                <th>Nama</th>
                <td id="modalNama"></td>
              </tr>
              <tr>
                <th>Email</th>
                <td id="modalEmail"></td>
              </tr>
              <tr>
                <th>Pesan Pemohon</th>
                <td id="modalPesan"></td>
              </tr>
              <tr>
                <th>Pesan Balasan</th>
                <td><input type="text" id="modalBalasan" class="form-control"></td>
              </tr>
              <tr>
                <th>Diproses Oleh</th>
                <td><input type="text" id="modalDiprosesOleh" class="form-control"></td>
              </tr>
              <tr>
                <th>File Persyaratan</th>
                <td id="modalFilePersyaratan"></td>
              </tr>
              <tr>
                <th>Status</th>
                <td>
                  <select id="modalStatus" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                  </select>
                </td>
              </tr>
            </table>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" id="closeButton" data-bs-dismiss="modal"><i
              class="bi bi-x"></i> Tutup</button>
          <button type="button" class="btn btn-sm btn-primary" id="saveChanges">
            <span id="buttonText"><i class="bi bi-save"></i> Simpan Perubahan</span>
            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"
              aria-hidden="true"></span>
          </button>
        </div>
      </div>
    </div>
  </div>

@endsection
