@extends('cms.app')
@section('title', 'Data Feedback')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Data Feedback</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Feedback</li>
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
                    <th class="text-center">No.</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Tipe Feedback</th>
                    <th class="text-center">URL</th>
                    <th class="text-center">Pesan</th>
                    <th class="text-center">Tanggal dibuat</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($feedbacks as $feedback)
                    <tr>
                      <td class="align-middle text-center">{{ $loop->iteration }}</td>
                      <td class="align-middle text-center">{{ $feedback->email }}</td>
                      <td class="align-middle text-center">{{ $feedback->tipe_feedback }}</td>
                      <td class="align-middle text-center">{{ $feedback->url }}</td>
                      <td class="align-middle text-center">{{ $feedback->pesan }}</td>
                      <td class="align-middle text-center">{{ $feedback->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</td>
                      <td class="align-middle text-center">
                        <form action="{{ route('feedback.delete', $feedback->id) }}" method="post"
                          id="delete-feedback-form-{{ $feedback->id }}">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-sm btn-danger"
                            onclick="confirmDelete({{ $feedback->id }})"><i class="bi bi-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(feedbackId) {
                  Swal.fire({
                    title: 'Hapus Feedback ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-feedback-form-' + feedbackId).submit();
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
