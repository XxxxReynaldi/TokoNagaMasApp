{{-- <x-app-layout> --}}

@extends('layouts.admin')

@section('title', '- Halaman Profil')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-4">
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

        <div class="card my-4 d-flex align-items-center">
          <div class="card-header p-0 position-relative mt-4 mx-3 z-index-2">
            <div class="d-flex justify-content-center">
              <div class="picture-div blur">
                <img src="{{ $user->profilePhotoPath }}" aria-hidden alt="picture-{{ $user->name }}" class="picture-img" width="300" height="300">
              </div>
            </div>
            <div class="d-flex justify-content-center">
              <form action="{{ route('user.photo-update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-file-upload form-file-simple my-3 ">
                  <label class="form-label">Upload Foto :</label>
                  <input type="file" class="form-control" name="profilePhotoPath">
                </div>
                <button type="submit" class="btn bg-gradient-success btn-lg w-100">Simpan</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-8">
        <div class="card my-4">
          <div class="card-header">
            <div class="nav-wrapper position-relative end-0">
              <ul class="nav nav-pills nav-fill p-1" role="tablist">
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs-icons" role="tab" aria-controls="preview" aria-selected="true">
                  <span class="material-icons align-middle mb-1">badge</span>
                  Profile saya
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#password-tabs-icons" role="tab" aria-controls="code" aria-selected="false">
                    <span class="material-icons align-middle mb-1">
                      lock
                    </span>
                     Password
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="tab-content">
            <div id="profile-tabs-icons" class="tab-pane fade show active">
              <form action="{{ route('user.profile-update', $user) }}" method="POST" >
                @csrf
                @method('PATCH')
                <div class="card-body">
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" value="{{ $user->email }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Alamat</label>
                    <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">No Telp</label>
                    <input type="text" class="form-control" name="phone_number" value="{{ $user->phone_number }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn bg-gradient-success btn-lg w-100">Simpan</button>
                </div>
              </form>
            </div>
            <div id="password-tabs-icons" class="tab-pane fade show">
              <form action="{{ route('user.password-update', $user) }}" method="POST" >
                @csrf
                @method('PATCH')
                <div class="card-body">
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Password lama</label>
                    <input type="password" class="form-control" name="current_password">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" name="password_confirmation">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn bg-gradient-success btn-lg w-100">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
  </div>


@endsection
   

    
{{-- </x-app-layout> --}}

