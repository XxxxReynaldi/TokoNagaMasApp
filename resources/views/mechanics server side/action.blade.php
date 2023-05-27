
<div class="d-flex gap-1 justify-content-center p-lg-0">
    <button data-bs-id="{{ $mechanic->id }}" data-bs-toggle="modal" data-bs-target="#mechanicModal" class="btn bg-gradient-success btn-block mb-3">Edit</button>
    <button data-id="{{ $mechanic->id }}"
        class="btn bg-gradient-danger btn-block mb-3">Hapus</button>

    {{-- <form action="{{ route('mechanics.destroy', $mechanic->id) }}" method="POST" >
        @csrf
        @method('DELETE')

        <button type="button" class="btn bg-gradient-danger btn-block mb-3">Delete</button>
    </form> --}}
</div>
