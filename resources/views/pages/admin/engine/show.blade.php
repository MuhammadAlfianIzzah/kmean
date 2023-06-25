<x-admin-layout>
    <x-slot name="title">
        Halaman Engine
    </x-slot>
    @if (request('job_id'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="el-progress">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0"
                        aria-valuemax="100">Loading</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="jumbotron bg-white">
        <h3 class="display-5">#Engine: {{ $engine->nama }}</h3>
        <hr>
        <p class="p-0">
            <i class="fa-solid fa-check-double text-success"></i>
            Jumlah Kluster
            :
            <span class="text-info">{{ $engine->jumlah_cluster }} </span>
        </p>
        <p class="p-0">
            <i class="fa-solid fa-check-double text-success"></i>
            Max Literasi
            :
            <span class="text-info">{{ $engine->max_literasi }} </span>
        </p>

        <p>
            <i class="fa-solid fa-check-double text-success"></i>
            Kluster
            :
            @foreach ($klusters as $kluster)
                <span class="badge badge-primary">Kluster {{ $kluster }}</span>
            @endforeach
        </p>

        @foreach ($klusters as $kluster)
            <p class="p-0">
                <i class="fa-solid fa-check-double text-success"></i>
                Kluster {{ $kluster }}
                :
                <span class="text-info">{{ $engine->historyEngine()->where(['cluster' => $kluster])->count() }} </span>
            </p>
        @endforeach

    </div>

    <div class="container-fluid bg-white">
        <div class="row">
            <div class="col-12 py-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">kode</th>
                            <th scope="col">nama_barang</th>
                            <th scope="col">stok_awal</th>
                            <th scope="col">ttl_penjualan</th>
                            <th scope="col">stok_akhir</th>
                            <th scope="col">cluster</th>
                            <th scope="col">engine_id</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($engine->historyEngine()->paginate(10) as $key=> $he)
                            <tr>
                                <th scope="row">{{ $engine->historyEngine()->paginate(10)->firstItem() + $key }}
                                </th>
                                <td>{{ $he->kode }}</td>
                                <td>{{ $he->nama_barang }}</td>
                                <td>{{ $he->stok_awal }}</td>
                                <td>{{ $he->ttl_penjualan }}</td>
                                <td>{{ $he->stok_akhir }}</td>
                                <td>{{ $he->cluster }}</td>
                                <td>{{ $he->engine_id }}</td>
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
        {{ $engine->historyEngine()->paginate(10)->withQueryString()->links() }}
    </div>
    @if (request('job_id') !== null)
        @push('script')
            <script>
                $.ajax({
                    type: 'GET', //THIS NEEDS TO BE GET
                    url: '/check-progress/' + @json(request('job_id')),
                    success: function(data) {
                        if (data.data.progress == 100) {
                            let element = document.querySelector(".el-progress").innerHTML = `<div class="alert alert-primary" role="alert">
                            Data berhasil diload
                            </div>`
                        } else {
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    }
                });
            </script>
        @endpush
    @endif
</x-admin-layout>
