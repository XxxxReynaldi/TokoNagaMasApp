{{-- <x-app-layout> --}}

@extends('layouts.admin')

@section('title', '- Halaman Pengguna')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Data Pengguna</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            
            @if(($errors->any()))
              @foreach ($errors->all() as $error)
              <div class="mt-3 mx-3 alert alert-danger alert-dismissible text-white fade show" role="alert">
                  <span class="alert-icon align-middle">
                    <span class="material-icons">
                      warning_amber
                    </span>                      
                  </span>
                  <span class="alert-text"><strong>Peringatan ! </strong> {{ $error }}</span>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              @endforeach
            @endif

            @if(session('success'))
              <div class="mt-3 mx-3 alert alert-success alert-dismissible text-white fade show" role="alert">
                  <span class="alert-icon align-middle">
                    <span class="material-icons">
                      done
                    </span>
                  </span>
                  <span class="alert-text"><strong>Berhasil ! </strong>{{ session('success') }}</span>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
            @endif

            <div class="table-responsive p-5">
              <table class="table align-items-center mb-0" id="users-table" >
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="30%">Alamat</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" width="20%">No Telp</th>
                    <th class="text-secondary opacity-7" width="10%"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                      <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="{{$user->profilePhotoPath}}" class="avatar avatar-sm me-3 border-radius-lg" alt="photo-{{$user->name}}">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                              </div>
                            </div>
                          </td>
                          <td class="text-xs text-secondary mb-0">{{ $user->address }}</td>
                          <td class="text-xs text-secondary mb-0">{{ $user->phone_number }}</td>
                          <td>  
                            <button data-bs-toggle="modal" data-bs-target="#resetPWUserModal-{{$user->id}}" class="btn bg-gradient-warning btn-block mb-3">Reset Password</button>
                            <button data-bs-toggle="modal" data-bs-target="#viewUserModal-{{$user->id}}" class="btn bg-gradient-info btn-block mb-3">Lihat</button>
                            <button data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{$user->id}}" class="btn bg-gradient-danger btn-block mb-3">Hapus</button>
                          </td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
  </div>


@push('modals')
<!-- Modal -->
@foreach ($users as $user)
<div class="modal fade" id="viewUserModal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="userModalLabel">Lihat Pengguna</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <form action="{{ route('user.users.update', $user) }}" method="POST" enctype="multipart/form-data"> --}}
      {{-- <form action="#" method="POST" enctype="multipart/form-data"> --}}
        {{-- @csrf
        @method('PATCH') --}}
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <div class="d-flex justify-content-center">
                  <div class="picture-div blur">
                    <img src="{{ $user->profilePhotoPath }}" aria-hidden alt="picture-{{ $user->name }}" class="picture-img" width="300" height="300">
                  </div>
              </div>
            </div>
            
            <div class="col-6">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <tbody>
                    <tr>
                      <td>Nama</td>
                      <td>:</td>
                      <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td>:</td>
                      <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                      <td>Alamat</td>
                      <td>:</td>
                      <td>{{ $user->address }}</td>
                    </tr>
                    <tr>
                      <td>No Telp</td>
                      <td>:</td>
                      <td>{{ $user->phone_number }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal">Tutup</button>
        </div>
      {{-- </form> --}}
    </div>
  </div>
</div>

<div class="modal fade" id="deleteUserModal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="userModalLabel">Hapus Pengguna</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('user.destroy', $user) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p>Apa anda yakin ingin menghapus pengguna <code>{{$user->name}}</code> ?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gray-100" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-gradient-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="resetPWUserModal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="userModalLabel">Reset Password Pengguna</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('user.password-reset', $user) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <p>Apa anda yakin ingin mereset password pengguna <code>{{ $user->name }}</code> ?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gray-100" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-gradient-warning">Reset</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endpush



@push('scripts')
<script>
    $(document).ready(function() {

      usersTable();
      function usersTable(){
        $('#users-table').DataTable();  
      }

    });
</script>
@endpush
 

@endsection
   

    
{{-- </x-app-layout> --}}

