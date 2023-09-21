<x-admin-layout>
    <x-slot name="title">
        Data Transaksi
    </x-slot>

    <div class="row bg-white py-4 px-3">
        <div class="col-8 mb-2 d-flex">

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#Importdata">
                Import Data
            </button>
            <form action="{{ route('data.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="confirm('anda yakin ingin menghapus semua data?')">Reset
                    Data</button>
            </form>
            <!-- Modal -->
            <div class="modal fade" id="Importdata" tabindex="-1" aria-labelledby="export-data" aria-hidden="true">
                <div class="modal-dialog modal-md ">
                    <div class="modal-content">
                        <form action="{{ route('data.transaksi.import') }}" method="POST"
                            enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="export-data">Export data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="file">Export data</label>
                                    <input type="file" class="form-control-file" id="file" name="file">
                                    @error('file')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">kode</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Margin</th>
                        <th scope="col">Stok Awal</th>
                        <th scope="col">Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datas as $key => $data)
                        <tr>
                            <th scope="row">{{ $datas->firstItem() + $key }}</th>
                            <td>{{ $data->kode }}</td>
                            <td>{{ $data->nama_barang ?? '-' }}</td>
                            <td>{{ $data->stok_akhir ?? '-' }}</td>
                            <td>{{ $data->stok_awal ?? '-' }}</td>
                            <td>{{ $data->ttl_penjualan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>
                                Data Kosong
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>
</x-admin-layout>
