<x-admin-layout>
    <x-slot name="title">
        Halaman Engine
    </x-slot>
    <div class="container-fluid bg-white mb-2">
        <div class="row d-flex align-items-center py-3 bg-white">
            <div class="col-8">
                <form class="d-none d-sm-inline-block form-inline w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-white small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-4">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#createengine">
                    Tambah
                </button>

            </div>
        </div>
        {{-- <img src="../resources/images/Image1.jpg"
            onerror="this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTWje_gjVcmi-wks5nTRnW_xv5W2l3MVnk7W1QDcZuhNg&s'" /> --}}
    </div>


    <!-- Modal -->
    <div class="modal fade" id="createengine" tabindex="-1" aria-labelledby="createengineLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content" action="{{ route('engine.store') }}">
                @method('POST')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createengineLabel">Tambah Data engine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama engine</label>
                        <input type="text" name="nama" class="form-control" id="nama"
                            value="{{ old('nama') ?? '' }}">
                        @error('nama')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jumlah_cluster">jumlah_cluster</label>
                        <input type="text" name="jumlah_cluster" class="form-control" id="jumlah_cluster"
                            value="{{ old('jumlah_cluster') ?? '' }}">
                        @error('jumlah_cluster')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="max_literasi">max_literasi</label>
                        <input type="text" name="max_literasi" class="form-control" id="max_literasi"
                            value="{{ old('max_literasi') ?? '' }}">
                        @error('max_literasi')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid bg-white">
        <div class="row">
            <div class="col-12 py-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">id</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($engines as $key => $engine)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $engine->id }}</td>
                                <td>{{ $engine->nama }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <form action="{{ route('engine.destroy', [$engine->id]) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                        <a href="{{ route('engine.show', [$engine->id, 'job_id' => $engine->job_id ?? null]) }}"
                                            class="btn btn-light">Show</a>
                                        @if (is_null($engine->finish_at))
                                            <form action="{{ route('engine.run', [$engine->id]) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fa-solid fa-play"></i> Engine</button>
                                            </form>
                                        @else
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa-solid fa-play"></i> Already Running</button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>Data Kosong</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                {{ $engines->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
