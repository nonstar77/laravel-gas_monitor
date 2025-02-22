@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Monitoring Gas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Pilih Perangkat -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="device-select">Pilih Perangkat:</label>
                        <select id="device-select" class="form-control">
                            <option value="">Pilih Perangkat</option>
                            @foreach ($devices as $device)
                                <option value="{{ $device->id }}">{{ $device->name }}</option>
                            @endforeach
                        </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Grafik Data Sensor</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="sensorChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabel Data Sensor -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Sensor</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Perangkat</th>
                                        <th>MQ-4</th>
                                        <th>MQ-6</th>
                                        <th>MQ-8</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody id="sensor-data">
                                    @foreach($sensorData as $data)
                                        <tr>
                                            <td>{{ $data->device->name }}</td>
                                            <td>{{ $data->mq4_value }}</td>
                                            <td>{{ $data->mq6_value }}</td>
                                            <td>{{ $data->mq8_value }}</td>
                                            <td>{{ $data->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let ctx = document.getElementById('sensorChart').getContext('2d');
    let sensorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                { label: 'MQ-4', borderColor: 'red', backgroundColor: 'rgba(255,0,0,0.2)', data: [], fill: true },
                { label: 'MQ-6', borderColor: 'yellow', backgroundColor: 'rgba(255,255,0,0.2)', data: [], fill: true },
                { label: 'MQ-8', borderColor: 'blue', backgroundColor: 'rgba(0,0,255,0.2)', data: [], fill: true }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: { display: true, text: 'Waktu', color: '#FFFFFF' },
                    ticks: { color: '#FFFFFF' }
                },
                y: {
                    title: { display: true, text: 'Nilai Sensor', color: '#FFFFFF' },
                    ticks: { color: '#FFFFFF' },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#FFFFFF',
                        font: { size: 14 }
                    }
                }
            }
        }
    });

    function updateChart(deviceId) {
        if (!deviceId) return;

        fetch(`/api/sensor?device_id=${deviceId}`)
            .then(response => response.json())
            .then(data => {
                let labels = [];
                let mq4_values = [];
                let mq6_values = [];
                let mq8_values = [];

                data.forEach(sensor => {
                    labels.push(sensor.created_at);
                    mq4_values.push(sensor.mq4_value);
                    mq6_values.push(sensor.mq6_value);
                    mq8_values.push(sensor.mq8_value);
                });

                sensorChart.data.labels = labels;
                sensorChart.data.datasets[0].data = mq4_values;
                sensorChart.data.datasets[1].data = mq6_values;
                sensorChart.data.datasets[2].data = mq8_values;
                sensorChart.update();
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    let deviceSelect = document.getElementById('device-select');
    deviceSelect.addEventListener('change', function () {
        let deviceId = this.value;
        updateChart(deviceId); // Panggil pertama kali saat perangkat dipilih

        setInterval(() => {
            updateChart(deviceId); // Ambil data tiap 5 detik
        }, 5000);
    });
});

</script>
@endsection

