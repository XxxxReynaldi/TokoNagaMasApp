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
        <h6>Selamat datang, {{ auth()->user()->name }} </h6>
        <div class="d-flex justify-content-center align-items-center">
          <img src="{{ asset('img/logo.png') }}" aria-hidden alt="picture-{{ auth()->user()->name }}" >
        </div>
      </div>
    </div>
    
  </div>

@endsection
   

    
{{-- </x-app-layout> --}}

