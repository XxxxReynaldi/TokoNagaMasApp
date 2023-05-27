{{-- <x-app-layout> --}}

@extends('layouts.admin')

@section('title', '- Halaman Produk')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Data Produk</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="card-header p-0 mx-3 mb-n4 z-index-2">
                <button type="button" data-bs-toggle="modal" data-bs-target="#createProductModal" class="btn bg-gradient-info btn-block mb-3">Tambah</button>           
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
                  <span class="alert-text"><strong>Berhasil ! </strong>{{ session('success') }}</span>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
            @endif

            <div class="table-responsive p-5">
              <table class="table align-items-center mb-0" id="products-table" >
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" width="10%">Stok barang</th>
                    <th  class="text-secondary opacity-7" width="10%"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($products as $product)
                      <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="{{$product->productPhotoPath}}" class="avatar avatar-sm me-3 border-radius-lg" alt="photo-{{$product->name}}">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                              </div>
                            </div>
                          </td>
                          <td class="text-xs text-secondary mb-0 text-center">{{ $product->description }}</td>
                          <td class="text-xs text-secondary mb-0 text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                          <td class="text-xs text-secondary mb-0">{{ $product->stock }}</td>
                          <td>  
                            <button data-bs-toggle="modal" data-bs-target="#editProductModal-{{$product->id}}" class="btn bg-gradient-success btn-block mb-3">Edit</button>
                            <button data-bs-toggle="modal" data-bs-target="#deleteProductModal-{{$product->id}}" class="btn bg-gradient-danger btn-block mb-3">Hapus</button>
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
<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="productModalLabel">Tambah Produk</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('products.store') }}" method="POST" id="product-form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="input-group input-group-outline my-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name" required>
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="row">
            <div class="col-3">
              <div class="input-group input-group-outline my-3">
                <label class="form-label">Stok</label>
                <input type="number" class="form-control" name="stock" required>
                <span class="invalid-feedback" role="alert"></span>
              </div>
            </div>
            <div class="col-9">
              <div class="input-group input-group-outline my-3">
                <label class="form-label">Harga</label>
                <input type="number" class="form-control" name="price" required>
                <span class="invalid-feedback" role="alert"></span>
              </div>
            </div>
          </div>
          <div class="my-3">
            <label class="form-label">Deskripsi</label>
            <div class="input-group input-group-outline">
              <textarea type="text" class="form-control" name="description" rows="3"></textarea>
            </div>
            <span class="invalid-feedback" role="alert"></span>
          </div>
          
          <div class="form-file-upload form-file-simple my-3 ">
            <label class="form-label">Upload Gambar</label>
            <input type="file" class="form-control" name="productPhotoPath">
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
@foreach ($products as $product)
<div class="modal fade" id="editProductModal-{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="productModalLabel">Edit Produk</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <div class="d-flex justify-content-center">
                  <div class="picture-div blur">
                    <img src="{{ $product->productPhotoPath }}" aria-hidden alt="picture-{{ $product->name }}" class="picture-img" width="300" height="300">
                  </div>
              </div>
            </div>
            <div class="col-6">
              <div class="input-group input-group-outline my-3 is-filled">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                <span class="invalid-feedback" role="alert"></span>
              </div>
              <div class="row">
                <div class="col-3">
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" name="stock" value="{{ $product->stock }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                </div>
                <div class="col-9">
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" name="price" value="{{ $product->price }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                </div>
              </div>
              <div class="my-3">
                <label class="form-label">Deskripsi</label>
                <div class="input-group input-group-outline">
                  <textarea type="text" class="form-control" name="description" rows="3">{{ $product->description }}</textarea>
                </div>
                <span class="invalid-feedback" role="alert"></span>
              </div>
              <div class="form-file-upload form-file-simple my-3 ">
                <label class="form-label">Upload Gambar baru</label>
                <input type="file" class="form-control" name="productPhotoPath">
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

<div class="modal fade" id="deleteProductModal-{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="productModalLabel">Hapus Produk</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('products.destroy', $product) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p>Apa anda yakin ingin menghapus produk <code>{{$product->name}}</code> ?</p>
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

      productsTable();
      function productsTable(){
        $('#products-table').DataTable();  
      }

    });
</script>
@endpush
 

@endsection
   

    
{{-- </x-app-layout> --}}

