<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>{{ $data['title'] }} - {{ $company['name'] }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 9pt; color: #18130E; background: #fff; }

    /* ── Header ── */
    .header { padding: 24px 28px 20px; border-bottom: 2px solid #C8102E; margin-bottom: 20px; }
    .header-inner { display: flex; justify-content: space-between; align-items: flex-start; }
    .logo-area { display: flex; align-items: center; gap: 12px; }
    .logo { height: 40px; }
    .company-name { font-size: 16pt; font-weight: 800; color: #18130E; line-height: 1; }
    .company-tagline { font-size: 8pt; color: #7A7166; margin-top: 2px; }
    .report-meta { text-align: right; }
    .report-title { font-size: 13pt; font-weight: 700; color: #C8102E; }
    .report-period { font-size: 8pt; color: #7A7166; margin-top: 3px; }
    .report-generated { font-size: 7.5pt; color: #9ca3af; margin-top: 2px; }

    /* ── Company info bar ── */
    .info-bar { background: #F9F6EF; border: 1px solid #E4DCC9; border-radius: 6px; padding: 10px 16px; margin-bottom: 20px;
                display: flex; justify-content: space-between; font-size: 8pt; color: #7A7166; }
    .info-bar span { margin-right: 16px; }
    .info-bar strong { color: #18130E; }

    /* ── Summary cards ── */
    .summary-grid { display: flex; gap: 10px; margin-bottom: 22px; }
    .summary-card { flex: 1; border: 1px solid #E4DCC9; border-radius: 6px; padding: 10px 12px; background: #FAFAFA; }
    .summary-card .label { font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #7A7166; }
    .summary-card .value { font-size: 14pt; font-weight: 800; color: #18130E; margin-top: 2px; line-height: 1; }
    .summary-card .sub { font-size: 7.5pt; color: #9ca3af; margin-top: 2px; }

    /* ── Table ── */
    .section-title { font-size: 10pt; font-weight: 700; color: #18130E; margin-bottom: 8px; padding-bottom: 5px; border-bottom: 1px solid #E4DCC9; }
    table { width: 100%; border-collapse: collapse; font-size: 8pt; }
    thead tr { background: #18130E; }
    thead th { padding: 7px 10px; text-align: left; color: #fff; font-weight: 700; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .04em; }
    tbody tr:nth-child(even) { background: #F9F6EF; }
    tbody tr:nth-child(odd)  { background: #fff; }
    tbody td { padding: 6px 10px; border-bottom: 1px solid #E4DCC9; vertical-align: top; }
    .amount-col { text-align: right; font-weight: 700; }
    .status-badge { display: inline-block; padding: 1px 7px; border-radius: 10px; font-size: 7pt; font-weight: 700; }
    .status-confirmed { background: #dcfce7; color: #166534; }
    .status-completed  { background: #dbeafe; color: #1e40af; }
    .status-pending    { background: #fef9c3; color: #854d0e; }
    .status-cancelled  { background: #fee2e2; color: #991b1b; }

    /* ── Totals row ── */
    .totals-row td { background: #18130E !important; color: #fff; font-weight: 700; padding: 8px 10px; }
    .totals-row .amount-col { color: #fca5a5; font-size: 10pt; }

    /* ── Footer / Signature ── */
    .footer-section { margin-top: 36px; border-top: 1px solid #E4DCC9; padding-top: 20px; }
    .signature-grid { display: flex; justify-content: space-between; gap: 20px; }
    .signature-box { flex: 1; }
    .signature-line { border-top: 1px solid #9CA3AF; margin-top: 40px; margin-bottom: 6px; }
    .signature-label { font-size: 8pt; color: #7A7166; font-weight: 600; }
    .signature-sub { font-size: 7.5pt; color: #9ca3af; }
    .page-footer { margin-top: 24px; text-align: center; font-size: 7.5pt; color: #9ca3af; border-top: 1px solid #F3F0EA; padding-top: 10px; }
    .disclaimer { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 4px; padding: 8px 12px; margin-top: 16px; font-size: 7.5pt; color: #92400e; }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="header">
    <div class="header-inner">
        <div class="logo-area">
            @if(file_exists($company['logo']))
            <img src="{{ $company['logo'] }}" class="logo" alt="{{ $company['name'] }}">
            @endif
            <div>
                <div class="company-name">{{ $company['name'] }}</div>
                <div class="company-tagline">{{ $company['tagline'] }}</div>
            </div>
        </div>
        <div class="report-meta">
            <div class="report-title">{{ $data['title'] }}</div>
            <div class="report-period">Period: {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</div>
            <div class="report-generated">Generated: {{ now()->format('d M Y, h:i A') }}</div>
        </div>
    </div>
</div>

{{-- ══ COMPANY INFO BAR ══ --}}
<div class="info-bar">
    <span><strong>Address:</strong> {{ $company['address'] }}</span>
    <span><strong>Phone:</strong> {{ $company['phone'] }}</span>
    <span><strong>Email:</strong> {{ $company['email'] }}</span>
    <span><strong>Web:</strong> {{ $company['web'] }}</span>
</div>

{{-- ══ SUMMARY CARDS ══ --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="label">Total Revenue</div>
        <div class="value">৳{{ number_format($data['summary']['total_revenue']) }}</div>
        <div class="sub">All services</div>
    </div>
    <div class="summary-card">
        <div class="label">Tours & Visas</div>
        <div class="value">{{ $data['summary']['total_bookings'] }}</div>
        <div class="sub">৳{{ number_format($data['summary']['bookings_revenue']) }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Hotel Bookings</div>
        <div class="value">{{ $data['summary']['hotel_bookings'] }}</div>
        <div class="sub">৳{{ number_format($data['summary']['hotel_revenue']) }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Transfers</div>
        <div class="value">{{ $data['summary']['transfer_bookings'] }}</div>
        <div class="sub">৳{{ number_format($data['summary']['transfer_revenue']) }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Unique Visitors</div>
        <div class="value">{{ number_format($data['summary']['visitors']) }}</div>
        <div class="sub">In period</div>
    </div>
</div>

{{-- ══ DATA TABLE ══ --}}
<div class="section-title">Booking Details - {{ $data['title'] }}</div>

@if(count($data['rows']) > 0)
<table>
    <thead>
        <tr>
            <th style="width:42px">ID</th>
            <th>Service</th>
            <th style="width:55px">Type</th>
            <th>Guest</th>
            <th style="width:80px">Date</th>
            <th style="width:60px; text-align:center">Status</th>
            <th style="width:75px; text-align:right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php $totalAmount = 0; @endphp
        @foreach($data['rows'] as $row)
        @php
            $amt = preg_replace('/[^\d.]/', '', $row['amount']);
            if(is_numeric($amt)) $totalAmount += (float)$amt;
        @endphp
        <tr>
            <td>{{ $row['id'] }}</td>
            <td>{{ Str::limit($row['service'], 45) }}</td>
            <td>{{ $row['type'] }}</td>
            <td>
                {{ $row['guest'] }}<br>
                <span style="color:#9ca3af;font-size:7pt;">{{ $row['email'] }}</span>
            </td>
            <td>{{ $row['date'] }}</td>
            <td style="text-align:center">
                @php $sc = match(strtolower($row['status'])) {
                    'confirmed' => 'status-confirmed',
                    'completed' => 'status-completed',
                    'cancelled' => 'status-cancelled',
                    default     => 'status-pending',
                }; @endphp
                <span class="status-badge {{ $sc }}">{{ $row['status'] }}</span>
            </td>
            <td class="amount-col">{{ $row['amount'] }}</td>
        </tr>
        @endforeach
        <tr class="totals-row">
            <td colspan="5"><strong>TOTAL ({{ count($data['rows']) }} records)</strong></td>
            <td style="text-align:center; color:#fca5a5;">-</td>
            <td class="amount-col">৳{{ number_format($totalAmount) }}</td>
        </tr>
    </tbody>
</table>
@else
<p style="color:#9ca3af; text-align:center; padding:24px 0;">No records found for the selected period.</p>
@endif

{{-- ══ DISCLAIMER ══ --}}
<div class="disclaimer">
    This report is for internal use only. All figures are based on booking records as of {{ now()->format('d M Y') }}. Revenue reflects confirmed and completed bookings only in summary cards; table includes all statuses.
</div>

{{-- ══ SIGNATURE BLOCK ══ --}}
<div class="footer-section">
    <div class="signature-grid">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Prepared by</div>
            <div class="signature-sub">Name &amp; Designation</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Reviewed by</div>
            <div class="signature-sub">Name &amp; Designation</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Authorized by</div>
            <div class="signature-sub">Director / Management</div>
        </div>
    </div>
</div>

{{-- ══ PAGE FOOTER ══ --}}
<div class="page-footer">
    {{ $company['name'] }} · {{ $company['address'] }} · {{ $company['phone'] }} · {{ $company['email'] }} · {{ $company['web'] }}<br>
    This document was generated automatically by FlyoverBD admin system.
</div>

</body>
</html>
