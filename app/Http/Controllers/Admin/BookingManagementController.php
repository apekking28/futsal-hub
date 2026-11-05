<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingManagementController extends Controller
{
    /**
     * Daftar Transaksi Booking (Filter by Status & Pagination)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Booking::with(['field.gor', 'details']);

        // Scope jika login sebagai Admin GOR
        if ($user->hasRole('admin-gor') && $user->gor_id) {
            $query->whereHas('field', function ($q) use ($user) {
                $q->where('gor_id', $user->gor_id);
            });
        }

        // Filter Pencarian (Kode Booking / Nama / No HP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Filter Status Bayar
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Pagination 10 item per halaman + membawa query string filter
        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Quick Check-In / Redemption di Lokasi oleh Kasir
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'booking_code' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $code = strtoupper(trim($request->booking_code));

        $booking = Booking::with('field')
            ->where('booking_code', $code)
            ->first();

        if (! $booking) {
            return back()->with('toast_error', "Kode Booking {$code} tidak ditemukan!");
        }

        // Cek apakah kasir berhak atas GOR transaksi ini
        if (! $user->hasRole('super-admin') && $user->gor_id !== $booking->field->gor_id) {
            return back()->with('toast_error', 'Kode Booking ini bukan milik GOR Anda.');
        }

        if ($booking->booking_status === 'completed') {
            return back()->with('toast_info', "Booking {$code} sudah selesai/check-in sebelumnya.");
        }

        if ($booking->booking_status === 'cancelled') {
            return back()->with('toast_error', "Booking {$code} telah dibatalkan.");
        }

        // Eksekusi Check-In & Pelunasan
        $booking->update([
            'payment_status' => 'approved',
            'booking_status' => 'completed',
        ]);

        return back()->with('toast_success', "Check-In berhasil! Kode {$code} status: SELESAI & LUNAS.");
    }

    /**
     * Verifikasi Transfer: Approve
     */
    public function approvePayment($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'payment_status' => 'approved',
            'booking_status' => 'confirmed',
        ]);

        return back()->with('toast_success', "Pembayaran untuk Kode {$booking->booking_code} disetujui.");
    }

    /**
     * Verifikasi Transfer: Reject (Wajib Alasan)
     */
    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ], [
            'rejection_reason.required' => 'Wajib mengisi alasan penolakan pembayaran.',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'payment_status' => 'rejected',
            'booking_status' => 'cancelled',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('toast_success', "Pembayaran Kode {$booking->booking_code} ditolak. Slot jam dibebaskan.");
    }

    /**
     * Cancel Booking Manual oleh Admin (Wajib Alasan)
     */
    public function cancelBooking(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:255'],
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'payment_status' => 'cancelled',
            'booking_status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return back()->with('toast_success', "Booking {$booking->booking_code} berhasil dibatalkan.");
    }

    /**
     * Re-Update Status Booking & Pembayaran jika Terjadi Kesalahan Input
     */
   public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status'      => 'required|in:pending,approved,rejected,cancelled',
            'booking_status'      => 'required|in:reserved,confirmed,completed,cancelled',
            'rejection_reason'    => 'nullable|required_if:payment_status,rejected|string|max:255',
            'cancellation_reason' => 'nullable|required_if:booking_status,cancelled|required_if:payment_status,cancelled|string|max:255',
        ], [
            'rejection_reason.required_if'    => 'Wajib mengisi alasan penolakan pembayaran.',
            'cancellation_reason.required_if' => 'Wajib mengisi alasan pembatalan booking.',
        ]);

        $booking = Booking::findOrFail($id);

        $updateData = [
            'payment_status' => $request->payment_status,
            'booking_status' => $request->booking_status,
        ];

        if ($request->payment_status === 'rejected') {
            $updateData['rejection_reason'] = $request->rejection_reason;
        }

        if ($request->booking_status === 'cancelled' || $request->payment_status === 'cancelled') {
            $updateData['cancellation_reason'] = $request->cancellation_reason;
        }

        $booking->update($updateData);

        return back()->with('toast_success', 'Status transaksi ' . $booking->booking_code . ' berhasil dikoreksi!');
    }
}
