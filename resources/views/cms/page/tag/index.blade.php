@extends('cms.app')
@section('title', 'Data Tag')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Data Tag</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Tag</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <a href="{{ route('createTag') }}" class="shadow-sm btn btn-sm btn-primary"><i
                  class="bi bi-plus-circle"></i>
                Input Tag Baru</a>
            </h5>
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Nama Tag</th>
                    <th class="text-center">Slug</th>
                    <th class="text-center">Jumlah Penggunaan</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($tags as $tag)
                    <tr>
                      <td class="align-middle text-center">{{ $loop->iteration }}</td>
                      <td class="align-middle text-center">{{ $tag->nama_tag }}</td>
                      <td class="align-middle text-center">{{ $tag->slug }}</td>
                      <td class="align-middle text-center"><small class="fw-bold text-muted"><i class="bi bi-eye"></i>
                          {{ $tag->jumlah_penggunaan }} Kali</small></td>
                      <td class="align-middle text-center">
                        @if (auth()->user()->role == 'admin')
                          <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <a href="{{ route('editTag', $tag->id) }}" class="btn btn-sm btn-primary"><i
                                class="bi bi-pencil"></i></a>
                            <form action="{{ route('deleteTag', $tag->id) }}" method="post"
                              id="delete-tag-form-{{ $tag->id }}">
                              @csrf
                              @method('DELETE')
                              <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $tag->id }})"><i class="bi bi-trash"></i></button>
                            </form>
                          </div>
                        @else
                          @if ($tag->jumlah_dibaca >= 2)
                            <i class="bi bi-graph-up text-success"></i>
                          @else
                            <i class="bi bi-graph-down text-danger"></i>
                          @endif
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(tagId) {
                  Swal.fire({
                    title: 'Hapus Tag ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-tag-form-' + tagId).submit();
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
