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
            <h1 class="text-2xl font-bold text-gray-900">Advanced Analytics</h1>
            <p class="text-gray-600 mt-1">Deep dive into your travel business metrics</p>
        </div>
        <div class="flex items-center gap-3">
            <select x-model="period" @change="refreshData()" class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white text-sm">
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">Last Year</option>
            </select>
            <button @click="refreshData()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Key Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value">৳<span x-text="formatNumber(metrics.totalRevenue)"></span></div>
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-change positive" x-text="metrics.revenueGrowth > 0 ? '+' + metrics.revenueGrowth + '%' : metrics.revenueGrowth + '%'"></div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value" x-text="metrics.totalBookings"></div>
                    <div class="metric-label">Total Bookings</div>
                    <div class="metric-change" x-text="metrics.pendingBookings + ' pending · ' + metrics.confirmedBookings + ' confirmed'"></div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value" x-text="formatNumber(metrics.totalVisitors)"></div>
                    <div class="metric-label">Total Visitors</div>
                    <div class="metric-change" x-text="metrics.bounceRate + '% bounce rate'"></div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="metric-value" x-text="metrics.conversionRate + '%'"></div>
                    <div class="metric-label">Conversion Rate</div>
                    <div class="metric-change" x-text="metrics.conversionTrend > 0 ? '+' + metrics.conversionTrend + '%' : metrics.conversionTrend + '%'"></div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Grid -->
    <div class="analytics-grid">
        <!-- Main Content -->
        <div class="main-content space-y-6">
            <!-- Revenue Trends -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Trends</h3>
                    <div class="flex gap-2">
                        <button @click="revenueChartType = 'line'" :class="revenueChartType === 'line' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1 rounded-lg text-sm transition">Line</button>
                        <button @click="revenueChartType = 'bar'" :class="revenueChartType === 'bar' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1 rounded-lg text-sm transition">Bar</button>
                    </div>
                </div>
                <div class="chart-container-large">
                    <canvas x-ref="revenueChart"></canvas>
                </div>
            </div>

            <!-- Advanced Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Traffic Sources -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Traffic Sources</h3>
                    <div class="chart-container">
                        <canvas x-ref="trafficChart"></canvas>
                    </div>
                </div>

                <!-- Device Analytics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Device Analytics</h3>
                    <div class="chart-container">
                        <canvas x-ref="deviceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Geographic Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Geographic Distribution</h3>
                <div class="chart-container">
                    <canvas x-ref="geoChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="sidebar-content space-y-6">
            <!-- Performance Metrics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Conversion Rate</div>
                            <div class="text-xs text-gray-500">vs last period</div>
                        </div>
                        <div class="text-lg font-bold text-blue-600" x-text="metrics.conversionRate + '%'"></div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Avg Booking Value</div>
                            <div class="text-xs text-gray-500">per transaction</div>
                        </div>
                        <div class="text-lg font-bold text-green-600">৳<span x-text="formatNumber(metrics.avgBookingValue)"></span></div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Revenue per Visitor</div>
                            <div class="text-xs text-gray-500">per unique visitor</div>
                        </div>
                        <div class="text-lg font-bold text-purple-600">৳<span x-text="formatNumber(metrics.revenuePerVisitor)"></span></div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Avg Session Duration</div>
                            <div class="text-xs text-gray-500">time on site</div>
                        </div>
                        <div class="text-lg font-bold text-orange-600" x-text="metrics.avgSessionDuration"></div>
                    </div>
                </div>
            </div>

            <!-- Top Countries -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Countries</h3>
                <div class="space-y-3">
                    <template x-for="country in topCountries" :key="country.name">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium" x-text="country.flag"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900" x-text="country.name"></div>
                                    <div class="text-xs text-gray-500" x-text="country.visitors + ' visitors'"></div>
                                </div>
                            </div>
                            <div class="text-sm font-medium text-gray-600" x-text="country.percentage + '%'"></div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <template x-for="activity in recentActivity" :key="activity.id">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-900" x-text="activity.description"></div>
                                <div class="text-xs text-gray-500" x-text="activity.time"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function analyticsDashboard() {
    return {
        period: '30',
        revenueChartType: 'line',
        metrics: {
            totalRevenue: 0,
            totalBookings: 0,
            totalVisitors: 0,
            conversionRate: 0,
            revenueGrowth: 0,
            pendingBookings: 0,
            confirmedBookings: 0,
            bounceRate: 0,
            conversionTrend: 0,
            avgBookingValue: 0,
            revenuePerVisitor: 0,
            avgSessionDuration: '00:00'
        },
        topCountries: [],
        recentActivity: [],
        
        init() {
            this.refreshData();
            this.initCharts();
        },
        
        async refreshData() {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            this.metrics = {
                totalRevenue: 2847650,
                totalBookings: 156,
                totalVisitors: 2847,
                conversionRate: 5.5,
                revenueGrowth: 12.3,
                pendingBookings: 23,
                confirmedBookings: 133,
                bounceRate: 42.1,
                conversionTrend: 2.1,
                avgBookingValue: 18245,
                revenuePerVisitor: 1000,
                avgSessionDuration: '03:24'
            };
            
            this.topCountries = [
                { name: 'Bangladesh', flag: '🇧🇩', visitors: 1247, percentage: 43.8 },
                { name: 'India', flag: '🇮🇳', visitors: 892, percentage: 31.3 },
                { name: 'United States', flag: '🇺🇸', visitors: 445, percentage: 15.6 },
                { name: 'United Kingdom', flag: '🇬🇧', visitors: 263, percentage: 9.3 }
            ];
            
            this.recentActivity = [
                { id: 1, description: 'New booking: Tour Package to Cox\'s Bazar', time: '2 minutes ago' },
                { id: 2, description: 'User registered from Dhaka', time: '5 minutes ago' },
                { id: 3, description: 'Payment received: Visa Processing', time: '12 minutes ago' },
                { id: 4, description: 'New inquiry about Dubai Tour', time: '18 minutes ago' }
            ];
            
            this.updateCharts();
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat('en-IN').format(num);
        },
        
        initCharts() {
            this.$nextTick(() => {
                this.initRevenueChart();
                this.initTrafficChart();
                this.initDeviceChart();
                this.initGeoChart();
            });
        },
        
        initRevenueChart() {
            const ctx = this.$refs.revenueChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: this.revenueChartType,
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Revenue',
                        data: [450000, 620000, 580000, 847650],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: this.revenueChartType === 'bar' ? 'rgba(59, 130, 246, 0.5)' : 'rgba(59, 130, 246, 0.1)',
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
        
        initTrafficChart() {
            const ctx = this.$refs.trafficChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Direct', 'Social Media', 'Search', 'Referral'],
                    datasets: [{
                        data: [35, 25, 30, 10],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(147, 51, 234, 0.8)'
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
        
        initDeviceChart() {
            const ctx = this.$refs.deviceChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Desktop', 'Mobile', 'Tablet'],
                    datasets: [{
                        data: [45, 40, 15],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(251, 146, 60, 0.8)'
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
        
        initGeoChart() {
            const ctx = this.$refs.geoChart?.getContext('2d');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Bangladesh', 'India', 'USA', 'UK', 'UAE'],
                    datasets: [{
                        label: 'Visitors',
                        data: [1247, 892, 445, 263, 156],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)'
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
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        
        updateCharts() {
            // Update chart data when period changes
            this.initCharts();
        }
    }
}
</script>
@endpush
</x-admin-layout>
