<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\District;
use App\Models\Field;
use App\Models\Gor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Halaman Utama Booking Publik (Support AJAX No-Reload)
     */
    public function index(Request $request)
    {
        $districts = District::orderBy('name')->get();

        $gors = Gor::where('is_active', true)
            ->when($request->district_id, fn($q) => $q->where('district_id', $request->district_id))
            ->orderBy('name')
            ->get();

        $fieldsQuery = Field::with(['gor.district', 'images'])->where('is_active', true);

        if ($request->filled('district_id')) {
            $fieldsQuery->whereHas('gor', function ($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        if ($request->filled('gor_id')) {
            $fieldsQuery->where('gor_id', $request->gor_id);
        }

        if ($request->filled('type')) {
            $fieldsQuery->where('type', $request->type);
        }

        // Tampilkan 6 item per halaman menggunakan pagination standar bawaan Laravel
        $fields = $fieldsQuery->paginate(6)->withQueryString();

        $selectedFieldId = $request->get('field_id');
        $selectedDate = $request->get('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'));

        // JIKA AJAX REQUEST: Kembalikan partial view komponen kartu lapangan saja (tanpa reload)
        if ($request->ajax()) {
            return view('components.field-card-list', compact('fields', 'districts', 'gors', 'selectedFieldId'))->render();
        }

        return view('booking.index', compact('fields', 'districts', 'gors', 'selectedFieldId', 'selectedDate'));
    }

    /**
     * API JSON untuk Ambil Status Slot Jam Real-Time
     */
    public function getSlots(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
        ]);

        $fieldId = $request->field_id;
        $date = $request->date;
        $now = Carbon::now('Asia/Jakarta');
        $isToday = $date === $now->format('Y-m-d');
        $currentHour = (int) $now->format('H');

        // Slot jam operasional (08:00 - 22:00)
        $allSlots = [
            '08:00',
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00',
            '20:00',
            '21:00',
            '22:00',
        ];

        // Ambil jam yang sudah dibooking (yang tidak cancelled)
        $bookedSlots = BookingDetail::whereHas('booking', function ($q) use ($fieldId, $date) {
            $q->where('field_id', $fieldId)
                ->whereDate('booking_date', $date)
                ->where('booking_status', '!=', 'cancelled');
        })->pluck('start_time')->map(function ($time) {
            return substr($time, 0, 5); // Ambil format HH:mm
        })->toArray();

        $slotStatuses = [];
        foreach ($allSlots as $slot) {
            $slotHour = (int) explode(':', $slot)[0];

            $isBooked = in_array($slot, $bookedSlots);
            $isPast = $isToday && ($slotHour <= $currentHour);

            $slotStatuses[] = [
                'time' => $slot,
                'is_booked' => $isBooked,
                'is_past' => $isPast,
                'available' => ! $isBooked && ! $isPast,
            ];
        }

        return response()->json([
            'success' => true,
            'slots' => $slotStatuses,
        ]);
    }

    /**
     * Store Booking Baru (Anonim)
     */
    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'slots' => 'required|array|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:transfer,cash',
        ], [
            'slots.required' => 'Pilih minimal satu slot jam bermain!',
        ]);

        try {
            $booking = DB::transaction(function () use ($request) {
                // Locking & Anti Double-Booking Check
                $existingBookings = BookingDetail::whereHas('booking', function ($q) use ($request) {
                    $q->where('field_id', $request->field_id)
                        ->where('booking_date', $request->booking_date)
                        ->whereIn('booking_status', ['reserved', 'confirmed', 'completed']);
                })
                    ->whereIn('start_time', $request->slots)
                    ->lockForUpdate()
                    ->exists();

                if ($existingBookings) {
                    throw new \Exception('Salah satu slot jam yang Anda pilih baru saja dibooking orang lain.');
                }

                $field = Field::findOrFail($request->field_id);
                $totalPrice = count($request->slots) * $field->price_per_hour;

                // Generate Booking
                $booking = Booking::create([
                    'booking_code' => 'FTS-'.strtoupper(Str::random(6)),
                    'field_id' => $request->field_id,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'booking_date' => $request->booking_date,
                    'total_price' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'booking_status' => 'reserved',
                ]);

                // Create Details
                foreach ($request->slots as $slot) {
                    $startTime = $slot.':00';
                    $endTime = sprintf('%02d:00', ((int) substr($slot, 0, 2)) + 1);

                    BookingDetail::create([
                        'booking_id' => $booking->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'price' => $field->price_per_hour,
                    ]);
                }

                return $booking;
            });

            return redirect()->route('booking.success', $booking->booking_code)
                ->with('toast_success', 'Booking berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withInput()->with('toast_error', $e->getMessage());
        }
    }

    /**
     * Halaman Struk Sukses Booking
     */
    public function success($code)
    {
        $booking = Booking::with(['field.gor.district', 'details'])
            ->where('booking_code', $code)
            ->firstOrFail();

        return view('booking.success', compact('booking'));
    }
}
