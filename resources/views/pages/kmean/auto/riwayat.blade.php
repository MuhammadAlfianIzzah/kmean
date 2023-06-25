<x-admin-layout>
    <x-slot name="title">
        Riwayat Kmean
    </x-slot>
    <div class="row bg-white py-4 px-3">
        @forelse ($data_proses as $data)
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        data proses pada tanggal {{ $data->created_at }}
                    </div>
                    <a href="{{ route('hasil.kmean', [$data->id, 'progress_id' => $data->progress_id]) }}"
                        class="btn btn-primary">DETAIL</a>
                </div>
            </div>
        @empty
            <div class="alert alert-warning w-100" role="alert">
                Riwayat Belum Ada
            </div>
        @endforelse
    </div>
</x-admin-layout>
