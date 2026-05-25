<x-admin-layout pageTitle="Analytics Dashboard">

@push('styles')
    <style>
        .analytics-card {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.07);
            border: 1px solid #f3f4f6;
            padding: 1.5rem;
        }
        .metric-card {
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #bfdbfe;
        }
        .metric-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
        }
        .metric-label {
            font-size: 0.875rem;
            color: #4b5563;
            margin-top: 0.25rem;
        }
        .metric-change {
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }
        .metric-change.positive { color: #16a34a; }
        .metric-change.negative { color: #dc2626; }
        .chart-container {
            height: 16rem;
            width: 100%;
        }
        .realtime-indicator {
            display: inline-block;
            width: 0.5rem;
            height: 0.5rem;
            background: #22c55e;
            border-radius: 9999px;
            margin-right: 0.5rem;
            animation: pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        .table-hover tbody tr:hover { background: #f9fafb; }
    </style>
@endpush

<div class="min-h-screen bg-gray-50" x-data="analyticsDashboard()">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>
                    <p class="text-gray-600 mt-1">Monitor your website traffic and visitor behavior</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="realtime-indicator"></span>
                    <span class="text-sm text-gray-600">Live Data</span>
                    <select x-model="period" @change="refreshData()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="1d">Last 24 Hours</option>
                        <option value="7d">Last 7 Days</option>
                        <option value="30d">Last 30 Days</option>
                        <option value="90d">Last 90 Days</option>
                        <option value="1y">Last Year</option>
                    </select>
                    <a href="{{ route('admin.analytics.export') }}?period={{ $period }}" class="btn-secondary text-sm">
                        Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Overview Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="metric-value">{{ number_format($metrics['total_visitors']) }}</div>
                        <div class="metric-label">Total Visitors</div>
                        <div class="metric-change {{ $metrics['visitors_growth'] >= 0 ? 'positive' : 'negative' }}">
                            @if($metrics['visitors_growth'] >= 0)↑@else↓@endif {{ abs($metrics['visitors_growth']) }}% from previous period
                        </div>
                    </div>
                    <div class="text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="metric-value">{{ number_format($metrics['total_page_views']) }}</div>
                        <div class="metric-label">Page Views</div>
                        <div class="metric-change {{ $metrics['page_views_growth'] >= 0 ? 'positive' : 'negative' }}">
                            @if($metrics['page_views_growth'] >= 0)↑@else↓@endif {{ abs($metrics['page_views_growth']) }}% from previous period
                        </div>
                    </div>
                    <div class="text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="metric-value">{{ number_format($metrics['total_sessions']) }}</div>
                        <div class="metric-label">Sessions</div>
                        <div class="metric-change {{ $metrics['sessions_growth'] >= 0 ? 'positive' : 'negative' }}">
                            @if($metrics['sessions_growth'] >= 0)↑@else↓@endif {{ abs($metrics['sessions_growth']) }}% from previous period
                        </div>
                    </div>
                    <div class="text-purple-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="metric-value">{{ $metrics['bounce_rate'] }}%</div>
                        <div class="metric-label">Bounce Rate</div>
                        <div class="metric-change">
                            {{ gmdate('i:s', $metrics['avg_session_duration']) }} avg session duration
                        </div>
                    </div>
                    <div class="text-orange-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Visitors Trend -->
            <div class="analytics-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Visitors Trend</h3>
                <div class="chart-container">
                    <canvas id="visitorsChart"></canvas>
                </div>
            </div>

            <!-- Device Types -->
            <div class="analytics-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Device Types</h3>
                <div class="chart-container">
                    <canvas id="devicesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Pages & Countries -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Pages -->
            <div class="analytics-card">
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
                            @foreach($pagesData['top_pages'] as $page)
                            <tr class="table-hover">
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-medium text-gray-900">{{ $page->title ?? $page->path }}</div>
                                    <div class="text-gray-500 text-xs">{{ $page->path }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium">{{ number_format($page->views) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Countries -->
            <div class="analytics-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Countries</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Visitors</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($visitorsData['top_countries'] as $country)
                            <tr class="table-hover">
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center">
                                        <span class="text-lg mr-2">{{ $country->country_code }}</span>
                                        <span class="font-medium text-gray-900">{{ $country->country }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium">{{ number_format($country->count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Real-time Activity -->
        <div class="analytics-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Real-time Activity</h3>
                <div class="flex items-center text-sm text-gray-600">
                    <span class="realtime-indicator"></span>
                    {{ $realtimeData['active_visitors'] }} active visitors
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Visitor</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current Page</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Session Duration</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Last Activity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($realtimeData['current_sessions'] as $session)
                        <tr class="table-hover">
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $session['browser'] }}</div>
                                        <div class="text-gray-500 text-xs">{{ $session['device_type'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="text-gray-900">{{ $session['location'] }}</div>
                                <div class="text-gray-500 text-xs">Session #{{ $session['id'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="text-gray-900 font-medium">{{ $session['ip_address'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="text-gray-900">{{ $session['page_title'] }}</div>
                                <div class="text-gray-500 text-xs">{{ $session['page_path'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ gmdate('i:s', $session['duration']) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $session['last_activity_at']->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                
                init() {
                    this.initCharts();
                    // Auto-refresh real-time data every 30 seconds
                    setInterval(() => {
                        this.refreshRealtimeData();
                    }, 30000);
                },
                
                refreshData() {
                    window.location.href = `{{ route('admin.analytics.index') }}?period=${this.period}`;
                },
                
                initCharts() {
                    // Visitors Trend Chart
                    const visitorsCtx = document.getElementById('visitorsChart').getContext('2d');
                    new Chart(visitorsCtx, {
                        type: 'line',
                        data: {
                            labels: @json($visitorsData['daily_trend']->pluck('date')),
                            datasets: [{
                                label: 'Daily Visitors',
                                data: @json($visitorsData['daily_trend']->pluck('count')),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    
                    // Device Types Chart
                    const devicesCtx = document.getElementById('devicesChart').getContext('2d');
                    new Chart(devicesCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($deviceData['device_types']->pluck('device_type')),
                            datasets: [{
                                data: @json($deviceData['device_types']->pluck('count')),
                                backgroundColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(251, 146, 60)',
                                    'rgb(244, 63, 94)'
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
                
                refreshRealtimeData() {
                    // This would typically make an AJAX call to refresh real-time data
                    // For now, we'll just reload the page
                    location.reload();
                }
            }
        }
    </script>
@endpush

</x-admin-layout>
