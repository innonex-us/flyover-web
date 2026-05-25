<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $data['title'] }} - {{ $company['name'] }}</title>
<style>
    @media print {
        @page { size: A4 portrait; margin: 0mm; }
        .no-print { display: none !important; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; margin: 0; padding: 0; }
        .document-wrapper { box-shadow: none !important; margin: 0 !important; width: 100% !important; min-height: 100vh; }
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: "Garamond", "Georgia", "Times New Roman", serif; 
        font-size: 10pt; 
        color: #1a1a1a; 
        background: #f4f4f4; 
        line-height: 1.4;
    }

    /* ── Print Control Bar ── */
    .print-bar { 
        background: #111827; 
        color: #fff; 
        padding: 12px 30px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        font-family: sans-serif;
    }
    .print-btn { background: #dc2626; color: #fff; border: none; padding: 8px 24px; border-radius: 4px; font-size: 13px; font-weight: 700; cursor: pointer; }
    .close-btn { background: transparent; color: #fff; border: 1px solid #374151; padding: 8px 16px; border-radius: 4px; font-size: 13px; cursor: pointer; margin-left: 8px; }

    /* ── Document Wrapper (The Paper) ── */
    .document-wrapper {
        background: #fff;
        width: 210mm;
        min-height: 297mm;
        margin: 60px auto;
        padding: 20mm;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    /* ── Watermark Background Style (Document Pad feel) ── */
    .document-wrapper::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background: #dc2626;
    }

    /* ── Header ── */
    .header { 
        display: flex; 
        justify-content: space-between; 
        align-items: flex-start;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    .logo-section { display: flex; align-items: center; gap: 15px; }
    .logo { height: 50px; filter: grayscale(100%); opacity: 0.9; }
    .brand-name { 
        font-family: sans-serif;
        font-size: 20pt; 
        font-weight: 900; 
        color: #111827; 
        letter-spacing: -0.5px;
        text-transform: uppercase;
    }
    .brand-tagline { 
        font-family: sans-serif;
        font-size: 8pt; 
        color: #6b7280; 
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: -4px;
    }

    .meta-section { text-align: right; font-family: sans-serif; }
    .doc-type { font-size: 14pt; font-weight: 800; color: #dc2626; text-transform: uppercase; margin-bottom: 4px; }
    .doc-id { font-size: 9pt; color: #9ca3af; font-weight: 600; }
    .doc-date { font-size: 9pt; color: #374151; margin-top: 4px; }

    /* ── Address Block ── */
    .address-grid {
        display: grid;
        grid-template-cols: 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
        font-size: 9pt;
    }
    .address-box h4 { 
        font-family: sans-serif;
        text-transform: uppercase; 
        font-size: 8pt; 
        color: #9ca3af; 
        margin-bottom: 8px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 4px;
    }
    .address-content { color: #4b5563; }

    /* ── Summary Stats ── */
    .stats-table {
        width: 100%;
        margin-bottom: 40px;
        border-collapse: collapse;
    }
    .stats-table td {
        padding: 15px;
        border: 1px solid #f3f4f6;
        width: 20%;
        text-align: center;
    }
    .stat-label { font-family: sans-serif; font-size: 7pt; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 5px; }
    .stat-value { font-size: 14pt; font-weight: 700; color: #111827; }

    /* ── Main Data Table ── */
    .main-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
    .main-table th { 
        font-family: sans-serif;
        font-size: 8pt; 
        font-weight: 700; 
        text-transform: uppercase; 
        color: #4b5563; 
        border-bottom: 2px solid #111827;
        padding: 10px 8px;
        text-align: left;
    }
    .main-table td { 
        padding: 10px 8px; 
        border-bottom: 1px solid #f3f4f6; 
        vertical-align: top;
        font-size: 9pt;
    }
    .main-table tr:nth-child(even) { background-color: #f9fafb; }
    .main-table .ref-col { font-family: sans-serif; font-weight: 700; font-size: 8pt; }
    .main-table .amount-col { text-align: right; font-weight: 700; }
    
    .status-pill {
        display: inline-block;
        padding: 2px 8px;
        font-family: sans-serif;
        font-size: 7pt;
        font-weight: 800;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid #e5e7eb;
    }

    .total-row { background: #111827 !important; color: #fff; }
    .total-row td { border: none; font-weight: 700; padding: 12px 8px; font-size: 10pt; }

    /* ── Approval Section ── */
    .approval-section {
        margin-top: 60px;
        display: grid;
        grid-template-cols: repeat(3, 1fr);
        gap: 30px;
    }
    .signature-box { text-align: center; }
    .signature-line { 
        border-top: 1px solid #ccc; 
        width: 80%; 
        margin: 40px auto 10px;
    }
    .signature-label { font-family: sans-serif; font-size: 8pt; font-weight: 700; color: #666; text-transform: uppercase; }

    /* ── Footer ── */
    .footer {
        position: absolute;
        bottom: 15mm;
        left: 20mm;
        right: 20mm;
        border-top: 1px solid #eee;
        padding-top: 10px;
        display: flex;
        justify-content: space-between;
        font-family: sans-serif;
        font-size: 7.5pt;
        color: #999;
    }
</style>
</head>
<body>

<div class="print-bar no-print">
    <span>OFFICIAL DOCUMENT &nbsp;·&nbsp; {{ $data['title'] }}</span>
    <div>
        <button class="print-btn" onclick="window.print()">PRINT DOCUMENT</button>
        <button class="close-btn" onclick="window.close()">CLOSE</button>
    </div>
</div>

<div class="document-wrapper">
    {{-- Header --}}
    <div class="header">
        <div class="logo-section">
            <img src="{{ asset('logo.png') }}" class="logo" alt="Logo">
            <div>
                <div class="brand-name">{{ $company['name'] }}</div>
                <div class="brand-tagline">{{ $company['tagline'] }}</div>
            </div>
        </div>
        <div class="meta-section">
            <div class="doc-type">Report Summary</div>
            <div class="doc-id">REF: {{ strtoupper(Str::random(8)) }}</div>
            <div class="doc-date">DATE: {{ now()->format('d M, Y') }}</div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="address-grid">
        <div class="address-box">
            <h4>Organization Info</h4>
            <div class="address-content">
                {{ $company['name'] }}<br>
                {{ $company['address'] }}<br>
                {{ $company['phone'] }}<br>
                {{ $company['email'] }}
            </div>
        </div>
        <div class="address-box">
            <h4>Report Parameters</h4>
            <div class="address-content">
                <strong>Subject:</strong> {{ $data['title'] }}<br>
                <strong>Timeframe:</strong> {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}<br>
                <strong>Status:</strong> All Confirmed Transactions
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <table class="stats-table">
        <tr>
            <td>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">৳{{ number_format($data['summary']['total_revenue']) }}</div>
            </td>
            <td>
                <div class="stat-label">Tours/Visas</div>
                <div class="stat-value">{{ $data['summary']['total_bookings'] }}</div>
            </td>
            <td>
                <div class="stat-label">Hotels</div>
                <div class="stat-value">{{ $data['summary']['hotel_bookings'] }}</div>
            </td>
            <td>
                <div class="stat-label">Transfers</div>
                <div class="stat-value">{{ $data['summary']['transfer_bookings'] }}</div>
            </td>
            <td>
                <div class="stat-label">Unique Traffic</div>
                <div class="stat-value">{{ number_format($data['summary']['visitors']) }}</div>
            </td>
        </tr>
    </table>

    {{-- Main Table --}}
    <table class="main-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Description</th>
                <th>Client Name</th>
                <th>Schedule</th>
                <th style="text-align:center">Status</th>
                <th style="text-align:right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse($data['rows'] as $row)
                @php $amt = preg_replace('/[^\d.]/', '', $row['amount']); if(is_numeric($amt)) $totalAmount += (float)$amt; @endphp
                <tr>
                    <td class="ref-col">{{ $row['id'] }}</td>
                    <td>
                        {{ Str::limit($row['service'], 40) }}<br>
                        <small style="color:#999">{{ $row['type'] }}</small>
                    </td>
                    <td>{{ $row['guest'] }}</td>
                    <td>{{ $row['date'] }}</td>
                    <td style="text-align:center">
                        <span class="status-pill">{{ $row['status'] }}</span>
                    </td>
                    <td class="amount-col">{{ $row['amount'] }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center; padding: 40px; color: #999;">No transaction records found for the selected period.</td></tr>
            @endforelse
            
            @if(count($data['rows']) > 0)
            <tr class="total-row">
                <td colspan="5" style="text-align:right">TOTAL CONSOLIDATED REVENUE:</td>
                <td style="text-align:right">৳{{ number_format($totalAmount) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- Approval Section --}}
    <div class="approval-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Prepared By</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Verified By</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Authorized Signature</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>Generated by FlyoverBD Internal Management System</div>
        <div>Page 1 of 1</div>
        <div>Security Hash: {{ hash('crc32', now()) }}</div>
    </div>
</div>

</body>
</html>
