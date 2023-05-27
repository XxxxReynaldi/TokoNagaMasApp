{{-- <x-app-layout> --}}

@extends('layouts.admin')

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
            <div class="card-header p-0 position-relative mx-3 mb-n4 z-index-2">
                <button onclick="add_mechanic()" type="button" data-bs-toggle="modal" data-bs-target="#mechanicModal" class="btn bg-gradient-info btn-block mb-3">Tambah</button>
            </div>
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
<div class="modal fade" id="mechanicModal" tabindex="-1" role="dialog" aria-labelledby="mechanicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="mechanicModalLabel">Nama Form</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="#" class="needs-validation" novalidate="" id="mechanic-form">
        <div class="modal-body">
          <div class="input-group input-group-outline my-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name">
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="input-group input-group-outline my-3">
            <label class="form-label">Kategori</label>
            <input type="text" class="form-control" name="category">
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="input-group input-group-outline my-3">
            <label class="form-label">Harga</label>
            <input type="text" class="form-control" name="price">
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="my-3">
            <label class="form-label">Deskripsi</label>
            <div class="input-group input-group-outline">
              <textarea type="text" class="form-control" name="description" rows="3"></textarea>
            </div>
            <span class="invalid-feedback" role="alert"></span>
          </div>
          <div class="input-group input-group-outline my-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" value="1" name="status" id="flexSwitchCheckDefault" checked="">
              <label class="custom-control-label" for="flexSwitchCheckDefault">Status Aktif</label>
            </div>
          </div>
         
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gray-100" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn bg-gradient-primary" id="saveBtn">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

  @push('scripts')
  <script>
      $(document).ready(function() {

        mechanicsTable();
        function mechanicsTable(){
          $('#mechanics-table').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ route('mechanics.index') }}",
              columns: [
                  { data: 'name', name: 'name', className:'text-xs text-secondary mb-0' },
                  { data: 'category', name: 'category',className:'text-xs text-secondary mb-0' },
                  { data: 'price', name: 'price',className:'text-xs text-secondary mb-0 text-end' },
                  { data: 'status_label', name: 'status_label',className:'text-xs text-secondary mb-0 text-center' },
                  // Kolom lainnya
                  { data: 'action', name: 'action', orderable: false, searchable: false, width: '20px' }
              ]
          });  
        }
          
        function add_mechanic(){
          $('#mechanic-form')[0].reset(); // reset form on modals
          $('.input-group').children().removeClass('is-invalid'); // clear error class
          $('.invalid-feedback').empty(); // clear error string
          $('#mechanicModal').modal('show'); // show bootstrap modal
          $('.modal-title').text('Tambah Mekanik'); // Set Title to Bootstrap modal title
          $('#image-preview').css("background-image", "none");
          $('.img-server').css("display", "none");
        }

      });
  </script>
  @endpush

  



@endsection
   

    
{{-- </x-app-layout> --}}

