<x-admin-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Import</h5>
                    <a href="{{ route('data.transaksi.import') }}" class="card-text">Import Data</a>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Proses Kmean</h5>
                    <a href="{{ route('hitung.kmean') }}" class="card-text">Proses Kmean</a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
