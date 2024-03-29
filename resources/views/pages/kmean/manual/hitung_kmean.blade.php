<x-admin-layout>
    <x-slot name="title">
        Hitung Kmean Manual
    </x-slot>
    <div class="row bg-white py-4 px-3">
        <div class="col-12">
            <form method="POST" action="{{ route('proses-kmean-steps-1') }}">
                @method('POST')
                @csrf
                {{-- <div class="form-group"> --}}
                {{-- <label for="max_literasi">Max Literasi</label> --}}
                <input type="hidden" value="{{ old('max_literasi') ?? 100 }}" class="form-control" name="max_literasi"
                    id="max_literasi">
                {{-- @error('max_literasi') --}}
                {{-- <small class="form-text text-danger"> --}}
                {{-- {{ $message }}</small> --}}
                {{-- @enderror --}}
                {{-- </div> --}}
                <div class="form-group">
                    <label for="jumlah_centroid">Jumlah Centroid</label>
                    <input type="number" value="{{ old('jumlah_centroid') }}" class="form-control"
                        name="jumlah_centroid" id="jumlah_centroid">
                    @error('jumlah_centroid')
                        <small class="form-text text-danger">
                            {{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Proses Kmean</button>
            </form>
        </div>
    </div>
</x-admin-layout>
