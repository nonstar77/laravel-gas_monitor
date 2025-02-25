// Data untuk Chart dengan 3 subjek
let chartData = {
    labels: [], // Timestamp
    datasets: [
        {
            label: 'Gas Value MQ4',
            data: [],
            borderColor: '#1e90ff',
            backgroundColor: 'rgba(0, 102, 204, 0.2)',
            fill: true,
            tension: 0.5,
        },
        {
            label: 'Gas Value MQ6',
            data: [],
            borderColor: '#ab1111',
            backgroundColor: 'rgba(153, 0, 51, 0.2)',
            fill: true,
            tension: 0.5,
        },
        {
            label: 'Gas Value MQ8',
            data: [],
            borderColor: '#32cd32',
            backgroundColor: 'rgba(0, 153, 76, 0.2)',
            fill: true,
            tension: 0.5,
        }
    ]
};

// Konfigurasi Chart.js
const ctx = document.getElementById('gasChart').getContext('2d');
const gasChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                labels: { color: '#000000' } // Ubah warna teks legenda jadi hitam
            }
        },
        scales: {
            x: {
                ticks: { color: '#000000' } // Ubah warna teks sumbu X jadi hitam
            },
            y: {
                ticks: { color: '#000000' }, // Ubah warna teks sumbu Y jadi hitam
                beginAtZero: true
            }
        }
    }
});

let currentDeviceId = null;
let interval = null;

function updateChart(deviceId) {
    if (!deviceId || deviceId !== currentDeviceId) return;

    fetch(`/api/sensor?device_id=${deviceId}`)
        .then(response => response.json())
        .then(data => {
            let labels = [];
            let mq4_values = [];
            let mq6_values = [];
            let mq8_values = [];

            data.forEach(sensor => {
                let date = new Date(sensor.created_at);
                let formattedDate = date.toLocaleString("id-ID", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit"
                });

                labels.push(formattedDate);
                mq4_values.push(sensor.mq4_value);
                mq6_values.push(sensor.mq6_value);
                mq8_values.push(sensor.mq8_value);
            });

            gasChart.data.labels = labels;
            gasChart.data.datasets[0].data = mq4_values;
            gasChart.data.datasets[1].data = mq6_values;
            gasChart.data.datasets[2].data = mq8_values;
            gasChart.update();
        })
        .catch(error => console.error('Error fetching data:', error));
}

let deviceSelect = document.getElementById('device-select');
deviceSelect.addEventListener('change', function () {
    let deviceId = this.value;
    currentDeviceId = deviceId;

    gasChart.data.labels = [];
    gasChart.data.datasets.forEach(dataset => dataset.data = []);
    gasChart.update();

    if (interval) clearInterval(interval);

    updateChart(deviceId);

    interval = setInterval(() => {
        updateChart(deviceId);
    }, 5000);
});

// Ambil data pertama kali saat halaman dimuat
updateChart(currentDeviceId);

window.addEventListener("resize", function () {
    sensorChart.resize();
});
