<x-admin-layout pageTitle="Business Reports">

@push('styles')
    <style>
        .report-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 10px 15px -5px rgba(0,0,0,.02);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        .report-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
        }
        .filter-pill {
            transition: all 0.2s ease;
        }
        .filter-pill.active {
            background: #18130E;
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(24, 19, 14, 0.2);
        }
        .status-badge {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
        }
        .btn-brand {
            background: #C8102E;
            color: #fff;
        }
        .btn-brand:hover {
            background: #a50d26;
        }
        .btn-dark {
            background: #18130E;
            color: #fff;
        }
        .btn-dark:hover {
            background: #2E2720;
        }
    </style>
@endpush

@php
    $reportTypes = [
        'sales'       => ['label' => 'Revenue Overview', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 2v2m0 4v1m-4 2a9 9 0 110-18 9 9 0 010 18z'],
        'tours-visas' => ['label' => 'Tours & Visas',    'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
        'hotels'      => ['label' => 'Accommodations',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        'transfers'   => ['label' => 'Logistics',        'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
    ];
@endphp

<div class="space-y-8">
    {{-- Header & Filters --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Business Reports</h1>
            <p class="text-gray-500 mt-1">Analyze your performance and export financial data.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @php $exportParams = array_filter(['type'=>$type,'period'=>$period,'date_from'=>request('date_from'),'date_to'=>request('date_to')]); @endphp
            <a href="{{ route('admin.reports.pdf', $exportParams) }}" target="_blank"
               class="btn-brand px-5 py-2.5 text-sm font-semibold rounded-xl transition shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export PDF
            </a>
            <a href="{{ route('admin.reports.print', $exportParams) }}" target="_blank"
               class="btn-dark px-5 py-2.5 text-sm font-semibold rounded-xl transition shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reports.index') }}" id="filter-form" class="report-card p-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
            {{-- Report Type --}}
            <div class="lg:col-span-5">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Report Category</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($reportTypes as $val => $rt)
                        <button type="button" onclick="setType('{{ $val }}')"
                                class="filter-pill flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold border border-gray-100 {{ $type === $val ? 'active' : 'bg-gray-50 text-gray-500 hover:bg-gray-100' }}"
                                data-type="{{ $val }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $rt['icon'] }}"/></svg>
                            {{ $rt['label'] }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="type" id="type-input" value="{{ $type }}">
                <input type="hidden" name="period" id="period-input" value="{{ $period }}">
            </div>

            {{-- Period --}}
            <div class="lg:col-span-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Timeframe</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['7d'=>'7D','30d'=>'30D','90d'=>'90D','1y'=>'1Y','all'=>'ALL'] as $val=>$label)
                        <a href="{{ route('admin.reports.index', ['type'=>$type,'period'=>$val]) }}"
                           class="filter-pill px-4 py-2.5 rounded-xl text-xs font-bold border border-gray-100 {{ $period===$val ? 'active' : 'bg-gray-50 text-gray-500 hover:bg-gray-100' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Custom Dates --}}
            <div class="lg:col-span-3">
                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Custom Range</label>
                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-xl p-1">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="bg-transparent border-none text-[11px] font-semibold focus:ring-0 p-1 w-full">
                            <span class="text-gray-300">/</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="bg-transparent border-none text-[11px] font-semibold focus:ring-0 p-1 w-full">
                        </div>
                    </div>
                    <button type="submit" class="btn-dark p-3 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="report-card p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 2v2m0 4v1m-4 2a9 9 0 110-18 9 9 0 010 18z"/></svg>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Revenue</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">৳{{ number_format($summary['total_revenue']) }}</h3>
            </div>
            <p class="text-[10px] text-gray-400 mt-4 pt-4 border-t border-gray-50 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> Consolidated earnings
            </p>
        </div>

        <div class="report-card p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tours & Visas</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($summary['total_bookings']) }}</h3>
            </div>
            <p class="text-[10px] text-gray-400 mt-4 pt-4 border-t border-gray-50 font-bold">
                ৳{{ number_format($summary['bookings_revenue']) }} <span class="font-normal">revenue</span>
            </p>
        </div>

        <div class="report-card p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hotel Bookings</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($summary['hotel_bookings']) }}</h3>
            </div>
            <p class="text-[10px] text-gray-400 mt-4 pt-4 border-t border-gray-50 font-bold">
                ৳{{ number_format($summary['hotel_revenue']) }} <span class="font-normal">revenue</span>
            </p>
        </div>

        <div class="report-card p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Logistics/Transfers</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($summary['transfer_bookings']) }}</h3>
            </div>
            <p class="text-[10px] text-gray-400 mt-4 pt-4 border-t border-gray-50 font-bold">
                ৳{{ number_format($summary['transfer_revenue']) }} <span class="font-normal">revenue</span>
            </p>
        </div>
    </div>

    {{-- Preview Table --}}
    <div class="report-card overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-900">Activity Preview</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Displaying latest activity for the selected criteria.</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Period Range</span>
                <span class="text-xs font-bold text-gray-700">{{ $start->format('M d, Y') }} — {{ $end->format('M d, Y') }}</span>
            </div>
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
                <thead>
                    <tr class="bg-gray-50/30 border-b border-gray-50">
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Reference</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Service/Entity</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Client Info</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Schedule</th>
                        <th class="px-8 py-4 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($previewRows as $row)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded">#{{ $row->id }}</span>
                        </td>
                        <td class="px-8 py-5">
                            @if($type === 'tours-visas')
                                <div class="text-sm font-bold text-gray-800">{{ $row->payable?->title ?? $row->payable?->country ?? 'N/A' }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ class_basename($row->payable_type ?? '') }}</div>
                            @elseif($type === 'hotels')
                                <div class="text-sm font-bold text-gray-800">{{ $row->room?->hotel?->name ?? 'N/A' }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $row->room?->name ?? '' }}</div>
                            @elseif($type === 'transfers')
                                <div class="text-sm font-bold text-gray-800">{{ Str::limit($row->pickup_location . ' → ' . $row->drop_location, 40) }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Transfer Service</div>
                            @else
                                <div class="text-sm font-bold text-gray-800">{{ $row->payable?->title ?? $row->payable?->country ?? 'N/A' }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ class_basename($row->payable_type ?? '') }}</div>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-semibold text-gray-900">{{ $row->user?->name ?? $row->guest_name ?? 'Guest' }}</div>
                            <div class="text-[11px] text-gray-500">{{ $row->user?->email ?? $row->guest_email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-medium text-gray-700">
                                @if($type === 'hotels')
                                    {{ $row->check_in?->format('d M Y') }}
                                @elseif($type === 'transfers')
                                    {{ $row->travel_date?->format('d M Y') }}
                                @else
                                    {{ ($row->booking_date ?? $row->created_at)?->format('d M Y') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @php
                                $sc = match($row->status) {
                                    'confirmed' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                    'completed' => 'bg-sky-50 text-sky-700 border border-sky-100',
                                    'cancelled' => 'bg-rose-50 text-rose-700 border border-rose-100',
                                    default     => 'bg-amber-50 text-amber-700 border border-amber-100',
                                };
                            @endphp
                            <span class="status-badge {{ $sc }}">{{ $row->status }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="text-sm font-black text-gray-900">
                                @if(isset($row->total_amount) && $row->total_amount > 0)
                                    ৳{{ number_format($row->total_amount) }}
                                @else
                                    <span class="text-gray-300">TBD</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-900">No records found</p>
                                <p class="text-xs text-gray-500 mt-1">Adjust your filters or timeframe to see more data.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function setType(val) {
    document.getElementById('type-input').value = val;
    document.getElementById('filter-form').submit();
}
</script>

</x-admin-layout>
