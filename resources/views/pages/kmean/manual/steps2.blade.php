<x-admin-layout>
    <h4>Tentukan Centroid</h4>
    @if ($centroids->count() < request('jumlah_centroid'))
        <div class="row mt-4 mb-2">
            <div class="col-6">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahCentroid">
                    Tambah Centroid
                </button>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="tambahCentroid" tabindex="-1" aria-labelledby="tambahCentroidLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahCentroidLabel">Tambah Centroid</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('proses-kmean-steps-2.tambah.centroid') }}" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" id="literasi" name="literasi" value="1">
                        <input type="hidden" class="form-control" id="data_proses_id" name="data_proses_id"
                            value="{{ request('data_proses_id') ?? '' }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="c{{ $centroids->count() + 1 }}" readonly>
                                @error('nama')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="c1">c1</label>
                                    <input type="number" step="0.01" class="form-control" id="c1"
                                        name="c1" value="{{ old('c1') }}">
                                    @error('c1')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="c2">c2</label>
                                    <input type="number" step="0.01" class="form-control" id="c2"
                                        name="c2" value="{{ old('c2') }}">
                                    @error('c2')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="c3">c3</label>
                                    <input type="number" step="0.01" class="form-control" id="c3"
                                        name="c3" value="{{ old('c3') }}">
                                    @error('c3')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="c4">c4</label>
                                    <input type="number" step="0.01" class="form-control" id="c4"
                                        name="c4" value="{{ old('c4') }}">
                                    @error('c4')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                            {{-- <div class="form-group">
                            <label for="literasi">literasi</label>

                            @error('literasi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-primary mt-4" role="alert">
            Centroid telah terpenuhi
        </div>
    @endif
    <div class="row bg-white py-4 px-2">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Data Proses Id</th>
                        <th scope="col">Nama</th>
                        <th scope="col">C1</th>
                        <th scope="col">C2</th>
                        <th scope="col">C3</th>
                        <th scope="col">C4</th>
                        <th scope="col">Literasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($centroids as $centroid)
                        <tr>
                            <th scope="row">1</th>
                            <td>{{ $centroid->data_proses_id }}</td>
                            <td>{{ $centroid->nama }}</td>
                            <td>{{ $centroid->c1 }}</td>
                            <td>{{ $centroid->c2 }}</td>
                            <td>{{ $centroid->c3 }}</td>
                            <td>{{ $centroid->c4 }}</td>
                            <td>{{ $centroid->literasi }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>data kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($centroids->count() >= request('jumlah_centroid'))
        <div class="row justify-content-center mt-2 mb-4">
            <div class="col-12 text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#next-steps3">
                    Proses Kmean
                </button>
            </div>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="next-steps3" tabindex="-1" aria-labelledby="next-steps3Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="next-steps3Label">Konfirmasi Proses Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('proses-kmean-steps-3') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="data_proses_id">Data Proses ID</label>
                            <input type="number" value="{{ old('data_proses_id') ?? request('data_proses_id') }}"
                                class="form-control" name="data_proses_id" id="data_proses_id" readonly>
                            @error('data_proses_id')
                                <small class="form-text text-danger">
                                    {{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_centroid">jumlah_centroid</label>
                                <input type="number" step="0.01" class="form-control" id="jumlah_centroid"
                                    name="jumlah_centroid"
                                    value="{{ old('jumlah_centroid') ?? request('jumlah_centroid') }}" readonly>
                                @error('jumlah_centroid')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_literasi">max_literasi</label>
                                <input type="number" step="0.01" class="form-control" id="max_literasi"
                                    name="max_literasi" value="{{ old('max_literasi') ?? request('max_literasi') }}"
                                    readonly>
                                @error('max_literasi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Proses Kmean</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
