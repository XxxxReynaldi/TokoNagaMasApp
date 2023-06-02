{{-- <x-app-layout> --}}

@extends('layouts.admin')

@section('title', '- Halaman Transaksi Produk')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Data Transaksi Produk</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            {{-- <div class="card-header p-0 mx-3 mb-n4 z-index-2"> --}}
                {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#createProductModal" class="btn bg-gradient-info btn-block mb-3">Tambah</button>            --}}
            {{-- </div> --}}
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
              <table class="table align-items-center mb-0" id="p-transactions-table" >
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Pembeli</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bukti Pembayaran</th>
                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Pembayaran</th>
                    <th  class="text-secondary opacity-7" width="10%"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($productTransactions as $productTransaction)
                      <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="{{$productTransaction->user->profilePhotoPath}}" class="avatar avatar-sm me-3 border-radius-lg" alt="photo-{{$productTransaction->user->name}}">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $productTransaction->user->name }}</h6>
                              </div>
                            </div>
                          </td>
                          <td class="text-xs text-secondary mb-0 text-center">{{ $productTransaction->status }}</td>
                          <td>
                            <div class="d-flex justify-content-center">
                              <img data-bs-toggle="modal" data-bs-target="#previewProductTransModal-{{$productTransaction->id}}" 
                              src="{{$productTransaction->purchaseReceiptPath}}" class="avatar avatar-lg me-3 border-radius-lg" 
                              alt="photo-{{$productTransaction->purchaseReceiptPath}}" style="cursor: pointer">
                            </div>
                          </td>
                          <td class="text-xs text-secondary mb-0 text-end">Rp {{ number_format($productTransaction->total_price, 0, ',', '.') }}</td>
                          <td>  
                            <button data-bs-toggle="modal" data-bs-target="#editProductTransModal-{{$productTransaction->id}}" class="btn bg-gradient-success btn-block mb-3">Edit</button>
                            {{-- <button data-bs-toggle="modal" data-bs-target="#deleteProductTransModal-{{$productTransaction->id}}" class="btn bg-gradient-danger btn-block mb-3">Hapus</button> --}}
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
@foreach ($productTransactions as $productTransaction)

<div class="modal fade" id="previewProductTransModal-{{$productTransaction->id}}" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="productModalLabel">Preview Bukti Transaksi Produk</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="d-flex justify-content-center">
                <div class="picture-receipt-div filter">
                  <img src="{{ $productTransaction->purchaseReceiptPath }}" aria-hidden alt="picture-{{ $productTransaction->user->name }}" class="picture-receipt-img" width="100%" height="100%">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editProductTransModal-{{$productTransaction->id}}" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="productModalLabel">Konfirmasi Pembayaran Transaksi Produk</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('product-transactions.update', $productTransaction) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-6" style="display: flex; flex-wrap: wrap;">
              
                <div class="d-flex justify-content-center align-content-center">
                    <div class="picture-receipt-div filter">
                      <img src="{{ $productTransaction->purchaseReceiptPath }}" aria-hidden alt="picture-{{ $productTransaction->id }}" class="picture-receipt-img" width="100%" height="100%">
                    </div>
                </div>
              
            </div>
            <div class="col-6">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <tbody>
                    <tr>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Nama</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="5%">:</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ $productTransaction->user->name }}</td>
                    </tr>
                    <tr>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Nama Bank</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="5%">:</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ $productTransaction->bank_name }}</td>
                    </tr>
                    <tr>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Atas Nama</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="5%">:</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ $productTransaction->bank_account_name }}</td>
                    </tr>
                    <tr>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">No Rekening</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder" width="5%">:</td>
                      <td class="text-uppercase text-secondary text-xxs font-weight-bolder">{{ $productTransaction->account_number }}</td>
                    </tr>
                  </tbody>
                </table>
                <hr>
                <table class="table align-items-center mb-0" class="product-table">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-xxs font-weight-bolder opacity-100">Nama Produk</th>
                      <th class="text-uppercase text-xxs font-weight-bolder opacity-100 text-center">Jumlah</th>
                      <th class="text-uppercase text-xxs font-weight-bolder opacity-100 text-end">Total Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{-- {{dump($productTransaction->products)}} --}}
                    @foreach ($productTransaction->products as $product)
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
                      <td class="text-xs text-secondary mb-0 text-center">{{ $product->pivot->quantity }}</td>
                      <td class="text-xs text-secondary mb-0 text-end">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                      <th class="text-uppercase text-secondary text-xs text-start" colspan="2">Total Harga Akhir</th>
                      <th class="text-uppercase text-secondary text-xs text-end" >Rp {{ number_format($productTransaction->total_price, 0, ',', '.') }}</th>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="my-4">
                <label class="form-label">Status</label>
                <div class="input-group input-group-outline">
                  <select class="form-control material-selection" name="status" style="width: 100%;">
                    <option value="pending" @if($productTransaction->status == 'pending') selected @endif>pending</option>
                    <option value="kirim" @if($productTransaction->status == 'kirim') selected @endif>kirim</option>
                    <option value="selesai" @if($productTransaction->status == 'selesai') selected @endif>selesai</option>
                    <option value="batal" @if($productTransaction->status == 'batal') selected @endif>batal</option>
                  </select>
                </div>
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

{{-- <div class="modal fade" id="deleteProductTransModal-{{$productTransaction->id}}" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="productModalLabel">Hapus Transaksi Produk</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('product-transactions.destroy', $productTransaction) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p>Apa anda yakin ingin menghapus produk <code>{{$productTransaction->name}}</code> ?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gray-100" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn bg-gradient-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div> --}}
@endforeach

@endpush



@push('scripts')
<script>
    $(document).ready(function() {

      productTransactionsTable();
      mechanicTransactionsTable();
      function productTransactionsTable(){
        $('#p-transactions-table').DataTable();  
        $('.product-table').DataTable();  
      }

      function mechanicTransactionsTable(){
        $('#m-transactions-table').DataTable();  
      }

    });
</script>
@endpush
 

@endsection
   

    
{{-- </x-app-layout> --}}

