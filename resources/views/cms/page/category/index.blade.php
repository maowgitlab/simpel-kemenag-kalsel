@extends('cms.app')
@section('title', 'Data Kategori')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Data Kategori</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Kategori</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><a href="{{ route('createCategory') }}" class="shadow-sm btn btn-sm btn-primary"><i
                  class="bi bi-plus-circle"></i> Input Kategori Baru</a></h5>
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Nama Kategori</th>
                    <th class="text-center">Slug</th>
                    <th class="text-center">Jumlah Baca</th>
                    <th class="text-center">Total Media</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($categories as $category)
                    <tr>
                      <td class="align-middle text-center">{{ $loop->iteration }}</td>
                      <td class="align-middle text-center">{{ $category->nama_kategori }}</td>
                      <td class="align-middle text-center">{{ $category->slug }}</td>
                      <td class="align-middle text-center"><small class="fw-bold text-muted"><i class="bi bi-eye"></i>
                          {{ $category->jumlah_dibaca }} Kali</small></td>
                      <td class="align-middle text-center">{{ $category->medias->count() . ' Media' }}</td>
                      <td class="align-middle text-center">
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                          <a href="{{ route('editCategory', $category->id) }}" class="btn btn-sm btn-primary"><i
                              class="bi bi-pencil"></i></a>
                          @if ($category->medias->count() == 0)
                            <form action="{{ route('deleteCategory', $category->id) }}" method="post"
                              id="delete-category-form-{{ $category->id }}">
                              @csrf
                              @method('DELETE')
                              <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $category->id }})"><i class="bi bi-trash"></i></button>
                            </form>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(categoryId) {
                  Swal.fire({
                    title: 'Hapus Kategori ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-category-form-' + categoryId).submit();
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
