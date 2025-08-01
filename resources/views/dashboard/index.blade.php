<x-layout>
    <x-slot:title>Dashboard</x-slot:title>

    <section class="bg-white py-8 md:py-16">
        <div class="mx-auto max-w-screen-xl px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Dashboard Overview</h2>
           @if(request('start_date') && request('end_date'))
                <p class="text-sm text-gray-600 mb-4">
                    Menampilkan data dari <strong>{{ request('start_date') }}</strong> sampai <strong>{{ request('end_date') }}</strong>
                </p>
            @endif
            <form method="GET" action="{{ route('dashboard.index') }}" class="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                </div>
                <div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow">
                        Filter
                    </button>
                </div>
            </form>
            <script>
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                startDateInput.addEventListener('change', function () {
                    endDateInput.min = this.value;

                    // Jika end_date sekarang < start_date, kosongkan
                    if (endDateInput.value && endDateInput.value < this.value) {
                        endDateInput.value = this.value;
                    }
                });
            </script>


            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-dashboard.card title="Total Revenue" value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}" color="green"/>
                <x-dashboard.card title="Total Orders" value="{{ $totalOrders }}" color="blue"/>
                <x-dashboard.card title="Total Products" value="{{ $totalProducts }}" color="yellow"/>
                <x-dashboard.card title="Total Customers" value="{{ $totalCustomers }}" color="purple"/>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center justify-end mb-8 gap-4">
                <a href="{{ route('dashboard.export.pdf') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                    Export PDF
                </a>
                <a href="{{ route('dashboard.export.excel') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    Export Excel
                </a>
            </div>

            <!-- Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <x-dashboard.chart-card title="Orders Per Month" canvasId="ordersChart" />
                <x-dashboard.chart-card title="Revenue Per Month" canvasId="revenueChart" />
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="p-6 bg-white rounded-lg shadow h-[400px]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Order Status Distribution</h3>
                    <canvas id="statusChart" class="h-full w-full"></canvas>
                </div>
                <div class="p-6 bg-white rounded-lg shadow h-[400px]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Geographical Distribution (Province)</h3>
                    <canvas id="provinceChart" class="h-full w-full"></canvas>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="p-6 bg-white rounded-lg shadow h-[400px]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Most Ordered Products</h3>
                    <canvas id="productChart" class="h-full w-full"></canvas>
                </div>
                <div class="p-6 bg-white rounded-lg shadow h-[400px]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Revenue by Category</h3>
                    <canvas id="categoryChart" class="h-full w-full"></canvas>
                </div>
            </div>                 
        </div>
    </section>

    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    console.log('📊 Chart script loaded'); // debug manual

    const months = {!! json_encode(array_keys($ordersPerMonth)) !!};
    const ordersData = {!! json_encode(array_values($ordersPerMonth)) !!};
    const revenueData = {!! json_encode(array_values($revenuePerMonth)) !!};

    const statusLabels = {!! json_encode(array_keys($orderStatusDistribution)) !!};
    const statusCounts = {!! json_encode(array_values($orderStatusDistribution)) !!};

    console.log({ months, ordersData, revenueData, statusLabels, statusCounts });

    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Order',
                data: ordersData,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderRadius: 5
            }]
        },
        options: { responsive: true }
    });

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueData,
                borderColor: 'rgba(34, 197, 94, 1)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: ['#22c55e', '#3b82f6', '#facc15', '#ef4444'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
    // 📌 Geographical Distribution (By Province)
const provinceLabels = {!! json_encode(array_keys($topProvinces)) !!};
const provinceCounts = {!! json_encode(array_values($topProvinces)) !!};

new Chart(document.getElementById('provinceChart'), {
    type: 'pie',
    data: {
        labels: provinceLabels,
        datasets: [{
            data: provinceCounts,
            backgroundColor: [
                '#f87171', // merah muda
                '#60a5fa', // biru muda
                '#34d399', // hijau toska
                '#facc15', // kuning
                '#a78bfa', // ungu
                '#94a3b8'  // abu-abu
            ],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { 
                position: 'right' 
            }
        }
    }
});


// 📌 Most Ordered Products
const productLabels = {!! json_encode(array_keys($topProducts)) !!};
const productCounts = {!! json_encode(array_values($topProducts)) !!};

new Chart(document.getElementById('productChart'), {
    type: 'bar',
    data: {
        labels: productLabels,
        datasets: [{
            label: 'Jumlah Terjual',
            data: productCounts,
            backgroundColor: '#3b82f6',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Produk Paling Banyak Dipesan'
            }
        }
    }
});

// 📌 Revenue by Product Category
const categoryLabels = {!! json_encode(array_keys($revenueByCategory)) !!};
const categoryRevenue = {!! json_encode(array_values($revenueByCategory)) !!};

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Total Pendapatan (Rp)',
            data: categoryRevenue,
            backgroundColor: '#10b981',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Pendapatan Berdasarkan Kategori Produk'
            }
        }
    }
});

</script>


    @endpush
</x-layout>
