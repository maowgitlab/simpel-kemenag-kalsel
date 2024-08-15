@extends('cms.app')
@section('title', 'Komentar Spam')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  </script>
  <div class="pagetitle">
    <h1>Komentar Spam</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Komentar Spam</li>
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
                    <th>Media</th>
                    <th>Perangkat</th>
                    <th>Komentar</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($comments as $comment)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $comment->media->judul }}</td>
                      <td>{{ $comment->perangkat }}</td>
                      <td><i>{{ $comment->komentar }}</i></td>
                      <td class="align-middle text-center">
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                          <button type="button" class="btn btn-sm btn-primary"
                            onclick="refuseComment({{ $comment->id }})"><i class="bi bi-check-circle"></i></button>
                          <form action="{{ route('deleteComment', $comment->id) }}" method="post"
                            id="delete-comment-form-{{ $comment->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger"
                              onclick="confirmDelete({{ $comment->id }})"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(commentId) {
                  Swal.fire({
                    title: 'Hapus Komentar Spam ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-comment-form-' + commentId).submit();
                    }
                  })
                }

                function refuseComment(commentId) {
                  Swal.fire({
                    title: 'Abaikan komentar ini?',
                    text: "Komentar yang diabaikan akan dianggap bukan spam.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Abaikan',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = "{{ route('refuseComment', ':commentId') }}".replace(':commentId', commentId);
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
