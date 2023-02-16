<x-admin-layout>
    <h4>Hasil Kmean</h4>
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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        @if (request('progress_id') !== null)
            <div class="el-progress">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0"
                        aria-valuemax="100">Loading</div>
                </div>
            </div>
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="row">
        <div class="col-6">
            <canvas id="barChart" width="100" height="50"></canvas>
        </div>
        <div class="col-6">
            <canvas id="lineChart" width="100" height="50"></canvas>
        </div>
    </div>

    <div class="row bg-white py-4 px-3">
        @foreach ($data_klusters as $key => $klusters)
            <div class="mb-3 d-flex w-100 justify-content-between">

                <div class="alert alert-primary" role="alert">
                    Literasi {{ $key + 1 }}
                </div>
                <div class="btn-group" style="height: 40px" role="group" aria-label="Basic example">
                    <a href="{{ route('show.hasil.kmean.literasi', [$klusters[0]->data_proses_id, $key + 1]) }}"
                        class="btn btn-secondary">Show</a>
                </div>
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
                            <th scope="col">c4</th>
                            <th scope="col">c5</th>
                            <th scope="col">c6</th>
                            <th scope="col">c_min</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($klusters as $key => $kluster)
                            <tr style="background-color: <?= setColor($kluster->c_min) ?>">
                                <th scope="row">{{ $klusters->firstItem() + $key }}</th>
                                <td>{{ $kluster->nama }}</td>
                                <td>{{ $kluster->c1 ?? '-' }}</td>
                                <td>{{ $kluster->c2 ?? '-' }}</td>
                                <td>{{ $kluster->c3 ?? '-' }}</td>
                                <td>{{ $kluster->c4 ?? '-' }}</td>
                                <td>{{ $kluster->c5 ?? '-' }}</td>
                                <td>{{ $kluster->c6 ?? '-' }}</td>
                                <td>{{ $kluster->c_min }}</td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
                {{ $klusters->appends(request()->query())->links() }}
            </div>
        @endforeach
    </div>
    @if (request('progress_id') !== null)
        @push('script')
            <script>
                $.ajax({
                    type: 'GET', //THIS NEEDS TO BE GET
                    url: '/kmean-auto/check-progress/' + @json(request('progress_id')),
                    success: function(data) {
                        if (data.data.progress == 100) {
                            let element = document.querySelector(".el-progress").innerHTML = `<div class="alert alert-primary" role="alert">
                            Data berhasil diload
                            </div>`
                        } else {
                            setTimeout(() => {
                                location.reload();
                            }, 500);
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
            const dataBarChart = {
                labels: @json($data_chart->pluck('c_min')),
                datasets: [{
                    label: 'BarChart',
                    data: @json($data_chart->pluck('total')),
                    backgroundColor: [
                        '#fdfdfd',
                        '#fff4e3',
                        '#22d1ee',
                        '#ecfffb',
                        'blue',
                        '#e8ffe8',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            };
            const dataLineChart = {
                labels: @json($data_line_chart->pluck('label')),
                datasets: [{
                    label: 'My First Dataset',
                    data: @json($data_line_chart->pluck('persen')),
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };
            const configBarChart = {
                type: 'line',
                data: dataBarChart,
            };
            const configLineChart = {
                type: 'line',
                data: dataLineChart,
            };


            const barChart = new Chart(
                document.getElementById('barChart'),
                configBarChart
            );
            const lineChart = new Chart(
                document.getElementById('lineChart'),
                configLineChart
            );
        </script>
    @endpush
</x-admin-layout>
