<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $data['title'] }} - {{ $company['name'] }}</title>
<style>
    @media print {
        @page { size: A4 portrait; margin: 14mm 12mm 14mm 12mm; }
        .no-print { display: none !important; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 9pt; color: #18130E; background: #fff; max-width: 800px; margin: 0 auto; padding: 20px; }

    /* ── Print button ── */
    .print-bar { background: #18130E; color: #fff; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    .print-btn { background: #C8102E; color: #fff; border: none; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; }
    .close-btn { background: transparent; color: #aaa; border: 1px solid #444; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; margin-left: 8px; }

    /* ── Header ── */
    .header { padding: 0 0 16px; border-bottom: 2.5px solid #C8102E; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-start; }
    .logo { height: 44px; }
    .company-name { font-size: 18pt; font-weight: 800; color: #18130E; line-height: 1; }
    .company-tagline { font-size: 8.5pt; color: #7A7166; }
    .report-title { font-size: 14pt; font-weight: 700; color: #C8102E; text-align: right; }
    .report-period { font-size: 8.5pt; color: #7A7166; text-align: right; margin-top: 3px; }
    .report-generated { font-size: 7.5pt; color: #aaa; text-align: right; }

    /* ── Info bar ── */
    .info-bar { background: #F9F6EF; border: 1px solid #E4DCC9; border-radius: 6px; padding: 9px 14px; margin-bottom: 16px; font-size: 8pt; color: #7A7166; display: flex; flex-wrap: wrap; gap: 12px; }
    .info-bar strong { color: #18130E; }

    /* ── Summary ── */
    .summary-grid { display: flex; gap: 10px; margin-bottom: 18px; }
    .summary-card { flex: 1; border: 1px solid #E4DCC9; border-radius: 8px; padding: 10px 12px; background: #FAFAF9; }
    .s-label { font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #7A7166; }
    .s-value { font-size: 15pt; font-weight: 800; color: #18130E; line-height: 1.1; margin-top: 2px; }
    .s-sub { font-size: 7.5pt; color: #aaa; margin-top: 2px; }

    /* ── Table ── */
    .section-title { font-size: 10.5pt; font-weight: 700; color: #18130E; margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1px solid #E4DCC9; }
    table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
    thead tr { background: #18130E; }
    thead th { padding: 8px 10px; text-align: left; color: #fff; font-weight: 700; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .05em; }
    tbody tr:nth-child(even) { background: #F9F6EF; }
    tbody td { padding: 6px 10px; border-bottom: 1px solid #EDE9E0; vertical-align: top; }
    .amt { text-align: right; font-weight: 700; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 7pt; font-weight: 700; }
    .s-confirmed { background: #dcfce7; color: #166534; }
    .s-completed  { background: #dbeafe; color: #1e40af; }
    .s-pending    { background: #fef9c3; color: #854d0e; }
    .s-cancelled  { background: #fee2e2; color: #991b1b; }
    .totals-row td { background: #18130E !important; color: #fff !important; font-weight: 700; padding: 9px 10px; }
    .totals-amt { color: #fca5a5 !important; font-size: 11pt; text-align: right; }

    /* ── Signature ── */
    .footer-section { margin-top: 32px; border-top: 1px solid #E4DCC9; padding-top: 20px; }
    .sig-grid { display: flex; justify-content: space-between; gap: 24px; }
    .sig-box { flex: 1; }
    .sig-line { border-top: 1px solid #9CA3AF; margin-top: 44px; margin-bottom: 6px; }
    .sig-label { font-size: 8.5pt; color: #18130E; font-weight: 700; }
    .sig-sub { font-size: 7.5pt; color: #9ca3af; }

    /* ── Disclaimer ── */
    .disclaimer { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 5px; padding: 8px 12px; margin-top: 16px; font-size: 7.5pt; color: #92400e; }

    /* ── Page footer ── */
    .page-footer { margin-top: 20px; text-align: center; font-size: 7.5pt; color: #9ca3af; border-top: 1px solid #F3F0EA; padding-top: 10px; }
</style>
</head>
<body>

{{-- ── Print Control Bar ── --}}
<div class="print-bar no-print">
    <span style="font-size:14px; font-weight:600;">{{ $data['title'] }} &nbsp;·&nbsp; {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</span>
    <div>
        <button class="print-btn" onclick="window.print()">🖨 Print</button>
        <button class="close-btn" onclick="window.close()">✕ Close</button>
    </div>
</div>

{{-- ── HEADER ── --}}
<div class="header">
    <div style="display:flex; align-items:center; gap:14px;">
        <img src="{{ asset('logo.png') }}" class="logo" alt="{{ $company['name'] }}">
        <div>
            <div class="company-name">{{ $company['name'] }}</div>
            <div class="company-tagline">{{ $company['tagline'] }}</div>
        </div>
    </div>
    <div>
        <div class="report-title">{{ $data['title'] }}</div>
        <div class="report-period">{{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</div>
        <div class="report-generated">Generated {{ now()->format('d M Y, h:i A') }}</div>
    </div>
</div>

{{-- ── COMPANY INFO BAR ── --}}
<div class="info-bar">
    <span><strong>Address:</strong> {{ $company['address'] }}</span>
    <span><strong>Phone:</strong> {{ $company['phone'] }}</span>
    <span><strong>Email:</strong> {{ $company['email'] }}</span>
    <span><strong>Web:</strong> {{ $company['web'] }}</span>
</div>

{{-- ── SUMMARY ── --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="s-label">Total Revenue</div>
        <div class="s-value">৳{{ number_format($data['summary']['total_revenue']) }}</div>
        <div class="s-sub">All services</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Tour/Visa Bookings</div>
        <div class="s-value">{{ $data['summary']['total_bookings'] }}</div>
        <div class="s-sub">৳{{ number_format($data['summary']['bookings_revenue']) }}</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Hotel Bookings</div>
        <div class="s-value">{{ $data['summary']['hotel_bookings'] }}</div>
        <div class="s-sub">৳{{ number_format($data['summary']['hotel_revenue']) }}</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Transfers</div>
        <div class="s-value">{{ $data['summary']['transfer_bookings'] }}</div>
        <div class="s-sub">৳{{ number_format($data['summary']['transfer_revenue']) }}</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Visitors</div>
        <div class="s-value">{{ number_format($data['summary']['visitors']) }}</div>
        <div class="s-sub">Unique in period</div>
    </div>
</div>

{{-- ── TABLE ── --}}
<div class="section-title">{{ $data['title'] }}</div>

@if(count($data['rows']) > 0)
@php $totalAmount = 0; @endphp
<table>
    <thead>
        <tr>
            <th style="width:40px">ID</th>
            <th>Service</th>
            <th style="width:55px">Type</th>
            <th>Guest</th>
            <th style="width:82px">Date</th>
            <th style="width:64px; text-align:center">Status</th>
            <th style="width:78px; text-align:right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['rows'] as $row)
        @php $amt = preg_replace('/[^\d.]/', '', $row['amount']); if(is_numeric($amt)) $totalAmount += (float)$amt; @endphp
        <tr>
            <td>{{ $row['id'] }}</td>
            <td>{{ Str::limit($row['service'], 48) }}</td>
            <td>{{ $row['type'] }}</td>
            <td>
                {{ $row['guest'] }}<br>
                <span style="color:#9ca3af;font-size:7.5pt;">{{ $row['email'] }}</span>
            </td>
            <td>{{ $row['date'] }}</td>
            <td style="text-align:center">
                @php $sc = 's-' . strtolower($row['status']); @endphp
                <span class="badge {{ $sc }}">{{ $row['status'] }}</span>
            </td>
            <td class="amt">{{ $row['amount'] }}</td>
        </tr>
        @endforeach
        <tr class="totals-row">
            <td colspan="5" style="color:#fff;"><strong>TOTAL &nbsp;·&nbsp; {{ count($data['rows']) }} records</strong></td>
            <td style="color:#fca5a5; text-align:center">-</td>
            <td class="totals-amt">৳{{ number_format($totalAmount) }}</td>
        </tr>
    </tbody>
</table>
@else
<p style="text-align:center;color:#9ca3af;padding:20px;">No records in selected period.</p>
@endif

<div class="disclaimer">
    This report is for internal use only. Figures reflect booking records as of {{ now()->format('d M Y') }}. Revenue summary includes confirmed and completed bookings only.
</div>

{{-- ── SIGNATURE BLOCK ── --}}
<div class="footer-section">
    <div class="sig-grid">
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-label">Prepared by</div>
            <div class="sig-sub">Name &amp; Designation</div>
        </div>
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-label">Reviewed by</div>
            <div class="sig-sub">Name &amp; Designation</div>
        </div>
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-label">Authorized by</div>
            <div class="sig-sub">Director / Management</div>
        </div>
    </div>
</div>

<div class="page-footer">
    {{ $company['name'] }} &nbsp;·&nbsp; {{ $company['address'] }} &nbsp;·&nbsp; {{ $company['phone'] }} &nbsp;·&nbsp; {{ $company['email'] }} &nbsp;·&nbsp; {{ $company['web'] }}<br>
    Auto-generated by FlyoverBD admin system &nbsp;·&nbsp; {{ now()->format('d M Y') }}
</div>

<script>
    // Auto-trigger print dialog after short delay
    window.addEventListener('load', function() {
        setTimeout(function() {
            // Don't auto-print, let user click
        }, 300);
    });
</script>

</body>
</html>
