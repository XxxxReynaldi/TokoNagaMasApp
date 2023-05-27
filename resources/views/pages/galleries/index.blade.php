{{-- <x-app-layout> --}}

@extends('layouts.admin')

@section('title', '- Halaman Galeri')

@section('content')

<div class="container-fluid py-4">
<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Data Galeri</h6>
          </div>
      </div>
      <div class="card-body px-0 pb-2">
        <div class="card-header p-0 mx-3 mb-n4 z-index-2">
            <button type="button" data-bs-toggle="modal" data-bs-target="#createGalleryModal"
                class="btn bg-gradient-info btn-block mb-3">Tambah</button>
        </div>
        @if(($errors->any()))
          @foreach($errors->all() as $error)
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
          <table class="table align-items-center mb-0" id="galleries-table">
            <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Repaint</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rekomendasi Mekanik</th>
                  <th class="text-secondary opacity-7" width="10%"></th>
                </tr>
            </thead>
            <tbody>
              @foreach($galleries as $gallery)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $gallery->galleryPhotoPath }}"
                            class="avatar avatar-sm me-3 border-radius-lg"
                            alt="photo-{{ $gallery->repair_type }}">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $gallery->repair_type }}</h6>
                      </div>
                    </div>
                  </td>
                  <td class="text-xs text-secondary mb-0">{{ $gallery->description }}</td>
                  <td class="text-xs text-secondary mb-0">{{ $gallery->mechanic_recommendation }}
                  </td>
                  <td>
                    <button data-bs-toggle="modal"
                        data-bs-target="#editGalleryModal-{{ $gallery->id }}"
                        class="btn bg-gradient-success btn-block mb-3">Edit</button>
                    <button data-bs-toggle="modal"
                        data-bs-target="#deleteGalleryModal-{{ $gallery->id }}"
                        class="btn bg-gradient-danger btn-block mb-3">Hapus</button>
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
  <div class="modal fade" id="createGalleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-normal" id="galleryModalLabel">Tambah Galeri</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('galleries.store') }}" method="POST" id="gallery-form"
          enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Jenis Repaint</label>
              <input type="text" class="form-control" name="repair_type" required>
              <span class="invalid-feedback" role="alert"></span>
            </div>
            <div class="my-3">
              <label class="form-label">Deskripsi</label>
              <div class="input-group input-group-outline">
                  <textarea type="text" class="form-control" name="description" rows="3"></textarea>
              </div>
              <span class="invalid-feedback" role="alert"></span>
            </div>
            <div class="my-3">
              <label class="form-label">Bahan</label>
              <div class="input-group input-group-outline">
                  <select class="form-control material-selection" name="product[]" multiple="multiple"
                      style="width: 100%;">
                  </select>
              </div>
            </div>
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Rekomendasi Mekanik</label>
              <input type="text" class="form-control" name="mechanic_recommendation" required>
              <span class="invalid-feedback" role="alert"></span>
            </div>
            <div class="form-file-upload form-file-simple my-3 ">
              <label class="form-label">Upload Gambar</label>
              <input type="file" class="form-control" name="galleryPhotoPath">
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
  @foreach($galleries as $gallery)
    <div class="modal fade" id="editGalleryModal-{{ $gallery->id }}" tabindex="-1" role="dialog"
      aria-labelledby="galleryModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title font-weight-normal" id="galleryModalLabel">Edit Galeri</h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="modal-body">
              <div class="row">
                <div class="col-6">
                  <div class="d-flex justify-content-center">
                    <div class="picture-div blur">
                        <img src="{{ $gallery->galleryPhotoPath }}" aria-hidden
                            alt="picture-{{ $gallery->name }}" class="picture-img" width="300"
                            height="300">
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Jenis Repaint</label>
                    <input type="text" class="form-control" name="repair_type"
                        value="{{ $gallery->repair_type }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  <div class="my-3">
                    <label class="form-label">Deskripsi</label>
                    <div class="input-group input-group-outline">
                        <textarea type="text" class="form-control" name="description"
                            rows="3">{{ $gallery->description }}</textarea>
                    </div>
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  <div class="my-3">
                    <label class="form-label">Bahan</label>
                    <div class="input-group input-group-outline">
                        <select class="form-control material-selection" name="product[]" multiple="multiple" style="width: 100%;">
                          @if (!empty($gallery->products)) 
                              @foreach ($gallery->products as $product )
                                  <option value="{{ $product->id }}" selected >{{$product->name}}</option>
                              @endforeach
                          @else
                              
                          @endif
                        </select>
                    </div>
                  </div>
                  <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Rekomendasi Mekanik</label>
                    <input type="text" class="form-control" name="mechanic_recommendation" value="{{ $gallery->mechanic_recommendation }}">
                    <span class="invalid-feedback" role="alert"></span>
                  </div>
                  
                  <div class="form-file-upload form-file-simple my-3 ">
                    <label class="form-label">Upload Gambar baru</label>
                    <input type="file" class="form-control" name="galleryPhotoPath">
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

    <div class="modal fade" id="deleteGalleryModal-{{ $gallery->id }}" tabindex="-1" role="dialog"
        aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="galleryModalLabel">Hapus Galeri</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('galleries.destroy', $gallery) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apa anda yakin ingin menghapus produk <code>{{ $gallery->name }}</code> ?</p>
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
  $(document).ready(function () {

    galleriesTable();
    selectProduct();


  });

  function galleriesTable() {
      $('#galleries-table').DataTable();
  }

  function selectProduct() {
    $('.material-selection').select2({
      ajax: {
        url: '{{ route('product-api') }}', // 
        dataType: 'json',
        type: "POST",
        delay: 250,
        data: function (params) {
            return {
                searchTerm: params.term, // search term
                _token: '<?php echo csrf_token()?>'
            };
        },
        processResults: function (response) {
            const { data } = response
            return {
                results: $.map(data, function (item) {
                    return { id: item.id, text: item.name }
                })
            };
        },
        cache: true
      },
      placeholder: 'Pilih Bahan',
    });  
  }

</script>
@endpush


@endsection



{{-- </x-app-layout> --}}
