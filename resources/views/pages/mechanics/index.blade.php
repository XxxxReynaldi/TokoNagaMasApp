{{-- <x-app-layout> --}}

@extends('layouts.admin')

@section('title', '- Halaman Mekanik')

@section('content')

{{-- <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link">Sign Out</button>
        </form>
    </div>
</header> --}}

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Data Mekanik</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="card-header p-0 mx-3 mb-n4 z-index-2">
                <button type="button" data-bs-toggle="modal" data-bs-target="#createMechanicModal" class="btn bg-gradient-info btn-block mb-3">Tambah</button>           
            </div>
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
                <span
                    class="alert-text"><strong>Berhasil ! </strong>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <div class="table-responsive p-5">
              <table class="table align-items-center mb-0" id="mechanics-table" >
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-secondary opacity-7"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($mechanics as $mechanic)
                      <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="{{$mechanic->mechanicPhotoPath}}" class="avatar avatar-sm me-3 border-radius-lg" alt="photo-{{$mechanic->name}}">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $mechanic->name }}</h6>
                              </div>
                            </div>
                          </td>
                          <td class="text-xs text-secondary mb-0">{{ $mechanic->category }}</td>
                          <td class="text-xs text-secondary mb-0 text-end">{{ $mechanic->price }}</td>
                          <td class="text-xs text-secondary mb-0 text-center">{{ $mechanic->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                          <td width="10%">  
                            <button data-bs-toggle="modal" data-bs-target="#editMechanicModal-{{$mechanic->id}}" class="btn bg-gradient-success btn-block mb-3">Edit</button>
                            <button data-bs-toggle="modal" data-bs-target="#deleteMechanicModal-{{$mechanic->id}}" class="btn bg-gradient-danger btn-block mb-3">Hapus</button>
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
<div class="modal fade" id="createMechanicModal" tabindex="-1" role="dialog" aria-labelledby="mechanicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="mechanicModalLabel">Tambah Mekanik</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('mechanics.store') }}" method="POST" id="mechanic-form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="input-group input-group-outline my-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name" required>
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="input-group input-group-outline my-3">
            <label class="form-label">Kategori</label>
            <input type="text" class="form-control" name="category" required>
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="input-group input-group-outline my-3">
            <label class="form-label">Harga</label>
            <input type="number" class="form-control" name="price" required>
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="my-3">
            <label class="form-label">Deskripsi</label>
            <div class="input-group input-group-outline">
              <textarea type="text" class="form-control" name="description" rows="3"></textarea>
            </div>
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="input-group input-group-outline my-3" >
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" value="1" name="status" id="flexSwitchCheckDefault" checked="">
              <label class="custom-control-label" for="flexSwitchCheckDefault">Status Bisa Disewa</label>
            </div>
          </div>
          <div class="form-file-upload form-file-simple my-3 ">
            <label class="form-label">Upload Gambar</label>
            <input type="file" class="form-control" name="mechanicPhotoPath">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gray-100" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-gradient-info">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

@push('modals')
<!-- Modal -->
@foreach ($mechanics as $mechanic)
<div class="modal fade" id="editMechanicModal-{{$mechanic->id}}" tabindex="-1" role="dialog" aria-labelledby="mechanicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="mechanicModalLabel">Edit Mekanik</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('mechanics.update', $mechanic) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <div class="d-flex justify-content-center">
                  <div class="picture-div blur">
                    <img src="{{ $mechanic->mechanicPhotoPath }}" aria-hidden alt="picture-{{ $mechanic->name }}" class="picture-img" width="300" height="300">
                  </div>
              </div>
            </div>
            <div class="col-6">
              <div class="input-group input-group-outline my-3 is-filled">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="name" value="{{ $mechanic->name }}">
                <span class="invalid-feedback" role="alert"></span>
              </div>
              <div class="input-group input-group-outline my-3 is-filled">
                <label class="form-label">Kategori</label>
                <input type="text" class="form-control" name="category" value="{{ $mechanic->category }}">
                <span class="invalid-feedback" role="alert"></span>
              </div>
              <div class="input-group input-group-outline my-3 is-filled">
                <label class="form-label">Harga</label>
                <input type="number" class="form-control" name="price" value="{{ $mechanic->price }}">
                <span class="invalid-feedback" role="alert"></span>
              </div>
              <div class="my-3">
                <label class="form-label">Deskripsi</label>
                <div class="input-group input-group-outline">
                  <textarea type="text" class="form-control" name="description" rows="3">{{ $mechanic->description }}</textarea>
                </div>
                <span class="invalid-feedback" role="alert"></span>
              </div>
              <div class="input-group input-group-outline my-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" value="1" name="status" id="flexSwitchCheckDefault" {{ $mechanic->status==1?"checked":"" }} >
                  <label class="custom-control-label" for="flexSwitchCheckDefault">Status Bisa Disewa</label>
                </div>
              </div>
              <div class="form-file-upload form-file-simple my-3 ">
                <label class="form-label">Upload Gambar baru</label>
                <input type="file" class="form-control" name="mechanicPhotoPath">
              </div>
            </div>
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gray-100" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-gradient-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteMechanicModal-{{$mechanic->id}}" tabindex="-1" role="dialog" aria-labelledby="mechanicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="mechanicModalLabel">Hapus Mekanik</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('mechanics.destroy', $mechanic) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p>Apa anda yakin ingin menghapus mekanik <code>{{$mechanic->name}}</code> ?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gray-100" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-gradient-danger">Hapus</button>
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

      mechanicsTable();
      function mechanicsTable(){
        $('#mechanics-table').DataTable();  
      }

    });
</script>
@endpush
 

@endsection
   

    
{{-- </x-app-layout> --}}

