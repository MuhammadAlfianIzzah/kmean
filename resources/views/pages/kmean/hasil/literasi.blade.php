<x-admin-layout>
    <h4>Hasil Kmean</h4>
    @php
        function setColor($label)
        {
            if ($label == 'c1') {
                return '#fdfdfd';
            } elseif ($label == 'c2') {
                return '#fff4e3';
            } elseif ($label == 'c3') {
                return '#22d1ee';
            } elseif ($label == 'c4') {
                return '#ecfffb';
            } elseif ($label == 'c5') {
                return 'blue';
            } elseif ($label == 'c6') {
                return '#e8ffe8';
            } else {
                return '#5d5d5a';
            }
        }
    @endphp
    <div class="row">
        <div class="col-6">
            <canvas id="myChart" width="100" height="50"></canvas>
        </div>
    </div>
    <div class="row bg-white  py-4 px-3">
        <div class="col-12">
            <form action="" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                        aria-label="Search" name="search" value="{{ old('search') ?? request('search') }}"
                        aria-describedby="basic-addon2">
                    <select class="custom-select" name="c_min">
                        <option disabled selected>Filter By c_min</option>
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
                        <th scope="col">c4</th>
                        <th scope="col">c5</th>
                        <th scope="col">c6</th>
                        <th scope="col">c_min</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_klusters as $key => $kluster)
                        <tr style="background-color: <?= setColor($kluster->c_min) ?>">
                            <th scope="row">{{ $data_klusters->firstItem() + $key }}</th>
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
            const data = {
                labels: @json($data_chart->pluck('c_min')),
                datasets: [{
                    label: 'My First Dataset',
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
            const config = {
                type: 'bar',
                data: data,
            };


            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        </script>
    @endpush
</x-admin-layout>
