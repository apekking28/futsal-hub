<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $booking_code
 * @property int $field_id
 * @property string $customer_name
 * @property string $customer_phone
 * @property \Illuminate\Support\Carbon $booking_date
 * @property numeric $total_price
 * @property string $payment_method
 * @property string $payment_status
 * @property string $booking_status
 * @property string|null $proof_of_payment
 * @property string|null $rejection_reason
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookingDetail> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\Field $field
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereProofOfPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property string $start_time
 * @property string $end_time
 * @property numeric $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingDetail whereUpdatedAt($value)
 */
	class BookingDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gor> $gors
 * @property-read int|null $gors_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereUpdatedAt($value)
 */
	class District extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $gor_id
 * @property string $name
 * @property string $type
 * @property numeric $price_per_hour
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \App\Models\Gor $gor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field whereGorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field wherePricePerHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Field whereUpdatedAt($value)
 */
	class Field extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $district_id
 * @property string $name
 * @property string $slug
 * @property string $address
 * @property string $phone_number
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\District $district
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Field> $fields
 * @property-read int|null $fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gor whereUpdatedAt($value)
 */
	class Gor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $gor_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $phone_number
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Gor|null $gor
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $teams
 * @property-read int|null $teams_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTeam($teams)
 */
	class User extends \Eloquent {}
}

