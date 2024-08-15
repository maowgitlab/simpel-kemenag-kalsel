@extends('cms.app')
@section('title', 'Data Media')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Data Media</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Media</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
              {{ $error }}
            @endforeach
          </div>
        @endif
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <a href="{{ route('createMedia') }}" class="shadow-sm btn btn-sm btn-primary"><i
                  class="bi bi-plus-circle"></i>
                Input Media Baru</a>
            </h5>
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th class="text-start">#</th>
                    <th class="text-center">Gambar</th>
                    <th width="350px">Judul</th>
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Pilihan</th>
                    <th class="text-center">View</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($medias as $media)
                    <tr>
                      <td class="align-middle text-center">{{ $loop->iteration }}</td>
                      <td class="text-center align-middle"><img src="{{ asset('storage/' . $media->gambar) }}"
                          alt="" class="img-fluid rounded" width="80px" height="60px"></td>
                      <td class="align-middle">
                        {{ $media->judul }}
                        <p class="text-muted" style="font-style: italic; font-size: 12px;">{!! Str::limit(strip_tags(str_replace('PROJECT-KU.MY.ID -', '"', $media->konten)), 150, '...') !!}</p>
                        <small class="text-muted" style="font-size: 12px"><i class="bi bi-tags"></i> Tags:</small>
                        @forelse ($media->tags as $tag)
                          <small class="text-primary fw-bold"
                            style="font-size: 12px">{{ '#' . $tag->nama_tag . ',' }}</small>
                        @empty
                          <small class="text-primary fw-bold" style="font-size: 12px">-</small>
                        @endforelse
                      </td>
                      <td class="text-center align-middle"><span
                          class="badge bg-primary rounded-pill">{{ $media->category->nama_kategori }}</span></td>
                      <td class="align-middle text-center"><span
                          class="badge bg-{{ $media->pilihan == 1 ? 'success' : 'danger' }} rounded-pill">{{ $media->pilihan == 1 ? 'Ya' : 'Tidak' }}</span>
                      </td>
                      <td class="align-middle text-center"><small class="fw-bold text-muted"><i class="bi bi-eye"></i>
                          {{ $media->jumlah_dibaca }}</small></td>
                      <td class="align-middle text-center">
                        <div class="btn-group">
                          <a href="{{ route('editMedia', $media->slug) }}" class="btn btn-sm btn-primary"><i
                              class="bi bi-pencil-square"></i></a>
                          <form action="{{ route('deleteMedia', $media->id) }}" method="post"
                            id="delete-media-form-{{ $media->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger"
                              onclick="confirmDelete({{ $media->id }})"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(mediaId) {
                  Swal.fire({
                    title: 'Hapus media ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-media-form-' + mediaId).submit();
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
