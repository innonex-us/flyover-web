<x-admin-layout pageTitle="Reports">

@php
    $reportTypes = [
        'sales'       => ['label' => 'All Sales',        'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 2v2m0 4v1m-4 2a9 9 0 110-18 9 9 0 010 18z'],
        'tours-visas' => ['label' => 'Tours & Visas',    'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
        'hotels'      => ['label' => 'Hotels',           'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        'transfers'   => ['label' => 'Transfers',        'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
    ];
@endphp

{{-- ── Filter Bar ──────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.reports.index') }}" id="filter-form">
    <div class="flex flex-col lg:flex-row gap-4 mb-6 items-start lg:items-end">

        {{-- Report type --}}
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Report Type</label>
            <div class="inline-flex gap-1 bg-gray-100 p-1 rounded-xl">
                @foreach($reportTypes as $val => $rt)
                <button type="button" onclick="setType('{{ $val }}')"
                        class="report-type-btn flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold transition {{ $type === $val ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                        data-type="{{ $val }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $rt['icon'] }}"/></svg>
                    {{ $rt['label'] }}
                </button>
                @endforeach
            </div>
            <input type="hidden" name="type" id="type-input" value="{{ $type }}">
            <input type="hidden" name="period" id="period-input" value="{{ $period }}">
        </div>

        {{-- Period shortcuts --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Quick Period</label>
            <div class="inline-flex gap-1 bg-gray-100 p-1 rounded-xl">
                @foreach(['7d'=>'7 Days','30d'=>'30 Days','90d'=>'90 Days','1y'=>'1 Year','all'=>'All Time'] as $val=>$label)
                <a href="{{ route('admin.reports.index', ['type'=>$type,'period'=>$val]) }}"
                   class="px-3 py-2 rounded-lg text-sm font-semibold transition {{ $period===$val ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Custom date range --}}
        <div class="flex gap-2 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="border-gray-200 rounded-lg text-sm py-2 px-3 focus:border-red-500 focus:ring-red-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="border-gray-200 rounded-lg text-sm py-2 px-3 focus:border-red-500 focus:ring-red-200">
            </div>
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                Apply
            </button>
        </div>

    </div>
</form>

{{-- ── Summary Cards ────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Revenue</p>
        <p class="text-2xl font-extrabold text-gray-900">৳{{ number_format($summary['total_revenue']) }}</p>
        <p class="text-xs text-gray-400 mt-1">All services combined</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tour/Visa Bookings</p>
        <p class="text-2xl font-extrabold text-gray-900">{{ $summary['total_bookings'] }}</p>
        <p class="text-xs text-gray-400 mt-1">৳{{ number_format($summary['bookings_revenue']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Hotel Bookings</p>
        <p class="text-2xl font-extrabold text-gray-900">{{ $summary['hotel_bookings'] }}</p>
        <p class="text-xs text-gray-400 mt-1">৳{{ number_format($summary['hotel_revenue']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Transfers</p>
        <p class="text-2xl font-extrabold text-gray-900">{{ $summary['transfer_bookings'] }}</p>
        <p class="text-xs text-gray-400 mt-1">৳{{ number_format($summary['transfer_revenue']) }}</p>
    </div>
</div>

{{-- ── Action Buttons ───────────────────────────────── --}}
<div class="flex flex-wrap gap-3 mb-6">
    @php $exportParams = array_filter(['type'=>$type,'period'=>$period,'date_from'=>request('date_from'),'date_to'=>request('date_to')]); @endphp
    <a href="{{ route('admin.reports.pdf', $exportParams) }}" target="_blank"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Download PDF
    </a>
    <a href="{{ route('admin.reports.print', $exportParams) }}" target="_blank"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print Report
    </a>
    <span class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-gray-400 bg-gray-50 rounded-xl border border-gray-100">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}
    </span>
</div>

{{-- ── Preview Table ────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" id="preview-section">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-900">Preview</h3>
            <p class="text-xs text-gray-400 mt-0.5">Showing latest 20 records · full data exports to PDF</p>
        </div>
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
            @php
                $typeLabels = ['sales'=>'All Sales','tours-visas'=>'Tours & Visas','hotels'=>'Hotels','transfers'=>'Transfers'];
            @endphp
            {{ $typeLabels[$type] ?? $type }}
        </span>
    </div>

    @php
        $previewRows = match($type) {
            'tours-visas' => \App\Models\Booking::with(['user','payable'])->whereBetween('created_at',[$start,$end])->latest()->limit(20)->get(),
            'hotels'      => \App\Models\HotelBooking::with(['room.hotel','user'])->whereBetween('created_at',[$start,$end])->latest()->limit(20)->get(),
            'transfers'   => \App\Models\TransferBooking::with(['route','user'])->whereBetween('created_at',[$start,$end])->latest()->limit(20)->get(),
            default       => \App\Models\Booking::with(['user','payable'])->whereBetween('created_at',[$start,$end])->latest()->limit(20)->get(),
        };
    @endphp

    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">ID</th>
                    <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Service</th>
                    <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Guest</th>
                    <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Date</th>
                    <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                    <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($previewRows as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3.5 text-sm font-semibold text-gray-500">#{{ $row->id }}</td>
                    <td class="px-5 py-3.5 text-sm text-gray-800 max-w-[200px] truncate">
                        @if($type === 'tours-visas')
                            {{ $row->payable?->title ?? $row->payable?->country ?? '-' }}
                            <span class="text-[10px] text-gray-400 ml-1">{{ class_basename($row->payable_type ?? '') }}</span>
                        @elseif($type === 'hotels')
                            {{ $row->room?->hotel?->name ?? '-' }}<br>
                            <span class="text-xs text-gray-400">{{ $row->room?->name ?? '' }}</span>
                        @elseif($type === 'transfers')
                            {{ Str::limit($row->pickup_location . ' → ' . $row->drop_location, 50) }}
                        @else
                            {{ $row->payable?->title ?? $row->payable?->country ?? '-' }}
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-sm text-gray-800">{{ $row->user?->name ?? $row->guest_name ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $row->user?->email ?? $row->guest_email ?? '' }}</div>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-600">
                        @if($type === 'hotels')
                            {{ $row->check_in?->format('d M Y') }}
                        @elseif($type === 'transfers')
                            {{ $row->travel_date?->format('d M Y') }}
                        @else
                            {{ ($row->booking_date ?? $row->created_at)?->format('d M Y') }}
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @php
                            $sc = match($row->status) {
                                'confirmed' => 'bg-green-100 text-green-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default     => 'bg-yellow-100 text-yellow-700',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ ucfirst($row->status) }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-right text-sm font-bold text-gray-900">
                        @if(isset($row->total_amount) && $row->total_amount > 0)
                            ৳{{ number_format($row->total_amount) }}
                        @else
                            <span class="text-gray-400">TBD</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">No records in selected period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function setType(val) {
    document.getElementById('type-input').value = val;
    document.querySelectorAll('.report-type-btn').forEach(btn => {
        btn.classList.toggle('bg-white', btn.dataset.type === val);
        btn.classList.toggle('shadow-sm', btn.dataset.type === val);
        btn.classList.toggle('text-gray-900', btn.dataset.type === val);
        btn.classList.toggle('text-gray-500', btn.dataset.type !== val);
    });
    document.getElementById('filter-form').submit();
}
</script>

</x-admin-layout>
