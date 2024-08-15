@extends('cms.app')
@section('title', 'Data User')
@section('cms')
  @if (session()->has('message'))
    {!! session('message') !!}
  @endif
  <div class="pagetitle">
    <h1>Data User</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data User</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <a href="{{ route('createUser') }}" class="shadow-sm btn btn-sm btn-primary"><i
                  class="bi bi-plus-circle"></i>
                Input User Baru</a>
            </h5>
            <div class="table-responsive-lg my-3">
              <table class="table table-hover" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Profil</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Jumlah Media</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td class="align-middle text-center">{{ $loop->iteration }}</td>
                      <td class="align-middle text-center"><img
                          src="{{ $user->avatar == 'user.png' ? asset('img/user.png') : asset('storage/' . $user->avatar) }}"
                          alt="" width="32" height="32" class="rounded-circle"></td>
                      <td class="align-middle text-center">{{ $user->username }}</td>
                      <td class="align-middle text-center"><span class="badge rounded-pill bg-primary"><i
                            class="bi bi-{{ $user->role == 'admin' ? 'shield-shaded' : 'pencil-square' }}"></i>
                          {{ $user->role }}</span></td>
                      @if ($user->status == 'aktif')
                        <td class="align-middle text-center"><small><i class="bi bi-check-circle text-success"></i>
                            {{ $user->status }}</small></td>
                      @else
                        <td class="align-middle text-center"><small><i class="bi bi-x-circle text-danger"></i>
                            {{ $user->status }}</small></td>
                      @endif
                      <td class="align-middle text-center">{{ $user->email }}</td>
                      <td class="align-middle text-center">{{ $user->medias->count() . ' Media' }}</td>
                      <td class="align-middle text-center">
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                          <a href="{{ route('editUser', $user->id) }}" class="btn btn-sm btn-primary"><i
                              class="bi bi-pencil"></i></a>
                          @if ($user->medias->count() == 0)
                            @if ($user->role != 'admin')
                              <form action="{{ route('deleteUser', $user->id) }}" method="post"
                                id="delete-user-form-{{ $user->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger"
                                  onclick="confirmDelete({{ $user->id }})"><i class="bi bi-trash"></i></button>
                              </form>
                            @endif
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <script>
                function confirmDelete(userId) {
                  Swal.fire({
                    title: 'Hapus User ini?',
                    text: "Data tidak bisa dipulihkan kembali jika dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      document.getElementById('delete-user-form-' + userId).submit();
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
