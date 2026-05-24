<x-admin-layout pageTitle="Analytics Dashboard">

@push('styles')
    <style>
        .metric-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid rgb(243 244 246);
            padding: 1.5rem;
        }
        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: rgb(17 24 39);
        }
        .metric-label {
            font-size: 0.875rem;
            color: rgb(75 85 99);
            margin-top: 0.25rem;
        }
        .metric-change {
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }
        .metric-change.positive {
            color: rgb(34 197 94);
        }
        .metric-change.negative {
            color: rgb(239 68 68);
        }
        .chart-container {
            height: 16rem;
            width: 100%;
        }
        .chart-container-large {
            height: 20rem;
            width: 100%;
        }
        .sidebar-nav {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid rgb(243 244 246);
            padding: 1rem;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: rgb(55 65 81);
            border-radius: 0.5rem;
            transition: all 0.2s;
            cursor: pointer;
        }
        .nav-item:hover {
            background-color: rgb(249 250 251);
        }
        .nav-item.active {
            background-color: rgb(239 246 255);
            color: rgb(37 99 235);
        }
        .analytics-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .main-content {
            grid-column: 1;
        }
        .sidebar-content {
            grid-column: 1;
        }
        
        /* Responsive fixes */
        @media (min-width: 768px) {
            .metric-card {
                padding: 1.25rem;
            }
            .metric-value {
                font-size: 1.75rem;
            }
            .chart-container {
                height: 18rem;
            }
            .chart-container-large {
                height: 22rem;
            }
        }
        
        @media (min-width: 1024px) {
            .analytics-grid {
                grid-template-columns: 2fr 1fr;
                gap: 2rem;
            }
            .main-content {
                grid-column: 1;
            }
            .sidebar-content {
                grid-column: 2;
            }
            .metric-card {
                padding: 1.5rem;
            }
            .metric-value {
                font-size: 2rem;
            }
            .chart-container {
                height: 16rem;
            }
            .chart-container-large {
                height: 20rem;
            }
        }
        
        /* Header responsive fixes */
        @media (max-width: 767px) {
            .space-y-6 > div:first-child {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .space-y-6 > div:first-child > div:last-child {
                width: 100%;
                flex-direction: column;
                gap: 0.75rem;
            }
            .space-y-6 > div:first-child select {
                width: 100%;
            }
            .space-y-6 > div:first-child button {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Metric cards responsive grid */
        @media (max-width: 767px) {
            .grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        @media (min-width: 768px) and (max-width: 1023px) {
            .grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }
        
        /* Chart container responsive */
        @media (max-width: 767px) {
            .chart-container,
            .chart-container-large {
                height: 12rem;
            }
        }
        
        /* Service charts grid */
        @media (max-width: 767px) {
            .grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        /* Table responsive */
        @media (max-width: 767px) {
            .overflow-x-auto {
                margin: 0 -1rem;
                padding: 0 1rem;
            }
        }
        
        /* Performance metrics responsive */
        @media (max-width: 767px) {
            .space-y-4 > div {
                padding: 0.75rem;
            }
        }
    </style>
@endpush

<div x-data="analyticsDashboard()" class="space-y-6">
    <!-- Header with Period Selector -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="text-gray-600 mt-1">Comprehensive insights for your travel business</p>
        </div>
        <div class="flex items-center gap-3">
            <select x-model="period" @change="refreshData()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="7d">Last 7 Days</option>
                <option value="30d" selected>Last 30 Days</option>
                <option value="90d">Last 90 Days</option>
                <option value="1y">Last Year</option>
            </select>
            <button @click="refreshData()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Refresh
            </button>
        </div>
    </div>

    <!-- Key Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue -->
        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value">৳{{ number_format($totalRevenue) }}</div>
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-change positive">
                        {{ $performanceMetrics['conversionRate'] }}% conversion rate
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bookings -->
        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value">{{ number_format($bookingStats['total']) }}</div>
                    <div class="metric-label">Total Bookings</div>
                    <div class="metric-change">
                        {{ $bookingStats['pending'] }} pending &middot; {{ $bookingStats['confirmed'] }} confirmed
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Visitors -->
        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value">{{ number_format($visitorStats['totalVisitors']) }}</div>
                    <div class="metric-label">Total Visitors</div>
                    <div class="metric-change">
                        {{ $visitorStats['bounceRate'] }}% bounce rate
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value">{{ $totalPackages + $activeVisas }}</div>
                    <div class="metric-label">Active Services</div>
                    <div class="metric-change">
                        {{ $totalPackages }} packages &middot; {{ $activeVisas }} visas
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Analytics Grid -->
    <div class="analytics-grid">
        <!-- Main Content Area -->
        <div class="main-content space-y-6">
            <!-- Revenue Chart -->
            <div class="metric-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Trends</h3>
                    <div class="flex gap-2">
                        <button @click="switchRevenueChart('line')" :class="revenueChartType === 'line' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'" class="px-3 py-1 rounded text-sm">Line</button>
                        <button @click="switchRevenueChart('bar')" :class="revenueChartType === 'bar' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'" class="px-3 py-1 rounded text-sm">Bar</button>
                    </div>
                </div>
                <div class="chart-container-large">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Bookings vs Visitors Chart -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bookings vs Visitors</h3>
                <div class="chart-container-large">
                    <canvas id="bookingsVisitorsChart"></canvas>
                </div>
            </div>

            <!-- Service Revenue Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="metric-card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Service</h3>
                    <div class="chart-container">
                        <canvas id="serviceRevenueChart"></canvas>
                    </div>
                </div>

                <div class="metric-card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Status</h3>
                    <div class="chart-container">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Pages Table -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Pages</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Page</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Views</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($topPages as $page)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-medium text-gray-900">{{ $page['title'] ?? $page['path'] }}</div>
                                    <div class="text-gray-500 text-xs">{{ $page['path'] }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium">{{ number_format($page['views']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar-content space-y-6">
            <!-- Quick Stats -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Conversion Rate</span>
                            <span class="font-medium">{{ $performanceMetrics['conversionRate'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $performanceMetrics['conversionRate'] }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Avg Booking Value</span>
                            <span class="font-medium">৳{{ number_format($performanceMetrics['avgBookingValue']) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Revenue per Visitor</span>
                            <span class="font-medium">৳{{ number_format($performanceMetrics['revenuePerVisitor']) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Avg Session Duration</span>
                            <span class="font-medium">{{ gmdate('i:s', $visitorStats['avgSessionDuration']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geography -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Countries</h3>
                <div class="space-y-3">
                    @foreach($visitorGeography as $country)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $country['country_code'] }}</span>
                            <span class="text-sm text-gray-600">{{ $country['country'] }}</span>
                        </div>
                        <span class="text-sm font-medium">{{ number_format($country['visitors']) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookings</h3>
                <div class="space-y-3">
                    @foreach($recentBookings as $booking)
                    <div class="text-sm">
                        <div class="font-medium text-gray-900">{{ $booking->user?->name ?? $booking->guest_name ?? 'Guest' }}</div>
                        <div class="text-gray-500">{{ $booking->payable_type }} - ৳{{ number_format($booking->total_amount) }}</div>
                        <div class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Communication Stats -->
            <div class="metric-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Communication</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Contact Messages</span>
                        <span class="text-sm font-medium">{{ $contactStats['total'] }}</span>
                    </div>
                    @if($contactStats['unread'] > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Unread</span>
                        <span class="text-sm font-medium text-red-600">{{ $contactStats['unread'] }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Customization Requests</span>
                        <span class="text-sm font-medium">{{ $customizationStats['total'] }}</span>
                    </div>
                    @if($customizationStats['pending'] > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Pending</span>
                        <span class="text-sm font-medium text-orange-600">{{ $customizationStats['pending'] }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function analyticsDashboard() {
            return {
                period: '{{ $period }}',
                revenueChartType: 'line',
                charts: {},
                
                init() {
                    this.initCharts();
                },
                
                refreshData() {
                    window.location.href = `{{ route('admin.dashboard') }}?period=${this.period}`;
                },
                
                switchRevenueChart(type) {
                    this.revenueChartType = type;
                    this.updateRevenueChart(type);
                },
                
                initCharts() {
                    // Revenue Chart
                    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                    this.charts.revenue = new Chart(revenueCtx, {
                        type: 'line',
                        data: {
                            labels: @json(array_column($monthlyRevenue, 'month')),
                            datasets: [{
                                label: 'Revenue',
                                data: @json(array_column($monthlyRevenue, 'revenue')),
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '৳' + value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                    
                    // Bookings vs Visitors Chart
                    const bookingsVisitorsCtx = document.getElementById('bookingsVisitorsChart').getContext('2d');
                    this.charts.bookingsVisitors = new Chart(bookingsVisitorsCtx, {
                        type: 'line',
                        data: {
                            labels: @json(array_column($bookingTrends, 'date')),
                            datasets: [{
                                label: 'Bookings',
                                data: @json(array_column($bookingTrends, 'bookings')),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                yAxisID: 'y'
                            }, {
                                label: 'Visitors',
                                data: @json(array_column($visitorTrends, 'visitors')),
                                borderColor: 'rgb(168, 85, 247)',
                                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                                tension: 0.4,
                                yAxisID: 'y1'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                }
                            }
                        }
                    });
                    
                    // Service Revenue Chart
                    const serviceRevenueCtx = document.getElementById('serviceRevenueChart').getContext('2d');
                    this.charts.serviceRevenue = new Chart(serviceRevenueCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_column($revenueByService, 'service_type')),
                            datasets: [{
                                data: @json(array_column($revenueByService, 'revenue')),
                                backgroundColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(34, 197, 94)',
                                    'rgb(251, 146, 60)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                    
                    // Booking Status Chart
                    const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
                    this.charts.bookingStatus = new Chart(bookingStatusCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                            datasets: [{
                                data: [{{ $bookingStats['pending'] }}, {{ $bookingStats['confirmed'] }}, {{ $bookingStats['completed'] }}, {{ $bookingStats['cancelled'] }}],
                                backgroundColor: [
                                    'rgb(251, 191, 36)',
                                    'rgb(34, 197, 94)',
                                    'rgb(59, 130, 246)',
                                    'rgb(239, 68, 68)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                },
                
                updateRevenueChart(type) {
                    this.charts.revenue.config.type = type;
                    this.charts.revenue.update();
                }
            }
        }
    </script>
@endpush
</x-admin-layout>
