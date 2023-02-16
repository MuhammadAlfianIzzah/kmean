<x-admin-layout>
    <h4>Hitung Kmean</h4>

    <div class="row bg-white py-4 px-3">
        @foreach ($data_proses as $data)
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        data proses pada tanggal {{ $data->created_at }}
                    </div>
                    <a href="{{ route('hasil.kmean', [$data->id, 'progress_id' => $data->progress_id]) }}"
                        class="btn btn-primary">DETAIL</a>
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
