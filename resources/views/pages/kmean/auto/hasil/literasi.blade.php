<x-admin-layout>
    <h4 class="py-3">Hasil Kmean</h4>
    @php
        function setColor($label)
        {
            if ($label == 'c1') {
                return '#fdfdfd';
            } elseif ($label == 'c2') {
                return '#303a52';
            } elseif ($label == 'c3') {
                return '#ff8a5c';
            } elseif ($label == 'c4') {
                return '##3f3b3b';
            } elseif ($label == 'c5') {
                return 'blue';
            } elseif ($label == 'c6') {
                return '#e8ffe8';
            } else {
                return '#1e2a78';
            }
        }
    @endphp
    <div class="row bg-white py-4 px-3">
        <div class="col-12">
            <h4 class="mb-2">Data Centroid</h4>
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">nama</th>
                        <th scope="col">c1</th>
                        <th scope="col">c2</th>
                        <th scope="col">c3</th>
                        {{-- <th scope="col">c4</th>
                        <th scope="col">c5</th>
                        <th scope="col">c6</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($centroids as $key => $centroid)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $centroid->nama }}</td>
                            <td>{{ $centroid->c1 ?? '-' }}</td>
                            <td>{{ $centroid->c2 ?? '-' }}</td>
                            <td>{{ $centroid->c3 ?? '-' }}</td>
                            {{-- <td>{{ $centroid->c4 ?? '-' }}</td>
                            <td>{{ $centroid->c5 ?? '-' }}</td>
                            <td>{{ $centroid->c6 ?? '-' }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row bg-white mt-2 py-4 px-3 mb-2">
        <div class="col-12">
            Hasil Perhitungan Cluster
        </div>
        <div class="col-12 mt-2">
            <div class="col-12">
                <canvas id="barChar" width="100" height="20"></canvas>
            </div>
            {{-- @foreach ($data_chart as $dc)
                @php
                    $label = str_split($dc->c_min);
                    $label = 'Cluster ' . $label[1];
                @endphp
                <div class="mb-1"><span class="font-weight-bold">{{ $label }}</span>: {{ $dc->total }}</div>
            @endforeach --}}
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-6">
            <canvas id="myChart" width="100" height="50"></canvas>
        </div>
    </div> --}}
    <div class="row bg-white  py-4 px-3">
        <div class="col-12">
            <form action="" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                        aria-label="Search" name="search" value="{{ old('search') ?? request('search') }}"
                        aria-describedby="basic-addon2">
                    <select class="custom-select" name="c_min">
                        <option disabled selected>Filter By Cluster</option>
                        @foreach ($data_chart->pluck('c_min') as $value)
                            <option {{ request('c_min') == $value ? 'selected' : '' }} value="{{ $value }}">
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row bg-white py-4 px-3">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">nama</th>
                        <th scope="col">c1</th>
                        <th scope="col">c2</th>
                        <th scope="col">c3</th>
                        {{-- <th scope="col">c4</th>
                        <th scope="col">c5</th>
                        <th scope="col">c6</th> --}}
                        <th scope="col">Cluster</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_klusters as $key => $kluster)
                        <tr>
                            <th scope="row">{{ $data_klusters->firstItem() + $key }}</th>
                            <td>{{ $kluster->nama }}</td>
                            <td>{{ $kluster->c1 ?? '-' }}</td>
                            <td>{{ $kluster->c2 ?? '-' }}</td>
                            <td>{{ $kluster->c3 ?? '-' }}</td>
                            {{-- <td>{{ $kluster->c4 ?? '-' }}</td>
                            <td>{{ $kluster->c5 ?? '-' }}</td>
                            <td>{{ $kluster->c6 ?? '-' }}</td> --}}
                            <td>{{ $kluster->c_min }}</td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $data_klusters->appends(request()->query())->links() }}
        </div>
    </div>
    @if (request('progress_id') !== null)
        @push('script')
            <script>
                $.ajax({
                    type: 'GET', //THIS NEEDS TO BE GET
                    url: '/check-progress/' + @json(request('progress_id')),
                    success: function(data) {
                        if (data.data.progress == 100) {
                            let element = document.querySelector(".el-progress").innerHTML = `<div class="alert alert-primary" role="alert">
                            Data berhasil diload
                            </div>`
                        } else {
                            setTimeout(() => {
                                location.reload();
                            }, 5000);
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
    @push('script')
        <script type="text/javascript">
            const databarChar = {
                labels: @json($data_chart->pluck('c_min')),
                datasets: [{
                    label: 'Hasil Elbow',
                    data: @json($data_chart->pluck('total')),
                    // fill: false,
                    tension: 0.1,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                    ],
                }],

            };
            // const configBarChart = {
            //     type: 'line',
            //     data: dataBarChart,
            // };
            const configbarChar = {
                type: 'bar',
                data: databarChar,
            };
            const barChar = new Chart(
                document.getElementById('barChar'),
                configbarChar
            );
        </script>
    @endpush
</x-admin-layout>
