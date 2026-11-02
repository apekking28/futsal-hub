<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $startOfMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
        $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth();

        $query = Booking::query();

        if ($user->hasRole('admin-gor') && $user->gor_id) {
            $query->whereHas('field', function ($q) use ($user) {
                $q->where('gor_id', $user->gor_id);
            });
        }

        // 1. Stat Cards Data
        $monthlyRevenue = (clone $query)->where('payment_status', 'approved')
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->sum('total_price');

        $monthlyBookingsCount = (clone $query)->whereIn('booking_status', ['confirmed', 'completed', 'reserved'])
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->count();

        $pendingTransfersCount = (clone $query)->where('payment_method', 'transfer')
            ->where('payment_status', 'pending')
            ->count();

        // 2. Data Grafik 1: Tren Pendapatan 30 Hari Terakhir
        $thirtyDaysAgo = Carbon::now('Asia/Jakarta')->subDays(29)->startOfDay();
        $revenueData = (clone $query)->where('payment_status', 'approved')
            ->whereBetween('booking_date', [$thirtyDaysAgo, Carbon::now('Asia/Jakarta')->endOfDay()])
            ->selectRaw('DATE(booking_date) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartDates = [];
        $chartRevenues = [];
        for ($i = 29; $i >= 0; $i--) {
            $dateStr = Carbon::now('Asia/Jakarta')->subDays($i)->format('Y-m-d');
            $chartDates[] = Carbon::parse($dateStr)->format('d M');
            $chartRevenues[] = (float) ($revenueData[$dateStr] ?? 0);
        }

        // 3. Data Grafik 2: Jam Sibuk (Peak Hours)
        $peakHoursData = BookingDetail::whereHas('booking', function ($q) use ($user) {
            if ($user->hasRole('admin-gor') && $user->gor_id) {
                $q->whereHas('field', function ($f) use ($user) {
                    $f->where('gor_id', $user->gor_id);
                });
            }
            $q->where('booking_status', '!=', 'cancelled');
        })
        ->selectRaw('HOUR(start_time) as hour, COUNT(*) as count')
        ->groupBy('hour')
        ->pluck('count', 'hour');

        $chartHours = [];
        $chartHourCounts = [];
        for ($h = 8; $h <= 22; $h++) {
            $chartHours[] = sprintf('%02d:00', $h);
            $chartHourCounts[] = (int) ($peakHoursData[$h] ?? 0);
        }

        return view('admin.dashboard', compact(
            'monthlyRevenue',
            'monthlyBookingsCount',
            'pendingTransfersCount',
            'chartDates',
            'chartRevenues',
            'chartHours',
            'chartHourCounts'
        ));
    }
}
