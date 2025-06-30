<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelRequest extends Model
{
    public $guarded = [];

    protected $casts = [
        'departure_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterByPeriod($query, $departureDate = null, $returnDate = null)
    {
        if ($departureDate && $returnDate) {
            $query->where(function ($q) use ($departureDate, $returnDate) {
                $q->whereDate('departure_date', '<=', $returnDate)
                    ->whereDate('return_date', '>=', $departureDate);
            });
            return $query;
        }
        if ($departureDate) {
            $query->whereDate('departure_date', '>=', $departureDate);
        }
        if ($returnDate) {
            $query->whereDate('return_date', '<=', $returnDate);
        }
        return $query;
    }

    public function scopeFilterByDestination($query, $destination)
    {
        if ($destination) {
            $query->where('destination', 'like', '%' . $destination . '%');
        }
        return $query;
    }
}
