<x-admin-layout pageTitle="Reports">

@push('styles')
    <style>
        .report-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid rgb(243 244 246);
            padding: 1.5rem;
        }
        .report-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .chart-container {
            height: 20rem;
            width: 100%;
        }
        .table-container {
            overflow-x: auto;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container th,
        .table-container td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid rgb(229 231 235);
        }
        .table-container th {
            background-color: rgb(249 250 251);
            font-weight: 600;
            color: rgb(55 65 81);
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-success {
            background-color: rgb(34 197 94);
            color: white;
        }
        .badge-warning {
            background-color: rgb(251 146 60);
            color: white;
        }
        .badge-danger {
            background-color: rgb(239 68 68);
            color: white;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background-color: rgb(59 130 246);
            color: white;
        }
        .btn-primary:hover {
            background-color: rgb(37 99 235);
        }
        .btn-secondary {
            background-color: rgb(107 114 128);
            color: white;
        }
        .btn-secondary:hover {
            background-color: rgb(75 85 99);
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .report-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            .table-container {
                margin: 0 -1rem;
                padding: 0 1rem;
            }
        }
    </style>
@endpush

<div x-data="reportsDashboard()" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Business Reports</h1>
            <p class="text-gray-600 mt-1">Comprehensive sales and visitor analytics</p>
        </div>
        <div class="flex items-center gap-3">
            <select x-model="dateRange" @change="refreshReports()" class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white text-sm">
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">Last Year</option>
            </select>
            <button @click="exportReports()" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </button>
            <button @click="refreshReports()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="report-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900">৳<span x-text="formatNumber(salesReport.totalRevenue)"></span></div>
                    <div class="text-sm text-gray-600">Total Revenue</div>
                    <div class="text-xs mt-1" :class="salesReport.growth > 0 ? 'text-green-600' : 'text-red-600'" x-text="(salesReport.growth > 0 ? '+' : '') + salesReport.growth + '%'"></div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="report-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900" x-text="salesReport.totalBookings"></div>
                    <div class="text-sm text-gray-600">Total Bookings</div>
                    <div class="text-xs mt-1 text-blue-600" x-text="salesReport.avgPerDay + ' per day'"></div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="report-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900" x-text="formatNumber(visitorReport.totalVisitors)"></div>
                    <div class="text-sm text-gray-600">Total Visitors</div>
                    <div class="text-xs mt-1 text-purple-600" x-text="visitorReport.newVisitors + ' new'"></div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="report-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900" x-text="salesReport.conversionRate + '%'"></div>
                    <div class="text-sm text-gray-600">Conversion Rate</div>
                    <div class="text-xs mt-1 text-orange-600" x-text="salesReport.avgBookingValue + ' avg value'"></div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Report Section -->
    <div class="report-card">
        <div class="report-header">
            <h2 class="text-xl font-semibold text-gray-900">Sales Report</h2>
            <div class="flex gap-2">
                <button @click="salesView = 'chart'" :class="salesView === 'chart' ? 'btn btn-primary' : 'btn btn-secondary'" class="text-sm">Chart View</button>
                <button @click="salesView = 'table'" :class="salesView === 'table' ? 'btn btn-primary' : 'btn btn-secondary'" class="text-sm">Table View</button>
            </div>
        </div>

        <!-- Chart View -->
        <div x-show="salesView === 'chart'" class="chart-container">
            <canvas x-ref="salesChart"></canvas>
        </div>

        <!-- Table View -->
        <div x-show="salesView === 'table'" class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Avg Value</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="sale in salesReport.dailySales" :key="sale.date">
                        <tr>
                            <td x-text="sale.date"></td>
                            <td x-text="sale.bookings"></td>
                            <td>৳<span x-text="formatNumber(sale.revenue)"></span></td>
                            <td>৳<span x-text="formatNumber(sale.avgValue)"></span></td>
                            <td>
                                <span class="badge" :class="{
                                    'badge-success': sale.status === 'Good',
                                    'badge-warning': sale.status === 'Average',
                                    'badge-danger': sale.status === 'Poor'
                                }" x-text="sale.status"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Visitor Report Section -->
    <div class="report-card">
        <div class="report-header">
            <h2 class="text-xl font-semibold text-gray-900">Visitor Report</h2>
            <div class="flex gap-2">
                <button @click="visitorView = 'chart'" :class="visitorView === 'chart' ? 'btn btn-primary' : 'btn btn-secondary'" class="text-sm">Chart View</button>
                <button @click="visitorView = 'table'" :class="visitorView === 'table' ? 'btn btn-primary' : 'btn btn-secondary'" class="text-sm">Table View</button>
            </div>
        </div>

        <!-- Chart View -->
        <div x-show="visitorView === 'chart'" class="chart-container">
            <canvas x-ref="visitorChart"></canvas>
        </div>

        <!-- Table View -->
        <div x-show="visitorView === 'table'" class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Visitors</th>
                        <th>New</th>
                        <th>Returning</th>
                        <th>Bounce Rate</th>
                        <th>Avg Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="visitor in visitorReport.dailyVisitors" :key="visitor.date">
                        <tr>
                            <td x-text="visitor.date"></td>
                            <td x-text="visitor.total"></td>
                            <td x-text="visitor.new"></td>
                            <td x-text="visitor.returning"></td>
                            <td x-text="visitor.bounceRate + '%'"></td>
                            <td x-text="visitor.avgDuration"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Service Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="report-card">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Service Performance</h2>
            <div class="chart-container">
                <canvas x-ref="serviceChart"></canvas>
            </div>
        </div>

        <div class="report-card">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Revenue by Service</h2>
            <div class="chart-container">
                <canvas x-ref="revenueByServiceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="report-card">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Top Performing Services</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Growth</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="service in topPerformers" :key="service.name">
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium" x-text="service.name"></div>
                                        <div class="text-xs text-gray-500" x-text="service.category"></div>
                                    </div>
                                </div>
                            </td>
                            <td x-text="service.bookings"></td>
                            <td>৳<span x-text="formatNumber(service.revenue)"></span></td>
                            <td>
                                <span class="text-sm font-medium" :class="service.growth > 0 ? 'text-green-600' : 'text-red-600'" x-text="(service.growth > 0 ? '+' : '') + service.growth + '%'"></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <span x-text="service.rating"></span>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reportsDashboard() {
    return {
        dateRange: '30',
        salesView: 'chart',
        visitorView: 'chart',
        salesReport: {
            totalRevenue: 2847650,
            totalBookings: 156,
            growth: 12.3,
            avgPerDay: 5.2,
            conversionRate: 5.5,
            avgBookingValue: 18245,
            dailySales: []
        },
        visitorReport: {
            totalVisitors: 2847,
            newVisitors: 1847,
            dailyVisitors: []
        },
        topPerformers: [],
        
        init() {
            this.refreshReports();
            this.initCharts();
        },
        
        async refreshReports() {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            this.salesReport.dailySales = [
                { date: '2024-01-01', bookings: 12, revenue: 245000, avgValue: 20417, status: 'Good' },
                { date: '2024-01-02', bookings: 8, revenue: 156000, avgValue: 19500, status: 'Average' },
                { date: '2024-01-03', bookings: 15, revenue: 289000, avgValue: 19267, status: 'Good' },
                { date: '2024-01-04', bookings: 6, revenue: 98000, avgValue: 16333, status: 'Poor' },
                { date: '2024-01-05', bookings: 11, revenue: 234000, avgValue: 21273, status: 'Good' }
            ];
            
            this.visitorReport.dailyVisitors = [
                { date: '2024-01-01', total: 245, new: 180, returning: 65, bounceRate: 42.1, avgDuration: '03:24' },
                { date: '2024-01-02', total: 189, new: 142, returning: 47, bounceRate: 38.7, avgDuration: '04:12' },
                { date: '2024-01-03', total: 312, new: 234, returning: 78, bounceRate: 35.2, avgDuration: '05:45' },
                { date: '2024-01-04', total: 156, new: 98, returning: 58, bounceRate: 45.8, avgDuration: '02:18' },
                { date: '2024-01-05', total: 278, new: 201, returning: 77, bounceRate: 40.3, avgDuration: '03:56' }
            ];
            
            this.topPerformers = [
                { name: 'Cox\'s Bazar Tour', category: 'Tour Package', bookings: 45, revenue: 890000, growth: 15.2, rating: 4.8 },
                { name: 'Dubai Visa', category: 'Visa Service', bookings: 38, revenue: 456000, growth: 8.7, rating: 4.6 },
                { name: 'Airport Transfer', category: 'Transfer Service', bookings: 32, revenue: 224000, growth: -2.3, rating: 4.5 },
                { name: 'Thailand Package', category: 'Tour Package', bookings: 28, revenue: 672000, growth: 22.1, rating: 4.9 },
                { name: 'Malaysia Visa', category: 'Visa Service', bookings: 25, revenue: 325000, growth: 5.4, rating: 4.4 }
            ];
            
            this.updateCharts();
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat('en-IN').format(num);
        },
        
        initCharts() {
            this.$nextTick(() => {
                this.initSalesChart();
                this.initVisitorChart();
                this.initServiceChart();
                this.initRevenueByServiceChart();
            });
        },
        
        initSalesChart() {
            const ctx = this.$refs.salesChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.salesReport.dailySales.map(s => s.date),
                    datasets: [{
                        label: 'Revenue',
                        data: this.salesReport.dailySales.map(s => s.revenue),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
        },
        
        initVisitorChart() {
            const ctx = this.$refs.visitorChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: this.visitorReport.dailyVisitors.map(v => v.date),
                    datasets: [
                        {
                            label: 'New Visitors',
                            data: this.visitorReport.dailyVisitors.map(v => v.new),
                            backgroundColor: 'rgba(59, 130, 246, 0.8)'
                        },
                        {
                            label: 'Returning Visitors',
                            data: this.visitorReport.dailyVisitors.map(v => v.returning),
                            backgroundColor: 'rgba(16, 185, 129, 0.8)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        
        initServiceChart() {
            const ctx = this.$refs.serviceChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: this.topPerformers.map(s => s.name),
                    datasets: [{
                        data: this.topPerformers.map(s => s.bookings),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(147, 51, 234, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        initRevenueByServiceChart() {
            const ctx = this.$refs.revenueByServiceChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: this.topPerformers.map(s => s.name),
                    datasets: [{
                        data: this.topPerformers.map(s => s.revenue),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(147, 51, 234, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        updateCharts() {
            this.initCharts();
        },
        
        exportReports() {
            // Simulate PDF export
            alert('PDF export functionality would be implemented here');
        }
    }
}
</script>
@endpush
</x-admin-layout>
