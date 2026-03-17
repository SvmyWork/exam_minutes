const ctx = document.getElementById('area-chart').getContext('2d');

// Sample data for different periods
const chartData = {
    yesterday: [100, 200, 150, 220, 180, 90, 120],
    today: [400, 500, 300, 450, 600, 300, 500],
    last7: [1200, 1900, 3000, 5000, 2300, 3400, 4000],
    last30: [3000, 3500, 4200, 3800, 4500, 4800, 5000],
    last90: [1000, 2000, 3000, 4000, 3500, 3700, 3900],
};

const userCounts = {
    yesterday: "1.2₹",
    today: "3.5₹",
    last7: "32.4₹",
    last30: "120₹",
    last90: "300₹",
};

const percentageChanges = {
    yesterday: "5%",
    today: "8%",
    last7: "12%",
    last30: "20%",
    last90: "35%",
};

const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Earnings',
            data: chartData.last7,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { display: false },
            y: { display: false }
        }
    }
});

const dropdownButton = document.getElementById('dropdownDefaultButton');
const dropdownMenu = document.getElementById('lastDaysdropdown');
const userCount = document.getElementById('user-count');
const percentageChange = document.getElementById('percentage-change');
const Duration = document.getElementById('Duration');

dropdownButton.addEventListener('click', (event) => {
    event.stopPropagation();
    dropdownMenu.classList.toggle('hidden');
});

document.addEventListener('click', function (event) {
    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add('hidden');
    }
});

// Update chart and data when clicking dropdown options
document.querySelectorAll('#lastDaysdropdown a').forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        const period = item.getAttribute('data-period');

        if (chartData[period]) {
            myChart.data.datasets[0].data = chartData[period];
            myChart.update();
        }

        userCount.textContent = userCounts[period];
        Duration.textContent = `Earning this ${period}`;
        percentageChange.innerHTML = `
            ${percentageChanges[period]}
            <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 10 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13V1m0 0L1 5m4-4 4 4" />
            </svg>
        `;

        // Update dropdown button label
        dropdownButton.innerHTML = `
            ${item.textContent}
            <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m1 1 4 4 4-4" />
            </svg>
        `;

        dropdownMenu.classList.add('hidden');
    });
});