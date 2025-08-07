// resources/js/dashboard-chart.js

document.addEventListener('DOMContentLoaded', () => {
    const chartCanvas = document.getElementById('nilaiChart');

    if (!chartCanvas) {
        return;
    }

    // --- PERBAIKAN DI SINI ---
    // Kita tambahkan '|| "[]"' sebagai nilai default jika data-attributenya tidak ada.
    // Ini memastikan JSON.parse() selalu menerima string JSON yang valid.
    const labels = JSON.parse(chartCanvas.dataset.labels || '[]');
    const dataIPS = JSON.parse(chartCanvas.dataset.ips || '[]');
    const dataIPK = JSON.parse(chartCanvas.dataset.ipk || '[]');
    // --- AKHIR PERBAIKAN ---

    new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: labels, // Gunakan variabel yang sudah di-parse
            datasets: [{
                label: 'IP Semester',
                data: dataIPS, // Gunakan variabel yang sudah di-parse
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                tension: 0.1
            }, {
                label: 'IPK',
                data: dataIPK, // Gunakan variabel yang sudah di-parse
                borderColor: 'rgb(167, 139, 250)',
                backgroundColor: 'rgba(167, 139, 250, 0.5)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
});