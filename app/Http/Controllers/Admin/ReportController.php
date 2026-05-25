<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\TransferBooking;
use App\Models\Visitor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period    = $request->get('period', '30d');
        $type      = $request->get('type', 'sales');
        [$start, $end] = $this->dateRange($request);

        $summary = $this->buildSummary($start, $end);

        return view('admin.reports.index', compact('summary', 'period', 'type', 'start', 'end'));
    }

    public function pdf(Request $request)
    {
        [$start, $end] = $this->dateRange($request);
        $type = $request->get('type', 'sales');

        $data = $this->buildReportData($type, $start, $end);
        $company = $this->companyInfo();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('data', 'company', 'type', 'start', 'end'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $filename = 'flyoverbd-' . $type . '-report-' . $start->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function print(Request $request)
    {
        [$start, $end] = $this->dateRange($request);
        $type = $request->get('type', 'sales');

        $data    = $this->buildReportData($type, $start, $end);
        $company = $this->companyInfo();

        return view('admin.reports.print', compact('data', 'company', 'type', 'start', 'end'));
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function dateRange(Request $request): array
    {
        if ($request->filled('date_from') && $request->filled('date_to')) {
            return [Carbon::parse($request->date_from)->startOfDay(), Carbon::parse($request->date_to)->endOfDay()];
        }

        $now = now();
        return match($request->get('period', '30d')) {
            '7d'  => [$now->copy()->subDays(7)->startOfDay(), $now],
            '30d' => [$now->copy()->subDays(30)->startOfDay(), $now],
            '90d' => [$now->copy()->subDays(90)->startOfDay(), $now],
            '1y'  => [$now->copy()->subYear()->startOfDay(), $now],
            'all' => [Carbon::parse('2020-01-01'), $now],
            default => [$now->copy()->subDays(30)->startOfDay(), $now],
        };
    }

    private function buildSummary(Carbon $start, Carbon $end): array
    {
        $bookingsRevenue  = Booking::whereIn('status', ['confirmed', 'completed'])->whereBetween('created_at', [$start, $end])->sum('total_amount');
        $hotelRevenue     = HotelBooking::whereIn('status', ['confirmed', 'completed'])->whereBetween('created_at', [$start, $end])->sum('total_amount');
        $transferRevenue  = TransferBooking::whereIn('status', ['confirmed', 'completed'])->whereBetween('created_at', [$start, $end])->sum('total_amount');

        return [
            'total_revenue'      => $bookingsRevenue + $hotelRevenue + $transferRevenue,
            'bookings_revenue'   => $bookingsRevenue,
            'hotel_revenue'      => $hotelRevenue,
            'transfer_revenue'   => $transferRevenue,
            'total_bookings'     => Booking::whereBetween('created_at', [$start, $end])->count(),
            'hotel_bookings'     => HotelBooking::whereBetween('created_at', [$start, $end])->count(),
            'transfer_bookings'  => TransferBooking::whereBetween('created_at', [$start, $end])->count(),
            'visitors'           => Visitor::whereBetween('first_visit_at', [$start, $end])->count(),
        ];
    }

    private function buildReportData(string $type, Carbon $start, Carbon $end): array
    {
        $summary = $this->buildSummary($start, $end);

        $rows = match($type) {
            'tours-visas' => Booking::with(['user', 'payable'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get()
                ->map(fn($b) => [
                    'id'      => '#' . $b->id,
                    'service' => $b->payable?->title ?? $b->payable?->country ?? '-',
                    'type'    => class_basename($b->payable_type ?? ''),
                    'guest'   => $b->user?->name ?? $b->guest_name ?? '-',
                    'email'   => $b->user?->email ?? $b->guest_email ?? '-',
                    'date'    => $b->booking_date?->format('d M Y') ?? $b->created_at->format('d M Y'),
                    'status'  => ucfirst($b->status),
                    'amount'  => '৳' . number_format($b->total_amount),
                ])->toArray(),

            'hotels' => HotelBooking::with(['room.hotel', 'user'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get()
                ->map(fn($b) => [
                    'id'      => '#' . $b->id,
                    'service' => ($b->room?->hotel?->name ?? '-') . ' - ' . ($b->room?->name ?? '-'),
                    'type'    => 'Hotel',
                    'guest'   => $b->user?->name ?? $b->guest_name ?? '-',
                    'email'   => $b->user?->email ?? $b->guest_email ?? '-',
                    'date'    => $b->check_in?->format('d M Y') . ' → ' . $b->check_out?->format('d M Y'),
                    'status'  => ucfirst($b->status),
                    'amount'  => '৳' . number_format($b->total_amount),
                ])->toArray(),

            'transfers' => TransferBooking::with(['route', 'user'])
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get()
                ->map(fn($b) => [
                    'id'      => '#' . $b->id,
                    'service' => $b->pickup_location . ' → ' . $b->drop_location,
                    'type'    => $b->is_custom ? 'Custom' : 'Preset',
                    'guest'   => $b->user?->name ?? $b->guest_name ?? '-',
                    'email'   => $b->user?->email ?? $b->guest_email ?? '-',
                    'date'    => $b->travel_date?->format('d M Y'),
                    'status'  => ucfirst($b->status),
                    'amount'  => $b->total_amount > 0 ? '৳' . number_format($b->total_amount) : 'TBD',
                ])->toArray(),

            default => $this->allBookingsRows($start, $end),
        };

        $columns = ['ID', 'Service', 'Type', 'Guest', 'Email', 'Date', 'Status', 'Amount'];
        $title   = match($type) {
            'tours-visas' => 'Tours & Visa Bookings Report',
            'hotels'      => 'Hotel Bookings Report',
            'transfers'   => 'Transfer Bookings Report',
            default       => 'Sales Report - All Services',
        };

        return compact('summary', 'rows', 'columns', 'title');
    }

    private function allBookingsRows(Carbon $start, Carbon $end): array
    {
        $tours = Booking::with(['user', 'payable'])->whereBetween('created_at', [$start, $end])->latest()->get()
            ->map(fn($b) => [
                'id'      => '#' . $b->id,
                'service' => $b->payable?->title ?? $b->payable?->country ?? '-',
                'type'    => class_basename($b->payable_type ?? ''),
                'guest'   => $b->user?->name ?? $b->guest_name ?? '-',
                'email'   => $b->user?->email ?? $b->guest_email ?? '-',
                'date'    => $b->booking_date?->format('d M Y') ?? $b->created_at->format('d M Y'),
                'status'  => ucfirst($b->status),
                'amount'  => '৳' . number_format($b->total_amount),
            ]);

        $hotels = HotelBooking::with(['room.hotel', 'user'])->whereBetween('created_at', [$start, $end])->latest()->get()
            ->map(fn($b) => [
                'id'      => '#' . $b->id,
                'service' => ($b->room?->hotel?->name ?? '-') . ' - ' . ($b->room?->name ?? ''),
                'type'    => 'Hotel',
                'guest'   => $b->user?->name ?? $b->guest_name ?? '-',
                'email'   => $b->user?->email ?? $b->guest_email ?? '-',
                'date'    => $b->check_in?->format('d M Y'),
                'status'  => ucfirst($b->status),
                'amount'  => '৳' . number_format($b->total_amount),
            ]);

        $transfers = TransferBooking::with(['route', 'user'])->whereBetween('created_at', [$start, $end])->latest()->get()
            ->map(fn($b) => [
                'id'      => '#' . $b->id,
                'service' => $b->pickup_location . ' → ' . $b->drop_location,
                'type'    => 'Transfer',
                'guest'   => $b->user?->name ?? $b->guest_name ?? '-',
                'email'   => $b->user?->email ?? $b->guest_email ?? '-',
                'date'    => $b->travel_date?->format('d M Y'),
                'status'  => ucfirst($b->status),
                'amount'  => $b->total_amount > 0 ? '৳' . number_format($b->total_amount) : 'TBD',
            ]);

        return $tours->concat($hotels)->concat($transfers)->sortByDesc('id')->values()->toArray();
    }

    private function companyInfo(): array
    {
        return [
            'name'    => 'FlyoverBD',
            'tagline' => 'Your Trusted Travel Partner',
            'address' => 'Dhaka, Bangladesh',
            'phone'   => '+880 1234-567890',
            'email'   => 'info@flyoverbd.com',
            'web'     => 'www.flyoverbd.com',
            'logo'    => public_path('logo.png'),
        ];
    }
}
