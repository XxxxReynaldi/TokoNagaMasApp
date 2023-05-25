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

  @push('scripts')
  <script>
      $(document).ready(function() {
        
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
          
      });
  </script>
  @endpush
@endsection
   

    
{{-- </x-app-layout> --}}

