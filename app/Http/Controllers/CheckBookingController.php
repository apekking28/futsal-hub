<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckBookingController extends Controller
{
    public function index(Request $request)
    {
        $booking = null;

        if ($request->has('code') && !empty($request->code)) {
            $booking = Booking::with(['field.gor'])
                ->where('booking_code', trim($request->code))
                ->first();
        }

        return view('booking.check', compact('booking'));
    }

    public function uploadProof(Request $request, $id)
{
    $request->validate([
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ], [
        'payment_proof.required' => 'File bukti transfer wajib diunggah.',
        'payment_proof.image'    => 'Bukti transfer harus berupa gambar (JPG/PNG).',
        'payment_proof.max'      => 'Ukuran file maksimal 2MB.',
    ]);

    $booking = Booking::findOrFail($id);

    if ($request->hasFile('payment_proof')) {
        // Hapus foto lama jika ada
        if ($booking->proof_of_payment) {
            Storage::disk('public')->delete($booking->proof_of_payment);
        }

        // Store file & simpan ke kolom proof_of_payment
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $booking->update([
            'proof_of_payment' => $path,
            'payment_status'   => 'pending',
        ]);
    }

    return redirect()->route('booking.check', ['code' => $booking->booking_code])
        ->with('toast_success', 'Bukti transfer berhasil diunggah! Menunggu konfirmasi Admin GOR.');
}
}
