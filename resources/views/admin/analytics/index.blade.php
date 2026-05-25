<x-admin-layout pageTitle="Visitor Analytics">

@push('styles')
    <style>
        .analytics-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 10px 15px -5px rgba(0,0,0,.02);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        .metric-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #f3f4f6;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
            transition: all 0.3s ease;
        }
        .metric-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,.1);
            transform: translateY(-2px);
        }
        .metric-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.5px;
        }
        .metric-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        .growth-badge {
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 2px;
        }
        .growth-positive { background: #ecfdf5; color: #059669; }
        .growth-negative { background: #fef2f2; color: #dc2626; }
        
        .chart-container {
            height: 18rem;
            width: 100%;
        }
        .realtime-indicator {
            display: inline-block;
            width: 0.625rem;
            height: 0.625rem;
            background: #10b981;
            border-radius: 9999px;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .btn-brand {
            background: #dc2626;
            color: #fff;
        }
        .btn-brand:hover {
            background: #b91c1c;
        }
    </style>
@endpush

<div class="space-y-4 sm:space-y-8" x-data="analyticsDashboard()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="realtime-indicator"></span>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Live System</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Traffic Intel</h1>
            <p class="text-sm text-gray-500 mt-1">Deep insights into visitor behavior and platform performance.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <select x-model="period" @change="refreshData()" class="flex-1 sm:flex-none bg-white border-gray-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm font-semibold shadow-sm focus:ring-red-100 focus:border-red-400">
                <option value="1d">Last 24 Hours</option>
                <option value="7d">Last 7 Days</option>
                <option value="30d">Last 30 Days</option>
                <option value="90d">Last 90 Days</option>
                <option value="1y">Last Year</option>
            </select>
            <a href="{{ route('admin.analytics.export') }}?period={{ $period }}" class="flex-1 sm:flex-none btn-brand px-5 py-2.5 text-xs sm:text-sm font-semibold rounded-xl transition shadow-sm inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="hidden sm:inline">Export Dataset</span>
                <span class="sm:hidden">Export</span>
            </a>
        </div>
    </div>

    <!-- Overview Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="metric-card p-3 sm:p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest truncate">Total Reach</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ number_format($metrics['total_visitors']) }}</h3>
                    <span class="growth-badge {{ $metrics['visitors_growth'] >= 0 ? 'growth-positive' : 'growth-negative' }} scale-75 origin-left">
                        {{ $metrics['visitors_growth'] >= 0 ? '↑' : '↓' }} {{ abs($metrics['visitors_growth']) }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="metric-card p-3 sm:p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest truncate">Engagement</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ number_format($metrics['total_page_views']) }}</h3>
                    <span class="growth-badge {{ $metrics['page_views_growth'] >= 0 ? 'growth-positive' : 'growth-negative' }} scale-75 origin-left">
                        {{ $metrics['page_views_growth'] >= 0 ? '↑' : '↓' }} {{ abs($metrics['page_views_growth']) }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="metric-card p-3 sm:p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest truncate">Sessions</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ number_format($metrics['total_sessions']) }}</h3>
                    <span class="growth-badge {{ $metrics['sessions_growth'] >= 0 ? 'growth-positive' : 'growth-negative' }} scale-75 origin-left">
                        {{ $metrics['sessions_growth'] >= 0 ? '↑' : '↓' }} {{ abs($metrics['sessions_growth']) }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="metric-card p-3 sm:p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest truncate">Retention</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ gmdate('i:s', $metrics['avg_session_duration']) }}</h3>
                    <span class="text-[8px] text-gray-400 font-bold tracking-tighter">{{ $metrics['bounce_rate'] }}% bounce</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
        <div class="analytics-card p-4 sm:p-8">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px]">Visitor Volume Trend</h3>
                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded">Daily Samples</span>
            </div>
            <div class="chart-container h-64 sm:h-[18rem]">
                <canvas id="visitorsChart"></canvas>
            </div>
        </div>

        <div class="analytics-card p-4 sm:p-8">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px]">Platform Distribution</h3>
                <span class="text-[10px] font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded">Device Type</span>
            </div>
            <div class="chart-container h-64 sm:h-[18rem]">
                <canvas id="devicesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Pages & Countries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Pages -->
        <div class="analytics-card overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px]">Engagement by Path</h3>
                <span class="text-[10px] font-bold text-gray-400">Top 10 Performance</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/30 border-b border-gray-50">
                            <th class="px-8 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Resource Path</th>
                            <th class="px-8 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Impressions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pagesData['top_pages'] as $page)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-8 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $page->title ?? $page->path }}</div>
                                <div class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $page->path }}</div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-sm font-black text-gray-900">{{ number_format($page->views) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Countries -->
        <div class="analytics-card overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px]">Geographic Reach</h3>
                <span class="text-[10px] font-bold text-gray-400">By Origin</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/30 border-b border-gray-50">
                            <th class="px-8 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Territory</th>
                            <th class="px-8 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Visitors</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($visitorsData['top_countries'] as $country)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-black text-gray-300">{{ $country->country_code }}</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $country->country }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-sm font-black text-gray-900">{{ number_format($country->count) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Real-time Activity -->
    <div class="analytics-card overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-red-600">
            <div>
                <h3 class="font-bold text-white uppercase tracking-widest text-[10px]">Real-time Session Feed</h3>
                <p class="text-[10px] text-red-100 mt-0.5">Live activity stream from active endpoints.</p>
            </div>
            <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full border border-white/20">
                <span class="realtime-indicator bg-white shadow-none"></span>
                <span class="text-[10px] font-black text-white uppercase tracking-tighter">{{ $realtimeData['active_visitors'] }} PULSE NODES</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/30 border-b border-gray-50">
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Terminal Info</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Origin / Geo</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Active Path</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Uptime</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($realtimeData['current_sessions'] as $session)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $session['browser'] }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $session['device_type'] }} · {{ $session['ip_address'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-semibold text-gray-700">{{ $session['city'] }}, {{ $session['country'] }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $session['region'] }} ({{ $session['country_code'] }})</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-gray-800">{{ $session['page_title'] }}</div>
                            <a href="{{ $session['page_url'] }}" target="_blank" class="text-[10px] font-mono text-red-600 hover:underline mt-0.5 block truncate max-w-xs">{{ $session['page_path'] }}</a>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="text-sm font-black text-gray-900">{{ gmdate('i:s', $session['duration']) }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $session['last_activity_at']->diffForHumans() }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                                borderColor: '#dc2626',
                                backgroundColor: 'rgba(220, 38, 38, 0.05)',
                                borderWidth: 3,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#dc2626',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                tension: 0.4,
                                fill: true
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
                                    grid: { color: '#f3f4f6', drawBorder: false },
                                    ticks: { font: { size: 10, weight: 'bold' }, color: '#9ca3af' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 10, weight: 'bold' }, color: '#9ca3af' }
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
                                    '#dc2626',
                                    '#1f2937',
                                    '#9ca3af',
                                    '#f3f4f6'
                                ],
                                borderWeight: 0,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: { size: 11, weight: 'bold' },
                                        color: '#4b5563'
                                    }
                                }
                            }
                        }
                    });
                },
                
                refreshRealtimeData() {
                    location.reload();
                }
            }
        }
    </script>
@endpush

</x-admin-layout>
