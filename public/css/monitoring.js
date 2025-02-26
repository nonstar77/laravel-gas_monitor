// Chart
let chartData = {
    labels: [],
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

const canvas = document.getElementById('gasChart');
const parent = canvas.parentElement;

const ctx = document.getElementById('gasChart').getContext('2d');
const gasChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: true, // Tambahkan ini
        plugins: {
            legend: {
                display: true,
                labels: { color: '#ffffff' }
            }
        },
        scales: {
            x: {
                ticks: { color: '#ffffff' }
            },
            y: {
                ticks: { color: '#ffffff' },
                beginAtZero: true
            }
        },
        layout: {
            backgroundColor: 'rgba(0, 0, 0, 0)'
        }
    }
});

parent.style.alignItems = "center";
parent.style.justifyContent = "center";
parent.style.overflow = "hidden";

document.getElementById('gasChart').style.backgroundColor = '#343a40';

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

updateChart(currentDeviceId);

window.addEventListener("resize", function () {
    gasChart.resize();
});

//Card
function updateCards(deviceId) {
    if (!deviceId || deviceId !== currentDeviceId) return;

    fetch(`/api/sensor?device_id=${deviceId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let latestData = data[data.length - 1]; // Ambil data terbaru

                document.getElementById("mq4-value").textContent = latestData.mq4_value;
                document.getElementById("mq6-value").textContent = latestData.mq6_value;
                document.getElementById("mq8-value").textContent = latestData.mq8_value;
            }
        })
        .catch(error => console.error("Error fetching data:", error));
}

document.addEventListener("DOMContentLoaded", function () {
    function updateCards(deviceId) {
        if (!deviceId || deviceId !== currentDeviceId) return;

        fetch(`/api/sensor?device_id=${deviceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let latestData = data[data.length - 1]; // Ambil data terbaru

                    let mq4 = document.getElementById("mq4-value");
                    let mq6 = document.getElementById("mq6-value");
                    let mq8 = document.getElementById("mq8-value");

                    if (mq4 && mq6 && mq8) {
                        mq4.textContent = latestData.mq4_value;
                        mq6.textContent = latestData.mq6_value;
                        mq8.textContent = latestData.mq8_value;
                    } else {
                        console.error("Elemen card tidak ditemukan!");
                    }
                }
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    updateCards(currentDeviceId);
});


deviceSelect.addEventListener("change", function () {
    let deviceId = this.value;
    currentDeviceId = deviceId;

    if (interval) clearInterval(interval);

    // Update chart & card setiap 5 detik
    updateChart(deviceId);
    updateCards(deviceId);

    interval = setInterval(() => {
        updateChart(deviceId);
        updateCards(deviceId);
    }, 5000);
});

// Jalankan saat halaman pertama kali dibuka
updateCards(currentDeviceId);
