<x-admin-layout>
    <h4 class="text-bold text-dark">Dashboard</h4>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Repudiandae sint voluptates porro rerum reprehenderit
        fugiat minus, veniam hic cum consectetur?</p>
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
