<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_status',
        'payment_status',
        'requested_at',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function receipts()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    protected static function booted(): void
    {
        static::saving(function (Enrollment $enrollment) {
            if (
                $enrollment->payment_status === 'paid' &&
                in_array($enrollment->enrollment_status, ['rejected', 'cancelled'])
            ) {
                throw new \InvalidArgumentException(
                    'No se puede marcar un pago como pagado en una inscripción rechazada o cancelada.'
                );
            }
        });
    }
}
